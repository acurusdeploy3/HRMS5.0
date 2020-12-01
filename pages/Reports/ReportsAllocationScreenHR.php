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
//$date = date("Y-m-d");
if(isset($_GET['employee_id']) && $_GET['employee_id'] != ''){
	$employee_id = $_GET['employee_id'];}

$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");

$rptdropdown = mysqli_query($db,"SELECT * FROM `report_allocation_table` r
inner join report_master  m on r.Report_Master_ID=m.Report_Master_ID
where r.Allocated_employee_id=$employee_id and r.is_active='Y' and m.is_active='Y'");

$listquery=mysqli_query($db,"SELECT concat(First_name,' ',Last_Name) as hrname,employee_id, count(allocation_id) as rptcnt FROM `employee_details` e left join report_allocation_table r on e.employee_id=allocated_employee_id
where job_role like 'HR' and e.is_active='Y' group by employee_id");

$rptlst=mysqli_query($db,"select * from report_master where report_master_id not in (select report_master_id from report_allocation_table where allocated_employee_id=$employee_id and is_active='Y') and is_active='Y'");

if(isset($_POST['Submit'])){
	$date = date("Y-m-d h:i:s");
$query1="INSERT INTO report_allocation_table(Report_Master_ID, Allocated_Employee_ID, Is_Active, created_date_and_time, created_by) 
	VALUES
	((select report_master_id from report_master where report_name='".$_POST['rptname']."'),
	'".$employee_id."',
	'Y',
	'".$date."',
	'".$userid."'
	)";
$result = mysqli_query($db,$query1);
 if(!$result){
			$message="Problem in Adding to database. Please Retry";	
	}
	else{
		header("Location:ReportsAllocationScreenHR.php?employee_id=$employee_id");
	}
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
<script src="dist/js/loader.js"></script>
   <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <script src="dist/js/loader.js"></script>

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
      <h1>Allocation and De-Allocation
		<input action="action" class="btn btn-info pull-right" onclick="window.location='ReportsAllocationScreen.php';" type="button" value="Back" id="goprevious"/>       </h1>
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
			 <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" autocomplete="off" >
              <div class="box-body">
				<div class="form-group">
                  <label for="inputReport" id="inputReport" class="col-sm-2 control-label">Report Name<span class="error">*  </span></label>
                <div class="col-md-3">
                     <select class="form-control" id="rptname" name="rptname" required >
                      <option value="">Select Report Name</option>
                    <?php
					
                    while($row = mysqli_fetch_assoc($rptlst)){
                      echo "<option value='".$row['Report_Name']."'>".$row['Report_Name']."</option>";
                    }
                    ?>        
                                         
                    </select>
                  </div>
				  <div class = "col-sm-3">
				  	<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields">
				  </div>
				  </div>
				</div>	
</form>				
			 <div class="box-footer">   
              </div>
              <!-- /.box-footer -->			  		    
		<div class="border-class">
            <table class="table" id="lstreports">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Report ID</th>
					<th>Report Name</th>
					<th>Actions</th>
					
                </tr>
                <?php
                if(mysqli_num_rows($rptdropdown) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row1 = mysqli_fetch_assoc($rptdropdown)){
                    echo "<tr><td>".$i.".</td>";
                    echo "<td>".$row1['Report_ID']."</td>";
                    echo "<td>".$row1['Report_Name']."</td>";
					echo "<td class='AllocId'  style='display:none;'>".$row1['Allocation_ID']."</td>";
                 echo  "<td><a id='deletkeyid_".$row1['Allocation_ID']."' href='deleterep.php?Allocation_ID=".$row1['Allocation_ID']."&empld_id=$employee_id'><i class='fa fa-trash'></i></a></td>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
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
	$("#savefields").click(function() {
			if(document.getElementById('rptname').value == ''){
			}else{ajaxindicatorstart("Processing..Please Wait..");}
});
	</script>
<script>
$(function() {
  var bid, trid;
  $('#lstreports tr').click(function() {
       Id = $(this).find('.AllocId').text();
	   $('#deletkeyid_' + Id).click(function() {
			ajaxindicatorstart("Processing..Please Wait..");
});
  });
});
</script>
</body>
</html>
