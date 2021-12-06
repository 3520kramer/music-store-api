<?php

function get_url($index_root)
{
  // Get url without parameters
  $url = strtok($_SERVER['REQUEST_URI'], "?");

  // Exclude the trailing slash from basedir if present
  $url = rtrim($url, '/');

  // Remove everything in the url which comes before the basedir
  // Allow the api to be deployed anywhere
  $url = substr($url, strpos($url, basename($index_root)));
  #$url = substr($url, strpos($url, basename(__DIR__)));

  // Split the array by '/'
  $urlPieces = explode('/', urldecode($url));
  
  return $urlPieces;
}

function url_get_last_element(){
  $last_element = explode('/', $_SERVER['REQUEST_URI']);
  return end($last_element);
}

// PHP doesn't allow column names to be inserted into prepared statements
// This function will take care of checking if the input from the url params is valid
// Inspired by: 'Your Common Sense' @ https://stackoverflow.com/a/2543144/13799636 
function is_param_allowed($value, $allowed)
{
  $is_in_array = in_array($value, $allowed);

  if (!$is_in_array) {
    throw new InvalidArgumentException("Not allowed");
  }
}

// PHP does not handle PUT parameters explicitly. 
// For this reason, they must be read from the request body’s raw data
function get_put_body(){
  return (array)json_decode(file_get_contents('php://input'),TRUE);
}

function has_id_field(string $id_field_name = 'id'): bool{
  return isset($_POST[$id_field_name]) ? true : false;
}