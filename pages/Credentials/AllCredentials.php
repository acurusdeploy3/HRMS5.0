<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Credential Management</title>
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
   <link href="toastr/toastr.css" rel="stylesheet" type="text/css" />
  <script src="../../dist/js/loader.js"></script>
  <style>
.text-red {
  color:red;
}
</style>

  <!-- Font Awesome -->
 
  <!-- DataTables -->

  <!-- Theme style -->
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  

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
        Credential Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        
        <li class="active">All Credentials</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<form id="resForm" method="post">
      <div class="row">
        <div class="col-xs-12">
		
               <!-- /.box -->
		<div class="box">
		<div class="box-header">
			<br>
			<table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
               <h4 class="box-title" style="margin-left:15px;"><strong>Expiring / Expired Item(s)</strong></h4>
			  <br>
			  
			  
            </div>
		 <br>
            
             
              
			<?php
			include("config.php");
			session_start();
			$userid = $_SESSION['login_user'];
			$query = mysqli_query($db,"select a.id,category,physical_location,logical_location,user_name,comments,last_renewed_date,

timestampdiff(day,curdate(),next_renewal_date) as days,concat(First_Name,' ',last_name,' : ',employee_id) as created_by from credentials_master a

join employee_details b on a.created_by=b.employee_id
where a.is_active='Y'

and timestampdiff(day,curdate(),next_renewal_date) <=10 and a.id in (select item_id from credentials_ownership where owner_id=$userid)");
				$count = mysqli_num_rows($query);
			?>
   </form>        

            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  
				  <th>Category</th>
			      <th>Phy Location / App Name</th>
                  <th>Logical Location</th>
				  <th>User Name</th>
				  <th>Created By </th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
					$i=1;
				while ($queuedItems = mysqli_fetch_assoc($query))
					{
				?>
                <tr>
                  
                  <td><?php echo $queuedItems['category'];  ?></td>
                  <td><?php echo $queuedItems['physical_location'];  ?></td>
                  <td><?php echo $queuedItems['logical_location'];  ?></td>
                  <td><?php echo $queuedItems['user_name'];  ?></td>
                  <td><?php echo $queuedItems['created_by'];  ?></td>
				  <?php 
				  
				  if($queuedItems['days']>10)
				  {
					  echo '<td><span class="label label-success">Active</span></td>';
				  }
				  elseif($queuedItems['days']<=10 && $queuedItems['days']>0)
				  {
					  echo '<td><span class="label label-warning">Expires in '.$queuedItems['days'].' day(s)</span></td>';
				  }
				  
				  else
				  {
					  echo '<td><span class="label label-danger">Expired</span></td>';  
				  }
					  
				  
				  ?>
				 <td> <a href="#" class="updateItembtn" data-src="<?php echo $queuedItems['id']; ?>" title="Renew Item" id="update" data-toggle="modal" data-target="#modal-default-update"><i class="fa fa-fw fa-refresh"></i></a> | 
				 
					<a href="#" class="EditItem" data-src="<?php echo $queuedItems['id']; ?>" id="EditItem" title="Edit Item" data-toggle="modal" data-target="#modal-default-Edit"><img alt='User' src='../../dist/img/editimg.png' width='18px' height='18px' /></a></td>
                </tr>
                <?php
				$i++;
					}
				}
					?>
                </tbody>
              </table>
			   
            </div>
            <!-- /.box-body -->
		<div class="box-header">
			<br>
			<table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
         
					<a href="#" target="_blank" data-toggle="modal" style="margin-left: 15px;" data-target="#modal-default-Create" class="btn btn-block btn-primary pull-left">Add New Item <i class="fa fa-fw fa-plus"></i></a>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
               <h4 class="box-title" style="margin-left:15px;"><strong>All Item(s)</strong></h4>
			   <input type="hidden" name="ItemIdHidden" id="ItemIdHidden" />
			  <br>
			  
			  
            </div>
		 <br>
            
             
              
			<?php
			include("config.php");
			session_start();
			$userid = $_SESSION['login_user'];
			
			$query1 = mysqli_query($db,"select a.id,category,physical_location,logical_location,user_name,comments,last_renewed_date,

timestampdiff(day,curdate(),next_renewal_date) as days,

concat(First_Name,' ',last_name,' : ',employee_id) as created_by from credentials_master a

join employee_details b on a.created_by=b.employee_id
where a.is_active='Y'

 and a.id in (select item_id from credentials_ownership where owner_id=$userid)");
				$count = mysqli_num_rows($query1);
			?>
   </form>        

            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Category</th>
			      <th>Phy Location / App Name</th>
                  
				  <th>View / Edit Owners</th>
				  <th>Created By</th>
				  <th>Last Renewal Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
					$i=1;
				while ($CreatedItems = mysqli_fetch_assoc($query1))
					{
				?>
                <tr>
					
                  <td><?php echo $CreatedItems['category'];  ?></td>
                  <td><?php echo $CreatedItems['physical_location'];  ?></td>
                  
				  <td><a href="#" class="ViewOwners" data-src="<?php echo $CreatedItems['id']; ?>" id="ViewOwners" title="View Owners" data-toggle="modal" data-target="#modal-default-ViewOwners">Click to View</a> </td>
                  	<td><?php echo $CreatedItems['created_by'];  ?></td>
                <td><?php echo ($CreatedItems['last_renewed_date']=='0001-01-01')?'--':$CreatedItems['last_renewed_date'];  ?></td>
				  
				  <?php 
				  
				  if($CreatedItems['days']>10)
				  {
					  echo '<td><span class="label label-success">Expires in '.$CreatedItems['days'].' day(s)</span></td>';
				  }
				  elseif($CreatedItems['days']<=10 && $CreatedItems['days']>0)
				  {
					  echo '<td><span class="label label-warning">Expires in '.$CreatedItems['days'].' day(s)</span></td>';
				  }
				  
				  else
				  {
					  echo '<td><span class="label label-danger">Expired</span></td>';  
				  }
					  
				  
				  ?>
				  <?php 
				  $iteid = $CreatedItems['id'];
					$isOwnerRow = mysqli_query($db,"select * from credentials_ownership where item_id='$iteid' and owner_id='$userid'");
					$isOwner = mysqli_num_rows($isOwnerRow);
					if($isOwner==0)
					{
				  ?>
				 <td><a href="#" class="EditItem" data-src="<?php echo $CreatedItems['id']; ?>" id="EditItem" title="Edit Item" data-toggle="modal" data-target="#modal-default-Edit"><img alt='User' src='../../dist/img/editimg.png' width='18px' height='18px' /></a> | 
					<a href="#" id="TestID" class="deleteItem" data-src="<?php echo $CreatedItems['id']; ?>" ><img alt='User' src='../tables/Images/notrep.png' width='18px' height='18px' /></a> |
					<a href="#" class="NotifyOwner" id="NotifyOwner" data-src="<?php echo $CreatedItems['id']; ?>" title="Notify Owner to update password" data-toggle="modal" data-target="#modal-default-notify"><i class="fa fa-fw fa-bell"></i></a>
					</td>
					<?php } else { ?>
				<td><a href="#" class="EditItem" data-src="<?php echo $CreatedItems['id']; ?>" id="EditItem" title="Edit Item" data-toggle="modal" data-target="#modal-default-Edit"><img alt='User' src='../../dist/img/editimg.png' width='18px' height='18px' /></a> | 
					<a href="#" id="TestID" class="deleteItem" data-src="<?php echo $CreatedItems['id']; ?>" ><img alt='User' src='../tables/Images/notrep.png' width='18px' height='18px' /></a>
					
					</td>	
					<?php } ?>
                </tr>
                <?php
				$i++;
					}
				}
				else
				{
					?>
					 <tr>
					   <td>No Results Found</td>
					 </tr>
				<?php	 
				}
					?>
                </tbody>
              </table>
			   
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
	  <div class="modal fade" id="modal-default-Create">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Item</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">

          <form id="ItemForm" method="post">
        <div class="box-body">
          <div class="row">
		  <?php
			  include("config.php");
			  $getCategoryNames = mysqli_query($db,"select category from all_categories");
			  $getExpiryCycle = mysqli_query($db,"select cycle from expiry_cycle");
			  $getEmployees = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name) as Name FROM `employee_details` where is_Active='Y'");
			  ?>
		  <div class="col-md-6">
              <div class="form-group">
			  
                 <label>Category<span class="text-red">*</span></label>
                <select class="form-control select2" id="CategorySel" name="CategorySel" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php

				while ($getCategoryNamesRow = mysqli_fetch_assoc($getCategoryNames))
				{
				?>
                  <option value="<?php echo $getCategoryNamesRow['category']  ?>"><?php echo $getCategoryNamesRow['category']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			</div>	
		
			<div class="col-md-6">
					<div class="form-group">
						 <label id="phyloclbl">Physical Location <span class="text-red">*</span></label>
						<input type="text" name="PhysicalLocationText" class="form-control" required id="PhysicalLocationText" placeholder="Enter Physical Location">
					  </div>
			</div>	
			<div class="col-md-6">
					<div class="form-group">
						 <label>Logical Location <span class="text-red">*</span></label>
						<input type="text" name="LogicalLocationText" class="form-control" required id="LogicalLocationText" placeholder="Enter Logical Location">
					  </div>
			</div>
			<div class="col-md-6">
					<div class="form-group">
						 <label>UserName <span class="text-red">*</span></label>
						<input type="text" name="userNameText" class="form-control" required id="userNameText" placeholder="Enter UserName">
					  </div>
			</div>
			<div class="col-md-6">
              <div class="form-group">
			  
                 <label>Expiry Cycle (In days)<span class="text-red">*</span></label>
                <select class="form-control select2" id="ExpiryCycle" name="ExpiryCycle" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php

				while ($getExpiryCycleRow = mysqli_fetch_assoc($getExpiryCycle))
				{
				?>
                  <option value="<?php echo $getExpiryCycleRow['cycle']  ?>"><?php echo $getExpiryCycleRow['cycle']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			</div>			
			<div class="col-md-6">
			 <div class="form-group">
                <label>Next Renewal Date <span class="text-red">*</span></label>

             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text"  name="NextRenewalDate" class="form-control pull-right" id="datepicker11" placeholder="Pick a date" required>
                </div>
              </div>
			  </div>
			  
			  <div class="col-md-12">
              <div class="form-group">
			  
                 <label>Item Ownership <span class="text-red">*</span> <i class="fa fa-fw fa-info-circle" data-toggle="tooltip" data-original-title="Selected owners can only renew the password of this item."></i></label>
                <select class="form-control select2" required multiple="multiple" data-placeholder="Please Select from below" id="ItemOwners" name="ItemOwners[]" style="width: 100%;" required>
				
				<?php

				while ($getEmployeesRow = mysqli_fetch_assoc($getEmployees))
				{
				?>
                  <option value="<?php echo $getEmployeesRow['employee_id']  ?>"><?php echo $getEmployeesRow['Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			</div>	
			<!-- /.form-group -->
              <!-- /.form-group -->
            
		
          <br>
<div class="col-md-12">
<div class="col-md-12">
<div class="form-group">
                 <label>Comments / SOP <span class="text-red">*</span><i class="fa fa-fw fa-info-circle" data-toggle="tooltip" data-original-title="Comments are requested to be based on the steps to be followed to update the password for this item"></i></label>
                <textarea rows="8" column="20" id="Comments" name="Comments" maxlength="2000" class="is-maxlength" required resize="no" style="width:100%;resize:none;"></textarea>
              </div>
</div>
</div>
<br>

          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddNewProject" value="Create Item" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  <div class="modal fade" id="modal-default-Edit">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Item</h4>
              </div>
			  <form id="EditItemForm" method="post">
              <div class="modal-body" id="UpdateElements">
                 
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddNewProject" value="Update Item" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
          </div>
		  
		  <div class="modal fade" id="modal-default-ViewOwners">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Item Owner(s)</h4>
              </div>
			  
              <div class="modal-body">
                  <div class="box box-info">
            <form id="EditOwnerForm" autocomplete="off" method="post" action="">
        <div class="box-body">
          <div class="row">
			<br>
			 <input type="hidden" name="ItemIdOwner" id="ItemIdOwner" />
			<div class="col-md-12">
              <div class="form-group">
			  
                 <label>Item Ownership <span class="text-red">*</span> <i class="fa fa-fw fa-info-circle" data-toggle="tooltip" data-original-title="Selected owners can only renew the password of this item."></i></label>
                <select class="form-control select2" required multiple="multiple" data-placeholder="Please Select from below" id="EditItemOwners" name="EditItemOwners[]" style="width: 100%;" required>
                </select>
              </div>
			</div>
	
		<br>
		
          </div>

				</div>
          </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="EditOwner" value="Modify Owner(s)" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
          </div>
		  
		  <div class="modal fade" id="modal-default-update">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Renew Item</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
            <form id="UpdateForm" autocomplete="off" method="post" action="">
        <div class="box-body">
          <div class="row">
			<br>
			 <input type="hidden" name="ItemId" id="ItemId" />
			 <input type="hidden" name="RenewalCycle" id="RenewalCycle" />
		  <div class="col-md-12">
              <div class="form-group">
                 <label>Comments / SOP <span class="astrick">*</span> </label>
             <textarea rows="8" column="20" id="UpdateComments" name="UpdateComments"  class="is-maxlength" required resize="no" style="width:100%;resize:none;" disabled></textarea>
            </div>
		</div>
		<div class="col-md-6">
					<div class="form-group">
						 <label>Phy Location / App Name <span class="text-red">*</span></label>
						<input type="text" name="PhysicalLocationLbl" class="form-control" required id="PhysicalLocationLbl" placeholder="Enter Physical Location" disabled>
					  </div>
			</div>	
			<div class="col-md-6">
					<div class="form-group">
						 <label>Logical Location <span class="text-red">*</span></label>
						<input type="text" name="LogicalLocationLbl" class="form-control" required id="LogicalLocationLbl" placeholder="Enter Locgical Location" disabled>
					  </div>
			</div>
			<div class="col-md-6">
					<div class="form-group">
						 <label>UserName <span class="text-red">*</span></label>
						<input type="text" name="userNameLbl" class="form-control" required id="userNameLbl" placeholder="Enter UserName" disabled>
					  </div>
			</div>
            <div class="col-md-6">
					<div class="form-group">
						 <label>Expiry Cycle (In Days)<span class="text-red">*</span></label>
						<input type="text" name="ExpiryCycleLbl" class="form-control" required id="ExpiryCycleLbl" placeholder="Enter UserName" disabled>
					  </div>
			</div>
		<br>
		
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole1" onclick="clearFields();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="UpdateItem" value="Renew Item" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  
    <strong>Acurus Solutions Private Limited.</strong>
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

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
<script src="toastr/toastr.js"></script>
<!-- SlimScroll -->
<!-- FastClick -->
<!-- AdminLTE App -->

<!-- page script -->
<script>
$(document).ready(function() {
    //button modal
    $('.ViewOwners').click(function(){
		debugger;
		ajaxindicatorstart("Please Wait..");
		var id = $(this).attr("data-src");
        $.post("OwnersList.php?id="+id, function( htmlContents ) {
          $('#EditItemOwners').html( htmlContents ); 
		  $('#ItemIdOwner').val(id);
		  
		   ajaxindicatorstop();
        });      
    })
})
//-------------
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
    $('#datepicker11').datepicker({
      autoclose: true,
	  startDate:'+d'
    })
	$('#datepicker1').datepicker({
      autoclose: true,
	  startDate:'+d'
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
$(document).ready(function() {
	
    $("#ItemForm").submit(function(e) {
		e.preventDefault();
		debugger;
		
		
			ajaxindicatorstart("One Moment Please..");
			e.preventDefault();
			var data = $("#ItemForm").serialize();
			$.ajax({
         data: data,
         type: "post",
         url: "InsertNewItem.php",
         success: function(data){
			toastMsg('New Item Added Successfully');
		  location.reload();
		   ajaxindicatorstop();
         }
});
		
		
		
	});
	});
</script>
<script>
$(document).ready(function() {
	
    $("#UpdateForm").submit(function(e) {
		e.preventDefault();
		debugger;
		
		
			ajaxindicatorstart("One Moment Please..");
			e.preventDefault();
			var data = $("#UpdateForm").serialize();
			$.ajax({
         data: data,
         type: "post",
         url: "RenewItem.php",
         success: function(data){
			toastMsg('Item Renewed Successfully');
		  location.reload();
		   ajaxindicatorstop();

         }
});
		
		
		
	});
	});
</script>
<script>
	$(".content-wrapper").on("submit","#EditItemForm", function(e) {

		e.preventDefault();
		debugger;
			ajaxindicatorstart("One Moment Please..");
			e.preventDefault();
			var data = $("#EditItemForm").serialize();
			$.ajax({
         data: data,
         type: "post",
         url: "updateItem.php",
         success: function(data){
			toastMsg('Updated Successfully');
		  location.reload();
		   ajaxindicatorstop();

         }
});
		
		
		
	});
</script>

<script>
	$(".content-wrapper").on("submit","#EditOwnerForm", function(e) {

		e.preventDefault();
		debugger;
			ajaxindicatorstart("One Moment Please..");
			e.preventDefault();
			var data = $("#EditOwnerForm").serialize();
			$.ajax({
         data: data,
         type: "post",
         url: "updateOwner.php",
         success: function(data){
			toastMsg('Updated Successfully');
		  location.reload();
		   ajaxindicatorstop();

         }
});
		
		
		
	});
</script>

<script>
$(".content-wrapper").on("click",".updateItembtn", function(e) {
	debugger;
	ajaxindicatorstart("Please Wait..");
	var id = $(this).attr("data-src");
	$('#ItemId').val(id);
	var data = $('#ItemId').serialize();
	$.ajax({
         data: data,
         type: "post",
         url: "getItemInfo.php",
		 dataType: "json",
         success: function(data){
	$('#UpdateComments').val(data.result.comments);
	$('#PhysicalLocationLbl').val(data.result.physical_location);
	$('#LogicalLocationLbl').val(data.result.logical_location);
	$('#userNameLbl').val(data.result.user_name);
	$('#RenewalCycle').val(data.result.expiry_cycle);
	$('#ExpiryCycleLbl').val(data.result.expiry_cycle);
		   ajaxindicatorstop();

         }
});
});
</script>
<script>
$(".content-wrapper").on("click",".EditItem", function(e) {
	debugger;
	ajaxindicatorstart("Please Wait..");
	var id = $(this).attr("data-src");
	$('#ItemIdHidden').val(id);
	var data = $('#ItemIdHidden').serialize();
	$.ajax({
         data: data,
         type: "post",
         url: "EditInfo.php",
         success: function(data){
					$('#UpdateElements').html(data);
		   ajaxindicatorstop();

         }
});
});
</script>
<script>
$(".content-wrapper").on("click",".deleteItem", function(e) {
	debugger;
	ajaxindicatorstart("Please Wait..");
	var id = $(this).attr("data-src");
	$('#ItemIdHidden').val(id);
	var data = $('#ItemIdHidden').serialize();
	$.ajax({
         data: data,
         type: "post",
         url: "DeleteItem.php",
         success: function(data){
		toastMsg('Item Deleted Successfully');
		  location.reload();
		   ajaxindicatorstop();
		   

         }
});
});
</script>
<script>
$(".content-wrapper").on("click",".NotifyOwner", function(e) {
	debugger;
	ajaxindicatorstart("Please Wait..");
	var id = $(this).attr("data-src");
	$('#ItemIdHidden').val(id);
	var data = $('#ItemIdHidden').serialize();
	$.ajax({
         data: data,
         type: "post",
         url: "NotifyItem.php",
         success: function(data){
		toastMsg('Alert Sent Successfully');
		  location.reload();
		   ajaxindicatorstop();
         }
});
});
</script>
<script>
  $(document).on('change', '#CategorySel', function(){
	  debugger;
    var val = $(this).val();
	if(val=='Application Accounts')
	{
		$('#phyloclbl').text('Application Name');
		$('#LogicalLocationText').prop('disabled',true);
	}
	else
	{
		$('#phyloclbl').text('Physical Location');
		$('#LogicalLocationText').prop('disabled',false);
	}
		});

</script>
<script>
  $(document).on('change', '#CategorySelEdit', function(){
	  debugger;
    var val = $(this).val();
	if(val=='Application Accounts')
	{
		$('#phyloclblEdit').text('Application Name');
		$('#LogicalLocationTextEdit').prop('disabled',true);
	}
	else
	{
		$('#phyloclblEdit').text('Physical Location');
		$('#LogicalLocationTextEdit').prop('disabled',false);
	}
		});

</script>
<script type="text/javascript">
$(document).ready(function(){
		debugger;
        	
			<?php $_SESSION['NewItem']=''; ?>
});
</script>
	<script type="text/javascript">
function toastMsg(message){
debugger;
             var i = -1;
             var toastCount = 0;
             var $toastlast;

             var getMessage = function () {
                 var msgs = ['My name is Inigo Montoya. You killed my father. Prepare to die!',
                     '<div><input class="input-small" value="textbox"/>&nbsp;<a href="http://johnpapa.net" target="_blank">This is a hyperlink</a></div><div><button type="button" id="okBtn" class="btn btn-primary">Close me</button><button type="button" id="surpriseBtn" class="btn" style="margin: 0 8px 0 8px">Surprise me</button></div>',
                     'Are you the six fingered man?',
                     'Inconceivable!',
                     'I do not think that means what you think it means.',
                     'Have fun storming the castle!'
                 ];
                 i++;
                 if (i === msgs.length) {
                     i = 0;
                 }

                 return msgs[i];
             };

             var getMessageWithClearButton = function (msg) {
                 msg = msg ? msg : 'Clear itself?';
                 msg += '<br /><br /><button type="button" class="btn clear">Yes</button>';
                 return msg;
             };

             $('#closeButton').click(function() {
                 if($(this).is(':checked')) {
                     $('#addBehaviorOnToastCloseClick').prop('disabled', false);
                 } else {
                     $('#addBehaviorOnToastCloseClick').prop('disabled', true);
                     $('#addBehaviorOnToastCloseClick').prop('checked', false);
                 }
             });

     				var msg = message;

     					if(msg!=''){


     		debugger;
                 var shortCutFunction = "success";
                 var msg = $('#message').val();
                 var title = $('#title').val() || '';
                 var $showDuration = $('#showDuration');
                 var $hideDuration = $('#hideDuration');
                 var $timeOut = $('#timeOut');
                 var $extendedTimeOut = $('#extendedTimeOut');
                 var $showEasing = $('#showEasing');
                 var $hideEasing = $('#hideEasing');
                 var $showMethod = $('#showMethod');
                 var $hideMethod = $('#hideMethod');
                 var toastIndex = toastCount++;
                 var addClear = $('#addClear').prop('checked');

                 toastr.options = {
                     closeButton: false,
                     debug: false,
                     newestOnTop: false,
                     progressBar: false,
                     rtl: false,
                     positionClass: $('#positionGroup input:radio:checked').val() || 'toast-top-right',
                     preventDuplicates: false,
                     onclick: null
                 };

                 if ($('#addBehaviorOnToastClick').prop('checked')) {
                     toastr.options.onclick = function () {
                         alert('You can perform some custom action after a toast goes away');
                     };
                 }

                 if ($('#addBehaviorOnToastCloseClick').prop('checked')) {
                     toastr.options.onCloseClick = function () {
                         alert('You can perform some custom action when the close button is clicked');
                     };
                 }


                     toastr.options.showDuration = 1000;



                     toastr.options.hideDuration = 1000;



                     toastr.options.timeOut = 5000;



                     toastr.options.extendedTimeOut = 1000;



                     toastr.options.showEasing = 'swing';



                     toastr.options.hideEasing = 'linear';



                     toastr.options.showMethod = 'fadeIn';



                     toastr.options.hideMethod = 'fadeOut';



                 if (!msg) {
                     msg = getMessage();
                 }
     						msg= message;
                 $('#toastrOptions').text('Command: toastr["'
                         + shortCutFunction
                         + '"]("'
                         + msg
                         + (title ? '", "' + title : '')
                         + '")\n\ntoastr.options = '
                         + JSON.stringify(toastr.options, null, 2)
                 );

                 var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                 $toastlast = $toast;

                 if(typeof $toast === 'undefined'){
                     return;
                 }

                 if ($toast.find('#okBtn').length) {
                     $toast.delegate('#okBtn', 'click', function () {
                         alert('you clicked me. i was toast #' + toastIndex + '. goodbye!');
                         $toast.remove();
                     });
                 }
                 if ($toast.find('#surpriseBtn').length) {
                     $toast.delegate('#surpriseBtn', 'click', function () {
                         alert('Surprise! you clicked me. i was toast #' + toastIndex + '. You could perform an action here.');
                     });
                 }
                 if ($toast.find('.clear').length) {
                     $toast.delegate('.clear', 'click', function () {
                         toastr.clear($toast, { force: true });
                     });
                 }
             }

             function getLastToast(){
                 return $toastlast;
             }
             $('#clearlasttoast').click(function () {
                 toastr.clear(getLastToast());
             });
             $('#cleartoasts').click(function () {
                 toastr.clear();
             });
         }
</script>

</body>
</html>
