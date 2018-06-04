<?php
	session_start();
	$rate=$_REQUEST["delete"];
	if(!is_numeric($rate)||$rate<0){
		header('Location: change_stuff.php');
	}
	try{
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'UPDATE CURRENCY SET EXCHANGE_RATE=:rate WHERE CURR_ID=3');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		oci_bind_by_name($stid,':rate',$rate);
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