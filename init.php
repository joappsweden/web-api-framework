<?php

include_once './library/Access.php';
include_once './library/Environment.php';
include_once './library/SetTimezone.php';
include_once './library/GetHeader.php';
include_once './library/Method.php';
include_once './library/Request.php';
include_once './library/Response.php';
include_once './library/Database.php';
include_once './library/DatabaseHelper.php';
include_once './library/Model.php';
include_once './library/Route.php';
include_once './library/Url.php';
include_once './library/Validation.php';
include_once './library/SaveModels.php';
include_once './library/GetModels.php';
include_once './library/Token.php';
include_once './library/Setup.php';
include_once './library/Upload.php';
include_once './library/GetUser.php';

SetTimezone();
Setup();
Token();

?>
