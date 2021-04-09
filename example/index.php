<?php
require_once '../autoload.php';

use \FriendlyRouter\Router;

$router = new Router();
$router->setBaseDir('friendly-router/example');
$router->setTemplatePath('views');

$router->get('/', function($req, $res, $layout) {
    $layout->render([
        "title" => "Página principal",
        "content" => $layout->viewPath('home/content'),
        "ip_address" => $req['REMOTE_ADDR'],
        "country" => "Argentina"
    ]);
});

$router->get('/user/:id', function($req, $res) {
    $res->content("Id del usuario: {$req['params']['id']}");
    $res->send();
});

$router->wait();