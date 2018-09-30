<?php
// Connect to database
require_once(__DIR__.'/db_config.php');

// Start a session
session_start();

// Check if user is already logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: http://budget.aamirhatim.com/php/welcome.php");
  die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get username and pw info from database
  if($sql = $link->prepare("SELECT account_id FROM accounts WHERE username = ? AND password = ?")) {
    mysqli_stmt_bind_param($sql, 'ss', $user, $pwd);
    $user = htmlspecialchars($_POST['user']);
    $pwd = htmlspecialchars($_POST['pwd']);
    if (mysqli_stmt_execute($sql)) {
      mysqli_stmt_store_result($sql);
      if (mysqli_stmt_num_rows($sql) == 1) {
        mysqli_stmt_bind_result($sql, $account_id);
        if(mysqli_stmt_fetch($sql)) {
          // Start a new session
          session_start();
          $_SESSION["loggedin"] = true;
          $_SESSION["id"] = $account_id;
          $_SESSION["username"] = trim($_POST['user']);

          // Redirect
          header("location: http://budget.aamirhatim.com/php/welcome.php");
          die();
        }
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
