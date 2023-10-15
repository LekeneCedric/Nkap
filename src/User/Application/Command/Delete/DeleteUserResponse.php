<?php

namespace Code237\Nkap\User\Application\Command\Delete;

class DeleteUserResponse
{
    public bool $isDeleted = false;
    public ?string $message = '';
}