<?php
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = $link->prepare('INSERT INTO accounts (account_id, username, password) VALUES (NULL, ?, ?)');
    $sql->bind_param('ss', $user, $pwd);
    $sql->execute();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up!</title>
    </head>

    <body>
        <form action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method = 'post'>
            <input type = 'text' name = 'user'>
            <input type = 'text' name = 'pwd'>
            <input type = 'submit' value = 'Submit'>
        </form>
    </body>
</html>
