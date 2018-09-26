<?php

// Initialize a session
start_session();

// Clear all info for current session
$_SESSION = array();

// Destroy session
session_destroy();

// Redirect to main page
header(index.php);
exit;

?>