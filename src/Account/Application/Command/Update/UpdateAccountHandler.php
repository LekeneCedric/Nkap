<?php

namespace Code237\Nkap\Account\Application\Command\Update;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Account\Domain\Services\CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
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
            balance: new AmountVo($command->balance),
            name: new StringVO($command->accountName),
            isIncludeInTotalBalance: $command->isIncludeInTotalBalance,
            id: $command->id ? new Id($command->id): new Id(),
            createdAt: $command->createdAt ? new Datevo($command->createdAt): new Datevo(),
            updatedAt: $command->updatedAt ? new Datevo($command->updatedAt): new Datevo(),
            totalIncomes: $command->totalIncomes ? new AmountVo($command->totalIncomes): new AmountVo(0),
            totalExpenses: $command->totalExpenses ? new AmountVo($command->totalExpenses): new AmountVo(0),
            lastTransactionDate: $command->lastTransactionDate ? new Datevo($command->lastTransactionDate): new Datevo(),
            color: $command->color ? new StringVO($command->color): new StringVO('green'),
            iconName: $command->iconName ? new StringVO($command->iconName): new StringVO('balance'),
        );

        $this->repository->update($accountToUpdate);

        $response->isUpdate = true;
        return $response;
    }
}