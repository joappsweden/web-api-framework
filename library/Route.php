<?php

/**
 * Route
 */
class Route
{
  private $name;
  private $controller;
  private $id;
  private $data;
  private $dbh;

  function __construct($name)
  {
    $this->name = $name;
    $url = Url();

    if (isset($url[0]) && $url[0] != '') {
      $this->controller = $url[0];
    } else {
      Response([
        'error' => 'No path'
      ]);
    }

    if (isset($url[1])) {
      if (is_numeric($url[1])) {
        $this->id = $url[1];
      } else {
        Response([
          'error' => 'Id is not a number'
        ]);
      }
    }

    $this->data = Request();

    $this->dbh = new DatabaseHelper();
  }

  public function post($acceptedFields, $rolesToAccess)
  {
    if (Method() === 'post') {
      if ($this->dbh->doesTableExists($this->controller)) {
        if (count($this->data) > 0) {
          echo 'post';
        } else {
          Response([
            'error' => 'No data'
          ]);
        }
      } else {
        Response([
          'error' => 'Model was not found'
        ]);
      }
    }
  }

  public function get($fieldsToShow, $rolesToAccess)
  {
    if (Method() === 'get') {
      if ($this->dbh->doesTableExists($this->controller)) {
        if (isset($this->id)) {
          echo 'get id';
        } else {
          echo 'get';
        }
      } else {
        Response([
          'error' => 'Model was not found'
        ]);
      }
    }
  }

  public function put($acceptedFields, $rolesToAccess)
  {
    if (Method() === 'put') {
      if ($this->dbh->doesTableExists($this->controller)) {
        if (isset($this->id)) {
          if (count($this->data) > 0) {
            echo 'put';
          } else {
            Response([
              'error' => 'No data'
            ]);
          }
        }
      } else {
        Response([
          'error' => 'Model was not found'
        ]);
      }
    }
  }

  public function delete($rolesToAccess)
  {
    if (Method() === 'delete') {
      if ($this->dbh->doesTableExists($this->controller)) {
        if (isset($this->id)) {
          echo 'delete';
        }
      } else {
        Response([
          'error' => 'Model was not found'
        ]);
      }
    }
  }
}


?>
