<?php
// Connect to database
require_once(__DIR__.'/db_config.php');

// Start a session
session_start();

// Check if user is already logged in
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header('location: http://budget.aamirhatim.com/php/welcome.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and pw info from database
    if($sql = $link->prepare(
        'SELECT
            account_id
        FROM accounts
        WHERE username = ?
        AND password = ?')) {
            $sql->bind_param('ss', $user, $pwd);
            $user = htmlspecialchars($_POST['user']);
            $pwd = htmlspecialchars($_POST['pwd']);
            if ($sql->execute()) {
                $sql->store_result();
                if ($sql->num_rows == 1) {
                    $sql->bind_result($account_id);
                    if($sql->fetch()) {
                        // Start a new session
                        session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $account_id;
                        $_SESSION['username'] = trim($_POST['user']);

                        // Redirect
                        header('location: http://budget.aamirhatim.com/php/welcome.php');
                        die();
                    }
                } else {
                    echo '<h1>The username/password was incorrect.</h1>';
                }
            } else {
                echo $sql->$error;
            }
    }
}
$link->close();

?>
