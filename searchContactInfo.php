<?php
require_once("config.php");
session_start();
$employeeid=$_SESSION['login_user'];
$employeegrp=$_SESSION['login_user_group'];
$Searchid = $_GET['empId'];
$CheckRep = mysqli_query($db,"select * from employee_details where reporting_manager_id='$employeeid' and employee_id='$Searchid'");
if($employeegrp=='HR Manager' || $employeegrp=='HR' || mysqli_num_rows($CheckRep)>0 )
{
?>
<?php
require_once("config.php");
session_start();
$backpage = $_SESSION['searchBack'];
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
require_once('searchEmployeeCommon.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Personal Info
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
 		 <li><a href="#">Search Employee</a></li>
        <li><a href="#">Employee Info</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
			<button onclick="window.location='pages/tables/<?php echo $backpage ?>'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
           <br>
			Employee Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <?php require_once('searchEmployeeInfo.php'); ?>
            </div>

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  <div class="col-md-2" id="tab-menu">
                    <ul class="nav nav-tabs tabs-left">
                      <li class=""><a href="searchMydetails.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="true">Official</a></li>
                      <li class=""><a href="searchPersonalInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Personal</a></li>
                      <li class="active"><a href="searchContactInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Contact</a></li>
                      <li class=""><a href="searchWorkHistoryInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Work History</a></li>
                      <li class=""><a href="searchEducationInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Education</a></li>
					   <li class=""><a href="searchFamilyInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
                      <li class=""><a href="searchCertificationsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                      <li class=""><a href="searchKYEInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
                      <li class=""><a href="searchDocumentsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Documents</a></li>
                      <li class=""><a href="searchSkillsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Skills</a></li>
                    </ul>
                  </div>
                  <div class="col-md-10" id="tab-content">
                    <div class="tab-content">
                        <div class="content-tab-pane tab-pane active">
            							<div class="border-class">
                            <h4 class="add-header-class">PERSENT ADDRESS</h4>
<?php
if (@$personDetailsData['Present_Address_Line_2']=='')
{
@$personDetailsData['Present_Address_Line_2']='--';
}
if (@$personDetailsData['Present_Address_Line_3']=='')
{
@$personDetailsData['Present_Address_Line_3']='--';

}
if (@$personDetailsData['Permanent_Address_Line_2']=='')
{
@$personDetailsData['Permanent_Address_Line_2']='--';
}
if (@$personDetailsData['Permanent_Address_Line_3']=='')
{
@$personDetailsData['Permanent_Address_Line_3']='--';
}

?>
            								<table class="table table-striped">
            									<tbody>
            										<tr>
            										  <th>Building / Par</th>
            										  <td><?php echo@$personDetailsData['Present_Address_Line_1']; ?></td>
            										  <th>Address Line2</th>
            										 <td><?php echo 'New No: '.@$personDetailsData['Present_Address_Line_2'].' / Old No :'.@$personDetailsData['Present_Address_Line_3'].', '.@$personDetailsData['Present_Street']; ?></td>
            										</tr>
            										<tr>
            										  <th>City</th>
            										  <td><?php echo @$personDetailsData['Present_City']; ?></td>
            										  <th>State</th>
            										  <td><?php echo @$personDetailsData['Present_State']; ?></td>
            										</tr>
            										<tr>
            										  <th>Country</th>
            										  <td><?php echo @$personDetailsData['Present_Country']; ?></td>
            										  <th>Zip Code</th>
            										  <td><?php echo @$personDetailsData['Present_Address_ZipCode']; ?></td>
            										</tr>
            									</tbody>
            								</table>
                            <h4 class="add-header-class">PERMANENT ADDRESS</h4>
            								<table class="table table-striped">
            									<tbody>

            										<tr>
            										  <th>Address Line1</th>
            										  <td><?php echo @$personDetailsData['Permanent_Address_Line_1']; ?></td>
            										  <th>Address Line2</th>
            										   <td><?php echo 'New No: '.@$personDetailsData['Permanent_Address_Line_2'].' / Old No :'.@$personDetailsData['Permanent_Address_Line_3'].', '.@$personDetailsData['Permanent_Street']; ?></td>
            										</tr>
            										<tr>
            										  <th>City</th>
            										  <td><?php echo @$personDetailsData['Permanent_City']; ?></td>
            										  <th>State</th>
            										  <td><?php echo @$personDetailsData['Permanent_State']; ?></td>
            										</tr>
            										<tr>
            										  <th>Country</th>
            										  <td><?php echo @$personDetailsData['Permanent_Country']; ?></td>
            										  <th>Zip Code</th>
            										  <td><?php echo @$personDetailsData['Permanent_Address_Zip']; ?></td>
            										</tr>
            									</tbody>
            								</table>
            								<!-- <h4>EMERGENCY DETAILS</h4>
            								<table class="table table-striped">
            									<tbody>
            										<tr>
            										  <th>Name</th>
            										  <td><?php echo @$empRealtionsData['family_member_name']; ?></td>
            										  <th>Number</th>
            										  <td><?php echo @$empRealtionsData['contact_number']; ?></td>
            										</tr>
            									</tbody>
            								</table> -->
            							</div>
            						</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box-footer">
            </div>
          </div>
          <!-- /.box -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/bottom-footer.php');
//require_once('layouts/control-sidebar.php');
?>
<?php
}
else
{
  header("Location: pages/forms/Logout.php");
}