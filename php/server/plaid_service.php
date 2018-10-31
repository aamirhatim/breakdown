<?php

function call_plaid_service($token, $action, $bank_account_ids = 'none', $start_date = 'none', $trans_offset = 0) {
  $plaid_url = "https://sandbox.plaid.com";
  $client_id = '5ba876b107df5000124dcbdd';
  $secret = 'bdcd0bf9075a258404b52e1ec65c74';

  if ($action == 'exchange') {
    $data = array(
      "client_id" => $client_id,
      "secret" => $secret,
      "public_token"=>$token
    );
    $url = '/item/public_token/exchange';
  } else if ($action == 'transactions') {
    $data = array(
      "client_id" => $client_id,
      "secret" => $secret,
      "access_token"=>$token,
      "start_date"=> $start_date,
      "end_date"=> (string) date('Y-m-d'),
      "options"=> ['account_ids'=>$bank_account_ids, 'offset'=>$trans_offset]
    );
    $url = '/transactions/get';
  } else {
    $data = array(
      "client_id" => $client_id,
      "secret" => $secret,
      "access_token"=>$token
    );
  }

  $data_fields = json_encode($data);

  if ($action == 'balance') {
      $url = '/accounts/balance/get';
  } else if ($action == 'identity') {
      $url = '/identity/get';
  } else if ($action == 'income') {
      $url = '/income/get';
  } else if ($action == 'remove') {
    $url = '/item/remove';
  }

  //initialize session
  $ch = curl_init($plaid_url . $url);

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

  return $service;
}

?>
