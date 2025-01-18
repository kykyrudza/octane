<?php

namespace Kernel\Application\Http;

/**
 * Class Response
 *
 * Represents an HTTP response, allowing content and status code manipulation.
 * It can send responses to the client and handle error responses.
 */
class Response
{
    /**
     * The content of the response.
     *
     * @var mixed
     */
    private mixed $content = '';

    /**
     * The HTTP status code of the response.
     *
     * @var int
     */
    private int $statusCode = 200;

    /**
     * Sets the content of the response.
     *
     * @param mixed $content The response content.
     * @return void
     */
    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    /**
     * Sets the HTTP status code of the response.
     *
     * Updates the status code sent with the HTTP headers.
     *
     * @param int $code The HTTP status code.
     * @return void
     */
    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
        http_response_code($code);
    }

    /**
     * Sends the response content to the client.
     *
     * This also ensures the correct HTTP status code is sent.
     *
     * @return mixed The content of the response.
     */
    public function send(): mixed
    {
        http_response_code($this->statusCode);

        return $this->content;
    }

    /**
     * Aborts the request with a given HTTP status code and optional message.
     *
     * This method sets the status code, outputs an error message, and optionally
     * includes a custom error view if it exists. Execution stops after calling this method.
     *
     * @param int $code The HTTP status code for the abort.
     * @param string $message The optional error message.
     * @return void
     */
    public static function abort(int $code, string $message = ''): void
    {
        $response = new self;

        $response->setStatusCode($code);
        $response->setContent($message);

        extract([
            'code' => $code,
            'message' => $message,
        ]);

        $abortView = APP_ROOT . '/core/App/Errors/errors/abort.php';

        if (file_exists($abortView)) {
            include $abortView;
        }

        exit();
    }
}
