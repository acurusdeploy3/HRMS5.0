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

      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="ResourceMgmtCount.php">Resource Management</a></li>
        <li class="active">View Resource</li>
      </ol>
    </section>
	<?php
		$id = $_GET['id'];
		session_start();
		$_SESSION['EmpId']=$id;
		?>
	 <?php
			  include("config.php");
			  include("ModificationFunc.php");
			  $ExpireQuery = mysql_query("select id,project_id from employee_projects where allocated_to < now() and employee_id='$id' and is_active='Y'");
			  $cntexp = mysql_num_rows($ExpireQuery);
			  if($cntexp>0)
			  {
			  while($idRow= mysql_fetch_assoc($ExpireQuery))
			  {
				$projname= mysql_query("select project_name from all_projects where project_id='".$idRow['project_id']."'");
				$projRow = mysql_fetch_array($projname);
				 $project = $projRow['project_name'];
				$tabname = 'employee_projects';
				$primKey = 'id';
				StoreDatainHistory($idRow['id'], $tabname,$primKey);
				$sql="update employee_projects set is_active='N',modified_Date_and_time=now(),modified_by='$name' where id=".$idRow['id']."";
				$result=mysql_query($sql);
				$emailrec  = mysql_query ("SELECT distinct(contact_email) as contact_email,reporting_manager_contact_email FROM notification_contact_email where employee_id=$id");

				$emRow = mysql_fetch_array($emailrec);
				$emailval = $emRow['contact_email'];
				$repemailval = $emRow['reporting_manager_contact_email'];
				$content = "By the end of the day, you will be relieved from project ".$project.". We appreciate the effort you had put in to make the project unimaginably successful, and we request you to do the same for all the forthcoming projects. Kindly ping in with your Manager or HR for any Queries.";
	
	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
                 $emailval => $emailval,
				 $repemailval  => $repemailval,
                 
            );
			
			require 'PHPMailerAutoload.php';

            
            $mail = new PHPMailer();

            // Set up SMTP
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'Acurus Solutions';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear Employee,</td>
					</tr>

					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>". $content ."</td>
					</tr>

					
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Regards,</td>
					</tr>

					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Acurus HR.</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>P.S : Do not reply to this Mail.</td>
					</tr>
				</tbody>
			
	</table>
	</body></html>");
            $mail->AltBody = 'This is a plain-text message body';
            //$mail->addAttachment('images/phpmailer_mini.gif');

            // Set the from/to
            $mail->setFrom($username, $senderName);
            // foreach ($recipients as $address => $name) {
                // $mail->addAddress($address, $name);
            // }
			foreach ($recipients as $address => $name) {
                $mail->addAddress($address, $name);
             }
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } 
				
				
				
				
				
				
				
				
				
				
			  }

			  }			  
			  
			  $getName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id=$id");
			  $EmpNameRow = mysql_fetch_array($getName);
			  $EmpName = $EmpNameRow['name'];
			  $getReportingMngr = mysql_query("select reporting_manager from resource_management_table where employee_id = $id");
			 $getReportingMngr = mysql_query("select reporting_manager from resource_management_table where employee_id = $id and is_Active='Y'");
			  $ManagerRow = mysql_fetch_array($getReportingMngr);
			  $repMngrid  = $ManagerRow['reporting_manager'];
			  $getMngrName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$repMngrid'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];
			  
			  $getDOJ = mysql_query("select Date_format(date_joined,'%d-%b-%Y') as doj from employee_details where employee_id=$id");
			  $getDOJRow = mysql_fetch_array($getDOJ);
			  $DOJ = $getDOJRow['doj'];
			  
			  
			  $resQuery = mysql_query("select res_management_id,employee_id,band,designation,level,department,
				date_format(effective_from,'%d-%b-%Y') as  Effective_From,project_id,reporting_manager,signed_loa_doc


					from resource_management_table
						 WHERE employee_id=$id and is_Active='Y'");
			  $tRow = mysql_fetch_array($resQuery);
			  $resID = $tRow['res_management_id']; 
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
			  
			  session_Start();
			  
			  $_SESSION['rId']=$resID;
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
                    <button OnClick="window.location='ResourceMgmtCount.php'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>
          <h2 class="page-header">
           <?php echo $EmpName ?> : <?php echo $id  ?>
            <small class="pull-right"><?php echo  'Date Joined : '.$DOJ  ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
         <div class="row">
        <div class="col-xs-12">
        
            <div class="box-header">
              <h3 class="box-title">Active Designation</h3>

              <div class="box-tools">
              </div>
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
                  <th>Office Order</th>
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
				  if($currLoa=='0')
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
				   <td><?php echo $currLoa ?></td>
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
		    include("config.php");
		  $ProQuery = mysql_query("select a.id,a.project_id,b.project_name,a.employee_id,date_format(a.created_date_and_time,'%d - %b - %Y') as
created_date_and_time,a.created_by,concat(c.first_name,' ',c.last_name) as allocated_by,TIMESTAMPDIFF(MONTH,a.allocated_from,now()) as months_served,allocated_percentage,date_format(a.allocated_from,'%d - %b - %Y') as allocated_from,date_format(a.allocated_to,'%d - %b - %Y') as allocated_to
 from employee_projects a left join all_projects b on
a.project_id=b.project_id left join employee_details c on
c.employee_id= a.created_by

 where a.employee_id='$id' and a.is_active='Y' and allocated_from <=now()
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
					 

						 ?>
		  <div class="row">
		  
		  <input type="hidden" id="remVal1" name="remVal1" value=<?php echo $remPercent1; ?> />
		   <div class="col-xs-12">
		   <?php
		   if($percent1!=100)
		   {
		   ?>
          <a href="#" target="_blank" data-toggle="modal" data-target="#modal-default-Level" class="btn btn-success pull-left">Add Project</a>
          <a href="#" id="notBtn" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Not" class="btn btn-danger pull-right">Skip Upload</a>
		  
		  <?php
		   }
		  ?>
        </div>
		<br>
		<br>
        <div class="col-xs-12">
         
            <div class="box-header">
              <h3 class="box-title">Active Project(s)
		</h3>
		 <h4 class="pull-right">Resource Usage : <?php echo  $percent1.' %';  ?></h4>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="ActProj" id="ActProj" class="table table-hover">
                <tr>
                  <th>Project</th>
                  <th>Allocated %</th>
				  <th>Allocated From</th>
                  <th>Allocated To</th>
				  <th>Date of Allocation</th>
				  <th>Months Served</th>
                  <th>Deallocate</th>
                  <th>Modify Allocation %</th>
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
                  
                  <td><?php echo $ProjRes['allocated_from']; ?></td>
                  <td><?php echo $ProjRes['allocated_to']; ?></td>
				  <td><?php echo $ProjRes['created_date_and_time']; ?></td>
                  <td><?php echo $ProjRes['months_served']; ?></td>
				   <td><a href="RemovefromProj.php?id=<?php echo $ProjRes['id'] ?>" OnClick="return confirm('Are you Sure you want to Deallocate?');"><img alt='User' src='../../dist/img/img_318906.png' title="Deallocate Resource from Project" width='18px' height='18px' /></a></td>
				  <td><a href="#" id="additionalBand" data-toggle="modal" data-target="#modal-default-Modify"><i class="fa fa-fw fa-eraser"></i></a></td> 
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
        </div>
		 
        <!-- /.col -->
      </div>
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	 <!--Future Projects -->
	  
	  
	  
	  
	    <?php
		    include("config.php");
		  $FuProQuery = mysql_query("select a.id,a.project_id,b.project_name,a.employee_id,date_format(a.created_date_and_time,'%d - %b - %Y') as
created_date_and_time,a.created_by,concat(c.first_name,' ',c.last_name) as allocated_by,TIMESTAMPDIFF(MONTH,a.allocated_from,now()) as months_served,allocated_percentage,date_format(a.allocated_from,'%d - %b - %Y') as allocated_from,date_format(a.allocated_to,'%d - %b - %Y') as allocated_to
 from employee_projects a left join all_projects b on
a.project_id=b.project_id left join employee_details c on
c.employee_id= a.created_by

 where a.employee_id='$id' and a.is_active='Y' and allocated_from > now()
");
					 ?>
		  <div class="row">
		 
		   
		<br>
		<br>
        <div class="col-xs-12">
         
            <div class="box-header">
              <h3 class="box-title">Future Project(s)
		</h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="ActProj1" id="ActProj1" class="table table-hover">
                <tr>
                  <th>Project</th>
				  <th>Allocated From</th>
                  <th>Allocated To</th>
				  <th>Date of Allocation</th>
				  <th>Deallocate</th>
                 
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
                 
                  
                  <td><?php echo $FuProjRes['allocated_from']; ?></td>
                  <td><?php echo $FuProjRes['allocated_to']; ?></td>
                  <td><?php echo $FuProjRes['created_date_and_time']; ?></td>
			
				   <td><a href="RemovefromProj.php?id=<?php echo $FuProjRes['id'] ?>" OnClick="return confirm('Are you Sure you want to Deallocate?');"><img alt='User' src='../../dist/img/img_318906.png' title="Deallocate Resource from Project" width='18px' height='18px' /></a></td>
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
        </div>
		 
        <!-- /.col -->
      </div>
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
      <!-- /.row -->
<div class="row no-print">
  </div>
      <!-- Table row -->
	  <br>
	  <br>
      <div class="row">
        <div class="col-xs-12 table-responsive">
		<div class="box-header">
              <h3 class="box-title">Resource Designation History</h3>

              <div class="box-tools">
              </div>
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
			<th>Office Order</th>
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
	  <div class="box-header">
              <h3 class="box-title">Project Allocation History</h3>

              <div class="box-tools">
              </div>
            </div>
			<?php
				$getProHistory = mysql_query("select a.id,a.project_id,b.project_name,a.employee_id,date_format(a.date_archived,'%d - %b - %Y') as
date_archived,a.created_by,concat(c.first_name,' ',c.last_name) as allocated_by,TIMESTAMPDIFF(MONTH,a.created_date_and_time,date_archived) as months_served
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
		  
		  
		  
		  
		  
		   <div class="modal fade" id="modal-default-Deallocate">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Set Remainder</h4>
              </div>
            <div class="modal-body">
                <p>Do you want to set a Remainder for OO Upload for <?php echo $EmpName ?></p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeLvl" OnClick="window.location='ViewResource.php?id=<?php echo $id ?>'" class="btn btn-default pull-left" data-dismiss="modal">Not Required</button>
                 <button type="button" id="SetRemainder" OnClick="window.location='SetRemainderforUpload.php'" class="btn btn-primary">Yes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		  <div class="modal fade" id="modal-default-Modify">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modify Allocation</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php  
		   
		   ?>
            <form id="ModForm" enctype="multipart/form-data" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
				 <label for="inputEmail3" class="col-sm-2 control-label">Allocated %</label>
				 <input type="hidden" name="rowId" id="rowId" value="" />
				  <div class="col-sm-10">
                  <input type="number" id="ModifiedAllocatedPer" min="0" value="" max="" oninput="this.className = ''" name="ModifiedAllocatedPer"  required="required"  readonly="readonly"/>
				    <button type="button" onclick="addValMod()" id="IncrementVal" name="IncrementVal"><i class="fa fa-fw fa-plus"></i></button> &nbsp;  <button type="button" id="DecrementVal" onclick = "SubValMod()" name="DecrementVal"><i class="fa fa-fw fa-minus"></i></button>
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
           
            <form id="ProForm" enctype="multipart/form-data" method="post" class="form-horizontal" action="InsertNewProj.php">
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
                  <option value="<?php echo $Pro['project_id']  ?>"><?php echo $Pro['project_id']  ?></option>
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
				  
                  <input type="text" name="dateFrom" class="form-control pull-right" required="required" id="datepicker1" placeholder="Pick a date" required="required" required>
				
                </div>
				<br>
				<label>Allocation Period To</a></label>
				<div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
				  
                  <input type="text" name="dateUpto" class="form-control pull-right" required="required" id="datepicker" placeholder="Pick a date" required="required" required>
				
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
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
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
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
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
	   
       rem = $('#remVal1').val();
	   
	   maxval =parseInt(trid, 10) +parseInt(rem, 10);
		$('#ModifiedAllocatedPer').val(trid);
		$('#rowId').val(trname);
		$("#ModifiedAllocatedPer").attr({
		"max" : maxval   
    });
		// table row ID 
       //alert(trid);
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
