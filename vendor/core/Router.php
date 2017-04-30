<?php

namespace vendor\core; 

class Router
{
    protected static $routes = [];

    protected static $route = [];

    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function getRoute()
    {
        return self::$route;
    }

    protected static function matchRoute($url)
    {

        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * перенаправляет URL по корректнтому маршруту
     * @param $url
     */
    public static function dispatch($url)
    {
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::upperCamelCase(self::$route['controller']);
            self::upperCamelCase($controller);
            if(class_exists($controller)){
                $cObj = new $controller;
                $action = self::lowerCamelCase(self::$route['action']).'Action';
                if(method_exists($cObj,$action)){
                    $cObj->$action();
                } else {
                    echo "Метод не найден";
                }

            } else {
                echo "Контроллер <b>$controller</b> не найден";
            }
//            var_dump(self::$route);
        } else {
            http_response_code(404);
            include '404.html';
        }
    }

    protected static function upperCamelCase($name){
        $name = str_replace('-',' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ','', $name);
        return $name;
    }
    protected static function lowerCamelCase($name){

        return lcfirst(self::upperCamelCase($name));
    }
    
}