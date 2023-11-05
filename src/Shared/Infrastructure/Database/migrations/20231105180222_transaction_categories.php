<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TransactionCategories extends AbstractMigration
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
            $table = $this->table("Transaction_categories");
            $table->addColumn('uuid', 'string', ['limit' => 255])
                  ->addColumn('name', 'string', ['limit' => 255])
                  ->addColumn('icon', 'string', ['limit' => 255])
                  ->addColumn('color', 'string', ['limit' => 255])
                  ->addColumn('description', 'string', ['limit' => 255])
                  ->addColumn('created_at', 'datetime', ['null' => true])
                  ->addColumn('updated_at', 'datetime', ['null' => true])
                  ->create();
    }
}
