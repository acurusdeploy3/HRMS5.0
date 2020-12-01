<?php   
session_start();  
$userid=$_SESSION['login_user'];
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
$date = date("Y-m-d");
if(isset($_GET['res_id']) && $_GET['res_id'] != ''){
	$res_id = $_GET['res_id'];}
$result99=mysqli_query($db,"SELECT * FROM `employee_resignation_information` where res_id_value = '".$res_id."'");
$detRow99 = mysqli_fetch_array($result99);
$resignation_id=$detRow99['resignation_id'];
$usergrp=$_SESSION['login_user_group'];
$certQuery = mysqli_query($db,"SELECT  ei.employee_id as empid,concat(First_name,' ',MI,' ',Last_Name) as Name,Employee_Designation,Department,
date_format(Date_Joined,'%d %b %Y') as Date_Joined,Primary_Mobile_Number,
date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,
date_format(date(date_of_leaving),'%d %b %Y') as dl,
concat(Present_Address_Line_2,' ',Present_Address_Line_3,', ',Present_Address_Line_1,', ') as line1,concat(Present_Street,', ',Present_City,', ',Present_District,', ',Present_State,', ',Present_Country) as line2,Present_Address_ZipCode as line3,Employee_Personal_Email
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id where ei.resignation_id=$resignation_id and ei.is_active='Y' ");
$row= mysqli_fetch_assoc($certQuery);
$columnsquery =mysqli_query($db,"SELECT value FROM `all_fields` where field_name='Column_Name' order by sort_order asc");

$depquery=mysqli_query($db,"SELECT value from `all_fields` where field_name='name' and description='Department-Main';");


$query2=mysqli_query($db,"SELECT department as description,value,status,comments,sort_order FROM `all_fields` a left join  nodueformentries n on a.value=n.details
where field_name='Details' and value ='Knowledge Transfer :' and  resignation_id=$resignation_id group by department union SELECT description,value,status,comments,sort_order
FROM `all_fields` a left join  nodueformentries n on a.value=n.details where field_name='Details' and  resignation_id=$resignation_id and value !='Knowledge Transfer :'
order by sort_order asc");

//$adminname=mysqli_query($db,"select concat(First_name,' ',MI,' ',Last_Name) as AdminName from employee_details where job_role='System Admin Manager' and is_active='Y'");


$hadminname=mysqli_query($db,"select concat(First_name,' ',MI,' ',Last_Name) as AdminName from employee_details where employee_id=
(SELECT distinct modified_by FROM `nodueformentries` where resignation_id=$resignation_id and department='Administration')");
$hadminnamerow = mysqli_fetch_assoc($hadminname);
$hadminval = $hadminnamerow['AdminName'];



$adminname=mysqli_query($db,"select concat(First_name,' ',MI,' ',Last_Name) as AdminName from employee_details where employee_id=
(SELECT distinct modified_by FROM `nodueformentries` where resignation_id=$resignation_id and department='System Administration')");
$adminnamerow = mysqli_fetch_assoc($adminname);
$adminval = $adminnamerow['AdminName'];

//$accname=mysqli_query($db,"select concat(First_name,' ',MI,' ',Last_Name) as AccName from employee_details where job_role='Accounts Manager' and is_active='Y'");
$accname=mysqli_query($db,"select concat(First_name,' ',MI,' ',Last_Name) as AccName from employee_details where employee_id=
(SELECT distinct modified_by FROM `nodueformentries` where resignation_id=$resignation_id and department='Accounts')");
$accnamerow = mysqli_fetch_assoc($accname);
$accval = $accnamerow['AccName'];

$manname=mysqli_query($db,"select concat(First_name,' ',MI,' ',Last_Name) as AccName from employee_details where employee_id=(SELECT distinct modified_by FROM `nodueformentries` where resignation_id=$resignation_id and details='Knowledge Transfer :')");
$mannamerow = mysqli_fetch_assoc($manname);
$manval = $mannamerow['AccName'];

//$hrname=mysqli_query($db,"SELECT substring_index(allocated_to,'-',1) as AccName FROM `employee_resignation_information` where resignation_id=$resignation_id");
$hrname=mysqli_query($db,"select concat(First_name,' ',MI,' ',Last_Name) as AccName from employee_details where employee_id=
(SELECT distinct modified_by FROM `nodueformentries` where resignation_id=$resignation_id and department='Human Resources')");
$hrnamerow = mysqli_fetch_assoc($hrname);
$hrnameval = $hrnamerow['AccName'];

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

td, th {
  border: 1px solid black;
  text-align: left;
  padding: 8px;
  width:5%;
  height:30px;
}
th {
  background-color: #fbe2d8;
}
	.error {color: #FF0000;}
.fa-fw {
    padding-top: 13px;
}
#goprevious{
	background-color: #286090;
	display: block;
    padding: 10px 18px;
    margin-bottom: 0;
    font-size: 15px;
    font-weight: 900;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
#downloadpdf{
	background-color: #f4f4f4;
	margin-bottom: 12px !important;
	display: block;
    padding: 1px 12px 10px 10px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:black;
	color:#444;
	border: 1px solid black;
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
.special1
{
    font-weight: bold;
}


</style>
<style>

@media print 
{
   @page
   {
     margin: 0mm;
  }
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
   <section class="content-header">
	   <input action="action" class="btn btn-info pull-left" style="margin-right:7px;" onclick="window.location='hrnodueprocessingform.php?res_id=<?php echo $res_id;?>';" type="button" value="Back" id="goprevious"/> 
	   <h1 style="text-align: center;">
       ACURUS NO DUE FORM
      <small> Resignation </small>
        <button  style="margin-right:10px;" type="button" id="downloadpdf" onclick="javascript:printContent();" class="btn btn-default pull-right">Download PDF &nbsp; <i class="fa fa-fw fa-file-pdf-o"> </i></button>
		</h1>
    </section>
	
	<section class="content">
      <div class="row">
	  
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info" style="width:110%;">
			
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
          
              <div class="box-body">
				</div>
			
			 <div class="box-footer">                
              </div>
              <!-- /.box-footer -->			  		  
          <?php
                if(mysqli_num_rows($certQuery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
					?>
		  <div id="noduedownload" class="border-class">
        <table class="table" style="width:100%">
		<tbody>
		<table style="width:100%">
		<tr>
			<td style="width:100%;text-align:center;background-color:lavender;" class="special1">NO DUE FORM</td>
		</tr>
		<table>
		<table style="width:100%">
		<tr>
			<td style ="width:20%" class="special1">Name</td>
			<?php  echo "<td style='width:20%'>".$row['Name']."</td>"; ?>
			<td style ="width:20%" class="special1">Date of Joining</td>
			<?php  echo "<td style='width:40%'>".$row['Date_Joined']."</td>"; ?>
		</tr>
		<tr>
			<td style ="width:20%" class="special1">Emp.ID</td>
			<?php  echo "<td style='width:20%'>".$row['empid']."</td>"; ?>
			<td style ="width:20%" class="special1">Resignation letter date</td>
			<?php  echo "<td style='width:40%'>".$row['ds']."</td>"; ?>
		</tr>
		<tr>
			<td style ="width:20%" class="special1">Designation</td>
			<?php  echo "<td style='width:20%'>".$row['Employee_Designation']."</td>"; ?>
			<td style ="width:20%" class="special1">Relieving Date</td>
			<?php  echo "<td style='width:20%'>".$row['dl']."</td>"; ?>
		</tr>
		<tr>
			<td style ="width:20%" class="special1">Department</td>
			<?php  echo "<td style='width:20%'>".$row['Department']."</td>"; ?>
			<td style ="width:20%;border-bottom:none;" class="special1">Address for Communication</td>
			<?php  echo "<td style='width:40%;border-bottom:none;'>".$row['line1']."</td>"; ?>
		</tr>
		<tr>
			<td style ="width:20%" class="special1">Contact Number</td>
			<?php  echo "<td style='width:20%'>".$row['Primary_Mobile_Number']."</td>"; ?>
			<td style ="width:20%;border-top:none;border-bottom:none;" class="special1"></td>
			<?php  echo "<td style='width:40%;border-top:none;border-bottom:none;'>".$row['line2']."</td>"; ?>
			
		</tr>
		<tr>
			<td style ="width:20%" class="special1">E-mail ID</td>
			<?php  echo "<td style='width:20%'>".$row['Employee_Personal_Email']."</td>"; ?>
			<td style ="width:20%;border-top:none" class="special1"></td>
			<?php  echo "<td style='width:40%;border-top:none;'>".$row['line3']."</td>"; ?>
			</tr>
		</table>
		<br><br>
		<?php if(mysqli_num_rows($columnsquery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
					?>
		<table class="table" style="width:100%;margin-bottom:0px;font-weight:bold;table-layout:fixed;">
		<tr>
       
		<?php 
		echo "<td style='width:15%;background-color:lavender;border-top: 1px solid black;' class='special1'> <span>Department</span> </td>";
        echo "<td style='width:20%;background-color:lavender;border-top: 1px solid black;' class='special1'> <span>Name</span> </td>";
        echo "<td style='width:15%;background-color:lavender;border-top: 1px solid black;' class='special1'> <span>Details</span> </td>";
        echo "<td style='width:10%;background-color:lavender;border-top: 1px solid black;' class='special1'> <span>Status</span> </td>";
        echo "<td style='width:15%;background-color:lavender;border-top: 1px solid black;' class='special1'> <span>Comments</span></td>";
        echo "<td style='width:15%;background-color:lavender;border-top: 1px solid black;' class='special1'> <span>Signature</span> </td>";
		 ?>
        </tr>
		</table>
				<?php } ?>
			<?php if(mysqli_num_rows($query2) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
					?>
		<table class="table" style="width:100%;margin-bottom:0px;table-layout:fixed;" style="border-bottom:1px solid black;">
		<?php 
		$k=1;$j=1;
		while($row3 = mysqli_fetch_assoc($query2)){
		echo "<tr>";
		if($row3['description']==''){
		echo "<td style='width:15%;border-bottom:none;' class='special1'>".$row3['description']."</td>";
    }
        else {
        echo "<td style='width:15%;border-bottom:none;border-top:1px solid black;' class='special1'>".ucwords(strtolower($row3['description']))."</td>";
        }
		if($row3['description']=='System Administration'){
		echo "<td style='width:20%;border-bottom:none;border-top:1px solid black;'>".$adminval."</td>";}
		else if ($row3['description']=='Accounts'){
			echo "<td style='width:20%;border-bottom:none;border-top:1px solid black;'>".$accval."</td>";
		}
		else if ($row3['value']=='Knowledge Transfer :'){
			echo "<td style='width:20%;border-bottom:none;border-top:1px solid black;'>".$manval."</td>";
		}
		else if ($row3['description']=='Administration'){
			echo "<td style='width:20%;border-bottom:none;border-top:1px solid black;'>".$hadminval."</td>";
		}
		else if ($row3['description']=='Human Resources')
		{
			echo "<td style='width:20%;border-bottom:none;border-top:1px solid black;'>".$hrnameval."</td>";
		}
		else {
        echo "<td style='width:15%;border-bottom:none;'></td>";
        }
        if($row3['description']=='')
        {
        echo "<td style='width:15%;border-bottom:none;' class='special1'>".$row3['value']."</td>";
		echo "<td style='width:10%;border-bottom:none;'>".$row3['status']."</td>";
		echo "<td style='width:15%;border-bottom:none;'>".$row3['comments']."</td>";
		echo "<td style='width:15%;border-bottom:none;'></td>";
		}
        else {
		echo "<td style='width:15%;border-top:1px solid black;border-bottom: none;' class='special1'>".$row3['value']."</td>";
		echo "<td style='width:10%;border-top:1px solid black;border-bottom: none;'>".$row3['status']."</td>";
		echo "<td style='width:15%;border-top:1px solid black;border-bottom: none;'>".$row3['comments']."</td>";
		echo "<td style='width:15%;border-top:1px solid black;border-bottom: none;'></td>";
        }
		echo "</tr>";
		$k++;
			
		} ?>
		</table>
				<?php } ?>		
		</tbody>
		</table>		
		
          </div>
		   <?php } ?>
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
	function clearfields()
	{
		document.getElementById("reason_for_resignation").value="";
	}
	</script>
<script>
function printContent()
{
var DocumentContainer = document.getElementById('noduedownload');
var html = '<html>'+
			'<style>'+
			'table {'+
				'border-collapse: collapse;'+
				'}'+
			'table, th, td {'+
				'border: 1px solid black;'+
			'}'+
			'.special1 {'+
				'font-weight: bold;'+
			'}'+
			'</style>'+
				'<body style="background:#ffffff;">'+
               DocumentContainer.innerHTML+
               '</body></html>';

    var WindowObject = window.open("", "PrintWindow",
    "_blank");
    WindowObject.document.writeln(html);
    WindowObject.document.close();
    WindowObject.focus();
    WindowObject.print();
    WindowObject.close();
    document.getElementById('noduedownload').style.display='block';
}
</script>
<script>
//function print() {
	//document.getElementById("downloadpdf").style.display="none";
  //  var e = document.getElementById("noduedownload");
  //  printWindow = window.open(), printWindow.document.write("<div style='width:70%;margin:0'>"), printWindow.document.write("<div id='noduedownload' onload="javascript:window.print();"/>"), //printWindow.document.write("</div>"), printWindow.setTimeout(function () {
    //    printWindow.close()
   // }, 1e4)
//}

</script>
</body>
</html>
