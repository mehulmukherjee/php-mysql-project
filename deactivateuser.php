<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'amt6702';
$password = 'BatmanRocks_ms23';
$host = 'localhost';
$dbname = 'amt6702_431W';

$deactivate_user_query = 'UPDATE ACCOUNT_INFO SET ACCOUNT_STATUS = "I" WHERE ID ="' .$_POST["select_user"].'"'; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->exec($deactivate_user_query);
    } 
catch(PDOException $e) {
      echo $get_admin_status . "<br>" . $e->getMessage();
    }


?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Style.css"/>
</head>

<body>
    <script>
            window.location='admin.php';
    </script>
</body>
</html>