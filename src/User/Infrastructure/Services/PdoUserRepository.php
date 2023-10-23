<?php

namespace Code237\Nkap\User\Infrastructure\Services;

use Code237\Nkap\Shared\Infrastructure\Lib\MySQLPdoConnection;
use Code237\Nkap\Shared\Lib\PdoConnection;
use Code237\Nkap\Shared\VO\EmailVo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Repository\UserRepository;

class PdoUserRepository implements UserRepository
{
    public function __construct(
        private ?PdoConnection $pdoConnection = null,
    )
    {
        $this->pdoConnection = new MySQLPdoConnection();
    }

    public function save(User $user): true
    {
        $userId = $user->id()->value();
        $userEmail = $user->email()->value();
        $userName = $user->name()->value();
        $userSurName = $user->surName()->value();

        $sql = " INSERT INTO users (uuid, name, surname, email) 
                 VALUES (:uuid, :name, :surname, :email)";

        $connection = $this->pdoConnection->getPdo()->prepare($sql);
        $connection->bindParam('uuid', $userId);
        $connection->bindParam('name', $userName);
        $connection->bindParam('surname', $userSurName);
        $connection->bindParam('email', $userEmail);
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
            id: new Id($result->uuid)
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