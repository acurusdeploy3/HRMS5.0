<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Training Management</title>
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
  
   <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
		th {
			background-color : #fbe2d8;
		}
		</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php
 require_once('Layouts/main-header.php');
 session_start();
$usergr=$_SESSION['login_user_group'];
 ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php
 require_once('Layouts/main-sidebar.php');
 ?>
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Training Management

      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="TrainingMgmt.php">Training Management</a></li>
        <li class="active">Training Summary</li>
      </ol>
    </section>
	<?php
		$id = $_GET['id'];
		session_start();
		$_SESSION['trainingId']=$id;
		?>
	 <?php
			  include("config2.php");
			  $getTraining = mysqli_query($db,"select a.training_module_id,b.training_desc,training_department,frequency,current_occurence,total_occurence_per_frequency,is_evaluation_required,Date_format(a.created_datE_and_time,'%d - %b - %Y') as created_datE_and_time,cycle_id from active_trainings a
left join all_training_modules b on a.training_module_id=b.training_module_id where training_id=$id");
 $getparticipants = mysqli_query($db,"select a.participant_id,a.employee_id,concat(b.first_name,' ',b.last_name,' ',b.mi) as Name, c.department,concat(c.band,' ',c.designation,' ',c.level) as designation
from training_participants a
left join employee_details b on a.employee_id=b.employee_id
left join resource_management_Table c on a.employee_id=c.employee_id where a.training_id=$id and a.is_active='Y'");
			  $tRow = mysqli_fetch_array($getTraining);
			  $trainMod = $tRow['training_module_id']; 
			  $Module = $tRow['training_desc']; 
			  $Dept = $tRow['training_department']; 
			  $freq = $tRow['frequency']; 
			  $CurOcc = $tRow['current_occurence']; 
			  $EvalReq = $tRow['is_evaluation_required']; 
			  $CyclId = $tRow['cycle_id']; 
			  $datecreated = $tRow['created_datE_and_time']; 
			  $OccPerFre = $tRow['total_occurence_per_frequency']; 
			  session_Start();
			  ?>
			   <?php
			  include("config.php");
			  $query = mysql_query("Select designation_id,designation_desc from all_designations where designation_desc!='$desn'");
			  $query1 = mysql_query("Select band_no,band_desc from all_bands where band_desc !='$band' and band_desc!=''");
			  $query2 = mysql_query("Select level_id,level_desc from all_levels where level_desc !='$level'");
			  $query3 = mysql_query("SELECT department_id,department FROM `all_departments` where department!='$Dept'");
			  $query4 = mysql_query("select project_no,project_id,project_name from all_projects where project_id!='$projname'");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager' and employee_id not in ('$repMngrid','$id')");
			  
			  ?>
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
		 <table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
                    <button onclick="window.history.go(-1); return false;" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>
          <h2 class="page-header"><strong>
           <?php echo $Module.' : Cycle '.$CyclId?> </strong>
            <small class="pull-right"><?php echo  'Date Created : '.$datecreated ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
         <div class="row">
		    

        <!-- /.col -->
      </div>
	  <br>
	 
	  <?php
include("config2.php");  
session_start();
				$trainer = $_SESSION['login_user'];
 $getSessions= mysqli_query($db,"select session_id,topic,trainer,concat(b.first_name,' ',b.last_name,' ',b.MI
) as Name ,date(date_of_Session) as start_Date_db,Date_format(date_of_Session,'%d - %b - %Y') as start_Date,session_time,
session_duration,mode_of_training,is_completed,has_started
 from training_sessions a left join employee_details b on a.trainer=b.employee_id where training_id='$id' and a.is_active='Y' order by start_date ASC");
 
 $getper= mysqli_query($db,"select
round(
(select count(is_completed) from training_Sessions where is_completed!='N' and training_id='$id' and cycle_id='$CyclId' and occurence_count='$CurOcc')
  /
 (select count(*) from training_Sessions where training_id='$id' and cycle_id='$CyclId' and occurence_count='$CurOcc')
   *
   100
  ) as percent");
  $getPerRow = mysqli_fetch_array($getper);
  $percentage = $getPerRow['percent'];
?>
		  <div class="row">
        <div class="col-xs-12">
         
            <div class="box-header">
              <h3 class="box-title"><strong>Training Sessions  </strong>
		</h3>
              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table name="ActProj" id="ActProj" class="table table-striped">
                <tr>
                  <th>Session No</th>
                  <th>Topic </th>
                  <th>Date of Session</th>
                  <th>Session Duration (Hours)</th>
                  <th>Training Mode</th>
                  <th>Status</th>
                
                </tr>
				<?php  
				if(mysqli_num_rows($getSessions)>0)
				{
					$i=1;
					while($SessionRow = mysqli_fetch_assoc($getSessions))
					{
				?>
                <tr>
                  <td class="id"><?php echo $i ?></td>
                  <td class="trainerId" style="display:none" ><?php  echo $SessionRow['trainer']; ?></td>
                  <td class="sessionId" style="display:none" ><?php  echo $SessionRow['session_id']; ?></td>
                  <td class="startdate" style="display:none" ><?php  echo $SessionRow['start_Date_db']; ?></td>
                  <td class="topic"><?php echo $SessionRow['topic'];  ?></td>
                  <td><?php echo $SessionRow['start_Date'];  ?></td>
        
                  <td class="sessduration"><?php echo $SessionRow['session_duration']; ?></td>
                  <td class="modeoftraining"><?php echo $SessionRow['mode_of_training'];  ?></td>
				  <?php
				
				 if($SessionRow['is_completed']=='Y' && $SessionRow['has_started']=='Y')
				 {					 
				  ?>
                  <td> <span class="badge bg-green">Completed</span></td>
				  
				  <?php
				 }
				 elseif($SessionRow['is_completed']=='N' && $SessionRow['has_started']=='Y')
				 {
				  ?>
				   <td> <span class="badge bg-yellow">Session In Progress</span></td>
				  <?php
				 }
				 else
				 {
				  ?>
				  
				   <td> <span class="badge bg-light-blue">Scheduled</span></td>
				  <?php
				 }
				  ?>
			   </tr>
                <?php
					$i++;
					}
				}
				else
				{
					if($usergr=='HR Manager')
					{
					?>
				 <tr>
                  <td>Start Scheduling for your Training!</td>
				  </tr>
<?php				
					}
					else
					{
					?>	
					 <tr>
                  <td>Not Scheduled Yet!</td>
				  </tr>
				<?php			
					}
				}
				?>
              </table>
           
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		 
        <!-- /.col -->
      </div>
	  <br>
	  <br>
	  <?php
	    session_start();
	  $userid = $_SESSION['login_user'];
	
	  ?>
	
	   <div class="row">
        <div class="col-xs-12">
   
            <div class="box-header">
              <h3 class="box-title"><strong>Team Training Attendee(s)</strong></h3>

              <div class="box-tools">
              </div>
			    <br>
			<?php
			
			$getAttendance = mysqli_query($db,"select a.employee_id,a.session_id,has_attended,topic,date(date_of_session) as date_sess from training_attendance a
left join training_sessions b on a.session_id=b.session_id
 where employee_id=$userid and a.training_id='$id' and a.is_active='Y' and b.is_active='Y'");	
			$GetTeamAttendance = mysqli_query($db,"select a.employee_id,a.session_id,has_attended,topic,date(date_of_session) as date_sess,concat(First_Name,' ',Last_name) as Name from training_attendance a
left join training_sessions b on a.session_id=b.session_id
left join employee_details c on a.employee_id=c.employee_id
 where reporting_manager_id=$userid and a.employee_id
  in (select a.employee_id from training_participants where training_id='$id') and a.training_id='$id' and a.is_active='Y' and b.is_active='Y'");
			?>
				</div>
				
          
				<div class="box-body no-padding">
				<table class="table table-striped">
                <tr>
                  
                  <th>Topic</th>
                  <th>Employee ID</th>
                   <th>Name</th>
                  <th>Date of Session</th>
                  <th>Attendance</th>
	
				 </tr>
				
				<?php
				$i=1;
				while($GetAttendanceRow = mysqli_fetch_array($GetTeamAttendance))
				{
					
				?>
				  <tr>
					
					<td><?php echo $GetAttendanceRow['topic']; ?></td>
					<td><?php echo $GetAttendanceRow['employee_id']; ?></td>
                    <td><?php echo $GetAttendanceRow['Name']; ?></td>
					<td><?php echo $GetAttendanceRow['date_sess']; ?></td>
					
					<?php
						if($GetAttendanceRow['has_attended']=='Y')
						{
						?>
						<td><span class="badge bg-green">Attended</td>
						<?php
						}
						elseif($GetAttendanceRow['has_attended']=='N')
						{
						?>
						<td><span class="badge bg-red">Absent</td>
						<?php
						}
						else
						{
						?>
						<td><span class="badge bg-yellow">Not Marked</td>
						<?php
						}
						?>
					 <?php
					 $i++;
				}
					?>
				 </tr>	
  </table>
          
			
		            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		 
        <!-- /.col -->
      </div>				 
	
	  <br>
	  <br> 
	  <?php
	  if($EvalReq=='Yes')
	  {
	  ?>
	  
			  <div class="row">
        <div class="col-xs-12">
   
            <div class="box-header">
              <h3 class="box-title"><strong>Your Team Evaluation Scores</strong></h3>

              <div class="box-tools">
              </div>
			    <br>
			<?php
			$getEvaluation = mysqli_query($db,"SELECT if(a.marks_obtained='Did Not Attend','Did Not Attend',round((a.marks_obtained/a.max_score)*100)) as percentage,date_of_assessment,marks_obtained,evaluation_topic,status FROM `training_evaluation` where employee_id='$userid' and training_id='$id' and is_active='Y'");	
			
			$getTeamEvaluation = mysqli_query($db,"SELECT a.employee_id,if(a.marks_obtained='Did Not Attend','Did Not Attend',round((a.marks_obtained/a.max_score)*100)) as percentage,a.date_of_assessment,a.marks_obtained,evaluation_topic,status FROM
`training_evaluation` a
left join employee_details b on a.employee_id=b.employee_id
where reporting_manager_id=$userid
and a.employee_id
  in (select employee_id from training_participants where training_id='$id')
   and training_id='$id' and a.is_active='Y';")
			?>
				</div>
				
          
				<div class="box-body no-padding">
				<table class="table table-striped">
                <tr>
                  <th>Evaluation #</th>
                  <th>Employee ID</th>
                  <th>Date of Evalutation</th>
                  <th>Evalutaion Criteria</th>
                  <th>Marks Obtained</th>
                  <th>Status</th>
	
				 </tr>
				
				<?php
				while($GetEvaluationRow = mysqli_fetch_array($getTeamEvaluation))
				{
					$i=1;
				?>
				  <tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $GetEvaluationRow['employee_id']; ?></td>
					<td><?php echo $GetEvaluationRow['date_of_assessment']; ?></td>
					<td><?php echo $GetEvaluationRow['evaluation_topic']; ?></td>
					<?php
						if($GetEvaluationRow['percentage']!='Did Not Attend')
						{
					?>
							<td><?php echo $GetEvaluationRow['percentage'].' %'; ?></td>
						<?php
						}
						else
						{
						?>
						<td><span class="badge bg-red"><?php echo $GetEvaluationRow['percentage']; ?></td>
						
						<?php
						}
						?>
					
					<?php
						if($GetEvaluationRow['status']=='Pass')
						{
						?>
						<td><span class="badge bg-green">Pass</td>
						<?php
						}
						elseif($GetEvaluationRow['status']=='Fail')
						{
						?>
						<td><span class="badge bg-red">Fail</td>
						<?php
						}
						else
						{
						?>
						<td><span class="badge bg-blue">Did Not Attend</td>
						<?php
						}
						?>
					 <?php
					 $i++;
				}
					?>
				 </tr>	
  </table>
          
			
		            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		 
        <!-- /.col -->
      </div>
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
			<?php
			}
			?>

	  <br>
	  <br>
	 
	 

      <!-- /.row -->

      <!-- Table row -->
	  <br>
	  <br>
	  <br>
	  <br>
<?php
if($usergr=='HR' || $usergr == 'HR Manager' || $isTrainer=='Y')
{
?>	  
	<div class="row">
        <div class="col-xs-12">
   
            <div class="box-header">
              <h3 class="box-title">Previous Cycle(s) for :<strong><?php echo $Module ?></strong></h3>

              <div class="box-tools">
              </div>
			    <br>
			<?php
			  $getTrainingHistory = mysqli_query($db,"select training_id,a.training_module_id,b.training_desc,training_department,frequency,current_occurence,total_occurence_per_frequency,is_evaluation_required,Date_format(a.created_datE_and_time,'%d - %b - %Y') as created_datE_and_time,cycle_id,date(date_completed) as date_completed from history_active_trainings a
left join all_training_modules b on a.training_module_id=b.training_module_id where training_id=$id order by cycle_id desc");
			?>
				</div>
				
          
				<div class="box-body no-padding">
				<table class="table table-bordered table-striped dataTable no-footer">
                <tr>
                  <th>Cycle</th>
			      <th>Date of Completion</th>
			      <th>View</th>
				 </tr>
				
				<?php
				if(mysqli_num_rows($getTrainingHistory)>0)
				{
				while($GetHistoryRow = mysqli_fetch_array($getTrainingHistory))
				{
				?>
				  <tr>
					<td><?php echo $GetHistoryRow['cycle_id']; ?></td>
					<td><?php echo $GetHistoryRow['date_completed']; ?></td>
						<td><a href="HistoryTrainingSummary.php?id=<?php echo $GetHistoryRow['training_id'] ?>&CID=<?php echo $GetHistoryRow['cycle_id'] ?>"><img alt='User' src='../../dist/img/details-icons--download-8-free-details-icon-page-1--18.png' width='18px' height='18px' /></a> </td>
				
				 </tr>		
				<?php
				}
				}
				else
				{
				?>
				 <tr>
					<td>No Data Found!</td>	
				 </tr>
				<?php
				}
				?>
              </table>
          
			
		            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		 
        <!-- /.col -->
      </div>  
	  
	  
<?php
}
?>	  
	  
	  
	  
	  
	  
	  
	  
	  
      <div class="row">
        <div class="col-xs-12 table-responsive">
		
		
	  
	  <br>
	  <br>
	
		  <div class="modal fade" id="modal-default-Session">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Session</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
          <form id="AllocationForm" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
			  <?php
			  include("config.php");
			  $ExisTrainees  = mysql_query("select group_concat('''',employee_id,'''') as employee_id from training_participants where training_id='$id' and cycle_id='$CyclId' and occurence_count='$CurOcc' and Is_active='Y';");
					$ExixProRow = mysql_fetch_array($ExisTrainees);
					$TIDs = $ExixProRow['employee_id'];
			   $Trainers = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from all_trainers a left join employee_details b
on a.employee_id=b.employee_id");
			   $AllTrainers = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id where a.employee_id not in (select employee_id from all_trainers )");
			  $recaud = mysql_query("select distinct(department) as department from resource_management_table where department!='' and employee_id not in ($TIDs);");
			  $recaudRole = mysql_query("select distinct(designation) as designation from resource_management_table where department!='' and employee_id not in ($TIDs);");
			  $query1 = mysql_query("Select department_id,department from all_departments where department!=''");
			  $EmpQuery = mysql_query("Select a.employee_id,concat(concat(b.First_Name,' ',b.Last_Name,' ',b.Mi),' : ',a.employee_id) as Employee_Name,b.Job_Role from all_trainers a left join employee_details b on a.employee_id=b.employee_id");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager'");
			  $EmpQuery1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id where a.employee_id not in ($TIDs);");
			  $Freq = mysql_query("Select frequency from training_frequency");
			    include("config.php");
			   $Trainers1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from all_trainers a left join employee_details b
on a.employee_id=b.employee_id");
			   $AllTrainers1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id where a.employee_id not in (select employee_id from all_trainers )");
			  ?>
			  
			  <input type="hidden" name="cycleVal" value="<?php echo $CyclId ?>" />
			  <input type="hidden" name="OccVal" value="<?php echo $CurOcc ?>" />
                 <label>Trainer <a href="#" id="additionalTrainer" title="Click to Add More Trainers" data-toggle="modal" data-target="#modal-default-Trainers"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="TrainerSel" name="TrainerSel" style="width: 100%;" required disabled>
				<option value="" selected disabled>Please Select from Below</option>
				<?php
				
				while ($trai = mysql_fetch_assoc($Trainers))
				{
				?>
                  <option value="<?php echo $trai['employee_id']  ?>"><?php echo $trai['Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  
			  <div class="form-group">
                <label>Session Date</label>
					
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text"  name="sessdate" class="form-control pull-right" id="datepicker1" required>
                </div>
              </div>
				<!-- /.form-group -->  
			
			 
			   <div class="form-group">
                 <label>Duration Per Session (Hours)</label>
             <input type="number" class="form-control pull-right" min="0" name="SessDuration" id="SessDuration" placeholder="Enter Duration" required="required" />
              </div>
			  <br>
			  <br>
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
               <div class="form-group"> 
                 <label>Topic</label>
                <input type="text" name="topicText" class="form-control" placeholder="Enter Topic of Interest">
              </div>
			 <div class="form-group">
                <label>Start Time</label>
					
              <div class="input-group">
                    <input type="text" id="startTime" name="startTime" class="">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
              </div>
			  <div class="form-group">
                <label>Mode of Training</label>
					
                 <select class="form-control select2" id="TrainingMode" name="TrainingMode" style="width: 100%;" required>
                 <option value="" selected disabled>Please Select from Below</option>
                 <option value="Classroom Training">Classroom Training</option>
                 <option value="On Demand"  >On Demand</option>
                 <option value="Live Online" >Live Online</option>

                </select>
              </div>
              <!-- /.form-group -->
              
				 
                  
			  
			   
              <!-- /.form-group -->
            </div>
			 
            <!-- /.col -
			
            <!-- /.col -->
          </div>
		  
</div>	
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddtoSchedule" value="Add To Schedule" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		 
		  
		  <div class="modal fade" id="modal-default-Trainee">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Participants</h4>
              </div>
			  <form id="trainingForm" method="post">
            <div class="modal-body">
               <div class="box box-info">
        <div class="form-group">
                <label>Add by Department</label>
					
                <select class="form-control select2" id="SelByDept" name="SelByDept[]" multiple="multiple" data-placeholder="Select Department(s)"
                        style="width: 100%;" required>
                 <?php
				
				while ($aud = mysql_fetch_assoc($recaud))
				{
				?>
                  <option value="<?php echo $aud['department']  ?>"><?php echo $aud['department']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
            <div class="form-group">
                <label>Add by Role</label>
					
                <select class="form-control select2" id="SelByRole" name="SelByRole[]" multiple="multiple" data-placeholder="Select Role(s)"
                        style="width: 100%;" required>
                 <?php
				
				while ($audR = mysql_fetch_assoc($recaudRole))
				{
				?>
                  <option value="<?php echo $audR['designation']  ?>"><?php echo $audR['designation']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  <div class="form-group">
                <label>Add By Employee</label>
					
                <select class="form-control select2" id="TraineesSel" name="TraineesSelVal[]" multiple="multiple" data-placeholder="Select Trainee(s)"
                        style="width: 100%;" required>
                 <?php
				
				while ($emp2 = mysql_fetch_assoc($EmpQuery1))
				{
				?>
                  <option value="<?php echo $emp2['employee_id']  ?>"><?php echo $emp2['Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>  
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddTrainee" value="Add Trainee" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
 
		  
		  <?php
			  include("config.php");
			  $ExisTrainees  = mysql_query("select group_concat('''',employee_id,'''') as employee_id from training_participants where training_id='$id' and cycle_id='$CyclId' and occurence_count='$CurOcc' and Is_active='Y';");
					$ExixProRow = mysql_fetch_array($ExisTrainees);
					$TIDs = $ExixProRow['employee_id'];
			   $Trainers = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from all_trainers a left join employee_details b
on a.employee_id=b.employee_id");
			   $AllTrainers = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id where a.employee_id not in (select employee_id from all_trainers )");
			  $recaud = mysql_query("select distinct(department) as department from resource_management_table where department!='' and employee_id not in ($TIDs);");
			  $recaudRole = mysql_query("select distinct(designation) as designation from resource_management_table where department!='' and employee_id not in ($TIDs);");
			  $query1 = mysql_query("Select department_id,department from all_departments where department!=''");
			  $EmpQuery = mysql_query("Select a.employee_id,concat(concat(b.First_Name,' ',b.Last_Name,' ',b.Mi),' : ',a.employee_id) as Employee_Name,b.Job_Role from all_trainers a left join employee_details b on a.employee_id=b.employee_id");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager'");
			  $EmpQuery1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id where a.employee_id not in ($TIDs);");
			  $Freq = mysql_query("Select frequency from training_frequency");
			    include("config.php");
			   $Trainers1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from all_trainers a left join employee_details b
on a.employee_id=b.employee_id");
			   $AllTrainers1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id where a.employee_id not in (select employee_id from all_trainers )");
			  ?>
		  
		  
		  
		  
		 <div class="modal fade" id="modal-default-Modify">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modify Session</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php  
		   
		   ?>
            <form id="ModificationForm" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
			  
			  <input type="hidden" name="cycleVal" value="<?php echo $CyclId ?>" />
			  <input type="hidden" name="OccVal" value="<?php echo $CurOcc ?>" />
			  <input type="hidden" name="sessionid" id="sessionid" />
                 <label>Trainer <a href="#" id="additionalTrainer" title="Click to Add More Trainers" data-toggle="modal" data-target="#modal-default-Trainers"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="TrainerSelModify" name="TrainerSelModify" style="width: 100%;" disabled>
				<option value="" disabled>Please Select from Below</option>
				<?php
				
				while ($train = mysql_fetch_assoc($Trainers1))
				{
				?>
                  <option value="<?php echo $train['employee_id']  ?>"><?php echo $train['Name'];  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  
			  <div class="form-group">
                <label>Session Date</label>
					
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="sessdateMdify" class="form-control pull-right" id="datepicker" required>
                </div>
              </div>
				<!-- /.form-group -->  
			
			 
			   <div class="form-group">
                 <label>Duration Per Session (Hours)</label>
             <input type="number" class="form-control pull-right" min="0" name="SessDurationModify" id="SessDurationModify" placeholder="Enter Duration" required="required" />
              </div>
			  <br>
			  <br>
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
               <div class="form-group"> 
                 <label>Topic</label>
                <input type="text" name="topicTextModify" id="topicTextModify" class="form-control" placeholder="Enter Topic of Interest" >
              </div>
			 <div class="form-group">
                <label>Start Time</label>
					
              <div class="input-group">
                    <input type="text" id="startTimeModify" name="startTimeModify" class="form-control timepicker">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
              </div>
			  <div class="form-group">
                <label>Mode of Training</label>
					
                 <select class="form-control select2" id="TrainingModeModify" name="TrainingModeModify" style="width: 100%;" required>
                 <option value="" selected disabled>Please Select from Below</option>
                 <option value="Classroom Training">Classroom Training</option>
                 <option value="On Demand"  >On Demand</option>
                 <option value="Live Online" >Live Online</option>

                </select>
              </div>
              <!-- /.form-group -->
              
				 
                  
			  
			   
              <!-- /.form-group -->
            </div>
			 
            <!-- /.col -
			
            <!-- /.col -->
          </div>
		  
</div>	
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="ModifySchedule" value="Modify Schedule" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div> 
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		 
		  
		 
		 
		 
		  <div class="modal fade" id="modal-default-Trainers">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Trainer</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label>Add Trainer</label>
                <select class="form-control select2" id="TrainerSelNew" name="TrainerSelNew" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php
				
				while ($Atrai = mysql_fetch_assoc($AllTrainers))
				{
				?>
                  <option value="<?php echo $Atrai['employee_id']  ?>"><?php echo $Atrai['Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
                </div>			
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeTrainer" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addTrainer"  class="btn btn-primary">Add Trainer</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 


<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Know your Project ID</h4>
              </div>
              <div class="modal-body">
                 <table class="table table-bordered">
			  <tr>
                  
                  <th>Project</th>
                  <th style="width: 40px">Project ID</th>
                </tr>
			  <?php 
				$ProjectQuery = mysql_query("select project_no,project_id,project_name from all_projects where project_id!=''");
				
				$i = 1;
				while($ProRow = mysql_fetch_assoc($ProjectQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $ProRow['project_name'];  ?></td>
                 <td><?php echo $ProRow['project_id'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>   
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>










		 
		  
		  
		  
		  
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      
      <!-- /.row -->

      <!-- this row will not appear when printing -->
     
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>
  <!-- Content Wrapper. Contains page content -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  
    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong> 
  </footer>

  <!-- Control Sidebar -->
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
   $(document).on('click','#AddtoSchedule',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#AllocationForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertSession.php",
         success: function(data){
			 
		location.reload();
		   ajaxindicatorstop();
			
         }
});

});
    </script>
	
	
	

	
	<script type="text/javascript">
   $(document).on('click','#ModifySchedule',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ModificationForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "ModifySession.php",
         success: function(data){
			 
		location.reload();
		   ajaxindicatorstop();
			
         }
});

});
    </script>
	
	
	
	
	
	<script type="text/javascript">
       $(document).on('click','#addTrainer',function(e) {
		   var data = $("#TrainerSelNew").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddTrainer.php",
         success: function(data){
			 AdditionalTrainer();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	<script type="text/javascript">
       function AdditionalTrainer() {
          
			var modal = document.getElementById('modal-default-Trainers');
            var ddl = document.getElementById("TrainerSel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("TrainerSelNew").value;
            option.value = document.getElementById("TrainerSelNew").value;
            ddl.options.add(option);
			document.getElementById("closeTrainer").click();
			//document.getElementById("inputDept").value="";
        
			     
        }
    </script>
<script type="text/javascript">
//note also the proper type declaration in script tags, with NO SPACES (IMPORTANT!)

function addVal(){
   document.getElementById("AllocatedPer").stepUp(5);
}
function addValMod(){
   document.getElementById("ModifiedAllocatedPer").stepUp(5);
}
function SubVal(){
    document.getElementById("AllocatedPer").stepDown(5);
}
function SubValMod(){
    document.getElementById("ModifiedAllocatedPer").stepDown(5);
}
</script>
<script type="text/javascript">
   $(document).on('click','#AddTrainee',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#trainingForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertNewTrainee.php",
         success: function(data){	 
		 location.reload();
		   ajaxindicatorstop();
			
         }
});

});
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
   $(document).on('click','#ModifyTraining',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#trainingForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertModifiedTraining.php",
         success: function(data){
			 
		 window.location.href = "TrainingMgmt.php";
		   ajaxindicatorstop();
			
         }
});

});
    </script>
	<script>
	
	$(function() {
  var bid, trid;
  $('#ActProj tr').click(function() {
       Id = $(this).find('.sessionId').text();
       Topic = $(this).find(".topic").text();
	   Time = $(this).find(".starttime").text();
	   DateVak = $(this).find(".startdate").text();
	   SessDuration = $(this).find(".sessduration").text();
	   Trainer = $(this).find(".trainerId").text();
	   TrainingMode = $(this).find(".modeoftraining").text();
		$('#sessionid').val(Id);
		$('#topicTextModify').val(Topic);
		$('#startTimeModify').val(Time);
		$('#datepicker').val(DateVak);
		$('#SessDurationModify').val(SessDuration);
		$('#TrainerSelModify option[value="'+Trainer+'"]').prop('selected', true);
		$('#TrainingModeModify option[value="'+TrainingMode+'"]').prop('selected', true);
		
  });
});
	</script>
	
	<script type="text/javascript">
	$('#ProjSelect').one('change', function() {
			
			$('#AddProj').prop('disabled', false);
	});

	</script>

	<script type="text/javascript">
   $(document).on('click','#AddProj',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ProForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertNewProj.php",
         success: function(data){
			 
			location.reload();
		   ajaxindicatorstop();
			
         }
});

});
    </script>
	
	
	
	<script type="text/javascript">
   $(document).on('click','#ModifyAlloc',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ModForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "ModProAlloc.php",
         success: function(data){
			 
			location.reload();
		   ajaxindicatorstop();
			
         }
});

});
    </script>
	
	
	
	

	
	<script type="text/javascript">
   $(document).on('click','#TestTraining',function(e) {
     
	ajaxindicatorstart("Please Wait..");
});
    </script>
	<script type="text/javascript">
    $(document).on('click','#editClick',function(e){
        $("#ResForm :input").prop("disabled", false);
    });
</script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
<script type="text/javascript">
       $(document).on('click','#UploadDoc',function(e) {
		//var data = $("#ResumeFileDoc").files;
		 e.preventDefault();
		ajaxindicatorstart("Please Wait..");
  $.ajax({
		type: 'POST',
		url: 'UploadLOA.php',
		data: new FormData('#roleForm'),
		contentType: false,
		cache: false,
		processData:false,
		url: "UploadLOA.php",
		 enctype: 'multipart/form-data',
         success: function(data){
			alert(data);
			 window.location.href = "ViewResource.php?id="+id;
			  ajaxindicatorstop();
         }
});
 });
    </script>
	
<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>
