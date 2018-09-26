<?php

// Initialize a session
session_start();

// Clear all info for current session
$_SESSION = array();

// Destroy session
session_destroy();

// Redirect to main page
header('location: index.php');
exit;

?>