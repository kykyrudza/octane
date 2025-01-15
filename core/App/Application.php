<?php

namespace Kernel\Application;

use Exception;
use Kernel\Application\Configuration\Config;
use Kernel\Application\Configuration\Env;
use Kernel\Application\DataBase\DB\DataBaseConnect;
use Kernel\Application\Errors\ErrorHandler;
use Kernel\Application\Http\Kernel;
use Kernel\Application\Http\Request;
use Kernel\Application\Http\Response;
use Kernel\Application\Session\Session;
use Kernel\Application\View\View;

class Application extends Container
{
    public function __construct(array $services = [])
    {
        $this->loadHelpers();
        parent::__construct($services);
    }

    public function run(): void
    {
        $this->loadEnvironment();
        $this->setupErrorHandling();
        $this->registerServices();
        $this->initializeConfig();
        $this->createKernel()->run();
    }

    private function registerServices(): void
    {
        $config = new Config($this->loadConfig());

        $this->set('config', $config);

        $this->set('request', $this->services['request'] ?? Request::createFromGlobals());
        $this->set('response', $this->services['response'] ?? new Response);
        $this->set('view', $this->services['view'] ?? new View);
        $this->set('session', $this->services['session'] ?? new Session);

        $this->set('database', $this->services['database'] ?? new DataBaseConnect($this->get('config')));
    }

    private function createKernel(): Kernel
    {
        return new Kernel(
            $this->get('request'),
            $this->get('response')
        );
    }

    private function loadEnvironment(): void
    {
        try {
            Env::load(APP_ROOT.'/.env');
        } catch (Exception $e) {
            ErrorHandler::handleException($e);
        }
    }

    private function setupErrorHandling(): void
    {
        $debug = Env::get('APP_DEBUG') === 'true';

        if ($debug) {
            set_exception_handler([ErrorHandler::class, 'handleException']);
            set_error_handler([ErrorHandler::class, 'handleError']);
            register_shutdown_function([ErrorHandler::class, 'handleShutdown']);
        } else {
            $this->setProductionErrorHandlers();
        }
    }

    private function setProductionErrorHandlers(): void
    {
        set_exception_handler(fn () => abort(500, 'Internal Server Error'));
        set_error_handler(fn () => abort(500, 'Internal Server Error'));
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== null) {
                abort(500, 'Internal Server Error');
            }
        });
    }

    private function initializeConfig(): void
    {
        $this->loadConfig();
    }

    private function loadConfig(): array
    {
        $configPath = str_replace(DIRECTORY_SEPARATOR, '/', APP_ROOT.'/config/config.php');

        if (! file_exists($configPath)) {
            throw new Exception('Config file not found.');
        }

        $config = require $configPath;

        if (! is_array($config)) {
            throw new Exception('Config file is not an array.');
        }

        return $config;
    }

    private function loadHelpers(): void
    {
        $helpersPath = APP_ROOT.'/core/App/helpers.php';

        if (! file_exists($helpersPath)) {
            ErrorHandler::handleException(new Exception('Helpers file not found.'));

            return;
        }

        require_once $helpersPath;
    }
}
