<?php
	session_start();
	if(!$_REQUEST["firstname"]||!$_REQUEST["lastname"]||!$_REQUEST["email"]||!$_REQUEST["password"]){
		header("Location: singup.php");
	}
	try{
		if(!exists($_REQUEST["email"])){
			create_user();
			header("Location: user_created.php");
		}else{
			header("Location: signup.php");
		}
	}catch(Exception $e){
		session_unset();
		header("Location: error_while_connecting.php");
	}
	
	function exists($email){
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT EMAIL FROM USERS');
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
		$exists=false;
		while ($row = oci_fetch_array($stid, OCI_NUM)){
			if($row[0]==email){
				$exists=true;
			}
		}
		oci_free_statement($stid);
		oci_close($conn);
		return $exists;
	}
	
	function create_user(){
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		$stid = oci_parse($conn, 'SELECT MAX(USER_ID) FROM USERS');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
		$row=oci_fetch_array($stid, OCI_NUM);
		$id=$row[0];
		$newid=$id+1;
		$stid=oci_parse($conn,'INSERT INTO USERS VALUES(:id,:firstname,:lastname,:email,:password)');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		oci_bind_by_name($stid,":id",$newid);
		oci_bind_by_name($stid,":firstname",$_REQUEST["firstname"]);
		oci_bind_by_name($stid,":lastname",$_REQUEST["lastname"]);
		oci_bind_by_name($stid,":email",$_REQUEST["email"]);
		oci_bind_by_name($stid,":password",$_REQUEST["password"]);
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
	}
?>