<?php

namespace Framework;

class Session
{

  /**
   * Start the session
   * 
   * @return void
   */
  public static function start()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Set a session variable
   * 
   * @param string $key
   * @param mixed $value
   * @return void
   */
  public static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  /**
   * Get a session variable
   * 
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public static function get($key, $default = null)
  {
    return $_SESSION[$key] ?? $default;
  }

  /**
   * Check if a session variable exists
   * 
   * @param string $key
   * @return bool
   */
  public static function has($key)
  {
    return isset($_SESSION[$key]);
  }

  /**
   * Clear a session variable
   * 
   * @param string $key
   * @return void
   */
  public static function clear($key)
  {
    if (self::has($key)) {
      unset($_SESSION[$key]);
    }
  }

  /**
   * Destroy the session
   * 
   * @return void
   */
  public static function destroy()
  {
    session_unset();
    session_destroy();
  }
}
