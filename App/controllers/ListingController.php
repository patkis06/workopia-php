<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
  protected $db;

  public function __construct()
  {
    $config = require base_path('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Show the listings page.
   * 
   * @return void
   */
  public function index()
  {
    $listings = $this->db->query('SELECT * FROM listings')->fetchAll();

    load_view('listings/index', ['listings' => $listings]);
  }

  /**
   * Show the create listing page.
   * 
   * @return void
   */
  public function create()
  {
    load_view('listings/create');
  }

  /**
   * Show a single listing
   * 
   * @return void
   */
  public function show($params)
  {
    $params = [
      'id' => $params[1]
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    if (!$listing) {
      ErrorController::notFound('Listing not found!');
      return;
    }

    load_view('listings/show', ['listing' => $listing]);
  }

  /**
   * Store a new listing
   * 
   * @return void
   */
  function store()
  {
    $allowed = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirments', 'benefits'];

    $data = array_map('sanitize', array_intersect_key($_POST, array_flip($allowed)));

    $data['user_id'] = 1;

    $required = ['title', 'description', 'city', 'state', 'email', 'salary'];

    $errors = [];

    foreach ($required as $field) {
      if (!Validation::string($field) || empty($data[$field])) {
        $errors[$field] = 'The ' . ucfirst($field) . ' field is required';
      }
    }

    if (!empty($errors)) {
      load_view('listings/create', ['errors' => $errors, 'data' => $data]);
      return;
    } else {
      $fields = [];

      foreach ($data as $field => $value) {
        $fields[] = $field;
      }

      $fields = implode(', ', $fields);

      $values = [];

      foreach ($data as $field => $value) {
        if ($value === '') {
          $data[$field] = null;
        }

        $values[] = ':' . $field;
      }

      $values = implode(', ', $values);

      $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

      $this->db->query($query, $data);

      $_SESSION['success_message'] = 'Listing created successfully!';

      redirect('listings');
    }
  }
}
