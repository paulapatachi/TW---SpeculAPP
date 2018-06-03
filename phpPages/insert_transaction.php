<?php	
	session_start();
	$gameid=$_REQUEST["gameid"];
	$curr1=$_REQUEST["curr1"];
	$curr2=$_REQUEST["curr2"];
	$sum=$_REQUEST["sum"];
	
	
	try{
		//create new transaction id
		$conn=oci_connect('speculapp','SPECULAPP','localhost/XE');
		if (!$conn) {
			$e = oci_error();
			throw new Exception;
		}
		// Prepare the statement
		$stid = oci_parse($conn, 'SELECT MAX(TRANSACTION_ID) FROM TRANSACTION');
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
		$row=oci_fetch_array($stid, OCI_NUM);
		$oldtransactionid=$row[0];
		$transactionid=$oldtransactionid+1;
		oci_free_statement($stid);
		
		//fetch currency IDes
		$stid=oci_parse($conn,'SELECT CURR_ID FROM CURRENCY WHERE TRIGRAMM=:curr');
		if(!$stid){
			throw new Exception;
		}
		oci_bind_by_name($stid,':curr',$curr1);
		$r=oci_execute($stid);
		if(!$r){
			throw new Exception;
		}
		$row=oci_fetch_array($stid, OCI_NUM);
		$currid1=$row[0];
		oci_bind_by_name($stid,':curr',$curr2);
		$r=oci_execute($stid);
		if(!$r){
			throw new Exception;
		}
		$row=oci_fetch_array($stid,OCI_NUM);
		$currid2=$row[0];
		oci_free_statement($stid);
		
		$stid=oci_parse($conn,'INSERT INTO TRANSACTION VALUES(:transactionid,:sessionid,:gameid,:userid,:curr1,:curr2,:sum1,:sum2,sysdate)');
		if(!$stid){
			throw new Exception;
		}
		oci_bind_by_name($stid,':transactionid',$transactionid);
		oci_bind_by_name($stid,':sessionid',$_SESSION["sesion"]);
		oci_bind_by_name($stid,':gameid',$gameid);
		oci_bind_by_name($stid,':userid',$_SESSION["uid"]);
		oci_bind_by_name($stid,':curr1',$currid1);
		oci_bind_by_name($stid,':curr2',$currid2);
		oci_bind_by_name($stid,':sum1',$sum);
		oci_bind_by_name($stid,':sum2',$sum);
		$r=oci_execute($stid);
		if(!$r){
			throw new Exception;
		}
		oci_free_statement($stid);
	}catch(Exception $e){
	
	}
?>