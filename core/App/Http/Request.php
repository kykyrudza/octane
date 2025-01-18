<?php

namespace Kernel\Application\Http;

use Kernel\Application\Session\Session;
use Kernel\Application\Validation\Validation;
use Kernel\Application\Validation\Validator\Validator;

/**
 * Class Request
 *
 * Represents an HTTP request, providing access to GET, POST, FILES, SERVER, and COOKIE data.
 * It supports data validation, input retrieval, and session-based old input flashing.
 */
class Request
{
    /**
     * GET parameters from the request.
     *
     * @var array
     */
    private array $getData;

    /**
     * POST parameters from the request.
     *
     * @var array
     */
    private array $postParam;

    /**
     * Uploaded files from the request.
     *
     * @var array
     */
    private array $files;

    /**
     * Server and execution environment parameters.
     *
     * @var array
     */
    private array $server;

    /**
     * Cookies sent with the request.
     *
     * @var array
     */
    private array $cookies;

    /**
     * Session instance for managing session data.
     *
     * @var Session
     */
    private Session $session;

    /**
     * Constructor.
     *
     * Initializes the request with session and various HTTP data arrays.
     *
     * @param Session $session The session instance.
     * @param array $getData The GET parameters.
     * @param array $postParam The POST parameters.
     * @param array $files The uploaded files.
     * @param array $server The server environment data.
     * @param array $cookies The cookies sent with the request.
     */
    public function __construct(
        Session $session,
        array $getData = [],
        array $postParam = [],
        array $files = [],
        array $server = [],
        array $cookies = []
    ) {
        $this->getData = $getData;
        $this->postParam = $postParam;
        $this->files = $files;
        $this->server = $server;
        $this->cookies = $cookies;
        $this->session = $session;

        if ($this->method() === 'POST') {
            $this->flashOldData();
        }
    }

    /**
     * Creates a new Request instance using global PHP variables.
     *
     * @return Request
     */
    public static function createFromGlobals(): Request
    {
        return self::globals();
    }

    /**
     * Retrieves basic information about the request.
     *
     * @return array An array containing the URI and method of the request.
     */
    public function getRequestInfo(): array
    {
        return [
            'uri' => $this->uri(),
            'method' => $this->method(),
        ];
    }

    /**
     * Retrieves the request URI.
     *
     * @return string The URI from the server parameters.
     */
    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '';
    }

    /**
     * Retrieves the request method.
     *
     * @return string The HTTP method, defaulting to 'GET' if unavailable.
     */
    public function method(): string
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Retrieves an input value by key or all input data.
     *
     * @param string|null $key The input key to retrieve, or null to get all input data.
     * @return mixed The input value or null if not found.
     */
    public function input(?string $key = null): mixed
    {
        if ($key) {
            return $this->postParam[$key] ?? $this->getData[$key] ?? $this->old($key) ?? null;
        }

        return array_merge($this->getData, $this->postParam);
    }

    /**
     * Validates the input data against a set of rules.
     *
     * @param array $rules An associative array of validation rules.
     * @return array The validation results.
     */
    public function validate(array $rules): array
    {
        $validator = new Validator();
        $validation = new Validation($validator);

        return $validation->validate($rules, $this->input());
    }

    /**
     * Merges additional data into the GET parameters.
     *
     * @param array $data The data to merge.
     * @return void
     */
    public function merge(array $data): void
    {
        $this->getData = array_merge($this->getData, $data);
    }

    /**
     * Retrieves all request data as an object.
     *
     * @return static The current Request instance.
     */
    public function all(): static
    {
        return $this;
    }

    /**
     * Retrieves a file by key from the request.
     *
     * @param string $key The file key.
     * @return mixed The file data or null if not found.
     */
    public function file(string $key): mixed
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Retrieves a cookie by key from the request.
     *
     * @param string $key The cookie key.
     * @return mixed The cookie value or null if not found.
     */
    public function cookie(string $key): mixed
    {
        return $this->cookies[$key] ?? null;
    }

    /**
     * Stores old POST data in the session for later retrieval.
     *
     * @return void
     */
    private function flashOldData(): void
    {
        foreach ($this->postParam as $key => $value) {
            $this->session->set('old_data.' . $key, $value);
        }
    }

    /**
     * Retrieves old input data from the session.
     *
     * @param string|null $key The key of the old data to retrieve, or null to get all old data.
     * @return mixed The old data value or null if not found.
     */
    public function old(string $key = null): mixed
    {
        if ($key) {
            $oldValue = $this->session->get('old_data.' . $key);
            $this->session->delete('old_data.' . $key);
            return $oldValue;
        }

        $oldData = $this->session->get('old_data') ?? [];
        $this->session->delete('old_data');
        return $oldData;
    }

    /**
     * Creates a Request instance using global PHP variables.
     *
     * @return Request The created Request instance.
     */
    private static function globals(): Request
    {
        $session = new Session();

        return new self(
            $session,
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_COOKIE,
        );
    }
}
