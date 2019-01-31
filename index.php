<?php
// Run login script
require(__DIR__.'/php/server/login_script.php');
?>

<!DOCTYPE html>
<html>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <head>
        <base href = 'http://budget.aamirhatim.com/' />
        <title>Log In</title>
        <link rel = 'stylesheet' href = 'css/style.css'>
        <link rel = 'stylesheet' href = 'css/login.css'>
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC:100,300,400,500,700,900" rel="stylesheet">
    </head>

    <body>
        <div id = 'login-container'>
            <div id = 'login-contents'>
                <form id = 'login-form' action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method = 'POST'>
                    <div id = 'login-text'><h1>Hey there ;)</h1></div>

                    <input type = 'text' name = 'user' placeholder = 'username' required>
                    <input type = 'password' name = 'pwd' placeholder = 'password' required>
                    <input type = 'submit' value = 'Submit'>

                    <div class = 'alert-msg'>
                        <?php
                        echo '<p>' . $_SESSION['alert_msg'] . '</p>';
                        $_SESSION['alert_msg'] = "";
                        ?>
                    </div>

                    <div id = 'register-link'>
                        <a href = 'http://budget.aamirhatim.com/php/client/register.php'>Sign Up</a>
                    </div>
                </form>
            </div>
        </div>

        <main>
            <div class = 'sidebar'>
            </div>

            <div class = 'main-content'>
                <div id = 'about'>
                    <h1>What Is This?</h1>
                    <p>Simple. It's a website that keeps track of your expenses and shows you how you spend your money. It's easy to get lost (or not care) in the day to day expenses. Let us take care of that for you so you can just focus the stuff that matters, like saving up for that "brand new thing" you've been eyeing.
                </div>
            </div>

            <div class = 'sidebar'>
            </div>
        </main>

    </body>
</html>
