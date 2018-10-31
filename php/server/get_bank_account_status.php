<?php
require_once(__DIR__.'/db_service.php');

// $bank_info = array();
$status = get_bank_account_status($_POST['bank_account_id']);
// array_push($bank_info, $status);
// array_push($bank_info, $_POST['bank_account_id']);
$bank_info = array(
    "status" => $status,
    "bank_id" => $_POST['bank_account_id']
);

echo json_encode($bank_info);
?>
