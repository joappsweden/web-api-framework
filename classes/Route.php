<?php

include_once 'classes/Database.php';

/**
 * Route
 */
class Route
{
  private $url;
  private $controller;
  private $method;
  private $data;
  private $database;

  function __construct($controller)
  {
    $this->url = explode('/', explode('?', trim($_SERVER['REQUEST_URI'], '/'))[0]);
    $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    $this->controller = $controller;
    $this->data = json_decode(file_get_contents('php://input'), false);

    if ($this->isControllerCorrect($this->url, $this->controller)) {
      $this->database = new Database();
    }
  }

  public function post($accept, $roles=[])
  {
    if (
      $this->method === 'post' &&
      $this->isControllerCorrect($this->url, $this->controller) &&
      $this->doesKeyExists($accept, $this->data) &&
      isset($this->url[0]) &&
      $this->database != NULL
    ) {
      echo $this->database->create($this->url[0], $this->data);
    }
  }

  public function get($accept, $roles=[])
  {
    if (
      $this->method === 'get' &&
      $this->isControllerCorrect($this->url, $this->controller) &&
      isset($this->url[0]) &&
      $this->database != NULL
    ) {
      if (isset($this->url[1])) {
        echo $this->database->read($this->url[0], $this->url[1], $accept);
      } else {
        echo $this->database->read($this->url[0], 0, $accept);
      }
    }
  }

  public function put($accept, $roles=[])
  {
    if (
      $this->method === 'put' &&
      $this->isControllerCorrect($this->url, $this->controller) &&
      $this->doesKeyExists($accept, $this->data) &&
      isset($this->url[0]) &&
      isset($this->url[1]) &&
      $this->database != NULL
    ) {
      echo $this->database->update($this->url[0], $this->data, $this->url[1]);
    }
  }

  public function delete($roles=[])
  {
    if (
      $this->method === 'delete' &&
      $this->isControllerCorrect($this->url, $this->controller) &&
      isset($this->url[0]) &&
      isset($this->url[1]) &&
      $this->database != NULL
    ) {
      echo $this->database->delete($this->url[0], $this->url[1]);
    }
  }

  private function doesKeyExists($accept, $data)
  {
    if ($data != NULL) {
      foreach ($data as $key => $value) {
        return in_array($key, $accept);
      }
    }

    return false;
  }

  private function getKey($accept, $data)
  {
    if ($data != NULL) {
      foreach ($data as $key => $value) {
        if (in_array($key, $accept)) {
          return [
            $key => $value
          ];
        }
      }
    }

    return NULL;
  }

  private function isControllerCorrect($url, $controller)
  {
    return in_array($controller, $url);
  }
}


?>
