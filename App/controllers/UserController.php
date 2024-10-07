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
   * Show login view
   * 
   * @return void
   */
  public function login()
  {
    load_view('auth/login');
  }

  /**
   * Show registration view
   * 
   * @return void
   */
  public function create()
  {
    load_view('auth/register');
  }

  /**
   * Register a user
   * 
   * @return void
   */
  public function store()
  {
    $allowed = ['name', 'email', 'city', 'state', 'password', 'password_confirmation'];

    $data = array_map('sanitize', array_intersect_key($_POST, array_flip($allowed)));

    $errors = [];

    foreach (array_keys($data) as $field) {
      if (empty($data[$field])) {
        $errors[$field] = 'The ' . ucfirst($field) . ' field is required';
      }
    }

    $string_fields = ['name', 'city', 'state', 'password', 'password_confirmation'];

    foreach ($string_fields as $field) {
      if (!Validation::string($field)) {
        $errors[$field] = 'The ' . ucfirst($field) . ' field is not a string';
      }
    }

    if (!Validation::email($data['email'])) {
      $errors += ['email' => 'The Email field is not an Email'];
    }

    if (!Validation::email_exists($data['email'], $this->db)) {
      $errors += ['email' => 'The Email already exists'];
    }

    if (!Validation::match($data['password'], $data['password_confirmation'])) {
      $errors['password'] = 'The Passwords do not match';
    }

    if (!empty($errors)) {
      load_view('auth/register', ['errors' => $errors, 'data' => $data]);
      return;
    } else {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
      unset($data['password_confirmation']);

      $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $data);

      redirect();
    }
  }
}
