<?php
namespace System\Http;

use System\Traits\Http\Cookies, System\Traits\Http\Session;

class Request {
    use Cookies, Session;

    private array $server, $resources = [];

    public function __construct() {
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
    }
    public function clearUri(string $uri, string $baseDir): string {
        $output = str_replace($baseDir, '', $uri);
        $output = str_replace('%20', '-', $output);
        $output = str_replace('%A0', '+', $output);
        return trim(htmlspecialchars($output, ENT_COMPAT, 'UTF-8'));
    }
    public function getMethod(): string {
        return $this->server('REQUEST_METHOD');
    }
    public function getUri(): string {
        return $this->clearUri($this->server('REQUEST_URI'), '');
    }
    public function setUrlParams(array $params) {
        $this->params = $params;
    }
    public function server(string $param): mixed {
        if (!isset($this->server[$param])) {
            throw new \Exception("Param in server variable not found.");
        }
        return $this->server[$param];
    }
}