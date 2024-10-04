<?php

require '../vendor/autoload.php';
require '../helpers.php';

$router = new Router();

$routes = require base_path('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
