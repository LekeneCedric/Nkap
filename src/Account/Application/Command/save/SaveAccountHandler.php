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

        $newAccount = Account::create(
            userId: new Id($saveAccountCommand->userId),
            balance: new AmountVo($saveAccountCommand->balance),
            name: new StringVO($saveAccountCommand->accountName),
            isIncludeInTotalBalance: $saveAccountCommand->isIncludeInTotalBalance,
        );

        $this->accountRepository->save($newAccount);

        $response->isSaved = true;
        $response->message = 'Compte sauvegardé avec succès !';

        return $response;
    }
}