<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'amt6702';
$password = 'BatmanRocks_ms23';
$host = 'localhost';
$dbname = 'amt6702_431W';


$delete_user_customer_info = 'DELETE FROM CUSTOMER_INFO WHERE USER_ID = "' .$_SESSION['userid'] . '"';
$delete_user_account_info = 'DELETE FROM ACCOUNT_INFO WHERE ID = "'.$_SESSION['userid']. '"';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec($delete_user_customer_info );

    $conn->exec($delete_user_account_info );

    $_SESSION['userid'] = "";
?>

<script>
window.location = "startup.php";
</script>

<?php
    } 
catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    }
?>