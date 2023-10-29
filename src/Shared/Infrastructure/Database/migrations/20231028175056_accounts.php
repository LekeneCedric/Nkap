<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Accounts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('Accounts');
        $table->addColumn('uuid', 'string', ['limit' => 255])
            ->addColumn('user_id', 'string', ['limit' => 255])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('is_include_in_total_balance', 'string', ['limit' => 255])
            ->addColumn('balance', 'float')
            ->addColumn('total_incomes', 'float')
            ->addColumn('total_expenses', 'float')
            ->addColumn('last_transaction_date', 'datetime', ['null' => true])
            ->addColumn('icon_name', 'string')
            ->addColumn('color', 'string')
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
