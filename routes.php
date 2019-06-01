<?php

$user = new Route('user');
$user->get(['email'], [1]);
$user->post(['email'], [1]);
$user->put(['email'], [1]);
$user->delete([1]);

$post = new Route('post');
$post->get(['body'], [1]);
$post->post(['body'], [1]);
$post->put(['body'], [1]);
$post->delete([1]);

?>
