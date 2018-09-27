<?php

function get_all_accounts() {
    include('db_config.php');
    if ($sql = $link->prepare('SELECT account_name, institution FROM tokens WHERE account_id = ?')) {
        $sql->bind_param('i', $account_id);
        $account_id = $_SESSION['id'];
        if ($sql->execute()) {
            // $sql->bind_result($account_name, $institution);
            return $sql;
        }
    }

    $link->close();
}

?>