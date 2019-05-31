<?php

$user = new Route('user');
$user->get(['email'], [1]);
$user->post(['email'], [1]);
$user->put(['email'], [1]);
$user->delete([1]);

?>
