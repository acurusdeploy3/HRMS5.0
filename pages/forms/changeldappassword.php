<?php
require_once("dbcontroller.php");
if (isset($_POST['submit']))
{   
	session_start();	
	$login_session= $_SESSION['login_user'];
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
<body>
<div class="col-md-8">
		<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Password</h4>
              </div>
              <div class="modal-body">
                <div class="box-body">
				
                  <label for="inputName" class="col-sm-4 control-label">Employee Id</label>
                  
                    <input type="text" class="form-control" name="First_Name" placeholder="Employee Id" 
					value=<?=$_SESSION['login_user'];?> disabled >
                  
                  <label for="inputName" class="col-sm-4 control-label">Current Password</label>
                  
                    <input type="text" class="form-control"  name="First_Name" placeholder="Enter your Current Password" value="<?=( isset( $_POST['First_Name'] ) ? $_POST['First_Name'] : '' )?>"  >
                     <label for="inputName" class="col-sm-4 control-label">New Password</label>
                  
                    <input type="text" class="form-control"  name="First_Name" placeholder="Enter your New Password" value="<?=( isset( $_POST['First_Name'] ) ? $_POST['First_Name'] : '' )?>"  >
                  
                  <label for="inputName" class="col-sm-4 control-label">Confirm Password</label>
                  
                    <input type="text" class="form-control"  name="First_Name" placeholder="Confirm your Password" value="<?=( isset( $_POST['First_Name'] ) ? $_POST['First_Name'] : '' )?>"  >
                
				</div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" name="submit" value="Change Password" class="btnSubmit" >
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
		</div>
		</body>
		</html>