<?php
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email address already exists
    $sql = $link->prepare("SELECT * from accounts WHERE email = ?");
    $sql->bind_param('s', $_POST['email']);
    $sql->execute();
    $result = $sql->get_result();
    while($row = $result->fetch_assoc()) {
        echo '<h1>TEST</h1>' . $row[email];
      }

    // $sql = $link->prepare("INSERT INTO accounts (account_id, email, username, password) VALUES (NULL, ?, ?, ?)");
    // $sql->bind_param('ss', $email, $user, $pwd);
    // $email = $_POST['email'];
    // $user = $_POST['user'];
    // $pwd = $_POST['pwd'];
    // $sql->execute();
    $sql->close();
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
