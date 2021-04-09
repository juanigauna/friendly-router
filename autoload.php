<?php
define('ROOT', dirname(__FILE__));
function namespaceToClassPath($namespace) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $namespace); 
    $path = ROOT . DIRECTORY_SEPARATOR . $file . '.php';
    return $path;
}
spl_autoload_register(function($namespace) {
    $path = namespaceToClassPath($namespace);
    if (!file_exists($path)) {
        throw new Exception('Error: Class not found or not work.');
    }
    require_once($path);
});