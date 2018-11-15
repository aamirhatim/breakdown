<?php

// Load Plaid service function
include(__DIR__.'/plaid_service.php');

// Initialize session
session_start();


// Get public token and metadata
$public_token = htmlspecialchars($_POST['token']);
$meta = $_POST['meta'];

// Iterate through all selected accounts
foreach ($meta['accounts'] as $requested_account) {
    // Check if account has already been added
    $account_exists = 0;
    require(__DIR__.'/db_config.php');
    if($sql = $link->prepare(
                            'SELECT
                                COUNT(b.account_id)
                            FROM
                                bank_accounts b
                            JOIN items i ON
                                b.item_id = i.item_id
                            WHERE b.account_id = ?
                            AND b.account_mask = ?
                            AND b.account_name = ?
                            AND i.institution = ?')) {
        $sql->bind_param('isss', $account_id, $account_mask, $account_name, $account_institution);
        $account_id = $_SESSION['id'];
        $account_mask = $requested_account['mask'];
        $account_name = $requested_account['name'];
        $account_institution = $meta['institution']['name'];
        if ($sql->execute()) {
            $sql->bind_result($count);
            $sql->fetch();
            if ($count > 0) {
                echo '<h3>This bank account has already been linked!</h3>';
                $account_exists = 1;
            }
        }
    }
    $link->close();

    // If account does not exist, add it to the database
    if (!$account_exists) {
        // Exchange public token to create an item
        $exchange = call_plaid_service($public_token, 'exchange');
        $access_token = $exchange['access_token'];
        $item_id = $exchange['item_id'];

        // Add bank account info
        require(__DIR__.'/db_config.php');
        if($sql = $link->prepare(
                                'INSERT INTO bank_accounts
                                    (account_id,
                                    bank_account_id,
                                    account_mask,
                                    account_name,
                                    item_id)
                                VALUES (?, ?, ?, ?, ?)')) {
            $sql->bind_param('issss', $account_id, $bank_account_id, $account_mask, $account_name, $item_id);
            $account_id = $_SESSION['id'];
            $bank_account_id = $requested_account['id'];
            $account_mask = $requested_account['mask'];
            $account_name = $requested_account['name'];
            if($sql->execute()) {
                echo '<h3>New bank account added!</h3>';
                $account_added = 1;
            }
        }
        $link->close();
    }
}

// Add item info if an account was added
if($account_added) {
    require(__DIR__.'/db_config.php');
    if($sql = $link->prepare(
                            'INSERT INTO items
                                (account_id,
                                access_token,
                                item_id,
                                institution)
                            VALUES (?, ?, ?, ?)')) {
        $sql->bind_param('isss', $account_id, $access_token, $item_id, $institution);
        $account_id = $_SESSION['id'];
        $institution = $meta['institution']['name'];
        if($sql->execute()) {
            echo '<h3>Added to Items DB</h3>';
        }
    }
    $link->close();
}

?>
