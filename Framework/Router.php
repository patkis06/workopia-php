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
  public function route($uri, $method)
  {
    foreach ($this->routes as $route) {
      if ($route['uri'] === $uri && $route['method'] === $method) {
        $controller = "App\\Controllers\\{$route['controller']}";
        $controller = new $controller;
        $action = $route['action'];

        $controller->$action();

        return;
      }
    }

    ErrorController::notFound();
  }
}
