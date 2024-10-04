<?php

namespace App\Controllers;

use Error;
use Framework\Database;

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
}
