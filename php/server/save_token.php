<?php

// Load Plaid service function
include(__DIR__.'/plaid_service.php');

// Initialize session
session_start();

// Connect to database
require_once(__DIR__.'/db_config.php');

// Exchange public token for access_token
$public_token = htmlspecialchars($_POST['token']);
$exchange = call_plaid_service($public_token, 'exchange');
$access_token = $exchange['access_token'];
$item_id = $exchange['item_id'];
$meta = $_POST['meta'];

// Iterate through all selected accounts
foreach ($meta['accounts'] as $requested_account) {
  // Check if account has already been added
  $account_exists = 0;
  if($sql = $link->prepare("SELECT account_id FROM bank_accounts WHERE account_id = ? AND account_mask = ? AND account_name = ?")) {
    $sql->bind_param('iss', $account_id, $account_mask, $account_name);
    $account_id = $_SESSION['id'];
    $account_mask = $requested_account['mask'];
    $account_name = $requested_account['name'];
    if ($sql->execute()) {
      $sql->store_result();
      if ($sql->num_rows > 0) {
        echo '<h3>This bank account has already been linked!</h3>';
        $account_exists = 1;
      }
    }
  }

  // If account does not exist, add it to the database
  if (!$account_exists) {
      // Add bank account info
    if($sql = $link->prepare("INSERT INTO bank_accounts (account_id, bank_account_id, account_mask, account_name, institution, access_token, item_id) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
      $sql->bind_param('issssss', $account_id, $bank_account_id, $account_mask, $account_name, $institution, $access_token, $item_id);
      $account_id = $_SESSION['id'];
      $bank_account_id = $requested_account['id'];
      $account_mask = $requested_account['mask'];
      $account_name = $requested_account['name'];
      $institution = $meta['institution']['name'];
      if($sql->execute()) {
        echo '<h3>New bank account added!</h3>';
        $account_added = 1;
      }
    }
  }
}

// Add item info if an account was added
if($account_added) {
    if($sql = $link->prepare("INSERT INTO items (account_id, access_token, item_id) VALUES (?, ?, ?)")) {
        $sql->bind_param('iss', $account_id, $access_token, $item_id);
        $account_id = $_SESSION['id'];
        if($sql->execute()) {
            echo '<h3>Added to Items DB</h3>';
        }
    }
}

$link->close();

?>
