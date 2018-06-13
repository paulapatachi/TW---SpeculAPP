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
?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/navbar.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/deletestuff.css" />
	<link rel="icon" href="assets/img/favicon.jpg">

	<link rel="stylesheet" href="http://tablesorter.com/docs/css/jq.css" type="text/css" media="print, projection, screen" />
	<link rel="stylesheet" href="http://tablesorter.com/themes/blue/style.css" type="text/css" media="print, projection, screen" />
	<script type="text/javascript" src="http://tablesorter.com/jquery-latest.js"></script>
	<script type="text/javascript" src="http://tablesorter.com/__jquery.tablesorter.js"></script>
	<script type="text/javascript" src="http://tablesorter.com/addons/pager/jquery.tablesorter.pager.js"></script>
	<script type="text/javascript" src="http://tablesorter.com/docs/js/chili/chili-1.8b.js"></script>
	<script type="text/javascript" src="http://tablesorter.com/docs/js/docs.js"></script>
	<script type="text/javascript">
	$(function() {
		$("#sessionTable")
			.tablesorter({widthFixed: true, widgets: ['zebra']})
			.tablesorterPager({container: $("#sessionpager")});
	});
	$(function() {
		$("#transactionTable")
			.tablesorter({widthFixed: true, widgets: ['zebra']})
			.tablesorterPager({container: $("#transactionpager")});
	});
	$(function() {
		$("#gameTable")
			.tablesorter({widthFixed: true, widgets: ['zebra']})
			.tablesorterPager({container: $("#gamepager")});
	});
	$(function() {
		$("#usersTable")
			.tablesorter({widthFixed: true, widgets: ['zebra']})
			.tablesorterPager({container: $("#userspager")});
	});
	</script>
	<link rel="stylesheet" type="text/css" href="http://tablesorter.com/docs/js/chili/javascript.css">

