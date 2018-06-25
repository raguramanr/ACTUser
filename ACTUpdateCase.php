<html>
<body>
<link href="style1.css" rel="stylesheet" type="text/css">
<form method="post" name="frm" action="<?php echo $PHP_SELF?>">

<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('chk[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>

<?php
include 'db_connect.php';
include 'common.php';

#########################################################################################
#											#
#  Function to display the Testcase from Database, Also sets appropriate del/mod flags	#
#											#
#########################################################################################

function dispTCase($viewType, $act_mod_name) {
include 'db_connect.php';
include 'common.php';

if ($viewType == "assignCase") {
  $formDirect=$PHP_SELF;
}

$caseDirect="ACTModifyCase.php";

echo "<br><br><br><center>";
echo "<b><font size=2>Listing Test cases for feature: $_GET[act_mod_name]</font></b><br><br></center>";

# Displaying the Options
echo "<table border=1>\n";
echo "<tr><td class=a>Mark Status</td>";
echo "<td><SELECT name=testStatus>";
echo "<OPTION VALUE=\"Not-Automatable\">Not-Automatable</OPTION>";
echo "<OPTION SELECTED VALUE=\"Scripted\">Scripted</OPTION>";
echo "<OPTION VALUE=\"Review\">Review</OPTION>";
echo "<OPTION VALUE=\"Rework\">Rework</OPTION>";
echo "<OPTION VALUE=\"Checked-In\">Checked-In</OPTION>";
echo "<OPTION VALUE=\"Existing\">Existing</OPTION>";
echo "<OPTION VALUE=\"DEBUG\">Dev/Debug</OPTION>";
echo "<OPTION VALUE=\"QUICK\">Quick/AllPass</OPTION>";
echo "</td></tr></SELECT></table><br><br>";

$sort_field="act_test_case_no asc";

# Displaying the Testcase with Checkbox
if ($_GET[act_test_status] != "") {
  $sql = "select * from $test_report_db where act_test_module=\"$_GET[act_mod_name]\" and act_test_assigned_to='$User' and act_test_status=\"$_GET[act_test_status]\" order by $sort_field";
} else {
  $sql = "select * from $test_report_db where act_test_module=\"$_GET[act_mod_name]\" and act_test_assigned_to='$User' order by $sort_field";
}

if ($_GET[act_test_status] == "Sanity") {
  $sql = "select * from $test_report_db where act_test_module=\"$_GET[act_mod_name]\" and act_test_assigned_to='$User' and act_test_priority like '%Sanity%' and act_test_status='$assigned' order by $sort_field";
}

$result = $conn->query($sql);

$count=0;
$c=0;
if ($result->num_rows > 0) {
echo "<table width=\"100%\" border=1>\n";
echo "<tr>
	<td class=a><input type=\"checkbox\" onClick=\"toggle(this)\" /> <br/></td>
	<td class=a>Testcase ID</td>
	<td class=a>Project</td>
	<td class=a>Module</td>
	<td class=a>Testsuite</td>
	<td class=a>Title</td>
	<td class=a>Release ID</td>
	<td class=a>Priority</td>
	<td class=a>Status</td>
	<td class=a>Toplogy</td>
	<td class=a>Script Name</td>
	<td class=a>CR Details</td>
	<td class=a>Test Result</td>
	<td class=a>Comments</td>
	<td class=a>Regression</td>
       </tr>\n";
     while($row = $result->fetch_assoc()) {
        printf("<tr>
		<td><input type=checkbox name=chk[] value=%s>
                <td class=$row[act_test_status]><a href=\"%s?act_test_case_no=%s&modCase=yes\">%s</td>
                <td class=$row[act_test_status]><a href=\"%s?act_test_case_no=%s&modCase=yes\">%s</td>
                <td class=$row[act_test_status]><a href=\"%s?act_test_case_no=%s&modCase=yes\">%s</td>
                <td class=$row[act_test_status]><a href=\"%s?act_test_case_no=%s&modCase=yes\">%s</td>
                <td class=$row[act_test_status]><a href=\"%s?act_test_case_no=%s&modCase=yes\">%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		<td class=$row[act_test_status]>%s</td>
		</tr>\n",
		$row["act_test_case_no"], 
		$caseDirect, $row["act_test_case_no"], $row["act_test_case_id"], 
		$caseDirect, $row["act_test_case_no"], $row["act_test_project"], 
		$caseDirect, $row["act_test_case_no"], $row["act_test_module"], 
		$caseDirect, $row["act_test_case_no"], $row["act_test_suite"], 
		$caseDirect, $row["act_test_case_no"], $row["act_test_title"], 
		$row["act_test_release_id"], 
		$row["act_test_priority"], 
		$row["act_test_status"], 
		$row["act_test_topology"], 
		$row["act_test_script_name"], 
		$row["act_test_defect_id"], 
		$row["act_test_result"], 
		$row["act_test_comments"],
		$row["act_test_regression"]
	       );
     $c = $c + 1;
     $count++;
    }
} else {
}
echo "</table><br><br><b><center><font size=2>Total Testcases : ". $c ." <br><br>";
printf("<input type=hidden name=totalCase value=%s\n", $count);
  if ($c != 0) {
	echo "<br><center><input type=\"Submit\" name=\"submit\" value=\"Update Testcase\">";
  } else  {
  }
$conn->close();
}

#########################################################################################
#                                                                                       #
# Handle Submit Information and Update the user record accordingly                      #
#                                                                                       #
#########################################################################################

if ($_REQUEST[submit]) {
if(!empty($_POST['chk'])) {
    foreach($_POST['chk'] as $tCaseNo) {
	
	$testStatus = $_POST[testStatus];
	if ($testStatus == "Scripted") {
          $sql = "update $test_report_db set act_test_status='$_POST[testStatus]', act_test_scripted_date='$today' where act_test_case_no='$tCaseNo'";
 	} elseif ($testStatus == "Rework") {
	  $sql = "update $test_report_db set act_test_status='$_POST[testStatus]', act_test_rework_date='$today' where act_test_case_no='$tCaseNo'";
 	} elseif ($testStatus == "Review") {
	  $sql = "update $test_report_db set act_test_status='$_POST[testStatus]', act_test_review_date='$today' where act_test_case_no='$tCaseNo'";
 	} elseif ($testStatus == "Checked-In") {
	  $sql = "update $test_report_db set act_test_status='$_POST[testStatus]', act_test_checkin_date='$today' where act_test_case_no='$tCaseNo'";
 	} elseif ($testStatus == "Not-Automatable" || $testStatus == "Existing") {
	  $sql = "update $test_report_db set act_test_status='$_POST[testStatus]', act_test_checkin_date='0000-00-00', act_test_rework_date='0000-00-00', act_test_scripted_date='0000-00-00', act_test_review_date='0000-00-00' where act_test_case_no='$tCaseNo'";
        } elseif ($testStatus == "QUICK" || $testStatus == "DEBUG") {
          $sql = "update $test_report_db set act_test_regression='$_POST[testStatus]' where act_test_case_no='$tCaseNo'";
        } else {
	  echo "No options Selected";
	}

        // echo $sql;
       if ($conn->query($sql) === TRUE) {
       } else {
          echo "Error updating record: " . $conn->error;
       }
    }
}
$conn->close();
}

#########################################################################################
#											#
# Code to check whether Delete View, Modify View or Normal View Needs to be shown       #
#											#
#########################################################################################
if ($_GET[showcases == "yes"]) {
      dispTCase("assignCase",$_GET[act_mod_name]); 
} else {
      dispTCase("dummy"); 
}

?>
</body>
</html>
