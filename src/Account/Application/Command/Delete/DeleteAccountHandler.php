<?php

namespace Code237\Nkap\Account\Application\Command\Delete;

use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Shared\VO\Id;

readonly class DeleteAccountHandler
{
    public function __construct(
        private AccountRepository $repository,
    )
    {
    }

    public function handle(string $accountId): DeleteAccountResponse
    {
        $response = new DeleteAccountResponse();

        $this->repository->delete(new Id($accountId));

        $response->isDeleted = true;
        $response->message = 'Compte supprimée avec success !';

        return $response;
    }
}