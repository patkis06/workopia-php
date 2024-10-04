<?php

namespace App\Controllers;

use Error;
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
    $id = $params[1];

    $params = [
      'id' => $id
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
    $allowed = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

    $data = array_map('sanitize', array_intersect_key($_POST, array_flip($allowed)));

    $data['user_id'] = 1;

    $required = ['title', 'description', 'city', 'state', 'email'];

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
      $this->db->query('INSERT INTO listings (title, description, salary, tags, company, address, city, state, phone, email, requirements, benefits) VALUES (:title, :description, :salary, :tags, :company, :address, :city, :state, :phone, :email, :requirements, :benefits)', $data);

      // redirect('/listings');
    }
    dd($errors);

    $this->db->query('INSERT INTO listings (title, description, salary, tags, company, address, city, state, phone, email, requirements, benefits) VALUES (:title, :description, :salary, :tags, :company, :address, :city, :state, :phone, :email, :requirements, :benefits)', $data);

    $title = $_POST['title'];
    $description = $_POST['description'];
    $salary = $_POST['salary'];
    $requirements = $_POST['requirements'];
    $benefits = $_POST['benefits'];
    $company = $_POST['company'];

    $params = [
      'title' => $title,
      'description' => $description,
      'salary' => $salary,
      'requirements' => $requirements,
      'benefits' => $benefits,
      'company' => $company
    ];

    $this->db->query('INSERT INTO listings (title, description, salary, requirements, benefits, company) VALUES (:title, :description, :salary, :requirements, :benefits, :company)', $params);

    // redirect('/listings');
  }
}
