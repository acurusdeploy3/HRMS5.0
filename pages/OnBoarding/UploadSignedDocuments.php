<?php
session_start();
$usergrp = $_SESSION['login_user_group'];
if($usergrp == 'HR' || $usergrp == 'HR Manager') 
{
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Employee Boarding</title>
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
        th {
          background-color: #31607c;
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
        Employee Boarding
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="BoardingHome.php">Boarding Home</a></li>
        <li class="active">Complete Formalities</li>
      </ol>
    </section>
<?php
$EmployeeID = $_GET['id'];
session_start();
$_SESSION['Employee_id']=$EmployeeID;
include("config2.php");
$getName = mysqli_query($db,"select concat(First_Name,' ',last_Name,' ',MI) as Name from employee_details where employee_id=$EmployeeID");
$getNameRow = mysqli_fetch_array($getName);
$getNameVal = $getNameRow['Name'];
?>
    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->

		<?php
		include("config2.php");

		$getEmpDocumentTypes = mysqli_query ($db,"select group_concat('''',document_type,'''') as document_types from employee_documents_Tracker where employee_id=$EmployeeID and document_module='Employee_Boarding'");
		$getEmpDocRow = mysqli_fetch_array($getEmpDocumentTypes);
		$EmpDocTypes = $getEmpDocRow['document_types'];
		if($EmpDocTypes=='')

			{
			$getDocTypes = mysqli_query($db,"Select document_type from all_document_types where authorized_for='HR' and document_type !=''");
			}
			else
			{
				$getDocTypes = mysqli_query($db,"Select document_type from all_document_types where authorized_for='HR' and document_type not in($EmpDocTypes)");
			}
		?>
	<!--    Files Content    -->


			<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><strong>Upload Signed Documents</strong></h3>
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
				<button OnClick="window.location.href='BoardingHome.php'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
			  </td>

			  </tr>
			  </tbody>
			  </table>
			  <br>
             <h4 class="box-title" style="color:#3c8dbc;"><strong><?php echo $getNameVal ?> : <?php echo $EmployeeID  ?></strong></h4>
			  <br>
			  <div class="box-tools pull-right">
          </div>

            </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			<div class="col-md-6">
			<form id="UploadForm" action="InsertBoardingDocument.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
                 <label>Document Type <a href="#" title="Click to Add More Document Types" id="additionalDoc" data-toggle="modal" data-target="#modal-default-Doc"><i class="fa fa-fw fa-plus"></i></a></label>
              <select class="form-control select2" id="DocSelect" required="required" name="DocSelect" style="width: 100%;" >

                  <option value="" selected="selected" disabled>Select From Below</option>
                 <?php

				while ($Doc = mysqli_fetch_assoc($getDocTypes))
				{
				?>
                  <option value="<?php echo $Doc['document_type']  ?>"><?php echo $Doc['document_type']  ?></option>
                 <?php
				}
				 ?>
                </select>
				</div>
				
				<div class="form-group">
                 <label>Upload Document</label>
               <input type="file" id="ResumeFileDoc" oninput="this.className = ''" name="ResumeFileDoc" accept= "application/msword,text/plain, application/pdf,image/x-png,image/gif,image/jpeg" required="required" />


              </div>
				






				</div>
		<div class="col-md-6">
		<div class="form-group">
				<label>Document Number</label>
                      <input type="text" class="form-control" id="docNum" name="docNum" value="<?php echo $docNum; ?>" />

              </div>
			  <div class="form-group">
						<div id="ifYes" style="display: none;">
						<label>Valid From</label>
                         <input type="text" class="form-control" id="datepicker" name="validFrom" value="<?php echo $validFrom; ?>"/>

                             <label>Valid To</label>

                             <input type="text" class="form-control" id="datepicker1" name="validTo"  value="<?php echo $validTo; ?>" />
                              <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
							   </div>
				</div>

			<?php
				include("config2.php");
				$getDocumentQuery = mysqli_query($db,"select a.doc_id,employee_id,document_type,b.document_name from employee_documents_tracker a
left join employee_documents b on a.doc_id=b.doc_id where employee_id=$EmployeeID and document_module='Employee_Boarding' and b.document_name!=''");
			?>
			
<div class="form-group">
				  <label></label>
                <label></label>
				 <button type="submit" style="width:30%" id="AddDocument" class="btn btn-block btn-primary" >Add Document</button>
				 </div>

				</div>
				</form>
				<br>
				<br>
              <table class="table table-striped">
                <tr style="color:White;">
                  <th>Document Type</th>
				   <th>Document ID</th>
				   <th>Download</th>
                </tr>
				<?php
				if(mysqli_num_rows($getDocumentQuery)>0)
				{
					while($Documents = mysqli_fetch_assoc($getDocumentQuery))
					{
				?>
                <tr>
                  <td><?php echo $Documents['document_type']; ?></td>
                  <td><?php echo $Documents['doc_id']; ?></td>
                   <td><i class="fa fa-fw fa-download"></i><a href="DownloadDocument.php?link=<?php echo $Documents['document_name']; ?>">Download</a></td>



                </tr>

				<?php
					}
				}
				else
				{
				?>
				<tr>
                  <td>No Documents Found!</td>
                </tr>
				<?php
				}
				?>
              </table>
			  <button type="button" id="FinishFormalities" class="btn btn-success pull-right">Finish</button>
            </div>
            <!-- /.box-body -->
          </div>

<div class="modal fade" id="modal-default-Doc">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Document Type</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">

            <form id="BandForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Document Type :</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputDoc" name="inputDoc" placeholder="Enter Type" />

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
                <button type="button" id="closeDoc" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addDocbtn"  class="btn btn-primary">Add Type</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        <!-- /.box-body -->
    </section>
    <!-- /.content -->
 </div>

 <?php

 require_once('Layouts/documentModals.php');
 ?>
      <!-- /.box --
      <!-- /.row -->

  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong>
  </footer>

  <!-- Control Sidebar -->
   </div>
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

var number = document.getElementById('WKS');

// Listen for input event on numInput.
number.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58)
      || e.keyCode == 8)) {
        return false;
    }
}
</script>
<script>
$(function(){
  var addKYEName = $("form#addKYE select[name='docType'] option:eq(1)").val();
	var addKYENameArray = addKYEName.split(" ");
	$("form#addKYE div.btn_div a[href='#myModal']").attr("id",addKYENameArray[0]);
	$("form#addKYE input[name='docId']").attr("id",addKYENameArray[0]+"_doc_id");
  $("form#addKYE select[name='docType'] option:eq(1)").attr("selected","selected");
});



