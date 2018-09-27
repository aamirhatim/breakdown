<?php

include('db_service.php');
include('plaid_service.php');

$result = get_tokens();
$result->bind_result($token);
while ($result->fetch()) {
    echo $token;
}

?>