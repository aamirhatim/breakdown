<?php
// Run registration script
require(__DIR__.'/../server/register_script.php');
?>

<!DOCTYPE html>
<html>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <head>
    <title>Sign Up</title>
    <link rel = 'stylesheet' href = '../../css/style.css'>
  </head>

  <body>
    <div id = 'login-container'>
      <form id = 'login-form' action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method = 'POST'>
        <div id = 'login-img'>filler image/icon</div>
        <div id = 'login-text'>Sign Up</div>

        <input type = 'text' name = 'email' placeholder = 'email'>
        <input type = 'text' name = 'user' placeholder = 'username'>
        <input type = 'password' name = 'pwd' placeholder = 'password'>
        <input type = 'submit' value = 'Submit'>

        <div>
          <a href = 'http://budget.aamirhatim.com/index.php'>Log in here!</a>
        </div>
      </form>
    </div>

  </body>
</html>
