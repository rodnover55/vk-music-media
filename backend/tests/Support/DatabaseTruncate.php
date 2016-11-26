<?php
namespace VkMusic\Tests\Support;


use Illuminate\Database\DatabaseManager;

trait DatabaseTruncate
{
    public function runDatabaseTruncate()
    {
        /** @var DatabaseManager $dbManager */
        $dbManager = $this->app->make('db');
        $connection = $dbManager->connection();

        $tables = $connection->getDoctrineSchemaManager()->listTableNames();

        $strTables = implode(', ', array_filter($tables, function ($item) {
            return $item != 'migrations';
        }));

        $connection->statement("
            TRUNCATE {$strTables} RESTART IDENTITY
        ");
    }
}