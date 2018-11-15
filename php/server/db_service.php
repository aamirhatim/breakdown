<?php
// Initialize a session
session_start();

// CLIENT SIDE FUNCTIONS //

// Returns list of all accounts for a user
// Need to be logged in for this function to work
function get_all_accounts($account_id) {
    include(__DIR__.'/db_config.php');
    $user_accounts = [];
    if ($sql = $link->prepare(
                            'SELECT
                                b.account_name,
                                b.bank_account_id,
                                b.active,
                                i.institution
                            FROM
                                bank_accounts b
                            JOIN items i ON
                                b.item_id = i.item_id
                            WHERE i.account_id = ?
                            AND b.active = 1
                            ORDER BY b.account_name')) {
        $sql->bind_param('i', $account_id);
        if ($sql->execute()) {
            $sql->bind_result($account_name, $bank_id, $status, $institution);
            while($sql->fetch()) {
                $account = [
                    "account_name" => $account_name,
                    "bank_id" => $bank_id,
                    "status" => $status,
                    "institution" => $institution
                ];
                array_push($user_accounts, $account);
            }
        } else echo $sql->error;
    } else echo $sql->$error;
    $link->close();
    return $user_accounts;
}

// Returns list of all item ids and respective institution names associated with an account
function get_all_items($account_id) {
    include(__DIR__.'/db_config.php');
    $user_items = [];
    if ($sql = $link->prepare(
                            'SELECT
                                item_id,
                                institution
                            FROM
                                items
                            WHERE account_id = ?')) {
        $sql->bind_param('i', $account_id);
        if ($sql->execute()) {
            $sql->bind_result($item_id, $institution);
            while($sql->fetch()) {
                $item = [
                    'item_id' => $item_id,
                    'institution' => $institution
                ];
                array_push($user_items, $item);
            }
        } else echo $sql->$error;
    }
    $link->close();
    return $user_items;
}

// Returns list of bank accounts for a given item id
function get_item_accounts($item_id) {
    include(__DIR__.'/db_config.php');
    $item_accounts = [];
    if ($sql = $link->prepare(
                            'SELECT
                                account_name,
                                bank_account_id,
                                active
                            FROM
                                bank_accounts
                            WHERE item_id = ?
                            ORDER BY account_name')) {
        $sql->bind_param('s', $item_id);
        if ($sql->execute()) {
            $sql->bind_result($bank_account_name, $bank_account_id, $status);
            while($sql->fetch()) {
                $account = [
                    'bank_account_name' => $bank_account_name,
                    'bank_account_id' => $bank_account_id,
                    'status' => $status
                ];
                array_push($item_accounts, $account);
            }
        }
    }
    $link->close();
    return $item_accounts;
}

// Returns list of user's transactions
function get_transactions($account_id) {
    include(__DIR__.'/db_config.php');
    $transactions = [];
    if ($sql = $link->prepare(
                            'SELECT
                                t.bank_account_id,
                                t.amount,
                                t.transaction_name,
                                t.date,
                                t.categories
                            FROM
                                transactions t
                            JOIN bank_accounts b ON
                                t.bank_account_id = b.bank_account_id
                            WHERE t.account_id = ?
                            AND b.active = 1
                            ORDER BY date DESC')) {
        $sql->bind_param('i', $account_id);
        if ($sql->execute()) {
            $sql->bind_result($bank_id, $amount, $transaction_name, $date, $categories);
            while($sql->fetch()) {
                $t = [
                    'bank_id' => $bank_id,
                    'amount' => $amount,
                    'trans_name' => $transaction_name,
                    'date' => $date,
                    'categories' => $categories
                ];
                array_push($transactions, $t);
            }
        }
    }
    $link->close();
    return $transactions;
}

// Returns the account name for a given active bank id
function get_bank_name($bank_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'SELECT
                                account_name
                            FROM
                                bank_accounts
                            WHERE bank_account_id = ?
                            AND active = "1"')) {
        $sql->bind_param('s', $bank_id);
        if ($sql->execute()) {
            $sql->bind_result($bank_name);
            $sql->store_result();
            if ($sql->num_rows == 1) {
                $sql->fetch();
                return $bank_name;
            }
        }
    }
    $link->close();
}

function get_bank_account_status($bank_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'SELECT
                                active
                            FROM
                                bank_accounts
                            WHERE bank_account_id = ?
                            AND account_id = ?')) {
        $sql->bind_param('si', $bank_id, $account_id);
        $account_id = $_SESSION['id'];
        if ($sql->execute()) {
            $sql->bind_result($status);
            $sql->store_result();
            if ($sql->num_rows == 1) {
                $sql->fetch();
                return $status;
            }
        }
    }
    $link->close();
}

function set_bank_account_status($bank_id, $status) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'UPDATE
                                bank_accounts
                            SET
                                active = ?
                            WHERE
                                bank_account_id = ?')) {
        $sql->bind_param('is', $status, $bank_id);
        if($sql->execute()) {
            echo "Preferences saved";
        }
        else {
            echo $sql->error;
        }
    }
    $link->close();
}

// SERVER SIDE FUNCTIONS //

// Sets the status of an item given an item id
function set_item_status($item_id, $status) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'UPDATE
                                items
                            SET
                                status = ?
                            WHERE item_id = ?')) {
        $sql->bind_param('ss', $status, $item_id);
        $sql->execute();
    }
    $link->close();
}

// Returns access token for an item id
function get_access_token($item_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'SELECT
                                access_token
                            FROM
                                items
                            WHERE item_id = ?')) {
        $sql->bind_param('s', $item_id);
        if($sql->execute()) {
            $sql->bind_result($access_token);
            $sql->store_result();
            if($sql->num_rows == 1) {
                $sql->fetch();
                return $access_token;
            }
        }
    }
    $link->close();
}

// Returns the name of the institution of the item
function get_item_institution($item_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'SELECT
                                institution
                            FROM
                                items
                            WHERE item_id = ?')) {
        $sql->bind_param('s', $item_id);
        if($sql->execute()) {
            $sql->bind_result($institution);
            $sql->store_result();
            if($sql->num_rows == 1) {
                $sql->fetch();
                return $institution;
            }
        }
    }
    $link->close();
}

function drop_bank_account($bank_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'DELETE FROM bank_accounts
                            WHERE bank_account_id = ?')) {
        $sql->bind_param('s', $bank_id);
        if (!$sql->execute()) {
            echo $sql->$error;
        }
    }
    $link->close();
}

function drop_account_transactions($bank_id) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'DELETE FROM transactions
                            WHERE bank_account_id = ?')) {
        $sql->bind_param('s', $bank_id);
        if (!$sql->execute()) {
            echo $sql->$error;
        }
    }
    $link->close();
}

function drop_item($access_token) {
    include(__DIR__.'/db_config.php');
    if ($sql = $link->prepare(
                            'DELETE FROM items
                            WHERE access_token = ?')) {
        $sql->bind_param('s', $access_token);
        if (!$sql->execute()) {
            echo $sql->$error;
        }
    }
    $link->close();
}

?>
