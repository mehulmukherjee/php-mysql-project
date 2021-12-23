<?php
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Style.css"/>
</head>

<body>

    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'Login')">Login</button>
        <button class="tablinks" onclick="openTab(event, 'SignUp')">SignUp</button>
        <button class="tablinks" style = "float:right">CRYPTO CURRENCY TRADING SYSTEM - 431W FINAL PROJECT</button>
    </div>

    <div id="Login" class="tabcontent">
		<br><h2>Login:</h2>
        
		<form action="authenticate.php" method="post">
			<table>
				<tr><td>Login ID :</td><td><input type="text" id="loginid" name="loginid" value=""></td></tr>
                <tr><td>Password :</td><td><input type="password" id="password" name="password" value=""></td></tr>
			</table>
            <button type="submit">Login</button>

		</form>
        <h4><?php echo $msg; ?></h4>
		<br>
    </div>

    <div id="SignUp" class="tabcontent">
    
    <br><h2>SignUp:</h2>
		<form action="signup.php" method="post">
			<table>
				<tr><td><label>First name :</td><td><input type="text" id="fname" name="fname" value=""></td></tr>
                <tr><td><label>Middle name :</td><td><input type="text" id="mname" name="mname" value=""></td></tr>
				<tr><td><label>Last name :</td><td><input type="text" id="lname" name="lname" value=""></td></tr>
				<tr><td><label>Login ID :</td><td><input type="text" id="loginid" name="loginid" value=""></td></tr>
               <tr><td><label>Password :</td><td><input type="password" id="password" name="password" value=""></td></tr>
               <tr><td><label>Balance :</td><td><input type="text" id="balance" name="balance" value=""></td></tr>
			</table>
            <button type="submit">SignUp</button>
		</form>
		<br>
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