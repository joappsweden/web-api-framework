<?php

function Setup()
{
  $dbh = new DatabaseHelper();

  if ($dbh->doesTableExists('user')) {
    $dbh = new DatabaseHelper();
    $lookForUsers = $dbh->selectAll('user');

    if (count($lookForUsers) === 0) {
      $createSuperUser = $dbh->insert('user', [
        'email' => Environment('setup.super.email'),
        'password' => Environment('setup.super.password'),
        'role' => 0
      ]);

      if ($createSuperUser) {
        Response([
          'result' => 'Super user created'
        ]);
      }
    }
  }
}

?>
