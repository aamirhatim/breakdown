<?php

// Start a session
session_start();

// Check if user is already logged in
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
  header("location: http://budget.aamirhatim.com/index.php");
  die();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <base href = 'http://budget.aamirhatim.com/' />
        <title>Link an Account!</title>
        <link rel = 'stylesheet' href = 'css/style.css'>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    </head>

    <body>
        <nav>
            <?php require(__DIR__.'/navbar.php'); ?>
        </nav>

        <main>
            <h2>Looks like you don't have any accounts linked! Go to your settings and add an account.</h2>
        </main>
    </body>
</html>
