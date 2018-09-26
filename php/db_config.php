<?php

define('DB_SERVER', 'server183.web-hosting.com');
define('DB_USERNAME', 'aamixvks');
define('DB_PASSWORD', '92AWrfiB_UGJw');
define('DB_NAME', 'aamixvks_budget');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>