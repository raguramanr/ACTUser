<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>ACT Login</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link href="style1.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
echo "<TABLE width=\"100%\" border=1 bgcolor=#A0B0E0>";
echo "<TR>";
echo "<TD class=menu>";
echo "<font color=black>";
$User = $_SERVER["REMOTE_USER"];
echo "<A href=\"ACTUserMycases.php\" target=body>Welcome: $User </a>";
echo "</strong></font>";
echo "</TD>";
?>
<TD class=menu><font color="#00FF00"><A href="ACTUserMycases.php" target=body>My  Assigned Cases</A></font></TD>
<TD class=menu><A href="ACTDashboard.php" target=body>Dashboard</A></TD>
<TD class=menu><A href="ACTRegression.php" target=body>Regression Status</A></TD>
<TD class=menu><A href="ACTExecutionTrend.php" target=body>Execution Trend</A></TD>
<TD class=menu><A href="ACTReviewTracker.php" target=body>Review Tracker</A></TD>
<TD class=menu><A href="ACTPassmod.php" target=body>Change Password</A></TD>
</TR>
</TABLE>
</BODY>
</HTML>
