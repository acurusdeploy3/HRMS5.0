<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
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
                        <li class="active"><a href="contactInfo.php" data-toggle="tab" aria-expanded="false">Contact</a></li>
                        <!-- <li class=""><a href="#Acurus_History" data-toggle="tab" aria-expanded="false">History in Acurus</a></li> -->
            						<li class=""><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class=""><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
									<li class="" ><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
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
            							<div class="border-class">
                            <h4 class="add-header-class">PERSENT ADDRESS</h4>
                            <div class="add-btn-class">
                              <a href="presentAddressEdit.php"><i class="fa fa-pencil"></i> Edit</a>
            								</div>
            								<table class="table table-striped">
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
            									<tbody>
            										<tr>
            										  <th>Line 1</th>
            										  <td><?php echo@$personDetailsData['Present_Address_Line_1']; ?></td>
            										  <th>Line 2</th>
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
                            <div class="add-btn-class">
                              <a href="permanentAddressEdit.php"><i class="fa fa-pencil"></i> Edit</a>
                            </div>
            								<table class="table table-striped">
            									<tbody>
            										<tr>
            										  <th>Line 1</th>
            										  <td><?php echo @$personDetailsData['Permanent_Address_Line_1']; ?></td>
            										  <th>Line 2</th>
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
