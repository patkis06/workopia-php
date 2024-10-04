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

function load_view($name, $data = [])
{
  $view = base_path("App/views/{$name}.view.php");
  if (file_exists($view)) {
    extract($data);
    require $view;
  } else {
    echo "View {$name} not found";
  }
}


/**
 * Load partial
 * 
 * @param string $name
 * @return void
 */

function load_partial($name)
{
  $view = base_path("App/views/partials/{$name}.php");
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
 * Inspect a value('s) and die
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

/**
 * Format salary
 * 
 * @param mixed $salary
 * @return string
 */

function format_salary($salary)
{
  return '$' . number_format($salary);
}

function sanitize($data)
{
  return filter_var(trim($data), FILTER_SANITIZE_SPECIAL_CHARS);
}
