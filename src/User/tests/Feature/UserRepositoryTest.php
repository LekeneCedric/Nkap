<?php

namespace Code237\Nkap\User\tests\Feature;

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

        $command = SaveUserCommandBuilder::asSUT()
            ->withName($name)
            ->withSurname($surname)
            ->withEmail($email)
            ->build();

        $response = $this->saveUser($command);

        $this->assertTrue($response->isSaved);
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