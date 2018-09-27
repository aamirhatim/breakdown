<?php

function plaid_service($token, $action) {
    if ($action == 'exchange') {
        $data = array(
            "client_id" => $plaid_client_id,
            "secret" => $plaid_secret,
            "public_token"=>$token
        );
        $url = '/item/public_token/exchange';
    } else {
        $data = array(
            "client_id" => $plaid_client_id,
            "secret" => $plaid_secret,
            "access_token"=>$token
        ); 
    }

    $data_fields = json_encode($data);

    if ($action == 'balance') {
        $url = '/accounts/balance/get';
    } else if ($action == 'identity') {
        $url = '/identity/get';
    } else if ($action == 'transactions') {
        $url = '/transactions/get';
    } else if ($action == 'income') {
        $url = '/income/get';
    }

    //initialize session
    $ch=curl_init($plaid_url . $url);

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
    $service_json = curl_exec($ch);
    $service = json_decode($service_json,true);  
    //check for errors
    if(isset($service['error_code'])){
    error_log("Plaid Error Message: " . $service_json);
    }            
    //close session
    curl_close($ch);
}

?>