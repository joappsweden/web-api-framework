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

  public function key()
  {
    // code...
  }

  public function integer($name, $characters=255)
  {
    // code...
  }

  public function string($name, $characters=250)
  {
    // code...
  }

  public function email($name)
  {
    // code...
  }

  public function password($name)
  {
    // code...
  }

  public function file($name)
  {
    // code...
  }

  public function text($name)
  {
    // code...
  }

  public function datatime($name)
  {
    // code...
  }

  public function timestamp()
  {
    // code...
  }
}

?>
