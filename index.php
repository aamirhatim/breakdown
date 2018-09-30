<?php
// Run login script
require(__DIR__.'/php/server/login_script.php');
?>

<!DOCTYPE html>
<html>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <head>
    <title>Log In</title>
    <link rel = 'stylesheet' href = 'css/style.css'>
  </head>

  <body>
    <div id = 'login-container'>
      <form id = 'login-form' action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method = 'POST'>
        <div id = 'login-img'>filler image/icon</div>
        <div id = 'login-text'>Log In</div>

        <input type = 'text' name = 'user' placeholder = 'username'>
        <input type = 'password' name = 'pwd' placeholder = 'password'>
        <input type = 'submit' value = 'Submit'>

        <div>
          <a href = 'http://budget.aamirhatim.com/php/client/register.php'>New users click here!</a>
        </div>
      </form>
    </div>

  </body>
</html>
