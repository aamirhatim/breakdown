<?php

// Start a session
session_start();

// Check if user is already logged in
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
  header("location: http://budget.aamirhatim.com/index.php");
  die();
}

// Include db service library
require_once(__DIR__.'/../server/db_service.php');

?>

<!DOCTYPE html>
<html>
  <head>
    <base href = 'http://budget.aamirhatim.com/' />
    <title>Trends</title>
    <link rel = 'stylesheet' href = 'css/style.css'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
    <script src = 'js/accounts_settings.js'></script>
  </head>

  <body>
    <nav>
      <?php require(__DIR__.'/navbar.php'); ?>
    </nav>

    <main>
      <div class = 'sidebar'></div>

      <div class = 'main-content'></div>

      <div class = 'sidebar'></div>
    </main>
  </body>

</html>
