<?php

namespace Code237\Nkap\Account\Application\Command\Transaction\Update;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\Exceptions\InvalidTransactionException;
use Code237\Nkap\Account\Domain\Exceptions\NotFoundAccountException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\Domain\Transaction;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

readonly class UpdateTransactionHandler
{
    public function __construct(
        private AccountRepository $repository,
    )
    {
    }

    /**
     * @throws NotFoundAccountException
     * @throws InvalidTransactionException
     */
    public function handle(UpdateTransactionCommand $updateTransactionCommand): UpdateTransactionResponse
    {
        $response = new UpdateTransactionResponse();

        $account = $this->getAccountOrThrowNotFoundAccountException($updateTransactionCommand->accountId);
        $updatedTransaction = $this->createUpdatedTransactionByCommand($updateTransactionCommand);

        $account->updateTransaction($updatedTransaction);
        $this->repository->update($account);

        $response->isUpdated = true;

        return $response;
    }

    private function createUpdatedTransactionByCommand(UpdateTransactionCommand $updateTransactionCommand): Transaction
    {
        return Transaction::create(
            accountId: new Id($updateTransactionCommand->accountId),
            transactionCategoryId: new Id($updateTransactionCommand->transactionCategoryId),
            type: TransactionTypeEnum::in($updateTransactionCommand->transactionType),
            amount: new AmountVO($updateTransactionCommand->transactionAmount),
            description: new StringVO($updateTransactionCommand->transactionDescription),
            operationDate: new DateVO($updateTransactionCommand->transactionOperationDate),
            id: new Id($updateTransactionCommand->id),
            createdAt: new DateVO($updateTransactionCommand->createdAt),
            updatedAt: new DateVO($updateTransactionCommand->updatedAt),

        );
    }

    /**
     * @throws NotFoundAccountException
     */
    private function getAccountOrThrowNotFoundAccountException(string $accountId): Account
    {
        $account = $this->repository->getById(new Id($accountId));
        if (is_null($account)) {
            throw new NotFoundAccountException("Cette transaction ne correspond a aucun compte !");
        }
        return $account;
    }
}