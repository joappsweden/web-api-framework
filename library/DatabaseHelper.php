<?php

/**
 * DatabaseHelper
 */
class DatabaseHelper extends Database
{
  public function syncTables()
  {
    // code...
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
