<?php

if($_SERVER['REQUEST_URI'] == '/camagru/config/database.php')
{
    include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/redirect.php');
}


$dbname = "camagru_db";
$DB_DSN = "mysql:host=localhost;dbname=".$dbname.";";
$DB_USER = "root";
$DB_PASSWORD = "pass";
?>
