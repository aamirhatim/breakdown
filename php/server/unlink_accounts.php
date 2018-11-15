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
foreach($accounts as $a) {
    // Remove all transactions for the account
    drop_account_transactions($a['bank_account_id']);

    // Remove bank account
    drop_bank_account($a['bank_account_id']);

    // Unlink access token from Plaid
    call_plaid_service($access_token, 'remove', $a['bank_account_id']);

    // Remove item and access token
    drop_item($access_token);
}
$link->close();
echo '<h3>All accounts removed</h3><br>';
?>
