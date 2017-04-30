<?php

use vendor\core\Router;

error_reporting(-1);

define('DS', DIRECTORY_SEPARATOR);

define('WWW', __DIR__);
define('CORE', dirname(__DIR__). '/vendor/core');
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__).'/app');
$query =$_SERVER['QUERY_STRING'];

//require(ROOT.DS.'vendor/core/Router.php');

spl_autoload_register(function($class){
    $file = ROOT. DS .str_replace('\\','/',$class).'.php';
    if(is_file($file)){
        require_once $file;
    }
});

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?');


Router::dispatch($query);
