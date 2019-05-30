<?php

/**
 * Database
 */
class Database
{
  protected $connection;

  function __construct()
  {
    $host = Environment('mysql.host');
    $database = Environment('mysql.database');
    $username = Environment('mysql.username');
    $password = Environment('mysql.password');

    try {
      $this->connection = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8",
        $username,
        $password
      );
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      Response([
        "error" => "Connection failed: " . $e->getMessage()
      ]);
    }
  }

  protected function query($sql, $data=[])
  {
    $statement = $this->connection->prepare($sql);
    $result = $statement->execute($data);

    if (strpos(strtolower($sql), 'select') !== false) {
      return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    return $result;
  }
}


?>
