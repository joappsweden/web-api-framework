<?php

function GetModels()
{
  return unserialize(file_get_contents('./.models'));
}

?>
