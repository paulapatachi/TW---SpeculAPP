<?php
	session_start();
	$losesum=$_REQUEST["delete"];
	if(!is_numeric($losesum)||$losesum<0){
		header('Location: change_stuff.php');
	}
	
	try{
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'UPDATE SETTINGS SET LOSE_SUM=:losesum');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		oci_bind_by_name($stid,':losesum',$losesum);
		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
		oci_free_statement($stid);
	}catch(Exception $e){
		header("Location: error_while_connecting.php");
	}		
	header("Location: change_stuff.php");
?>