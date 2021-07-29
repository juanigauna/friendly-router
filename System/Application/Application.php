<?php
namespace System\Application;

class Application {
    private string $baseDir;

    public function __construct(string $baseDir = '') {
        $this->baseDir = $baseDir;
    }
}