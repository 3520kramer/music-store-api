<?php
include_once __DIR__ . '/utilities/helpers.php';
include_once __DIR__ . '/utilities/authenticate.php';

include_once __DIR__ . '/routes/authentication.php';
include_once __DIR__ . '/routes/tracks.php';
include_once __DIR__ . '/routes/albums.php';
include_once __DIR__ . '/routes/account.php';
include_once __DIR__ . '/routes/search.php';
include_once __DIR__ . '/environment/my-env.php';

// Initializing the static class with environment variables
Env::set_env_vars();

$url = get_url(__DIR__);

// Show the API description if path is '/' i.e. only one item in array 
if (count($url) === 1) {
  echo 'Show API description';
  return;
}

// Sets the headers of the responses
header('Content-Type: application/json');
header('Accept-version: v1');

/* AUTHORISAITION */
$headers = apache_request_headers();
$auth_header = $headers['Authorization'] ?? null;

if ($url[1] === 'auth') {
  // Need to go to switch instead?
  authentication_route();
  return;
} else if (is_null($auth_header) || !is_jwt_valid($auth_header)) {
  http_response_code(401);
  echo 'Not authorized';
  return;
}

// Router
switch ($url[1]) {
  case 'search':
    #employees_route($req_method);
    search_route();
    break;
  case 'albums':
    albums_route();
    break;
  case 'tracks':
    tracks_route();
    break;
  case 'auth':
    //sign_in_route();
    echo 'Should never hit here - auth';
    break;
  case 'account':
    account_route(__DIR__); #$req_method, $url[2]);
    break;
  default:
    // Show bad format message if it's not the right collection
    echo 'Only employees and departments collection is accessible - BAD FORMAT',  '<br>';
}
