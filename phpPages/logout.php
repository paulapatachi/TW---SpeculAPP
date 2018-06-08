<?php
	session_start();
	try{
		update_sesion();
	}catch(Exception $e){
		session_destroy();
		header("Location: home.php");
	}
	session_destroy();
	header("Location: home.php");
	
	function update_sesion(){
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'DECLARE 
					v_dateend DATE; 
					BEGIN 
					select sysdate into v_dateend from dual;
					update sesion set endtime=v_dateend where sesion_id=:sid;
					END;');
		if (!$stid) {
			$e = oci_error($conn);
			throw new Exception;
		}
		$sid=$_SESSION["sesion"];
		oci_bind_by_name($stid,':sid',$sid);
		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			throw new Exception;
		}
	}
?>