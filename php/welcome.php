<?php

// Init a session
session_start();

// Check if user is already logged in
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
    header("location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome!</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
        <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
        <script src = '../js/link_script.js'></script>
    </head>

    <body>
        <h1>YOU'RE LOGGED IN</h1>
        <p><br>Click <a href = 'logout.php'>here</a> to log out.</p>
        <p id = 'test'></p>

        <h2>Your Accounts</h2>
        <div id = 'accounts'>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Institution</th>
                </tr>

                <?php
                include('db_service.php');
                $result = get_all_accounts();
                $result->bind_result($account_name, $institution);
                while ($result->fetch()) {
                    echo '<tr>';
                    echo '<td>' . $account_name . '</td>';
                    echo '<td>' . $institution . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>

        <h2>Transactions</h2>
        <div id = 'transactions'>
            <?php
            include('plaid_service.php');
            $result = get_tokens();
            $result->bind_result($token, $bank_account_id);
            while ($result->fetch()) {
                $transactions = call_plaid_service($token, 'transactions', $bank_account_id);
                // print_r($transactions['transactions']);

                foreach ($transactions['transactions'] as $t) {
                    // Save transaction details
                    // $trans_amount = $t['amount'];
                    // $trans_categories = $t['category'];
                    // $trans_date = $t['date'];
                    $trans_loc = $t['location'];
                    // $trans_name = $t['name'];
                    $trans_pending = $t['pending'];
                    // $trans_id = $t['transaction_id'];

                    // Add transaction to database
                    // $sql = $link->prepare("INSERT INTO transactions (account_id, bank_account_id, transaction_id, amount, transaction_name, date, categories, address, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    // $sql->bind_param('ississsssss', $account_id, $bank_account_id, $trans_id, $trans_amount, $trans_name, $trans_date, $trans_categories, $trans_address, $trans_city, $trans_state, $trans_zip);
                    $account_id = $_SESSION['id'];
                    $trans_id = $t['transaction_id'];
                    $trans_amount = $t['amount'];
                    $trans_name = $t['name'];
                    $trans_date = $t['date'];
                    echo $t['category'] . ' ' .count($t['category']) . '<br>';
                    $trans_categories = '';
                    for ($i = 0; $i < count($t['category']) - 1; $i++) {
                        echo $t['category'][$i] . ' ' . '<br>';
                        // $trans_categories += $t['category'][$i] . ',';
                    }
                    $trans_categories += $t['category'][count($t['category']) - 1];
                    echo $trans_categories . '<br>';

                    $trans_categories = $t['category'];
                    $trans_address = $trans_loc['address'];
                    $trans_city = $trans_loc['city'];
                    $trans_state = $trans_loc['state'];
                    $trans_zip = $trans_loc['zip'];

                    // $sql->execute();
                    // $sql->close();

                    echo $t['amount'] . ' ';
                    print_r($t['category']);
                    echo ' ' . $t['date'] . ' ';
                    echo $t['name'];
                    echo '<br>';
                }
                echo '<br><br>';
            }
            ?>
        </div>

        <button id="link-button">Link Account</button>
        
    </body>
</html>