<?php

// Init a session
// session_start();

// // Check if user is already logged in
// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//     header("location: welcome.php");
//     exit;
// }

require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and pw info from database
    if($sql = $link->prepare("SELECT account_id FROM accounts WHERE username = ? AND password = ?")) {
        mysqli_stmt_bind_param($sql, 'ss', $user, $pwd);
        $user = htmlspecialchars($_POST['user']);
        $pwd = htmlspecialchars($_POST['pwd']);
        if (mysqli_stmt_execute($sql)) {
            mysqli_stmt_store_result($sql);

            // $count = 0;
            // while ($sql->fetch()) {
            //     $count ++;
            // }
            if (mysqli_stmt_num_rows($sql) == 1) {
                echo '<h1>We have a match!</h1>';
                // Start a new session
                // session_start();
                // $_SESSION["loggedin"] = true;
                // $_SESSION["id"] = $id;
                // $_SESSION["username"] = trim($_POST['user']); 
            } else {
                echo '<h1>The username/password was incorrect.</h1>';
            }
        } else {
            echo mysqli_error($link);
        }
    }
}
$link->close();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Log In!</title>
    </head>

    <body>
        <form action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method = 'POST'>
            <input type = 'text' name = 'user'>
            <input type = 'text' name = 'pwd'>
            <input type = 'submit' value = 'Submit'>
        </form>
    </body>
</html>