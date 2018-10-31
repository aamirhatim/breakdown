<?php
// Include db service library
require_once(__DIR__.'/db_config.php');

if($_POST['bank_id']) {
    // Mark account as inactive
    $bank_id = $_POST['bank_id'];
    if($sql = $link->prepare("UPDATE bank_accounts SET active = ? WHERE bank_account_id = ?")) {
        $sql->bind_param('is', $status, $bank_id);
        $status = $_POST['status'];
        if($sql->execute()) {
            echo "Preference Saved";
        }
        else {
            echo $sql->error;
        }
    } else {
        echo $sql->error;
    }
}

$link->close();
?>
