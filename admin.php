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

$get_users = "SELECT * FROM ACCOUNT_INFO JOIN CUSTOMER_INFO ON ID = USER_ID";

$get_transactions = "SELECT * FROM CUSTOMER_INFO CI
JOIN ACCOUNT_INFO AI ON CI.USER_ID = AI.ID
JOIN CURRENCY_INFO CRI ON CRI.CURRENCY = CI.CURRENCY
JOIN TRANSACTION_TABLE TT ON CI.USER_ID = TT.USER_ID
JOIN 
(SELECT BUY_ID AS 'BUYSELL_ID', CRYPTO, AMOUNT, BUY_PRICE AS 'BS_PRICE', TIME FROM BUY_ORDER 
UNION
SELECT SELL_ID AS 'BUYSELL_ID', CRYPTO, AMOUNT, SELL_PRICE AS 'BS_PRICE', TIME FROM SELL_ORDER) BS 
ON TT.TRANS_ID = BS.BUYSELL_ID
JOIN CRYPTOCURRENCY_INFO CRYI ON BS.CRYPTO = CRYI.CRYPTO ORDER BY CI.USER_ID ASC, BS.TIME DESC ";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $result_users = $conn->query($get_users);
    $result_users->setFetchMode(PDO::FETCH_ASSOC);

    $result_transactions = $conn->query($get_transactions);
    $result_transactions->setFetchMode(PDO::FETCH_ASSOC);
    } 
catch(PDOException $e) {
      echo $get_admin_status . "<br>" . $e->getMessage();
    }
$conn = null;

$All_Users = $result_users->fetchAll();
$all_transactions = $result_transactions->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Style.css"/>
</head>

<body>

    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'Transactions')">Transactions</button>
        <button class="tablinks" onclick="openTab(event, 'Customers')">Customers</button>
        <?php if(!empty($_SESSION["userid"])){?>
        <button class="tablinks" style = "float: right;" onclick="logout()">Logout</button>
        <script>
            function logout(){
                <?php $_SESSION["user_id"] = ''; ?>
                window.location = 'startup.php';
            }
        </script>
        <?php } ?>
    </div>



    <div id="Transactions" class="tabcontent">
    
    <h2>All Transactions</h2>
      <table border=1 cellspacing=5 cellpadding=5>
          <thead>
              <tr>
                  <th>User ID</th>
                  <th>Account Type</th>
                  <th>Account Status</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Last Name</th>
                  <th>Balance</th>
                  <th>Transaction ID</th>
                  <th>Transaction Type</th>
                  <th>Crypto Currency</th>
                  <th>Amount</th>
                  <th>Transaction Price</th>
                  <th>Time</th>
                  <th>Crypto Name</th>
                  <th>Crypto Type</th>
                  <th>Current Price Per Unit</th>
                  <th>Currency</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($all_transactions as $row) : array_map('htmlentities', $row); ?>
                  <tr>
                      <td><?php echo htmlspecialchars($row['ID']); ?></td>
                      <td><?php echo htmlspecialchars($row['ACCOUNT_TYPE']); ?></td>
                      <td><?php echo htmlspecialchars($row['ACCOUNT_STATUS']); ?></td>
                      <td><?php echo htmlspecialchars($row['FNAME']); ?></td>
                      <td><?php echo htmlspecialchars($row['MNAME']); ?></td>
                      <td><?php echo htmlspecialchars($row['LNAME']); ?></td>
                      <td><?php echo htmlspecialchars($row['BALANCE']); ?>USD</td>
                      <td><?php echo htmlspecialchars($row['TRANS_ID'] ); ?></td>
                      <td><?php echo htmlspecialchars($row['TRANS_TYPE']); ?></td>
                      <td><?php echo htmlspecialchars($row['CRYPTO']); ?></td>
                      <td><?php echo htmlspecialchars($row['AMOUNT']); ?></td>
                      <td><?php echo htmlspecialchars($row['BS_PRICE']); ?>USD</td>
                      <td><?php echo htmlspecialchars($row['TIME']); ?></td>
                      <td><?php echo htmlspecialchars($row['NAME']); ?></td>
                      <td><?php echo htmlspecialchars($row['TYPE']); ?></td>
                      <td><?php echo htmlspecialchars($row['PRICE']); ?>USD</td>
                      <td><?php echo htmlspecialchars($row['CURRENCY']); ?></td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  </div>


    <div id="Customers" class="tabcontent">
    	
    <div>
        
        <h2>Deactivate Users</h2>
	
        <form action="deactivateuser.php"  method="post">
            Select User : <select list="list_user" name="select_user" required>
            <datalist id="list_user">
                <?php foreach ($All_Users as $row) : array_map('htmlentities', $row);
                if (($row['ACCOUNT_STATUS'] == 'A') and ($row['ACCOUNT_TYPE'] != 'Admin')){?>
                    <option><?php echo htmlspecialchars($row['ID']); ?></option>
                <?php }endforeach; ?>
            </datalist>
	        </select>
            <br>
            <button type="submit">Deactivate User</button>
            <br>
            <br>
        </form>
    </div>

        

        <div>
        <h2>All Customers</h2>
        <table border=1 cellspacing=5 cellpadding=5>
            <thead>
                <tr>
                  <th>User ID</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Last Name</th>
                  <th>Balance</th>
                  <th>Account Type</th>
                  <th>Account Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($All_Users as $row) : array_map('htmlentities', $row); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['FNAME']); ?></td>
                        <td><?php echo htmlspecialchars($row['MNAME']); ?></td>
                        <td><?php echo htmlspecialchars($row['LNAME']); ?></td>
                        <td><?php echo htmlspecialchars($row['BALANCE']); ?></td>
                        <td><?php echo htmlspecialchars($row['ACCOUNT_TYPE']); ?></td>
                        <td><?php echo htmlspecialchars($row['ACCOUNT_STATUS']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <script>
        function openTab(evt, cityName) {
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