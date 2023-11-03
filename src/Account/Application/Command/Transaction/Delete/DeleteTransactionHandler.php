<?php

namespace Code237\Nkap\Account\Application\Command\Transaction\Delete;

use Code237\Nkap\Account\Domain\Exceptions\NotFoundAccountException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Shared\VO\Id;

readonly class DeleteTransactionHandler
{
    public function __construct(
        private AccountRepository $repository,
    )
    {
    }

    /**
     * @throws NotFoundAccountException
     */
    public function handle(string $accountId, string $transactionId): DeleteTransactionResponse
    {
        $response = new DeleteTransactionResponse();

        $account = $this->repository->getById(new Id($accountId));
        if (empty($account)) {
            throw new NotFoundAccountException("Le compte sélectionné n'existe pas dans le système !");
        }

        $account->removeTransaction(new Id($transactionId));

        $this->repository->create($account);

        $response->isDeleted = true;
        return $response;
    }
}