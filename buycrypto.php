<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'amt6702';
$password = 'BatmanRocks_ms23';
$host = 'localhost';
$dbname = 'amt6702_431W';

try {
    $status_message = "";

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $conn->beginTransaction();

    $insertion_query = "INSERT INTO TRANSACTION_TABLE(USER_ID, TRANS_TYPE) VALUES('" .$_SESSION['userid']."', 'Buy')"; 
    $conn->exec($insertion_query);
    
    $id = $conn->lastInsertId();
    $crypto = $_POST['list1'];

    $get_crypto = 'SELECT * FROM CRYPTOCURRENCY_INFO WHERE CRYPTO = "'.$crypto.'"';
    $result_crypto = $conn->query($get_crypto);
    $crypto_array = $result_crypto->fetch();


    $datetime = date("Y-m-d H:i:s");

    $buy_table_insertion = "INSERT INTO BUY_ORDER(BUY_ID, CRYPTO, AMOUNT, BUY_PRICE, TIME) VALUES('";
    $buy_table_insertion = $buy_table_insertion . $id. "','".$crypto_array['CRYPTO']. "','".$_POST['quantity']. "','".$crypto_array['PRICE']."','".$datetime."')";

    $conn->exec($buy_table_insertion);

    

    $balance_query = "SELECT BALANCE FROM CUSTOMER_INFO WHERE USER_ID = '".$_SESSION["userid"]."'";
    $balance_result = $conn->query($balance_query);
    $balance = $balance_result->fetch();

    if($balance['BALANCE'] - ($crypto_array['PRICE'] * $_POST['quantity'])  < 0){
        throw new Exception("Unsuccessful transaction due to low balance");
    }

    //UPDATE BALANCE
    $balance_update_query = "UPDATE CUSTOMER_INFO SET BALANCE = BALANCE-".($crypto_array['PRICE'] * $_POST['quantity'])." WHERE USER_ID = '".$_SESSION["userid"]."'";
    $conn->exec($balance_update_query);
    
    $status_message = "Successful Transaction";

    $port_crypto_query = "SELECT * FROM CUSTOMER_PORTFOLIO WHERE USER_ID = '" .$_SESSION['userid']."' AND CRYPTO = '".$crypto."'";
    $pc_result = $conn->query($port_crypto_query);
    $pcurrency = $pc_result->fetch();

    if($pcurrency['CRYPTO'] == $crypto)
    {
        //Update
        $new_quantity =  $pcurrency['QUANTITY'] + $_POST['quantity'];
        $avg_price = ($pcurrency['QUANTITY'] * $pcurrency['AVERAGE_PRICE'] + $_POST['quantity'] * $crypto_array['PRICE'])/$new_quantity;
        $update_port_query = "UPDATE CUSTOMER_PORTFOLIO SET QUANTITY = ".$new_quantity.",  AVERAGE_PRICE = ".$avg_price." WHERE USER_ID = '" .$_SESSION['userid']."' AND CRYPTO = '".$crypto."'";
        $conn->exec($update_port_query);
    }
    else
    {
        //Insert
        $insert_port_query = "INSERT INTO CUSTOMER_PORTFOLIO(USER_ID, CRYPTO, QUANTITY, AVERAGE_PRICE) VALUES('";
        $insert_port_query = $insert_port_query. $_SESSION['userid']. "','".$crypto_array['CRYPTO']. "','".$_POST['quantity']. "','".$crypto_array['PRICE']."')";
        $conn->exec($insert_port_query);
    }
    $conn->commit();

} catch (Exception $e) {
    //Php code for rollback
    $status_message = "Unsuccessful Transaction due to low balance";
    $conn->rollback();
    
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Style.css"/>
</head>
    <body>
        <h2><?php echo htmlspecialchars($status_message );?></h2>
        <script>
					var timer = setTimeout(function() {
						window.location='customer.php'
					}, 1000);
		</script>
    </body>
</html>