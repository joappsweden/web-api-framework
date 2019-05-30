<?php

/**
 * Model
 */
class Model
{
  private $name;
  private $properties;

  function __construct($name)
  {
    $this->name = $name;
  }

  public function key()
  {
    $this->properties['id'] = [
      'type' => 'int',
      'character' => 255,
      'autoIncrement' => true,
      'primaryKey' => true,
      'validationType' => 'key'
    ];
  }

  public function integer($name, $characters=255)
  {
    $this->properties[$name] = [
      'type' => 'int',
      'character' => $characters,
      'validationType' => 'integer'
    ];
  }

  public function string($name, $characters=250)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'character' => $characters,
      'validationType' => 'string'
    ];
  }

  public function email($name)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'character' => 250,
      'validationType' => 'email'
    ];
  }

  public function password($name)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'character' => 250,
      'validationType' => 'password'
    ];
  }

  public function file($name)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'character' => 250,
      'validationType' => 'file'
    ];
  }

  public function text($name)
  {
    $this->properties[$name] = [
      'type' => 'text',
      'validationType' => 'text'
    ];
  }

  public function datetime($name)
  {
    $this->properties[$name] = [
      'type' => 'datetime',
      'validationType' => 'datetime'
    ];
  }

  public function timestamp()
  {
    $this->properties['createdAt'] = [
      'type' => 'datetime',
      'validationType' => 'createdAt'
    ];
    $this->properties['updatedAt'] = [
      'type' => 'datetime',
      'validationType' => 'updatedAt'
    ];
  }

  public function getName()
  {
    return $this->name;
  }

  public function getProperties()
  {
    return $this->properties;
  }
}


?>
