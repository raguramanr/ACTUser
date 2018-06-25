<html>
<body>
<link href="style1.css" rel="stylesheet" type="text/css">
<?php

#########################################################################################
#											#
#  Function to display the Testcase from Database, Also sets appropriate del/mod flags	#
#											#
#########################################################################################

function dispTCase($viewType, $act_mod_name) {
include 'db_connect.php';
include 'common.php';

if ($viewType == "delView") {
  $reqAction="delete";
  $actFlag="yes";
  $cnfrmFlag="onClick";
  $formDirect=$PHP_SELF;
} elseif ($viewType == "modView") {
  $reqAction="modCase";
  $actFlag="yes";
  $cnfrmFlag="dummy";
  $formDirect=$PHP_SELF;
} else {
  $reqAction="action";
  $actFlag="nothing";
  $cnfrmFlag="dummy";
  $formDirect=$PHP_SELF;
}

if ($_GET[delCase] == "yes" || $_GET[modCase] == "yes" || $act_mod_name != "" || $_GET[action] == "nothing" ) {
 $sql = "select * from $test_report_db where act_test_module=\"$_GET[act_mod_name]\" and act_test_assigned_to='$User'";
} else {
 $sql = "select * from $test_report_db";
}



$result = $conn->query($sql);

if ($result->num_rows > 0) {
echo "<br><br><br><center>";
echo "<b><font size=2>Listing Test cases</font></b><br><br>";
echo "<table border=1>\n";
echo "<tr>
	<td class=a>Testcase ID</td>
	<td class=a>Project</td>
	<td class=a>Module</td>
	<td class=a>Testsuite</td>
	<td class=a>Title</td>
	<td class=a>Release ID</td>
	<td class=a>Priority</td>
	<td class=a>Status</td>
	<td class=a>Toplogy</td>
	<td class=a>Scripted On</td>
	<td class=a>Reviewed On</td>
	<td class=a>Reworked On</td>
	<td class=a>Checkin On</td>
	<td class=a>Script Name</td>
	<td class=a>CR Details</td>
	<td class=a>Test Result</td>
	<td class=a>Comments</td>
	<td class=a>Assigned To</td>
       </tr>\n";
     while($row = $result->fetch_assoc()) {
        printf("<tr>
		<td><a href=\"%s?act_mod_name=%s&act_test_case_no=%s&$reqAction=$actFlag\" $cnfrmFlag=\"return confirm('Sure? Once deleted, the record CANNOT be reverted.')\">%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		</tr>\n",
		$formDirect,
	 	$_GET[act_mod_name],
		$row["act_test_case_no"], 
		$row["act_test_case_id"], 
		$row["act_test_project"], 
		$row["act_test_module"], 
		$row["act_test_suite"], 
		$row["act_test_title"], 
		$row["act_test_release_id"], 
		$row["act_test_priority"], 
		$row["act_test_status"], 
		$row["act_test_topology"], 
		$row["act_test_scripted_date"], 
		$row["act_test_review_date"], 
		$row["act_test_rework_date"], 
		$row["act_test_checkin_date"], 
		$row["act_test_script_name"], 
		$row["act_test_defect_id"], 
		$row["act_test_result"], 
		$row["act_test_comments"], 
		$row["act_test_assigned_to"] 
	       );
     $c = $c + 1;
    }
} else {
    echo "<center><b>0 Testcases found";
}
echo "</table><br><br><b><center><font size=2>Total Testcases : ". $c ." <br><br><br <br><br><br>";
$conn->close();
}

#########################################################################################
#											#
#  Function to delete the given module from database					#
#											#
#########################################################################################
function remCase($act_test_case_no) {
include 'db_connect.php';
include 'common.php';

$sql = "DELETE FROM $test_report_db WHERE act_test_case_no='$act_test_case_no'";
 if (mysqli_query($conn, $sql)) {
      echo "<br><br><b><center><font size=2>Testcase deleted successfully - Record Number ($act_test_case_no) <br><br>";
      dispTCase(D,$_GET[act_mod_name]);
 } else {
     echo "<br><br><b><center>Error deleting record: " . mysqli_error($conn);
 }
}

#########################################################################################
#                                                                                       #
#  Function to modify the given module from database                                    #
#                                                                                       #
#########################################################################################
function modCase($act_test_case_no) {
include 'db_connect.php';
include 'common.php';
echo "Request to edit the testcase $act_test_case_no";

//$sql = "DELETE FROM $act_test_case_no WHERE act_test_case_no='$act_test_case_no'";
// if (mysqli_query($conn, $sql)) {
//      echo "<br><br><b><center><font size=2>Testcase Modified successfully - Record Number ($act_mod_no) <br><br>";
//      dispTCase(D);
// } else {
//     echo "<br><br><b><center>Error deleting record: " . mysqli_error($conn);
// }
}


#########################################################################################
#											#
# Code to check whether Delete View, Modify View or Normal View Needs to be shown       #
#											#
#########################################################################################
if ($_GET[delCase]) {
      dispTCase("delView"); 
} elseif ($_GET[modCase]) {
      dispTCase("modView"); 
} elseif ($_GET[delete] == "yes" ) { 
      remCase($_REQUEST[act_test_case_no]);
} elseif ($_GET[modify] == "yes" ) { 
      modCase($_REQUEST[act_test_case_no]);
} elseif ($_GET[showcases] == "yes" ) {
      dispTCase("dummy",$_GET[act_mod_name]);
} else {
      dispTCase("dummy"); 
}

?>
</body>
</html>
