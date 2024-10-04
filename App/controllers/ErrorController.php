<?php

namespace App\Controllers;

class ErrorController
{

  /**
   * Show the 404 page.
   * 
   * @param string $message
   * @return void
   */
  public static function notFound($message = "Not found!")
  {
    http_response_code(404);

    load_view('error', ['status' => '404', 'message' => $message]);
  }

  /**
   * Show the 403 unauthorized page.
   * 
   * @param string $message
   * @return void
   */
  public static function unauthorized($message = "You are not authorized!")
  {
    http_response_code(403);

    load_view('error', ['status' => '403', 'message' => $message]);
  }
}
