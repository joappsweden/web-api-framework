<?php

/**
 * Database
 */
class Database
{
  private $connection;
  private $models;

  function __construct()
  {
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'test';

    try {
      $this->connection = new PDO(
        "mysql:host=$servername;dbname=$database;charset=utf8",
        $username,
        $password
      );
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }

    $this->models = unserialize(file_get_contents('./serialized_models.text'));

    foreach ($this->models as $class => $properties) {
      $columns = '';

      foreach ($properties->getProperties() as $name => $property) {
        $columns .= $name . " ";
        $columns .= $property['type'];

        if (isset($property['character'])) {
          $columns .= "(" . $property['character'] . ")";
        }

        if (isset($property['autoIncrement']) && $property['autoIncrement']) {
          $columns .= " AUTO_INCREMENT";
        }

        if (isset($property['primaryKey']) && $property['primaryKey']) {
          $columns .= " PRIMARY KEY";
        }

        $columns .= ", ";
      }
      $columns = trim($columns, ", ");

      $sql = "CREATE TABLE IF NOT EXISTS $class ($columns);";
      $this->query($sql);
    }
  }

  public function create($table, $data)
  {
    if (isset($this->models[$table])) {
      return 'insert '.json_encode([$table, $data]);
    }
  }

  public function read($table, $id, $accept)
  {
    if (isset($this->models[$table])) {
      if ($id != 0 && is_numeric($id)) {
        return 'read'.json_encode([$table, $id]);
      } else {
        return 'read one'.json_encode([$table]);
      }
    }
  }

  public function update($table, $data, $id)
  {
    if (isset($this->models[$table]) && is_numeric($id)) {
      return 'update'.json_encode([$table, $data, $id]);
    }
  }

  public function delete($table, $id)
  {
    if (isset($this->models[$table]) && is_numeric($id)) {
      return 'delete'.json_encode([$table, $id]);
    }
  }

  private function query($sql, $data=[])
  {
    $statement = $this->connection->prepare($sql);
    $result = $statement->execute($data);

    if (strpos('select', strtolower($sql)) !== false) {
      return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    return $result;
  }
}


?>
