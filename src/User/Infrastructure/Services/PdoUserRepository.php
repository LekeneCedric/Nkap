<?php

namespace Code237\Nkap\User\Infrastructure\Services;

use Code237\Nkap\Shared\Infrastructure\Lib\MySQLPdoConnection;
use Code237\Nkap\Shared\Lib\PdoConnection;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\EmailVo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\PasswordVo;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Repository\UserRepository;
use Exception;

class PdoUserRepository implements UserRepository
{
    public function __construct(
        private ?PdoConnection $pdoConnection = null,
    )
    {
        $this->pdoConnection = new MySQLPdoConnection();
    }

    /**
     * @throws Exception
     */
    public function save(User $user): true
    {
        $userId = $user->id()->value();
        $userEmail = $user->email()->value();
        $userName = $user->name()->value();
        $userSurName = $user->surName()->value();
        $userPassword = $user->password()->hashedPasswordValue();
        $userUpdatedAt = $user->updatedAt()->formatYMDHIS();
        $userCreatedAt = $user->createdAt()->formatYMDHIS();

        $sql = " INSERT INTO users (uuid, name, surname, email, password, updated_at, created_at) 
                 VALUES (:uuid, :name, :surname, :email, :password, :updated_at, :created_at)";

        $connection = $this->pdoConnection->getPdo()->prepare($sql);
        $connection->bindParam('uuid', $userId);
        $connection->bindParam('name', $userName);
        $connection->bindParam('surname', $userSurName);
        $connection->bindParam('email', $userEmail);
        $connection->bindParam('password', $userPassword);
        $connection->bindParam('updated_at', $userUpdatedAt);
        $connection->bindParam('created_at', $userCreatedAt);

        $connection->execute();

        return true;
    }

    public function byId(Id $userId): ?User
    {
        $userIdValue = $userId->value();

        $sql = "
            SELECT * FROM users
            WHERE users.uuid = :userId
        ";

        $connection = $this->pdoConnection->getPdo()->prepare($sql);
        $connection->bindParam('userId', $userIdValue);
        $connection->setFetchMode(\PDO::FETCH_OBJ);
        $connection->execute();
        $result = $connection->fetch();

        if (!$result) {
            return null;
        }

        return User::create(
            name: new StringVO($result->name),
            surname: new StringVO($result->surname),
            email: new EmailVo($result->email),
            password: new PasswordVo(hashedPassword: $result->password),
            id: new Id($result->uuid),
            createdAt: new Datevo($result->created_at),
            updatedAt: new Datevo($result->updated_at),
        );
    }

    public function deleteById(Id $userId): true
    {
        $userIdValues = $userId->value();

        $sql = " DELETE FROM users WHERE uuid = :userId";

        $connection = $this->pdoConnection->getPdo()->prepare($sql);
        $connection->bindParam('userId', $userIdValues);
        $connection->execute();

        return true;
    }
}