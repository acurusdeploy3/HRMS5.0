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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Resource Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.html"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="ResourceMgmtCount.php">Resource Management</a></li>
        <li class="active">Modify Resource</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<?php
		session_start();
		
		$id = $_GET['id'];
	$_SESSION['EmpId']=$id;
		?>
		<input type="hidden" value ="<?php echo $id  ?>" id="EmpId">
	 <?php
			  include("config.php");
			  session_start();
			  $_SESSION['DesChange']='';
			  $_SESSION['RepMgmr']='';
			  $_SESSION['Proj']='';
			  $_SESSION['Dept']='';
			  $getName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id=$id");
			  $EmpNameRow = mysql_fetch_array($getName);
			  $EmpName = $EmpNameRow['name'];
			  $getReportingMngr = mysql_query("select reporting_manager from resource_management_table where employee_id = $id and is_Active='Y'");
			  $ManagerRow = mysql_fetch_array($getReportingMngr);
			  $repMngrid  = $ManagerRow['reporting_manager'];
			  $getMngrName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$repMngrid'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];
			  
			  
			  $resQuery = mysql_query("select res_management_id,employee_id,band,designation,level ,department,
				TIMESTAMPDIFF(MONTH,Created_Date_and_time,now()) as Months,project_id

					from resource_management_table
						 WHERE employee_id=$id and is_Active='Y'");
			  $tRow = mysql_fetch_array($resQuery);
			  $rId = $tRow['res_management_id']; 
			  $band = $tRow['band']; 
			  $desn = $tRow['designation']; 
			  $level = $tRow['level']; 
			  $monthsserved = $tRow['Months']; 
			  $projname = $tRow['project_id']; 
			  $Dept = $tRow['department']; 
			  $_SESSION['rID']=$rId;
			  ?>
			   <?php
			  include("config.php");
			  $query = mysql_query("Select designation_id,designation_desc from all_designations where designation_desc!='$desn' and designation_desc!=''");
			  $query1 = mysql_query("Select band_no,band_desc from all_bands where band_desc !='$band' and band_desc!=''");
			  $query2 = mysql_query("Select level_id,level_desc from all_levels where level_desc !='$level' and level_desc!=''");
			  $query3 = mysql_query("SELECT department_id,department FROM `all_departments` where department!='$Dept' and department!=''");
			  $query4 = mysql_query("select project_no,project_id,project_name from all_projects where project_id!='$projname'");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager' and employee_id not in ('$repMngrid','$id')");
			  
			  ?>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
		<div class="box-header">
			
			 <table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
				<button OnClick="window.history.go(-1); return false;" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
			  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
             <h4 class="box-title"><strong><?php echo $EmpName ?> : <?php echo $id  ?></strong></h4>
			  <br>
			  <div class="box-tools pull-right">
          </div>
			  
            </div>
        </div>
		   
		  
		
	
        <!-- /.box-header -->
		<form id="ResForm" method="post" action="InsertModifiedResource.php">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
				
                <label>Department <a href="#" id="additionalDept" title="Click to Add More Departments" data-toggle="modal" data-target="#modal-default-Dept"><i class="fa fa-fw fa-plus"></i></a></label>
				<input type="hidden" id="trainid" name="trainid" value = <?php echo $trainingid ; ?> >
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
			  <label>Band <a href="#" title="Click to Add More Bands" id="additionalBand" data-toggle="modal" data-target="#modal-default-Band"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="BandSelect" name="BandSelect" style="width: 100%;"  required>
				<option value="">None</option>
				<option value="<?php echo $band ?>" selected ><?php echo $band ?></option>
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
				  
                  <input type="text" name="dateFrom" class="form-control pull-right" required="required" id="datepicker1" placeholder="Pick a date" required>
				
                </div>
				</div>
				<div class="form-group">
				 <input type="submit" style="width:50%" id="ModifyResource" value="Modify Resource" class="btn btn-block btn-primary"/>

				 
			 </div>
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Role <a href="#" title="Click to Add More Roles" id="additionalRole" data-toggle="modal" data-target="#modal-default-Role" ><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="RoleSelect" name="RoleSelect" style="width: 100%;"   >
					
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
			     <label>Level <a href="#" title="Click to Add More Levels" id="additionalLevel" data-toggle="modal" data-target="#modal-default-Level"><i class="fa fa-fw fa-plus"></i></a></label>
               <select class="form-control select2" id="LevelSel" name="LevelSel"  style="width: 100%;" required >
                  <option value="">None</option>
                  <option selected="<?php echo $level ?>" ><?php echo $level ?></option>
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

			   <div class="col-md-6">
			
		
			  </tr>
			  </tbody>
			  </table>	  
</div>	
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
		  
          <!-- /.modal-dialog -->
		  
		  
		   <!-- /.modal-Notification -->

	<!--  ROle Modal  -->	  
		  <div class="modal fade" id="modal-default-Role">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Role</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="roleForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Role Name</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputRole" name="inputRole" placeholder="Enter Role" />
                  </div>
                </div>
               
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addRolebtn"  class="btn btn-primary">Add Role</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  <!--  Band Modal  -->	  
		  <div class="modal fade" id="modal-default-Band">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Band</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="BandForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Band :</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputBand" name="inputBand" placeholder="Enter Band" />
                    
                  </div>
                </div>
		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Band Description:</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputBandDesc" name="inputBandDesc" placeholder="Enter Band Desc" />
                    
                  </div>
                </div>				
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeBand" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addBandbtn"  class="btn btn-primary">Add Band</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		  
		  
		  
		  
		  
		  
		   <!--  Dept Modal  -->	  
		  <div class="modal fade" id="modal-default-Dept">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Department</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Department</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputDept" name="inputDept" placeholder="Enter Department" />
                    
                  </div>
                </div>			
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeDept" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addDeptbtn"  class="btn btn-primary">Add Department</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		  
		  
		   <!--  Level Modal  -->	  
		  
		  
		  
		  
		   <div class="modal fade" id="modal-default-Level">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Level</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Level</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputLevel" name="inputLevel" placeholder="Enter Level" />
                    
                  </div>
                </div>			
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeLvl" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addLvlbtn"  class="btn btn-primary">Add Level</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		  
		 <!-- alert Modal --> 
		  
		  
		  <input id="alertModal"  style = "display:none" type="submit" data-toggle="modal" data-target="#modal-warning" value="Send for Approval">
		  
		  
		  <div class="modal fade" id="modal-warning1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Resource Management</h4>
              </div>
              <div class="modal-body">
                <p>Please Fill in the Required Fields!</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
        </div>
</form>
 <div class="col-md-6">
			
			  </div>
          <!-- /.row -->
		  
		   </section>
        </div>
        <!-- /.box-body -->
     
	
      <!-- /.box --
      <!-- /.row -->

   
    <!-- /.content -->
 
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
<script>

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
	   startDate: 'd'
	  
    })
	 $('#datepicker1').datepicker({
	 startDate: 'd',
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
function CallFunc()
{
	var Dept = document.getElementById("DeptSelect").value;
	var Band = document.getElementById("BandSelect").value;
	var Mgmr = document.getElementById("RepMgmr").value;
	var Rol = document.getElementById("RoleSelect").value;
	var Lev = document.getElementById("LevelSel").value;
	var Proj = document.getElementById("ProjSelect").value;
	var Dat = document.getElementById("datepicker1").value;
	
	if(Dept==''|| Mgmr==''|| Rol==''|| Dat=='')
	{
		document.getElementById("alertModal").click();
		return false;
	}
	else
	{
		ModifyRes();
	}
	
}
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
	<script type="text/javascript">
   $(document).on('click','#TestTraining',function(e) {
     
	ajaxindicatorstart("Please Wait..");
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
       $(document).on('click','#addRolebtn',function(e) {
		   var data = $("#inputRole").serialize();
  //var data = $("#roleForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addRole.php",
         success: function(data){
			//alert(data);
			AdditionalRole();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalRole() {
          
			var modal = document.getElementById('modal-default-Role');
            var ddl = document.getElementById("RoleSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputRole").value;
            option.value = document.getElementById("inputRole").value;
            ddl.options.add(option);
			document.getElementById("closeRole").click();
			document.getElementById("inputRole").value="";
        
			     
        }
    </script>
	
	
	
	<script type="text/javascript">
       $(document).on('click','#addBandbtn',function(e) {
		 //  var data = $("#inputBand").serialize();
  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addBand.php",
         success: function(data){
			//alert(data);
			AdditionalBand();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	<script type="text/javascript">
   $(document).on('click','#ModifyResource1',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ResForm").serialize();
   alert('Hi');
  $.ajax({
         data: data,
         type: "post",
         url: "InsertModifiedResource.php",
         success: function(data){
			 alert('HEllo');
		 window.location.href = "NotifyResource.php";
		ajaxindicatorstop();
			
         }
});

   });
    </script>
	<script type="text/javascript">
       function AdditionalBand() {
          
			var modal = document.getElementById('modal-default-Band');
            var ddl = document.getElementById("BandSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputBandDesc").value;
            option.value = document.getElementById("inputBandDesc").value;
            ddl.options.add(option);
			document.getElementById("closeBand").click();
			document.getElementById("inputBandDesc").value="";
			document.getElementById("inputBand").value="";
        
			     
        }
    </script>
	
	
	<script type="text/javascript">
       $(document).on('click','#addDeptbtn',function(e) {
		   var data = $("#inputDept").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddDept.php",
         success: function(data){
			AdditionalDept();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalDept() {
          
			var modal = document.getElementById('modal-default-Dept');
            var ddl = document.getElementById("DeptSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDept").value;
            option.value = document.getElementById("inputDept").value;
            ddl.options.add(option);
			document.getElementById("closeDept").click();
			document.getElementById("inputDept").value="";
        
			     
        }
    </script>
	
	
	
	
	
	
	
	
	<script type="text/javascript">
       $(document).on('click','#addLvlbtn',function(e) {
		   var data = $("#inputLevel").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddLevel.php",
         success: function(data){
			//alert(data);
			Additionallev();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function Additionallev() {
          
			var modal = document.getElementById('modal-default-Level');
            var ddl = document.getElementById("LevelSel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputLevel").value;
            option.value = document.getElementById("inputLevel").value;
            ddl.options.add(option);
			document.getElementById("closeLvl").click();
			document.getElementById("inputLevel").value="";
        
			     
        }
    </script>
	
	
	
	
</body>
</html>