function yesnoCheck(that) {
        if (that.value == "Yes") {

            document.getElementById("ifYes").style.display = "block";
        } else {
            document.getElementById("ifYes").style.display = "none";
        }
    }
</script>
<script type="text/javascript">
       $(document).on('click','#addModbtn',function(e) {
		   var data = $("#inputMod").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddMod.php",
         success: function(data){
			AdditionalMod();
			 ajaxindicatorstop();

         }
});
 });
    </script>

	<script type="text/javascript">
       function AdditionalMod() {

			var modal = document.getElementById('modal-default-Mod');
            var ddl = document.getElementById("TrainingModule");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputMod").value;
            option.value = document.getElementById("inputMod").value;
            ddl.options.add(option);
			document.getElementById("closeMod").click();
			document.getElementById("inputMod").value="";


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
       $(document).on('click','#addDocbtn',function(e) {
		   var data = $("#inputDoc").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddDoc.php",
         success: function(data){
			AdditionalDoc();
			 ajaxindicatorstop();

         }
});
 });
    </script>
<script type="text/javascript">
	unsaved = false;
$(":input").change(function() {
   unsaved = true;
});
</script>

	<script type="text/javascript">
       function AdditionalDoc() {

			var modal = document.getElementById('modal-default-Doc');
            var ddl = document.getElementById("DocSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDoc").value;
            option.value = document.getElementById("inputDoc").value;
            ddl.options.add(option);
			document.getElementById("closeDoc").click();
			document.getElementById("inputDoc").value="";


        }
    </script>









<script type="text/javascript">
function changetextbox()
{
    if (document.getElementById("MandForAll").value === "Yes") {
		document.getElementById("SelByRole").disabled=true;
		document.getElementById("SelByDept").disabled=true;
		document.getElementById("TraineesSel").disabled=true;
	}
	else
	{
		document.getElementById("SelByRole").disabled=false;
		document.getElementById("SelByDept").disabled=false;
		document.getElementById("TraineesSel").disabled=false;
	}

}
</script>
<script type="text/javascript">
function changeFreq()
{
    if (document.getElementById("TrainFreq").value === "Once") {
		document.getElementById("TrainFreqOcc").disabled=true;

	}
	else
	{
		document.getElementById("TrainFreqOcc").disabled=false;

	}

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
     $(document).on('click','#FinishFormalities',function(e) {
	   var data = $("#UploadForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "CompleteAllFormalities.php",
         success: function(data){
			window.location.href = "BoardingHome.php";
			 ajaxindicatorstop();

         }
});
});
    </script>



</body>
</html>
<?php
}
else
{
	header("Location: ../forms/Logout.php");
}
?>
