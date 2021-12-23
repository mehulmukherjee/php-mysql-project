<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'amt6702';
$password = 'BatmanRocks_ms23';
$host = 'localhost';
$dbname = 'amt6702_431W';
?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
		<p>
        <?php 
				echo "Inserting new user: " . $_POST["fname"] . " " . $_POST["lname"] . " " . $_POST["loginid"] . "..."; 
				$customer_info_insert = 'INSERT INTO CUSTOMER_INFO (USER_ID, FNAME, MNAME, LNAME, BALANCE,CURRENCY) ';
				$customer_info_insert = $customer_info_insert . 'VALUES ("'.$_POST["loginid"] . '","' . $_POST["fname"] . '","'  . $_POST["mname"] . '","'  . $_POST["lname"] . '","' . $_POST["balance"] . '","USD" )'; 

                $account_info_insert = 'INSERT INTO ACCOUNT_INFO (ID, PASSWORD, ACCOUNT_TYPE, ACCOUNT_STATUS) ';
                $account_info_insert = $account_info_insert . 'VALUES ("'.$_POST["loginid"] .'","' .$_POST["password"]. '", "Customer", "A")';
                
				try {
					$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$conn->exec($customer_info_insert);
                    $conn->exec($account_info_insert);
					echo "New record created successfully";

					$_SESSION["userid"] = $_POST["loginid"];

			?>
				<script>
						window.location='customer.php';
				</script>
			<?php
				} catch(PDOException $e) {
					echo $sql . "<br>" . $e->getMessage();
				}
				$conn = null;
			?>
		</p>
    </body>
</div>
</html>