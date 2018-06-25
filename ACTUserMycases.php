<html>
<body>
<link href="style1.css" rel="stylesheet" type="text/css">
<?php

#########################################################################################
#                                                                                       #
#  Function to return start and end date of the week from specified date                #
#                                                                                       #
#########################################################################################
function x_week_range($date) {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    return array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next saturday', $start)));
}

#########################################################################################
#                                                                                       #
#  Function to return data from database   		                                #
#                                                                                       #
#########################################################################################
function getDetail($sql) {
include 'db_connect.php';
include 'common.php';
    //echo "<br> $sql";
    $result = $conn->query($sql);
       if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              $value = $row['value'];
          }
          mysql_close();
       } else {
        $errmsg = "connection failed.";
        $value = 0;
    }
    return $value;
    //echo "<br>Called function returning value $value to mainfunction getCount";
}


#########################################################################################
#                                                                                       #
#  Function with queries to get the counter                                             #
#                                                                                       #
#########################################################################################
function getCount($db, $act_test_module, $user, $act_test_status) {
   $count = getDetail("select count(*) as value from $db where act_test_module='$act_test_module' and act_test_assigned_to='$user' and act_test_status='$act_test_status'");
   //echo "<br><br>Count $act_test_module, $act_test_status is $count";
   return $count;
}

########################################################################################
#											#
#  Function to display the Modules from Database, Also sets appropriate del/mod flags	#
#											#
#########################################################################################

