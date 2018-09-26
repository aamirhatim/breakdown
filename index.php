<?php
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email address already exists
    $sql = $link->prepare("SELECT email FROM accounts WHERE email = ?");
    mysqli_stmt_bind_param($sql, 's', $email);
    $email = htmlspecialchars($_POST['email']);
    if (mysqli_stmt_execute($sql)) {
        echo 'SUCCESS<br>';
        $sql->bind_result($result);
        echo $result->num_rows;
        while ($sql->fetch()) {
            echo $result;
            echo '<br>';
        }
    } else {
        echo 'FAILED<br>';
        echo mysqli_error($link);
    }


    // print_r($_POST['email']);
    // echo '<br>';
    // $query = "SELECT email FROM accounts WHERE email ='" . trim($_POST['email'] . "'");
    // print_r($query);
    // echo '<br>';
    // $result = mysqli_query($link, $query);
    // while ($row = mysqli_fetch_assoc($result)) {
    //     echo $row;
    //     echo '<br>';
    // }
    // mysqli_close($link);


    // echo 'finished';
    // echo '<br>';


    // $sql = $link->prepare("INSERT INTO accounts (account_id, email, username, password) VALUES (NULL, ?, ?, ?)");
    // $sql->bind_param('sss', $_POST['email'], $_POST['user'], $_POST['pwd']);
    // $sql->execute();
    // $sql->close();
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
