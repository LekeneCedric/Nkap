<?php

namespace Code237\Nkap\Account\Application\Command\Create;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Account\Domain\Exceptions\UserLinkedToAccountDoesNotExistException;
use Code237\Nkap\Account\Domain\Services\CheckIfUserLinkedToAccountExistOrThrowException;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

readonly class CreateAccountHandler
{
    public function __construct(
        private AccountRepository $accountRepository,
        private CheckIfUserLinkedToAccountExistOrThrowException $checkIfUserLinkedToAccountExistOrThrowException,
    )
    {
    }

    /**
     * @throws UserLinkedToAccountDoesNotExistException
     */
    public function handle(CreateAccountCommand $saveAccountCommand): CreateAccountResponse
    {
        $response = new CreateAccountResponse();

        $command = $saveAccountCommand;

        $this->checkIfUserLinkedToAccountDoesNotExistOrThrowUserLinkedToAccountDoesNotExistException($command->userId);

        $accountToSave = Account::create(
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

        $this->accountRepository->create($accountToSave);

        $response->isSaved = true;
        $response->message = 'Compte créee avec succès !';

        return $response;
    }

    /**
     * @throws UserLinkedToAccountDoesNotExistException
     */
    private function checkIfUserLinkedToAccountDoesNotExistOrThrowUserLinkedToAccountDoesNotExistException(string $userId): void
    {
        $isUserLinkedToAccountExist = $this->checkIfUserLinkedToAccountExistOrThrowException->execute(new Id($userId));

        if (!$isUserLinkedToAccountExist) {
            throw new UserLinkedToAccountDoesNotExistException("Aucun utilisateur n'est liée a ce compte !");
        }
    }
}