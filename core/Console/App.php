<?php

namespace Kernel\Console;

use Kernel\Console\Commands\CreateSQLiteDatabaseCommand;
use Kernel\Console\Commands\StartServer;
use Symfony\Component\Console\Application as SymfonyConsoleApp;

class App extends SymfonyConsoleApp
{
    public function __construct()
    {
        parent::__construct('Octane Console Assistant', '1.0.0');

        $this->add(new StartServer);
        $this->add(new CreateSQLiteDatabaseCommand);
    }
}
