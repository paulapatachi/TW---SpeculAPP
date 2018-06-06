<?php
	session_start();
	if(isset($_SESSION["logged"])){
		header("Location: already_logged_in.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/navbar.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/login.css" />
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
			<li><a class="active" href="login.php">Login</a></li>
			<li><a href="signup.php">Signup</a></li> 
			<?php	
				if(isset($_SESSION["logged"])){
					echo '<li><a href="logout.php">Logout</a></li>';
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
	<section id="loginform">
		<form id="myForm" method="post" action="connect.php" onsubmit="return myFunction()">
			<input type="text" id="name" name="email" placeholder="E-mail" ><br>
			<input type="password" id="surname" name="password" placeholder="Password" ><br>
			<input  type="submit" id="sub" value="Submit" >
			<p class="message">Not registered? <a href="signup.php">Create an account</a></p>
		</form> 
	</section>
	<p id="demo"></p>
	<script>
		function myFunction() {
			var x = document.getElementById("myForm");
			if(x.elements[0].value!=""&&x.elements[1].value!="") {return true;}
			else{
				var newHTML = '<div style="color:red; font-size:30px; text-align:center; font-weight:bold; padding:100px;"> \
				    All boxes must be filled! \
					</div>';
				document.getElementById("demo").innerHTML=newHTML;
				return false;
			}
		}
	</script>
</body>
</html>