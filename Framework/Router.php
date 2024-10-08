<?php

namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Auth;

class Router
{
  protected $routes = [];

  /**
   * Register route
   * 
   * @param string $method
   * @param string $uri
   * @param string $action
   * @return void
   */
  public function register_route($method, $uri, $action, $middleware = [])
  {
    list($controller, $action) = explode('@', $action);

    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
      'action' => $action,
      'middleware' => $middleware
    ];
  }

  /**
   * Add get route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function get($uri, $controller, $middleware = [])
  {
    $this->register_route('GET', $uri, $controller, $middleware);
  }

  /**
   * Add post route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function post($uri, $controller, $middleware = [])
  {
    $this->register_route('POST', $uri, $controller, $middleware);
  }

  /**
   * Add put route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function put($uri, $controller, $middleware = [])
  {
    $this->register_route('PUT', $uri, $controller, $middleware);
  }

  /**
   * Add delete route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function delete($uri, $controller, $middleware = [])
  {
    $this->register_route('DELETE', $uri, $controller, $middleware);
  }

  /**
   * Route the request
   * 
   * @param string $uri
   * @return void
   */
  public function route($uri)
  {
    foreach ($this->routes as $route) {
      $uri_segments = explode('/', trim($uri, '/'));

      $route_segments = explode('/', trim($route['uri'], '/'));

      $matched = true;

      $method = strtoupper($_SERVER['REQUEST_METHOD']);

      if ($method === 'POST' && isset($_POST['_method'])) {
        $method = strtoupper($_POST['_method']);
      }

      if (count($uri_segments) === count($route_segments) && $method === $route['method']) {
        $params = [];

        $matched = true;

        foreach ($uri_segments as $key => $uri_segment) {
          if (strpos($route_segments[$key], '{') === false && $uri_segment !== $route_segments[$key]) {
            $matched = false;
            break;
          } else {
            $params[] = $uri_segment;
          }
        }

        if ($matched) {
          $controller = "App\\Controllers\\{$route['controller']}";
          $controller = new $controller;

          if (count($route['middleware']) > 0) {
            foreach ($route['middleware'] as $middleware) {
              $middlewareClass = "Framework\\Middleware\\{$middleware}";
              $middlewareClass = new $middlewareClass;

              $middlewareClass->handle();
            }
          }

          $action = $route['action'];

          $controller->$action($params);

          return;
        }
      }
    }

    ErrorController::notFound();
  }
}
