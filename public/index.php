<?php
use vendore\core\Router;

$url = $_SERVER['QUERY_STRING'];

require_once('/../vendor/core/Router.php');


define('ROOT', dirname(__DIR__));
define('APP', ROOT. '/app');
define('LAYOUT', 'default');


spl_autoload_register(function($class){
    $file = ROOT. '/'. str_replace('\\', '/', $class). '.php';
    if(is_file($file)){
        require_once $file;
    }
});

Router::add('^page/(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$', ['controller' => 'Page']);
Router::add('^page/(?P<alias>[a-z-]+)$', ['controller' => 'Page', 'action' => 'view']);
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?');


Router::dispatch($url);