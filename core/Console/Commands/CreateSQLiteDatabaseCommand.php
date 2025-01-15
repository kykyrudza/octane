<?php

namespace Kernel\Console\Commands;

use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSQLiteDatabaseCommand extends Command
{
    protected static $defaultName = 'app:database';

    public function __construct()
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->setDescription('Creates a new empty SQLite database at the specified path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dbPath = DATABASE_PATH.'/database.sqlite';

        if (! file_exists(dirname($dbPath))) {
            mkdir(dirname($dbPath), 0777, true);
        }

        try {
            $pdo = new PDO("sqlite:$dbPath");

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $output->writeln("<info>SQLite database created successfully at: $dbPath</info>");

            return Command::SUCCESS;
        } catch (PDOException $e) {
            $output->writeln('<error>Failed to create SQLite database: '.$e->getMessage().'</error>');

            return Command::FAILURE;
        }
    }
}
