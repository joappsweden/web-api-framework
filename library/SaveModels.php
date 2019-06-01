<?php

function SaveModels($models)
{
  file_put_contents('./.models', serialize($models));
}

?>
