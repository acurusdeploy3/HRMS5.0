<?php
session_start();
$usergrp = $_SESSION['login_user_group'];
$userid=$_SESSION['login_user'];
$_SESSION['searchBack'] ='ConfirmServices';
?>
<?php
include("config2.php");
include("config6.php");
$getProbationEmp= mysqli_query($db,"select cos_master_id,c.Employee_ID,concat(First_Name,' ',MI,' ',Last_Name) as Employee_Name,date_format(c.Date_Joined,'%d %b %Y') as doj ,c.Date_Joined as dojt,c.Probation_Period,c.cos_process_queue_id,c.Department,e.business_unit,Date_Of_Completion_of_Probation as doct,date_format(date_add(Date_Of_Completion_of_Probation, interval 3 month),'%d %b %Y') as extdate,
date_format(c.Date_Of_Completion_of_Probation,'%d %b %Y') as Date_Of_Completion_of_Probation,c.Manager_ID,c.HOD_ID from cos_master c inner join employee_details e on c.employee_id=e.employee_id where substring_index(cos_pending_queue_id,'-',1) = '$userid' and e.is_active='Y'");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Employee Boarding</title>
  <!-- Tell the browser to be responsive to screen width -->
   <meta name="viewport" content="width=device-width, initial-scale=0.36, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
  background-color: #31607c;
}
	td {
    border: 1px solid white ! important;
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
.modal-backdrop {
    position: unset ! important;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed ! important; /* Stay in place */
   <!-- z-index: 1; /* Sit on top */ -->
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
.modal-dialog {
  height: 90%; /* = 90% of the .modal-backdrop block = %90 of the screen */
}
.modal-content {
  height: 100%; /* = 100% of the .modal-dialog block */
}
/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
	height:max-content;
}
.close123 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close123:hover,
.close123:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.close12 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close12:hover,
.close12:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
#col1{
	width: 50px;
	background-color: forestgreen !important;
}
#col2{
	width: 50px;
	background-color: #b93017 !important;
}
</style>
<style>
.content-wrapper
{
	max-height: 500px;
	overflow-y:scroll;
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
       Employee Boarding

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Boarding Home</li>
      </ol>
    </section>


    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
         <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Employee(s) in Probation</strong></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="JoinEmp" id="JoinEmp" align="center" class="table table-hover">
                <tr style="Color:White;">
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Date of Joining</th>
                  <th>Probation Period</th>
                  <th>Probation Completion Date</th>
				<?php $checkquery1 = mysqli_query($db,"SELECT * FROM `employee_details` where job_role like 'HR%' and employee_id='$userid'");
				if(mysqli_num_rows($checkquery1)>0){	?>
                  <th>Extended Upto</th>
				<?php }?>
                  <th>Attendance</th>
                  <th>Productivity</th>
                  <th align="right">Confirm Services</th>
                  <th align="right">Extend Probation</th>
                </tr>
			<?php
			if(mysqli_num_rows($getProbationEmp)>0)
			{
				while($getProbationEmpRow = mysqli_fetch_assoc($getProbationEmp))
				{
					?>
				<tr>
                  <td class="emplid"><?php echo $getProbationEmpRow['Employee_ID']; ?></td>
                  <td class="emplName"><?php echo $getProbationEmpRow['Employee_Name']; ?></td>
                  <td class="doj" ><?php echo $getProbationEmpRow['doj']; ?></td>
                  <td><?php echo $getProbationEmpRow['Probation_Period'].' Months'; ?></td>
                  <?php if($getProbationEmpRow['cos_process_queue_id']=='4' || $getProbationEmpRow['cos_process_queue_id']=='2' || $getProbationEmpRow['cos_process_queue_id']=='5'){ ?>
				  <td class="dateProb"><?php echo $getProbationEmpRow['Date_Of_Completion_of_Probation']; ?></td>
				  <?php  } else {
					  echo "<td></td>";
				  }
				  $employeeid = $getProbationEmpRow['Employee_ID'];
				  $datejoined = $getProbationEmpRow['dojt'];
				  $datecompleted = $getProbationEmpRow['doct'];
				  $checkquery2 = mysqli_query($db,"SELECT * FROM `employee_details` where job_role like 'HR%' and employee_id='$userid'");
				  if(mysqli_num_rows($checkquery2)>0){
				  if($getProbationEmpRow['cos_process_queue_id']=='8' || $getProbationEmpRow['cos_process_queue_id']=='9'){
				  ?>
				  <td class="dateExt"><?php echo $getProbationEmpRow['extdate']; ?></td>
				  <?php }
				  else {
					  echo "<td></td>";
				  } }?>
				  <input type ='hidden' id='Emp' value='<?php echo $employeeid; ?>'/>
				  <input type ='hidden' id='DOJ' value='<?php echo $datejoined; ?>'/>
				  <input type ='hidden' id='DOC' value='<?php echo $datecompleted; ?>'/>
                  <td>
				  <button class='btn' id='empldata' style='background-color:transparent' onclick="ShowAttendance('<?php echo $employeeid; ?>','<?php echo $datejoined; ?>','<?php  echo $datecompleted; ?>')"><i class="fa fa-paperclip" aria-hidden="true"></i></button></td> 
				<?php if($getProbationEmpRow['Department']=='RCM') {?>
				  <td>
				  <button class='btn' id='emplproddata' style='background-color:transparent' onclick="ShowProd('<?php echo $employeeid; ?>','<?php echo $datejoined; ?>','<?php  echo $datecompleted; ?>')"><i class="fa fa-database" aria-hidden="true"></i></button></td> 
				<?php } 
				else {
					echo "<td></td>";
				}
				  $cos_master_id = $getProbationEmpRow['cos_master_id']; 
				  $Manager_ID = $getProbationEmpRow['Manager_ID'];
				  $HOD_ID = $getProbationEmpRow['HOD_ID'];
				  $cos_process_queue_id = $getProbationEmpRow['cos_process_queue_id'];
				  $Location= $getProbationEmpRow['business_unit'];
				  $Department = $getProbationEmpRow['Department'];
				  $hoddet = mysqli_query ($db,"select * from all_hods where Department ='$Department' and lovation='$Location'");
				  $hoddetrow = mysqli_fetch_assoc($hoddet);
				  $who_hod=$hoddetrow['employee_id'] ;
				  
				  $hrmanager = mysqli_query($db,"SELECT value FROM `application_configuration` where config_type='COS_HANDLING' and parameter ='HR_ID'");
				  $hrmanagerRow = mysqli_fetch_assoc($hrmanager);
				  $who_hrm = $hrmanagerRow['value'];
				  
				  $ishod = mysqli_query ($db,"select * from all_hods where employee_id='$Manager_ID' and Department ='$Department' and lovation='$Location'");
				  $is_hod =$getProbationEmpRow['Employee_ID'];
				  
				  
				  echo "<input type ='hidden' id='mngr' value='$Manager_ID'/>";
				  echo "<input type ='hidden' id='loguser' value='$userid'/>";
				  echo "<input type ='hidden' id='hod_id' value='$who_hod'/>";
				  echo "<input type ='hidden' id='empid' value='$is_hod'/>";
                  echo "<input type ='hidden' id='whohrmm' value='$who_hrm'/>";
				  echo "<input type ='hidden' id='someid' value='$cos_process_queue_id'/>";
				  if($userid == $Manager_ID && $userid == $HOD_ID && $userid == $who_hrm)
				  { if($cos_process_queue_id ==2 || $cos_process_queue_id==5){?>
						
						<td style="width:10%"><a href="EmpConfirmationofservicesCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=N" id="confirm" class="confirm" title="View"><img alt='User' src='Images/confirm_34118.png' title="Conifrm Services" width='18px' height='18px' /></a></td>
						<td><a href="EmpPIPCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=N"  id="extend" title="View"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a>
				  <?php }else if($cos_process_queue_id ==7 || $cos_process_queue_id==9) {?>
						<td></td>
						<td><a href="EmpPIPCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=N" id="extend" title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>
					  
				  <?php } }
				  else if($userid == $Manager_ID && $userid ==$HOD_ID)
				  { if($cos_process_queue_id ==2 ){?>
						<td style="width:10%"><a href="EmpConfirmationofservicesApproval.php?idval=<?php echo $cos_master_id; ?>&scnval=N" id="confirm" class="confirm" title="View"><img alt='User' src='Images/confirm_34118.png' title="Conifrm Services" width='18px' height='18px' /></a></td>
						<td><a href="EmpPIPApproval.php?idval=<?php echo $cos_master_id; ?>&scnval=N"  title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>
				  <?php }}
				 else if($userid == $HOD_ID && $userid == $who_hrm)
				 {?>					
						<?php if($cos_process_queue_id==7) {?>
						<td></td>
						<td>
						<a href="EmpPIPCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=N" title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>
						<?php } else {?>
						<td style="width:10%"><a href="EmpConfirmationofservicesCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=N" id="confirm" class="confirm"  title="View"><img alt='User' src='Images/confirm_34118.png' title="Conifrm Services" width='18px' height='18px' /></a></td>
						<td>
						<a href="EmpPIPCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=N" title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>	
				 <?php }}
				else if ($userid == $Manager_ID && $cos_process_queue_id !=4)
				{ if($cos_process_queue_id == 14) { ?>
						<td></td>
						<td>
						<a href="EmpPIP.php?idval=<?php echo $cos_master_id; ?>"  title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>
				<?php } else { ?>
						<td style="width:10%"><a href="EmpConfirmationofservices.php?idval=<?php echo $cos_master_id; ?>" id="confirm" class="confirm"  title="View"><img alt='User' src='Images/confirm_34118.png' title="Conifrm Services" width='18px' height='18px' /></a></td>
						<td>
						<a href="EmpPIP.php?idval=<?php echo $cos_master_id; ?>"  title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>
				<?php } }
				else if($userid == $HOD_ID){?>
						
						<?php if($cos_process_queue_id==7) {?>
						<td></td>
						<td>
						<a href="EmpPIPApproval.php?idval=<?php echo $cos_master_id; ?>&scnval=Y"  title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>
						<?php } else{ ?>
						<td style="width:10%"><a href="EmpConfirmationofservicesApproval.php?idval=<?php echo $cos_master_id; ?>&scnval=Y" id="confirm" class="confirm"  title="View"><img alt='User' src='Images/confirm_34118.png' title="Conifrm Services" width='18px' height='18px' /></a></td>
				 <?php } }
				else if($userid == $who_hrm){?>
					
					<?php if($cos_process_queue_id==8 || $cos_process_queue_id ==9) {?>
					<td></td>
					<td>
						<a href="EmpPIPCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=Y"  title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a></td>
					<?php } else {?>
					<td style="width:10%"><a href="EmpConfirmationofservicesCompletion.php?idval=<?php echo $cos_master_id; ?>&scnval=Y" id="confirm" class="confirm"  title="View"><img alt='User' src='Images/confirm_34118.png' title="Conifrm Services" width='18px' height='18px' /></a></td>
					<td>
					<!--<a href="EmpPIPCompletion.php?idval=&scnval=Y"  title="View" id="extend"><img alt='User' src='Images/13465868-0-ExtendTime.png' title="Extend Probation" width='18px' height='18px' /></a> --></td>
				 <?php } }?>
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
			  <div id="datamodel" class="modal">
			 	 <div class="modal-content">
						<span class="close123" data-dismiss="modal">&times;</span>
						<p style="font-size: 22px;color: #eb6027;">&nbsp;</p>
						<div id="display">
						</div>
			     </div>
			  </div>
				<div id="datamodel12" class="modal" style='width:120%'>
			 		 <div class="modal-content">
						<span class="close12" data-dismiss="modal">&times;</span>
							<p style="font-size: 22px;color: #eb6027;">&nbsp;</p>
							<div id="displayprod1" style="height:90%">
							</div>
                        </div>
				</div>

            
            
            
            	<div class="modal fade" id="datamodel1">
         			 <div class="modal-dialog modal-lg">
            			<div class="modal-content" style="width:120%;overflow-y:scroll;height:750px;overflow-x:scroll;">
             				 <div class="modal-header" style="background-color:lightblue">
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  					<span aria-hidden="true">&times;</span></button>
               				 	<h4 class="modal-title">Productivity Report</h4>
                             </div>
              			<div id="displayprod" class="modal-body">
                  
		   				</div>
             		 </div>
			   		 <div class="modal-footer">
              		<!--  <button type="button" id="closeBal" class="btn btn-default pull-right" data-dismiss="modal">Close</button> -->
             		 </div>
            		</div>
            <!-- /.modal-content -->
          		</div>  
            
            
            
            
            
            
            
            
            <!-- /.box-body -->
          </div>
		  <br>
		  <br>
		  <?php 
		  $checkquery = mysqli_query($db,"SELECT * FROM `employee_details` where job_role like 'HR%' and employee_id='$userid'");
			if(mysqli_num_rows($checkquery)>0){	
		  ?>
		 <!-- <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Employess - Services Confirmed</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			<div class="box-body table-responsive no-padding">
              <table name="ViewEmp" id="ViewEmp" align="center" class="table table-hover">
			  <tr style="Color:White;">
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Date of Joining</th>
                  <th>Date of Confirmation</th>
                  <th>Department</th>
				  <th>HOD</th>
                </tr>-->
				<?php 
				$query1 = mysqli_query($db,"SELECT c.Employee_ID,concat(e.First_Name,' ',e.MI,' ',e.Last_Name) as Name,c.department,date_format(c.Date_Joined,'%d %b %Y') as doj, 
				date_format(c.modified_date_and_time,'%d %b %Y') as Date_of_Confirmation,concat(e1.First_Name,' ',e1.MI,' ',e1.Last_Name) as Manager_Name FROM `cos_master` c inner join employee_details e on c.employee_id=e.employee_id inner join employee_details e1 on c.hod_id=e1.employee_id where cos_process_queue_id=6 and e.is_active='Y'");
				?>
			  <!--</table>
			  </div> -->
        	  <br>
			  <br>
			   <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Employee(s) - Services Extended</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			<div class="box-body table-responsive no-padding">
              <table name="ViewEmp" id="ViewEmp" align="center" class="table table-hover">
			  <tr style="Color:White;">
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Date of Joining</th>
                  <th>Extended Upto</th>
                  <th>Department</th>
				  <th>HOD</th>
                </tr>
				<?php 
				$query1 = mysqli_query($db,"SELECT c.Employee_ID,concat(e.First_Name,' ',e.MI,' ',e.Last_Name) as Name,c.department,date_format(c.Date_Joined,'%d %b %Y') as doj ,date_format(date_add(date(now()), interval 3 month),'%d %b %Y') as Date_of_Confirmation,concat(e1.First_Name,' ',e1.MI,' ',e1.Last_Name) as Manager_Name FROM `cos_master` c inner join employee_details e on c.employee_id=e.employee_id inner join employee_details e1 on c.hod_id=e1.employee_id where cos_process_queue_id=10 and e.is_active='Y'");
				while($query1Row = mysqli_fetch_assoc($query1))
				{
					echo "<tr><td>".$query1Row['Employee_ID']."</td>";
					echo "<td>".$query1Row['Name']."</td>";
					echo "<td >".$query1Row['doj']."</td>";
					echo "<td >".$query1Row['Date_of_Confirmation']."</td>";
					echo "<td >".$query1Row['department']."</td>";
					echo "<td >".$query1Row['Manager_Name']."</td></tr>";
				}
				?>
			  </table>
			  </div>
			  <br><br>
			  <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Employee(s) List</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			  <div class="box-body table-responsive no-padding">
              <table align="center" id="example1" class="table table-hover">
			  <thead style="Color:White;">
                  <th>Employee</th>
                  <th>Date of Joining</th>
                  <th>Probation Completion Date</th>
              	  <th>Attendance</th>
                  <th>Productivity</th>
                  <th>Status</th>
                  <th>Department</th>
				  <th>Manager</th>
                  <th>Confirmation / Extension Letter</th>
              	<th>Letter Acknowledged by Employee</th>
                </thead>
				<?php 
				$query1 = mysqli_query($db,"SELECT c.Employee_ID,concat(e.First_Name,' ',e.MI,' ',e.Last_Name) as Name,c.department,date_format(c.Date_Joined,'%d %b %Y') as doj,ct.description ,concat(e1.First_Name,' ',e1.MI,' ',e1.Last_Name) as Manager_Name, 
                c.Date_Joined as dojt,Date_Of_Completion_of_Probation as doct,date_format(Date_Of_Completion_of_Probation,'%d %b %Y') as docp,c.is_active,c.cos_process_queue_id FROM `cos_master` c 
				inner join cos_process_queue_table ct on c.cos_process_queue_id=ct.cos_process_queue_id
				inner join employee_details e on c.employee_id=e.employee_id 
				inner join employee_details e1 on c.Manager_ID=e1.employee_id
                where e.is_active='Y'
				order by date(c.created_date_and_time) desc ");
				while($query1Row = mysqli_fetch_assoc($query1))
				{
					echo "<tr><td>".$query1Row['Employee_ID']." - ".$query1Row['Name']."</td>";
                	$empidforprod=$query1Row['Employee_ID'];
               		 $datejoined = $query1Row['dojt'];
				  $datecompleted = $query1Row['doct'];
					
					echo "<td >".$query1Row['doj']."</td>";
               		echo "<td>".$query1Row['docp']."</td>";
              		?>
                 <td><button class='btn' id='empldata' style='background-color:transparent' onclick="ShowAttendance('<?php echo $empidforprod; ?>','<?php echo $datejoined; ?>','<?php  echo $datecompleted; ?>')"><i class="fa fa-paperclip" aria-hidden="true"></i></button></td> 
				<?php 
                if($query1Row['department']=='RCM') {?>
				  <td>
				  <button class='btn' id='emplproddata' style='background-color:transparent' onclick="ShowProd('<?php echo $empidforprod; ?>','<?php echo $datejoined; ?>','<?php  echo $datecompleted; ?>')"><i class="fa fa-database" aria-hidden="true"></i></button></td> 
				<?php } 
				else {
					echo "<td></td>";
				}
					echo "<td >".$query1Row['description']."</td>";
					echo "<td >".$query1Row['department']."</td>";
					echo "<td >".$query1Row['Manager_Name']."</td>";
                    if($query1Row['is_active']=='N')
                    {
                    	if($query1Row['cos_process_queue_id']=='6')
                        {
                     		echo '<td><a id="CnfrmLetter" title="Download" href="GeneratePDF/DownloadCL.php?id='.$query1Row['Employee_ID'].'"><i class="fa fa-download" aria-hidden="true" title="Download"></i></a></td>';
                        }
                    	else
                        {
                        	echo '<td><a id="ExtLetter" title="Download" href="GeneratePDF/DownloadEL.php?id='.$query1Row['Employee_ID'].'"><i class="fa fa-download" aria-hidden="true" title="Download"></i></a></td>';
                        }
                    }
                else
                {
                    echo '<td>NA</td>';
                }
                if($query1Row['is_active']=='N')
                    {
                $getAcknowledgedLetter = mysqli_query($db,"SELECT * FROM `kye_details`  where employee_id=".$query1Row['Employee_ID']." and document_type in ('Confirmation Letter','Extension Letter') and is_active='Y';");
                    if(mysqli_num_rows($getAcknowledgedLetter)>0)
                    {
                    	echo "<td >Yes ( <a href='../../searchDocumentsInfo.php?empId=".$query1Row['Employee_ID']."'>Download</a> )</td></tr>";
                    }
                	else
                    {
                    	echo "<td >No ( <a href='../../searchAddKYE.php?empId=".$query1Row['Employee_ID']."'>Upload</a> )</td></tr>";
                    }
                }
                else
                {
                	echo '<td>NA</td>';
                }
				}
				?>
			  </table>
			  </div>
			<?php }
			else {?>
			<br><br>
			  <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Employee(s) List</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			  <div class="box-body table-responsive no-padding">
              <table align="center" id="example1" class="table table-hover">
			  <thead style="Color:White;">
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Date of Joining</th>
                  <th>Status</th>
                  <th>Department</th>
				  <th>Manager</th>
                </thead>
				<?php 
				$query1 = mysqli_query($db,"SELECT c.Employee_ID,concat(e.First_Name,' ',e.MI,' ',e.Last_Name) as Name,c.department,date_format(c.Date_Joined,'%d %b %Y') as doj,ct.description ,concat(e1.First_Name,' ',e1.MI,' ',e1.Last_Name) as Manager_Name FROM `cos_master` c 
				inner join cos_process_queue_table ct on c.cos_process_queue_id=ct.cos_process_queue_id
				inner join employee_details e on c.employee_id=e.employee_id 
				inner join employee_details e1 on c.Manager_ID=e1.employee_id
				and c.manager_id in ('$userid') and e.is_active='Y'
				order by date(c.created_date_and_time) desc");
				while($query1Row = mysqli_fetch_assoc($query1))
				{
					echo "<tr><td>".$query1Row['Employee_ID']."</td>";
					echo "<td>".$query1Row['Name']."</td>";
					echo "<td >".$query1Row['doj']."</td>";
					echo "<td >".$query1Row['description']."</td>";
					echo "<td >".$query1Row['department']."</td>";
					echo "<td >".$query1Row['Manager_Name']."</td></tr>";
				}
				?>
			  </table>
			  </div>
			<?php  } ?>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>
	  <br>
	  <br>
      <!-- /.row -->

      <!-- Table row -->
	  <br>
	  <br>
      <div class="row">
        <div class="col-xs-12 table-responsive">


	  <br>
	  <br>

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
<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
function ShowAttendance(val1,val2,val3){
	 $('#datamodel').modal('show');
		var employeeid = val1;
		var doj = val2;
		var doc = val3;
            $.ajax({
                type: "POST",
                url: "attendanceval.php",
                data: {
					employeeid:employeeid,
					doj:doj,
					doc:doc
				},	
                success: function(data){
                    $('#display').html(data);
                }
            });
}
</script>
<script>
function ShowProd(val1,val2,val3){
	$('#displayprod').empty();
	ajaxindicatorstart("Processing..Please Wait..");
	 $('#datamodel1').modal('show');
		var employeeid = val1;
		var doj = val2;
		var doc = val3;
            $.ajax({
                type: "POST",
                url: "ProductionSummary.php",
                data: {
					employeeid:employeeid,
					doj:doj,
					doc:doc
				},	
                success: function(data){
                    $('#displayprod').html(data);
                }
            });
}
</script>
<!-- Page script -->
	<script>
// Get the modal
var modal1 = document.getElementById('datamodel');

// Get the button that opens the modal
var btn1 = document.getElementById("empldata");

// Get the <span> element that closes the modal
var span1 = document.getElementsByClassName("close123")[0];

// When the user clicks the button, open the modal 
// btn1.onclick = function() {
    // modal1.style.display = "block";
	// $('.modal-backdrop').remove();
// }

// When the user clicks on <span> (x), close the modal
span1.onclick = function() {
    modal1.style.display = "none";
	//window.location.reload();
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal1) {
        modal1.style.display = "none";
    }
}
</script>
	<script>
// Get the modal
var modal = document.getElementById('datamodel1');

// Get the button that opens the modal
var btn = document.getElementById("emplproddata");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close12")[0];

// When the user clicks the button, open the modal 
// btn1.onclick = function() {
    // modal1.style.display = "block";
	// $('.modal-backdrop').remove();
// }

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
	window.location.reload();
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<script>

	$(function() {
  var bid, trid;
  $('#JoinEmp tr').click(function() {
       Id = $(this).find('.emplid').text();
	   doj = $(this).find('.doj').text();
	   dateprob = $(this).find('.dateProb').text();
		$('#EmpLID').val(Id);
		$('#doj').val(doj);
		$('#dateprob').val(dateprob);
  });
});

$('.confirm').click(function(){
	ajaxindicatorstart("Processing..Please Wait..");
});

$('#extend').click(function(){
	ajaxindicatorstart("Processing..Please Wait..");
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

<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>

