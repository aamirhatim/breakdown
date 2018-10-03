<?php
// Connect to database
require_once(__DIR__.'/../server/db_config.php');

//Initialize a session
session_start();

// Get list of accounts
$labels = array();
$values = array();
if($sql = $link->prepare("SELECT bank_account_id FROM bank_accounts WHERE account_id = ?")) {
  $sql->bind_param('i', htmlspecialchars($_SESSION['id']));
  if($sql->execute()) {
    $sql->bind_result($bank_id);
    $sql->store_result();
    if($sql->num_rows > 0) {
      $count = 0;
      while($sql->fetch()) {
        $labels[$count] = $bank_id;

        // Get transaction amounts for each bank id
        if($trans = $link->prepare("SELECT amount FROM transactions WHERE bank_account_id = ?")) {
          $trans->bind_param('s', $bank_id);
          if($trans->execute()) {
            $trans->bind_result($amount);
            $trans->store_result();
            if($trans->num_rows > 0) {
              $total = 0.0;
              while($trans->fetch()) {
                // Add each amount to the existing total
                $total += $amount;
              }
              $values[$count] = $total;
            }
          }
        }

        $count ++;
      }

    }
  }
}
// print_r($labels);
// print_r($values);

$data = array(
  'labels' => $labels,
  'values' => $values
);

// print_r($data);

// JSONify data
$json_data = json_encode($data);
print_r($json_data);

?>
