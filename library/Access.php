<?php

function Access($roles)
{
  if (count($roles) > 0) {
    if (!is_array(GetHeader('token'))) {
      $token = GetHeader('token');

      $dbh = new DatabaseHelper();

      if ($dbh->doesTableExists('user')) {
        $user = $dbh->selectByExactCondition('user', ['token' => $token]);

        if (isset($user[0])) {
          return in_array($user[0]['role'], $roles);
        }
      }

    }

    return false;
  } else {
    return true;
  }
}

?>
