<?php
namespace FriendlyRouter;

class ViewEngine {
    private string $templatePath;
    public function setTemplatePath(string $path) {
        $this->templatePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path;
    }
    public function viewPath(string $viewPath) {
        return $this->templatePath . DIRECTORY_SEPARATOR . $viewPath . '.php';
    }
    public function render(array $view) {
        $view;
        include_once $this->templatePath . DIRECTORY_SEPARATOR . 'container.php';
    }
}