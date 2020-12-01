<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = $FamMember = $dob = $occupation = $RelationShip = '';
$id = 0;
if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $famHistory = mysqli_query($db,"select family_id,family_member_name,relationship_with_employee,date_of_birth,if(date_of_birth='0001-01-01','--',timestampdiff(year,date_of_birth,curdate())) as age,occupation from employee_family_particulars where employee_id=$empId and family_id ='$id' and is_active='Y'");
  $famHistoryData = mysqli_fetch_assoc($famHistory);

  $FamMember = @$famHistoryData['family_member_name'];
  $dob = @$famHistoryData['date_of_birth'];
  $occupation = @$famHistoryData['occupation'];
  $RelationShip = @$famHistoryData['relationship_with_employee'];
 
}
if($id=='')
{
	 $relationQuery = mysqli_query($db,"SELECT relation FROM all_relations");
}
else
{
	 $relationQuery = mysqli_query($db,"SELECT relation FROM all_relations where relation!='$RelationShip'");
}
if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $FamMember = (isset($_POST['FamMember']) && $_POST['FamMember'] != '')?mysqli_real_escape_string($db,$_POST['FamMember']):$FamMember;
  $dob = (isset($_POST['dob']) && $_POST['dob'] != '')?mysqli_real_escape_string($db,$_POST['dob']):$dob;
  $occupation = (isset($_POST['occupation']) && $_POST['occupation'] != '')?mysqli_real_escape_string($db,$_POST['occupation']):'';
  $RelationShip = (isset($_POST['RelationShip']) && $_POST['RelationShip'] != '')?$_POST['RelationShip']:$RelationShip;
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;
if($dob=='')
{
		$dob='0001-01-01';
}
  if($id){
	  mysqli_query($db,"update employee_family_particulars set family_member_name='$FamMember',relationship_with_employee='$RelationShip',date_of_birth='$dob',occupation='$occupation' where family_id='$id'");
    if(mysqli_affected_rows($db)){
      storeDataInHistory($id, 'employee_family_particulars','family_id');
      $msg = 'Updated Successfully';
    }
  }else{
	  mysqli_query($db,"insert into employee_family_particulars (employee_id,family_member_name,relationship_with_employee,occupation,created_by,created_date_and_time,date_of_birth) values
		('$empId','$FamMember','$RelationShip','$occupation','$empId',now(),'$dob')");
  }
    if(mysqli_affected_rows($db)){
      $msg = 'Added Successfully';
    }
 
  header("Location: familyInfo.php");
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Self Service
        <small>My Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Self Service</a></li>
        <li class="active">My Details</li>
      </ol>
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
            						<li class=""><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class=""><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
									<li class="active"><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
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
                          <!-- form start -->
                         <form role="form" id="addFamily" action="addFamily.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group add-item-class">
                                  <label for="exampleInputEmail1">Name<span class="astrick">*</span></label>
                                  <input type="text" tabindex="1" class="form-control" id="FamMember" name="FamMember" value="<?php echo $FamMember; ?>" required>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Date of Birth</label>
									<input type="text" tabindex="3" placeholder="YYYY-MM-DD" class="form-control" id="dob" name="dob" value="<?php echo $dob; ?>" required>	
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Occupation</label>
                                  <input type="text" tabindex="2" class="form-control" id="occupation" name="occupation" value="<?php echo $occupation; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Relationship<span class="astrick">*</span></label>
                                   <select class="form-control" id="RelationShip" tabindex="4" name="RelationShip" required >
								   <?php
								   if($id=='')
								   {
								   ?>
									<option value="">Select Relation</option>
									
									<?php
								   }
								   else
								   {
									?>
									<option value="<?php echo $RelationShip ?>"><?php echo $RelationShip ?></option>
									<?php
								   }
									?>
									<?php
					
										while($row = mysqli_fetch_assoc($relationQuery)){
											echo "<option value='".$row['relation']."'>".$row['relation']."</option>";
									}
                    ?>        
                                         
                    </select>
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="familyInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
                            </div>
                            <!-- /.box-body -->
                          </form>
                          <!-- </form> -->
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
      <!-- Modal -->
      <div class="modal fade" id="addNewFieldModal" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add Education Level</h4>
            </div>
            <form>
            <div class="modal-body">
              <div class="form-group">
                <p class="text-red" id="uploadMsg"></p>
                <label for="exampleInputFile">Education Level<span class="astrick">*</span></label>
                <input type="text" id="newField" name="newField" />
                <input type="hidden" id="tableColumn" name="tableColumn" value="qualification_desc" />
                <input type="hidden" id="tableName" name="tableName" value="all_qualifications" />
                <input type="hidden" id="replaceField" name="replaceField" value="education" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="addNewField">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
<script>
$(function(){
         $('#dob').datepicker({
        autoclose: true
      });
});
</script>