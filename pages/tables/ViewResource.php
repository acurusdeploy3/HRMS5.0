<?php
include("config.php");
session_start();
$employeeid=$_GET['id'];
$name=$_SESSION['login_user'];
$role=$_SESSION['login_user_group'];
$checkREporting = mysql_query("select * from pms_manager_lookup where employee_id='$employeeid' and manager_id='$name'");
if(mysql_num_rows($checkREporting)>0  || $role=='HR' || $role=='HR Manager')
{
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Resource Management</title>
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
  background-color: lightsteelblue;
}
#historyDiv {
  position: relative;
  width: 100%;
  height: 100%;
  margin: 0 auto;
  font-family: Calibri;
  border: 2px solid #ccc;
  box-shadow: 7px 7px 20px #555;
}
#DesgnDiv {
  position: relative;
  width: 100%;
  height: 100%;
  margin: 0 auto;
  font-family: Calibri;
  border: 2px solid #ccc;
  box-shadow: 7px 7px 20px #555;
}
#ProjDiv {
  position: relative;
  width: 100%;
  height: 100%;
  margin: 0 auto;
  font-family: Calibri;
  border: 2px solid #ccc;
  box-shadow: 7px 7px 20px #555;
}
</style>
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
</style>
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
        Resource Management
<button OnClick="window.location.href='AuditLog.php?id=Resource Management'" type="button" class="btn btn-success pull-right">View Change Log</button>
      </h1>

    </section>
	<?php
		$id = $_GET['id'];
		session_start();
		$_SESSION['EmpId']=$id;
      $userRole = $_SESSION['login_user_group'];
	 $previous= $_SESSION['previous'];
		?>
	 <?php
			  include("config.php");


			  $getName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id=$id");
			  $EmpNameRow = mysql_fetch_array($getName);
			  $EmpName = $EmpNameRow['name'];
			 $_SESSION['LOAName']= $EmpName;
			  $getDOJ = mysql_query("select Date_format(date_joined,'%d-%b-%Y') as doj from employee_details where employee_id=$id");
			  $getDOJRow = mysql_fetch_array($getDOJ);
			  $DOJ = $getDOJRow['doj'];

			$getEmployeeProjects = mysql_query("SELECT group_concat('''',project_id,'''') as projects FROM `employee_projects` where employee_id=$id and is_active='Y';");
			$getEmployeeProjectsCnt = mysql_query("SELECT project_id FROM `employee_projects` where employee_id=$id and is_active='Y';");


			$getEmployeeProjectsRow = mysql_fetch_array($getEmployeeProjects);
			$EmployeeProjects = $getEmployeeProjectsRow['projects'];
			  $resQuery = mysql_query("select res_management_id,employee_id,band,designation,level,department,
				date_format(effective_from,'%d-%b-%Y') as  Effective_From,project_id,reporting_manager,signed_loa_doc


					from resource_management_table
						 WHERE employee_id=$id and is_Active='Y'");
			  $tRow = mysql_fetch_array($resQuery);
			  $resID = $tRow['res_management_id'];
			  $_SESSION['NewRID']=$resID;
			  $band = $tRow['band'];
			  $desn = $tRow['designation'];
			  $level = $tRow['level'];
			  $monthsserved = $tRow['Effective_From'];
			  $projname = $tRow['project_id'];
			  $Dept = $tRow['department'];
			  $currLoa = $tRow['signed_loa_doc'];
			  $getCurrDoc = mysql_query("select document_name from employee_documents where doc_id='$currLoa'");
			  $getCurRow =  mysql_fetch_assoc($getCurrDoc);
			  $Currdoc = $getCurRow['document_name'];


			   $resQuery1 = mysql_query("select res_management_id,employee_id,band,designation,level,department,
				date_format(effective_from,'%d-%b-%Y') as  Effective_From,project_id,reporting_manager,signed_loa_doc


					from resource_management_table
						 WHERE employee_id=$id and effective_From>=curdate() and is_active='N'");
			  $tRow1 = mysql_fetch_array($resQuery1);
			  $resID1 = $tRow1['res_management_id'];
			  $band1 = $tRow1['band'];
			  $desn1 = $tRow1['designation'];
			  $level1 = $tRow1['level'];
			  $monthsserved1 = $tRow1['Effective_From'];
			  $projname1 = $tRow1['project_id'];
			  $Dept1 = $tRow1['department'];
			  $currLoa1 = $tRow1['signed_loa_doc'];
			  $getCurrDoc1 = mysql_query("select document_name from employee_documents where doc_id='$currLoa1'");
			  $getCurRow1 =  mysql_fetch_assoc($getCurrDoc1);
			  $Currdoc1 = $getCurRow1['document_name'];




			$getReportingMngr = mysql_query("select reporting_manager from resource_management_table where res_management_id =  $resID");
			 $getReportingMngr = mysql_query("select reporting_manager from resource_management_table where res_management_id =  $resID");
			  $ManagerRow = mysql_fetch_array($getReportingMngr);
			  $repMngrid  = $ManagerRow['reporting_manager'];
			  $getMngrName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$repMngrid'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];



			   $getReportingMngr1 = mysql_query("select reporting_manager from resource_management_table where res_management_id =  $resID1");
			  $ManagerRow1 = mysql_fetch_array($getReportingMngr1);
			  $repMngrid1  = $ManagerRow1['reporting_manager'];
			  $getMngrName1 = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$repMngrid1'");
			  $ManagerNameRow1 = mysql_fetch_array($getMngrName1);
			  $repMngrName1 = $ManagerNameRow1['name'];






			  if($band!=$band1 || $level!=$level1 || $desn1!=$desn)
				{
				$degn = 'Y';
				$oldDesn = $band.' '.$desn.'  '.$level;
				$newdesn = $band1.' '.$desn1.' '.$level1;
				$_SESSION['DesChange']  = 'It is being announced with immense please that your are being designated as '.$newdesn.' from '.$oldDesn.'. ';
				$_SESSION['DesPDF']  = 'It is being announced with immense please that your are being designated as '.$newdesn.' from '.$oldDesn.'. ';
				}
			if($repMngrid!=$repMngrid1)
				{
					$mngrchanged = 'Y';
					$_SESSION['RepMgmr']='You are Supposed to be answerable to '.$repMngrName1.' for the deeds you will have to perform in any case.'.' ';
					$_SESSION['MgmrPDF']='You are Supposed to be answerable to '.$repMngrName1.' for the deeds you will have to perform in any case.'.' ';
				}
				if($Dept1 != $Dept && $Dept1!='')
				{
					$Deptchange = 'Y';
					$_SESSION['Dept'] = 'You are being transferred from  Department '.$Dept.' to '.$Dept1.'. ';
					$_SESSION['DeptPDF'] = 'You are being transferred from  Department '.$Dept.' to '.$Dept1.'. ';
				}
			  $_SESSION['effectivefrom']= $monthsserved1;
			  session_Start();

			  $_SESSION['rId']=$resID;
			 $fromSearchEmp =  $_SESSION['fromSearchEmployee'];
			  ?>
			   <?php
			  include("config.php");
			  $query = mysql_query("Select designation_id,designation_desc from all_designations where designation_desc!='$desn'");
			  $query1 = mysql_query("Select band_no,band_desc from all_bands where band_desc !='$band' and band_desc!=''");
			  $query2 = mysql_query("Select level_id,level_desc from all_levels where level_desc !='$level'");
			  $query3 = mysql_query("SELECT department_id,department FROM `all_departments` where department!='$Dept'");
			  if(mysql_num_rows($getEmployeeProjectsCnt)==0)
			  {
			  $query4 = mysql_query("select project_no,project_id,project_name from all_projects");
			  }
			  else
			  {
				 $query4 = mysql_query("select project_no,project_id,project_name from all_projects where project_id not in ($EmployeeProjects)");
			  }
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager' and employee_id not in ('$repMngrid','$id')");

			  ?>
			  <br>
			  <br>
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
				<button OnClick="window.location='<?php echo $previous?>'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
                  </td>

			  </tr>
			  </tbody>
			  </table>
          <h2 class="page-header">
           <?php echo $EmpName ?> : <?php echo $id  ?>
            <small class="pull-right"><?php echo  'Date Joined : <strong>'.$DOJ.'</strong>'  ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
         <div id="DesgnDiv" class="row">
        <div class="col-xs-12" style="
    background-color: transparent;
">
		<fieldset>
				<div class="box-header"><legend style="border-bottom: 0.5px solid #3c8dbc;color: black;font-weight: bold;"><h4 style="
				text-align: left;
				"><strong>Active Designation</strong></h4></legend>
				</div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Role</th>
                  <th>Band</th>
                  <th>Level</th>
                  <th>Department</th>
                  <th>Reporting Manager</th>
                  <th>Serving From</th>
                  <?php
                  if($userRole=='HR Manager' || $userRole=='HR')
                  {
                   ?>
                  <th>Office Order</th>

                  <?php
                }
                   ?>
                  <th>DOC ID</th>
                </tr>
                <tr>
                  <td><?php echo $desn ?></td>
                  <td><?php if ($band!='') { echo $band; } else { echo '--';} ?></td>
                  <td><?php  if ($level!='') { echo $level; } else { echo '--';}?></td>
                  <td><?php echo $Dept ?></td>
                  <td><?php echo $repMngrName ?></td>
                  <td><?php echo $monthsserved ?></td>
              <?php
              if($userRole=='HR Manager' || $userRole=='HR')
              {
               ?>
                    <?php
				                  if($currLoa=='')
			                        {
                    ?>
                                <td><a href="#myModal" class="btn_anchor" data-toggle="modal" id="PERMANANT" data-target="#modal-default-Upload"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a></td>
                    <?php
				                  }
		                        else
			                          {
                    ?>
				                    <td><i class="fa fa-fw fa-download"></i><a href="DownloadLOAHistory.php?link=<?php echo $Currdoc; ?>">Download</a></td>
                    <?php
				                      }
                      ?>
              <?php
              }
               ?>
				   <td><?php echo $currLoa ?></td>
                </tr>

              </table>
          </div>
		  </fieldset>
          <!-- /.box -->
      

        <!-- /.col -->
     
	  <br>
	  <br>
           <fieldset>
				<div class="box-header"><legend style="border-bottom: 0.5px solid #3c8dbc;color: black;font-weight: bold;"><h4 style="
				text-align: left;
				"><strong>New Designation</strong></h4></legend>
				</div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Role</th>
                  <th>Band</th>
                  <th>Level</th>
                  <th>Department</th>
                  <th>Reporting Manager</th>
                  <th>Effective From</th>
				  <th>Modify</th>
                  <th>Generate Office Order</th>
                  <th>Notify Employee</th>

                </tr>
				<?php
				if(mysql_num_rows($resQuery1)>0)
				{
				?>
                <tr>
                  <td><?php echo $desn1 ?></td>
                  <td><?php if ($band1!='') { echo $band1; } else { echo '--';} ?></td>
                  <td><?php  if ($level1!='') { echo $level1; } else { echo '--';}?></td>
                  <td><?php echo $Dept1 ?></td>
                  <td><?php echo $repMngrName1 ?></td>
                  <td><?php echo $monthsserved1 ?></td>
            <?php
              if($userRole=='HR Manager' || $userRole=='HR')
                {
                ?>
			  <td><a href="#" id="additionalBand" data-toggle="modal" data-target="#modal-default-ModifyDegn"><i class="fa fa-fw fa-eraser"></i></a></td>
			   <td><a href="GeneratePDF/DownloadLOA.php"><img alt='User' src='Images/download.jpg' title="Generate Office Order" width='18px' height='18px' /></a></td>
			   <td><a href="#" id="additionalBand" data-toggle="modal" data-target="#modal-default-Notify"><img alt='User' src='Images/Notify.png' title="Send Notification Email" width='18px' height='18px' /></a></td>
          <?php
        }
        else {
          ?>
          <td>NA</td>
          <td>NA</td>
          <td>NA</td>
          <?php
        }
           ?>
				</tr>
				<?php
				}
				else
				{
				?>
				 <tr>
				 <td>No Data Found</td>
				</tr>
				<?php
				}
				?>
              </table>
 <br>
			  <br>
            <!-- /.box-body -->
          </div>
		  
		   </fieldset>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>
	  <br>
	  <br>
	  <?php
		    include("config.php");
		  $ProQuery = mysql_query("select a.id,a.project_id,b.project_name,a.employee_id,date_format(a.created_date_and_time,'%d - %b - %Y') as
created_date_and_time,a.created_by,concat(c.first_name,' ',c.last_name) as allocated_by,TIMESTAMPDIFF(MONTH,a.allocated_from,now()) as months_served,allocated_percentage,date(allocated_from) as allocated_from,date(allocated_to) as allocated_to
 from employee_projects a left join all_projects b on
a.project_id=b.project_id left join employee_details c on
c.employee_id= a.created_by

 where a.employee_id='$id' and a.is_active='Y' and c.is_active='Y' and allocated_from <=now() order by allocated_from asc
");
					$cntpro = mysql_num_rows($ProQuery);
					$ExisPro  = mysql_query("select group_concat('''',project_id,'''') as project_id from employee_projects where employee_id='$id' and Is_active='Y'");
					$ExixProRow = mysql_fetch_array($ExisPro);
					$ProjIDs = $ExixProRow['project_id'];
					 $ProjQuery = mysql_query("select project_no,project_id,project_name from all_projects where project_id not in ($ProjIDs) and project_id!=''");
					 $persue = mysql_query("select sum(allocated_percentage) as resource_used_percentage from employee_projects where employee_id='$id' and is_active='Y' and allocated_from <=now()");

					 $persuerow  = mysql_fetch_array($persue);

					 $percent = $persuerow['resource_used_percentage'];
					 $totper =100;
					 $remPercent =  $totper + (-$percent);

					  $persue1 = mysql_query("select sum(allocated_percentage) as resource_used_percentage from employee_projects where employee_id='$id' and is_active='Y'");
					   $persuerow1  = mysql_fetch_array($persue1);
					  $percent1 = $persuerow1['resource_used_percentage'];
					 $totper1 =100;
					 $remPercent1 =  $totper1 + (-$percent1);


						$currPer = mysql_query("select sum(allocated_percentage) as curr_per from employee_projects where employee_id='$id' and allocated_from<=curdate() and is_active='Y'");
						$curPerRow = mysql_fetch_array($currPer);
						$CrrentPrecentage = $curPerRow['curr_per'];
						if($CrrentPrecentage=='')
						{
							$CrrentPrecentage=0;
						}

						$FutPer = mysql_query("select sum(allocated_percentage) as fut_per from employee_projects where employee_id='$id' and allocated_from>curdate() and is_active='Y'");
						$FutPerRow = mysql_fetch_array($FutPer);
						$FuturePrecentage = $FutPerRow['fut_per'];
						if($FuturePrecentage=='')
						{
							$FuturePrecentage=0;
						}
						 ?>
		  <div class="row">

		  <input type="hidden" id="remVal1" name="remVal1" value=<?php echo $remPercent1; ?> />
		   <div class="col-xs-12">
         <?php

		// if($userRole=='Accounts Manager')
		//{
          ?>
			   <a href="#" target="_blank" data-toggle="modal" style="margin-left: 15px;" data-target="#modal-default-Create" class="btn btn-primary pull-left">Create New Project</a>
              <a href="#" id="notBtn" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Not" class="btn btn-danger pull-right">Skip Upload</a>
                  <?php
              // }
                ?>
        </div>
        </div>
		<br>
		<br>
		
		<div id="ProjDiv" class="row">
        <div class="col-xs-12" style="
    background-color: transparent;
">

           <div class="box-header"><legend style="border-bottom: 0.5px solid #3c8dbc;color: black;font-weight: bold;"><h4 style="
				text-align: left;
				"><strong>Current Project(s)</strong></h4>
				 <h4 class="pull-right">Resource Current Usage : <strong><?php echo  $CrrentPrecentage.' %';  ?></strong></h4></legend>
				 </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
			 <?php
         if($userRole=='Accounts Manager' || $isFirstLine=='Y')
         {
         ?>
            <?php
  		   ?>
              <a href="#" target="_blank" data-toggle="modal" style="background-color:#3a3c80;" data-target="#modal-default-Level" class="btn btn-success pull-left">Add New Project <i class="fa fa-fw fa-plus"></i></a>
			   <br>
		 <br>
		            <?php
		            
		 }
		 ?>
		
		 <br>
              <table name="ActProj" id="ActProj" class="table table-hover">
                <tr>
                  <th>Project</th>
                  <th>Allocated %</th>
				  <th>Allocated From</th>
                  <th>Allocated To</th>
				  <th>Date of Allocation</th>
				  <th>Months Served</th>
                  <th>Deallocate</th>
                  <th>Modify</th>
                </tr>
				<?php
				$cntpro = mysql_num_rows($ProQuery);
				if($cntpro > 0)
				{
					while($ProjRes = mysql_fetch_assoc($ProQuery))
					{
				?>
                <tr id="<?php  echo $ProjRes['allocated_percentage']  ?>" name="<?php  echo $ProjRes['id']  ?>">
                  <td><?php echo $ProjRes['project_name']; ?></td>
                  <td>
					<?php
					if($ProjRes['allocated_percentage']!=0)
					{
					?>
					<span class="badge bg-light-blue"><?php echo $ProjRes['allocated_percentage']; ?>&nbsp; %</span>

				  <?php
				  }
				  else
				  {
				  ?>
				   <span class="badge bg-red"><?php echo $ProjRes['allocated_percentage']; ?>&nbsp; %</span>
				  <?php
				  }
				  ?>
				</td>

                  <td class="fromDate"><?php echo $ProjRes['allocated_from']; ?></td>
                  <td class="toDate"><?php echo $ProjRes['allocated_to']; ?></td>
				  <td><?php echo $ProjRes['created_date_and_time']; ?></td>
            <td><?php echo $ProjRes['months_served']; ?></td>
            <?php
            if($userRole=='Accounts Manager' || $isFirstLine=='Y')
            {
            ?>
				   <td><a href="RemovefromProj.php?id=<?php echo $ProjRes['id'] ?>&projName=<?php echo $ProjRes['project_name'] ?>" OnClick="return confirm('Are you Sure you want to Deallocate?');"><img alt='User' src='../../dist/img/img_318906.png' title="Deallocate Resource from Project" width='18px' height='18px' /></a></td>
				  <td><a href="#" id="additionalBand" data-toggle="modal" data-target="#modal-default-Modify"><i class="fa fa-fw fa-eraser"></i></a></td>
            <?php
            }
            else {
              ?>
              <td>NA</td>
                <td>NA</td>
              <?php
            }
             ?>
                </tr>
                <?php
					}
				}
				else
				{
				?>
				<tr>
                  <td>No Data Found</td>

				  <?php
				}
				  ?>
				</tr>
              </table>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->










	 <!--Future Projects -->




	    <?php
		    include("config.php");
		  $FuProQuery = mysql_query("select a.id,a.project_id,b.project_name,a.employee_id,date_format(a.created_date_and_time,'%d - %b - %Y') as
created_date_and_time,a.created_by,concat(c.first_name,' ',c.last_name) as allocated_by,TIMESTAMPDIFF(MONTH,a.allocated_from,now()) as months_served,allocated_percentage,date(allocated_from) as allocated_from,date(allocated_to) as allocated_to
 from employee_projects a left join all_projects b on
a.project_id=b.project_id left join employee_details c on
c.employee_id= a.created_by

 where a.employee_id='$id' and a.is_active='Y' and allocated_from > now()
 order by allocated_from asc");
					 ?>
		 


		<br>
		<br>
<div class="box-header"><legend style="border-bottom: 0.5px solid #3c8dbc;color: black;font-weight: bold;"><h4 style="
				text-align: left;
				"><strong>Upcoming Project(s)</strong></h4>
				 </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="ActProj1" id="ActProj1" class="table table-hover">
                <tr>
                  <th>Project</th>
				  <th>Allocated %</th>
				  <th>Allocated From</th>
                  <th>Allocated To</th>
				  <th>Date of Allocation</th>
				  <th>Deallocate</th>
				  <th>Modify</th>

                </tr>
				<?php
				$cntFupro = mysql_num_rows($FuProQuery);
				if($cntFupro > 0)
				{
					while($FuProjRes = mysql_fetch_assoc($FuProQuery))
					{
				?>
                <tr id="<?php  echo $FuProjRes['allocated_percentage']  ?>" name="<?php  echo $FuProjRes['id']  ?>">
                  <td><?php echo $FuProjRes['project_name']; ?></td>
				<?php
					if($FuProjRes['allocated_percentage']!=0)
					{
					?>
					 <td><span class="badge bg-light-blue"><?php echo $FuProjRes['allocated_percentage']; ?>&nbsp; %</span></td>

				  <?php
				  }
				  else
				  {
				  ?>
				   <td> <span class="badge bg-red"><?php echo $FuProjRes['allocated_percentage']; ?>&nbsp; %</span></td>
				  <?php
				  }
				  ?>

                  <td class="fromDateFut"><?php echo $FuProjRes['allocated_from']; ?></td>
                  <td class="toDateFut"><?php echo $FuProjRes['allocated_to']; ?></td>
                  <td><?php echo $FuProjRes['created_date_and_time']; ?></td>
                  <?php
                  if($userRole=='Accounts Manager' || $isFirstLine=='Y')
                  {
                  ?>
				   <td><a href="RemovefromProj.php?id=<?php echo $FuProjRes['id'] ?>" OnClick="return confirm('Are you Sure you want to Deallocate?');"><img alt='User' src='../../dist/img/img_318906.png' title="Deallocate Resource from Project" width='18px' height='18px' /></a></td>
                  <td><a href="#" id="additionalBand" data-toggle="modal" data-target="#modal-default-Modify"><i class="fa fa-fw fa-eraser"></i></a></td>
                  <?php
                }
                else {
                  ?>
                    <td>NA</td>
                    <td>NA</td>
                  <?php
                }
                   ?>

      	</tr>
                <?php
					}
				}
				else
				{
				?>
				<tr>
                  <td>No Data Found</td>

				  <?php
				}
				  ?>
				</tr>
              </table>
			  <br>
			  <br>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>



















      <!-- /.row -->
<div class="row no-print">
  </div>
      <!-- Table row -->
	  <br>
	  <br>
      <div id="historyDiv" class="row">
        <div class="col-xs-12 table-responsive" style="
    background-color: transparent;
">
		<div class="box-header"><legend style="border-bottom: 0.5px solid #3c8dbc;color: black;font-weight: bold;"><h4 style="
				text-align: left;
				"><strong>Resource Designation History</strong></h4></legend>
				 </div>
			<?php
				$getHistory = mysql_query("select employee_id,concat(band,' ',designation,' ',level) as designation,department,TIMESTAMPDIFF(MONTH,effective_From,a.date_archived) as Months,
c.document_name,date_format(DATE_SUB(a.date_archived, INTERVAL 1 DAY),'%d - %b - %Y') as Date_last_served
from history_resource_management_Table a
 left join history_employee_documents c on a.signed_loa_doc = c.doc_id
where employee_id='$id' order by a.date_archived desc");

			?>
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Designation</th>
              <th>Department</th>
              <th>Months Served</th>
              <th>Last Served Date</th>
              <?php
              if($userRole=='HR Manager' || $userRole=='HR')
              {
               ?>
              <th>Office Order</th>

              <?php
            }
               ?>
            </tr>
            </thead>
            <tbody>

			<?php
			$cnt = mysql_num_rows($getHistory);

			if($cnt>0)
			{
				while($HisDataRow = mysql_fetch_assoc($getHistory))
				{
			?>
			  <tr>
              <td><?php echo  $HisDataRow['designation'] ?></td>
              <td><?php echo  $HisDataRow['department'] ?></td>
              <td><?php echo  $HisDataRow['Months'] ?></td>
              <td><?php echo  $HisDataRow['Date_last_served'] ?></td>
              <?php
              if($userRole=='HR Manager' || $userRole=='HR')
              {
               ?>
      	<?php
						if($HisDataRow['document_name']!='')
						{
						?>

							<td><i class="fa fa-fw fa-download"></i><a href="DownloadLOAHistory.php?link=<?php echo $HisDataRow['document_name']; ?>">Download</a></td>
							<?php
						}
						else
						{
							?>
							<td>NA</td>
							<?php
						}
          }
						?>
				</tr>
            <?php
				}
			}
			else
			{
			?>
				<tr>
				<td>No Data Found</td>
				</tr>
			<?php
			}
			?>
            </tbody>
          </table>

	  <br>
	  <br>
	 	<div class="box-header"><legend style="border-bottom: 0.5px solid #3c8dbc;color: black;font-weight: bold;"><h4 style="
				text-align: left;
				"><strong>Project Allocation History</strong></h4></legend>
				 </div>
			<?php
				$getProHistory = mysql_query("select a.id,a.project_id,b.project_name,a.employee_id,date_format(a.date_archived,'%d - %b - %Y') as
date_archived,date_format(a.allocated_from,'%d - %b - %Y') as
date_worked_from,a.created_by,concat(c.first_name,' ',c.last_name) as allocated_by,TIMESTAMPDIFF(MONTH,allocated_from,a.allocated_to) as months_served
 from history_employee_projects a left join all_projects b on
a.project_id=b.project_id left join employee_details c on
c.employee_id= a.created_by

 where a.employee_id='$id'");


			?>
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Project ID</th>
              <th>Project</th>              
             <th>Worked from</th>
              <th>Months Worked</th>
              <th>Date of Deallocation</th>
            </tr>
            </thead>
            <tbody>

			<?php
			$cntPro = mysql_num_rows($getProHistory);

			if($cntPro>0)
			{
				while($HisProDataRow = mysql_fetch_assoc($getProHistory))
				{
			?>
				<tr>
              <td><?php echo  $HisProDataRow['project_id'] ?></td>
              <td><?php echo  $HisProDataRow['project_name'] ?></td>              
                <td><?php echo $HisProDataRow['date_worked_from'] ?></td>
              <td><?php echo  $HisProDataRow['months_served'] ?></td>
              <td><?php echo  $HisProDataRow['date_archived'] ?></td>
				</tr>
            <?php
				}
			}
			else
			{
			?>
				<tr>
				<td>No Data Found</td>
				</tr>
			<?php
			}
			?>
            </tbody>
          </table>
		  </div>
		  <div class="modal fade" id="modal-default-Upload">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Upload Signed OO</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">

            <form id="roleForm" action="InsertLOA.php" enctype="multipart/form-data" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload</label>

                  <div class="col-sm-10">
				  <input type="hidden" value="<?php echo $id ?>" name="hidEmpVal" id="hidEmpVal" >
                  <input type="file" id="ResumeFileDoc"  oninput="this.className = ''" name="ResumeFileDoc" accept= "application/msword,text/plain, application/pdf,image/x-png,image/gif,image/jpeg" required="required" />
                  </div>
                </div>

              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->

          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="UploadLOA" value="Upload Signed OO" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
	  
		  <div class="modal fade" id="modal-default-Create">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Project</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">

          <form id="ProjectForm" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
			  <?php
			  include("config2.php");
			  $getClientNames = mysqli_query($db,"select client_name,client_abb from all_clients");
			  $getDeptNames = mysqli_query($db,"select department_name,dept_abb from all_project_departments");
			  $getAllEmployeesName = mysqli_query($db,"select employee_id,employee_name from employee where status='A' and ldw='0001-01-01' and employee_id!=3");
			  ?>
                 <label>Client <a href="#" id="additionalClient" title="Click to Add More Clients" data-toggle="modal" data-target="#modal-default-Client"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="ClientSel" name="ClientSel" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php

				while ($getClientNamesRow = mysqli_fetch_assoc($getClientNames))
				{
				?>
                  <option value="<?php echo $getClientNamesRow['client_abb']  ?>"><?php echo $getClientNamesRow['client_name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
				<div class="form-group">
                 <label>Project Name</label>
                <input type="text" name="ProjectNameText" class="form-control" id="ProjectNameText" placeholder="Enter Project Name">
              </div>
			 <div class="form-group">
                <label>Start Date</label>

             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text"  name="startDate" autocomplete="off" class="form-control pull-right" id="datepicker11" placeholder="Pick a date" required>
                </div>
              </div>
			<!-- /.form-group -->
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
               <div class="form-group">
                 <label>Project Department <a href="#" id="additionalDepartments" title="Click to Add More Departments" data-toggle="modal" data-target="#modal-default-ProjDept"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="DeptSel" name="DeptSel" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php

				while ($getDeptNamesRow = mysqli_fetch_assoc($getDeptNames))
				{
				?>
                  <option value="<?php echo $getDeptNamesRow['dept_abb']  ?>"><?php echo $getDeptNamesRow['department_name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			<div class="form-group">
                 <label>Project Abbreviation</label>
                <input type="text" id="ProjectAbb" name="ProjectAbb" class="form-control" placeholder="Enter Abbreviation">
              </div>
			  <div class="form-group">
                 <label>Series</label>
             <input type="number" class="form-control pull-right" min="10" name="ProjectSeries" id="ProjectSeries" placeholder="Enter Series" required="required" />
              </div>
  
            </div>
          <br>
<div class="col-md-12">
<div class="col-md-12">
<div class="form-group">
                 <label>Project Manager </label>
                <select class="form-control select2" id="PMSelect" name="PMSelect" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php

				while ($getAllEmployeesNameRow = mysqli_fetch_assoc($getAllEmployeesName))
				{
				?>
                  <option value="<?php echo $getAllEmployeesNameRow['employee_id']  ?>"><?php echo $getAllEmployeesNameRow['employee_name'].' : '.$getAllEmployeesNameRow['employee_id']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
</div>
</div>
<br>
<div class="col-md-12">
<div class="input-group margin">
                <input type="text" id="ProjectIDText" name="ProjectIDText" placeholder="Enter Project ID" class="form-control" required="required"/>
                    <span class="input-group-btn">
                      <button type="button" id="GenerateProjectID" class="btn btn-info btn-flat">Generate Project ID</button>
                    </span>
              </div>
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
				  <input type="submit"  id="AddNewProject" value="Create Project" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>



		  
		  
		  
		    <div class="modal fade" id="modal-default-Client">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Client</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">

          <form id="ClientForm" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
            
				<div class="form-group">
                 <label>Client Name</label>
                <input type="text" name="ClientNameText" class="form-control" id="ClientNameText" placeholder="Enter Client Name" required="required">
              </div>
			
			<!-- /.form-group -->
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
         
			<div class="form-group">
                 <label>Client Abbreviation</label>
                <input type="text" id="ClientAbb" name="ClientAbb" class="form-control" placeholder="Enter Abbreviation" required="required">
              </div>
            </div>
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddClient" value="Add Client" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		    <div class="modal fade" id="modal-default-ProjDept">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Department</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">

          <form id="ProjDeptForm" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
            
				<div class="form-group">
                 <label>Department Name</label>
                <input type="text" name="DeptNameText" class="form-control" id="DeptNameText" placeholder="Enter Department Name" required="required">
              </div>
			
			<!-- /.form-group -->
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
         
			<div class="form-group">
                 <label>Department Abbreviation</label>
                <input type="text" id="DeptAbb" name="DeptAbb" class="form-control" placeholder="Enter Abbreviation" required="required">
              </div>
            </div>
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddProjDept" value="Add Department" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		   
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  

		  <div class="modal fade" id="modal-default-Modify">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modify Project</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php

		   ?>
            <form id="ModForm" enctype="multipart/form-data" method="post" class="form-horizontal" action="">
              <div class="box-body">
                <div class="form-group">
				 <label for="inputEmail3" class="col-sm-2 control-label">Allocated %</label>
				 <input type="hidden" name="rowId" id="rowId" value="" />
				  <div class="col-sm-10">
                  <input type="number" id="ModifiedAllocatedPer" min="0" value="" max="100" oninput="this.className = ''" name="ModifiedAllocatedPer"  required="required"  readonly="readonly"/>
				    <button type="button" onclick="addValMod()" id="IncrementVal" name="IncrementVal"><i class="fa fa-fw fa-plus"></i></button> &nbsp;  <button type="button" id="DecrementVal" onclick = "SubValMod()" name="DecrementVal"><i class="fa fa-fw fa-minus"></i></button>
                  </div>
              </div>
			    <div class="form-group">
				<label>Allocation Period From</a></label>
				<div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" name="DateFromEdit" class="form-control pull-right" required="required" id="datepicker2" placeholder="Pick a date" required="required" required>

                </div>
              </div>
			    <div class="form-group">
				<label>Allocation Period To</a></label>
				<div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" name="DateToEdit" class="form-control pull-right" required="required" id="datepicker3" placeholder="Pick a date" required="required" required>

                </div>
              </div>

              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->

          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="ModifyAlloc" value="Modify" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>


      <!-- Modify Future Designation -->


		<div class="modal fade" id="modal-default-ModifyDegn">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modify Designation</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php
			  include("config.php");
			  session_start();
			  $getName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name,job_role from employee_details where employee_id=$id");
			  $EmpNameRow = mysql_fetch_array($getName);
			  $EmpName = $EmpNameRow['name'];
			  $JobRole = $EmpNameRow['job_role'];
			  $getReportingMngr = mysql_query("select reporting_manager from resource_management_table where res_management_id =  '$resID1'");
			  $ManagerRow = mysql_fetch_array($getReportingMngr);
			  $repMngrid  = $ManagerRow['reporting_manager'];
			  $getMngrName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$repMngrid'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];


			  $resQuery = mysql_query("select res_management_id,employee_id,band,designation,level ,department,
				TIMESTAMPDIFF(MONTH,Created_Date_and_time,now()) as Months,project_id,date(effective_From) as effective_from

					from resource_management_table
						 WHERE employee_id=$id and effective_from > curdate()");
			  $tRow = mysql_fetch_array($resQuery);
			  $rId = $tRow['res_management_id'];
			  $band = $tRow['band'];
			  $desn = $tRow['designation'];
			  $level = $tRow['level'];
			  $monthsserved = $tRow['Months'];
			  $projname = $tRow['project_id'];
			  $Dept = $tRow['department'];
			  $efffromFut = $tRow['effective_from'];
			  $_SESSION['rID']=$rId;
			  ?>
			   <?php
			  include("config.php");
			  $query = mysql_query("Select designation_id,designation_desc from all_designations where designation_desc!='$desn' and designation_desc!=''");
			  $query1 = mysql_query("Select band_no,band_desc from all_bands where band_desc !='$band' and band_desc!=''");
			  $query2 = mysql_query("Select level_id,level_desc from all_levels where level_desc !='$level' and level_desc!=''");
			  $query3 = mysql_query("SELECT department_id,department FROM `all_departments` where department!='$Dept' and department!=''");
			 $query4 = mysql_query("select project_no,project_id,project_name from all_projects");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role not in ('Employee','System Admin','Accountant','HR') and employee_id not in ('$repMngrid','$id')");

			  ?>
           <form id="ResForm" method="post" action="">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">

                <label>Department</label>
				<input type="hidden" id="ResourceID" name="ResourceID" value = <?php echo $resID1 ; ?> >
                <select class="form-control select2" id="DeptSelect" name="DeptSelect" style="width: 100%;"  required>
				<option value="<?php echo $Dept ?>" selected ><?php echo $Dept ?></option>
				<?php

				while ($department = mysql_fetch_assoc($query3))
				{
				?>
                  <option value="<?php echo $department['department']  ?>"><?php echo $department['department']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
			  <label>Band</label>
                <select class="form-control select2" id="BandSelect" name="BandSelect" style="width: 100%;"  required>

				 <?php
			   if($band=='')
			   {
			   ?>
                  <option value="" selected>None</option>

              <?php
			   }
			   else
			   {
				?>
				  <option value="NIL">None</option>
				  	<option value="<?php echo $band ?>" selected ><?php echo $band ?></option>
				   <?php
			   }
				   ?>
                 <?php

				while ($bandrow = mysql_fetch_assoc($query1))
				{
				?>
                  <option value="<?php echo $bandrow['band_desc']  ?>"><?php echo $bandrow['band_desc']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  <div class="form-group">
			    <label>Effective From<span class="astrick">*</span> </label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" name="dateFrom" value="<?php echo  $efffromFut ?>" class="form-control pull-right" required="required" id="datepicker5" placeholder="Pick a date" required>

                </div>
				</div>
				</div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Designation</label>
                <select class="form-control select2" id="RoleSelect" name="RoleSelect" style="width: 100%;"  required="required" required>

                  <option  value="<?php echo $desn ?>" selected="selected"><?php echo $desn ?></option>
                 <?php

				while ($Design = mysql_fetch_assoc($query))
				{
				?>
                  <option value="<?php echo $Design['designation_desc']  ?>"><?php echo $Design['designation_desc']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
			     <label>Level </label>
               <select class="form-control select2" id="LevelSel" name="LevelSel"  style="width: 100%;" required >
			   <?php
			   if($level=='')
			   {
			   ?>
                  <option value="" selected>None</option>

              <?php
			   }
			   else
			   {
				?>
				  <option value="NIL">None</option>
				   <option selected="<?php echo $level ?>" ><?php echo $level ?></option>
				   <?php
			   }
				   ?>
				   <?php

				while ($levelQu = mysql_fetch_assoc($query2))
				{
				?>
                  <option value="<?php echo $levelQu['level_desc']  ?>"><?php echo $levelQu['level_desc']  ?></option>
                 <?php
				}
				 ?>
                </select>

              </div>
			   <div class="form-group">
                 <label>Reporting Manager</label>
              <select class="form-control select2" id="RepMgmr" name="RepMgmr" style="width: 100%;"   required>

                  <option  value="<?php echo $repMngrid ?>" selected="selected"><?php echo $repMngrName ?></option>
                 <?php

				while ($mng = mysql_fetch_assoc($MngQuery))
				{
				?>
                  <option value="<?php echo $mng['Employee_id']  ?>"><?php echo $mng['Employee_Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
  <!-- /.form-group -->
            </div>

            <!-- /.col -

            <!-- /.col -->
          </div>

</div>

        </div>

            </div>
            <!-- /.modal-content -->
          </div>
 <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="ModifyDesn" value="Modify" class="btn btn-primary" />
              </div>

</form>
</div>
</div>























		  <div class="modal fade" id="modal-default-Level">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Project</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">

            <form id="ProForm" enctype="multipart/form-data" method="post"  class="form-horizontal" >
              <div class="box-body">
                <div class="form-group">
				<input type="hidden" id="remVal" name="remVal" value=<?php echo $remPercent1; ?> />
                 <label>Project <a href="#" TITLE="Know your Project ID" id="additionalLevel" data-toggle="modal" data-target="#modal-default" ><i class="fa fa-fw fa-info-circle"></i></a></label>
               <select class="form-control select2" id="ProjSelect" name="ProjSelect" required="required" style="width: 100%;" >
					<option value="" disabled selected>Select a Project from Below</option>
                   <?php

				while ($Pro = mysql_fetch_assoc($query4))
				{
				?>
                  <option value="<?php echo $Pro['project_id']  ?>"><?php echo $Pro['project_name'].' : '.$Pro['project_id'];  ?></option>
                 <?php
				}
				 ?>
                </select>
				<br>
				<br>
				<label>Allocation Period From</a></label>
				<div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" name="dateFrom" autocomplete="off" class="form-control pull-right" required="required" id="datepicker1" placeholder="Pick a date" required="required" required>

                </div>
				<br>
				<?php
				$getPMSDate = mysql_query("select review_to_date from employee_performance_review_Dates where employee_id='$id' and is_active='Y'");
				$getPMSDateRow = mysql_fetch_array($getPMSDate);
				$Pmsdate  = $getPMSDateRow['review_to_date'];
				?>
				<input type="hidden" id="PMSDateVal" name="PMSDateVal" value="<?php echo $Pmsdate; ?>" />
				<label>Allocation Period To &nbsp;&nbsp; <input type="checkbox" value="Yes" id="checkPMSDate" name="checkPMSDate" >&nbsp;&nbsp; Set Appraisal review Date</input></label>
				<div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" name="dateUpto" autocomplete="off" class="form-control pull-right" required="required" id="datepicker" placeholder="Pick a date" required="required" required>

                </div>
				<br>

				<h4> &nbsp;&nbsp; Current Allocation : <strong style="color:darkslateblue;"><?php echo $CrrentPrecentage.' %'?></strong></h4>
				<br>
				<br>
				  <div class="form-group">
				 <label for="inputEmail3" class="col-sm-2 control-label">Allocation %</label>
				  <div class="col-sm-10">
                  <input type="number" id="AllocatedPer" min="0" value="0" max="100" oninput="this.className = ''" name="AllocatedPer"  required="required"  readonly="readonly"> </input>
				    <button type="button" onclick="addVal()" id="IncrementVal" name="IncrementVal"><i class="fa fa-fw fa-plus"></i></button> &nbsp;  <button type="button" id="DecrementVal" onclick = "SubVal()" name="DecrementVal"><i class="fa fa-fw fa-minus"></i></button>
                  </div>

              </div>
				<br>
				<br>

              </div>

              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->

          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNewProject" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddProj" value="Add Project" class="btn btn-primary" disabled />
              </div>
			  </form>
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



     <!-- Send Notification  -->
<?php
				$degn= $_SESSION['DesChange'];
				$rep = $_SESSION['RepMgmr'];
				$Project = $_SESSION['Proj'];
				$DepartmentVal = $_SESSION['Dept'];

				$effec= $_SESSION['effectivefrom'];
				$Cont = $degn.$rep.$DepartmentVal.$Project.' However, this will get effect from '.$effec.'.';
				?>
<div class="modal fade" id="modal-default-Notify">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Notify Resource</h4>
              </div>
              <div class="modal-body">
                  <textarea id="NotVal" name="NotVal" value="<?php echo $Cont." Please contact your Manager or HR for any Queries."  ?>" class="form-control" rows="7" col="19" placeholder="Enter ..."><?php echo $Cont." Please contact your Manager or HR for any Queries."  ?></textarea>
           <a href="#" id="notBtn" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Not" class="btn btn-danger pull-right">Skip Upload</a>
		   </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<input type="submit"  id="SendNotification" value="Send Notification" class="btn btn-primary"  />
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
<script>
$('#GenerateProjectID').on('click', function() {
		var Client = $('#ClientSel').val();
		var Department = $('#DeptSel').val();
		var ProjectCreatedFrom = $('#datepicker11').val();
		var Abb = $('#ProjectAbb').val();
		var ProjectSeries = $('#ProjectSeries').val();
		if(Client =='' || Department=='' || ProjectCreatedFrom =='' || Abb=='' || ProjectSeries =='')
		{
			alert("Please Fill all the Fields");
			return false;
		}
		else
		{
			GetProjectID();
			return true;
		}
	});
</script>
<script>
function GetProjectID() {
		var Client = $('#ClientSel').val();
		var Department = $('#DeptSel').val();
		var ProjectCreatedFrom = $('#datepicker11').val();
		var Abb = $('#ProjectAbb').val();
		var ProjectSeries = $('#ProjectSeries').val();
    //ajax request
   $.ajax({
      url: 'GenerateProjectID.php',
      type: 'post',
      data: {
      	'client' : Client,
      	'Department' : Department,
      	'projFrom' : ProjectCreatedFrom,
      	'projAbb' : Abb,
      	'ProjectSeries' : ProjectSeries,
      },
      success: function(response){
      	if (response != '' ) {
        
          $('#ProjectIDText').val(response);
      }
 	}
	});
   }
</script>
	<script type="text/javascript">
$(document).ready(function() {
    $("#ProjectForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ProjectForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "CreateNewProject.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
});
    </script>
	<script type="text/javascript">
$(document).ready(function() {
    $("#ClientForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ClientForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "AddClient.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
});
    </script>
		<script type="text/javascript">
$(document).ready(function() {
    $("#ProjDeptForm").submit(function(e) {
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ProjDeptForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "AddProjDept.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
});
    </script>

<script type="text/javascript">
$(document).ready(function() {
    $("#CloseNewProject").click(function() {
      location.reload();
        return true;
    });
});
</script>
<!-- Page script -->
<script type="text/javascript">
       $(document).on('click','#SendNotification',function(e) {
		   var data = $("#NotVal").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "SendNotification.php",
         success: function(data){
			location.reload();
			ajaxindicatorstop();
			//ShowSuccessNotification();



         }
});
 });
    </script>
	<script>
$(document).ready(function() {
	
    $("#ProForm").submit(function(e) {
		e.preventDefault();
		debugger;
		var from = document.getElementById("datepicker1").value;
		var to = document.getElementById("datepicker").value;
		var check = document.getElementById("AllocatedPer").value;
		if(check == 0)
		{
			alert("Please Assign a value greater than Zero");
			return false;
		}
		else if(Date.parse(from) > Date.parse(to))
		{
			alert("From date Should be lesser than To Date");
			return false;
		}
		else
		{
			ajaxindicatorstart("One Moment Please..");
			e.preventDefault();
			var data = $("#ProForm").serialize();

			$.ajax({
         data: data,
         type: "post",
         url: "DateCheck.php",
         success: function(data)
		 {
			 if(data=='pos')
			 {
					alert("Allocated Successfully!");
					AddProjectforEmp();
			 }
			 else
			 {
				alert("Resource isn't Available for the Given dates!"); 
				ajaxindicatorstop();
			 }
         }
			});
		}
		
		
	});
	});
</script>

<script>
$(document).ready(function() {
	
    $("#ModForm").submit(function(e) {
		e.preventDefault();
		debugger;
		var from = document.getElementById("datepicker2").value;
		var to = document.getElementById("datepicker3").value;
		var check = document.getElementById("ModifiedAllocatedPer").value;
		if(check == 0)
		{
			alert("Please Assign a value greater than Zero");
			return false;
		}
		else if(Date.parse(from) > Date.parse(to))
		{
			alert("From date Should be lesser than To Date");
			return false;
		}
		else
		{
			ajaxindicatorstart("One Moment Please..");
			e.preventDefault();
			var data = $("#ModForm").serialize();

			$.ajax({
         data: data,
         type: "post",
         url: "DateCheckMod.php",
         success: function(data)
		 {
			 if(data=='pos')
			 {
					alert("Resource Available");
					EditProjectforEmp();
			 }
			 else
			 {
				alert("Resource isn't Available for the Given dates!"); 
				ajaxindicatorstop();
			 }
         }
			});
		}
		
		
	});
	});
</script>

<script type="text/javascript">
function AddProjectforEmp() {

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

}
    </script>
	<script type="text/javascript">
  function EditProjectforEmp()
  {
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
	 $('#datepicker2').datepicker({
      autoclose: true
	   
    })
	 $('#datepicker3').datepicker({
      autoclose: true
	  
    })
	$('#datepicker5').datepicker({
      autoclose: true,
	   startDate: '+1d'
    })
	$('#datepicker11').datepicker({
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
       trid = $(this).attr('id');
       trname = $(this).attr('name');
	   datefromval = $(this).find('.fromDate').text();
       datetoval = $(this).find('.toDate').text();

       rem = $('#remVal1').val();

		$('#datepicker2').val(datefromval);
		$('#datepicker3').val(datetoval);

	   maxval =parseInt(trid, 10) +parseInt(rem, 10);
		$('#ModifiedAllocatedPer').val(trid);
		$('#rowId').val(trname);
		// table row ID
       //alert(trid);
  });
});
	</script>
	<script>

	$(function() {
  var bid, trid;
  $('#ActProj1 tr').click(function() {
       trid = $(this).attr('id');
       trname = $(this).attr('name');
	 datefromval = $(this).find('.fromDateFut').text();
	 datetoval = $(this).find('.toDateFut').text();
       rem = $('#remVal1').val();

	   	$('#datepicker2').val(datefromval);
		$('#datepicker3').val(datetoval);

	   maxval =parseInt(trid, 10) +parseInt(rem, 10);
		$('#ModifiedAllocatedPer').val(trid);
		$('#rowId').val(trname);
		// table row ID
       //alert(trid);
  });
});
	</script>
	<script type="text/javascript">
$(document).ready(function() {
    $("#ResForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ResForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "ModifyFutureResource.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
});
    </script>
	<script type="text/javascript">
	$('#ProjSelect').one('change', function() {

			$('#AddProj').prop('disabled', false);
	});

	</script>

	<script type="text/javascript">
   $(document).on('submit','#AddPro1j',function(e) {

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



	
<script>
$("#checkPMSDate").change(function() {
	if(this.checked) {
     var hv = $('#PMSDateVal').val();
	 $('#datepicker').val(hv);
	 $("#datepicker").attr('disabled', true);
    }
	else
	{
		 $('#datepicker').val('');
		 $("#datepicker").attr('disabled', false);
	}

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
<?php
}
else
{
	header("Location: ../forms/Mainlogin.php");
}
?>