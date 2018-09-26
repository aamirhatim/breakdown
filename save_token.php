<?php
// Initialize session
session_start();

// Connect to database
require_once('db_config.php');

// Exchange public token for access_token
$public_token = htmlspecialchars($_REQUEST['token']);
$access_token = get_access_token($public_token);
echo $access_token;

// Check if token already exists for the client
// $token_exists = 0;
// if($sql = $link->prepare("SELECT account_id FROM tokens WHERE access_token = ?")) {
//     mysqli_stmt_bind_param($sql, 's', $access_token);
//     $access_token = htmlspecialchars($_REQUEST['token']);
//     if (mysqli_stmt_execute($sql)) {
//         mysqli_stmt_store_result($sql);
//         if (mysqli_stmt_num_rows($sql) > 0) {
//             echo '<h3>This bank account has already been linked!</h3>';
//             $token_exists = 1;
//         }
//     } else {
//         echo mysqli_error($link);
//     }
// }

// If token does not exists, add it to the database
// if (!$token_exists) {
//     if($sql = $link->prepare("INSERT INTO tokens (account_id, access_token) VALUES (?, ?)")) {
//         mysqli_stmt_bind_param($sql, 'is', $account_id, $access_token);
//         $account_id = $_SESSION['id'];
//         if(mysqli_stmt_execute($sql)) {
//             echo '<h3>New bank account added!</h3>';
//         } else {
//             echo mysqli_error($link);
//         }
//     }
// }
$link->close();

// header('location: welcome.php');

// echo $token;

function get_access_token($public_token)
    {
        global $plaid_client_id, $plaid_secret, $plaid_url;
        $data = array(
            "client_id" => '5ba876b107df5000124dcbdd',
            "secret" => 'bdcd0bf9075a258404b52e1ec65c74',
            "public_token" => $public_token
        );
 
        $data_fields = json_encode($data);        
 
        //initialize session
        $ch = curl_init($plaid_url . "/item/public_token/exchange");
 
        //set options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
          'Content-Type: application/json',                                                                                
          'Content-Length: ' . strlen($data_fields))                                                                       
        );   
 
        //execute session
        $token_json = curl_exec($ch);
        $exchange_token = json_decode($token_json,true);          
        //close session
        curl_close($ch);        
 
        return $exchange_token;
    }

?>