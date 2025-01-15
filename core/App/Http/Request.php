<?php

namespace Kernel\Application\Http;

class Request
{
    private array $getData;

    private array $postParam;

    private array $files;

    private array $server;

    private array $cookies;

    public function __construct(
        array $getData,
        array $postParam,
        array $files,
        array $server,
        array $cookies,
    ) {
        $this->getData = $getData;
        $this->postParam = $postParam;
        $this->files = $files;
        $this->server = $server;
        $this->cookies = $cookies;
    }

    public static function createFromGlobals(): Request
    {
        return self::globals();
    }

    public function getRequestInfo(): array
    {
        return [
            'uri' => $this->uri(),
            'method' => $this->method(),
        ];
    }

    public function uri()
    {
        return $this->server['REQUEST_URI'];
    }

    public function method()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function input(?string $key = null)
    {
        if ($key) {
            return $this->postParam[$key] ?? $this->getData[$key] ?? null;
        }

        return array_merge($this->getData, $this->postParam);
    }

    public function merge(array $data): void
    {
        $this->getData = array_merge($this->getData, $data);
    }

    private static function globals(): Request
    {
        return new self(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_COOKIE
        );
    }
}
