<?php

namespace Kernel\Application\Http;

class Response
{
    private mixed $content = '';

    private int $statusCode = 200;

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
        http_response_code($code);
    }

    public function send(): mixed
    {
        http_response_code($this->statusCode);

        return $this->content;
    }

    public static function abort(int $code, string $message = ''): void
    {
        $response = new self;

        $response->setStatusCode($code);
        $response->setContent($message);

        extract([
            'code' => $code,
            'message' => $message,
        ]);

        $abortView = APP_ROOT.'/core/App/Errors/errors/abort.php';

        if (file_exists($abortView)) {
            include $abortView;
        }

        exit();
    }
}
