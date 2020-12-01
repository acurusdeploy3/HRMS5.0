<?php   
session_start();  
$userid=$_SESSION['login_user'];
if(!isset($_SESSION["login_user"]))
{  
    header("location:Mainlogin.php");  
} 
?> 
<?php
/* session_start();
include("functions.php");
if(isset($_SESSION["username"])) {
	if(isLoginSessionExpired()) {
		header("Location:logout1.php?session_expired=1");
	}
} */
?>
<?php

require_once("config.php");
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");

$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");

$rptdropdown = mysqli_query($db,"SELECT Report_ID,Report_Name,Allocated_Employee,Allocated_Employee as Name  FROM `report_master` r inner join employee_details d on allocated_employee=employee_id and r.is_active='Y' and report_classification='ALL_HR'");

$listquery=mysqli_query($db,"SELECT concat(First_name,' ',Last_Name) as hrname,employee_id, (select count(allocation_id) from report_allocation_table where allocated_employee_id=employee_id and is_active='Y') as rptcnt
FROM `employee_details` e left join report_allocation_table r on e.employee_id=allocated_employee_id
where job_role like 'HR' and e.is_active='Y' group by employee_id");

if(isset($_POST['Submit'])){
}

$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Reports</title>
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
<style>
.table table-striped:hover {
  background-color: #ffff99;
}
th {
  background-color: #fbe2d8;
}
	.error {color: #FF0000;}

.fa-fw {
    padding-top: 13px;
}
#savefields{
	background-color: #4CAF50;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
#goprevious{
	background-color: #286090;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
th {
  background-color: #31607c;
  color:white;
}
.modal-backdrop {
    position: unset ! important;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed ! important; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
}
/* The Close Button 1  */

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
#rptid
{
	font-weight: 500;
    margin-left: 20px;
	font-size:16px;
	color: tomato;
}
#rptlstid
{
	font-weight: 500;
    margin-left: 20px;
	font-size:16px;
	color: dodgerblue;
}
#faicon
{
	color: green;;
    font-size: 20px;
}
}
#myOverlay{position:absolute;height:100%;width:100%;z-index: 1800;}
#myOverlay{background:black;opacity:.7;z-index:2;display:none;}

#loadingGIF{position:absolute;top:50%;left:50%;z-index:3;display:none;}
</style>
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="myOverlay"></div>
<div id="loadingGIF"><img src="images/ajax-loader.gif" style="height:100px;width:100px;"/></div>
<div class="wrapper">

  <?php
 require_once('Layouts/main-header.php');
 ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php
 require_once('Layouts/main-sidebar.php');
 ?>
 
 <div class="content-wrapper">
   <section class="content-header">
      <h1>Report Allocation

		<input action="action" class="btn btn-info pull-right" onclick="window.location='../../DashboardFinal.php';" type="button" value="Back" id="goprevious"/>       </h1>
    </section>
	<section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
			
              <div class="box-body">
				 <table id="allocationchange" class="table" style="font-size:14px;">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>
				  <th>Employee ID</th>
				  <th>Employee Name</th>
			      <th>Count</th>
				  <th>Modify</th>
                </tr>			  
                <?php
                if(mysqli_num_rows($listquery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($listquery)){
                    echo "<tr><td style='width:5%'>".$i.".</td>";
					echo "<td class='EmpId' style='width:15%'>".$row['employee_id']."</td>";
					$emplid=$row['employee_id'];
					echo "<td style='width:35%'>".$row['hrname']."</td>";
					if($row['rptcnt'] == 0)
					{ echo "<td style='width:15%'>".$row['rptcnt']."</td>";}
				else{
				echo "<td style='width:15%'><a href='#' id='myBtn1'  data-toggle='modal' data-target='#myModal_".$emplid."'>".$row['rptcnt']."</a></td>";}
				echo "<td style='width:15%'><a href='ReportsAllocationScreenHR.php?employee_id=".$row['employee_id']."'><i class='fa fa-pie-chart' id='faicon'></a></i></td>";
				//	echo "<td style='width:15%'><a href='#' id='myBtn1'  data-toggle='modal' data-target='#myselModal_".$emplid."'><i class='fa fa-pie-chart' id='faicon'></a></i></td>";
					?>
				<div id="myModal_<?php echo $emplid; ?>" class="modal">
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close12" data-dismiss="modal">&times;</span>
						<p></p>
						<form id="statuschange" method="POST" action="">
						<input type="hidden" value="" name="EmpIdvalue" id="EmpIdvalue"/>
						
						<label for="inputStatusChange" class="col-sm-5 control-label">List of Reports </label><br><br>
						<?php
						$reportemplst=mysqli_query($db,"SELECT replace(Report_Name,'_',' ') as Report_Name FROM `report_allocation_table` a  inner join report_master m on a.report_master_id=m.report_master_id where allocated_employee_id=$emplid and a.is_active='Y' and m.is_active='Y' and report_classification='ALL_HR'");	
						$j=1;
						$lstcount=mysqli_num_rows($reportemplst);
						while($detrowval=mysqli_fetch_assoc($reportemplst)){
						echo "<i class='fa fa-pie-chart' aria-hidden='true'></i><label id='rptid'>".$detrowval['Report_Name']."</label> <br>";
						$j++;}?>
					<br>					
						</form>
							</div>
			</div>
			<div id="myselModal_<?php echo $emplid; ?>" class="modal">
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close12" data-dismiss="modal">&times;</span>
						<p></p>
						<form id="statuschange" method="POST" action="">
						<input type="hidden" value="" name="EmpIdvalue" id="EmpIdvalue"/>
						
						<label for="inputStatusChange" class="col-sm-5 control-label">List of Reports </label><br><br>
						<?php
						$reportemplst1=mysqli_query($db,"select * from (select Report_ID, Employee_id,Report_Name,report_master_id,if((select is_active from report_allocation_table where report_master_id=r.report_master_id and d.Employee_id=allocated_Employee_id) is null,'N','Y') as checkrpt from employee_details d,report_master r where job_role like 'HR' and report_classification='ALL_HR' and d.is_active='Y') as a where employee_id=$emplid");	
						$k=1;
						$lstcount1=mysqli_num_rows($reportemplst1);
						while($detrowval=mysqli_fetch_assoc($reportemplst1)){
							if($detrowval['checkrpt'] == 'Y'){
							echo "<input type='checkbox' name ='chk_[]' value='Yes' checked></input>";}
							else{
								echo "<input type='checkbox' value='No'></input>";
							}
							echo "<label id='rptlstid'>".$detrowval['Report_Name']."</label> <br>";
							$k++;}?>
					<br>		
						<input type ='button' value='Save' id='savefields'></input>
						</form>
							</div>
			</div>
			<?php
								echo "</tr>" ;
									  $i++;
                  }
				}
                ?>
              </tbody>
            </table>
				</div>		
			 <div class="box-footer">   
              </div>
              <!-- /.box-footer -->			  		    

          </div>
          <!-- /.box -->
		   </div>
       </div>
      <!-- /.row -->
        </section>		  

    </div>
      <!-- /.row -->
        
 
   
  
  <!-- Content Wrapper. Contains page content -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong>
  </footer>

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<!-- Page script -->


	<script>
	function clearfields()
	{
		document.getElementById("reason_for_resignation").value="";
	}
	</script>
	<script>

	$(function() {
  var bid, trid;
  $('#allocationchange tr').click(function() {
       Id = $(this).find('.EmpId').text();
	  // ReID = $(this).find('.RepId').text();
		$('#EmpIdvalue').val(Id);
	//	$('#RepIdvalue').val(ReID);	
  });
});
	</script>
	<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close12")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
	$('.modal-backdrop').remove();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>
