<?php

namespace Code237\Nkap\Account\Application\Command\save;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\Respository\AccountRepository;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

readonly class SaveAccountHandler
{
    public function __construct(
        private AccountRepository $accountRepository,
    )
    {
    }

    public function handle(SaveAccountCommand $saveAccountCommand): SaveAccountResponse
    {
        $response = new SaveAccountResponse();

        $accountToSave = Account::create(
            userId: new Id($saveAccountCommand->userId),
            balance: new AmountVo($saveAccountCommand->balance),
            totalIncomes: new AmountVo($saveAccountCommand->totalIncomes),
            totalExpenses: new AmountVo($saveAccountCommand->totalExpenses),
            lastTransactionDate: new Datevo($saveAccountCommand->lastTransactionDate),
            name: new StringVO($saveAccountCommand->accountName),
            isIncludeInTotalBalance: $saveAccountCommand->isIncludeInTotalBalance,
        );

        $this->accountRepository->save($accountToSave);

        $response->isSaved = true;
        $response->message = 'Compte sauvegardé avec succès !';

        return $response;
    }
}