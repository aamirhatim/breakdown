<?php
// Include db service library and Plaid service
require_once(__DIR__.'/db_service.php');
require_once(__DIR__.'/plaid_service.php');


// Save item_id and get access token
$item_id = $_POST['item_id'];
require_once(__DIR__.'/db_config.php');
$access_token = get_access_token($item_id);

// Get all accounts associated with item
$accounts = get_item_accounts($item_id);
$accounts->bind_result($bank_name, $bank_id, $status);

// Remove all data for each account
while($accounts->fetch()) {
    // Remove all transactions for the account
    drop_account_transactions($bank_id);

    // Remove bank account
    drop_bank_account($bank_id);

    // Unlink access token from Plaid
    call_plaid_service($access_token, 'remove', $bank_id);

    // Remove item and access token
    drop_item($access_token);
}
$link->close();
echo '<h3>All accounts removed</h3><br>';

// HELPER FUNCTIONS //

function drop_bank_account($bank_id) {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('DELETE FROM bank_accounts WHERE bank_account_id = ?')) {
    $sql->bind_param('s', $bank_id);
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

function drop_account_transactions($bank_id) {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('DELETE FROM transactions WHERE bank_account_id = ?')) {
    $sql->bind_param('s', $bank_id);
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

function drop_item($access_token) {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('DELETE FROM items WHERE access_token = ?')) {
    $sql->bind_param('s', $access_token);
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

?>
