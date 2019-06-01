<?php

$user = new Route('user');
$user->get(['id', 'email'], [0]);
$user->post(['email'], [1]);
$user->put(['email'], [1]);
$user->put(['role'], [0]);
$user->delete([1]);

$post = new Route('file');
$post->get(['path'], [0,1]);
$post->post(['path'], [0,1]);
$post->put(['path'], [0,1]);
$post->delete([1]);

?>
