<?php

/**
 * Schema
 */
class Schema
{
  protected $models;

  public function add(Model $model)
  {
    $this->models[$model->getName()] = $model;
  }

  public function syncronize()
  {
    return file_put_contents('./serialized_models.text', serialize($this->models));
  }
}


?>
