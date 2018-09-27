<?php

function get_all_accounts() {
    include('db_config.php');
    if ($sql = $link->prepare('SELECT * FROM tokens WHERE account_id = ?')) {
        $sql->bind_param('i', $account_id);
        $account_id = $_SESSION['id'];
        if ($sql->execute()) {
            if($result = $sql->get_result()) {
                echo 'hello';
            } else {
                echo 'nono';
            }
            echo 'hello';
            if ($result->num_rows > 0) {
                echo 'hello!';
            } else {
                echo 'NO';
                echo mysqli_error($link);
            }
        } else {
            echo 'NO no';
            echo mysqli_error($link);
        }
    } else {
        echo 'NO NO';
        echo mysqli_error($link);
    }

    $link->close();
}

?>