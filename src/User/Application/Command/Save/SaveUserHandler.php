<?php

namespace Code237\Nkap\User\Application\Command\Save;

use Code237\Nkap\Shared\VO\EmailVo;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Repository\UserRepository;

readonly class SaveUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function handle(SaveUserCommand $command): SaveUserResponse
    {
        $response = new SaveUserResponse();

        $user = User::create(
          name: new StringVO($command->name),
          surname: new StringVO($command->surname),
          email: new EmailVo($command->email)
        );

        $this->userRepository->save($user);

        $response->isSaved = true;
        $response->userId = $user->id()->value();

        return $response;
    }
}