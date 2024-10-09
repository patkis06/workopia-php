<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Middleware\Auth;

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

    $data['user_id'] = Auth::user()['id'];

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

  /**
   * Edit a single listing
   * 
   * @return void
   */
  public function edit($params)
  {
    $params = [
      'id' => $params[2]
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    if (!Auth::isOwner($listing->user_id)) {
      redirect('listing/' . $params['id']);
    }

    if (!$listing) {
      ErrorController::notFound('Listing not found!');
      return;
    }

    load_view('listings/edit', ['data' => $listing]);
  }

  /**
   * Store a new listing
   * 
   * @param array $params
   * @return void
   */
  function update($params)
  {
    $params = [
      'id' => $params[1]
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    if (!Auth::isOwner($listing->user_id)) {
      redirect('listing/' . $params['id']);
    }

    if (!$listing) {
      ErrorController::notFound('Listing not found!');
      return;
    }

    $allowed = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirments', 'benefits'];

    $data = array_map('sanitize', array_intersect_key($_POST, array_flip($allowed)));

    $required = ['title', 'description', 'city', 'state', 'email', 'salary'];

    $errors = [];

    foreach ($required as $field) {
      if (!Validation::string($field) || empty($data[$field])) {
        $errors[$field] = 'The ' . ucfirst($field) . ' field is required';
      }
    }

    if (!empty($errors)) {
      load_view('listings/edit', ['errors' => $errors, 'data' => $listing]);
      return;
    } else {
      $fields = [];
      foreach (array_keys($data) as $field) {
        $fields[] = "{$field} = :{$field}";
      }

      $fields = implode(', ', $fields);

      $query = "UPDATE listings SET $fields where id = :id";

      $data['id'] = $params['id'];

      $this->db->query($query, $data);

      $_SESSION['success_message'] = 'Listing updated successfully!';

      redirect('listing/' . $params['id']);
    }
  }

  /**
   * Delete a listing
   * 
   * @return void
   */
  public function destroy($params)
  {
    $params = [
      'id' => $params[1]
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    if (!$listing) {
      ErrorController::notFound('Listing not found!');
      return;
    }

    if (!Auth::isOwner($listing->user_id)) {
      redirect('listing/' . $params['id']);
    }

    $this->db->query('DELETE FROM listings WHERE id = :id', $params);

    redirect('listings');
  }

  /**
   * Search for listings
   * 
   * @return void
   */
  public function search()
  {
    $allowed = ['keywords', 'location'];

    $data = array_map('sanitize', array_intersect_key($_POST, array_flip($allowed)));

    $params = [
      'keywords' => '%' . $data['keywords'] . '%',
      'location' => '%' . $data['location'] . '%'
    ];

    $listings = $this->db->query('SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords) AND (city LIKE :location OR state LIKE :location)', $params)->fetchAll();

    load_view('listings/index', ['listings' => $listings]);
  }
}
