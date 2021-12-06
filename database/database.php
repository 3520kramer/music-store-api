<?php

/*
https://phpdelusions.net/pdo#query
Another useful mode is PDO::FETCH_CLASS, which can create an object of particular class
$news = $pdo->query('SELECT * FROM news')->fetchAll(PDO::FETCH_CLASS, 'News');
*/
class Database
{
  protected $db;

  public function __construct()
  {
    $this->db = $this->connect();
  }

  public function connect()
  {
    $host = 'localhost';
    $port = 8889;
    $db = 'chinook_abridged';
    $user = 'root';
    $pwd = 'root';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false
    ];

    try {
      $pdo = new PDO($dsn, $user, $pwd, $options);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      return;
    }
    return $pdo;
  }

  public function db_disconnect()
  {
    $this->db = null;
  }

  public function get_all(string $query)
  {
    try {
      $stmt = $this->db->query($query);
      $results = $stmt->fetchAll();
      return $results;
    } catch (Exception $e) {
      echo "ERROR fetching: \n$e";
    } finally {
      $this->db_disconnect();
    }
  }

  // Overloading is not possible in PHP - use if-else instead
  public function get_one(string $query, array $params = null)
  {
    try {
      if ($params) {
        $stmt = $this->db->prepare($query);

        $stmt->execute($params);

        $results = $stmt->fetch();
        return $results;
      } else {

        $stmt = $this->db->query($query);

        $results = $stmt->fetch();

        return $results;
      }
    } catch (Exception $e) {
      echo "ERROR fetching: \n$e";
    } finally {
      $this->db_disconnect();
    }
  }

  public function create(string $query, array $params)
  {
    try {
      $stmt = $this->db->prepare($query);
      return $stmt->execute($params);

      // $stmt->execute($params);
      // $results = $stmt->fetch();
      // return $results;

    } catch (Exception $e) {
      echo "ERROR fetching: \n$e";
    } finally {
      $this->db_disconnect();
    }
  }

  public function update(string $query, array $params)
  {
    try {
      $stmt = $this->db->prepare($query);
      return $stmt->execute($params);

      //echo $stmt;
      // $stmt->execute($params);
      // $results = $stmt->fetch();
      // return $results;

    } catch (Exception $e) {
      echo "ERROR fetching: \n$e";
    } finally {
      $this->db_disconnect();
    }
  }

  public function delete(string $query, array $params)
  {
    try {
      $stmt = $this->db->prepare($query);
      $stmt->execute($params);
      $deleted = $stmt->rowCount();
      return $deleted;
      //echo $stmt;
      // $stmt->execute($params);
      // $results = $stmt->fetch();
      // return $results;

    } catch (Exception $e) {
      echo "ERROR fetching: \n$e";
    } finally {
      $this->db_disconnect();
    }
  }
}
