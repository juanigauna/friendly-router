<?php
require_once './autoload.php';

use System\Router\Router, System\Http\Request, System\Http\Response;

$router = new Router(new Request, new Response, '/project');


$router->any('/', function() {
    return '/index';
});

$router->run();