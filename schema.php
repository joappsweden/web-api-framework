<?php

include_once 'classes/Schema.php';
include_once 'classes/Model.php';

// USER
$user = new Model('user');
$user->key();
$user->email('email');
$user->password('password');
$user->string('token', 40);
$user->string('role');
$user->timestamp();

// POST
$post = new Model('post');
$post->key();
$post->string('title');
$post->file('photo');
$post->text('body');
$post->timestamp();

// SCHEMA
$schema = new Schema();
$schema->add($user);
$schema->add($post);
$schema->syncronize();

?>
