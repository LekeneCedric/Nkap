<?php

namespace Code237\Nkap\User\Application\Command\Save;

use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\EmailVO;
use Code237\Nkap\Shared\VO\PasswordVO;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Domain\UserRepository;

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
          email: new EmailVO($command->email),
          password: PasswordVO::fromPassword($command->password),
          createdAt: new DateVO(),updatedAt: new DateVO()
        );

        $this->userRepository->save($user);

        $response->isSaved = true;
        $response->userId = $user->id()->value();

        return $response;
    }
}