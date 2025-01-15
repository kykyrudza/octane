<?php

namespace Kernel\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StartServer extends Command
{
    protected static $defaultName = 'server:start';

    public function __construct()
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Start a local development server')
            ->setHelp('This command starts a PHP built-in server for development')
            ->addOption(
                'host',
                null,
                InputOption::VALUE_OPTIONAL,
                'Host for the server',
                '127.0.0.1'
            )
            ->addOption(
                'port',
                null,
                InputOption::VALUE_OPTIONAL,
                'Port for the server',
                '8000'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getOption('host');
        $port = $input->getOption('port');

        $output->writeln("Starting server at <info>http://$host:$port</info>");
        $output->writeln('<comment>Press Ctrl+C to stop the server.</comment>');

        $command = sprintf('php -S %s:%s -t public', escapeshellarg($host), escapeshellarg($port));
        passthru($command);

        return Command::SUCCESS;
    }
}
