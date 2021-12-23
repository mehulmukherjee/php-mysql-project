<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(empty($_SESSION["userid"])){?>
    <script>
            window.location='startup.php';
      </script>
<?php }

$username = 'amt6702';
$password = 'BatmanRocks_ms23';
$host = 'localhost';
$dbname = 'amt6702_431W';

$get_user_details = 'SELECT * FROM CUSTOMER_INFO CI
  JOIN CURRENCY_INFO CRI
  ON CI.CURRENCY = CRI.CURRENCY
  WHERE USER_ID = "'.$_SESSION['userid'].'"';

$get_user_transaction = "SELECT * FROM CUSTOMER_INFO CI
JOIN ACCOUNT_INFO AI ON CI.USER_ID = AI.ID
JOIN CURRENCY_INFO CRI ON CRI.CURRENCY = CI.CURRENCY
JOIN TRANSACTION_TABLE TT ON CI.USER_ID = TT.USER_ID
JOIN 
(SELECT BUY_ID AS 'BUYSELL_ID', CRYPTO, AMOUNT, BUY_PRICE AS 'BS_PRICE', TIME FROM BUY_ORDER 
UNION
SELECT SELL_ID AS 'BUYSELL_ID', CRYPTO, AMOUNT, SELL_PRICE AS 'BS_PRICE', TIME FROM SELL_ORDER) BS 
ON TT.TRANS_ID = BS.BUYSELL_ID
JOIN CRYPTOCURRENCY_INFO CRYI ON BS.CRYPTO = CRYI.CRYPTO WHERE CI.USER_ID ='".$_SESSION['userid']."' ORDER BY BS.TIME DESC";


$get_market = "SELECT * FROM CRYPTOCURRENCY_INFO";

$get_portfolio = "SELECT * FROM CUSTOMER_PORTFOLIO CP
JOIN CRYPTOCURRENCY_INFO CRYI ON CP.CRYPTO = CRYI.CRYPTO
WHERE CP.USER_ID ='".$_SESSION['userid']."'";

$get_currencies = "SELECT * FROM CURRENCY_INFO";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $result_user = $conn->query($get_user_details);
    $result_user->setFetchMode(PDO::FETCH_ASSOC);

    $result_transactions = $conn->query($get_user_transaction);
    $result_transactions->setFetchMode(PDO::FETCH_ASSOC);

    $result_market = $conn->query($get_market);
    $result_market->setFetchMode(PDO::FETCH_ASSOC);

    $result_portfolio = $conn->query($get_portfolio);
    $result_portfolio->setFetchMode(PDO::FETCH_ASSOC);

    $result_currencies = $conn->query($get_currencies);
    $result_currencies->setFetchMode(PDO::FETCH_ASSOC);

    } 
catch(PDOException $e) {
      echo $result_user . "<br>" . $e->getMessage();
    }
$conn = null;

$customer = $result_user->fetch();
$transactions = $result_transactions->fetchAll();
$market = $result_market->fetchAll();
$portfolio = $result_portfolio->fetchAll();
$currencies = $result_currencies->fetchAll();


$balance = $customer["BALANCE"];
$rate = $customer["RATE"];
$symbol = $customer["SYMBOL"];

$sum = 0;
$x = 0; 

while($x < count($portfolio)) {
  $sum += $portfolio[$x]['PRICE'] * $portfolio[$x]['QUANTITY'] * $rate;
  $x++;
} 

// Buy Code


?>



<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Style.css"/>
</head>

<body>

