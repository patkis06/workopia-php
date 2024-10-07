<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class UserController
{
  protected $db;

  public function __construct()
  {
    $config = require base_path('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Login a user
   * 
   * @return void
   */
  public function login()
  {
    load_view('auth/login');
  }

  /**
   * Register a user
   * 
   * @return void
   */
  public function create()
  {
    load_view('auth/register');
  }
}
