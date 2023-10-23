<?php

namespace Code237\Nkap\User\tests\Unit;

use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\User\Application\Command\Delete\DeleteUserHandler;
use Code237\Nkap\User\Application\Command\Delete\DeleteUserResponse;
use Code237\Nkap\User\Domain\Exception\UserAlreadyExistException;
use Code237\Nkap\User\Repository\UserRepository;
use Code237\Nkap\User\tests\Unit\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

class DeleteUserTest extends TestCase
{
    private UserRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryUserRepository();
    }

    /**
     * @throws UserAlreadyExistException
     */
    public function test_can_delete_user()
    {
        $name = "Lekene";
        $surname = "Cedric";
        $email = "lekene@gmail.com";

        $initSUT = UserSUT::asSUT()
                        ->withName($name)
                        ->withSurname($surname)
                        ->withEmail($email)
                        ->build();
        $savedUser = $initSUT->user;

        $this->repository->save($savedUser);

        $this->assertArrayHasKey($savedUser->id()->value(), $this->repository->users);

        $response = $this->deleteUser($savedUser->id());

        $this->assertTrue($response->isDeleted);
        $this->assertArrayNotHasKey($savedUser->id()->value(), $this->repository->users);
    }

    /**
     * @throws UserAlreadyExistException
     */
    public function test_can_throw_user_not_exist_exception_if_user_not_exist(): void
    {
        $name = "Lekene";
        $surname = "Cedric";
        $email = "lekene@gmail.com";

        $initSUT = UserSUT::asSUT()
            ->withName($name)
            ->withSurname($surname)
            ->withEmail($email)
            ->build();
        $savedUser = $initSUT->user;

        $this->repository->save($savedUser);

        $this->expectException(UserAlreadyExistException::class);

        $this->deleteUser(new Id("false user Id"));
    }

    /**
     * @throws UserAlreadyExistException
     */
    private function deleteUser(Id $userId): DeleteUserResponse
    {
        $userId = $userId->value();

        $handler = new DeleteUserHandler(
            $this->repository,
        );

        return $handler->handle($userId);
    }
}