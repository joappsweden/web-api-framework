<?php

function Response($data)
{
  $charset = Environment('app.charset');
  header("Content-type: application/json; charset=$charset");
  die(json_encode($data));

  //print_r($data);
}

?>
