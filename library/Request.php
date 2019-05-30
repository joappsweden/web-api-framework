<?php

function Request($key='')
{
  $key = str_replace(".", "_", $key);
  $data = json_decode(file_get_contents('php://input'), true);
  $array = [];

  foreach ($data as $name => $value) {
    if ($key === '') {
      $array[$name] = $value;
    } else {
      if ($key === $name) {
        return $value;
      }
    }
  }

  return $array;
}

?>
