<?php

function GetUser()
{
  if (!is_array(GetHeader('token'))) {
    $token = GetHeader('token');

    $dbh = new DatabaseHelper();

    if ($dbh->doesTableExists('user')) {
      return $dbh->selectByExactCondition('user', ['token' => $token], ['id']);
    }

  }

  return NULL;
}

?>
