<?php
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email address already exists
    // $sql = $link->prepare("SELECT email FROM accounts WHERE email = 'aamir300@gmail.com'");
    // $sql->bind_param('s', htmlspecialchars($_POST['email']));
    // $result = $link->query($sql);
    // if ($result->num_rows > 0) {
    //     while ($row = $result->fetch_assoc()) {
    //         echo $row;
    //     }
    // } else {
    //     echo 'No rows!';
    // }


    print_r($_POST['email']);
    echo '\n';
    $query = "SELECT email FROM accounts WHERE email ='" . trim($_POST['email'] . "'");
    print_r($query);
    echo '\n';
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row;
        echo '\n';
    }
    mysqli_close($link);


    echo 'finished';
    echo '\n';


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
