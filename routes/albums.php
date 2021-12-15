<?php

include_once __DIR__ . '/../models/album.php';
include_once __DIR__ . '/../utilities/helpers.php';

function albums_route()
{
  // Get the request method
  $req_method = $_SERVER['REQUEST_METHOD'];

  $album = new Album();
  define('ROUTENAME', 'albums');
  
  $last_path_element = url_get_last_element();


  switch ($req_method) {
    case 'GET':
      // get all
      if ($last_path_element === ROUTENAME) {

        // TODO: pagination stuff
        $results = $album->get_all_albums();
      } else {
        $results = $album->get_album($last_path_element);
      }

      echo json_encode($results);

      break;

    case 'POST':
      // $is_put_request = has_id_field('TrackId');

      // if($is_put_request) {
      //   $results = $track->update_track($_POST);
      // }else{
      //   $results = $track->create_track($_POST);
      // }

      // echo json_encode($results);

      break;

    case 'PUT':
      echo 'PLEASE USE POST - But include id';
      break;

    case 'DELETE':
      // $id = url_get_last_element();
      // $results = $track->delete_track($id);
      // echo $results;
      break;

    default:
      echo 'Hit default in switch - error';
  }
}
