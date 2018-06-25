<html>
<body bgcolor=#a8a8a8>
<link href="style1.css" rel="stylesheet" type="text/css">
<form method="post" action="<?php echo $PHP_SELF?>">
<?php
include 'common.php';
if ($_REQUEST[submit]) {
$OldPasswd = $_SERVER["PHP_AUTH_PW"];
$Username = $_SERVER["REMOTE_USER"];
$loop1 = "yes";
if($loop1) {
$flag="no";
 if ( $OldPasswd == $_REQUEST[oldpwd] ) {
	$flag = "yes";
   } else {
	$flag = "error";
	echo "<br>Old Password Not Matching";
   }

// echo "<br>Stage 1 Flag Value is $flag";
if ($flag == "yes" ) {
  if ( $_REQUEST[newpwd] != "" ) {
	$flag = "yes";		
     } else {
	$flag = "error";
	echo "<br>Password field should not be Empty";
    }
}
// echo "<br>Stage 2 Flag Value is $flag";

if ($flag == "yes" ) {
  if ( $_REQUEST[newpwd] == $_REQUEST[confpwd] ) {
        $flag = "yes";
     } else {
        $flag = "error";
        echo "<br>New Password not matching with confirmed Password";
     }
}
// echo "<br>Stage 3 Flag Value is $flag";

if ($flag == "yes" ) {
//  echo "<br>Final Flag Value is $flag";
 $cmd = "/usr/bin/htpasswd -b $user_pwd_path $Username $_REQUEST[newpwd]";
 system("$cmd", $ret);
 if($ret == "0") {
  echo "<center><b>All authentication tokens updated successfully</b></center>";
        } else {
  echo "<center><b>Password Could not be Changed. Contact system Administrator !!</b></center>";
       exit;
       }
   } else {
 echo "<br> Password could not be changed  due to above reason ! <br>";
   }
} else {
} 
} else {
}
?>


<form method="post" action="<?php echo $PHP_SELF?>">
<input type=hidden name="id" value="<?php echo $myrow["id"] ?>">
<br><BR><center><b><font size=2>Change Password</font>
<table border=1 align=center>
<tr><td class=b>Old Password</td><td><input type="password" maxlength=20 name="oldpwd" value=""></td><br>
</tr><tr><td class=b>New Password</td><td><input type="password" maxlength=20 name="newpwd" value=""></td<br>
</tr><tr><td class=b>Confirm New Password</td><td><input type="password" maxlength= 20 name="confpwd" value=""></td></tr></table><br>
<br><input type="Submit" name="submit" value="Change Password">
</form>
</body>
</html>
