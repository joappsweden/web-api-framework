<?php

function GetHeader($key='')
{
  $array = [];

  foreach (getallheaders() as $name => $value) {
    $name = strtolower(str_replace("-", ".", $name));

    if ($key === '') {
      $array[$name] = $value;
    } else {
      if ($name === $key) {
        return $value;
      }
    }
  }

  return $array;
}

?>
