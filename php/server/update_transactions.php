<?php

// Connect to DB and include Plaid service to get transactions

require_once(__DIR__.'/db_service.php');
require_once(__DIR__.'/plaid_service.php');

// Function to update transactions for a single bank account. Initiated from webhook
function update_transactions($item_id, $start_date) {
  require(__DIR__.'/db_config.php');
  // Get bank account id from item_id
  if($sql = $link->prepare("SELECT account_id, bank_account_id, access_token FROM bank_accounts WHERE item_id = ?")) {
    $sql->bind_param('s', $item_id);
    if($sql->execute()) {
      $sql->bind_result($account_id, $bank_account_id, $token);
      $sql->store_result();
      $sql->fetch();
    }
  }

  // Update transactions if a single row was returned
  if($sql->num_rows == 1) {
    $transactions = call_plaid_service($token, 'transactions', $bank_account_id, (string) $start_date);
    // add_transactions($transactions);
  }

  $trans = print_r($transactions, true);
  mail("aamir300@gmail.com", "Webhook", "TRANSACTIONS ADDED" . $trans);

  $sql->close();
}

// Adds any new transaction for a bank account to the transactions table in the database
function add_transactions($transactions, $account_id) {
  require(__DIR__.'/db_config.php');
  // Extract useful info for each transaction
  foreach ($transactions['transactions'] as $t) {
      $trans_loc = $t['location'];
      $trans_pending = $t['pending'];

      // Check if transaction already exists
      if ($sql = $link->prepare("SELECT transaction_id FROM transactions WHERE transaction_id = ?")) {
          $sql->bind_param('s', $transaction_id);
          $transaction_id = $t['transaction_id'];
          if ($sql->execute()) {
              $sql->bind_result($result);
              $sql->store_result();
          }
      }

      // Add transaction to database if new
      if ($sql->num_rows == 0) {
          if($sql = $link->prepare("INSERT INTO transactions (account_id, bank_account_id, transaction_id, amount, transaction_name, date, categories, address, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
              $sql->bind_param('issdsssssss', $account_id, $bank_id, $trans_id, $trans_amount, $trans_name, $trans_date, $trans_categories, $trans_address, $trans_city, $trans_state, $trans_zip);

              $categories = '';
              for ($i = 0; $i < count($t['category']) - 1; $i++) {
                  $categories .= $t['category'][$i] . ',';
              }
              $categories .= $t['category'][count($t['category']) - 1];

              // Create info array
              $trans_info = [
                  'account_id' => $account_id,
                  'bank_id' => $bank_account_id,
                  'trans_id' => $t['transaction_id'],
                  'trans_amount' => $t['amount'],
                  'trans_name' => $t['name'],
                  'trans_date' => $t['date'],
                  'trans_categories' => $categories,
                  'trans_address' => $trans_loc['address'],
                  'trans_city' => $trans_loc['city'],
                  'trans_state' => $trans_loc['state'],
                  'trans_zip' => $trans_loc['zip']
              ];

              // Fill any null values with empty string
              foreach ($trans_info as $key => $value) {
                  if (is_null($value) && empty($value)) {
                      $trans_info[$key] = '';
                  }
              }

              $account_id = (int) $trans_info['account_id'];
              $bank_id = (string) $trans_info['bank_id'];
              $trans_id = (string) $trans_info['trans_id'];
              $trans_amount = (double) $trans_info['trans_amount'];
              $trans_name = (string) $trans_info['trans_name'];
              $trans_date = (string) $trans_info['trans_date'];
              $trans_categories = (string) $trans_info['trans_categories'];
              $trans_address = (string) $trans_info['trans_address'];
              $trans_city = (string) $trans_info['trans_city'];
              $trans_state = (string) $trans_info['tran_state'];
              $trans_zip = (string) $trans_info['trans_zip'];

              if ($sql->execute()) {
                  $row_count ++;
              }
          }
      }
  }
  $sql->close();
}

// // Get tokens for each bank account the user has registered
// $result = get_tokens();
// $result->bind_result($token, $bank_account_id);
// $result->store_result();
//
// // Exit if no tokens found
// if ($result->num_rows == 0) {
//     return;
// }
//
// // Reset count for new rows
// $row_count = 0;
//
// // Get transaction info for each account
// while ($result->fetch()) {
//     // Get raw list of transactions
//     $transactions = call_plaid_service($token, 'transactions', $bank_account_id);
//
//     // Extract useful info for each transaction
//     foreach ($transactions['transactions'] as $t) {
//         $trans_loc = $t['location'];
//         $trans_pending = $t['pending'];
//
//         // Check if transaction already exists
//         if ($sql = $link->prepare("SELECT transaction_id FROM transactions WHERE transaction_id = ?")) {
//             $sql->bind_param('s', $transaction_id);
//             $transaction_id = $t['transaction_id'];
//             if ($sql->execute()) {
//                 $sql->bind_result($result);
//                 $sql->store_result();
//             }
//         }
//
//         // Add transaction to database if new
//         if ($sql->num_rows == 0) {
//             if($sql = $link->prepare("INSERT INTO transactions (account_id, bank_account_id, transaction_id, amount, transaction_name, date, categories, address, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
//                 $sql->bind_param('issdsssssss', $account_id, $bank_id, $trans_id, $trans_amount, $trans_name, $trans_date, $trans_categories, $trans_address, $trans_city, $trans_state, $trans_zip);
//
//                 $categories = '';
//                 for ($i = 0; $i < count($t['category']) - 1; $i++) {
//                     $categories .= $t['category'][$i] . ',';
//                 }
//                 $categories .= $t['category'][count($t['category']) - 1];
//
//                 // Create info array
//                 $trans_info = [
//                     'account_id' => $_SESSION['id'],
//                     'bank_id' => $bank_account_id,
//                     'trans_id' => $t['transaction_id'],
//                     'trans_amount' => $t['amount'],
//                     'trans_name' => $t['name'],
//                     'trans_date' => $t['date'],
//                     'trans_categories' => $categories,
//                     'trans_address' => $trans_loc['address'],
//                     'trans_city' => $trans_loc['city'],
//                     'trans_state' => $trans_loc['state'],
//                     'trans_zip' => $trans_loc['zip']
//                 ];
//
//                 // Fill any null values with empty string
//                 foreach ($trans_info as $key => $value) {
//                     if (is_null($value) && empty($value)) {
//                         $trans_info[$key] = '';
//                     }
//                 }
//
//                 $account_id = (int) $trans_info['account_id'];
//                 $bank_id = (string) $trans_info['bank_id'];
//                 $trans_id = (string) $trans_info['trans_id'];
//                 $trans_amount = (double) $trans_info['trans_amount'];
//                 $trans_name = (string) $trans_info['trans_name'];
//                 $trans_date = (string) $trans_info['trans_date'];
//                 $trans_categories = (string) $trans_info['trans_categories'];
//                 $trans_address = (string) $trans_info['trans_address'];
//                 $trans_city = (string) $trans_info['trans_city'];
//                 $trans_state = (string) $trans_info['tran_state'];
//                 $trans_zip = (string) $trans_info['trans_zip'];
//
//                 if ($sql->execute()) {
//                     $row_count ++;
//                 }
//             }
//         }
//     }
// }
// $sql->close();

// echo '<h3>' . $row_count . ' new transactions added.</h3>';

?>
