<?php
// Include db service library
require_once(__DIR__.'/db_service.php');

if($_POST['bank_id']) {
    // Set account status
    set_bank_account_status($_POST['bank_id'], $_POST['status']);
}

$link->close();
?>
