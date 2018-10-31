<?php
// Initialize a session
session_start();

// CLIENT SIDE FUNCTIONS //

// Returns list of all accounts for a user
// Need to be logged in for this function to work
function get_all_accounts() {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('SELECT account_name, institution, bank_account_id, active FROM bank_accounts WHERE account_id = ? ORDER BY account_name')) {
    $sql->bind_param('i', $account_id);
    $account_id = $_SESSION['id'];
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

function get_tokens() {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('SELECT access_token, bank_account_id FROM bank_accounts WHERE account_id = ? AND active = "1"')) {
    $sql->bind_param('i', $account_id);
    $account_id = $_SESSION['id'];
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

// Returns list of user's transactions
// Need to be logged in for this function to work
function get_transactions() {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('SELECT bank_account_id, amount, transaction_name, date, categories FROM transactions WHERE account_id = ? ORDER BY date DESC')) {
    $sql->bind_param('i', $account_id);
    $account_id = $_SESSION['id'];
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

// Returns the account name for a given active bank id
function get_bank_name($bank_id) {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('SELECT account_name FROM bank_accounts WHERE bank_account_id = ? AND active = "1"')) {
    $sql->bind_param('s', $bank_id);
    if ($sql->execute()) {
      $sql->bind_result($bank_name);
      $sql->store_result();
      if ($sql->num_rows == 1) {
        $sql->fetch();
        return $bank_name;
      }
    }
  }
  $link->close();
}

function get_bank_account_status($bank_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare('SELECT active FROM bank_accounts WHERE bank_account_id = ? AND account_id = ?')) {
      $sql->bind_param('si', $bank_id, $account_id);
      $account_id = $_SESSION['id'];
      if ($sql->execute()) {
        $sql->bind_result($status);
        $sql->store_result();
        if ($sql->num_rows == 1) {
          $sql->fetch();
          return $status;
        }
      }
    }
    $link->close();
}

// SERVER SIDE FUNCTIONS //

// Sets the status of an item given an item id
function set_item_status($item_id, $status) {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare("UPDATE items SET status = ? WHERE item_id = ?")) {
    $sql->bind_param('ss', $status, $item_id);
    $sql->execute();
  }
  $link->close();
}

// Returns access token for an item id
function get_access_token($item_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare("SELECT access_token FROM items WHERE item_id = ?")) {
        $sql->bind_param('s', $item_id);
        if($sql->execute()) {
            $sql->bind_result($access_token);
            $sql->store_result();
            if($sql->num_rows == 1) {
                $sql->fetch();
                return $access_token;
            }
        }
    }
    $link->close();
}

?>
