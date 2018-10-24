<?php
require_once(__DIR__.'/server/update_transactions.php');

// Get webhook info
$payload = @file_get_contents("php://input");
$json_hook = json_decode($payload, true);
$hook_type = $json_hook['webhook_type'];
$hook_code = $json_hook['webhook_code'];
$hook_item_id = $json_hook['item_id'];

// Transaction updates
if($hook_type == 'TRANSACTIONS') {
  if($hook_code == 'INITIAL_UPDATE') {
    // Get transactions for the past 30 days
    $date = date("Y-m-d");
    $date_mod = strtotime($date . ' -30 days');
    $check_date = date('Y-m-d', $date_mod);

    // Run transaction update for the account associated with the item_id
    update_transactions($hook_item_id, $check_date);
    mail("aamir300@gmail.com", "Webhook", $payload);
  }
  else if($hook_code == 'HISTORICAL_UPDATE') {
    // Do historical update
    mail("aamir300@gmail.com", "Webhook", $payload);
  }
  else if($hook_code == 'DEFAULT_UPDATE') {
    // Get transactions for the past 30 days
    $date = date("Y-m-d");
    $date_mod = strtotime($date . ' -10 days');
    $check_date = date('Y-m-d', $date_mod);

    // Run transaction update for the account associated with the item_id
    update_transactions($hook_item_id, $check_date);
    mail("aamir300@gmail.com", "Webhook", $payload);
  }
}

http_response_code(200);
?>
