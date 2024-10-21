<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
use application\core\Router;

require "config/connection.php";
require "core/Router.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = $_SERVER["REQUEST_URI"];

$router = new Router();
$router->run();