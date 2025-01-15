<?php

namespace Kernel\Application\Configuration;

class Env
{
    private static $loaded = false;

    public static function load(string $filePath)
    {
        if (! file_exists($filePath)) {
            throw new \Exception("Файл .env не найден: {$filePath}");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode('=', $line, 2));

            $value = trim($value, "\"'");

            if (! array_key_exists($key, $_ENV) && ! getenv($key)) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }

        self::$loaded = true;
    }

    public static function get(string $key, $default = null)
    {
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }
}
