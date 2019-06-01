<?php

function Setup()
{
  $models = GetModels();
  $userModelExists = false;

  foreach ($models as $model) {
    if ($model->getName() === 'user') {
      $userModelExists = true;
      break;
    }
  }

  if ($userModelExists) {
    $dbh = new DatabaseHelper();
    $lookForUsers = $dbh->selectAll('user');

    if (count($lookForUsers) === 0) {
      $createSuperUser = $dbh->insert('user', [
        'email' => Environment('setup.super.email'),
        'password' => Environment('setup.super.password'),
        'role' => 1
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
