<?php

define('DB_SERVER', 'server183.web-hosting.com');
define('DB_USERNAME', 'aamixvks');
define('DB_PASSWORD', '92AWrfiB_UGJw');
define('DB_NAME', 'aamixvks_budget');

// $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$link = new PDO("mysql:host=DB_SERVER;dbname=DB_NAME", DB_USERNAME, DB_PASSWORD, $options);

// if($link === false){
//     die("ERROR: Could not connect. " . mysqli_connect_error());
// }

?>