<h2>Fiat Currency Balance = <?php echo htmlspecialchars($balance * $rate.$symbol); ?></h2>

    <div class="tab">
        <button class="tablinks" onclick="openCity(event, 'Buy')">Buy</button>
        <button class="tablinks" onclick="openCity(event,'Sell')">Sell</button>
        <button class="tablinks" onclick="openCity(event, 'Transactions')">Transactions</button>
        <button class="tablinks" onclick="openCity(event, 'Info')">My Profile</button>
        <?php
        if(!empty($_SESSION["userid"])){?>
        <button class="tablinks" style = "float: right;" onclick="logout()">Logout</button>
        <script>
            function logout(){
                <?php $_SESSION["user_id"] = ''; ?>
                window.location = 'startup.php';
            }
        </script>
        <?php } ?>
    </div>

    <div id="Buy" class="tabcontent">
    	
        <h2>Market</h2>
        
        <table border=1 cellspacing=5 cellpadding=5>
            <thead>
                <tr>
                    <th>Cryptocurrency</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>About</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($market as $row) : array_map('htmlentities', $row); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['CRYPTO']); ?></td>
                        <td><?php echo htmlspecialchars($row['NAME']); ?></td>
                        <td><?php echo htmlspecialchars($row['TYPE']); ?></td>
                        <td><?php echo htmlspecialchars($row['PRICE'] * $rate .$symbol); ?></td>
                        <td><?php echo htmlspecialchars($row['ABOUT']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Buy Cryptocurrency</h2>
	
        <form action="buycrypto.php" method = "post">
            Select : <select list="list1" name="list1" required>
            <datalist id="list1">
                <?php foreach ($market as $row) : array_map('htmlentities', $row); ?>
                    <option><?php echo htmlspecialchars($row['CRYPTO']); ?></option>
                <?php endforeach; ?>
            </datalist>
	    </select>
            <br>
            <br>
            Quantity : <input id = "quantity" name = "quantity" type="number" step="0.01" required>
            <br>
            <br>
            <button type="submit">Submit</button>
        </form>
    </div>

    <div id="Sell" class="tabcontent">
        <h2>Portfolio Value = <?php echo htmlspecialchars($sum.$symbol); ?></h2>
        <table border=1 cellspacing=5 cellpadding=5>
            <thead>
                <tr>
                    <th>Cryptocurrency</th>
                    <th>Amount</th>
                    <th>Avg Buy Price</th>
                    <th>Current Price</th>
                    <th>Total</th>
                    <th>Gains</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($portfolio as $row) : array_map('htmlentities', $row); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['CRYPTO']); ?></td>
                        <td><?php echo htmlspecialchars($row['QUANTITY']); ?></td>
                        <td><?php echo htmlspecialchars($row['AVERAGE_PRICE'] * $rate . $symbol); ?></td>
                        <td><?php echo htmlspecialchars($row['PRICE'] * $rate . $symbol); ?></td>
                        <td><?php echo htmlspecialchars($row['PRICE'] * $row['QUANTITY'] * $rate . $symbol); ?></td>
                        <td><?php echo htmlspecialchars(($row['PRICE'] * $row['QUANTITY'] - $row['AVERAGE_PRICE'] * $row['QUANTITY']) * $rate . $symbol); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Sell Cryptocurrency</h2>
	
        <form action="sellcrypto.php" method = "post">
            Select : <select list="list2" name="list2" required>
            <datalist id="list2">
                <?php foreach ($portfolio as $row) : array_map('htmlentities', $row); ?>
                    <option><?php echo htmlspecialchars($row['CRYPTO']); ?></option>
                <?php endforeach; ?>
            </datalist>
	    </select>
            <br>
            <br>
            Quantity : <input name = "quantity" type="number" step="0.01" required>
            <br>
            <br>
            <button type="submit">Submit</button>
        </form>
    </div>



    <div id="Transactions" class="tabcontent">
    
    	<h2>Past Transactions</h2>
        <table border=1 cellspacing=5 cellpadding=5>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Transaction Type</th>
                    <th>Cryptocurrency</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Transaction Price</th>
                    <th>Total</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $row) : array_map('htmlentities', $row); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['TRANS_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['TRANS_TYPE']); ?></td>
                        <td><?php echo htmlspecialchars($row['CRYPTO']); ?></td>
                        <td><?php echo htmlspecialchars($row['NAME']); ?></td>
                        <td><?php echo htmlspecialchars($row['TYPE']); ?></td>
                        <td><?php echo htmlspecialchars($row['AMOUNT']); ?></td>
                        <td><?php echo htmlspecialchars($row['BS_PRICE'] * $rate . $symbol); ?></td>
                        <td><?php echo htmlspecialchars($row['BS_PRICE'] * $row['AMOUNT'] * $rate . $symbol); ?></td>
                        <td><?php echo htmlspecialchars($row['TIME']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="Info" class="tabcontent">
        <h3>My Profile</h3>
		<div class="container">
    	<form action="updateuser.php" method="post">
        	<label>User ID</label>
      		<input type="text" name="first" disabled value="<?php echo htmlspecialchars($customer['USER_ID']);?>"><br />
      		<label>First Name</label>
      		<input type="text" name="first" disabled value="<?php echo htmlspecialchars($customer['FNAME']);?>"><br />
      		<label>Middle Name</label>
      		<input type="text" name="last" disabled value="<?php echo htmlspecialchars($customer['MNAME']);?>"><br />
      		<label>Last Name</label>
      		<input type="text" name="email" disabled value="<?php echo htmlspecialchars($customer['LNAME']);?>"><br/>
            <label>Change Currency</label>
            <select list="list3" name="list3" value="<?php echo htmlspecialchars($customer['CURRENCY']);?>" required>
            <datalist id="list3">
            <option selected disabled value><?php echo htmlspecialchars($customer["CURRENCY"]. "- Current Currency");?></option>
                <?php foreach ($currencies as $row) : ?>
                    <option><?php echo htmlspecialchars($row["CURRENCY"]);?></option>
                <?php endforeach; ?>
            </datalist>
	    </select>
            <br>
            <br>
            <button type="submit">Update</button>
    	</form>
        <form action="deleteuser.php" >
        	<br>
            <button type="submit">Delete Account</button>
        </form>
  </div>
    </div>

    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";

        }
    </script>

</body>

</html>