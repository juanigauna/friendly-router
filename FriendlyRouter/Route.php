<?php
namespace FriendlyRouter;

class Route {
    public array $routes = array();

    public function routes() {
        return $this->routes;
    }

    public function redirect($params, $toRedirect) {
        $this->routes[] = [
            "method" => "GET",
            "path" => $params,
            "callback" => function($req, $res) use ($toRedirect) {
                $res->send("Location: {$req['BASE_DIR']}{$toRedirect}");
            }
        ];
    }
    public function get(string $params, $callback) {
        $this->routes[] = [
            "method" => "GET",
            "path" => $params,
            "callback" => $callback
        ];
    }
    public function post(string $params, $callback) {
        $this->routes[] = [
            "method" => "POST",
            "path" => $params,
            "callback" => $callback
        ];
    }
    public function put(string $params, $callback) {
        $this->routes[] = [
            "method" => "PUT",
            "path" => $params,
            "callback" => $callback
        ];
    }
    public function delete(string $params, $callback) {
        $this->routes[] = [
            "method" => "DELETE",
            "path" => $params,
            "callback" => $callback
        ];
    }
    public function setRoutes(array $routes) {
        foreach ($routes as $route) {
            $this->routes[] = $route;
        }
    }
}