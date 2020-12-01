<?php   
session_start();  
$userid=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$res_id = $_GET['res_id'];
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
$date = date("Y-m-d h:i:s");
if(isset($_GET['res_id']) && $_GET['res_id'] != ''){
	$res_id = $_GET['res_id'];}
$result99=mysqli_query($db,"SELECT * FROM `employee_resignation_information` where res_id_value = '".$res_id."'");
$detRow99 = mysqli_fetch_array($result99);
$resignation_id=$detRow99['resignation_id'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

$getresigneduser = mysqli_query($db,"select ed.employee_id,concat(ed.First_name,' ',ed.MI,' ',ed.Last_Name) as Name,ed.Job_Role,ed.Employee_image,ed.employee_designation,ed.department,concat(ed1.First_name,' ',ed1.MI,' ',ed1.Last_Name) as ManagerName,
ei.reporting_manager_id,date_format(date_of_leaving,'%d %b %Y') as date_of_leaving
from employee_details ed 
inner join  employee_resignation_information ei on ed.employee_id=ei.employee_id 
left join employee_details ed1 on ei.reporting_manager_id=ed1.employee_id
where resignation_id=$resignation_id");
$getresigneduserrow = mysqli_fetch_assoc($getresigneduser);
$usernamevalue = $getresigneduserrow['Name'];
$usermngrnamevalue = $getresigneduserrow['ManagerName'];
$userRolevalue = $getresigneduserrow['Job_Role'];
$userImagevalue = $getresigneduserrow['Employee_image'];
$userempvalue = $getresigneduserrow['employee_id'];
$usermngrempvalue = $getresigneduserrow['reporting_manager_id'];
$profPicPath = '../../uploads/'.$userImagevalue;
$userDesgvalue =  $getresigneduserrow['employee_designation'];
$userDeptvalue =  $getresigneduserrow['department'];
$userDtvalue =  $getresigneduserrow['date_of_leaving'];

if(isset($_POST['Submit'])){
 $query1 = mysqli_query($db,"select * from nodueformentries where department='System Administration' and resignation_id=$resignation_id");
 while($row11 = mysqli_fetch_assoc($query1))
 {
	 $comm=$row11['details_id'].'Comments';
	 $PostVal = $row11['details_id'];
	 $update1="update nodueformentries set comments='".mysqli_real_escape_string($db,$_POST[$comm])."',status = 
	'".$_POST[$PostVal]."' where details_id = '$PostVal' and resignation_id=$resignation_id";
	$updatequery = mysqli_query($db,$update1);
	$datafill1="update nodueformentries set is_data_updated = 'Y',modified_by='$userid',modified_date_and_time='$date' where resignation_id=$resignation_id and department = 'System Administration'";
	$datafill=mysqli_query($db,$datafill1);
 }
 $update3=mysqli_query($db,"update employee_resignation_information set no_due_sysadmin_status='C',modified_by=$userid where
 resignation_id=$resignation_id");
 if($usergrp =='System Admin Manager' || $usergrp =='System Admin')
 {header("Location: nodueadminform.php");}
else{header("Location: resignationapprovalform.php");}
	}
$sysadminQuery = mysqli_query($db,"select * from nodueformentries where department='System Administration' and resignation_id=$resignation_id");
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Resignation Management</title>
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
    width: 50%;
}
/* The Close Button 1  */

.close1 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close1:hover,
.close1:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
#myOverlay{position:absolute;height:100%;width:100%;}
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
      <h1>
       ACURUS EMPLOYEE FORM
      <small> Resignation </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
      </ol>
    </section>
	<section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info" style="width:110%;">
            <div class="box-header with-border">
              <h3 class="box-title">RESIGNATION INFORMATION</h3>
			  <small> If any </small>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
          
              <div class="box-body">
				</div>
			
			 <div class="box-footer">
			 <?php if($usergrp=='System Admin Manager' || $usergrp=='System Admin') {?>
			  <input action="action" class="btn btn-info pull-left" onclick="window.location='nodueadminform.php';" type="button" value="Back" id="goprevious"/>
			 <?php } else { ?>
			 <input action="action" class="btn btn-info pull-left" onclick="window.location='resignationapprovalform.php';" type="button" value="Back" id="goprevious"/>
			 <?php } ?>                
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
              </div>
          
           <div class="border-class">
			<br>
          <div class="box box-widget widget-user-2">
            
          
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-olive-active">
              <h3 class="widget-user-username" style="font-weight:400;padding-left:1%"><?php echo $userempvalue ." - ".$usernamevalue; ?></h3>
              <h5 class="widget-user-desc" style="padding-left:1%;font-size: 20px ! important"><?php echo $userDesgvalue ." - ".$userDeptvalue ; ?></h5>
            </div>
            <div class="widget-user-image" style="top: 60px ! important;">
              <img class="img-circle" style="height:110px;width:110px" src="<?php echo $profPicPath; ?>" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php echo $usermngrempvalue ." - ".$usermngrnamevalue; ?></h5>
                    <span class="description-text">Manager</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header"><?php echo $userDtvalue; ?></h5>
                    <span class="description-text">Date of leaving</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">
           <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" >
              <div class="box-body">
				<label style="font-size: 20px;"> SYSTEM ADMINISTRATION </label>
				 <?php 
			  if(mysqli_num_rows($sysadminQuery) < 1){
                  echo "No Results Found";
                }else{
                  $i = 1;
				  echo "<div class='form-group'>";
                while($row = mysqli_fetch_assoc($sysadminQuery)){
				echo "<label for='inputforform' class='col-sm-3 control-label'>".$row['details']."</label>";
				echo "<div class='col-sm-2' style='margin-bottom:20px;'>";
				if($row['status']=='Yes')
				{
				echo "<select class='form-control' id=".$row['details_id']." name=".$row['details_id'].">
				<option value=".$row['status'].">".$row['status']."</option>
				<option value='No'>No</option></select>";
				}
				else if($row['status']=='No')
				{
				echo "<select class='form-control' id=".$row['details_id']." name=".$row['details_id'].">
				<option value=".$row['status'].">".$row['status']."</option>
				<option value='Yes'>Yes</option></select>";
				}
				else
				{
				echo "<select class='form-control' id=".$row['details_id']." name=".$row['details_id'].">
				<option value='No'>No</option></select>;
				<option value='Yes'>Yes</option></select>";	
				}
				echo "<textarea class='form-control' type='text' maxlength=1000 rows=4 style='margin-top: 7%;width:275px;resize:none;overflow-y:scroll;' id=".$row['details_id'].'Comments'." name=".$row['details_id'].'Comments'." placeholder='Comments if any' value='".$row['comments']."' autocomplete='off'></textarea>";
				echo "</div>";
                    $i++;
                  }
				  echo "</div>";
				}
                ?>
			  </div>
			  
			  <div class="box-footer">   
				<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields" />			   
              </div>
			  
			  </form>
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
<!-- Page script -->

 <script>
  $(function() {
  $("#datepicker,#datepicker1,#datepicker2,#datepicker3,#currentdate").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});
$("#savefields").click(function() {
  ajaxindicatorstart("Processing..Please Wait..");
});
	</script>
<script>

	$(function() {
  var bid, trid;
  $('#resgnchange tr').click(function() {
       Id = $(this).find('.EmpId').text();
		$('#EmpIdvalue').val(Id);
  });
});
	</script>
<script type="text/javascript">
   $(document).on('click','#test',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#statuschange").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "updatemngrstatus.php",
         success: function(data){
			alert('HI');
		 location.reload();
		   ajaxindicatorstop();

         }
});

});
    </script>
</body>
</html>
