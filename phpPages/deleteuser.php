<?php	
	session_start();
	
	$userid=$_REQUEST["delete"];
	if($userid==1){
		header("Location: admin.php");
	}
	$conn = oci_connect('speculapp', 'SPECULAPP', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Prepare the statement
$stid = oci_parse($conn, 'DELETE FROM USERS WHERE USER_ID=:userid');
oci_bind_by_name($stid,':userid',$userid);
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