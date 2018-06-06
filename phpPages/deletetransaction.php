<?php	
	session_start();
	
	$transactionid=$_REQUEST["delete"];
	$conn = oci_connect('speculapp', 'SPECULAPP', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Prepare the statement
$stid = oci_parse($conn, 'DELETE FROM TRANSACTION WHERE TRANSACTION_ID=:transactionid');
oci_bind_by_name($stid,':transactionid',$transactionid);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_free_statement($stid);
oci_close($conn);
header("Location: admin.php");
?>



