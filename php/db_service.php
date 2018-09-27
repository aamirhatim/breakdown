<?php

function get_all_accounts() {
    include('db_config.php');
    if ($sql = $link->prepare('SELECT account_name, institution FROM tokens WHERE account_id = ?')) {
        $sql->bind_param('i', $account_id);
        $account_id = $_SESSION['id'];
        if ($sql->execute()) {
            return $sql;
        }
    }
    $link->close();
}

function get_tokens() {
    include('db_config.php');
    if ($sql = $link->prepare('SELECT access_token, bank_account_id FROM tokens WHERE account_id = ? AND active = "1"')) {
        $sql->bind_param('i', $account_id);
        $account_id = $_SESSION['id'];
        if ($sql->execute()) {
            return $sql;
        }
    }
    $link->close();
}

?>