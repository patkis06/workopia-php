<?php

namespace Framework\Middleware;

use Framework\Session;

class Auth
{

  /**
   * Check if user is authenticated
   * 
   * @return bool
   */
  public function isAuthenticated()
  {
    return Session::has('user');
  }

  /**
   * Handle middleware
   * 
   * @param string $role
   * @return void
   */
  public function handle()
  {
    if (!$this->isAuthenticated()) {
      return redirect('auth/login');
    }
  }
}