function dispModule($viewType) {
include 'db_connect.php';
include 'common.php';

if ($viewType == "D") {
  $reqAction="delete";
  $actFlag="yes";
  $cnfrmFlag="onClick";
  $formDirect=$PHP_SELF;
} else {
  $reqAction="showcases";
  $actFlag="yes";
  $cnfrmFlag="dummy";
  $formDirect="ACTUpdateCase.php";
}


# Print last 4 weeks Target/Execution Data
$date = $today;
echo "<br><br><center>";
echo "<b><font size=2>Last 4 Week Scripting Status [Target/Week - $target]</font></b><br><br>";
echo "<table border=1>\n";
echo "<tr>
      <td class=a width=100 align=center>Current Week</td>
      <td class=a width=100 align=center>Last Week</td>
      <td class=a width=100 align=center>Last -1</td>
      <td class=a width=100 align=center>Last -2</td>
      </tr><tr>";
for ($week = 1; $week <= 4; $week++) {
     list($start_date, $end_date) = x_week_range($date);
     #echo "Start Date is $start_date - End Date is $end_date <br>";
     $countScripted = getDetail("select count(*) as value from $test_report_db where act_test_assigned_to='$User' and act_test_scripted_date between '$start_date' AND '$end_date'");
     if ($countScripted >= $target) {
       $class="pass";
     } else {
       $class="fail";
     }
     echo "<td class=$class align=center>$countScripted</td>";
     $date = date("Y-m-d", strtotime("-3 day", strtotime($start_date)));
}
echo "</tr></table>";

# Print the modules assigned to User
echo "<br><br><center>";
echo "<b><font size=2>Your Assigned Modules</font></b><br>";
echo "<table border=1>\n";
echo "<tr>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=act_mod_name>Module Name</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=act_release_scope>Release Scope</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=act_func_area>Area</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=act_mod_pri>Priority</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=act_mod_owner>Owner</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=total>Assigned</a></td>
	<td class=a>Sanity <a href=$PHP_SELF?sort_report=yes&sort_by=sanTotal>T</a>/<a href=$PHP_SELF?sort_report=yes&sort_by=sanCompleted>C</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=pending>Overall Pending</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=sanPending>Sanity Pending</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=notautomatable>Not Automatable</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=scripted>Scripted</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=review>Review</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=rework>Rework</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=checkin>CheckIn</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=act_topo_det>Topology</a></td>
	<td class=a><a href=$PHP_SELF?sort_report=yes&sort_by=act_remarks>Remarks</a></td>
       </tr>\n";


$sort_field="San_Pending desc";
if ($_GET[sort_report]) {
    if ($_GET[sort_by] == "act_mod_name") {
          $sort_field="act_test_report.act_test_module asc";
    } elseif ($_GET[sort_by] == "act_release_scope") {
          $sort_field="$module_db.act_release_scope asc";
    } elseif ($_GET[sort_by] == "act_func_area") {
          $sort_field="$module_db.act_func_area asc";
    } elseif ($_GET[sort_by] == "act_mod_pri") {
          $sort_field="$module_db.act_mod_pri asc";
    } elseif ($_GET[sort_by] == "act_mod_owner") {
          $sort_field="$module_db.act_mod_owner asc";
    } elseif ($_GET[sort_by] == "total") {
          $sort_field="Total desc";
    } elseif ($_GET[sort_by] == "sanTotal") {
          $sort_field="San_Total desc";
    } elseif ($_GET[sort_by] == "sanCompleted") {
          $sort_field="San_Completed desc";
    } elseif ($_GET[sort_by] == "pending") {
          $sort_field="Pending desc";
    } elseif ($_GET[sort_by] == "sanPending") {
          $sort_field="san_Pending desc";
    } elseif ($_GET[sort_by] == "notautomatable") {
          $sort_field="NotAutomatable desc";
    } elseif ($_GET[sort_by] == "scripted") {
          $sort_field="Scripted desc";
    } elseif ($_GET[sort_by] == "review") {
          $sort_field="Review desc";
    } elseif ($_GET[sort_by] == "rework") {
          $sort_field="Rework desc";
    } elseif ($_GET[sort_by] == "checkin") {
          $sort_field="CheckedIn desc";
    } elseif ($_GET[sort_by] == "act_topo_det") {
          $sort_field="$module_db.act_topo_det asc";
    } elseif ($_GET[sort_by] == "act_remarks") {
          $sort_field="$module_db.act_remarks desc";
    } else {
	  $sort_field="San_Pending desc";
    }
}

$c=0;
$sql = "select 
	$test_report_db.act_test_module, 
	$module_db.act_mod_no, 
	$module_db.act_release_scope, 
	$module_db.act_func_area, 
	$module_db.act_mod_pri, 
	$module_db.act_mod_owner, 
	coalesce(sum($test_report_db.act_test_status like '%'),0) Total, 
	coalesce(sum($test_report_db.act_test_priority like '%Sanity%' and (act_test_status!='Not-Automatable' && act_test_status!='Existing')),0) San_Total,
        coalesce(sum($test_report_db.act_test_priority like '%Sanity%' and (act_test_status='Review' || act_test_status='Rework' || act_test_status='Scripted' || act_test_status='Checked-In')),0) San_Completed,
	coalesce(sum($test_report_db.act_test_status like 'Assigned'),0) Pending, 
        coalesce(sum($test_report_db.act_test_priority like '%Sanity%' and (act_test_status='Assigned')),0) San_Pending,
	coalesce(sum($test_report_db.act_test_status like 'Not-Automatable'),0) NotAutomatable, 
	coalesce(sum($test_report_db.act_test_status like 'Scripted'),0) Scripted, 
	coalesce(sum($test_report_db.act_test_status like 'Review'),0) Review, 
	coalesce(sum($test_report_db.act_test_status like 'Rework'),0) Rework, 
	coalesce(sum($test_report_db.act_test_status like 'Checked-In'),0) CheckedIn,
	$module_db.act_topo_det,
	$module_db.act_remarks 
	from $test_report_db 
	LEFT join $module_db 
	on $module_db.act_mod_name=$test_report_db.act_test_module 
	where act_test_assigned_to='$User' 
	group by act_test_report.act_test_module
	order by $sort_field";


#echo "$sql";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
        printf("<tr>
                <td class=$row[act_remarks]><a href=\"%s?act_mod_no=%s&act_mod_name=%s\">%s</td>
                <td class=$row[act_remarks]>%s</td>
                <td class=$row[act_remarks]>%s</td>
                <td class=$row[act_remarks]>%s</td>
                <td class=$row[act_remarks]>%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&showcases=yes\">%s</td>
                <td class=$row[act_remarks]><center>%s/%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&act_test_status=%s\">%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&act_test_status=%s\">%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&act_test_status=%s\">%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&act_test_status=%s\">%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&act_test_status=%s\">%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&act_test_status=%s\">%s</td>
                <td class=$row[act_remarks]><center><a href=\"%s?act_mod_name=%s&act_test_status=%s\">%s</td>
                <td class=$row[act_remarks]>%s</td>
                <td class=$row[act_remarks]>%s</td>
                </tr>\n",
                $formDirect, $row["act_mod_no"], $row["act_test_module"], $row["act_test_module"],
                $row["act_release_scope"],
                $row["act_func_area"],
                $row["act_mod_pri"],
                $row["act_mod_owner"],
                $formDirect, $row["act_test_module"], $row["Total"],
                $row["San_Total"], $row["San_Completed"],
                $formDirect, $row["act_test_module"], $assigned, $row["Pending"],
                $formDirect, $row["act_test_module"], $sanity, $row["San_Pending"],
                $formDirect, $row["act_test_module"], $notAutomatable, $row["NotAutomatable"],
                $formDirect, $row["act_test_module"], $scripted, $row["Scripted"],
                $formDirect, $row["act_test_module"], $review, $row["Review"],
                $formDirect, $row["act_test_module"], $rework, $row["Rework"],
                $formDirect, $row["act_test_module"], $checkedIn, $row["CheckedIn"],
                $row["act_topo_det"],
                $row["act_remarks"]
               );
     $c = $c + 1;
    }
} else {
    echo "<br><br><center><b>0 Testcases found<br><br></center>";
}

echo "</table><br><br><b><center><font size=2>Total Modules Allotted: ". $c ." <br><br><br <br><br><br>";
$conn->close();
}



#########################################################################################
#											#
# Code to check whether Delete View, Modify View or Normal View Needs to be shown       #
#											#
#########################################################################################
if ($_GET[remView]) {
      dispModule("D"); 
} elseif ($_GET[modView]) {
      dispModule("M"); 
} elseif ($_GET[delete] == "yes" ) { 
      remModule($_REQUEST[act_mod_no]);
} elseif ($_GET[modify] == "yes" ) { 
      modModule($_REQUEST[act_mod_no]);
} elseif ($_GET[remCase] == "yes" ) { 
      dispModule("remCase"); 
} elseif ($_GET[modCase] == "yes" ) {
      dispModule("modCase");
} elseif ($_GET[assignCase] == "yes" ) {
      dispModule("assignCase");
} else {
      dispModule("N"); 
}

?>
</body>
</html>
