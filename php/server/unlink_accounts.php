<?php
// Include db service library and Plaid service
require_once(__DIR__.'/db_service.php');
require_once(__DIR__.'/plaid_service.php');

if($_POST['remove_type'] == 1) {
  // Save bank id and get access token
  $bank_id = $_POST['bank_id'];
  include(__DIR__.'/db_config.php');
  if($sql = $link->prepare("SELECT access_token FROM bank_accounts WHERE bank_account_id = ?")) {
    $sql->bind_param('s', $bank_id);
    if($sql->execute()) {
      $sql->bind_result($access_token);
      $sql->store_result();
      if($sql->num_rows == 1) {
        $sql->fetch();
        // Remove all transactions for the account
        delete_account_transactions($bank_id);

        // Remove bank account
        delete_bank_account($bank_id);

        // Check if access token is used for other accounts
        include(__DIR__.'/db_config.php');
        if($sql = $link->prepare("SELECT bank_account_id FROM bank_accounts WHERE access_token = ?")) {
          $sql->bind_param('s', $access_token);
          if($sql->execute()) {
            $sql->bind_result($bank_id);
            $sql->store_result();
            if($sql->num_rows == 0) {
              $sql->fetch();
              // Run script to unlink the access token if an account is found
              call_plaid_service($access_token, 'remove', $bank_id);
              echo 'Token unlinked.<br>';
            }
          }
        }
        $link->close();

        echo 'Account unlinked.';
      }
    }
  }
  $link->close();

} else if($_POST['remove_type'] == 2) {
  // Get list of tokens
  $token_list = array();

  $result = get_tokens();
  $result->bind_result($token, $bank_id);
  $result->store_result();
  while ($result->fetch()) {
    // Save token to array
    $token_list[] = $token;

    // Remove all transactions for the account
    delete_account_transactions($bank_id);

    // Remove bank account
    delete_bank_account($bank_id);
  }

  // Remove duplicates (if multiple accounts link to one token) and re-index array
  $token_list_unique = array_values(array_unique($token_list));

  // Run removal script on each token to unlink account
  foreach ($token_list as $t) {
    call_plaid_service($token, 'remove', $bank_id);
  }

  echo '<h3>All accounts removed</h3><br>';
}

// HELPER FUNCTIONS //

function delete_bank_account($bank_id) {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('DELETE FROM bank_accounts WHERE bank_account_id = ?')) {
    $sql->bind_param('s', $bank_id);
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

function delete_account_transactions($bank_id) {
  include(__DIR__.'/db_config.php');
  if ($sql = $link->prepare('DELETE FROM transactions WHERE bank_account_id = ?')) {
    $sql->bind_param('s', $bank_id);
    if ($sql->execute()) {
      return $sql;
    }
  }
  $link->close();
}

?>
