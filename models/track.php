<?php

include_once __DIR__ . '/../database/database.php';

class Track extends Database
{
  public function get_all_tracks()
  {
    $query = <<< SQL
      SELECT * FROM track LIMIT 5
    SQL;

    $results = $this->get_all($query);
    return $results;
  }

  public function get_track($id)
  {
    $query = <<< SQL
      SELECT * FROM track WHERE Trackid = :id
    SQL;

    $params = ['id' => $id];
    $results = $this->get_one($query, $params);

    return $results;
  }

  public function create_track($track)
  {
    $query = <<< SQL
      INSERT INTO `track` (
        `Name`, `AlbumId`, `MediaTypeId`, `GenreId`, 
        `Composer`, `Milliseconds`, `Bytes`, `UnitPrice`)
      VALUES ( 
        :Name, :AlbumId, :MediaTypeId, :GenreId, 
        :Composer, :Milliseconds, :Bytes, :UnitPrice);
    SQL;
    
    $is_success = $this->create($query, $track);
    return $is_success;
  }


  public function update_track($track)
  {
    echo 'update <br>';
    echo var_dump($track);
    $query = <<< SQL
      UPDATE `track`
      SET `Name` = :Name, `AlbumId` = :AlbumId, `MediaTypeId` = :MediaTypeId, `GenreId` = :GenreId, 
        `Composer` = :Composer, `Milliseconds` = :Milliseconds, `Bytes` = :Bytes, `UnitPrice` = :UnitPrice
      WHERE `TrackId` = :TrackId;
    SQL;

    $is_success = $this->update($query, $track);
    return $is_success;
  }

  public function delete_track($id)
  {
    $query = <<< SQL
      DELETE FROM `track` WHERE Trackid = :id
    SQL;
    
    $params = ['id' => $id];
    $is_success = $this->delete($query, $params);
    return $is_success;
  }
}
