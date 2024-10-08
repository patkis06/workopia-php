<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

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

      $last_id = $this->db->lastInsertId();

      SESSION::set('user', [
        'id' => $last_id,
        'name' => $data['name'],
        'email' => $data['email'],
        'city' => $data['city'],
        'state' => $data['state']
      ]);

      redirect();
    }
  }

  /**
   * Login a user
   * 
   * @return void
   */
  public function authorize()
  {
    $allowed = ['email', 'password'];

    $data = array_map('sanitize', array_intersect_key($_POST, array_flip($allowed)));

    $errors = [];

    foreach (array_keys($data) as $field) {
      if (empty($data[$field])) {
        $errors[$field] = 'The ' . ucfirst($field) . ' field is required';
      }
    }

    $string_fields = ['email', 'password'];

    foreach ($string_fields as $field) {
      if (!Validation::string($field)) {
        $errors[$field] = 'The ' . ucfirst($field) . ' field is not a string';
      }
    }

    $user = $this->db->query('SELECT * FROM users WHERE email = :email', ['email' => $data['email']])->fetch();
    if (!$user) {
      $errors += ['email' => 'The Email does not exist'];
    }

    if (!password_verify($data['password'] ?? '', $user->password ?? '')) {
      $errors += ['password' => 'The Password is incorrect'];
    }

    if (!empty($errors)) {
      load_view('auth/login', ['errors' => $errors, 'data' => $data]);
      return;
    } else {
      SESSION::set('user', [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'city' => $user->city,
        'state' => $user->state
      ]);

      redirect();
    }
  }

  /**
   * Logout a user
   * 
   * @return void
   */
  public function destroy()
  {
    Session::destroy();
    redirect();
  }
}
