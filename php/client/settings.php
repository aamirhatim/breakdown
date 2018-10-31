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
    <title>Settings</title>
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

      <div class = 'main-content'>
        <div id = 'test'></div>
        <div id = 'accounts-container'>
          <h1>Accounts</h1>
          <template id = 'account-group-template'>
              <div id = '' class = 'item'>
                  <div class = 'account-group-name'></div>
                  <button id = '' class = 'unlink-account-group-button'>Unlink</button>
              </div>
          </template>

          <template id = 'account-card-template'>
            <div class = 'account-card'>
              <div class = 'bank-account-name'></div>
              <button id = '' class = 'account-toggle-button'></button>
            </div>
          </template>

          <?php
          // Get list of all item ids for a user
          $items = get_all_items();
          $items->bind_result($item_id, $institution);
          while($items->fetch()) {
              echo '<script>create_account_group("' . $item_id . '","' . $institution . '");</script>';
              // Populate group with accounts
              $accounts = get_item_accounts($item_id);
              $accounts->bind_result($bank_name, $bank_id, $status);
              while ($accounts->fetch()) {
                  echo '<script>create_account_card("' . $item_id . '","' . $bank_name . '","' . $bank_id . '","' . $status . '");</script>';
              }
          }
          ?>

          <button id = "link-button">Link Account</button>
          <!-- <button id = 'link-update-button'>Update Credentials</button> -->
        </div>
      </div>

      <div class = 'sidebar'></div>
    </main>
  </body>

</html>
