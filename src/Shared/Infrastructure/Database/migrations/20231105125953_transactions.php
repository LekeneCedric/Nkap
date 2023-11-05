<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Transactions extends AbstractMigration
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
        $table = $this->table('Transactions');
        $table->addColumn('uuid', 'string', ['limit' => 255])
              ->addColumn('account_id', 'string', ['limit' => 255])
              ->addColumn('category_id', 'string', ['limit' => 255])
              ->addColumn('type', 'integer')
              ->addColumn('amount', 'float')
              ->addColumn('description', 'string', ['limit' => 255])
              ->addColumn('operation_date', 'datetime', ['null' => true])
              ->addColumn('created_at', 'datetime', ['null' => true])
              ->addColumn('updated_at', 'datetime', ['null' => true])
              ->create();
    }
}
