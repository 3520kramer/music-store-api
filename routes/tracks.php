<?php

include_once __DIR__ . '/../models/track.php';
include_once __DIR__ . '/../utilities/helpers.php';

function tracks_route()
{
  // Get the request method
  $req_method = $_SERVER['REQUEST_METHOD'];

  $track = new Track();
  define('ROUTENAME', 'tracks');
  $last_path_element = url_get_last_element();

  switch ($req_method) {
    case 'GET':

      // get all
      if ($last_path_element === ROUTENAME) {
        $query_params = $_GET ?? null;
        $ids = $query_params['ids'] ?? null;

        if (!$query_params) {
          // TODO: pagination stuff
          $results = $track->get_all_tracks();
        } else if (isset($ids)) {
          $results = $track->get_tracks($ids);
        }
      } else {
        $results = $track->get_track($last_path_element);
      }

      echo json_encode($results);

      break;

    case 'POST':
      // If id is not present in path it's a post
      $is_post_request = $last_path_element === ROUTENAME;

      if ($is_post_request) {
        $results = $track->create_track($_POST);
      } else {
        $results = $track->update_track($_POST);
      }

      echo json_encode($results);
      break;

    case 'PUT':
      echo 'PLEASE USE POST - But include id';
      break;

    case 'DELETE':
      $results = $track->delete_track($last_path_element);
      echo $results;
      break;

    default:
      echo 'Hit default in switch - error';
  }
}
