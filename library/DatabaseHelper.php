<?php

/**
 * DatabaseHelper
 */
class DatabaseHelper extends Database
{
  private $models;

  public function addModel(Model $model)
  {
    $this->models[] = $model;
  }

  public function syncModelsWithDatabase()
  {
    foreach ($this->models as $model) {
      print_r($model);
    }
  }

  public function createTable(Model $model)
  {
    $table = $model->getName();
    $properties = $model->getProperties();

    $columns = "";

    foreach ($properties as $name => $property) {
      $columns .= "$name";
      $columns .= " ".strtoupper($property['type']);

      if (isset($property['characters'])) {
        $columns .= "(".$property['characters'].")";
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

    $sql = "CREATE TABLE IF NOT EXISTS $table ($columns);";
    $createTable = $this->query($sql);

    Response([
      "result" => $createTable
    ]);
  }

  public function insert($table, $data)
  {
    if ($this->getColumn($table, 'createdAt') !== NULL) {
      $data['createdAt'] = date('Y-m-d H:i:s');
    }

    $keys = "";
    $labels = "";

    foreach ($data as $key => $value) {
      $keys .= "$key, ";
      $labels .= ":$key, ";
    }

    $keys = trim($keys, ", ");
    $labels = trim($labels, ", ");

    $sql = "INSERT INTO $table ($keys) VALUES ($labels);";
    $insert = $this->query($sql, $data);

    Response([
      "result" => $insert
    ]);
  }

  public function selectAll($table, $columns=[])
  {
    $selectColumns = "";

    if (count($columns) > 0) {
      foreach ($columns as $column) {
        $selectColumns .= "$column, ";
      }

      $selectColumns = trim($selectColumns, ", ");
    } else {
      $selectColumns = "*";
    }

    $sql = "SELECT $selectColumns FROM $table;";
    $select = $this->query($sql);

    Response($select);
  }

  public function selectById($table, $id)
  {
    if (is_numeric($id)) {
      Response($this->selectByExactCondition($table, ['id' => $id]));
    } else {
      Response([
        'error' => 'Id is not a number'
      ]);
    }
  }

  public function selectByExactCondition($table, $conditions, $columns=[])
  {
    $selectColumns = "";

    if (count($columns) > 0) {
      foreach ($columns as $column) {
        $selectColumns .= "$column, ";
      }

      $selectColumns = trim($selectColumns, ', ');
    } else {
      $selectColumns = "*";
    }

    $whereConditions = "";

    if (count($conditions) > 0) {
      $whereConditions = "WHERE ";

      foreach ($conditions as $key => $value) {
        $whereConditions .= "$key = :$key, ";
      }

      $whereConditions = trim($whereConditions, ", ");
    }

    $sql = "SELECT $selectColumns FROM $table $whereConditions;";
    $select = $this->query($sql, $conditions);

    Response($select);
  }

  public function selectBySearchCondition($table, $conditions, $columns=[])
  {
    $whereConditions = "";

    if (count($conditions) > 0) {
      $whereConditions = "WHERE ";

      foreach ($conditions as $key => $value) {
        $whereConditions .= "$key LIKE :$key, ";
      }

      $whereConditions = trim($whereConditions, ", ");
    }

    $sql = "SELECT $selectColumns FROM $table $whereConditions;";

    foreach ($conditions as $key => $value) {
      $conditions[$key] = "%$value%";
    }

    $select = $this->query($sql, $conditions);

    Response($select);
  }

  public function updateById($table, $data, $id)
  {
    if (is_numeric($id)) {
      Response([
        'result' => $this->updateByConditions($table, $data, ['id' => $id])
      ]);
    } else {
      Response([
        'error' => 'Id is not a number'
      ]);
    }
  }

  public function updateByConditions($table, $data, $conditions)
  {
    $settings = "";

    if (count($data) > 0) {
      $settings = "SET ";

      foreach ($data as $key => $value) {
        $settings .= "$key = :$key, ";
      }

      $settings = trim($settings, ", ");
    } else {
      Response([
        'error' => 'Not enough settings'
      ]);
    }

    $whereConditions = "";

    if (count($conditions) > 0) {
      $whereConditions = "WHERE ";

      foreach ($conditions as $key => $value) {
        $whereConditions .= "$key = :$key, ";
      }

      $whereConditions = trim($whereConditions, ", ");
    }

    $sql = "UPDATE $table $settings $whereConditions;";
    $update = $this->query($sql, array_merge($data, $conditions));

    Response([
      'result' => $update
    ]);
  }

  public function deleteById($table, $id)
  {
    if (is_numeric($id)) {
      $sql = "DELETE FROM $table WHERE id = :id;";
      $delete = $this->query($sql, ['id' => $id]);

      Response([
        'result' => $delete
      ]);
    } else {
      Response([
        'error' => 'Id is not a number'
      ]);
    }
  }

  private function countByCondition($table, $condition)
  {
    $whereConditions = "";

    if (count($conditions) > 0) {
      $whereConditions = "WHERE ";

      foreach ($conditions as $key => $value) {
        $whereConditions .= "$key = :$key, ";
      }

      $whereConditions = trim($whereConditions, ", ");
    }

    $sql = "SELECT COUNT(*) FROM $table $whereConditions";
    $count = $this->query($sql, $conditions);

    return $count[0]['COUNT(*)'];
  }

  private function getColumn($table, $key='')
  {
    $sql = "DESCRIBE $table";
    $columns = $this->query($sql);

    foreach ($columns as $column) {
      if ($column['Field'] === $key) {
        return $column;
      }
    }

    return $columns;
  }
}


?>
