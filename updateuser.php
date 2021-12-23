<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'amt6702';
$password = 'BatmanRocks_ms23';
$host = 'localhost';
$dbname = 'amt6702_431W';


$update_user_currency = 'UPDATE CUSTOMER_INFO SET CURRENCY ="' .$_POST['list3'] . '" WHERE USER_ID = "' .$_SESSION["userid"].'"';


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec($update_user_currency );
?>

<script>
window.location = "customer.php";
</script>

<?php
    } 
catch(PDOException $e) {
    echo $update_user_currency . "<br>" . $e->getMessage();
    }
?>