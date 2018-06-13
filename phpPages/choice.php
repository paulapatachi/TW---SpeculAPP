<?php

	session_start();
	if(!isset($_SESSION["logged"])){
		header("Location: login.php");
	}
	$wins=25;
	$losses=23;
	try{
		how_many();
	}catch(Exception $e){
		header("Location: generic_error.php");
	}
	function how_many(){
		global $wins,$losses;
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT COUNT(*) FROM GAME WHERE USER_ID=:id AND OUTCOME=1');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		$id=$_SESSION["uid"];
		oci_bind_by_name($stid,':id',$id);
		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
		// Fetch the results of the query
		$row = oci_fetch_array($stid, OCI_NUM);
		$wins=$row[0];
		//free the statement
		oci_free_statement($stid);
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT COUNT(*) FROM GAME WHERE USER_ID=:id AND OUTCOME=0');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		$id=$_SESSION["uid"];
		oci_bind_by_name($stid,':id',$id);
		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
		// Fetch the results of the query
		$row = oci_fetch_array($stid, OCI_NUM);
		$losses=$row[0];
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Specul-APP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/navbar.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/user.css" />
	<link rel="icon" href="assets/img/favicon.jpg" />

	<script type="text/javascript">
				var wins = "<?php echo $wins; ?>";
				var losses= "<?php echo $losses; ?>";
				window.onload=function () {
					var chart = new CanvasJS.Chart("upperleft",
					{
						theme: "theme2",
						title:{
							text: "Games Won/Lost"
						},
						data: [
						{
							type: "pie",
							//showInLegend: true,
							toolTipContent: "{y} - #percent %",
							yValueFormatString: "#0.#",
							legendText: "{indexLabel}",
							dataPoints: [
								{ y: wins, indexLabel: "Wins" },
								{ y: losses, indexLabel: "Losses" }
							]
						}
						]
					});
					chart.render();
				}
			</script>
	<script src="canvasjs.min.js"></script>


	<script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>

	<script type="text/javascript">
		$(function () {

	    var specialElementHandlers = {
	        '#buttonlist': function (element,renderer) {
	            return true;
	        }
	    };
	 $('#forpdf').click(function () {
	        var doc = new jsPDF();
	        doc.fromHTML(
	            $('#center').html(), 15, 15, 
	            { 'width': 170, 'elementHandlers': specialElementHandlers }, 
	            function(){ doc.save('pdf_top.pdf'); }
	        );

	    }); 

	 $('#forjson').click(function () {
	 	//  This gives you an HTMLElement object
		var element = document.getElementById('center');
		//  This gives you a string representing that element and its content
		var html = element.outerHTML;
		var json = JSON.stringify({html:html})

		var hiddenElement = document.createElement('a');

		hiddenElement.href = 'data:attachment/json,' + encodeURI(json);
		hiddenElement.target = '_blank';
		hiddenElement.download = 'json_top.json';
		hiddenElement.click();
    });

	    $('#forhtml').click(function () {
	    	var elHtml = document.getElementById('center').innerHTML;
		    var link = document.createElement('a');
		    mimeType = 'text/plain';

		    link.setAttribute('download', 'html_top.html');
		    link.setAttribute('href', 'data:' + mimeType  +  ';charset=utf-8,' + encodeURIComponent(elHtml));
		    link.click(); 
			    });  
			});
	</script>

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

	<div class="console">
		<div id="upperleft">
		</div>
		
		<div id="center">
			<p style="font-weight: bold; font-size:34px;"> Best three players: </p>
			<?php
			try{
				$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
				if (!$conn) {
					throw new Exception;
				}
				// Prepare the statement
				$stid = oci_parse($conn, 'SELECT * FROM TOP_TRADERS');
				if (!$stid) {
					throw new Exception;
				}
				// Perform the logic of the query
				$r = oci_execute($stid);
				if (!$r) {
					throw new Exception;
				}
				$statement=oci_parse($conn,'SELECT FIRST_NAME, LAST_NAME FROM USERS WHERE USER_ID=:userid');
				if (!$statement) {
					throw new Exception;
				}
				echo '<table id="best">';
				echo '<tr><th>Name</th><th>Win Ratio</th></tr>';
				while($row=oci_fetch_array($stid,OCI_NUM)){
					$userid=$row[0];
					$ratio=$row[1];
					oci_bind_by_name($statement,':userid',$userid);
					$result=oci_execute($statement);
					if(!$result){
						throw new Exception;
					}
					$arr=oci_fetch_array($statement,OCI_NUM);
					$name=$arr[0].' '.$arr[1];
					echo '<tr><td>'.$name.'</td><td>'.$ratio.'% </td></tr>';
				}
				echo '</table>';
				oci_free_statement($statement);
				oci_free_statement($stid);
			}catch(Exception $e){
				header("Location: generic_error.php");
			}
		?>
			<div id="buttonlist">
				<button id="forhtml" type="button">Download HTML</button>
				<button id="forjson" type="button">Download JSON</button>
				<button id="forpdf" type="button">Download PDF</button>
			</div>
		</div>
		
		<div id="upperright">
			<p style="padding-left:5em"><?php echo $_SESSION["email"] ?></p>
			<form action="game.php" method="get">
				<button class="newgame" type="submit">New Game</button>
			</form>
		</div>
	</div>

</body>
</html>