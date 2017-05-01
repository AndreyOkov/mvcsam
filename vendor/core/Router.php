<?php
namespace vendore\core;

class Router{
    
    public static $routes = [];
    
    public static $route = [];
    
    public static function getRoutes(){
 
    }
    
    public static function getRoute(){
        
    }
    
    public static function add($pattern, $route = []){
        self::$routes[$pattern] = $route;
    }
    
    public static function matchRoute($url){
        foreach (self::$routes as $pattern => $route){
            if(preg_match("#$pattern#i", $url, $matches)){
                foreach ($matches as $k => $v){
                    if(is_string($k)){
                        $route[$k] = $v;
                    }
                }
                if( !isset($route['action'])){
                    $route['action'] = 'index';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }
    
    public static function dispatch($url){
        $url = self::removeQueryString($url);
   
        if(self::matchRoute($url)){
    

            $controller = 'app\controllers\\' . self::$route['controller'];
            if(class_exists($controller)){
                $cObj = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']). 'Action';
                if(method_exists($cObj, $action)){
                    $cObj->$action();
                    $cObj->getView();
                } else {
                    echo "Method $action not found";
                }
            } else {
                echo "Class $controller not found";
            }
        } else {
            http_response_code(404);
            include '404.html';
        }
    }
    
    public static function upperCamelCase($name){
        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }
    
    public static function lowerCamelCase($name){
        $name = self::upperCamelCase($name);
        return lcfirst($name);
    }

    /**
     * функция предназначена для отрезания явных параметров от $url
     * сначала исходный url разбивается на 2 части по &-амперсанду
     * затем делается проверка: если в нулевом элементе массива есть знак '=', то он нам не подходит и мы выбрасываем всю строку,
     * передавая пустую строку в качестве $url.
     * Если же там нет знака '=' то значит нулевой элемент массива это правильный роут и мы его возвращаем в качестве $url;
     * @param $url
     * @return string
     */
    protected static function removeQueryString($url){
        if($url){
            $params = explode('&', $url, 2);
            if(false === strpos($params[0], '=')){
                return rtrim($params[0], '/');
            }else{
                return '';
            }
        }
    }
}