<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$id = 0;
$msg = '';

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  mysqli_query($db,"update employee_work_history set is_active = 'N' where employee_id = $empId and work_id = $id");
  if(mysqli_affected_rows($db)){
    storeDataInHistory($id, 'employee_work_history','work_id');
    $empWorkHistoryQry = mysqli_query($db,"select * from employee_work_history where employee_id = $empId and is_active = 'Y'");
    $msg = 'Deleted Successfully';
  }
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Personal Info
		<button OnClick="window.location.href='pages/tables/AuditLog.php?id=Employee Details'" type="button" class="btn btn-success pull-right">View Change Log</button>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Employee Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <?php require_once('employeeInfo.php'); ?>
            </div>

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  <div class="col-md-2" id="tab-menu">
                    <ul class="nav nav-tabs tabs-left">
                        <li class=""><a href="mydetails.php" data-toggle="tab" aria-expanded="true">Official</a></li>
                        <li class=""><a href="personalInfo.php" data-toggle="tab" aria-expanded="false">Personal</a></li>
                        <li class=""><a href="contactInfo.php" data-toggle="tab" aria-expanded="false">Contact</a></li>
                        <!-- <li class=""><a href="#Acurus_History" data-toggle="tab" aria-expanded="false">History in Acurus</a></li> -->
            						<li class="active"><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class=""><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
									<li class=""><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
            						<li class=""><a href="certificationsInfo.php" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                        <li class=""><a href="KYEInfo.php" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
            						<li class=""><a href="documentsInfo.php" data-toggle="tab" aria-expanded="false">Documents</a></li>
            						<li class=""><a href="skillsInfo.php" data-toggle="tab" aria-expanded="false">Skills</a></li>
            						<!-- <li class=""><a href="#Visa_Immigration" data-toggle="tab" aria-expanded="false">Visa and Immigration</a></li> -->
                    </ul>
                  </div>
                  <div class="col-md-10" id="tab-content">
                    <div class="tab-content">
                        <div class="content-tab-pane tab-pane active">
                          <?php if($msg != ''){ ?>
                          <div class="alert alert-success alert-dismissible custom-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $msg; ?>
                          </div>
                          <?php } ?>
            							<div class="border-class table-body-class">
                            <div class="add-btn-class">
            									<a href="addWorkHistory.php"><button type="button" class="btn btn-primary">Add+</button></a>
            								</div>
            								<table class="table table-striped">
            									<tbody>
            										<tr>
                                  <th>Actions</th>
            										  <th>Company Name</th>
            										  <th>Designation</th>
            										  <th>From</th>
            										  <th>To</th>
                                                      <th>Months Worked</th>
            										</tr>
                                <?php
                                if(mysqli_num_rows($empWorkHistoryQry) > 0){
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
                                      <td>
                                        <a href='addWorkHistory.php?id=".$empWorkHistoryData['work_id']."'><i class='fa fa-pencil'></i></a>
                                        <a href='workHistoryInfo.php?id=".$empWorkHistoryData['work_id']."'><i class='fa fa-trash'></i></a>
                                      </td>
                										  <td>".$empWorkHistoryData['company_name']."</td>
                										  <td>".$empWorkHistoryData['designation']."</td>
                                                          
                										  <td>".$date1F."</td>
                										  <td>".$date2F."</td>
                                                          <td>".$joining_months."</td>
                										</tr>";
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
