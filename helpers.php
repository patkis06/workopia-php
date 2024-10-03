<?php

/**
 * Get the base_path
 * 
 * @param syting $path
 * @return string
 */

function base_path($path = '')
{
  return __DIR__ . '/' . $path;
}


/**
 * Load view
 * 
 * @param string $name
 * @return void
 */

function load_view($name)
{
  $view = base_path("views/{$name}.view.php");
  if (file_exists($view))
    require $view;
}


/**
 * Load partial
 * 
 * @param string $name
 * @return void
 */

function load_partial($name)
{
  $view = base_path("views/partials/{$name}.php");
  if (file_exists($view))
    require $view;
}


/**
 * Insoect a value('s)
 * 
 * @param mixed $value
 * @return void
 */

function inspect($value)
{
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
}


/**
 * Insoect a value('s) and die
 * 
 * @param mixed $value
 * @return void
 */

function dd($value)
{
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
  die();
}
