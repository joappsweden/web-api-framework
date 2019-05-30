<?php

function SetTimezone()
{
  date_default_timezone_set(Environment('app.timezone'));
}

?>
