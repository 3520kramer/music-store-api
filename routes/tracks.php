<?php

include_once __DIR__ . '/../models/track.php';
include_once __DIR__ . '/../utilities/helpers.php';

function tracks_route(){
  // Get the request method
  $req_method = $_SERVER['REQUEST_METHOD'];
  
  $track = new Track();

  switch ($req_method) {
    case 'GET':
      $is_singular_get = isset($_GET['id']);

      if($is_singular_get){
        $id = $_GET['id'];
        $results = $track->get_track($id);
      }else{
        // TODO: pagination stuff
        $results = $track->get_all_tracks();
      }
      echo json_encode($results);

      break;

    case 'POST':
      $is_put_request = has_id_field('TrackId');

      if($is_put_request) {
        $results = $track->update_track($_POST);
      }else{
        $results = $track->create_track($_POST);
      }
      
      echo json_encode($results);

      break;
      
    case 'PUT':
      echo 'PLEASE USE POST - But include id';
      break;

    case 'DELETE':
      $id = url_get_last_element();
      $results = $track->delete_track($id);
      echo $results;
      break;

    default:
      echo 'Hit default in switch - error';
  }
}