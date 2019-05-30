<?php

include_once 'classes/Route.php';

// USER
$user = new Route('user');

$user->get([
  'email'
],[
  "administrator"
]);

$user->post([
  'email'
],[
  "administrator"
]);

$user->put([
  'email',
  'password',
  'role'
],[
  "administrator"
]);

$user->delete([
  "administrator"
]);

// POST
$post = new Route('post');

$post->get([
  'title',
  'photo',
  'body'
]);

?>
