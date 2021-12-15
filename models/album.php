<?php

include_once __DIR__ . '/../database/database.php';
include_once __DIR__ . '/../utilities/image_fetch.php';


class Album extends Database
{
  private $image_fetch;

  public function __construct()
  {
    parent::__construct();
    $this->image_fetch = new ImageUrlFetch();
  }

  public function get_all_albums()
  {
    $query = <<< SQL
      SELECT * FROM album LIMIT 5
    SQL;

    $results = $this->get_all($query);
    return $results;
  }

  public function get_album($id)
  {
    $query = <<< SQL
      SELECT track.TrackId AS trackId, track.Name AS trackTitle, 
        track.Composer AS trackComposer, track.Milliseconds AS trackTime, 
        track.Bytes AS trackSize, track.UnitPrice AS trackPrice, 
        genre.name AS trackGenre, mediatype.Name AS trackMediaType,
        album.AlbumId AS albumId, album.Title AS albumName, 
        artist.ArtistId AS artistId, artist.Name AS artistName
      FROM track
      JOIN album USING(AlbumId)
      JOIN artist USING(ArtistId)
      JOIN genre USING(GenreId)
      JOIN mediatype USING(MediaTypeId)
      WHERE album.AlbumId = :id;
    SQL;

    $params = ['id' => $id];

    $results['tracks'] = $this->get_all($query, $params);

    /* Format results */ 
    $results['album'] = [
      "albumId" => $results['tracks'][0]['albumName'],
      "albumName" => $results['tracks'][0]['albumName'],
      "artistId" => $results['tracks'][0]['artistId'],
      "artistName" => $results['tracks'][0]['albumName'],
      "imgUrl" => $this->image_fetch->get_album_art_url($results['tracks'][0]['albumName']),
    ];

    $results['tracks'] = array_map(function ($result) {
      
      return [
        "trackId" => $result['trackId'],
        "trackTitle" => $result['trackTitle']
      ];
    }, $results['tracks']);

    return $results;
  }
}
