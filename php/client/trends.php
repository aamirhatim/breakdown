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

// Show message to direct users to link bank account if no bank accounts found
$num_accounts = sizeof(get_all_accounts($_SESSION['id']));
if($num_accounts == 0){
    // Redirect to empty account page
    header('location: http://budget.aamirhatim.com/php/client/no_accounts.php');
}

?>

<!DOCTYPE html>
<html>
  <head>
    <base href = 'http://budget.aamirhatim.com/' />
    <title>Trends</title>
    <link rel = 'stylesheet' href = 'css/style.css'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js'></script>
    <script src = 'js/chart_test.js'></script>
  </head>

  <body>
    <nav>
      <?php require(__DIR__.'/navbar.php'); ?>
    </nav>

    <main>
      <div class = 'sidebar'></div>

      <div class = 'main-content'>
        <div id = 'test'></div>
        <div id = 'chart-test' style= 'width:500px; height:500px'>
          <canvas id="myChart">
            <script>plot_chart()</script>
          </canvas>
        </div>
      </div>

      <div class = 'sidebar'></div>
    </main>
  </body>

</html>
