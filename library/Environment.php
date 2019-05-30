<?php

function environment($key='')
{
  $key = str_replace(".", "_", $key);
  $content = file_get_contents('./.environment');
  $properties = explode("\n", trim($content));
  $array = [];

  foreach ($properties as $property) {
    $property = trim($property);
    $keyAndValue = explode("=", $property);

    if ($key != '') {
      if (strtolower($keyAndValue[0]) === $key) {
        if (isset($keyAndValue[1])) {
          return $keyAndValue[1];
        } else {
          return NULL;
        }
      }
    } else {
      if (isset($keyAndValue[1])) {
        $array[strtolower(str_replace(".", "_", $keyAndValue[0]))] = $keyAndValue[1];
      }
    }
  }

  return $array;
}

?>
