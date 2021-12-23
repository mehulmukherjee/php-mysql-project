<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'amt6702';
$password = 'BatmanRocks_ms23';
$host = 'localhost';
$dbname = 'amt6702_431W';


$get_admin_status = 'SELECT ACCOUNT_TYPE FROM ACCOUNT_INFO WHERE ACCOUNT_STATUS = "A" AND ID ="' . $_POST["loginid"] . '"AND PASSWORD ="'.$_POST["password"].'"';

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $result = $conn->query($get_admin_status);
  $result->setFetchMode(PDO::FETCH_ASSOC);
  } 
  catch(PDOException $e) {
    echo $get_admin_status . "<br>" . $e->getMessage();
  }
  $conn = null;

?>

<!DOCTYPE html>
<html>
  <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" type="text/css" href="Style.css"/>
  </head>
  <body>
    <?php
      if($result -> rowCount() == 0){
    ?>
    <h2> Invalid Credentials/User Deactivated</h2>
    <script>
    var timer = setTimeout(function() {
						window.location='startup.php'
					}, 3000);
    </script>
    <?php }while ($row = $result->fetch()): ?>
    <h1>
      <?php 
      if ($row['ACCOUNT_TYPE'] == 'Customer'){

        $_SESSION["userid"] = $_POST["loginid"];
      ?> 
      <script>
						window.location='customer.php';
			</script>
      <?php 
      } elseif($row['ACCOUNT_TYPE'] == 'Admin'){
        $_SESSION["userid"] = $_POST["loginid"];
      ?> 
      <script>
            window.location='admin.php';
      </script>
        <?php
      }
      else{
      }
      ?>   
    </h1>
    <?php endwhile; ?>
  </body>
</html>