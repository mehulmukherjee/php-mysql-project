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

    $insertion_query = "INSERT INTO TRANSACTION_TABLE(USER_ID, TRANS_TYPE) VALUES('" .$_SESSION['userid']."', 'Sell')"; 
    $conn->exec($insertion_query);
    $id = $conn->lastInsertId();

    $crypto = $_POST['list2'];

    $get_crypto = 'SELECT * FROM CRYPTOCURRENCY_INFO WHERE CRYPTO = "'.$crypto.'"';
    $result_crypto = $conn->query($get_crypto);
    $crypto_array = $result_crypto->fetch();


    $datetime = date("Y-m-d H:i:s");

    $sell_table_insertion = "INSERT INTO SELL_ORDER(SELL_ID, CRYPTO, AMOUNT, SELL_PRICE, TIME) VALUES('";
    $sell_table_insertion = $sell_table_insertion . $id. "','".$crypto_array['CRYPTO']. "','".$_POST['quantity']. "','".$crypto_array['PRICE']."','".$datetime."')";

    $conn->exec($sell_table_insertion);

    //UPDATE BALANCE
    $balance_update_query = "UPDATE CUSTOMER_INFO SET BALANCE = BALANCE+".($crypto_array['PRICE'] * $_POST['quantity'])." WHERE USER_ID = '".$_SESSION["userid"]."'";
    $conn->exec($balance_update_query);

    $quantity_query = "SELECT QUANTITY FROM CUSTOMER_PORTFOLIO WHERE USER_ID = '".$_SESSION["userid"]."' AND CRYPTO = '".$crypto."'";
    $quantity_result = $conn->query($quantity_query);
    $quantity = $quantity_result->fetch();

    $new_quantity = $quantity['QUANTITY'] - $_POST['quantity'];

    if($new_quantity < 0){
        throw new Exception("Unsuccessful transaction due to low balance");
    }
    elseif($new_quantity == 0){
        //delete from portfolio
        $delete_portfolio_record = "DELETE FROM CUSTOMER_PORTFOLIO WHERE  USER_ID = '" .$_SESSION['userid']."' AND CRYPTO = '".$crypto."'";
        $conn->exec($delete_portfolio_record);
    }
    else{
        //update portfolio
        $port_crypto_query = "SELECT * FROM CUSTOMER_PORTFOLIO WHERE USER_ID = '" .$_SESSION['userid']."' AND CRYPTO = '".$crypto."'";
        $pc_result = $conn->query($port_crypto_query);
        $pcurrency = $pc_result->fetch();
        $update_port_query = "UPDATE CUSTOMER_PORTFOLIO SET QUANTITY = ".$new_quantity." WHERE USER_ID = '" .$_SESSION['userid']."' AND CRYPTO = '".$crypto."'";
        $conn->exec($update_port_query);
    }
    $status_message = "Successful transaction";
    $conn->commit();

} catch (Exception $e) {
    //Php code for rollback
    $status_message = "Unsuccessful transaction due to low quantity";
    $conn->rollback();
    
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Style.css"/>
</head>
    <body>
        <h2><?php echo htmlspecialchars($status_message);?></h2>
        <script>
					var timer = setTimeout(function() {
						window.location='customer.php'
					}, 3000);
		</script>
    </body>
</html>