</head>
<body>
	<nav>
		<ul class="navigation">
			<?php	
				if(isset($_SESSION["logged"])){
					if($_SESSION["uid"]==1){
						echo '<li><a href="admin.php">Home</a></li>';
					}else{
						echo '<li><a href="choice.php">Home</a></li>';
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


<div class="lowleft">
<section class="deletesessionbutton">
			<form action="deletegame.php">
				<label for="delete3">Delete Game No.</label><br>
				<input type="text" id="delete3" name="delete" ><br>
				<input type="submit" value="Submit" class="sub">
			</form>
		</section>
			<!-- The transaction information table is created -->
			<?php
			$conn = oci_connect('speculapp', 'SPECULAPP', 'localhost/XE');
			if (!$conn) {
				$e = oci_error();
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$stid2 = oci_parse($conn, 'SELECT * FROM GAME');
			if (!$stid2) {
				$e = oci_error($conn);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			// Perform the logic of the query
			$r2 = oci_execute($stid2);
			if (!$r2) {
				$e = oci_error($stid2);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			// Fetch the results of the query
			print('<section id="sessiontable">');
			print('<table id="gameTable" class="tablesorter" border="1">');
			print('<thead><tr><th>Game ID</th><th>Sesion ID</th><th>User ID</th><th>Outcome</th><th>Total Sum</th><th>USD Sum</th><th>EUR Sum</th><th>RON Sum</th></tr></thead>');
			print('<tbody>');
			while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
				print "<tr>\n";
				foreach ($row as $item) {
					print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				print "</tr>\n";
			}
			print('</tbody>');
			print "</table>";
			print('</section>');

			oci_free_statement($stid2);

			oci_close($conn);
			?>

				<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
				<script type="text/javascript">
				_uacct = "UA-2189649-2";
				urchinTracker();
				</script>
</div>
<div class="lowright">
<section class="deletesessionbutton">
			<form action="deleteuser.php">
				<label for="delete4">Delete User No.</label><br>
				<input type="text" id="delete4" name="delete" ><br>
				<input type="submit" value="Submit" class="sub">
			</form>
		</section>
			<!-- The transaction information table is created -->
			<?php
			$conn = oci_connect('speculapp', 'SPECULAPP', 'localhost/XE');
			if (!$conn) {
				$e = oci_error();
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$stid2 = oci_parse($conn, 'SELECT * FROM USERS');
			if (!$stid2) {
				$e = oci_error($conn);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			// Perform the logic of the query
			$r2 = oci_execute($stid2);
			if (!$r2) {
				$e = oci_error($stid2);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			// Fetch the results of the query
			print('<section id="sessiontable">');
			print('<table id="usersTable" class="tablesorter" border="1">');
			print('<thead><tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Password</th></tr></thead>');
			print('<tbody>');
			while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
				print "<tr>\n";
				foreach ($row as $item) {
					print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				print "</tr>\n";
			}
			print('</tbody>');
			print "</table>";
			print('</section>');

			oci_free_statement($stid2);

			oci_close($conn);
			?>

				<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
				<script type="text/javascript">
				_uacct = "UA-2189649-2";
				urchinTracker();
				</script>
</div>

	<div class="upperleft">
		<section class="deletesessionbutton">
			<form action="deletesession.php">
				<label for="delete1">Delete Session No.</label><br>
				<input type="text" id="delete1" name="delete" ><br>
				<input type="submit" value="Submit" class="sub">
			</form>
		</section>

<!-- The session information table is created -->
<?php
$conn = oci_connect('speculapp', 'SPECULAPP', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$p = $_SESSION["uid"];

$stid = oci_parse($conn, 'begin :r := manager_sesiune.number_of_sessions(:p); end;');
oci_bind_by_name($stid, ':p', $p);
oci_bind_by_name($stid, ':r', $r, 40);

oci_execute($stid);

oci_free_statement($stid);

$stid2 = oci_parse($conn, 'SELECT * FROM SESION');
if (!$stid2) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r2 = oci_execute($stid2);
if (!$r2) {
    $e = oci_error($stid2);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Fetch the results of the query
print('<section id="sessiontable">');
print('<table id="sessionTable" class="tablesorter" border="1">');
print('<thead><tr><th>Session ID</th><th>User ID</th><th>Start time</th><th>End time</th></tr></thead>');
print('<tbody>');
while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    print "</tr>\n";
}
print('</tbody>');
print "</table>";
print('</section>');

oci_free_statement($stid2);

oci_close($conn);
?>
	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
	<script type="text/javascript">
	_uacct = "UA-2189649-2";
	urchinTracker();
	</script>
	</div>
	<!-- End of creation of session information table -->
	<div class="upperright">
		<section class="deletesessionbutton">
			<form action="deletetransaction.php">
				<label for="delete2">Delete Transaction No.</label><br>
				<input type="text" id="delete2" name="delete" ><br>
				<input type="submit" value="Submit" class="sub">
			</form>
		</section>
			<!-- The transaction information table is created -->
			<?php
			$conn = oci_connect('speculapp', 'SPECULAPP', 'localhost/XE');
			if (!$conn) {
				$e = oci_error();
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$stid2 = oci_parse($conn, 'SELECT * FROM TRANSACTION');
			if (!$stid2) {
				$e = oci_error($conn);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			// Perform the logic of the query
			$r2 = oci_execute($stid2);
			if (!$r2) {
				$e = oci_error($stid2);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			// Fetch the results of the query
			print('<section id="sessiontable">');
			print('<table id="transactionTable" class="tablesorter" border="1">');
			print('<thead><tr><th>Transaction ID</th><th>Sesion ID</th><th>Game ID</th><th>User ID</th><th>Curr ID 1</th><th>Curr ID 2</th><th>SUM 1</th><th>SUM 2</th><th>TIMP</th></thead>');
			print('<tbody>');
			while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
				print "<tr>\n";
				foreach ($row as $item) {
					print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				print "</tr>\n";
			}
			print('</tbody>');
			print "</table>";
			print('</section>');

			oci_free_statement($stid2);

			oci_close($conn);
			?>

				<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
				<script type="text/javascript">
				_uacct = "UA-2189649-2";
				urchinTracker();
				</script>
	</div>
	<!-- End of creation of transaction information table -->


</body>
</html>