<?php

namespace Code237\Nkap\Account\tests\Feature;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Account\Infrastructure\Repositories\PdoAccountRepository;
use Code237\Nkap\Shared\Infrastructure\Lib\MySQLPdoConnection;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use Exception;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    private AccountRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PdoAccountRepository(new MySQLPdoConnection());
    }

    public function test_can_save_account()
    {
        $accountToSave = Account::create(
            userId: new Id(),
            balance: new AmountVo(2000),
            name: new StringVO("compte epargne"),
            isIncludeInTotalBalance: true
        );

        $response = $this->repository->create($accountToSave);

        $this->assertTrue($response);
    }

    public function test_can_get_account_by_id()
    {
        $savedAccount = Account::create(
            userId: new Id(),
            balance: new AmountVo(2000),
            name: new StringVO("compte epargne"),
            isIncludeInTotalBalance: true
        );

        $this->repository->create($savedAccount);

        $getAccount = $this->repository->getById($savedAccount->id());

        $this->assertEquals($savedAccount->id()->value(), $getAccount->id()->value());
        $this->assertEquals($savedAccount->balance()->value(), $getAccount->balance()->value());
        $this->assertEquals($savedAccount->name()->value(), $getAccount->name()->value());
    }

    /**
     * @throws Exception
     */
    public function test_can_udpate_account()
    {
        $savedAccount = Account::create(
            userId: new Id(),
            balance: new AmountVo(2000),
            name: new StringVO("compte epargne"),
            isIncludeInTotalBalance: true
        );

        $this->repository->create($savedAccount);

        $savedAccount->changeName(new StringVO("compte epargne update"));

        $response = $this->repository->update($savedAccount);

        $this->assertTrue($response);
    }
}