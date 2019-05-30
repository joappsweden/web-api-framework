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

    // TODO: Autodetect existing models and complete them
    foreach ($this->models as $class => $properties) {
      // Create table in database
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
      $data['createdAt'] = date('Y-m-d H:i:s');
      $data = $this->filerData($table, $data);

      $columns = "";
      $values = "";

      foreach ($data as $key => $value) {
        $columns .= "$key, ";
        $values .= ":$key, ";
      }

      $columns = trim($columns, ", ");
      $values = trim($values, ", ");

      $sql = "INSERT INTO $table ($columns) VALUES ($values);";
      return $this->query($sql, $data);
    }
  }

  public function read($table, $id, $accept)
  {
    if (isset($this->models[$table])) {
      if ($id != 0 && is_numeric($id)) {
        $sql = "SELECT * FROM $table WHERE id = $id;";
        return $this->query($sql);
      } else {
        $sql = "SELECT * FROM $table";
        return $this->query($sql);
      }
    }
  }

  public function update($table, $data, $id)
  {
    if (isset($this->models[$table]) && is_numeric($id)) {
      $data['updatedAt'] = date('Y-m-d H:i:s');
      $data = $this->filerData($table, $data);

      $settings = "";

      foreach ($data as $key => $value) {
        $settings .= "$key = :$key, ";
      }

      $settings = trim($settings, ", ");

      $sql = "UPDATE $table SET $settings WHERE id = $id;";
      return $this->query($sql, $data);
    }
  }

  public function delete($table, $id)
  {
    if (isset($this->models[$table]) && is_numeric($id)) {
      $sql = "DELETE FROM $table WHERE id = $id;";
      return $this->query($sql);
    }
  }

  private function query($sql, $data=[])
  {
    $statement = $this->connection->prepare($sql);
    $result = $statement->execute($data);

    if (strpos(strtolower($sql), 'select') !== false) {
      return json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
    }

    return $result;
  }

  private function filerData($model, $data)
  {
    $filteredData = [];

    foreach ($data as $key => $value) {
      $property = $this->getProperty($model, $key);

      if ($property[$key]['validationType'] === 'email') {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $filteredData[$key] = $value;
        }
      } elseif ($property[$key]['validationType'] === 'password') {
        $filteredData[$key] = sha1($value);
      } else {
        $filteredData[$key] = $value;
      }
    }

    return $filteredData;
  }

  private function getProperty($model, $key)
  {
    foreach ($this->models as $class => $properties) {
      if ($class === $model) {
        foreach ($properties->getProperties() as $property => $settings) {
          if ($key === $property) {
            return [
              $property => $settings
            ];
          }
        }
      }
    }
  }
}


?>
