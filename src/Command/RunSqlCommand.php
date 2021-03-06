<?php

declare(strict_types=1);

namespace Chubbyphp\Model\Doctrine\DBAL\Command;

use Doctrine\Common\Util\Debug;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * (c) <http://www.doctrine-project.org>.
 */
final class RunSqlCommand
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $sql = $this->getSql($input);
        $depth = $this->getDepth($input);

        if (stripos($sql, 'select') === 0) {
            $resultSet = $this->connection->fetchAll($sql);
        } else {
            $resultSet = $this->connection->executeUpdate($sql);
        }

        $message = Debug::dump($resultSet, (int) $depth, true, false);

        $output->write($message);
    }

    /**
     * @param InputInterface $input
     *
     * @return string
     */
    private function getSql(InputInterface $input): string
    {
        if (($sql = $input->getArgument('sql')) === null) {
            throw new \RuntimeException("Argument 'SQL' is required in order to execute this command correctly.");
        }

        return $sql;
    }

    /**
     * @param InputInterface $input
     *
     * @return int
     */
    private function getDepth(InputInterface $input): int
    {
        $depth = $input->getOption('depth');

        if (!is_numeric($depth)) {
            throw new \InvalidArgumentException("Option 'depth' must contains an integer value");
        }

        return (int) $depth;
    }
}
