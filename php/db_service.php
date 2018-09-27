<?php
// Establish DB connection
include('db_config.php');

function get_all_accounts() {
    if ($sql = $link->prepare('SELECT * FROM tokens WHERE account_id = ?')) {
        $sql->bind_param('i', $account_id);
        $account_id = $_SESSION['id'];
        if ($sql->execute()) {
            $result = $sql->get_result();
            if ($result->num_rows > 0) {
                echo 'hello!';
            } else {
                echo 'NO';
            }
        }
    }
}

?>