<?php

function Token()
{
  $url = Url();

  if (isset($url[0]) && $url[0] === 'token') {
    $email = Request('email');
    $password = Request('password');

    $dbh = new DatabaseHelper();

    if ($dbh->doesTableExists('user')) {
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $user = $dbh->selectByExactCondition('user', [
          'email' => $email
        ],[
          'id',
          'password'
        ]);

        if (count($user) === 1) {
          $token = sha1($user[0]['id'].date('YmdHis').rand(1000,9999));

          $updateToken = $dbh->updateById('user', [
            "token" => $token
          ],$user[0]['id']);

          Response([
            'token' => $token
          ]);
        }
      }
    }
  }
}

?>
