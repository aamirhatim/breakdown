<?php
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email address already exists
    $sql = $link->prepare("SELECT * from accounts WHERE email = ?");
    $sql->bind_param('s', trim($_POST['email']));
    $sql->execute();
    $sql->bind_result($result);
    $sql->fetch();
    echo $result;
    $result = $sql->get_result();


    // $sql = $link->prepare("INSERT INTO accounts (account_id, email, username, password) VALUES (NULL, ?, ?, ?)");
    // $sql->bind_param('sss', $_POST['email'], $_POST['user'], $_POST['pwd']);
    // $sql->execute();
    $sql->close();
    $link->close();
}

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
    </body>
</html>
