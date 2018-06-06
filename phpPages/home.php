<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Homepage</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/navbar.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/home.css" />
	<link rel="icon" href="assets/img/favicon.jpg">
</head>

<body>
	<nav>
		<ul class="navigation">
			<?php	
				if(isset($_SESSION["logged"])){
					echo '<li><a class="active" href="choice.php">Home</a></li>';
				}
				else{
					echo '<li><a class="active" href="home.php">Home</a></li>';
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
			?> 
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
	<img src="assets/img/speculapp.png" id="logo" alt="SpeculAPP Logo">

	
</body>
</html>