<?php

// Load Plaid service function
// include('plaid_service.php');

// Initialize session
session_start();

// Connect to database
require_once('db_config.php');

// Exchange public token for access_token
$public_token = htmlspecialchars($_POST['token']);
$meta = $_POST['meta'];

// Check if account has already been added
$account_exists = 0;
if($sql = $link->prepare("SELECT account_id FROM tokens WHERE account_id = ? AND account_mask = ? AND account_name = ?")) {
    mysqli_stmt_bind_param($sql, 'iss', $account_id, $account_mask, $account_name);
    $account_id = $_SESSION['id'];
    $account_mask = $meta['account']['mask'];
    $account_name = $meta['account']['name'];
    if (mysqli_stmt_execute($sql)) {
        mysqli_stmt_store_result($sql);
        if (mysqli_stmt_num_rows($sql) > 0) {
            echo '<h3>This bank account has already been linked!</h3>';
            $account_exists = 1;
        }
    } else {
        echo mysqli_error($link);
    }
}

// If account does not exist, add it to the database
if (!$account_exists) {
    if($sql = $link->prepare("INSERT INTO tokens (account_id, account_mask, account_name, institution, access_token) VALUES (?, ?, ?, ?, ?)")) {
        mysqli_stmt_bind_param($sql, 'issss', $account_id, $account_mask, $account_name, $institution, $access_token);
        $account_id = $_SESSION['id'];
        $account_mask = $meta['account']['mask'];
        $account_name = $meta['account']['name'];
        $institution = $meta['institution']['name'];
        $exchange = get_access($public_token);
        $access_token = $exchange['access_token'];
        if(mysqli_stmt_execute($sql)) {
            echo '<h3>New bank account added!</h3>';
        } else {
            echo mysqli_error($link);
        }
    }
}
$link->close();


function get_access_token($public_token)
    {
        global $plaid_client_id, $plaid_secret, $plaid_url;
        $plaid_url = "https://sandbox.plaid.com";
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