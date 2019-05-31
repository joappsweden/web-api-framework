<?php

function Url()
{
  return explode('/', explode('?', trim($_SERVER['REQUEST_URI'], '/'))[0]);
}

?>
