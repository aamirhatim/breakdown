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
    <title>Transactions</title>
    <link rel = 'stylesheet' href = 'css/style.css'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <script src = 'js/accounts.js'></script>
  </head>

  <body>
    <nav>
      <?php require(__DIR__.'/navbar.php'); ?>
    </nav>

    <main>
      <div class = 'sidebar'></div>

      <div class = 'main-content'>
        <div id = 'accounts-container'>
          <h1>Accounts</h1>
          <template id = 'account-card-template'>
            <div class = 'account-card'>
              <div class = 'bank-account-name'></div>
              <div class = 'bank-institution'></div>
            </div>
          </template>

          <?php
          $accounts = get_all_accounts();
          $accounts->bind_result($bank_name, $institution, $bank_id);
          while ($accounts->fetch()) {
            echo '<script>create_account_card("' . $bank_name . '","' . $institution . '");</script>';
          }
          ?>
        </div>
      </div>

      <div class = 'sidebar'></div>
    </main>
  </body>

</html>
