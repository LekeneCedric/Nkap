<?php

namespace Code237\Nkap\User\tests\Feature;

use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\User\Application\Command\Save\SaveUserCommand;
use Code237\Nkap\User\Application\Command\Save\SaveUserHandler;
use Code237\Nkap\User\Application\Command\Save\SaveUserResponse;
use Code237\Nkap\User\Infrastructure\Services\PdoUserRepository;
use Code237\Nkap\User\Repository\UserRepository;
use Code237\Nkap\User\tests\Unit\CommandBuilder\SaveUserCommandBuilder;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PdoUserRepository();
    }

    public function test_can_save_user()
    {
        $name = "Lekene";
        $surname = "Cedric";
        $email = "lekene@gmail.com";
        $password = "hackerMan";

        $command = SaveUserCommandBuilder::asSUT()
            ->withName($name)
            ->withSurname($surname)
            ->withEmail($email)
            ->withPassword($password)
            ->build();

        $response = $this->saveUser($command);

        $this->assertTrue($response->isSaved);
    }

    public function test_can_get_user_by_id()
    {
        $name = "Lekene";
        $surname = "Cedric";
        $email = "lekene@gmail.com";
        $password = "hackerMan";

        $command = SaveUserCommandBuilder::asSUT()
            ->withName($name)
            ->withSurname($surname)
            ->withEmail($email)
            ->withPassword($password)
            ->build();

        $response = $this->saveUser($command);

        $createdUser = $this->repository->byId(new Id($response->userId));

        $this->assertTrue($response->isSaved);
        $this->assertEquals($name, $createdUser->name()->value());
        $this->assertEquals($surname, $createdUser->surName()->value());
        $this->assertNotNull($createdUser);
    }

    public function test_can_delete_user()
    {
        $name = "Lekene";
        $surname = "Cedric";
        $email = "lekene@gmail.com";
        $password = "hackerMan";

        $command = SaveUserCommandBuilder::asSUT()
            ->withName($name)
            ->withSurname($surname)
            ->withEmail($email)
            ->withPassword($password)
            ->build();

        $createUserResponse = $this->saveUser($command);

        $this->repository->deleteById(new Id($createUserResponse->userId));

        $deletedUser = $this->repository->byId(new Id($createUserResponse->userId));

        $this->assertNull($deletedUser);
    }

    /**
     * @param SaveUserCommand $command
     * @return SaveUserResponse
     */
    private function saveUser(SaveUserCommand $command): SaveUserResponse
    {
        $handler = new SaveUserHandler(
            $this->repository,
        );

        return $handler->handle($command);
    }
}