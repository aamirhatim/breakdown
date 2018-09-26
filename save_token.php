<?php

// Connect to database
require_once('db_config.php');

// Check if token already exists for the client
$token_exists = 0;
if($sql = $link->prepare("SELECT account_id FROM tokens WHERE access_token = ?")) {
    mysqli_stmt_bind_param($sql, 's', $access_token);
    $access_token = htmlspecialchars($_REQUEST['token']);
    if (mysqli_stmt_execute($sql)) {
        mysqli_stmt_store_result($sql);
        if (mysqli_stmt_num_rows($sql) > 0) {
            echo '<h3>This bank account has already been linked!</h3>';
            $token_exists = 1;
        }
    } else {
        echo mysqli_error($link);
    }
}

// If token does not exists, add it to the database
if (!$token_exists) {
    if($sql = $link->prepare("INSERT INTO tokens (account_id, access_token) VALUES (?, ?)")) {
        mysqli_stmt_bind_param($sql, 'is', $account_id, $access_token);
        $account_id = $_REQUEST['id'];
        if(mysqli_stmt_execute($sql)) {
            echo '<h3>New bank account added!</h3>';
        } else {
            echo mysqli_error($link);
        }
    }
    // $sql->bind_param('is', $_SESSION['id'], $access_token);
    // $sql->execute();
    // $sql->close();
    
}

// header('location: welcome.php');

echo $token;

?>