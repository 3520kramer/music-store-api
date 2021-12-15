<?php

include_once __DIR__ . '/../utilities/helpers.php';
include_once __DIR__ . '/../utilities/image_fetch.php';
include_once __DIR__ . '/../models/search.php';

function search_route()
{
  // Get the request method
  $req_method = $_SERVER['REQUEST_METHOD'];

  $search = new Search();

  switch ($req_method) {
    case 'GET':
      $search_value = $_GET['value'] ?? null;

      if (!$search_value) {
        echo "ERROR - value not present in query params";
        break;
      }

      $results = $search->search($search_value);

      echo json_encode($results);
      break;

    case 'POST':
      #$url = 'https://api.deezer.com/search?q=artist:"metallica"&limit=1';

      $image_fetch = new ImageUrlFetch();
      #echo $image_fetch->get_artist_img_url('metallica');
      echo $image_fetch->get_album_art_url('Living After Midnight');

      break;

    case 'PUT':
      echo 'PLEASE USE POST - But include id';
      break;

    case 'DELETE':

      break;

    default:
      echo 'Hit default in switch - error';
  }
}
