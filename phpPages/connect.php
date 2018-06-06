<?php
	session_start();
	if (!$_REQUEST["email"]||!$_REQUEST["password"]) { 
		header("Location: failure.html");
	} 
	try{
		$connected=connect($_REQUEST["email"],$_REQUEST["password"]);
	}catch(Exception $e){
		session_unset();
		header("Location: error_while_connecting.php");
	}
	if($connected){

		if($_SESSION["uid"]==1){
			header("Location: admin.php");
		}else{
			header("Location: choice.php");
		}
	}else{
		header("Location: failure.html");
	}
	function connect($email,$password){
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT EMAIL, PASSWORD, USER_ID FROM USERS');
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
		$connected=false;
		while ($row = oci_fetch_array($stid, OCI_NUM)) {
			if($row[0]==$email&&$row[1]==$password){
				$connected=true;
				$_SESSION["logged"]=true;
				$_SESSION["email"]=$row[0];
				$_SESSION["password"]=$row[1];
				$_SESSION["uid"]=$row[2];				
				$sti = oci_parse($conn, 'SELECT MAX(SESION_ID) FROM SESION ');
				if (!$sti) {
					$e = oci_error($conn);
					throw new Exception;
				}
				// Perform the logic of the query
				$re	= oci_execute($sti);
				if (!$re) {
					$e = oci_error($sti);
					throw new Exception;
				}
				$ro=oci_fetch_array($sti,OCI_NUM);
				$_SESSION["sesion"]=$ro[0]+1;
				//print($ro[0].' '.$_SESSION["sesion"]);
				oci_free_statement($sti);
				$sti = oci_parse($conn, 'DECLARE 
					v_datestart DATE; 
					BEGIN 
					select sysdate into v_datestart from dual;
					insert into Sesion values(:newsesion, :myid, v_datestart, v_datestart);
					END;');
				$newSesionId=$_SESSION["sesion"];
				$id=$_SESSION["uid"];
				oci_bind_by_name($sti,':myid',$id);
				oci_bind_by_name($sti,':newsesion',$newSesionId);
				if (!$sti) {
					$e = oci_error($conn);
					throw new Exception;
				}
				// Perform the logic of the query
				$re = oci_execute($sti);
				if (!$re) {
					$e = oci_error($sti);
					throw new Exception;
				}
				oci_free_statement($sti);
				break;
			}
		}
		return $connected;
	}
?> 