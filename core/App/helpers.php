<?php

use Kernel\Application\Application;
use Kernel\Application\Configuration\Env;
use Kernel\Application\Http\Request;
use Kernel\Application\Http\Response;
use Kernel\Application\Session\Session;
use Kernel\Application\View\View;

$app = new Application;

if (! function_exists('abort')) {
    function abort(int $code, string $message = ''): Response
    {
        global $app;
        $response = $app->get('response');

        return $response::abort($code, $message);
    }
}

if (! function_exists('request')) {
    function request(): Request
    {
        global $app;

        return $app->get('request');
    }
}

if (! function_exists('response')) {
    function response(): Response
    {
        global $app;

        return $app->get('response');
    }
}

if (! function_exists('view')) {
    function view(string $view, array|object $data = []): View
    {
        if (empty($view)) {
            throw new \Exception('View name must be provided');
        }

        return (new View)->render($view, $data);
    }
}

if (! function_exists('env')) {
    function env(string $key, $default = null)
    {
        return Env::get($key, $default);
    }
}

if (! function_exists('uuid')) {
    function uuid(): string
    {
        $uuid = bin2hex(random_bytes(12));
        if (! str_contains($uuid, '-')) {
            $uuidWithDash = '';
            for ($i = 0; $i < strlen($uuid); $i++) {
                if (($i + 1) % 5 === 0) {
                    $uuidWithDash .= '-';
                } else {
                    $uuidWithDash .= $uuid[$i];
                }
            }

            return $uuidWithDash;
        }

        return str_replace('-', '', $uuid);
    }
}

if (! function_exists('storage_path')) {
    function storage_path(string $path): string
    {
        return APP_ROOT.'/storage/'.$path;
    }
}

if (! function_exists('app_path')) {
    function app_path(string $path): string
    {
        return APP_ROOT.$path;
    }
}

if (! function_exists('views_path')) {
    function views_path(string $path = ''): string
    {
        if (empty($path)) {
            return APP_ROOT.$path;
        } else {
            return APP_ROOT.'/resources/views/';
        }
    }
}

if (! function_exists('config')) {
    function config(string $key, $default = null): array
    {
        global $app;

        return $app->get('config')->get($key) ?? $default;
    }
}
if (! function_exists('session')) {
    function session(): Session
    {
        global $app;

        return $app->get('session');
    }
}
