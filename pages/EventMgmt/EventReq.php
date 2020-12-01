<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo,approx_food_count,additional_food_count,is_breakfast_included,is_lunch_included,is_snacks_included,is_dinner_included FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);
$FoodServed='';
if($getEventDetailsRow['is_breakfast_included']=='Y' || $getEventDetailsRow['is_lunch_included']=='Y' || $getEventDetailsRow['is_snacks_included']=='Y' || $getEventDetailsRow['is_dinner_included']=='Y')
	{
		$FoodServed='Y';
	}
$getEventMemento = mysqli_query($db,"select memento_desc,quantity_bought,approx_required,additional_Required from event_common_memento where event_id='$EventID'");
if(mysqli_num_rows($getEventMemento)>0)
	{
		$getEventMementoRow = mysqli_fetch_array($getEventMemento);
		$MementoDesc= $getEventMementoRow['memento_desc'];
		$QuanityBought= $getEventMementoRow['approx_required'];
		$additional_Required= $getEventMementoRow['additional_Required'];
	}

$getAllEmployees = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name,' ',MI,' : ',employee_id) as Name FROM `employee_Details` where is_active='Y' and employee_id not in (select employee_id from event_management_team where event_id='$EventID' and is_active='Y')");
$AllRoles = mysqli_query($db,"SELECT role_desc FROM `event_roles`");
$getEventTeam = mysqli_query($db,"select a.id,a.employee_id,concat(b.first_name,' ',b.last_name,' ',b.mi) as Name,role from event_management_team a
								  inner join employee_Details b on a.employee_id=b.employee_id where event_id='$EventID';");
$getAllInvitees = mysqli_query($db,"select * from event_invitees where event_id='$EventID' and is_active='Y'");
$isFamEligible = mysqli_query($db,"select * from event_invitees where event_id='$EventID' and is_family_included='Y'");
if(mysqli_num_rows($isFamEligible)>0)
{
	$getFamCount = mysqli_query($db,"select * from employee_family_particulars where is_active='Y' and status!='Deceased' and employee_id in (select employee_id from event_invitees where event_id='$EventID' and is_active='Y');");
	$FoodRequired = mysqli_num_rows($getAllInvitees)+mysqli_num_rows($getFamCount);
	}	
	else
	{
		$FoodRequired= mysqli_num_rows($getAllInvitees);
	}
								  
								  ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Event Management</title>
 <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
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
.astrick
{
	color:red;
}
 
th {
	  background-color: lightgray;
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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Event Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.html"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="EventsInfo.php">Active Events</a></li>
        <li class="active">New Event</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><strong><?php echo $getEventDetailsRow['event_title']; ?> </strong></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
		  
		 <div class="col-xs-12">
	 
			 
		 <fieldset>	
<legend style="border-bottom: 0.5px solid #3c8dbc"><h4>Event Subsidiaries</h4></legend>
		<form id="NewEventSForm" method="post" enctype="multipart/form-data" action="UpdateSubsidiaries.php" onsubmit="return CheckforBal();">	 
			 <input type="hidden" name="EventID" value="<?php echo $EventID ?>">
			 <input type="hidden" name="FoodServed" value="<?php echo $FoodServed ?>">
			 <div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group">
							<label>Food to be served for the Attendee(s)?<span class="astrick">*</span></label>
							<select class="form-control select2" onchange="changetextbox();" id="IsFoodServed" name="IsFoodServed" required="required" style="width: 100%;" required>
									<?php
									if($FoodServed=='')
									{
									?>
									<option value="" selected disabled>Please Select from Below</option>
									<option value="Yes">Yes</option>
									<option value="No">No</option>
									<?php
									}
									else
									{
									?>
									<option value="Yes" selected>Yes</option>
									<option value="No">No</option>
									<?php
									}
									?>
								</select>
						</div>
						
					</div>
				<div class="col-md-6">
					<div class="col-md-6">
						<div class="form-group">
							<label>Food Type <span class="astrick">*</span></label>
								<select class="form-control select2" id="FoodType" name="FoodType[]" multiple="multiple" data-placeholder="Select from Type(s)"
									style="width: 100%;" <?php if($FoodServed=='') { ?> disabled <?php } ?> required>
									<option value="is_breakfast_included" <?php if ($getEventDetailsRow['is_breakfast_included']=='Y') { ?> selected  <?php } ?>>Break Fast</option>
									<option value="is_lunch_included"  <?php if ($getEventDetailsRow['is_lunch_included']=='Y') { ?> selected  <?php } ?>>Lunch</option>
									<option value="is_dinner_included"  <?php if ($getEventDetailsRow['is_dinner_included']=='Y') { ?> selected  <?php } ?>>Dinner</option>
									<option value="is_snacks_included"  <?php if ($getEventDetailsRow['is_snacks_included']=='Y') { ?> selected  <?php } ?>>Snacks</option>
								</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Approx. #</label>
							<input type="number" min="0" name="ApproxFoodRequired" value="<?php if($getEventDetailsRow['approx_food_count']!=' ') { echo $getEventDetailsRow['approx_food_count']; } else { echo $FoodRequired; }?>" id="ApproxFoodRequired" class="form-control" placeholder="# Required"  <?php if($FoodServed=='') { ?> disabled <?php } ?>>
						</div>
						</div>

						<div class="col-md-3">
						<div class="form-group">
							<label>Additional #</label>
							<input type="number" min="0" name="AdditionalRequired" value="<?php if($getEventDetailsRow['additional_food_count']!=' ') { echo $getEventDetailsRow['additional_food_count']; }?>" id="AdditionalRequired" class="form-control" placeholder="# Required"  <?php if($FoodServed=='') { ?> disabled <?php } ?>>
						</div>
						</div>
				</div>
			</div>
					
				<div class="col-md-12">
					<div class="col-md-6">
					
						<div class="form-group">
							<label>Event Memento to be Provided for Attendee(s)?<span class="astrick">*</span></label>
							<select class="form-control select2" onchange="changetextbox1();" id="IsMemntoProvided" name="IsMemntoProvided" required="required" style="width: 100%;" required>
									<?php
									if($MementoDesc=='')
									{
									?>
									<option value="" selected disabled>Please Select from Below</option>
									<option value="Yes">Yes</option>
									<option value="No">No</option>
									<?php
									}
									else
									{
									?>
									
									<option value="Yes" selected>Yes</option>
									<option value="No">No</option>
									
									<?php
									}
									?>
								</select>
						</div>
						
					</div>
					
					<div class="col-md-6">
						<div class="col-md-4">
						<div class="form-group">
							<label>Memento Desc.<span class="astrick">*</span></label>
							<input type="text" name="MementoDesc" value="<?php if($MementoDesc!='') { echo $MementoDesc; } ?>" id="MementoDesc" class="form-control" placeholder="Memento Desc" required <?php if($MementoDesc=='') { ?> disabled <?php } ?>>
						</div>
						</div>
						<div class="col-md-4">
						<div class="form-group">
							<label>Approx. #</label>
							<input type="number" min="0" name="QuantityBought" value="<?php if($QuanityBought!='') { echo $QuanityBought; } else { echo mysqli_num_rows($getAllInvitees); }?>" id="QuantityBought" class="form-control" placeholder="# Required"  <?php if($MementoDesc=='') { ?> disabled <?php } ?>>
						</div>
						</div>

						<div class="col-md-4">
						<div class="form-group">
							<label>Additional #</label>
							<input type="number" min="0" name="QuantityRequired" value="<?php if($additional_Required!='') { echo $additional_Required; } ?>" id="QuantityRequired" class="form-control" placeholder="# Required"  <?php if($MementoDesc=='') { ?> disabled <?php } ?>>
						</div>
						</div>
						<br>
						  <input type="submit" value="Save" id="EventSubSave" class="btn btn-success pull-right"></input> 
					</div>
	
				</div>

			<br>

			 </form>

          	 </fieldset>
			
		  <br>
		   <fieldset>
		<legend style="border-bottom: 0.5px solid #3c8dbc"><h4>Event Committee</h4></legend>

		   <div class="col-md-12">
			<form id="EventMgmtFrm" method="post" action="UpdateTeam.php" onsubmit="return loader();">
			<div class="box-body">
          <div class="row">
			 <input type="hidden" name="EventID" value="<?php echo $EventID ?>">
			 
			 <div class="col-md-6">
						 <div class="form-group">
							<label>Select Employee <span class="astrick">*</span></label>
					
							<select class="form-control select2" id="AdditionalEmp" name="AdditionalEmp" style="width: 100%;" required>
								<option value="" selected disabled>Please Select from Below</option>
							<?php
				
							while ($getAllEmployeesRow = mysqli_fetch_assoc($getAllEmployees))
							{
							?>
						<option value="<?php echo $getAllEmployeesRow['employee_id']  ?>"><?php echo $getAllEmployeesRow['Name']  ?></option>
						<?php
						}
						?>
							</select>
					</div>
					</div>
					<div class="col-md-6">
							<div class="form-group">
						
							<label>Role <span class="astrick">*</span>
							<a href="#" title="Click to Add More Roles" id="additionalRole" data-toggle="modal" data-target="#modal-default-Role"><i class="fa fa-fw fa-plus"></i></a>
							</label>
							  <select class="form-control select2" id="RoleSel" name="RoleSel" style="width: 100%;" required>
									<option value="" selected disabled>Please Select from Below</option>
									<?php
									while ($AllRolesRow = mysqli_fetch_assoc($AllRoles))
									{
								?>
									<option value="<?php echo $AllRolesRow['role_desc']  ?>"><?php echo $AllRolesRow['role_desc']  ?></option>
								<?php
									}
								?>
							</select>
						</div>
					<input type="submit" value="Save" id="EventCommSave" class="btn btn-success pull-right"></input> 
				 <br>
					</div>	
	</div>	
 </div>	
			 </form>
			 
			  <div class="box-body table-responsive no-padding">
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Employee ID</th>
				  <th>Name</th>
				  <th>Role</th>
                  <th>Remove</th>
                
                </tr>
                </thead>
				
                <tbody>
				<?php
				if(mysqli_num_rows($getEventTeam) > 0){
				while ($getEventRolesRow = mysqli_fetch_assoc($getEventTeam))
					{
				?>
                <tr>
                  <td><?php echo $getEventRolesRow['employee_id'];  ?></td>
                  <td><?php echo $getEventRolesRow['Name'];  ?></td>
                  <td><?php echo $getEventRolesRow['role'];  ?></td>
				 <td> <a href="DeleteTeam.php?empId=<?php echo $getEventRolesRow['id'] ?>" class="DelTeam" onclick="return confirm('Sure to Remove <?php echo $getEventRolesRow['Name'] ?> from List?')"><img alt='User' src='../../dist/img/remove-icon-png-8.png' width='18px' height='20px' /></a></td>
                </tr>
                <?php
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
			
			 
			 <br>

<br>

              <!-- /.form-group -->
            </div>
</div>
           	 </fieldset>
			 <!-- /.col -

            <!-- /.col -->
          </div>
		  <br>
	
 
          <!-- /.row -->
        </div>
		<?php
		if($_SESSION['fromEventsHome']!='Y')
						{
		?>
		
		<div class="box-footer">   
			   <input type="button" onclick="window.location='NewEventInvitees.php?id=<?php echo $EventID  ?>'" class="btn btn-primary pull-left" value="Back" id="Back"  />
               <button type="button" name="Submit" onclick="window.location='EventAgenda.php?id=<?php echo $EventID  ?>'" class="btn btn-primary pull-right" id="savefields">Next</button>     
	    </div>
			   <?php
						}
						else
						{
			  ?>
			  <div class="box-footer">
			      
               <button type="button" name="Submit" onclick="window.location='EventInfo.php?id=<?php echo $EventID  ?>'" class="btn btn-success pull-right" id="savefields">Finish</button>     
			
			
              </div>
			  
			  <?php
						}
				?>
				
				
				
				
				
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Role Desc</label>

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
				
				
				
				
				
				
				
				
				
				
				
				
				
				
        <!-- /.box-body -->
      </div>
      <!-- /.box --
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
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
            var ddl = document.getElementById("RoleSel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputRole").value;
            option.value = document.getElementById("inputRole").value;
            ddl.options.add(option);
			document.getElementById("closeRole").click();
			document.getElementById("inputRole").value="";
        
			     
        }
    </script>
<script type="text/javascript">
function changetextbox()
{
    if (document.getElementById("IsFoodServed").value === "Yes") {
		document.getElementById("FoodType").disabled=false;
		document.getElementById("ApproxFoodRequired").disabled=false;
		document.getElementById("AdditionalRequired").disabled=false;
	}
	else
	{
		document.getElementById("FoodType").disabled=true;
		document.getElementById("ApproxFoodRequired").disabled=true;
		document.getElementById("AdditionalRequired").disabled=true;
	}
	
}
</script>
<script type="text/javascript">
function changetextbox1()
{
    if (document.getElementById("IsMemntoProvided").value === "Yes") {
		document.getElementById("MementoDesc").disabled=false;
		document.getElementById("QuantityBought").disabled=false;
		document.getElementById("QuantityRequired").disabled=false;
	}
	else
	{
		document.getElementById("MementoDesc").disabled=true;
		document.getElementById("QuantityBought").disabled=true;
		document.getElementById("QuantityRequired").disabled=true;
	}
	
}
</script>
	<script>
function CheckforBal()
{
	var returnvalue = true;
	
			if(returnvalue == true)
			{
				ajaxindicatorstart("Saving Subsidiaries..");
				return true;
			}
			else
			{
				return false;
			}
}
	</script>
	<script>
function loader()
{
	var returnvalue1 = true;
	
			if(returnvalue1 == true)
			{
				ajaxindicatorstart("Adding to Committee..");
				return true;
			}
			else
			{
				return false;
			}
}
	</script>
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
      autoclose: true
    })
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: '+d'
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
</body>
</html>
