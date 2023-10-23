<?php

namespace Code237\Nkap\User\Application\Command\Delete;

use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\Exception\UserAlreadyExistException;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Repository\UserRepository;

readonly class DeleteUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    /**
     * @throws UserAlreadyExistException
     */
    public function handle(string $userId): DeleteUserResponse
    {
        $response = new DeleteUserResponse();

        $user = $this->GetUserByIdOrThrowIfNotExistUserNotFoundException($userId);

        $this->userRepository->deleteById($user->id());

        $response->isDeleted = true;

        return $response;
    }

    /**
     * @throws UserAlreadyExistException
     */
    private function GetUserByIdOrThrowIfNotExistUserNotFoundException(string $userId): User
    {
        $user = $this->userRepository->byId(new Id($userId));
        if (is_null($user)) {
            throw new UserAlreadyExistException("L'utilisateur sélectionné n'existe pas dans le système !");
        }
        return $user;
    }
}