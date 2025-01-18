<?php

namespace Kernel\Application\Session;

/**
 * Class Session
 *
 * A simple session management class for handling session operations.
 * Provides methods to set, retrieve, delete, and manage session data.
 */
class Session
{
    /**
     * Constructor.
     *
     * Starts a session if one is not already active and regenerates the session ID.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->regenerate();
    }

    /**
     * Sets a session value for the specified key.
     *
     * @param string $key The session key.
     * @param mixed $value The value to store in the session.
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a session value by its key.
     *
     * @param string $key The session key.
     * @return mixed|null The value associated with the key, or null if the key does not exist.
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Deletes a session value by its key.
     *
     * @param string $key The session key to delete.
     * @return void
     */
    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroys the current session.
     *
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
    }

    /**
     * Checks if a session key exists.
     *
     * @param string $key The session key to check.
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Retrieves all session data.
     *
     * @return array An array containing all session key-value pairs.
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * Sets a flash message and schedules it for deletion after retrieval.
     *
     * @param string $key The session key for the flash message.
     * @param mixed $value The value to store in the session.
     * @return void
     */
    public function flash($key, $value): void
    {
        $this->set($key, $value);
        $this->delete($key);
    }

    /**
     * Retrieves a flash message by its key and deletes it after retrieval.
     *
     * @param string $key The session key for the flash message.
     * @return mixed|null The flash message value, or null if it does not exist.
     */
    public function getFlash($key)
    {
        $value = $this->get($key);
        $this->delete($key);

        return $value;
    }

    /**
     * Regenerates the session ID while preserving session data.
     *
     * @return void
     */
    public function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /**
     * Regenerates the session ID without any additional options.
     *
     * @return void
     */
    public function regenerateId(): void
    {
        session_regenerate_id();
    }
}
