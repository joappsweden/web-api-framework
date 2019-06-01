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
    if (Access($rolesToAccess)) {
      if (Method() === 'post' && $this->name === $this->controller) {
        if ($this->dbh->doesTableExists($this->controller)) {
          if (count($this->data) > 0) {
            $post = $this->dbh->insert($this->controller, $this->data);

            Response([
              'result' => $post
            ]);
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
    } else {
      Response([
        'error' => 'No access'
      ]);
    }
  }

  public function get($fieldsToShow, $rolesToAccess)
  {
    if (Access($rolesToAccess)) {
      if (Method() === 'get' && $this->name === $this->controller) {
        if ($this->dbh->doesTableExists($this->controller)) {
          if (isset($this->id)) {
            $get = $this->dbh->selectById($this->controller, $this->id, $fieldsToShow);

            Response([
              'result' => $get
            ]);
          } else {
            $get = $this->dbh->selectAll($this->controller, $fieldsToShow);

            Response([
              'result' => $get
            ]);
          }
        } else {
          Response([
            'error' => 'Model was not found'
          ]);
        }
      }
    } else {
      Response([
        'error' => 'No access'
      ]);
    }
  }

  public function put($acceptedFields, $rolesToAccess)
  {
    if (Access($rolesToAccess)) {
      if (Method() === 'put' && $this->name === $this->controller) {
        if ($this->dbh->doesTableExists($this->controller)) {
          if (isset($this->id)) {
            if (count($this->data) > 0) {
              $put = $this->dbh->updateById($this->controller, $this->data, $this->id);

              Response([
                'result' => $put
              ]);
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
    } else {
      Response([
        'error' => 'No access'
      ]);
    }
  }

  public function delete($rolesToAccess)
  {
    if (Access($rolesToAccess)) {
      if (Method() === 'delete' && $this->name === $this->controller) {
        if ($this->dbh->doesTableExists($this->controller)) {
          if (isset($this->id)) {
            $delete = $this->dbh->deleteById($this->controller, $this->data);

            Response([
              'result' => $delete
            ]);
          }
        } else {
          Response([
            'error' => 'Model was not found'
          ]);
        }
      }
    } else {
      Response([
        'error' => 'No access'
      ]);
    }
  }
}


?>
