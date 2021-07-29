<?php
namespace System\Router;

final class Route {
    private string $method, $uri;
    private $callback;
    private array $methods = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'];
    private array $matches;

    public function __construct(string|array $method, string $uri, callable|array|string $callback) {
        $this->method = $this->validateMethods($method);
        $this->uri = $uri;
        $this->callback = $callback;
        $this->matches = explode('/', $uri);
    }
    private function checkMethodInArray(string $method) {
        $methodTranformed = strtoupper($method);
        if (in_array($methodTranformed, $this->methods)) {
            return $methodTranformed;
        }
        throw new \Exception("Method not found.");
    }
    private function validateMethods(string|array $input): string|array {
        if (gettype($input) === 'array') {
            $output = [];
            foreach ($input as $index => $method) {
                $output[] = $this->checkMethodInArray($method);
            }
            return $output;
        }
        if (gettype($input) === 'string') {
            return $this->checkMethodInArray($input);
        }
    }
    public function getMethod(): string|array {
        return $this->method;
    }
    public function getUri(): string {
        return $this->uri;
    }
    public function getUriMatches(): array {
        return $this->matches;
    }
    public function getCallback(): callable {
        return $this->callback;
    }
}