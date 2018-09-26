<?php
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email address already exists
    $account_exists = 0;
    if($sql = $link->prepare("SELECT email FROM accounts WHERE email = ?")) {
        mysqli_stmt_bind_param($sql, 's', $email);
        $email = htmlspecialchars($_POST['email']);
        if (mysqli_stmt_execute($sql)) {
            $count = 0;
            while ($sql->fetch()) {
                $count ++;
            }
            if ($count > 0) {
                $account_exists = 1;
                echo '<h3>An account already exists for the given email!</h3>';
            }
        } else {
            echo mysqli_error($link);
        }
    }

    // If email doesn't exist, create a new account
    if (!$account_exists) {
        $sql = $link->prepare("INSERT INTO accounts (account_id, email, username, password) VALUES (NULL, ?, ?, ?)");
        $sql->bind_param('sss', $_POST['email'], $_POST['user'], $_POST['pwd']);
        $sql->execute();
        $sql->close();
        echo '<h3>New Account created!</h3>';
    }
}
$link->close();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up!</title>
    </head>

    <body>
        <form action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method = 'POST'>
            <input type = 'text' name = 'email'>
            <input type = 'text' name = 'user'>
            <input type = 'text' name = 'pwd'>
            <input type = 'submit' value = 'Submit'>
        </form>

        <p><br>Or log in <a href = 'login.php'>here</a></p>
    </body>
</html>
