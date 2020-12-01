<?php

require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = $skill = $competencyLevel = $monthsUsed = $lastUsed = '';
$id = 0;
$getskillevels = mysqli_query($db,"select * from all_skill_levels");
if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $eduHistory = mysqli_query($db,"select * from employee_skills where employee_id = $empId and skill_id = $id and is_active = 'Y'");
  $eduHistoryData = mysqli_fetch_assoc($eduHistory);

  $skill = @$eduHistoryData['skill_desc'];
  $competencyLevel = @$eduHistoryData['competency_level'];
  $monthsUsed = @$eduHistoryData['months_of_experience'];
  $lastUsed = @$eduHistoryData['year_last_used'];
}

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $skill = (isset($_POST['skill']) && $_POST['skill'] != '')?$_POST['skill']:$skill;
  $competencyLevel = (isset($_POST['competencyLevel']) && $_POST['competencyLevel'] != '')?$_POST['competencyLevel']:'';
  $monthsUsed = (isset($_POST['monthsUsed']) && $_POST['monthsUsed'] != '')?$_POST['monthsUsed']:'';
  $lastUsed = (isset($_POST['lastUsed']) && $_POST['lastUsed'] != '')?$_POST['lastUsed']:'';
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;

  if($id){
    mysqli_query($db,"update employee_skills set skill_desc = '$skill', competency_level = '$competencyLevel', months_of_experience = '$monthsUsed', year_last_used = '$lastUsed', modified_by = $empId, modified_date_and_time = '$currentDate' where employee_id = $empId and skill_id = $id");
    if(mysqli_affected_rows($db)){
      storeDataInHistory($id, 'employee_skills','skill_id');
      $msg = 'Updated Successfully';
    }
  }else{
    mysqli_query($db, "INSERT INTO employee_skills(employee_id, skill_desc, competency_level, months_of_experience, year_last_used, created_by, created_date_and_time) VALUES('$empId', '$skill','$competencyLevel','$monthsUsed','$lastUsed',$empId,'$currentDate')");
    if(mysqli_affected_rows($db)){
      $msg = 'Added Successfully';
    }
  }
  header("Location: skillsInfo.php");
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
									<li class=""><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
            						<li class=""><a href="certificationsInfo.php" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                        <li class=""><a href="KYEInfo.php" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
            						<li class=""><a href="documentsInfo.php" data-toggle="tab" aria-expanded="false">Documents</a></li>
            						<li class="active"><a href="skillsInfo.php" data-toggle="tab" aria-expanded="false">Skills</a></li>
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
                           <form role="form" id="addSkills" action="addSkills.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Skill<span class="astrick">*</span></label>
                                  <input type="text" tabindex="1" class="form-control" id="skill" name="skill" value="<?php echo $skill; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Competency Level<span class="astrick">*</span></label>
                                 <select tabindex="3" class="form-control" id="competencyLevel" name="competencyLevel">
                                 	<?php if($competencyLevel!='')  { ?> 
                                 	<option value="<?php echo $competencyLevel ?>" selected><?php echo $competencyLevel ?></option>
                                   <?php  
                                 	while($rowq = mysqli_fetch_assoc($getskillevels))
                                    {
                                    	if($rowq['level']!=$competencyLevel)
                                        {
                                          ?>
                                 
                                 		<option value="<?php echo $rowq['level'] ?>" ><?php echo $rowq['level'] ?></option>
                                 <?php
                                        }
                                    }
                                  } else

										{
											while($rowq = mysqli_fetch_assoc($getskillevels))
                                   		 {
                                    	?>
                                 
                                 		<option value="<?php echo $rowq['level'] ?>"> <?php echo $rowq['level'] ?></option>
                                 <?php
                                        }
									}   ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Months Used<span class="astrick">*</span></label>
                                  <input type="text" tabindex="2" class="form-control" id="monthsUsed" name="monthsUsed" value="<?php echo $monthsUsed; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Last Used<span class="astrick">*</span></label>
                                  <select tabindex="4" class="form-control" id="lastUsed" name="lastUsed">
                                    <option value="">Last Time Used</option>
                                    <?php
                                    for($i=0;$i<=$limit;$i++){
                                      $start_year = $startYear+$i;
                                      echo "<option value='".$start_year."'";
                                      if($start_year == $lastUsed){
                                        echo 'selected';
                                      }
                                      echo ">".$start_year."</option>";
                                    }
                                    ?>
                                  </select>
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="skillsInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
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
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
