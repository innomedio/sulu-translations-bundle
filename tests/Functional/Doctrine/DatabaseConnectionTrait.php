<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Functional\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Phpro\SuluTranslationsBundle\Infrastructure\Doctrine\DatabaseConnectionManager;
use Phpro\SuluTranslationsBundle\Infrastructure\Doctrine\Schema\TranslationTable;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

use function Psl\Result\wrap;

trait DatabaseConnectionTrait
{
    use ProphecyTrait;

    protected Connection $connection;

    protected function setupConnection(): void
    {
        $dsnParser = new DsnParser([
            'db2' => 'ibm_db2',
            'mssql' => 'pdo_sqlsrv',
            'mysql' => 'pdo_mysql',
            'mysql2' => 'pdo_mysql',
            'postgres' => 'pdo_pgsql',
            'postgresql' => 'pdo_pgsql',
            'pgsql' => 'pdo_pgsql',
            'sqlite' => 'pdo_sqlite',
            'sqlite3' => 'pdo_sqlite',
        ]);
        $connectionParams = $dsnParser->parse($_ENV['DATABASE_URL']);

        $this->connection = DriverManager::getConnection($connectionParams);
    }

    /**
     * @return DatabaseConnectionManager|ObjectProphecy
     */
    protected function createDatabaseConnectionMock(Connection $connection): mixed
    {
        $databaseConnectionManager = $this->prophesize(DatabaseConnectionManager::class);
        $databaseConnectionManager->getConnection()->willReturn($connection);

        return $databaseConnectionManager;
    }

    protected function cleanup(): void
    {
        wrap(fn () => $this->connection->executeQuery('DROP TABLE '.TranslationTable::NAME));
    }
}
