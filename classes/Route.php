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

  function __construct($controller, $uniqueKeys=[])
  {
    $this->url = explode('/', explode('?', trim($_SERVER['REQUEST_URI'], '/'))[0]);
    $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    $this->controller = $controller;
    $this->data = json_decode(file_get_contents('php://input'), true);
    $this->uniqueKeys = $uniqueKeys;

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
      !isset($this->url[1]) &&
      $this->database != NULL &&
      $this->isAllowed($roles)
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
      $this->database != NULL &&
      $this->isAllowed($roles)
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
      $this->database != NULL &&
      $this->isAllowed($roles)
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
      $this->database != NULL &&
      $this->isAllowed($roles)
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

  private function isAllowed($roles)
  {
    if (count($roles) > 0) {
      if ($this->database != null) {
        $sql = "SELECT * FROM user WHERE token = :token";
        $sqlData = [
          "token" => $this->getTokenFromHeader()
        ];

        $result = json_decode($this->database->query($sql, $sqlData));

        if (count($result) > 0) {
          if (isset($result[0]->role) && in_array($result[0]->role, $roles)) {
            return true;
          }
        }

        return false;
      }
    } else {
      return true;
    }
  }

  private function isUnique()
  {
    foreach ($this->uniqueKeys as $keys) {
      // code...
    }
  }

  private function getTokenFromHeader()
  {
    foreach (getallheaders() as $name => $value) {
      if (strtolower($name) === 'token') {
        return $value;
      }
    }
  }

  public function getToken()
  {
    if (isset($this->url[0]) && $this->url[0] === 'token') {
      if (isset($this->data['email']) && isset($this->data['password'])) {
        $sql = "SELECT * FROM user WHERE email = :email AND password = :password";
        $sqlData = [
          "email" => $this->data['email'],
          "password" => sha1($this->data['password'])
        ];
        $database = new Database();
        $result = json_decode($database->query($sql, $sqlData));
        $id = $result[0]->id;

        $token = sha1($id.date('YmdHis').rand(100,999));
        $sql = "UPDATE user SET token = :token WHERE id = :id";
        $sqlData = [
          "token" => $token,
          "id" => $id
        ];
        $gotToken = $database->query($sql, $sqlData);

        if ($gotToken === true) {
          echo json_encode($token);
        }
      }
    }
  }
}


?>
