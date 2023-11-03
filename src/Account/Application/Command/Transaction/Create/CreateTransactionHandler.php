<?php

namespace Code237\Nkap\Account\Application\Command\Transaction\Create;

use Code237\Nkap\Account\Domain\Exceptions\InvalidTransactionException;
use Code237\Nkap\Account\Domain\Exceptions\NotFoundAccountException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

readonly class CreateTransactionHandler
{
    public function __construct(
        private AccountRepository $accountRepository,
    )
    {
    }

    /**
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     */
    public function handle(CreateTransactionCommand $command): CreateTransactionResponse
    {
        $response = new CreateTransactionResponse();

        $account = $this->accountRepository->getById(new Id($command->accountId));

        if (is_null($account)){
            throw new NotFoundAccountException("Le compte sélectionné n'existe pas dans le système !");
        }

        $account->addTransaction(
            transactionCategoryId: new Id($command->transactionCategoryId),
            transactionType: TransactionTypeEnum::in($command->transactionType),
            transactionAmount: new AmountVO($command->transactionAmount),
            transactionDescription: new StringVO($command->transactionDescription),
            transactionOperationDate: new DateVO($command->transactionOperationDate),
        );

        $this->accountRepository->update($account);

        return $response;
    }
}