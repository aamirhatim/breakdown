<?php
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email address already exists
    $sql = "SELECT email from accounts WHERE email =" . $_POST['email'];
    $result = mysqli_query($link, $sql);
    echo '<h1>' . $result . '</h1>';


    // $sql = $link->prepare("INSERT INTO accounts (account_id, email, username, password) VALUES (NULL, ?, ?, ?)");
    // $sql->bind_param('ss', $email, $user, $pwd);
    // $email = $_POST['email'];
    // $user = $_POST['user'];
    // $pwd = $_POST['pwd'];
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
