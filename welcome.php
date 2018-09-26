<?php

// Init a session
session_start();

// Check if user is already logged in
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
    header("location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome!</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
        <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
    </head>

    <body>
        <h1>YOU'RE LOGGED IN</h1>
        <p><br>Click <a href = 'logout.php'>here</a> to log out.</p>

        <button id="link-button">Link Account</button>
    </body>
</html>