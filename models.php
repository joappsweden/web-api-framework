<?php

$dbh = new DatabaseHelper();

$user = new Model('user', ['email']);
$user->key();
$user->email('email');
$user->password('password');
$user->string('token', 40);
$user->timestamp();

$dbh->createTable($user);

?>
