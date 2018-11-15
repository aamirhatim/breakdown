<?php

// Connect to DB and include Plaid service to get transactions

require_once(__DIR__.'/db_service.php');
require_once(__DIR__.'/plaid_service.php');

// Function to update transactions for an Item. Initiated from webhook
function update_transactions($item_id, $start_date) {
    require(__DIR__.'/db_config.php');
    // Get access token of item
    $token = get_access_token($item_id);

    // Get institution of Item
    $institution = get_item_institution($item_id);

    // Get list of bank accounts associated with item_id
    if($sql = $link->prepare("SELECT account_id, bank_account_id FROM bank_accounts WHERE item_id = ?")) {
        $sql->bind_param('s', $item_id);
        if($sql->execute()) {
            $sql->bind_result($account_id, $bank_account_id);
            $sql->store_result();
        }
    }
    $trans_accounts = [];
    while($sql->fetch()) {
        array_push($trans_accounts, $bank_account_id);
    }

    // Pull transactions
    $transactions_raw = call_plaid_service($token, 'transactions', (string) $start_date);
    $total_transactions = $transactions_raw['total_transactions'];
    $transactions = $transactions_raw['transactions'];

    // See if there are any new accounts
    foreach($transactions_raw['accounts'] as $t) {
        if(!in_array($t['account_id'], $trans_accounts)) {
            // Add the new account to the database
            if($sql = $link->prepare("INSERT INTO bank_accounts (account_id, bank_account_id, account_mask, account_name, item_id) VALUES (?, ?, ?, ?, ?)")) {
                $sql->bind_param('issss', $account_id, $bank_account_id, $account_mask, $account_name, $item_id);
                $bank_account_id = $t['account_id'];
                $account_mask = $t['mask'];
                $account_name = $t['name'];
                if($sql->execute()) {
                    echo '<h3>New bank account added!</h3>';
                    $account_added = 1;
                }
            }
        }
    }

    // Check if number of returned transactions is less than the number of total transactions to be recieved
    $trans_diff = $total_transactions - count($transactions);
    while($trans_diff > 0) {
        $transactions_raw = [];
        // Get remaining transactions
        $transactions_raw = call_plaid_service($token, 'transactions', (string) $start_date, $total_transactions - $trans_diff);

        // Add new transactions to existing list
        foreach($transactions_raw['transactions'] as $t) {
            array_push($transactions, $t);
        }

        // Update trans_diff
        $trans_diff -= count($transactions_raw['transactions']);
    }

    // Add list of transactions to database
    add_transactions($transactions, $account_id);
    $sql->close();

    return (count($transactions));
}

// Adds any new transaction for a bank account to the transactions table in the database
function add_transactions($transactions, $account_id) {
    require(__DIR__.'/db_config.php');

    // Extract useful info for each transaction
    $row_count = 0;
    foreach ($transactions as $t) {
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
                    'bank_id' => $t['account_id'],
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

?>
