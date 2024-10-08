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
   * Check if user is the owner of a resource
   * 
   * @param int $user_id
   * @return bool
   */
  public static function isOwner($user_id)
  {
    return Session::get('user')['id'] == $user_id;
  }

  /**
   * Get the authenticated user
   * 
   * @return array
   */
  public static function user()
  {
    return Session::get('user');
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
