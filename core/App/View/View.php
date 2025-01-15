<?php

namespace Kernel\Application\View;

use Exception;

class View
{
    protected string $viewDirectory;

    public function __construct(string $viewDirectory = '')
    {
        $this->viewDirectory = $viewDirectory ?: APP_ROOT.'/resources/views';
    }

    public function render(string $view, array $data = []): View
    {
        echo $this->renderView($view, $data);

        return $this;
    }

    private function renderView(string $view, array $data): string
    {
        $viewPath = $this->getViewPath($view);

        if (! file_exists($viewPath)) {
            throw new Exception("View file not found: $viewPath");
        }

        ob_start();
        extract($data, EXTR_SKIP);
        include $viewPath;

        return ob_get_clean();
    }

    private function getViewPath(string $view): string
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $this->viewDirectory.'/'.$view.'.php');
    }
}
