<?php

function get_all_accounts() {
    include('db_config.php');
    if ($sql = $link->prepare('SELECT * FROM tokens WHERE account_id = ?')) {
        echo 'hello';
        $sql->bind_param('i', $account_id);
        echo 'hello2';
        $account_id = $_SESSION['id'];
        echo 'hello3';
        if ($sql->execute()) {
            $result = $sql->get_result();
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