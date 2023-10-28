<?php

namespace Code237\Nkap\User\tests\Unit;

use Code237\Nkap\User\Application\Command\Save\SaveUserCommand;
use Code237\Nkap\User\Application\Command\Save\SaveUserHandler;
use Code237\Nkap\User\Application\Command\Save\SaveUserResponse;
use Code237\Nkap\User\Domain\UserRepository;
use Code237\Nkap\User\tests\Unit\CommandBuilder\SaveUserCommandBuilder;
use Code237\Nkap\User\tests\Unit\Repository\InMemoryUserRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SaveUserTest extends TestCase
{
    private UserRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryUserRepository();
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
        $this->assertNotEmpty($this->repository->users[$response->userId]);
    }

    public function test_can_throw_exception_if_user_email_is_invalid()
    {
        $name = "Lekene";
        $surname = "Cedric";
        $email = "lekenegmail.com";
        $password = "hackerMan";

        $command = SaveUserCommandBuilder::asSUT()
            ->withName($name)
            ->withSurname($surname)
            ->withEmail($email)
            ->withPassword($password)
            ->build();

        $this->expectException(InvalidArgumentException::class);

        $this->saveUser($command);
    }

    private function saveUser(SaveUserCommand $command): SaveUserResponse
    {
        $handler = new SaveUserHandler(
            $this->repository,
        );

        return $handler->handle($command);
    }
}