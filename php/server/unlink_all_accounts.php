<?php
// Include db service library and Plaid service
require_once(__DIR__.'/db_service.php');
require_once(__DIR__.'/plaid_service.php');

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
?>
