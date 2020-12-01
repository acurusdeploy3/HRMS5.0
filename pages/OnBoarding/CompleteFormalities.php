<?php
session_start();
$usergrp = $_SESSION['login_user_group'];
if($usergrp == 'HR' || $usergrp == 'HR Manager' || $usergrp=='System Admin Manager' || $usergrp=='System Admin') 
{
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Employee Boarding</title>
 <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../../plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
  <script src="../../dist/js/loader.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
.main-header
{
    height:50px;
}
.content-wrapper
{
	max-height: 500px;
	overflow-y:scroll;   
}
.astrick
{
	color:red;
}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

 <?php
 require_once('Layouts/main-header.php');
 ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php
 require_once('Layouts/main-sidebar.php');
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Employee Boarding
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="BoardingHome.php">Boarding Home</a></li>
        <li class="active">Complete Formalities</li>
      </ol>
    </section>
<?php
$EmployeeID = $_GET['id'];
session_start();
$_SESSION['Employee_id']=$EmployeeID;
include("config2.php");
$getName = mysqli_query($db,"select concat(First_Name,' ',last_Name,' ',MI) as Name,primary_mobile_number,department,Job_Type,official_email,workstation from employee_details where employee_id=$EmployeeID");
$getNameRow = mysqli_fetch_array($getName);
$getNameVal = $getNameRow['Name']; 
$Contact = $getNameRow['primary_mobile_number']; 
$department = $getNameRow['department']; 
$Job_Type = $getNameRow['Job_Type']; 
$official_email = $getNameRow['official_email']; 
$workstation = $getNameRow['workstation']; 
$getFamily = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee='Mother' and employee_id='$EmployeeID' ");
$getFamilyMother = mysqli_fetch_array($getFamily);
$MotherName = $getFamilyMother['family_member_name'];
$getFamilyDad = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee='Father' and employee_id='$EmployeeID' ");
$getFamilyDaDRow = mysqli_fetch_array($getFamilyDad);
$DadName = $getFamilyDaDRow['family_member_name'];

$getAllWS = mysqli_query($db,"select number from all_workstations where number not in (select workstation from employee_details)");
$getBoardingAdmin = mysqli_query($db,"SELECT system_login,system_login_password,mail_login,mail_login_password,os_type FROM `boarding_admin` where employee_id='$EmployeeID';");
$getBoardingAdminRow = mysqli_fetch_array($getBoardingAdmin);
$SystemLogin = $getBoardingAdminRow['system_login'];
$system_login_password = $getBoardingAdminRow['system_login_password'];
$mail_login = $getBoardingAdminRow['mail_login'];
$mail_login_password = $getBoardingAdminRow['mail_login_password'];
$os_type = $getBoardingAdminRow['os_type'];
$getAllos = mysqli_query($db,"select OS_Type from all_os_types where os_type!='$os_type'");
?>
<?php
			  $getMailType = mysqli_query($db,"select mail_type from employee_boarding where employee_id='$EmployeeID'");
			  $getMailTypeRow = mysqli_fetch_array($getMailType);
				$MailTypeVal = $getMailTypeRow['mail_type'];
			  ?>
    <!-- Main content -->
    <section class="content">
<!-- For Admin -->
<?php
if($usergrp=='System Admin Manager' || $usergrp=='System Admin')
{
?>
  <div class="box box-default">
        <div class="box-header with-border">
	<div class="box-header">
			
			 <table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
				<button OnClick="window.location.href='BoardingHome.php'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
			  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
             <h4 class="box-title"><strong><?php echo $getNameVal ?> : <?php echo $EmployeeID  ?></strong></h4>
			  <br>
			  <div class="box-tools pull-right">
          </div>
			  
            </div>	
	 <div class="row">
	  <div class="col-md-6">
	  <form id="AdminForm" method="post">
	  <?php
		include("config2.php");
		?>
		 <div class="form-group">
		    <label>Employee ID <span class="astrick">*</span></label>
                <input type="text" tabindex="1"   name="EmployeeIDSys" id="EmployeeIDSys" value="<?php echo $EmployeeID; ?>"  class="form-control" placeholder="Employee ID" readonly>
              </div>
		  <div class="form-group">
		    <label>Mother's Name <span class="astrick">*</span></label>
                <input type="text" tabindex="3"   name="MotherName" id="MotherName" value="<?php echo $MotherName; ?>"  class="form-control" placeholder="Mother's Name" readonly>
              </div>
 <div class="form-group">
		    <label>Employee Contact <span class="astrick">*</span></label>
                <input type="text" tabindex="5"   name="EmpContact" id="EmpContact" value="<?php echo $Contact; ?>"  class="form-control" placeholder="Employee Contact" readonly>
              </div>
<div class="form-group">
		    <label>Department <span class="astrick">*</span></label>
                <input type="text" tabindex="7"   name="EmpDepartment" id="EmpDepartment" value="<?php echo $department; ?>"  class="form-control" placeholder="Employee Department" readonly>
              </div>	
	<div class="form-group">
		    <label >Official E-Mail  <span class="astrick">*</span></label>
                <input type="email" tabindex="9" pattern="[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*" name="OfficeMail" id="OfficeMail" value="<?php echo $official_email; ?>"  class="form-control" placeholder="Official Mail" />
              </div>
			  <div class="form-group">
		    <label >Operating System <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="11" id="OSType" name="OSType" required="required" style="width: 100%;" required>
				 <?php
				 if($os_type=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select an OS Below</option>
				   <?php
				 }
				 else
				 {
				   ?>
				    <option value="<?php echo $os_type  ?>" selected><?php echo $os_type  ?></option>
				   <?php
				 }
				   ?>
				  <?php  
				  while($getAllosRow= mysqli_fetch_assoc($getAllos))
				  {
				  ?>
				  <option value="<?php echo $getAllosRow['OS_Type']  ?>"><?php echo $getAllosRow['OS_Type']  ?></option>
					<?php
				  }
					?>
				</select>
              </div>
			  <div class="form-group">
		    <label>Sytem Login UserName <span class="astrick">*</span></label>
                <input type="text" tabindex="13"   name="WindowsLogin" id="WindowsLogin" value="<?php echo $SystemLogin ?>"  class="form-control" placeholder="System Login UserName">
              </div>
</div>  
 <div class="col-md-6">
	  <?php
		include("config2.php");
		
		?>
		 <div class="form-group">
		    <label>Name <span class="astrick">*</span></label>
                <input type="text" tabindex="2"   name="EmpName" id="EmpName" value="<?php echo $getNameVal; ?>"  class="form-control" placeholder="Employee Name" readonly>
              </div>
		 <div class="form-group">
		    <label>Father's Name <span class="astrick">*</span></label>
                <input type="text" tabindex="4"   name="FatherName" id="FatherName" value="<?php echo $DadName; ?>"  class="form-control" placeholder="Father's Name" readonly>
              </div>
		 
 <div class="form-group">
		    <label >Job Type  <span class="astrick">*</span></label>
                <input type="text" tabindex="6" name="JobTypeSel" id="JobTypeSel" value="<?php echo $Job_Type; ?>"  class="form-control" placeholder="Job Type" readonly>
              </div>	
			<div class="form-group">
		    <label>E-Mail Type  <span class="astrick">*</span></label>
                <input type="text" tabindex="8" name="EmailType" id="EmailType" value="<?php echo $MailTypeVal; ?>" class="form-control" placeholder="Mail Type" readonly>
              </div>
			   <div class="form-group">
		    <label>Official E-Mail Password  <span class="astrick">*</span></label>
                <input type="text" tabindex="10" name="OfficeMailPwd" id="OfficeMailPwd" value="<?php echo $mail_login_password ?>"  class="form-control" placeholder="Mail Password">
              </div> 
			   <div class="form-group">
		    <label >Workstation #</label>
                 <select class="form-control select2" tabindex="11" id="Workstation" name="Workstation" style="width: 100%;">
                   <?php
				 if($workstation=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select an Workstation Below</option>
				   <?php
				 }
				 else
				 {
				   ?>
				    <option value="<?php echo $workstation  ?>" selected><?php echo $workstation  ?></option>
				   <?php
				 }
				   ?>
				  <?php  
				  while($getAllWSRow= mysqli_fetch_assoc($getAllWS))
				  {
				  ?>
				  <option value="<?php echo $getAllWSRow['number']  ?>"><?php echo $getAllWSRow['number']  ?></option>
					<?php
				  }
					?>
				</select>
              </div>
	<div class="form-group">
		    <label>System Login Password <span class="astrick">*</span></label>
                <input type="text" tabindex="14"   name="WindowsLoginPwd" id="WindowsLoginPwd" value="<?php  echo $system_login_password ?>"  class="form-control" placeholder="System Login Password">
              </div>			  
</div>
</div>  
<input type="submit" id="AdminSubmitBtn" value="Submit"  class="btn btn-primary pull-right" />
</div>  
  </div>
  </form>
   <div class="box box-default">
        <div class="box-header with-border">
	<div class="box-header">
             <h4 class="box-title"><strong>Employee Assets</strong></h4>
			  <br>
			  <div class="box-tools pull-right">
          </div>
			  
            </div>	
	 <div class="row">
	 <form id="AssetForm" method="post" action="InsertAsset.php">
	  <div class="col-md-6">
	  <?php
		include("config2.php");
		$getAllAssets = mysqli_query($db,"SELECT concat(asset_desc,' : ',asset_mgmt_id) as asset,asset_mgmt_id FROM `all_assets` where asset_mgmt_id not in (select asset_mgmt_id from employee_assets where is_active='Y')");
		?>
		 <div class="form-group">
		 <input type="hidden" name="EmployeeIDAsset" id="EmployeeIDAsset" value=<?php echo $EmployeeID ?> />
                <select class="form-control select2" tabindex="3" id="EmployeeAsset" name="EmployeeAsset" required="required" style="width: 100%;" required>
                   <option value="" selected disabled>Please Select a Asset Below</option>
				  <?php  
				  while($getAllAssetsRow= mysqli_fetch_assoc($getAllAssets))
				  {
				  ?>
				  <option value="<?php echo $getAllAssetsRow['asset_mgmt_id']  ?>"><?php echo $getAllAssetsRow['asset']  ?></option>
					<?php
				  }
					?>
				</select>
              </div>
</div>  
 <div class="col-md-6">
	  <div class="form-group">
				 <button type="submit" style="width:30%" id="AddAsset" class="btn btn-block btn-primary" >Add Asset</button>
				 </div>  
				 
</div>
</form>
<div class="col-md-12">
<?php
$getEmpAssets = mysqli_query($db,"SELECT employee_id,asset_desc,a.asset_mgmt_id FROM `employee_assets` a inner join all_assets b on a.asset_mgmt_id=b.asset_mgmt_id where employee_id='$EmployeeID' and is_Active='Y'");
?>
 <table align="center" class="table table-striped">

                <tr>
                  <th>Asset Provided</th>
                  <th>Asset ID</th>
                  <th>Cancel Provision</th>
                </tr>
				<?php
			  if(mysqli_num_rows($getEmpAssets)>0)
			  {
				  while($getEmpAssetsRow = mysqli_fetch_assoc($getEmpAssets))
				  {
			  ?>
				<tr>
				  <td><?php echo $getEmpAssetsRow['asset_desc'] ?></td>
                  <td><?php echo $getEmpAssetsRow['asset_mgmt_id'] ?></td>
				<td><a href="CancelProvision.php?id=<?php echo $getEmpAssetsRow['asset_mgmt_id']; ?>&EmpId=<?php echo $getEmpAssetsRow['employee_id']; ?>"><img alt='User' src='Images/notrep.png' title="Cancel Provision" width='18px' height='18px' /></a></td>
				</tr>

				<?php
				  }
			  }
			  else
			  {
				?>
					<tr>

                  <td>No Asset Provided</td>


				</tr>
				<?php
			  }
				?>
              </table>
</div>
</div>  

</div>  
  </div>

<!-- End of Admin -->
<?php
}
?>
<?php
if($usergrp=='HR Manager' || $usergrp=='HR')
{
?>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-primary">
        	<div class="box-header with-border">
   		 <h4 class="box-title"><strong>Employment Formalities</strong></h4>
        </div>
		<div class="box-header">
			
			 <table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
				<button OnClick="window.location.href='BoardingHome.php'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
			  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
             <h4 class="box-title"><strong><?php echo $getNameVal ?> : <?php echo $EmployeeID  ?></strong></h4>
			  <br>
			  <div class="box-tools pull-right">
          </div>
			  
            </div>
		<?php
		include("config2.php");
		$getEmpDocumentTypes = mysqli_query ($db,"select group_concat('''',document_type,'''') as document_types from employee_documents_Tracker where employee_id=$EmployeeID and document_module='Employee_Boarding'");
		$getEmpDocRow = mysqli_fetch_array($getEmpDocumentTypes);
		$EmpDocTypes = $getEmpDocRow['document_types'];
		if($EmpDocTypes=='')
		{
			$getDocTypes = mysqli_query($db,"Select document_type from all_document_types where authorized_for='HR' and document_type !=''");
		}
		else
		{
			$getDocTypes = mysqli_query($db,"Select document_type from all_document_types where authorized_for='HR' and document_type not in ($getDocTypes)");
		}
		?>
		
        <!-- /.box-header -->
		<form id="BoardingForm" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
			  <?php
			  include("config2.php");
			  $EmpDetails = mysqli_query($db,"select official_email,workstation,reporting_manager_id,department,employee_designation,mentor_id from employee_details where employee_id=$EmployeeID");
			  
			  $EmpDegnDetails = mysqli_query($db,"select designation,band,level from resource_management_table where employee_id=$EmployeeID");
			  
			  $EmpDataRow = mysqli_fetch_array($EmpDetails);
			  $EmpEmail = $EmpDataRow['official_email'];
			  $WKS = $EmpDataRow['workstation'];
			  $repmgmrId = $EmpDataRow['reporting_manager_id'];
			  $DeptSel = $EmpDataRow['department'];
			  $MentorID = $EmpDataRow['mentor_id'];
			  
			  $EmpDesgnDataRow = mysqli_fetch_array($EmpDegnDetails);
			  $EmpRole = $EmpDesgnDataRow['designation'];
			  $EmpBand = $EmpDesgnDataRow['band'];
			  $EmpLevel = $EmpDesgnDataRow['level'];
			  
			  
			  
			  
			  
			  $getMgmName = mysqli_query($db,"Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where employee_id = '$repmgmrId'"); 
			  
			  $getMgmNameRow = mysqli_fetch_array($getMgmName);
			  $MngrName = $getMgmNameRow['Employee_Name'];
			  
			  
			  $getMentorName = mysqli_query($db,"Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where employee_id = '$MentorID'"); 
			  
			  $getMentorNameRow = mysqli_fetch_array($getMentorName);
			  $MentorName = $getMentorNameRow['Employee_Name'];
			  
			  
			  
			  
			  
			  
			  
			  
			  $MngQuery = mysqli_query($db,"Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role not in ('Employee','System Admin','Accountant','HR') and employee_id not in ('$repmgmrId','$EmployeeID')");
			  
			  
			  
			  $MngQuery1 = mysqli_query($db,"Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role not in ('Employee','System Admin','Accountant','HR') and employee_id not in ('$MentorID','$EmployeeID')");
			  
			  $MentorQuery = mysqli_query($db,"Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where employee_id not in ('$MentorID','$EmployeeID')");
			  
			  $query3 = mysqli_query($db,"SELECT department_id,department FROM `all_departments` where department!='' and department != '$DeptSel'");
			  
			  
			  $query = mysqli_query($db,"Select designation_id,designation_desc from all_designations where designation_desc not in ('','$EmpRole')");
			  
			  
			  $query1 = mysqli_query($db,"Select band_no,band_desc from all_bands where band_desc !='$band' and band_desc not in ('','$EmpBand')");
			  
			  
			  $query2 = mysqli_query($db,"Select level_id,level_desc from all_levels where level_desc !='$level' and level_desc not in ('','$EmpLevel')");
			  ?>
	
                <label id="OffMailLbl">Official E-Mail  <span class="astrick">*</span></label>
                <input type="text" tabindex="1" name="OfficialMail" id="OfficialMail" value="<?php echo $EmpEmail; ?>"  class="form-control" placeholder="Official Mail">
              </div>
			  <div class="form-group">
                 <label id="RepMgrLbl">Reporting Manager <span class="astrick">*</span></label>
              <select class="form-control select2" tabindex="3" id="RepMgmr" name="RepMgmr" required="required" style="width: 100%;" required>
				<?php
				if($repmgmrId=='')
				{
				?>
                  
                  <option  value="" selected="selected" disabled>Select From Below</option>
				 <?php
				}
				else
				{
				?>				 
				  <option value="<?php echo $repmgmrId; ?>" selected="selected"><?php echo $MngrName;  ?></option>
				
				<?php
				}				
				?>  
				  <option  value="None" >None</option>
                 <?php
				
				while ($mng = mysqli_fetch_assoc($MngQuery))
				{
				?>
                  <option value="<?php echo $mng['Employee_id']  ?>"><?php echo $mng['Employee_Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			 <div class="form-group">
                <label id="RoleLbl">Role  <span class="astrick">*</span><a href="#" title="Click to Add More Roles" id="additionalRole" data-toggle="modal" data-target="#modal-default-Role" ><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" tabindex="5" id="RoleSelect" name="RoleSelect" required="required" style="width: 100%;"  required>
				
                <?php
				if($EmpRole=='')
				{
				?>
                  
                  <option  value="" selected="selected" disabled>Select From Below</option>
				 <?php
				}
				else
				{
				?>				 
				  <option value="<?php echo $EmpRole; ?>" selected="selected"><?php echo $EmpRole;  ?></option>
				
				<?php
				}				
				?>
                 <?php
				
				while ($Design = mysqli_fetch_assoc($query))
				{
				?>
                  <option value="<?php echo $Design['designation_desc']  ?>"><?php echo $Design['designation_desc']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
				<!-- /.form-group -->  
			
			 
			   <div class="form-group">
			     <label id="levelLbl">Level<a href="#" title="Click to Add More Levels" id="additionalLevel" data-toggle="modal" data-target="#modal-default-Level"><i class="fa fa-fw fa-plus"></i></a></label>
               <select class="form-control select2" tabindex="7" id="LevelSel" name="LevelSel" required="required" style="width: 100%;" required>
			       <?php
				if($EmpLevel=='')
				{
				?>
                  
                  <option  value="" selected="selected" disabled>Select From Below</option>
				 <?php
				}
				else
				{
				?>				 
				  <option value="<?php echo $EmpLevel; ?>" selected="selected"><?php echo $EmpLevel;  ?></option>
				
				<?php
				}				
				?>
                  <option value="None">None</option>
               
                   <?php
				
				while ($levelQu = mysqli_fetch_assoc($query2))
				{
				?>
                  <option value="<?php echo $levelQu['level_desc']  ?>"><?php echo $levelQu['level_desc']  ?></option>
                 <?php
				}
				 ?>
                </select>
                
              </div>
			 
         
			<br>

			</div>
            <div class="col-md-6">
             <div class="form-group">
			   <label>Allocated Work Station </label>
                <input type="number" tabindex="2" name="WKS" min="0" id="WKS" value="<?php echo $WKS;  ?>"  class="form-control" placeholder="WKS #">
              </div>

			  <div class="form-group">
				
                <label id="DeptLbl">Department <span class="astrick">*</span><a href="#" id="additionalDept" title="Click to Add More Departments" data-toggle="modal" data-target="#modal-default-Dept"><i class="fa fa-fw fa-plus"></i></a></label>
				<input type="hidden" id="trainid" name="trainid" value = <?php echo $trainingid ; ?> >
                <select class="form-control select2" tabindex="4" id="DeptSelect" name="DeptSelect" required="required" style="width: 100%;"  required>
			<?php
				if($DeptSel=='')
				{
				?>
                  
                  <option  value="" selected="selected" disabled>Select From Below</option>
				 <?php
				}
				else
				{
				?>				 
				  <option value="<?php echo $DeptSel; ?>" selected="selected"><?php echo $DeptSel;  ?></option>
				
				<?php
				}				
				?>  
				<?php
				
				while ($department = mysqli_fetch_assoc($query3))
				{
				?>
                  <option value="<?php echo $department['department']  ?>"><?php echo $department['department']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			   <div class="form-group">
			  <label id="BandLbl">Band<a href="#" title="Click to Add More Bands" id="additionalBand" data-toggle="modal" data-target="#modal-default-Band"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" tabindex="6" id="BandSelect" name="BandSelect" required="required" style="width: 100%;"  required>
				  <?php
				if($EmpBand=='')
				{
				?>
                  
                  <option  value="" selected="selected" disabled>Select From Below</option>
				 <?php
				}
				else
				{
				?>				 
				  <option value="<?php echo $EmpBand; ?>" selected="selected"><?php echo $EmpBand;  ?></option>
				
				<?php
				}				
				?>
				<option value="">None</option>
			 
                 <?php
				
				while ($bandrow = mysqli_fetch_assoc($query1))
				{
				?>
                  <option value="<?php echo $bandrow['band_desc']  ?>"><?php echo $bandrow['band_desc']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  <div class="form-group">
                 <label id="MentorLBl">Mentor</label>
              <select class="form-control select2" tabindex="8" id="MentorSel" name="MentorSel" style="width: 100%;" >
					
                <?php
				if($MentorID=='')
				{
				?>
                  
                  <option  value="" selected="selected" disabled>Select From Below</option>
				 <?php
				}
				else
				{
				?>				 
				  <option value="<?php echo $MentorID; ?>" selected="selected"><?php echo $MentorName;  ?></option>
				
				<?php
				}				
				?>  
                 <?php
				
				while ($mng1 = mysqli_fetch_assoc($MentorQuery))
				{
				?>
                  <option value="<?php echo $mng1['Employee_id']  ?>"><?php echo $mng1['Employee_Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  <br>
			   
				
				
			   
              <!-- /.form-group -->
            </div>
			 
            <!-- /.col -
			
            <!-- /.col -->
          </div>
		
		<input type="button" id="submitBtn" value="Save Employment Data"  class="btn btn-primary pull-right" />

	</form>

<div class="modal fade" id="modal-default-Level">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Level</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Level</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputLevel" name="inputLevel" placeholder="Enter Level" />
                    
                  </div>
                </div>			
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeLvl" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addLvlbtn"  class="btn btn-primary">Add Level</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>


 


  <div class="modal fade" id="modal-default-Not">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
              </div>
            <div class="modal-body">
			<?php
			session_start();
			$name = $_SESSION['login_user'];
			include("config2.php");
			$getName= mysqli_query($db,"select First_name from employee_details where employee_id='$name'");
			$getRow = mysqli_fetch_array($getName);
			$UserName = $getRow['First_name'];
			?>
                <p>Hey &nbsp; <strong><?php echo $UserName.', ' ?> </strong> It seems you are leaving the Joining Formalities Incomplete for <strong><?php  echo $getNameVal.'.'?></strong>The Employee has been moved to Incomplete Formalities Table in Boarding Home Screen.</p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
  
        








 <div class="modal fade" id="modal-default-Role">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Role</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="roleForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Role Name</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputRole" name="inputRole" placeholder="Enter Role" />
                  </div>
                </div>
               
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addRolebtn"  class="btn btn-primary">Add Role</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>



















 <div class="modal fade" id="modal-default-Band">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Band</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="BandForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Band :</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputBand" name="inputBand" placeholder="Enter Band" />
                    
                  </div>
                </div>
		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Band Description:</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputBandDesc" name="inputBandDesc" placeholder="Enter Band Desc" />
                    
                  </div>
                </div>				
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeBand" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addBandbtn"  class="btn btn-primary">Add Band</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		 

<div class="modal fade" id="modal-default-Doc">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Document Type</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="BandForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Document Type :</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputDoc" name="inputDoc" placeholder="Enter Type" />
                    
                  </div>
                </div>
					
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeDoc" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addDocbtn"  class="btn btn-primary">Add Type</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>

  
		   <div class="modal fade" id="modal-default-Dept">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Department</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Department</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputDept" name="inputDept" placeholder="Enter Department" />
                    
                  </div>
                </div>			
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeDept" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addDeptbtn"  class="btn btn-primary">Add Department</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  </div>
		  
		  
 <div class="col-md-6">
			
			  </div>
          <!-- /.row -->
        </div>
		<br>
		<?php
		include("config2.php");
		
		$getEmpDocumentTypes = mysqli_query ($db,"select group_concat('''',document_type,'''') as document_types from employee_documents_Tracker where employee_id=$EmployeeID and document_module='Employee_Boarding'");
		$getEmpDocRow = mysqli_fetch_array($getEmpDocumentTypes);
		$EmpDocTypes = $getEmpDocRow['document_types'];
		if($EmpDocTypes=='')
			
			{
			$getDocTypes = mysqli_query($db,"Select document_type from all_document_types where authorized_for='HR' and document_type !=''");
			}
			else
			{
				$getDocTypes = mysqli_query($db,"Select document_type from all_document_types where authorized_for='HR' and document_type not in($EmpDocTypes)");
			}
		?>
	<!--    Files Content    -->	
		
	<?php
	$getEmpdataSheet = mysqli_query($db,"select date_joined,salary_payment_mode,pf_number,esic_number,dispensary,local_office,bank_name,account_number,branch,ifsc_code,
date(date_of_Assessment) as date_of_Assessment,conducted_by,marks_obtained,max_score,typing_speed,typing_accuracy,business_unit

from employee_details where employee_id='$EmployeeID'");

	$getEmpdataRow = mysqli_fetch_array($getEmpdataSheet);
	$datejoined = $getEmpdataRow['date_joined'];
	$PaymentMode = $getEmpdataRow['salary_payment_mode'];
	$pfnum = $getEmpdataRow['pf_number'];
	$esic = $getEmpdataRow['esic_number'];
	$disp = $getEmpdataRow['dispensary'];
	$locoffice = $getEmpdataRow['local_office'];
	$bank = $getEmpdataRow['bank_name'];
	$accnum = $getEmpdataRow['account_number'];
	$branch = $getEmpdataRow['branch'];
	$ifsc = $getEmpdataRow['ifsc_code'];
	$doa = $getEmpdataRow['date_of_Assessment'];
	if($doa=='0001-01-01') {
		$doa='';
	}
	$condby = $getEmpdataRow['conducted_by'];
	$marksob = $getEmpdataRow['marks_obtained'];
	$maxscore = $getEmpdataRow['max_score'];
	$typspeed = $getEmpdataRow['typing_speed'];
	$accuracy = $getEmpdataRow['typing_accuracy'];
	$BusinessUnit = $getEmpdataRow['business_unit'];
	$CheckforPAN = mysqli_query($db,"select document_type,document_number from kye_details where employee_id='$EmployeeID' and document_type='PERMANANT ACCOUNT NUMBER' and is_active='Y'");
	$getPanRow = mysqli_fetch_array($CheckforPAN);
	$getPanNum = $getPanRow['document_number'];
	$getAllBusinessUnits = mysqli_query($db,"select business_unit from all_business_units where business_unit!='$BusinessUnit'");
	$getAllLocalOffices = mysqli_query($db,"select business_unit from all_business_units where business_unit!='$locoffice'");
	$getAllpaymentModes = mysqli_query($db,"select mode from all_payment_modes where mode!='$PaymentMode'");
	?>
	
	
	
			<br>
			<div class="box box-primary">
          
		<div class="box-header with-border">
			
             <h4 class="box-title"><strong>Employee Data Sheet : Office Use</strong></h4>
			 
        </div>
			
			 <div class="box-body">
		<form id="DataForm" method="post">
			 <div class="row">
			 
			<div class="col-md-6">
			<div class="form-group">
			   <label>Date of Appointment </label>
                <input type="text" name="DOJ" tabindex="9" id="DOJ" value="<?php echo $datejoined;  ?>"  class="form-control" placeholder="DOJ #" disabled >
              </div>
			  <div class="form-group">
			   <label id="BULbl">Business Unit </label>
                <select class="form-control select2" tabindex="11" id="BusinessUnitSelect" name="BusinessUnitSelect" style="width: 100%;" >
				<?php 
				if($BusinessUnit=='')
				{
				?>
				<option value="" selected disabled>Please Select From Below </option>
				
				<?php
				}
				else
				{
				?>
				<option value="<?php echo $BusinessUnit; ?>"><?php echo $BusinessUnit; ?></option>
					<?php
				}
				while($BusinessUnits = mysqli_fetch_assoc($getAllBusinessUnits))
				{
					?>
					<option value="<?php echo $BusinessUnits['business_unit']; ?>"><?php echo $BusinessUnits['business_unit']; ?></option>
				<?php
				}
				?>
				</select>
              </div>
			     <div class="form-group">
			   <label>ESIC Number </label>
                <input type="text" tabindex="13" name="ESICNumber" id="ESICNumber" value="<?php echo $esic;  ?>"  class="form-control" placeholder="ESIC #">
              </div>
			   <div class="form-group">
			   <label id="LoLbl">Local Office </label>
                  <select class="form-control select2" tabindex="15" id="LocalOfficeSelect" name="LocalOfficeSelect" style="width: 100%;" >
				<?php 
				if($locoffice=='')
				{
				?>
				<option value="" selected disabled>Please Select From Below </option>
				
				<?php
				}
				else
				{
				?>
				<option value="<?php echo $locoffice; ?>"><?php echo $locoffice; ?></option>
					<?php
				}
				while($LocalOffices = mysqli_fetch_assoc($getAllLocalOffices))
				{
					?>
					<option value="<?php echo $LocalOffices['business_unit']; ?>"><?php echo $LocalOffices['business_unit']; ?></option>
				<?php
				}
				?>
				</select>
              </div>
			</div>
				
		<div class="col-md-6">
		<div class="form-group">
			   <label id="SpLbl">Salary Payment Mode </label>
              <select class="form-control select2" tabindex="10" id="SalaryPaymentModeSelect" name="SalaryPaymentModeSelect" style="width: 100%;" >
				<?php 
				if($PaymentMode=='')
				{
				?>
				<option value="" selected disabled>Please Select From Below </option>
				<?php
				}
				else
				{
				?>
				<option value="<?php echo $PaymentMode; ?>"><?php echo $PaymentMode; ?></option>
					<?php
				}
				?>
				<?php
				while ($getPaymentModes = mysqli_fetch_assoc($getAllpaymentModes))
				{
				?>
				<option value="<?php echo $getPaymentModes['mode']; ?>"><?php echo $getPaymentModes['mode']; ?></option>
				<?php
				}
				?>
				</select>
              </div>
			   <div class="form-group">
			   <label id="PFlbl" >Provident Fund Number </label>
                <input type="text" tabindex="12" name="ProvidentFundNumber" id="ProvidentFundNumber" value="<?php echo $pfnum;  ?>"  class="form-control" placeholder="Provident Fund #">
              </div>
			    <div class="form-group">
			   <label>Dispensary </label>
                <input type="text" tabindex="14" name="DispensaryNumber" id="DispensaryNumber" value="<?php echo $disp;  ?>"  class="form-control" placeholder="Dispensary #">
              </div>
			   <div class="form-group">
			   <label id="PANLbl">Permanent Account Number (PAN) </label>
                <input type="text" tabindex="16" name="PANNumber" id="PANNumber" value="<?php echo $getPanNum;  ?>"  class="form-control" placeholder="PAN #">
              </div>
				
			</div>
                      <!-- /.box-body -->
			 </div>
			
			<?php
			
			include("config2.php");
			$getBankNames = mysqli_query($db,"select distinct(bank_name) as  bank_name from tblbankname where bank_name!='$bank'");
			?>
		<div class="box-header with-border">
			
             <h4 class="box-title"><strong>Bank Information</strong></h4>
			 
        </div>	
		
			
			 <div class="row">
			 
			<div class="col-md-6">
			<div class="form-group">
			   <label id="BankLbl">Bank Name </label>
                <select class="form-control select2" tabindex="17" id="SalaryBankSelect" name="SalaryBankSelect" style="width: 100%;" >
				<?php
				if($bank=='')
				{
				?>
				<option value="" selected disabled>Please Select From Below </option>
				
				<?php
				}
				else
				{
				?>
				<option value="<?php echo $bank;?>" selected><?php echo $bank;?></option>
				
				<?php
				}
				?>
				<?php
				while($getNames = mysqli_fetch_assoc($getBankNames))
				{
				?>
				
				<option value="<?php  echo $getNames['bank_name'] ?>"><?php  echo $getNames['bank_name'] ?></option>
				
				<?php
				}
				?>
				</select>
              </div>
			  <div class="form-group">
			   <label id="BranchLbl">Branch </label>
                <input type="text" tabindex="19" name="BankBranch" id="BankBranch" value="<?php echo  $branch ?>"  class="form-control" placeholder="Branch Name">
              </div>
			  
			</div>
				
		<div class="col-md-6">
		<div class="form-group">
			   <label id="AccnoLbl">Account Number</label>
                <input type="text" tabindex="18" name="AccountNumber" id="AccountNumber" value="<?php echo $accnum;  ?>"  class="form-control" placeholder="Account #">
              </div>
			   <div class="form-group">
			   <label id="IFSCLbl">IFSC Code</label>
                <input type="text" tabindex="20" name="IFSCCode" id="IFSCCode" value="<?php echo $ifsc;  ?>"  class="form-control" placeholder="IFSC Code">
              </div>
			   
				</div>
			</div>		
            <!-- /.box-body -->
			
			 
			 <div class="box-header with-border">
			
             <h4 class="box-title"><strong>Test Details</strong></h4>
			 
        </div>	
	
			
			 <div class="row">
			 
			<div class="col-md-6">
			<div class="form-group">
			   <label>Date of Assessment </label>
                <input type="text" name="DOA" tabindex="21" id="DOA" value="<?php echo $doa;  ?>"  class="form-control" placeholder="Pick a Date">
              </div>
			  <div class="form-group">
			   <label>Assessment Marks </label>
                <input type="text" name="AssMarks" tabindex="23" id="AssMarks" value="<?php echo $marksob;  ?>"  class="form-control" placeholder="Marks Obtained">
              </div>
			     <div class="form-group">
			   <label>Typing Speed (Words per Minute)</label>
                <input type="text" name="TypingSpeed" tabindex="25" id="TypingSpeed" value="<?php echo $typspeed;  ?>"  class="form-control" placeholder="Enter Typing Speed.">
              </div>
			</div>
				
		<div class="col-md-6">
		<div class="form-group">
			   <label>Conducted By</label>
               <input type="text" name="ConductedBySelect" tabindex="22" id="ConductedBySelect" value="<?php echo $condby;  ?>"  class="form-control" placeholder="Conducted By">
              </div>
			   <div class="form-group">
			   <label>Maximum Score</label>
                <input type="text" name="MaxScore" tabindex="24" id="MaxScore" value="<?php echo $maxscore;  ?>"  class="form-control" placeholder="Max Test Score">
              </div>
			    <div class="form-group">
			   <label>Typing Accuracy </label>
                <input type="text" name="TypingAcc" tabindex="26" id="TypingAcc" value="<?php echo $accuracy;  ?>"  class="form-control" placeholder="Accuracy %">
              </div>
			   
				</div>
			</div>
			<button type="button" id="SubmitDataBtn" class="btn btn-primary pull-right">Save AEDS (Office Use)</button>
		</form>
			
            <!-- /.box-body -->
			 </div>
			 
			 
			</div>
			
			
			
			

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
	
            <!-- /.box-header -->
            
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<?php
			include("config2.php");
			
			$getRadioValues  = mysqli_query($db,"select is_biometric_authorized,is_id_issued,is_login_created,is_system_allocated,attendance_required from employee_boarding where employee_id=$EmployeeID");
			$getRadioRow = mysqli_fetch_array($getRadioValues);
			$BioMetric = $getRadioRow['is_biometric_authorized'];
			$IDCard = $getRadioRow['is_id_issued'];
			$LoginCreated = $getRadioRow['is_login_created'];
			$SystemAllocated = $getRadioRow['is_system_allocated'];
			$attendance = $getRadioRow['attendance_required'];
			
			?>
			
			
			
			
			
			
			
			
			
			
			<!--    Radio Content    -->	
		
	
			<br>
			<br>
			<div class="box box-success">
          
		<div class="box-header with-border">
			
             <h4 class="box-title"><strong>Employee Provisions</strong></h4>
			 
        </div>
            <!-- /.box-header -->
			
            <div class="box-body">
			<form id="ChooseForm" method="post">
		<fieldset>
		 <div class="box-header"><legend style="border-bottom: 0.5px solid #3c8dbc"><h4 style="
    text-align: left;
">IT</h4></legend>
			</div>
           <div class="row">
		   
			  <br>
              <div class="box-tools">
              </div>
			 <div class="col-md-6">
			 
			  <div class="form-group">
			  <label id="EmailTypelBl">Email Type <span class="astrick">*</span></label>
                <select class="form-control select2" tabindex="3" id="MailType" name="MailType" style="width: 100%;">
                   <option value="" selected disabled>Please Select a Mail Type Below</option>
                   <option value="Intra Mail"  >Intra Mail</option>
                   <option value="Yahoo Mail" >Yahoo Mail</option>
				</select>
              </div>
			  <div class="form-group">
		    <label id="SystemLoginlbl">Domain Login UserName</label>
                <input type="text" tabindex="1"   name="WindowsLogin" id="WindowsLogin" value="<?php echo  $SystemLogin ?>"  class="form-control" placeholder="System Login UserName" readonly>
              </div>
			   <div class="form-group">
		    <label id="oEmailLbl">Official E-Mail</label>
                <input type="text" tabindex="1" name="OfficeMail" id="OfficeMail" value="<?php echo $EmpEmail; ?>"  class="form-control" placeholder="Official Mail" readonly>
              </div>
            </div>
			 <div class="col-md-6">
			    <div class="form-group">
		    <label id="oEmailLbl">OS Type</label>
                <input type="text" tabindex="1" name="OSTYPEHR" id="OSTYPEHR" value="<?php echo $os_type; ?>"  class="form-control" placeholder="Domain Operating System" readonly>
              </div>
			  <div class="form-group">
		    <label id="oEmailLbl">Domain Password</label>
                <input type="text" tabindex="1" name="DomainPassword" id="DomainPassword" value="<?php echo $system_login_password; ?>"  class="form-control" placeholder="Domain Password" readonly>
              </div>
				<div class="form-group">
		    <label>Official E-Mail Password  <span class="astrick">*</span></label>
                <input type="text" tabindex="10" name="OfficeMailPwdHR" id="OfficeMailPwdHR" value="<?php echo $mail_login_password ?>"  class="form-control" placeholder="Mail Password" disabled>
              </div> 
            </div>
			
		   </div>
		   <?php
			   if($MailTypeVal==' ')
			   {
			  ?>
				<button type="button" id="SendtoAdmin" class="btn btn-primary pull-right">Send to Admin</button>
		  <?php
			   }
			   else
			   {
			  ?>
			  <button type="button" id="" class="btn btn-primary pull-right" disabled>Request Sent</button>
			   <?php
			   }
			  ?>
			  </fieldset>
		   <fieldset>
		    <div class="box-header">
			<?php
			include("../LeaveMgmt/Attendance_Config.php");
			$getEmployeeShift= mysqli_query($att,"select shift_code from employee_shift where employee_id='$EmployeeID' and curdate() between start_date and end_date");
			$getEmployeeShiftRow = mysqli_fetch_array($getEmployeeShift);
			$EmpShift = $getEmployeeShiftRow['shift_code'];
			$getShiftTypes = mysqli_query($att,"SELECT shift_code,start_time,end_time FROM `shift` where day='F' and shift_code!='$EmpShift';");
			$getEmpDetails = mysqli_query($att,"select is_pl_eligible,is_comp_off_eligible from employee where employee_id='$EmployeeID'");
			$getEmpDetailsRow = mysqli_fetch_array($getEmpDetails);
			$isPl = $getEmpDetailsRow['is_pl_eligible'];
			$isComp = $getEmpDetailsRow['is_comp_off_eligible'];
			$getLeaves = mysqli_query($att,"select cl_closing,sl_closing,pl_closing from employee_leave_tracker where employee_id='$EmployeeID' and month=month(curdate()) and year=year(curdate())");
			$getLeavesRow = mysqli_fetch_array($getLeaves);
			$SLCount = $getLeavesRow['sl_closing'];
			$PLCount = $getLeavesRow['pl_closing'];
			$CLCount = $getLeavesRow['cl_closing'];
			$getAttendance = mysqli_query($att,"select TIME_FORMAT(check_in,'%H:%i %p') as check_in, TIME_FORMAT(check_out,'%H:%i %p') as check_out from attendance where shift_date='$datejoined' and employee_id='$EmployeeID'");
			$getAttendanceRow = mysqli_fetch_array($getAttendance);
			$CheckIn = $getAttendanceRow['check_in'];
			$CheckOut = $getAttendanceRow['check_out'];
			$getBenifits = mysqli_query($att,"SELECT cpp,night_shift_allowance,second_shift_allowance,excecutive FROM `employee_benefits` where employee_id='$EmployeeID';"); 
			$getBenifitsRow = mysqli_fetch_array($getBenifits);
			$isCPP=$getBenifitsRow['cpp'];
			$isNSA=$getBenifitsRow['night_shift_allowance'];
			$isSSA=$getBenifitsRow['second_shift_allowance'];
			$isExec=$getBenifitsRow['excecutive'];
			?>
			  
			  <div class="box-header" style="text-align: left;">
              <legend style="border-bottom: 0.5px solid #3c8dbc"><h4>Benefits</h4></legend>
        </div>
        </div>
		<div class="row">
		  <div class="col-md-6">
			  <div class="form-group">
			  <label id="EmpShiftLbl">Employee Shift <span class="astrick">*</span></label>
                <select class="form-control select2" tabindex="1" id="EmployeeShift" name="EmployeeShift" required="required" style="width: 100%;" required <?php if($EmpShift!='') { ?> disabled  <?php } ?>>
				<?php
				if($EmpShift=='')
				{
				?>
                   <option value="" selected disabled>Please Select From Below</option>
				   
				   <?php
				}
				else
				{
				   ?>
				     <option value="<?php echo $EmpShift; ?>" selected ><?php echo $EmpShift; ?></option>
				   <?php
				}
				   ?>
				   <?php
				   while($getShiftTypesRow = mysqli_Fetch_assoc($getShiftTypes))
				   {
				   ?>
						<option value="<?php echo $getShiftTypesRow['shift_code']; ?>"  ><?php echo $getShiftTypesRow['shift_code'].' ('.$getShiftTypesRow['start_time'].' - '.$getShiftTypesRow['end_time'].')'; ?> </option>
					<?php
				   }
					?>
				</select>
              </div>
	
	 <div class="form-group">
		    <label id="CPPLbl">CPP <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="3" id="CPPEligible" name="CPPEligible" required="required" style="width: 100%;" required <?php if($isCPP!='') { ?> disabled  <?php } ?>>
                    <?php
				 if($isCPP=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select from Below</option>
				    <option value="N"  >No</option>
                   <option value="Y" >Yes</option>
				   <?php
				 }
				 elseif ($isCPP=='N')
				 {
				   ?>
                   <option value="N"  Selected>No</option>
                   <option value="Y"  >Yes</option>
				      <?php
				 }
				 else
				 {
				   ?>
                   <option value="Y"  Selected>Yes</option>
				   <option value="N"  >No</option>
				   
				   <?php
				 }
				   ?>
				</select>
	  </div>
	    <div class="form-group">
		    <label id="SSALbl">Is Second Shift Allowance Eligible <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="5" id="ISSSAEligible" name="ISSSAEligible" required="required" style="width: 100%;" required <?php if($isSSA!='') { ?> disabled  <?php } ?>>
                 <?php
				 if($isSSA=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select from Below</option>
				    <option value="N"  >No</option>
                   <option value="Y" >Yes</option>
				   <?php
				 }
				 elseif ($isSSA=='N')
				 {
				   ?>
                   <option value="N"  Selected>No</option>
                   <option value="Y"  >Yes</option>
				      <?php
				 }
				 else
				 {
				   ?>
                   <option value="Y"  Selected>Yes</option>
				   <option value="N"  >No</option>
				   
				   <?php
				 }
				   ?>
				</select>
              </div>
			   <div class="form-group">
		    <label id="ISExecLbl">Is Executive <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="5" id="IsExecutive" name="IsExecutive" required="required" style="width: 100%;" required <?php if($isExec!='') { ?> disabled  <?php } ?>>
                     <?php
				 if($isExec=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select from Below</option>
				    <option value="N"  >No</option>
                   <option value="Y" >Yes</option>
				   <?php
				 }
				 elseif ($isExec=='N')
				 {
				   ?>
                   <option value="N"  Selected>No</option>
                   <option value="Y"  >Yes</option>
				      <?php
				 }
				 else
				 {
				   ?>
                   <option value="Y"  Selected>Yes</option>
				   <option value="N"  >No</option>
				   
				   <?php
				 }
				   ?>
				</select>
	  </div>
	  <div class="form-group">
		  <div class="col-md-4">
			<div class="form-group">
			  <label id="SLLbl">Base Leave Count (SL) <span class="astrick">*</span></label>
                  <input type="number" name="SL"  min="0" max=""  class="form-control pull-right" value="<?php  echo $SLCount; ?>"  required="required" onKeyPress="return isNumberKey(event)" step="1" id="SL"  placeholder="SL Count" required  <?php if($SLCount!='') { ?> readonly  <?php } ?>>
			 </div> 
		  </div> 
		  <div class="col-md-4">
			 <div class="form-group">
				  <label id="CLLbl">Base Leave Count (CL) <span class="astrick">*</span></label>
				  <input type="number" name="CL" min="0" max="" class="form-control pull-right" value="<?php  echo $CLCount; ?>" required="required" onKeyPress="return isNumberKey(event)" step="1" id="CL"  placeholder="CL Count" required <?php if($CLCount!='') { ?> readonly  <?php } ?>>
			 </div> 
		 </div> 
		 <div class="col-md-4">
			<div class="form-group">
				<label id="PLLbl">Base Leave Count (PL) <span class="astrick">*</span></label>
				   <input type="number" name="PL" min="0" max="" class="form-control pull-right"  value="<?php  echo $PLCount; ?>" required="required" onKeyPress="return isNumberKey(event)" step="1" id="PL"  placeholder="PL Count" required <?php if($PLCount!='') { ?> readonly  <?php } ?>>
			</div>
		</div>
	</div>
	   </div>
            
			 <div class="col-md-6">
			   <div class="form-group">
					<label id="PLElLbl">Is PL Eligible <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="2" id="ISPLEligible" name="ISPLEligible" required="required" style="width: 100%;" required <?php if($isPl!='') { ?> disabled  <?php } ?>>
				 <?php
				 if($isPl=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select from Below</option>
				    <option value="N"  >No</option>
                   <option value="Y" >Yes</option>
				   <?php
				 }
				 elseif ($isPl=='N')
				 {
				   ?>
                   <option value="N"  Selected>No</option>
                   <option value="Y"  >Yes</option>
				      <?php
				 }
				 else
				 {
				   ?>
                   <option value="Y"  Selected>Yes</option>
				   <option value="N"  >No</option>
				   
				   <?php
				 }
				   ?>
				</select>
              </div>
	<div class="form-group">
		    <label id="COLbl">Is Comp Off Eligible <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="4" id="ISCompEligible" name="ISCompEligible" required="required" style="width: 100%;" required <?php if($isComp!='') { ?> disabled  <?php } ?>>
                 <?php
				 if($isComp=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select from Below</option>
				    <option value="N"  >No</option>
                   <option value="Y" >Yes</option>
				   <?php
				 }
				 elseif ($isComp=='N')
				 {
				   ?>
                   <option value="N"  Selected>No</option>
                   <option value="Y"  >Yes</option>
				      <?php
				 }
				 else
				 {
				   ?>
                   <option value="Y"  Selected>Yes</option>
				   <option value="N"  >No</option>
				   
				   <?php
				 }
				   ?>
				</select>
              </div>
			  <div class="form-group">
		    <label id="NSALbl">Is Night Shift Allowance Eligible <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="4" id="ISNSAEligible" name="ISNSAEligible" required="required" style="width: 100%;" required <?php if($isNSA!='') { ?> disabled  <?php } ?>>
                 <?php
				 if($isNSA=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select from Below</option>
				    <option value="N"  >No</option>
                   <option value="Y" >Yes</option>
				   <?php
				 }
				 elseif ($isNSA=='N')
				 {
				   ?>
                   <option value="N"  Selected>No</option>
                   <option value="Y"  >Yes</option>
				      <?php
				 }
				 else
				 {
				   ?>
                   <option value="Y"  Selected>Yes</option>
				   <option value="N"  >No</option>
				   
				   <?php
				 }
				   ?>
				</select>
              </div>
			   <div class="col-md-12">
			    <div class="form-group">
                <label id="DayOneIN">Day 1 Check-In Time <span class="astrick">*</span></label>

              <div class="input-group">
                    <input type="text" id="InTime" name="InTime" value="<?php echo $CheckIn; ?>" class="form-control timepicker" required="required"		required <?php if($CheckIn!='') { ?> readonly  <?php } ?>>

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  </div>
                  </div>
              </div>
			
            </div>
			 </fieldset>
			 <br>
			 
			 	<fieldset>
		    <div class="box-header" style="
    text-align: left;
">
              <legend style="border-bottom: 0.5px solid #3c8dbc"><h4>Checklist</h4></legend>
        </div>
		    <div class="row">
			<div class="col-md-6">

				<br>
				<div class="form-group">
			      <label>
				&nbsp;&nbsp;&nbsp;&nbsp; Is Identity Card Issued for the Employee ? &nbsp; <label>
                  <input type="radio" name="r1" value="Yes" class="minimal" <?php if($IDCard == 'Yes') {echo "checked";} ?> > &nbsp; Yes </input>
                </label>
                <label>&nbsp;
                  <input type="radio" name="r1" value="No" class="minimal" <?php if($IDCard != 'Yes') {echo "checked";} ?> > &nbsp; No
                </label>
                </label>
                
              </div>		
		</div>
			
		<div class="col-md-6">
		<br>
			<div class="form-group">
			      <label>
				&nbsp;&nbsp;&nbsp;&nbsp; Is Biometric Device Authorized for the Employee ? &nbsp; <label>
                  <input type="radio" name="r3" value="Yes" class="minimal" <?php if($BioMetric == 'Yes') {echo "checked";} ?>> &nbsp; Yes
                </label>
                <label>&nbsp;
                  <input type="radio" name="r3" value="No" class="minimal" <?php if($BioMetric !='Yes') {echo "checked";} ?>> &nbsp; No
                </label>
                </label>
                
              </div>
		 <br>
		
			  <br>
			   
				</div>
			</div>
			
			 </fieldset>
			<button type="submit" id="SubmitCheckBtn" class="btn btn-primary pull-right">Save Employee Provisions</button>
		</form>
		
		
            <!-- /.box-body -->
			 </div>
			</div>
			<?php
			$getStatus = mysqli_query($db,"select are_documents_uploaded,is_provisions_completed,is_designated,is_data_sheet_completed
from employee_boarding
where employee_id='$EmployeeID'");
$getCompStatus = mysqli_fetch_array($getStatus);
$getDocStatus = $getCompStatus['are_documents_uploaded'];
$getEmpStatus = $getCompStatus['is_designated'];
$getProvStatus = $getCompStatus['is_provisions_completed'];
$GetDataStatus = $getCompStatus['is_data_sheet_completed'];

if($getEmpStatus == 'N' || $getProvStatus=='N' || $GetDataStatus=='N')
{	
	$isComplete = 'N';
}
else
{
		$isComplete='Y';
}	
if($isComplete=='Y')
{
			?>
	<button type="button" id="Submitformalities" class="btn btn-success pull-right">Finish Formalities</button>
	<?php
}
else
{
	?>
	<button type="button" id="Submitformalities" class="btn btn-success pull-right" disabled>Finish Formalities</button>
	<?php
}
	?>
				<br>
				<br>
			
			
			
			  <a href="#" id="btnEMpl" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Empl" class="btn btn-danger pull-right">Skip Upload</a>
			  <a href="#" id="btnaeds" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-aeds" class="btn btn-danger pull-right">Skip Upload</a>
			    <a href="#" id="btnpro" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Pro" class="btn btn-danger pull-right">Skip Upload</a>
			    <a href="#" id="btnADM" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Adm" class="btn btn-danger pull-right">Skip Upload</a>
			  
			  
			  
			  
			 <div class="modal fade" id="modal-default-Empl">
          <div class="modal-dialog">
            <div class="modal-content">
             <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Employee Boarding</h4>
              </div>
            <div class="modal-body">
                <p>Employment Formalities Saved Sucessfully!</p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" onclick="pagereload();" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
			
			 <div class="modal fade" id="modal-default-Adm">
          <div class="modal-dialog">
            <div class="modal-content">
             <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Employee Boarding</h4>
              </div>
            <div class="modal-body">
                <p>Notification Email Sent to Admin Team!</p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" onclick="pagereload();" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
			 <div class="modal fade" id="modal-default-aeds">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Employee Boarding</h4>
              </div>
            <div class="modal-body">
                <p>AEDS (Office Use) Saved Sucessfully!</p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" onclick="pagereload();" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
			
			
			
			 <div class="modal fade" id="modal-default-Pro">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Employee Boarding</h4>
              </div>
            <div class="modal-body">
                <p>Employee Provisions Saved Sucessfully!</p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" onclick="pagereload();" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
			
			
			
<?php

}

?>			
			
			
			
			
			
			
			
			
			
			
			
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
        <!-- /.box-body -->  
    </section>
    <!-- /.content -->
 </div>
 
 <?php
 
 require_once('Layouts/documentModals.php');
 ?>
      <!-- /.box --
      <!-- /.row -->

  <!-- /.content-wrapper -->
  <footer class="main-footer">
  
    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong> 
  </footer>
   </div>     
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../../bower_components/moment/min/moment.min.js"></script>
<script src="../../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page script -->
<script type="text/javascript">
   $(document).on('click','#SendtoAdmin',function(e) {
	  var returnval = true;
		ajaxindicatorstart("Please Wait..");
		e.preventDefault();
		var MailType = $('#MailType').val();
		if(MailType==null)
		{
			alert("Please Choose Official Mail Type.");
			returnval = false;
		}
		if(returnval==true)
		{
			 var data = $("#ChooseForm").serialize();
				$.ajax({
							data: data,
							type: "post",
							url: "SendAdminMail.php",
							success: function(data)
								{
									$('#btnADM').click();
									ajaxindicatorstop();
								}
 
					});
		}
		else
		{
			ajaxindicatorstop();
		}
 });
</script>
<script language="Javascript">
       <!--
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       //-->
    </script>
<script type="text/javascript">
 var AcntNum = document.getElementById('AccountNumber');

if (AcntNum.value == "") {
    document.getElementById('AccnoLbl').style.color = "red";
}


var ifsc = document.getElementById('IFSCCode');

if (ifsc.value == "") {
    document.getElementById('IFSCLbl').style.color = "red";
}

var EmpRole = document.getElementById('RoleSelect');

if (EmpRole.value == "") {
    document.getElementById('RoleLbl').style.color = "red";
}


var EmpDept = document.getElementById('DeptSelect');

if (EmpDept.value == "") {
    document.getElementById('DeptLbl').style.color = "red";
}


var Reporting = document.getElementById('RepMgmr');

if (Reporting.value == "") {
    document.getElementById('RepMgrLbl').style.color = "red";
}

var Reporting = document.getElementById('MentorSel');

if (Reporting.value == "") {
    document.getElementById('MentorLBl').style.color = "red";
}
var OffEmail = document.getElementById('OfficialMail');

if (OffEmail.value == "") {
    document.getElementById('OffMailLbl').style.color = "red";
}
var BUSel = document.getElementById('BusinessUnitSelect');

if (BUSel.value == "") {
    document.getElementById('BULbl').style.color = "red";
}



var LoSel = document.getElementById('LocalOfficeSelect');

if (LoSel.value == "") {
    document.getElementById('LoLbl').style.color = "red";
}


var PanVal = document.getElementById('PANNumber');

if (PanVal.value == "") {
    document.getElementById('PANLbl').style.color = "red";
}


var SPVal = document.getElementById('SalaryPaymentModeSelect');

if (SPVal.value == "") {
    document.getElementById('SpLbl').style.color = "red";
}


var SBVal = document.getElementById('SalaryBankSelect');

if (SBVal.value == "") {
    document.getElementById('BankLbl').style.color = "red";
}

var BBVal = document.getElementById('BankBranch');

if (BBVal.value == "") {
    document.getElementById('BranchLbl').style.color = "red";
}

var PFVal = document.getElementById('ProvidentFundNumber');

if (PFVal.value == "") {
    document.getElementById('PFlbl').style.color = "red";
}
</script>

<script>

var number = document.getElementById('WKS');

// Listen for input event on numInput.
number.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
</script>
<script>
$(function(){
  var addKYEName = $("form#addKYE select[name='docType'] option:eq(1)").val();
	var addKYENameArray = addKYEName.split(" ");
	$("form#addKYE div.btn_div a[href='#myModal']").attr("id",addKYENameArray[0]);
	$("form#addKYE input[name='docId']").attr("id",addKYENameArray[0]+"_doc_id");
  $("form#addKYE select[name='docType'] option:eq(1)").attr("selected","selected");
});



function yesnoCheck(that) {
        if (that.value == "Yes") {
            
            document.getElementById("ifYes").style.display = "block";
        } else {
            document.getElementById("ifYes").style.display = "none";
        }
    }
</script>
<script type="text/javascript">
       $(document).on('click','#addModbtn',function(e) {
		   var data = $("#inputMod").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddMod.php",
         success: function(data){
			AdditionalMod();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalMod() {
          
			var modal = document.getElementById('modal-default-Mod');
            var ddl = document.getElementById("TrainingModule");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputMod").value;
            option.value = document.getElementById("inputMod").value;
            ddl.options.add(option);
			document.getElementById("closeMod").click();
			document.getElementById("inputMod").value="";
        
			     
        }
    </script>


<script type="text/javascript">
       $(document).on('click','#addDeptbtn',function(e) {
		   var data = $("#inputDept").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddDept.php",
         success: function(data){
			AdditionalDept();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalDept() {
          
			var modal = document.getElementById('modal-default-Dept');
            var ddl = document.getElementById("DeptSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDept").value;
            option.value = document.getElementById("inputDept").value;
            ddl.options.add(option);
			document.getElementById("closeDept").click();
			document.getElementById("inputDept").value="";
        
			     
        }
    </script>
	
	
	
<script type="text/javascript">
       $(document).on('click','#addDocbtn',function(e) {
		   var data = $("#inputDoc").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddDoc.php",
         success: function(data){
			AdditionalDoc();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	<script type="text/javascript">
       function AdditionalDoc() {
          
			var modal = document.getElementById('modal-default-Doc');
            var ddl = document.getElementById("DocSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDoc").value;
            option.value = document.getElementById("inputDoc").value;
            ddl.options.add(option);
			document.getElementById("closeDoc").click();
			document.getElementById("inputDoc").value="";
        
			     
        }
    </script>


	
	





<script type="text/javascript">
function changetextbox()
{
    if (document.getElementById("MandForAll").value === "Yes") {
		document.getElementById("SelByRole").disabled=true;
		document.getElementById("SelByDept").disabled=true;
		document.getElementById("TraineesSel").disabled=true;
	}
	else
	{
		document.getElementById("SelByRole").disabled=false;
		document.getElementById("SelByDept").disabled=false;
		document.getElementById("TraineesSel").disabled=false;
	}
	
}
</script>
<script type="text/javascript">
function changeFreq()
{
    if (document.getElementById("TrainFreq").value === "Once") {
		document.getElementById("TrainFreqOcc").disabled=true;
		
	}
	else
	{
		document.getElementById("TrainFreqOcc").disabled=false;
		
	}
	
}
</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })
	 $('#datepicker1').datepicker({
      autoclose: true
    })
	 $('#DOJ').datepicker({
      autoclose: true
    })
	$('#DOA').datepicker({
      autoclose: true
    })
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
			showInputs: false
    })
  })
</script>
<script type="text/javascript">
   $(document).on('click','#submitBtn',function(e) {
     
	ajaxindicatorstart("Please Wait..");
  var data = $("#BoardingForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertFormalities.php",
         success: function(data){
			 ajaxindicatorstop();
			$('#btnEMpl').click();

         }
});

});
    </script>
	<script type="text/javascript">
   $(document).on('click','#SubmitDataBtn',function(e) {
     
	ajaxindicatorstart("Please Wait..");
  var data = $("#DataForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertDataSheet.php",
         success: function(data){
			 ajaxindicatorstop();
			$('#btnaeds').click();

         }
});

});
    </script>
	<script type="text/javascript">
       $(document).on('click','#addRolebtn',function(e) {
		   var data = $("#inputRole").serialize();
  //var data = $("#roleForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addRole.php",
         success: function(data){
			//alert(data);
			AdditionalRole();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       $(document).on('click','#SubmitCheckBtn1',function(e) {
		   var data = $("#ChooseForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "RadioEntries.php",
         success: function(data){ 
			ajaxindicatorstop();
			$('#btnpro').click(); 
         }
});
 });
    </script>
	<script type="text/javascript">
   $("#ChooseForm").submit(function(e) {
	  var returnval1 = true;
		ajaxindicatorstart("Please Wait..");
		e.preventDefault();
		var deptval = $("#DeptSelect").val();
		var RepMgmr = $("#RepMgmr").val();
		var BusinessUnitSelect = $("#BusinessUnitSelect").val();
		if(deptval==null)
			{
				alert("Please Choose a Department & Save Employment Data");
				returnval1 = false;
			}
	if(RepMgmr==null || BusinessUnitSelect==null)
	{
		alert("Please Complete Employment Formalities before Proceeding with Provisions.");
		returnval1 = false;
	}
	if(BusinessUnitSelect==null)
	{
		alert("Please Complete AEDS (Office Use) before Proceeding with Provisions.");
		returnval1 = false;
	}
	if(returnval1==false)
		{
			ajaxindicatorstop();
		}
		else
		{
			var data = $("#ChooseForm").serialize();
			$.ajax({
					data: data,
					type: "post",
					url: "RadioEntries.php",
					success: function(data){
							$('#btnpro').click(); 
							ajaxindicatorstop();
								}
				});
		}
 });
</script>
	<script type="text/javascript">
      $(document).ready(function() {
    $("#AdminForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#AdminForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "AdminEntries.php",
         success: function(data){

			window.location.href = "BoardingHome.php";
		   ajaxindicatorstop();

         }
});

});
});
    </script>
	<script type="text/javascript">
	 $(document).on('click','#Submitformalities',function(e) {
		 unsaved=false;
		 if(unsaved==true)
		 {
			 alert('There are unsaved Changes in the Form. Save before Proceeding.');
			 return false;
		 }
		 else
		 {
			 SubmitFormalitiesForm();
		 }
		  });
	</script>
	
	
	<script type="text/javascript">
      function SubmitFormalitiesForm()
	  {
	   var data = $("#FormalitiesForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "FinishFormalities.php",
         success: function(data){
			window.location.href = "BoardingHome.php";
			 ajaxindicatorstop();
			 
         }
});
 }
    </script>
<script>
function pagereload()
{
			location.reload();
			 ajaxindicatorstop();
}
</script>
	<script type="text/javascript">
       function AdditionalRole() {
          
			var modal = document.getElementById('modal-default-Role');
            var ddl = document.getElementById("RoleSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputRole").value;
            option.value = document.getElementById("inputRole").value;
            ddl.options.add(option);
			document.getElementById("closeRole").click();
			document.getElementById("inputRole").value="";
        
			     
        }
    </script>
	
	<script type="text/javascript">
       $(document).on('click','#addBandbtn',function(e) {
		 //  var data = $("#inputBand").serialize();
  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addBand.php",
         success: function(data){
			//alert(data);
			AdditionalBand();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
<script type="text/javascript">
       function AdditionalBand() {
          
			var modal = document.getElementById('modal-default-Band');
            var ddl = document.getElementById("BandSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputBandDesc").value;
            option.value = document.getElementById("inputBandDesc").value;
            ddl.options.add(option);
			document.getElementById("closeBand").click();
			document.getElementById("inputBandDesc").value="";
			document.getElementById("inputBand").value="";
        
			     
        }
    </script>
		
	<script type="text/javascript">
       $(document).on('click','#addLvlbtn',function(e) {
		   var data = $("#inputLevel").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddLevel.php",
         success: function(data){
			//alert(data);
			Additionallev();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function Additionallev() {
          
			var modal = document.getElementById('modal-default-Level');
            var ddl = document.getElementById("LevelSel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputLevel").value;
            option.value = document.getElementById("inputLevel").value;
            ddl.options.add(option);
			document.getElementById("closeLvl").click();
			document.getElementById("inputLevel").value="";
        
			     
        }
    </script>
	
	
</body>
</html>
<?php
}
else
{
	header("Location: ../forms/Logout.php");
}
?>