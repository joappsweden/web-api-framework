<?php

/**
 * Model
 */
class Model
{
  private $name;
  private $uniques;
  private $properties;

  function __construct($name, $uniques=[])
  {
    $this->name = $name;
    $this->uniques = $uniques;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getUniques()
  {
    return $this->uniques;
  }

  public function getProperties()
  {
    return $this->properties;
  }

  public function key()
  {
    $this->properties['id'] = [
      'type' => 'int',
      'characters' => 255,
      'autoIncrement' => true,
      'primaryKey' => true,
      'property' => 'key'
    ];

    /*
    $this->properties['id_hashed'] = [
      'type' => 'varchar',
      'characters' => 40,
      'property' => 'key_hashed'
    ];
    */
  }

  public function integer($name, $characters=255)
  {
    $this->properties[$name] = [
      'type' => 'int',
      'characters' => $characters,
      'property' => 'integer'
    ];
  }

  public function string($name, $characters=250)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'characters' => $characters,
      'property' => 'string'
    ];
  }

  public function email($name)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'characters' => 250,
      'property' => 'email'
    ];
  }

  public function password($name)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'characters' => 250,
      'property' => 'password'
    ];
  }

  public function file($name)
  {
    $this->properties[$name] = [
      'type' => 'varchar',
      'characters' => 250,
      'property' => 'file'
    ];
  }

  public function text($name)
  {
    $this->properties[$name] = [
      'type' => 'text',
      'property' => 'text'
    ];
  }

  public function datatime($name)
  {
    $this->properties[$name] = [
      'type' => 'datetime',
      'property' => 'datatime'
    ];
  }

  public function timestamp()
  {
    $this->properties['createdAt'] = [
      'type' => 'datetime',
      'property' => 'createdAt'
    ];

    $this->properties['updatedAt'] = [
      'type' => 'datetime',
      'property' => 'updatedAt'
    ];
  }
}

?>
