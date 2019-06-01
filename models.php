<?php

$dbh = new DatabaseHelper();

$user = new Model('user', ['email']);
$user->key();
$user->email('email');
$user->password('password');
$user->string('token', 40);
$user->integer('role', 1);
$user->timestamp();

$file = new Model('file');
$file->key();
$file->string('name');
$file->file('path');
$file->integer('userId');
$file->timestamp();

$dbh->addModel($user);
$dbh->addModel($file);
$dbh->syncModelsWithDatabase();

?>
