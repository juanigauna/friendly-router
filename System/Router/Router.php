<?php
namespace System\Router;

use System\Http\Request, System\Http\Response, System\Traits\Http\Cookies, System\Traits\Http\Session;

class Router {
    use Cookies, Session;
    
    private array $router = [], $routerMatches, $urlParams = [];
    private string $method, $uri, $content, $baseDir;

    private Route $route;
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response, string $baseDir = '') {
        $this->request = $request;
        $this->response = $response;
        $this->method = $this->request->getMethod();
        $this->uri = str_replace($baseDir, '', $this->request->getUri());
        $this->routerMatches = explode('/', $this->uri);
        $this->baseDir = $baseDir;
    }

    public function get(string $uri, callable|array|string $callback) {
        $this->addRoute(['GET'], $uri, $callback);
    }

    public function post(string $uri, callable|array|string $callback) {
        $this->addRoute(['POST'], $uri, $callback);
    }

    public function put(string $uri, callable|array|string $callback) {
        $this->addRoute(['PUT'], $uri, $callback);        
    }
    
    public function delete(string $uri, callable|array|string $callback) {
        $this->addRoute(['DELETE'], $uri, $callback);
    }

    public function any(string $uri, callable|array|string $callback) {
        $this->addRoute(['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'], $uri, $callback);
    }

    public function addRoute(array $methods, string $uri, callable|array|string $callback) {
        foreach ($methods as $method) {
            $this->router[$method][] = new Route($method, $uri, $callback);
        }
    }

    public function getRoutes(): array {
        return $this->router;
    }

    public function setRoutes(array $routes) {
        $this->router = $routes;
    }

    public function compareMatches(array $toCompare): bool {
        $matches = 0;
        if (count($this->routerMatches) === count($toCompare)) {
            foreach ($this->routerMatches as $key => $value) {
                if ($value === $toCompare[$key] || preg_match('/(:)([\w+])/', $toCompare[$key])) {
                    $matches++;
                }
            }
        }
        return $matches === count($toCompare);
    }
    public function setHeaders(): bool {
        if (empty($this->response->getHeaders())) return false;
        foreach ($this->response->getHeaders() as $header) header($header);
        return true;
    }
    public function findRoute(): bool {
        foreach ($this->router[$this->method] as $route) {
            if ($this->compareMatches($route->getUriMatches())) {
                $this->route = $route;
                return true;
            }
        }
        return false;
    }
    public function getRouter(): array {
        return $this->router;
    }
    public function getUrlParams(): bool {
        $matches = $this->route->getUriMatches();
        foreach ($matches as $key => $value) {
            $param = [];
            if (preg_match_all('/(:)(\w+)/', $value, $param)) {
                $this->urlParams[$param[2][0]] = $this->routerMatches[$key];
            }
        }
        return true;
    }
    public function checkMethodInRouter(): bool {
        return isset($this->router[$this->method]) && !empty($this->router[$this->method]);
    }

    public function run(): bool {
        if ($this->checkMethodInRouter() && $this->findRoute()) {
            $this->getUrlParams();
            $this->request->setUrlParams($this->urlParams);
            $this->content = $this->route->getCallback()($this->request, $this->response);
            $this->setHeaders();
            echo $this->content;
            return true;
        }
        echo "{$this->method} {$this->uri} not found";
        return false;
    }
}