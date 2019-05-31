<?php

$dbh = new DatabaseHelper();

$user = new Model('user', ['email']);
$user->key();
$user->email('email');
$user->password('password');
$user->string('token', 40);
$user->integer('role', 1);
$user->timestamp();

$post = new Model('post');
$post->key();
$post->string('title');
$post->file('photo');
$post->text('body');
$post->timestamp();

$dbh->addModel($user);
$dbh->addModel($post);
$dbh->syncModelsWithDatabase();

?>
