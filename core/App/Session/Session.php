<?php

namespace Kernel\Application\Session;

class Session
{
    public function __construct()
    {
        // Проверка и запуск сессии, если она еще не активна
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->regenerate();
    }

    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null; // Возвращаем null, если ключ не существует
    }

    public function delete($key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        session_destroy();
    }

    public function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function all(): array
    {
        return $_SESSION;
    }

    public function flash($key, $value): void
    {
        $this->set($key, $value);
        $this->delete($key);
    }

    public function getFlash($key)
    {
        $value = $this->get($key);
        $this->delete($key);

        return $value;
    }

    public function regenerate(): void
    {
        // Сессия должна быть активной перед регенерацией ID
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Регенерация ID сессии без уничтожения
            session_regenerate_id(true);
        }
    }

    public function regenerateId(): void
    {
        session_regenerate_id();
    }
}
