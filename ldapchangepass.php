
<?php
if (isset($_POST['submit']))
{   
	session_start();	
	require_once("changepass.php");
	
	$objLdap = new LDAPAuth();	
	$objLdap->connect_ldap();

	$var1=$_POST["username"];
	$_SESSION['login_user']=$var1; 
	$password =$_POST['password'];
	$newPassword =$_POST['userPassword'];
	$newPassword1 =$_POST['confirmPassword'];
	
	$objLdap->user_name = $var1;
	$objLdap->userDn = $var1;
	$objLdap->user_pass = $password;
	$result = $objLdap->login();

	if ($result) 
	{
		$count1 = $objLdap->validategroup();
		$result1 = $objLdap->changepassword();
		
	}
	else 
	{
		$message= "User Name Or Password Invalid!";
	}	
}
?>


<html>
<head>
<title>Change Password</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>

<center>
<form name="frmChange" method="post" action="">
<div style="width:500px;">
<div class="message"><?php if(isset($message)) { echo $message; } ?></div>
<table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="2"><font size="4">  Change Password</font></td>
</tr>
<tr>
<td><label>Username</label></td>
<td><input type="text" name="username"  class="txtField" value=<?=$_SESSION['login_user'];?> readonly /><span id="username" class="required"></span></td>
</tr>
<tr>
<td><label>Current Password</label></td>
<td><input type="password" name="password" class="txtField" required /><span id="password"  class="required"></span></td>
</tr>
<tr>
<td><label>New Password</label></td>
<td><input type="password" name="userPassword" class="txtField" required /><span id="userPassword" class="required"></span></td>
</tr>
<tr>
<td><label>Confirm Password</label></td>
<td><input type="password" name="confirmPassword" class="txtField" required /><span id="confirmPassword" class="required"></span></td>
</tr>
<tr>
<td colspan="2">
<input type="submit" name="submit" value="Submit" class="btnSubmit" >
<input action= "action" class="btnSubmit" onclick="window.history.go(-1); return false;" type="button" value="Cancel" />
</td>
</tr>
</table>
</div>
</form></center>
</body></html>




