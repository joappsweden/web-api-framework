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
      if (in_array($model->getName(), $this->getTable())) {
        $oldTable = $this->getColumn($model->getName());
        $newTable = $model->getProperties();
        $oldFields = [];
        $newFields = [];

        foreach ($oldTable as $field) {
          $oldFields[] = $field['Field'];
        }

        foreach ($newTable as $field => $value) {
          $newFields[] = $field;
        }

        if (count($oldTable) > count($newTable)) {
          // Remove columns
          foreach (array_diff($oldFields, $newFields) as $column) {
            $this->deleteColumn($model->getName(), $column);
          }
        } else {
          // Create columns
          foreach (array_diff($newFields, $oldFields) as $column) {
            $index = 0;

            for ($i=0; $i < count($newFields); $i++) {
              if ($newFields[$i] == $column && $i > 1) {
                $index = $i-1;
              } elseif ($newFields[$i] == $column && $i < 1) {
                $index = $i+1;
              }
            }

            $type = "";

            foreach ($newTable as $field => $value) {
              if ($field === $column) {
                $type .= strtoupper($value['type']);

                if (isset($value['characters'])) {
                  $type .= "(".$value['characters'].")";
                }

                $type .= ", ";
              }
            }

            $type = trim($type, ", ");

            $this->createColumn($model->getName(), $column, $type, $newFields[$index]);
          }
        }
      } else {
        $this->createTable($model);
      }
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
    return  $this->query($sql, $data);
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
    return $this->query($sql);
  }

  public function selectById($table, $id)
  {
    if (is_numeric($id)) {
      return $this->selectByExactCondition($table, ['id' => $id]);
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
    return $this->query($sql, $conditions);
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

    return $this->query($sql, $conditions);
  }

  public function updateById($table, $data, $id)
  {
    if (is_numeric($id)) {
      return $this->updateByConditions($table, $data, ['id' => $id]);
    } else {
      Response([
        'error' => 'Id is not a number'
      ]);
    }
  }

  public function updateByConditions($table, $data, $conditions)
  {
    if ($this->getColumn($table, 'updatedAt') !== NULL) {
      $data['updatedAt'] = date('Y-m-d H:i:s');
    }

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
    return $this->query($sql, array_merge($data, $conditions));
  }

  public function deleteById($table, $id)
  {
    if (is_numeric($id)) {
      $sql = "DELETE FROM $table WHERE id = :id;";
      return $this->query($sql, ['id' => $id]);
    } else {
      Response([
        'error' => 'Id is not a number'
      ]);
    }
  }

  public function createColumn($table, $column, $type, $after)
  {
    $sql = "ALTER TABLE $table ADD $column $type AFTER $after";
    return $this->query($sql);
  }

  public function deleteColumn($table, $column)
  {
    $sql = "ALTER TABLE $table DROP $column";
    return $this->query($sql);
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

  private function getTable()
  {
    $sql = "SHOW TABLES";
    $tables = $this->query($sql);
    $array = [];

    foreach ($tables as $table) {
      $array[] = $table['Tables_in_test'];
    }

    return $array;
  }

  public function doesTableExists($table)
  {
    return in_array($table, $this->getTable());
  }
}


?>
