<?php

namespace Code237\Nkap\User\Infrastructure\Repositories;

use Code237\Nkap\Shared\Infrastructure\Lib\MySQLPdoConnection;
use Code237\Nkap\Shared\Lib\PdoConnection;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\EmailVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\PasswordVO;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Domain\UserRepository;
use Exception;

readonly class PdoUserRepository implements UserRepository
{
    public function __construct(
        private PdoConnection $pdoConnection,
    )
    {
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

        $statement = $this->pdoConnection->getPdo()->prepare($sql);
        $statement->bindParam('uuid', $userId);
        $statement->bindParam('name', $userName);
        $statement->bindParam('surname', $userSurName);
        $statement->bindParam('email', $userEmail);
        $statement->bindParam('password', $userPassword);
        $statement->bindParam('updated_at', $userUpdatedAt);
        $statement->bindParam('created_at', $userCreatedAt);

        $statement->execute();

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
            email: new EmailVO($result->email),
            password: new PasswordVO(hashedPassword: $result->password),
            id: new Id($result->uuid),
            createdAt: new DateVO($result->created_at),
            updatedAt: new DateVO($result->updated_at),
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