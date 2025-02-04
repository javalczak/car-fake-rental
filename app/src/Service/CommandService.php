<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;

readonly class CommandService
{
    public function __construct(
        private ManagerRegistry $doctrine
    ){
    }

    public function resetAutoIncrement(string $tableName): void
    {
        $conn = $this->doctrine->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $sql = sprintf('ALTER TABLE %s AUTO_INCREMENT = 1', $tableName);
        $conn->executeQuery($sql);
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }

    public function truncate(string $tableName): void
    {
        $conn = $this->doctrine->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $sql = sprintf('TRUNCATE TABLE %s', $tableName);
        $conn->executeQuery($sql);
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }
}