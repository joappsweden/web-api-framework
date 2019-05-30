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

    Response($insert);
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
    $insert = $this->query($sql);

    Response($insert);
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
