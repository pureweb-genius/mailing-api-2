<?php

namespace application\core;

class Router {

    protected $routes = [];
    protected $params = [];

    public function __construct() {
        $arr = require __DIR__ . '/../config/routes.php';
        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    public function add($route, $params) {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match($url):bool {
        $url = strstr($url, '?', true) ?: $url;

        foreach ($this->routes as $route => $params) {

            if (preg_match($route, $url, $matches)) {

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function run(){
        $url = trim(str_replace('mailing-api','',$_SERVER['REQUEST_URI']),'/');
        if ($this->match($url)) {
            $controllerName = ucfirst($this->params['controller']).'Controller';
            $path = __DIR__ . '/../controllers/' . $controllerName . '.php';
            if (file_exists($path)) {
                require_once $path;
                if (class_exists($controllerName)) {

                    $action = $this->params['action'];


                    if (method_exists($controllerName, $action)) {
                        $controller = new $controllerName($this->params);
                        $result = $controller->$action();
                        echo json_encode($result);
                    } else {
                        echo json_encode([
                            'status' => 404,
                            'message' => 'Resource not found'
                        ]);
                    }
                }
            } else {
                echo json_encode([
                    'status' => 404,
                    'message' => 'Resource not found'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 404,
                'message' => 'Resource not found'
            ]);
        }
    }


}