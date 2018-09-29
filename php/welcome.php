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
            include('db_config.php');
            include('plaid_service.php');
            $result = get_tokens();
            $result->bind_result($token, $bank_account_id);
            while ($result->fetch()) {
                $transactions = call_plaid_service($token, 'transactions', $bank_account_id);
                foreach ($transactions['transactions'] as $t) {
                    $trans_loc = $t['location'];
                    $trans_pending = $t['pending'];

                    // Check if transaction already exists
                    if ($sql = $link->prepare("SELECT transaction_id FROM transactions WHERE transaction_id = ?")) {
                        $sql->bind_param('s', $transaction_id);
                        $transaction_id = htmlspecialchars($t['transaction_id']);
                        if ($sql->execute()) {
                            $sql->bind_result($result);
                            $count = 0;
                            while ($result->fetch()) {
                                $count ++;
                            }
                            echo $count . '<br>';
                        }
                    }

                    // Add transaction to database
                    if($sql = $link->prepare("INSERT INTO transactions (account_id, bank_account_id, transaction_id, amount, transaction_name, date, categories, address, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                        $sql->bind_param('issdsssssss', $account_id, $bank_id, $trans_id, $trans_amount, $trans_name, $trans_date, $trans_categories, $trans_address, $trans_city, $trans_state, $trans_zip);
                        
                        $categories = '';
                        for ($i = 0; $i < count($t['category']) - 1; $i++) {
                            $categories .= $t['category'][$i] . ',';
                        }
                        $categories .= $t['category'][count($t['category']) - 1];

                        // Create info array
                        $trans_info = [
                            'account_id' => $_SESSION['id'],
                            'bank_id' => $bank_account_id,
                            'trans_id' => $t['transaction_id'],
                            'trans_amount' => $t['amount'],
                            'trans_name' => $t['name'],
                            'trans_date' => $t['date'],
                            'trans_categories' => $categories,
                            'trans_address' => $trans_loc['address'],
                            'trans_city' => $trans_loc['city'],
                            'trans_state' => $trans_loc['state'],
                            'trans_zip' => $trans_loc['zip']
                        ];

                        // Fill any null values with empty string
                        foreach ($trans_info as $key => $value) {
                            if (is_null($value) && empty($value)) {
                                $trans_info[$key] = '';
                            }
                        }
                        
                        $account_id = (int) $trans_info['account_id'];
                        $bank_id = (string) $trans_info['bank_id'];
                        $trans_id = (string) $trans_info['trans_id'];
                        $trans_amount = (double) $trans_info['trans_amount'];
                        $trans_name = (string) $trans_info['trans_name'];
                        $trans_date = (string) $trans_info['trans_date'];
                        $trans_categories = (string) $trans_info['trans_categories'];
                        $trans_address = (string) $trans_info['trans_address'];
                        $trans_city = (string) $trans_info['trans_city'];
                        $trans_state = (string) $trans_info['tran_state'];
                        $trans_zip = (string) $trans_info['trans_zip'];

                        print_r($trans_info);
                        echo '<br>';

                        $sql->execute();
                    }
                }
                echo '<br><br>';
            }
            $sql->close();
            ?>
        </div>

        <button id="link-button">Link Account</button>
        
    </body>
</html>