<?php

namespace Kernel\Application\Errors;

class ErrorHandler
{
    private string $errorMessage;

    private int $errorCode;

    private string $errorFile;

    private int $errorLine;

    public function __construct(
        string $errorMessage,
        int $errorCode,
        string $errorFile,
        int $errorLine
    ) {
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
        $this->errorFile = $errorFile;
        $this->errorLine = $errorLine;

        $this->renderError();
    }

    public static function handleException(\Throwable $exception): void
    {
        $errorFile = $exception->getFile();
        $errorLine = $exception->getLine();

        error_log('Exception occurred in file: '.$errorFile.' on line: '.$errorLine);

        new self($exception->getMessage(), $exception->getCode(), $errorFile, $errorLine);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $backtrace = debug_backtrace();
        $errorFile = $backtrace[1]['file'] ?? 'Unknown file';
        $errorLine = $backtrace[1]['line'] ?? 0;

        error_log("Error occurred in file: $errorFile on line: $errorLine");

        $message = "Error: [$errno] $errstr";
        new self($message, $errno, $errfile, $errline);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            $message = "Fatal Error: [{$error['type']}] {$error['message']}";
            $errorFile = $error['file'] ?? 'Unknown file';
            $errorLine = $error['line'] ?? 0;

            error_log('Shutdown error occurred in file: '.$errorFile.' on line: '.$errorLine);

            $message .= " in $errorFile on line $errorLine";
            new self($message, $error['type'], $errorFile, $errorLine);
        }
    }

    private function renderError(): void
    {
        extract([
            'errorMessage' => $this->errorMessage,
            'errorFile' => $this->errorFile,
            'errorLine' => $this->errorLine,
        ]);

        include __DIR__.'/errors/error.php';

        exit();
    }
}
