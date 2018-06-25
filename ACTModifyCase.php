<html>
<body>
<link href="style1.css" rel="stylesheet" type="text/css">
<form method="post" name="frm" action="<?php echo $PHP_SELF?>">
<?php

#########################################################################################
#											#
#  Function to display the Testcase from Database, Also sets appropriate del/mod flags	#
#											#
#########################################################################################

function dispTCase($viewType, $act_test_case_no, $home_url) {
include 'db_connect.php';
include 'common.php';

$sql = "select * from $test_report_db where act_test_case_no='$act_test_case_no'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
echo "<br><br><br><center>";
echo "<b><font size=2>Listing Details of Testcase : $act_test_case_no</font></b><br><br>";
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
	<td class=a>Regression</td>
       </tr>\n";
     while($row = $result->fetch_assoc()) {
        printf("<tr>
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
		<td>%s</td>
		</tr>\n",
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
		$row["act_test_regression"] 
	       );
     $c = $c + 1;
    
     ## Printing table header
     echo "</table><br><br>";
     echo "<center><b><font size=2>Update Status<br><br>";
     echo "<table border=1>\n";
      

     ## Passing the Home URL to the submit function
     echo "<input type=hidden name=homeurl value=$home_url>";

     ## Printing to change the Release ID
     echo "<tr><td class=a>Release ID</td><td><input type=Text name=act_test_release_id value=$row[act_test_release_id]></td></tr>";

     ## Printing to change the Priority
     echo "<tr><td class=a>Priority</td><td>$row[act_test_priority]</td></tr>";
   
     ## Printing to change the Toplogy
     echo "<tr><td class=a>Topology</td><td><input type=Text name=act_test_topology value=\"$row[act_test_topology]\"></td></tr>";

     ## Printing the Option to Mark the Testcase
     echo "<tr><td class=a>Test Status</td><td>$row[act_test_status]</td></tr>";

     ## Commenting the option to change of Test status here 
     ## echo "<tr><td class=a>Test Status</td>";
     ## echo "<td><SELECT name=act_test_status>";
     ## echo "<OPTION VALUE=\"Not-Automatable\">Not-Automatable</OPTION>";
     ## echo "<OPTION SELECTED VALUE=\"Scripted\">Scripted</OPTION>";
     ## echo "<OPTION VALUE=\"Review\">Review</OPTION>";
     ## echo "<OPTION VALUE=\"Rework\">Rework</OPTION>";
     ## echo "<OPTION VALUE=\"Checked-In\">Checked-In</OPTION>";
     ## echo "</td></tr></SELECT>";
	
     ## Printing to change the Script Name
     echo "<tr><td class=a>Script Name</td><td><input type=Text name=act_test_script_name value=\"$row[act_test_script_name]\"></td></tr>";

     ## Printing to change the CR Details
     echo "<tr><td class=a>Defect ID</td><td><input type=Text name=act_test_defect_id value=\"$row[act_test_defect_id]\"></td></tr>";

     ## Printing the Option to Mark the Testcase
     echo "<tr><td class=a>Test Result</td>";
     echo "<td><SELECT name=act_test_result>";
     echo "<OPTION SELECTED VALUE=\"$row[act_test_result]\">$row[act_test_result]</OPTION>";
     if ($row[act_test_result] != "Pass") {
          echo "<OPTION VALUE=\"Pass\">Pass</OPTION>";
     }
     if ($row[act_test_result] != "Fail") {
          echo "<OPTION VALUE=\"Fail\">Fail</OPTION>";
     }
     if ($row[act_test_result] != "Blocked") {
          echo "<OPTION VALUE=\"Blocked\">Blocked</OPTION>";
     }
     if ($row[act_test_result] != "Not-Executed") {
          echo "<OPTION VALUE=\"Not-Executed\">Not-Executed</OPTION>";
     }
     echo "</td></tr></SELECT>";

     ## Printing to change the Regression Status
     echo "<tr><td class=a>Regression </td><td><input type=Text name=act_test_regression value=\"$row[act_test_regression]\"></td></tr>";

     ## Printing the Comment Box
     //echo "<tr><td class=a>Comments</td><td><input align=\"top\" type=textarea name=act_test_comments maxlength=255 style=\"height: 100px;\" size=\"50\" value=$row[act_test_comments]></td></tr>";
     echo "<tr><td class=a>Comments</td><td><textarea name=\"act_test_comments\" style=\"width: 200px\; height: 100px\" rows=\"10\" cols=\"80\">{$row[act_test_comments]}</textarea></td></tr>";

     ## Printing table footer 
     echo "</table><br><br>"; 
     echo "<br><center><input type=\"Submit\" name=\"submit\" value=\"Update Testcase\">";

    }
} else {
    echo "<center><b><font size=2>0 Testcases found";
}
$conn->close();
}

#########################################################################################
#                                                                                       #
# Handle Submit Information and Update the user record accordingly                      #
#                                                                                       #
#########################################################################################
if ($_REQUEST[submit]) {
include 'db_connect.php';
include 'common.php';
	$tCaseNo    = $_GET[act_test_case_no];
        $releaseID  = $_POST[act_test_release_id];
        $priority   = $_POST[act_test_priority];
	$topology   = $_POST[act_test_topology];
        ## $testStatus = $_POST[act_test_status];
	$scriptName = $_POST[act_test_script_name];
	$defectID   = $_POST[act_test_defect_id];
        $testResult = $_POST[act_test_result];
        $testRegression = $_POST[act_test_regression];
        $testComment = $_POST[act_test_comments];
        $homeurl = $_POST[homeurl];
      

       $sql = "update $test_report_db set 
		act_test_release_id='$releaseID', 
		act_test_topology='$topology',
		act_test_script_name='$scriptName', 
		act_test_defect_id='$defectID',
		act_test_result='$testResult',
		act_test_comments='$testComment',
		act_test_regression='$testRegression'  
		where act_test_case_no='$tCaseNo'";

       // echo $sql;
       if ($conn->query($sql) === TRUE) {
         echo "<b><br><br><center><font size=2> Record updated Successfully. Redirecting to parent page </font>";
         header ("Location: $homeurl");
       } else {
         echo "Error updating record: " . $conn->error;
       }
$conn->close();
      #echo "Redirecting to Home URL after Submit: $homeurl";
}


#########################################################################################
#											#
# Code to check whether Delete View, Modify View or Normal View Needs to be shown       #
#											#
#########################################################################################
if ($_GET[modCase] == "yes") {
      $home_url =  $_SERVER['HTTP_REFERER'];
      #echo $home_url;
      dispTCase("modView",$_GET[act_test_case_no],$home_url); 
} else {
      #dispTCase("dummy"); 
}

?>
</body>
</html>
