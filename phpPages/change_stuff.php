<?php
		session_start();
	if(!isset($_SESSION["logged"])){
		header("Location: login.php");
		
	}
	if(isset($_SESSION["logged"])){
		if($_SESSION["uid"]>1){
			header("Location: access_denied.php");;
					}
		}
	try{
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT WIN_SUM,LOSE_SUM FROM SETTINGS');
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
		$row = oci_fetch_array($stid, OCI_NUM);
		$winsum=$row[0];
		$losesum=$row[1];
		oci_free_statement($stid);
		$stid = oci_parse($conn, "SELECT EXCHANGE_RATE FROM CURRENCY WHERE TRIGRAMM='USD'");
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
		$row = oci_fetch_array($stid, OCI_NUM);
		$usdrate=$row[0];
		oci_free_statement($stid);
		$stid = oci_parse($conn, "SELECT EXCHANGE_RATE FROM CURRENCY WHERE TRIGRAMM='EUR'");
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
		$row = oci_fetch_array($stid, OCI_NUM);
		$eurrate=$row[0];
		oci_free_statement($stid);
	}catch(Exception $e){
		header("Location: error_while_connecting.php");
	}		
?>

<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/navbar.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/changestuff.css" />
	<link rel="icon" href="assets/img/favicon.jpg">
</head>
<body>
	<nav>
		<ul class="navigation">
			<?php	
				if(isset($_SESSION["logged"])){
					if($_SESSION["uid"]==1){
						echo '<li><a href="admin.php">Home</a></li>';
					}

				}
				else{
					
					echo '<li><a href="home.php">Home</a></li>';
					
				}
			?> 
			<li><a href="contact.php">Contact</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="login.php">Login</a></li>
			<li><a href="signup.php">Signup</a></li>
			<?php	
				if(isset($_SESSION["logged"])){
					echo '<li style="float:right"><a href="logout.php">Logout</a></li>';
				}
			?> </li> 
			
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
		<div class="board">
		<p>current win sum: <?php echo htmlspecialchars($winsum); ?></p>
		<form action="changewinsum.php">
				<input type="text" class="delete" name="delete" >
				<input type="submit" value="Change win sum" class="sub">
		</form >
		<p>current lose sum: <?php echo htmlspecialchars($losesum); ?></p>
		<form action="changelosesum.php">
				<input type="text" class="delete" name="delete" >
				<input type="submit" value="Change lose sum" class="sub">
		</form>
		<p>dollar exchange rate: <?php echo htmlspecialchars($usdrate); ?></p>
		<form action="changeusdrate.php">
				<input type="text" class="delete" name="delete" >
				<input type="submit" value="Change dollar rate" class="sub">
		</form>
		<p>euro exchange rate: <?php echo htmlspecialchars($eurrate); ?></p>
		<form action="changeeurrate.php">
				<input type="text" class="delete" name="delete" >
				<input type="submit" value="Change euro rate" class="sub">
		</form>
		
		
	</div>
</body>
</html>