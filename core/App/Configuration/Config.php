<?php

namespace Kernel\Application\Configuration;

class Config
{
    private array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $keyPart) {
            if (isset($value[$keyPart])) {
                $value = $value[$keyPart];
            } else {
                return $default;
            }
        }

        return $value;
    }

    public function all(): array
    {
        return $this->config;
    }

    public function loadFromFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            $this->config = require $filePath;
        } else {
            throw new \Exception("Configuration file does not exist: $filePath");
        }
    }
}
