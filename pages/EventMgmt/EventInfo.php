<?php
session_start();
date_default_timezone_set("Asia/Calcutta"); 
include("config.php");
$EventID = $_GET['id'];
$userid = $_SESSION['login_user'];
$_SESSION['eventid'] = $EventID ;
$_SESSION['fromEventsHome']='Y';
$userRole = $_SESSION['login_user_group'];
$getEventDetails = mysqli_query($db,"SELECT date_from as date_with_time,date(date_from) as date_from,date(date_to) as date_to,date_format(date_from,'%D %M, %Y') as date_formatted,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo,reg_last_date,is_breakfast_included,is_lunch_included,is_snacks_included,is_dinner_included,additional_food_count,is_registration_required FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);
$getSpeakers = mysqli_query($db,"select id,name,`desc`,email,contact_info,photograph from event_External_invitees where event_id='$EventID' and iS_active='Y'");
$getAgenda = mysqli_query($db,"select date_format(session_date,'%D %M, %Y') as date_formatted,date_format(session_date,'%h:%i %p') as session_time,activity from event_agenda
where is_active='Y' and event_id='$EventID'");
$getCommittee = mysqli_query($db,"select a.employee_id,concat(b.first_name,' ',b.last_name,' ',b.mi) as Name,employee_image, role from event_management_team a
inner join employee_details b on a.employee_id=b.employee_id where event_id='$EventID'");
$getFamily = mysqli_query($db,"select replace(family_member_name,' ','') as postVariableFam,family_member_name,relationship_with_employee,if(date_of_birth='0001-01-01','--',timestampdiff(year,date_of_birth,curdate())) as age from employee_family_particulars where is_Active='Y' and employee_id=$userid;");
$isFamEligible = mysqli_query($db,"select * from event_invitees where event_id='$EventID' and is_family_included='Y'");
$isRegistered = mysqli_query($db,"select * from event_invitation_acceptors where employee_id='$userid' and event_id='$EventID' and is_active='Y'");
if(mysqli_num_rows($isRegistered)>0)
{
	$Registed='Y';
}
else
{
	$Registed='N';
}
$Editaccesscontrol = mysqli_query($db,"select * from user_access_control where main_menu='Event Management'
						and sub_menu='Editing' and accessed_to='$userRole'");
if(mysqli_num_rows($Editaccesscontrol)>0)
{
	$isEditEligible='Y';
}
$FoodServed='';
if($getEventDetailsRow['is_breakfast_included']=='Y' || $getEventDetailsRow['is_lunch_included']=='Y' || $getEventDetailsRow['is_snacks_included']=='Y' || $getEventDetailsRow['is_dinner_included']=='Y')
	{
		$FoodServed='Y';
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Events & Happenings</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=0.25, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >
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
<style>
.table table-striped:hover {
  background-color: #ffff99;
}
th {
   background-color: #4b867b;
  color:white;
}
.form_error span {
  width: 80%;
  height: 35px;
  margin: 3px 10%;
  font-size: 1.1em;
  color: #D83D5A;
}
.form_error input {
  border: 1px solid #D83D5A;
}

/*Styling in case no errors on form*/
.form_success span {
  width: 80%;
  height: 35px;
  margin: 3px 10%;
  font-size: 1.1em;
  color: green;
}
.form_success input {
  border: 1px solid green;
}
#error_msg {
  color: red;
  text-align: center;
  margin: 10px auto;
}
</style>
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

.timer {
  border-radius: 2px;
  box-shadow: 0 0 5px 0 rgba(0,0,0,.2);
  display: inline-block;
  margin: 25px;
  padding: 25px 30px;
}

.timer__item {
  display: inline-block;
  text-align: center;
}

.timer__item + .timer__item {
  margin-left: 20px;
}

.timer__value,
.timer__label {
  display: block;
}

.timer__value {
  font-size: 28px;
  font-weight: 400;
  margin-bottom: 2px;
}

.timer__label {
  color: #777;
  font-size: 12px;
  letter-spacing: .04em;
  text-transform: uppercase;
}

.astrick
{
	color:red;
}

      
</style>
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Events & Happenings 	

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Events & Happenings</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
		 
		   <div class="col-md-12">
		   
				<div class="box box-primary">
            <form role="form">
              <div class="box-body" style="
    background-color: whitesmoke;
">
			    <div class="col-md-12" style="background-color: whitesmoke; text-align:center; background-image: url('Images/BG5.png');width: 100%;height: 500px; background-size: 1500px 600px; color: #da6464;" >
		   <br>
		   <br>
		   <br>
						<div class="form-group">
							<h1 class="box-title" style="
    color: white;
"><strong><?php echo $getEventDetailsRow['event_title'] ?> </strong></h1>
						</div>
						<div class="form-group">
							<h3 class="box-title" style="
    color: white;
"><i class="fa fa-fw fa-calendar"></i>&nbsp; <?php echo $getEventDetailsRow['date_formatted']; ?> </h3>
						</div>
						<?php
						if($getEventDetailsRow['event_location']!='')
						{
						?>
						<div class="form-group">
							<h3 class="box-title" style="
    color: white;
"><i class="fa fa-fw fa-map-marker"></i>&nbsp;<?php echo $getEventDetailsRow['event_location']; ?></h3>
						</div>
						
						<?php
						}
						else
						{
						?>
						<div class="form-group">
							<h3 class="box-title" style="color: white;"><i class="fa fa-fw fa-map-marker"></i>&nbsp; Acurus Solutions</h3>
						</div>
						<?php
						}
						?>
				<?php
				if($getEventDetailsRow['is_registration_required']!='N')
				{
				?>
					<?php 
						if($getEventDetailsRow['date_with_time']>=date('Y-m-d H:i:s'))
						{
					?>
						<?php
						if($getEventDetailsRow['reg_last_date']>=date('Y-m-d'))
						{
						?>
							<?php
							if(mysqli_num_rows($isFamEligible)>0)
							{
								if($Registed!='Y')
								{
							?>
								<button type="button" data-toggle="modal" data-target="#myModal" class="btn bg-olive margin">Register for Event</button>
							<?php
								}
							else
								{
							?>
								<button type="button"  class="btn bg-olive margin" disabled>Registered</button>
							<?php	
								}
							}
							else
							{
								if($Registed!='Y')
								{
							?>
									<button type="button" id="directRegister" class="btn bg-olive margin">Register for Event</button>  
							<?php
								}
								else
								{
							?>
									<button type="button"  class="btn bg-olive margin" disabled>Registered</button>
							<?php	
								}
								}	
						   ?>
						   <?php
						}
						else
						{
						   ?>
						   <button type="button"  class="btn bg-olive margin" disabled>Registration Closed</button> 
						   
						<?php
						}
					}
						else
						{
				  ?>
				  
				  <button type="button"  class="btn bg-olive margin" disabled>This Event has Completed</button>
				  
				  <?php
						}
				  ?>
				  <?php
				}
				else
				{
				  ?>
				  <br>
				  <?php
				}
				  ?>
						   <br>
		   <br>
		   <br>
		   <br>
		   <?php 
						if($getEventDetailsRow['date_with_time']>=date('Y-m-d H:i:s'))
						{
					?>
						<div class="form-group">
					
							<div id="js-timer" class="timer" role="timer"aria-label="A timer, counting down to {{eventName}}" data-endtime="<?php echo $getEventDetailsRow['date_with_time'];?>">
		
      <span class="timer__item" id="timer-days">
				<span class="timer__value">{{days}}</span>
				<span class="timer__label">Days</span>
			</span><!--/.timer__item-->

			<span class="timer__item" id="timer-hours">
				<span class="timer__value">{{hours}}</span>
				<span class="timer__label">
					Hours
				</span>
			</span><!--/.timer__item-->

			<span class="timer__item" id="timer-minutes">
				<span class="timer__value">{{minutes}}</span>
				<span class="timer__label">
					Minutes
				</span>
			</span><!--/.timer__item-->

			<span class="timer__item" id="timer-seconds">
				<span class="timer__value">{{seconds}}</span>
				<span class="timer__label">
					Seconds
				</span>
			</span><!--/.timer__item-->
      
		</div><!--/.timer-->



					</div>
		<?php
						}
						else
						{
							if($userRole !='HR Manager')
							{
								if($Registed=='Y')
								{
									$isFeedSum = mysqli_query($db,"select * from event_feedbacks where employee_id=$userid and event_id='$EventID'");
									if(mysqli_num_rows($isFeedSum)==0)
									{
										?>
											<br>
											<br>
										<div class="form-group">
								<h4 class="box-title">Thank you for your Interest. Your Feedback is Important to Us. Kindly help us Improve.</h4>
									</div>
							<button type="button" onclick="window.location='SubmitFeedback.php?id=<?php echo $EventID  ?>'" class="btn bg-olive margin">Submit Feedback</button>
								<?php
									}
								}
							}
							else
							{
								?>
							<button type="button" onclick="window.location='ViewAllFeedBacks.php?id=<?php echo $EventID  ?>'" class="btn bg-olive margin">Summary & Feedback</button>	
								<?php
							}
						}
								?>
						</div>
                </div>
            </form>
	</div>
	
	
	
	<div class="box box-info">
	 <div class="box-header with-border" style="background-color: #eaeaea;">
              <h4 class="box-title">About
			  <strong><?php echo ' '.$getEventDetailsRow['event_title'] ?></strong></h4>
			  
              <div class="box-tools">
			  <?php
				if($isEditEligible=='Y')
				{
					?>
<button type="button" onclick="window.location='NewEventDesc.php?id=<?php echo $EventID  ?>'" style="background-color: ivory;">Edit</button>
			<?php
				}
			?>
              </div>
            </div>
            <form role="form">
              <div class="box-body" style="background-color: white;">
			    <div class="col-md-12">
						<fieldset>
						<div class="form-group">
							<h5><?php echo $getEventDetailsRow['event_desc'] ?></h5>
						
						</div>
					</fieldset>
						</div>
                </div>
            </form>
	</div>
	
	<div class="box box-primary">
	<div class="box-header with-border" style="background-color: #eaeaea;">
      <h4 class="box-title"><strong>Keynote Speakers</strong></h4>
              
			  
			  <div class="box-tools">
			   <?php
				if($isEditEligible=='Y')
				{
					?>
<button type="button" onclick="window.location='NewEventSpeakers.php?id=<?php echo $EventID  ?>'" style="background-color: ivory;">Edit</button>
			<?php
				}
			?>
              </div>
			  
            </div>
            <form role="form">
		  <div class="box-body" style="
    background-color: white;">
			<div class="col-md-12">
				<fieldset>
				<?php
				if(mysqli_num_rows($getSpeakers)>0)
				{
					while($getSpeakersRow = mysqli_fetch_assoc($getSpeakers))
					{
				?>
				 <div class="col-md-4">
				<div class="box-body box-profile" style="background-color: #02263a;">
				<img class="profile-user-img img-responsive img-circle" src="../../uploads/<?php  if($getSpeakersRow['photograph']==' ') { echo 'avatar5.png'; } else { echo $getSpeakersRow['photograph']; } ?>" alt="User profile picture">

					<h3 class="profile-username text-center" style="
    color: white;
"><strong><?php echo $getSpeakersRow['name'];  ?></strong></h3>

					<p class="text-muted text-center" style="
    color: white;
"><i class="fa fa-fw fa-suitcase"></i><?php echo $getSpeakersRow['desc']; ?></p>

            </div>
				 </div>	
				 <?php
					}
				}
				else
				{
				 ?>
				 <div class="form-group">
						<img alt='User' src='Images/keynote-speaker.png' align="center" style="width:7%; height: 7%; margin-left: auto;
						margin-right: auto; display: block;" />
						<h5 class="box-title" style="text-align: center;">No Data Available</h5>
				 </div>
				 
				 <?php
				}
				 ?>
			</div> 
           
         
          
					</fieldset>
			</div>
			 </form>
                </div>
        


<div class="box box-info">
<div class="box-header with-border" style="background-color: #eaeaea;">
     <h4 class="box-title"><strong>Agenda for the Dates</strong>&nbsp;</h4>
               
			  
			  <div class="box-tools">
			  <?php
				if($isEditEligible=='Y')
				{
					?>
<button type="button" onclick="window.location='EventAgenda.php?id=<?php echo $EventID  ?>'" style="background-color: ivory;">Edit</button>
			<?php
				}
			?>
              </div>
			  
            </div>
            <form role="form">
              <div class="box-body" style="
    background-color: white;">
			    <div class="col-md-12" style="background-image: url('Images/BK7.png');" >
						<div class="form-group">
						</div>
						<fieldset>
						<div class="form-group">
							
							 <table id='leaveTable' style='padding: 0px;' class='table table-bordered'>
							<tr>
								<th>Date</th>
								<th>Time</th>
								<th>Scheduled Session</th>  
								</tr>
			<?php
			if(mysqli_num_rows($getAgenda)>0)
			{
				while($getAgendaRow = mysqli_fetch_assoc($getAgenda))
				{
				?>
				<tr>
                  <td><?php  echo $getAgendaRow['date_formatted']; ?></td>
                  <td><?php  echo $getAgendaRow['session_time']; ?></td>
                  <td><?php  echo $getAgendaRow['activity']; ?></td>
                </tr>
				<?php
				}
			}
			else
			{
				?>
                <tr>
                  <td>To be Announced Soon! Stay Tuned.</td>
                </tr>

				<?php
			}
				?>
              </table> 
						
						</div>
					</fieldset>
						</div>
                </div>
            </form>
	</div>
<?php
if($getEventDetailsRow['event_location']!='')
{
?>	

	<div class="box box-primary">
            <form role="form">
			<div class="box-header with-border" style="background-color: #eaeaea;">
              <h3 class="box-title">Venue:<strong>&nbsp; <?php echo $getEventDetailsRow['event_location'];  ?></strong>
			  </h3>
			   </div>
              <div class="box-body" style="
    background-color: white;
    ">
			    <div class="col-md-12">
						<iframe src="index.html" style="width: 100%;height: 500px;"></iframe>
				</div>
                </div>
            </form>
	</div>
	<?php
}
	?>
	
	
		<div class="box box-info">
		<div class="box-header with-border" style="background-color: #eaeaea;">
      <h4 class="box-title"><strong>Board of Committees & Members</strong></h4>
             
			 <div class="box-tools">
			 <?php
				if($isEditEligible=='Y')
				{
					?>
<button type="button" onclick="window.location='EventReq.php?id=<?php echo $EventID  ?>'" style="background-color: ivory;">Edit</button>
			<?php
				}
			?>
              </div>
            </div>
            <form role="form">
		  <div class="box-body" style="
    background-color: white;">
			<div class="col-md-12">
				<fieldset>
				<?php
				if(mysqli_num_rows($getCommittee)>0)
				{
					while($getCommitteeRow = mysqli_fetch_assoc($getCommittee))
					{
				?>
				<div class="col-md-4">
				<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="../../uploads/<?php echo $getCommitteeRow['employee_image']; ?>" alt="User profile picture">

					<h3 class="profile-username text-center"><strong><?php echo $getCommitteeRow['Name']; ?></strong></h3>

					<p class="text-muted text-center"><?php echo $getCommitteeRow['role']; ?></p>

            </div>
				 </div>	
				 <?php
					}
				}
				else
				{
				 ?>
				 <div class="form-group">
						<img alt='User' src='Images/keynote-speaker.png' align="center" style="width:7%; height: 7%; margin-left: auto;
						margin-right: auto; display: block;" />
						<h5 class="box-title" style="text-align: center;">No Data Available</h5>
				 </div>
				 
				 <?php
				}
				 ?>
			</div> 
           
         
          
					</fieldset>
			</div>
			 </form>
                </div>
				
			<?php
				if($userRole=='HR' || $userRole=='HR Manager' || $userRole =='HOD Accounts' || $userRole=='Chief Executive Officer')
				{
					$getInvitedCount = mysqli_query($db,"select employee_id from event_invitees where event_id=$EventID and is_active='Y';");
					$InvitedCount = mysqli_num_rows($getInvitedCount);
					$getRespondedCount = mysqli_query($db,"SELECT employee_id FROM `event_invitation_acceptors` where event_id=$EventID and is_active='Y';");
					$RespondedCount = mysqli_num_rows($getRespondedCount);
					$getRespondedFamily = mysqli_query($db,"SELECT * FROM `event_invitation_acceptors_family` where event_id=$EventID and is_active='Y';");
					$FamilyCount = mysqli_num_rows($getRespondedFamily);
					$TotalAttendees = $FamilyCount+$RespondedCount;
					$getRespondedCount = mysqli_query($db,"SELECT employee_id FROM `event_invitation_acceptors` where event_id=$EventID and is_active='Y';");
					$getActualTurnOut = mysqli_query($db,"select * from event_invitation_acceptors where event_id='$EventID' and has_attended='Y'");
					$getActualMR = mysqli_query($db,"select * from event_invitation_acceptors where event_id='$EventID' and is_memento_received='Y'");
			?>			
				<div class="col-md-6">
				
			<div class="box box-primary">
		<div class="box-header with-border" style="background-color: #eaeaea;">
              <h4 class="box-title">
      <h4 class="box-title"><strong>Event Invitee(s)</strong></h4>
			 <div class="box-tools">
			 
              </div>
            </div>
            <form role="form">
		  <div class="box-body" style="
    background-color: white;">
			<div class="col-md-12" style="background-image: url('Images/BK7.png');">
				<fieldset>
				 <div class="form-group">
						<h4 class="box-title" style="text-align: left;">RSVP Sent : <strong><?php echo ' '.$InvitedCount; ?></strong> &nbsp; <a href="NewEventInvitees.php?id=<?php echo $EventID; ?>">View Invitees</a> ||&nbsp; <a href="PublishEvent.php?id=<?php echo $EventID; ?>">View Invitation</a></h4>
				 </div>
				 <div class="form-group">
						<h4 class="box-title" style="text-align: left;">Expected Attendee(s)  : <?php if(mysqli_num_rows($isFamEligible)==0) {?><strong><?php echo ' '.$RespondedCount; ?></strong>  <?php  } else {?>  <strong><?php echo 'Staff : '.$RespondedCount.' Family : '.$FamilyCount; ?></strong>           <?php }  ?> </h4> 
				 </div>
				 <div class="form-group">
						<h4 class="box-title" style="text-align: left;"><strong>View : &nbsp;</strong> <a href="EventAttendees.php?id=<?php echo $EventID; ?>">Event Attendees</a> || &nbsp; <a href="EventNotResponded.php?id=<?php echo $EventID; ?>">Not Attending</a>  </h4>
				 </div>
				  <div class="form-group">
						<h4 class="box-title" style="text-align: left;">Actual Turnout(s)  : <strong><?php echo ' '.mysqli_num_rows($getActualTurnOut); ?></strong> &nbsp; <a href="MarkAttendance.php?id=<?php echo $EventID; ?>">Mark Attendance</a></h4> 
				 </div>
				 <div class="form-group">
						<h5 class="box-title" style="text-align: left;"><span class="astrick">* </span> Expected Attendee(s) # is based on RSVP Response.</h4> 
				 </div>
			</div> 
           
         
          
					</fieldset>
			</div>
			 </form>
                </div>
				
                </div>	
				
			
				
			<div class="col-md-6">
				<?php
				$isFoodRequired = mysqli_query($db,"select ");
				$getMeals = mysqli_query($db,"select Trim(trailing ',' from concat(if(is_breakfast_included='Y','Breakfast,',''),if(is_lunch_included='Y','Lunch,',''),
if(is_dinner_included='Y','Dinner,',''),if(is_snacks_included='Y','Snacks,',''))) as meal
from active_events where event_id=$EventID;");
				$getMealsRow = mysqli_fetch_array($getMeals);
				$MealsArranged = $getMealsRow['meal'];
				$getMementoDesc = mysqli_query($db,"SELECT memento_desc,additional_required FROM `event_common_memento` where event_id=$EventID;");
				$getMementoDescRow = mysqli_fetch_array($getMementoDesc);
				$MementoArranged = $getMementoDescRow['memento_desc'];
				?>
			<div class="box box-primary">
		<div class="box-header with-border" style="background-color: #eaeaea;">
      <h4 class="box-title"><strong>Event Subsidiaries(s)</strong></h4>
			 <div class="box-tools">
			<button type="button" onclick="window.location='EventReq.php?id=<?php echo $EventID  ?>'" style="background-color: ivory;">Edit</button>
              </div>
            </div>
            <form role="form">
		  <div class="box-body" style="
    background-color: white;">
			<div class="col-md-12" style="background-image: url('Images/BK7.png');">
			
				<fieldset>
				 <div class="form-group">
						<h4 class="box-title" style="text-align: left;">Arranged Meal : <strong><?php if($FoodServed=='Y') { echo ' '.$MealsArranged; } else  { echo 'NIL';}  ?>.</strong></h4>
				 </div>
				 <div class="form-group">
						<h4 class="box-title" style="text-align: left;">Approx. Count Required : <strong><?php if($FoodServed=='Y') { echo ' '.$TotalAttendees+$getEventDetailsRow['additional_food_count'].' (Includes Additional #)'; } else  { echo 'NIL';}  ?>.</strong></h4>
				 </div>
				  <div class="form-group">
						<h4 class="box-title" style="text-align: left;">Arranged Memento : <strong><?php if(mysqli_num_rows($getMementoDesc)>0) { echo ' '.$MementoArranged;  } else  { echo 'NIL';} ?></strong>.</h4>
				 </div>
				   <div class="form-group">
						<h4 class="box-title" style="text-align: left;">Approx. Count Required: <strong><?php if(mysqli_num_rows($getMementoDesc)>0) { echo ' '.$RespondedCount+$getMementoDescRow['additional_required'].' (Includes Additional #)';  } else  { echo 'NIL';}?></strong>.</h4>
				 </div> 
				<div class="form-group">
						<h4 class="box-title" style="text-align: left;">Memento Presented : <strong><?php if(mysqli_num_rows($getMementoDesc)>0) { echo ' '.mysqli_num_rows($getActualMR).' ';   ?> <a href="MarkAttendance.php?id=<?php echo $EventID; ?>">Mark Receivers  </a><?php  } else  { echo 'NIL';}?></strong>.</h4>
				 </div> 	
			</div> 
           
         
          
					</fieldset>
			</div>
			 </form>
                </div>
				
                </div>	
				
				<?php
				}
			?>			
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				 <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:aliceblue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Register for Event</strong></h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php

		   ?>
            <form id="NewEventForm" method="post">
        <div class="box-body" id="regForm">
          <div class="row">
	  <div class="col-md-12">
	  <input type="hidden" name="EventID" value="<?php echo $EventID ?>">
		</div>
		<?php
		if(mysqli_num_rows($isFamEligible)>0)
		{
		?>
		<div id="familyForm">
		<div class="col-md-12">
		<br>
		 <label>&nbsp;&nbsp;&nbsp;Please Choose your Family Members you wish to Accompany with. </label>
		 <br>
		</div>
		  <div class="col-md-12">
			  <table id='FamilyTable' style='padding: 0px;' class='table table-striped'>
							<tr>
								<th>Name</th>
								<th>Age</th>
								<th>Relationship</th>  
								<th>Select</th>  
							</tr>
						<?php
						if(mysqli_num_rows($getFamily)>0)
						{
						while($getFamilyRow = mysqli_fetch_assoc($getFamily))
						{
						?>
						<tr>
						<td><?php  echo $getFamilyRow['family_member_name']; ?></td>
						<td><?php  echo $getFamilyRow['age']; ?></td>
						<td><?php  echo $getFamilyRow['relationship_with_employee']; ?></td>
						<td><input type='checkbox' name='<?php echo $getFamilyRow['postVariableFam']; ?>' class="SelectFam" value='Yes' style='font-size:1em;'/></td>
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
            </div>
		</div>
		
		<?php
		}
		?>
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<?php
				if(mysqli_num_rows($isFamEligible)>0)
					{
				?>
				  <input type="submit"  id="Register" value="Register" class="btn btn-primary" />
				  
				  <?php
				  }
				  ?>
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
				
			






<div class="modal fade" id="ThanksModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:aliceblue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Event Registration</strong></h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php

		   ?>
            <form id="NewEventForm" method="post">
        <div class="box-body" id="regForm">
			<div class='box-body'>
                              <div class='col-sm-12'>
							  <div class='tab-content'>
							  
							  <h3 style='color:#286090'>Thank you for your Interest. We Appreciate your Input.</h3>
							  <br>
							  <h4 style='color:#286090'>Hope you would have a Great Time!</h4>
								<br>
							  </div>
								</div>
                              </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closebtn" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>






<button type="button" id="ThanksModalbtn" style="display:none;" data-toggle="modal" data-target="#ThanksModal" class="btn bg-olive margin">Register for Event</button>















			

		
	</div>
   
   
	   </section>
  
    <!-- Main content -->
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
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(function () {
    $('input[type=radio]').change(function(){
      alert ( $(this).val() );
      });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#NewEventForm").submit(function(e) {
		
		e.preventDefault();
		var x = CheckforBal();
		if(x==true)
				{
					SubmitRequest();
				}
				else
				{
					ajaxindicatorstop();
				}
		});
});
    </script>
	<script>
	function SubmitRequest()
	{
		
	ajaxindicatorstart("Registering for Event..");
	var data = $("#NewEventForm").serialize();
	
	$.ajax({
         data: data,
         type: "post",
         url: "RegisterEvent.php",
         success: function(data)
		 {
			 $("#familyForm").hide();
			 $("#Register").hide();
			 $("#regForm").html(data);
				ajaxindicatorstop();
         }
});
	}
	</script>
	<script>
function CheckforBal()
{
	var returnvalue = true;
	if(returnvalue == true)
	{
			return true;
	}
		else
	{
			return false;
	}
}
	</script>
	<script type="text/javascript">
   $(document).on('click','#closeRole',function(e) {
		 location.reload();	
});
    </script>
	<script type="text/javascript">
   $(document).on('click','#closebtn',function(e) {
		 location.reload();	
});
    </script>
	<script type="text/javascript">
   $(document).on('click','#directRegister',function(e) {
		ajaxindicatorstart("Registering for Event..");
		var data = $("#NewEventForm").serialize();
		$.ajax({
         data: data,
         type: "post",
         url: "RegisterEventDirect.php",
         success: function(data)
		 {
			 $("#ThanksModalbtn").click();
				ajaxindicatorstop();
         }
});
});
    </script>
  <script>
      var customLabel = {
        Entertainment: {
          label: ''
        },
        Shopping: {
          label: 'S'
        }
      };

        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(13.022236,80.249237),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;
 if (navigator.geolocation) {
     navigator.geolocation.getCurrentPosition(function (position) {
         initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
         map.setCenter(initialLocation);
     });
 }
          // Change this depending on the name of your PHP or XML file
          downloadUrl('MapsNew.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = "Venue for Acutal'19"
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1e8-5z1dH25SLXwdvYWhQWkxuxtJw6S4&callback=initMap">
    </script>
<script>
// Set the date we're counting down to
var countDownDate = new Date("2019-09-02 10:00:00").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = days + " Day(s) " + hours + " Hour(s) "
    + minutes + " Min(s) " + seconds + " Second(s) ";
    
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
<script>
(function() {

	'use strict';

  /**
   * Define global Util object if it doesn't exist
   */
  if ( 'object' !== typeof window.Timer ) {
    window.Timer = {};
  }

  /**
   * Namspace string
   */
  Timer.ns = "JavaScript Timer";

  Timer.getTimeRemaining = function( endtimeRaw ) {
// Some browsers need a "T" in there...
			var endtime = new Date( endtimeRaw.replace(/\s/, 'T') );
    var t = Date.parse( endtime ) - Date.parse( new Date() );
    var seconds = Math.floor( ( t / 1000 ) % 60 );
    var minutes = Math.floor( ( t / 1000 / 60 ) % 60 );
    var hours = Math.floor( ( t / ( 1000 * 60 * 60 ) ) % 24 );
    var days = Math.floor( t /( 1000 * 60 * 60 * 24 ) );

    // Build out the JSON object
    return {
      'total': t,
      'days': days,
      'hours': hours,
      'minutes': minutes,
      'seconds': seconds
    };

  };

  Timer.updateClock = function( endtime ) {

    var t = this.getTimeRemaining( endtime );
    var days = document.getElementById( 'timer-days' );
    var hours = document.getElementById( 'timer-hours' );
    var minutes = document.getElementById( 'timer-minutes' );
    var seconds = document.getElementById( 'timer-seconds' );

    days.querySelector('.timer__value').innerHTML = ( '0' + t.days ).slice( -2 );
    hours.querySelector('.timer__value').innerHTML = ( '0' + t.hours ).slice( -2 );
    minutes.querySelector('.timer__value').innerHTML = ( '0' + t.minutes ).slice( -2 );

    // Adds a leading 0 to maintain spacing
    seconds.querySelector('.timer__value').innerHTML = ( '0' + t.seconds ).slice( -2 );

    // If the timer is at zero
    if ( t.total <= 0 ) {

      // Stop the timer
      clearInterval( timeinterval );

      // Zero out the timer
      days.querySelector('.timer__value').innerHTML = 0;
      hours.querySelector('.timer__value').innerHTML = 0;
      minutes.querySelector('.timer__value').innerHTML = 0;
      seconds.querySelector('.timer__value').innerHTML = 0;

    } // if 0

  };

  Timer.start = function( obj ) {

		var timer = obj.timer;
		var endtime = obj.endtime;

		if ( timer ) {

			// Run the function once to avoid a delayed start
			this.updateClock( endtime );

			// Set up the time interval to tick the clock down
			var timeinterval = setInterval( function () {

				Timer.updateClock( endtime );

			}, 1000 );

		} // timer

  }

} )();

    (function() {

      Timer.start( {
        'timer' : document.getElementById( 'js-timer' ),
        'endtime' : document.getElementById( 'js-timer' ).getAttribute( 'data-endtime' )
      } );

    })();
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
      autoclose: true,
	   startDate: '+0d'
    })
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: '+0d'
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
<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>
