<?php

include_once './library/Environment.php';
include_once './library/SetTimezone.php';
include_once './library/GetHeader.php';
include_once './library/Request.php';
include_once './library/Response.php';
include_once './library/Database.php';
include_once './library/DatabaseHelper.php';
include_once './library/Model.php';

SetTimezone();

$dbh = new DatabaseHelper();

$user = new Model('user', ['email']);
$user->key();
$user->email('email');
$user->password('password');
$user->string('token', 40);
$user->timestamp();

$dbh->createTable($user);

/*
$env = Environment('mysql.host');
$header = GetHeader('host');
$request = Request('email');
$response = Response(['token'=>sha1(rand(100, 999))]);

print_r($env);
print_r($header);
print_r($request);
print_r($response);
*/


/*
include_once 'schema.php';
include_once 'routes.php';
*/

?>
