<?php
namespace FriendlyRouter;

use \FriendlyRouter\Route;
use \FriendlyRouter\ViewEngine;
use \FriendlyRouter\Response;

class Router extends Route {
    private $viewEngine, $response;
    public function __construct() {
        $this->viewEngine = new ViewEngine();
        $this->response = new Response();
    }

    public function setBaseDir(string $dir) {
        $this->baseDir = DIRECTORY_SEPARATOR . $dir;
    }
    
    public function setTemplatePath(string $path) {
        $this->viewEngine->setTemplatePath($this->baseDir . DIRECTORY_SEPARATOR . $path);
    }
    
    public function set($complement) {
        $this->response[] = $complement;
    }

    public function comparePath($reqPath, $routePath) {
        $reqPath = explode('/', $reqPath);
        $routePath = explode('/', $routePath);
        $output = array();
        foreach ($reqPath as $key => $value) {
            if (isset($routePath[$key]) && $value === $routePath[$key] || isset($routePath[$key]) && preg_match('/(:)(\w+)/', $routePath[$key])) {
                $output[] = $routePath[$key];
            }
        }
        return implode('/', $output) === implode('/', $routePath) ? true : false;
    }

    public function getParams($reqPath, $routePath) {
        $reqPath = explode('/', $reqPath);
        $routePath = explode('/', $routePath);
        $output = array();
        foreach ($routePath as $key => $value) {
            $param = array();
            if (preg_match('/(:)(\w+)/', $value, $param)) {
                $output[$param[2]] = $reqPath[$key];
            }
        }
        return $output;
    }

    public function findRoute($method, $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->comparePath($path, $route['path'])) {
                return [
                    "found" => true,
                    "route" => $route,
                    "params" => $this->getParams($path, $route['path'])
                ];
                break;
            }
        }
        return [
            "found" => false,
            "route" => '',
            "params" => []
        ];
    }
    public function getRequestFiles() {
        if (isset($_FILES)) {
            header('Content-Type: application/json');
            echo json_encode($_FILES);
        }
    }
    public function wait() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = str_replace($this->baseDir, '', $_SERVER['REQUEST_URI']);
        $route = $this->findRoute($method, $path);
        $request = $_SERVER;
        $request['BASE_DIR'] = $this->baseDir;
        $request['params'] = $route['params'];
        if ($method === 'POST' || $method === 'PUT' || $method === 'DELETE') {
            $request['body'] = $_REQUEST;
            $request['body']['files'] = $this->getRequestFiles();
        }
        if ($route['found']) {
            return $route['route']['callback']($request, $this->response, $this->viewEngine);
        }
        echo "Cannot $method $path";
    }
}
