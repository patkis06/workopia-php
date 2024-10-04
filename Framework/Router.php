<?php

namespace Framework;

use App\Controllers\ErrorController;

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
  public function register_route($method, $uri, $action)
  {
    list($controller, $action) = explode('@', $action);

    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
      'action' => $action
    ];
  }

  /**
   * Add get route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function get($uri, $controller)
  {
    $this->register_route('GET', $uri, $controller);
  }

  /**
   * Add post route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function post($uri, $controller)
  {
    $this->register_route('POST', $uri, $controller);
  }

  /**
   * Add put route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function put($uri, $controller)
  {
    $this->register_route('PUT', $uri, $controller);
  }

  /**
   * Add delete route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function delete($uri, $controller)
  {
    $this->register_route('DELETE', $uri, $controller);
  }

  /**
   * Route the request
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function route($uri)
  {
    foreach ($this->routes as $route) {
      $uri_segments = explode('/', trim($uri, '/'));

      $route_segments = explode('/', trim($route['uri'], '/'));

      $matched = true;

      if (count($uri_segments) === count($route_segments) && strtoupper($_SERVER['REQUEST_METHOD'] === $route['method'])) {
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
          $action = $route['action'];

          $controller->$action($params);

          return;
        }
      }
    }

    ErrorController::notFound();
  }
}
