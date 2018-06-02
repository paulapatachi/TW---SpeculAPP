<?php
	session_start();
	if(!isset($_SESSION["logged"])){
		header("Location: login.php");
	}

	//create new game with unknown outcome
	try{
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT MAX(GAME_ID) FROM GAME');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}	
		$row=oci_fetch_array($stid, OCI_NUM);
		$oldgameid=$row[0];
		$gameid=$oldgameid+1;
		oci_free_statement($stid);
		// Prepare the statement
		$stid=oci_parse($conn,'INSERT INTO GAME VALUES(:gameid,:sesionid,:userid,2,0,0,0,0)');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		oci_bind_by_name($stid,":gameid",$gameid);
		oci_bind_by_name($stid,":sesionid",$_SESSION["sesion"]);
		oci_bind_by_name($stid,":userid",$_SESSION["uid"]);
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
		oci_free_statement($stid);
		oci_close($conn);
	}catch(Exception $e){
		session_unset();
		header("Location: error_while_connecting.php");
	}
	//get average exchange rates
	try{
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT EXCHANGE_RATE FROM CURRENCY');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
		// Fetch the results of the query
		$row = oci_fetch_array($stid, OCI_NUM);
		$row = oci_fetch_array($stid, OCI_NUM);
		$usd_rate=$row[0];
		$row = oci_fetch_array($stid, OCI_NUM);
		$eur_rate=$row[0];
		oci_free_statement($stid);
		oci_close($conn);
	}catch(Exception $e){
		session_unset();
		header("Location: error_while_connecting.php");
	}
	//get win and loss sums
	try{
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT WIN_SUM, LOSE_SUM FROM SETTINGS');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
		$row=oci_fetch_array($stid,OCI_NUM);
		$win=$row[0];
		$lose=$row[1];
		oci_free_statement($stid);
		oci_close($conn);
	}catch(Exception $e){
		session_unset();
		header("Location: already_logged_in.php");
	}






	?>


<!DOCTYPE html>
<html>
<head>
	<title> New Game </title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/navbar.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/gamecss.css" />
	<link rel="icon" href="assets/img/favicon.jpg">
    
</head>
<body>
	<nav>
		<ul class="navigation">
			<?php	
				if(isset($_SESSION["logged"])){
					echo '<li><a href="choice.php">Home</a></li>';
				}
				else{
					echo '<li><a href="home.php">Home</a></li>';
				}
			?> 
			<li><a href="contact.php">Contact</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="login.php">Login</a></li>
			<li><a href="signup.php">Signup</a></li>
			<li style="float:right"><a href="logout.php">Logout</a></li> 
		</ul>
	</nav>
	<div class="responsive-bar">
		<h1>MENU</h1>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$("h1").click(function(){
				$("ul").slideToggle(500);
			})
		})
	</script>
	<div name="console" class="console">
	<div class="console1" id="console1">
	
	</div>
	<div name="console2" class="console2">

		

		<form class="ex" name="ex"  onsubmit="return calculate()">
		<fieldset>
			<legend>From</legend>
				<input type="radio" name="currency1" value="RON" checked> RON<br>
				<input type="radio" name="currency1" value="EUR"> EUR<br>
				<input type="radio" name="currency1" value="USD"> USD
		</fieldset>
		<fieldset>
			<legend>To</legend>
				<input type="radio" name="currency2" value="RON" checked> RON<br>
				<input type="radio" name="currency2" value="EUR"> EUR<br>
				<input type="radio" name="currency2" value="USD"> USD
		</fieldset>
		<fieldset>
		<legend>Sum</legend>
			Currency 1:<br>
			<input type="text" name="currency1sum" value="0"><br>
			<br>
		</fieldset>
			<input class="exchange" type="submit" value="convert">
		</form>
		<table border="1" style="margin-top:5px;width:100%;">
		<tr>
			<td>RON</td><td id="RON">1000</td>
		</tr>
		<tr>
			<td>USD</td><td id="USD">0</td>
		</tr>
		<tr>
			<td>EUR</td><td id="EUR">0</td>
		</tr>
		<tr>
			<td>TOTAL(RON)</td><td id="total"></td>
		</tr>
		</table>
	</div>
	</div>
	

</body>
</html>