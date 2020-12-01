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
                      <li class=""><a href="searchContactInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Contact</a></li>
                      <li class="active"><a href="searchWorkHistoryInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Work History</a></li>
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
            							<div class="border-class table-body-class">
            								<table class="table table-striped">
            									<tbody>
            										<tr>
                                  <th>Sno</th>
            										  <th>Company Name</th>
            										  <th>Designation</th>
            										  <th>From</th>
            										  <th>To</th>
                                                    	<th>Months Worked</th>
            										</tr>
                                <?php
                                if(mysqli_num_rows($empWorkHistoryQry) > 0){
                                  $i = 1;
                                  while($empWorkHistoryData = mysqli_fetch_assoc($empWorkHistoryQry)){
                                  
                                  	$date1 = date_create($empWorkHistoryData['worked_from']);
                                  $date1F = date_format($date1,'d M Y');
                                  $date2 = date_create($empWorkHistoryData['worked_upto']);
                                  $date2F = date_format($date2,'d M Y');
                                  
                                  $date1 = $empWorkHistoryData['worked_from']; 
							     $date2 = $empWorkHistoryData['worked_upto']; 
								 $ts1 = strtotime($date1); 
									$ts2 = strtotime($date2);
								$year1 = date('Y', $ts1); 
								$year2 = date('Y', $ts2); 
                                 $month1 = date('m', $ts1); 
								$month2 = date('m', $ts2); 
							 $joining_months = (($year2 - $year1) * 12) + ($month2 - $month1);
                                    echo "<tr>
                                      <td>".$i."</td>
                										  <td>".$empWorkHistoryData['company_name']."</td>
                										  <td>".$empWorkHistoryData['designation']."</td>
                										  <td>".$date1F."</td>
                										  <td>".$date2F."</td>
                                                          <td>".$joining_months."</td>
                										</tr>";
                                    $i++;
                                  }
                                } else {
                                  echo "<tr><td colspan='5'>No data found </td></tr>";
                                }
                                ?>
            									</tbody>
            								</table>
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

?>
