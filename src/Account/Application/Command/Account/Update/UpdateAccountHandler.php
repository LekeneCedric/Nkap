<?php

namespace Code237\Nkap\Account\Application\Command\Account\Update;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\Domain\Services\CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

readonly class UpdateAccountHandler
{
    public function __construct(
        private AccountRepository                                                    $repository,
        private CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService $checkIfAlreadyExistAccountByIdOrThrowNotFoundAccountException,
    )
    {
    }

    public function handle(UpdateAccountCommand $updateAccountCommand): UpdateAccountResponse
    {
        $response = new UpdateAccountResponse();

        $command = $updateAccountCommand;

        $this->checkIfAlreadyExistAccountByIdOrThrowNotFoundAccountException->execute(new Id($command->id));

        $accountToUpdate = Account::create(
            userId: new Id($command->userId),
            balance: new AmountVO($command->balance),
            name: new StringVO($command->accountName),
            isIncludeInTotalBalance: $command->isIncludeInTotalBalance,
            id: $command->id ? new Id($command->id): new Id(),
            createdAt: $command->createdAt ? new DateVO($command->createdAt): new DateVO(),
            updatedAt: $command->updatedAt ? new DateVO($command->updatedAt): new DateVO(),
            totalIncomes: $command->totalIncomes ? new AmountVO($command->totalIncomes): new AmountVO(0),
            totalExpenses: $command->totalExpenses ? new AmountVO($command->totalExpenses): new AmountVO(0),
            lastTransactionDate: $command->lastTransactionDate ? new DateVO($command->lastTransactionDate): new DateVO(),
            color: $command->color ? new StringVO($command->color): new StringVO('green'),
            iconName: $command->iconName ? new StringVO($command->iconName): new StringVO('balance'),
        );

        $this->repository->update($accountToUpdate);

        $response->isUpdate = true;
        return $response;
    }
}