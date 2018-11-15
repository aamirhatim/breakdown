<?php
// Connect to database
require_once(__DIR__.'/db_config.php');

// Check if account already exists with input credentials
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email address already exists
    $account_exists = 0;
    if($sql = $link->prepare(
        'SELECT
            email
        FROM
            accounts
        WHERE email = ?')) {
            $sql->bind_param('s', $email);
            $email = htmlspecialchars($_POST['email']);
            if ($sql->execute()) {
                $sql->store_result();
                if ($sql->num_rows > 0) {
                echo '<h3>An account already exists for the given email!</h3>';
                $account_exists = 1;
                }
            } else {
                echo $sql->$error;
            }
    }

    // Check if username is available
    if (!$account_exists) {
        $user_taken = 0;
        if ($sql = $link->prepare(
            'SELECT
                username
            FROM
                accounts
            WHERE username = ?')) {
                $sql->bind_param('s', $user);
                $user = htmlspecialchars($_POST['user']);
                if ($sql->execute()) {
                    $sql->store_result();
                    if ($sql->num_rows > 0) {
                        echo '<h3>That username has already been taken!</h3>';
                        $user_taken = 1;
                    }
                } else {
                    echo $sql->$error;
                }
        }
    }

    // If email doesn't exist, create a new account
    if (!$account_exists && !$user_taken) {
        if ($sql = $link->prepare(
            'INSERT INTO accounts
                (account_id,
                email,
                username,
                password)
            VALUES (NULL, ?, ?, ?)')) {
                $sql->bind_param('sss', $_POST['email'], $_POST['user'], $_POST['pwd']);
                if($sql->execute()) {
                    echo '<h3>New Account created!</h3>';
                }
        }
    }
}
$link->close();

?>
