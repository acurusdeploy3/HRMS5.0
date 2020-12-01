<?php
ob_start();
require_once("config.php");
require_once('queries.php');
require_once('Layouts/top-header.php');
require_once('Layouts/main-header.php');
require_once('Layouts/main-sidebar.php');
session_start();
$empId=$_SESSION['login_user'];

$msg = $docType = $validFrom = $expiry = $docNum = $validTo = $docId = '';
$id = 0;
$docTypeArray = array();
$currentDate = date("Y-m-d h:i:s");

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $eduHistory = mysqli_query($db,"select * from kye_details where employee_id = $empId and kye_id = $id and is_Active = 'Y'");
  $eduHistoryData = mysqli_fetch_assoc($eduHistory);

  $docType = @$eduHistoryData['document_type'];
  $validFrom = @$eduHistoryData['valid_from'];
  $expiry = @$eduHistoryData['has_expiry'];
  $docNum = @$eduHistoryData['document_number'];
  $validTo = @$eduHistoryData['valid_to'];
  $docId = @$eduHistoryData['doc_id'];

  $docTypeArray = explode(" ",$docType);
}
?>

<head>

<style>
.btn-default {
    background-color: #3c8dbc;
border-color : #3c8dbc; }
	.skin-blue .sidebar a {
    color: #ffffff;}
	.error {color: #FF0000;}
img {
    vertical-align: middle;
    height: 30px;
    width: 30px;
    border-radius: 20px;
}
.fa-fw {
    padding-top: 13px;
}
#goprevious,#finishme{
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
#finish{
	background-color: #4CAF50;
	display: inline-block;
    padding: 6px 30px;
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
/* The Close Button   */

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
</style>
</head>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Acurus Employee Form
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- /.box-header -->
            

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  
                  <div class="col-md-12" id="tab-content">
                    <div class="tab-content">
                          <!-- form start -->
                          <form role="form"action="" method="POST" class="form-horizontal">
							<div class="box-body">
                              <div class="col-sm-12">
							  <div class="tab-content">
							  <form role="form" id="" action="" method="POST" class="form-horizontal">
							  <H1 style="color:#286090">YOUR REGISTERATION PROCESS IS COMPLETE</H1>
							  <br>
							  <label style="color:#286090">Kindly submit the hardcopy of your douments to the HR</label>
							  <br><br>
							  <input type="button" id="finish" value="OK" onclick="logout();"></input>
							  </form>
							  </div>
								</div>
                              </div>
						
							
                            <!-- /.box-body -->
                          </form>
                          <!-- </form> -->
            						
                    </div>
								
                  </div>
                </div>
              </div>
            </div>

            <div class="box-footer">
            </div>
          </div>
          <!-- /.box -->
      </div>
      <!-- /.row -->
      <!-- Modal -->		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('Layouts/documentModals.php');

require_once('Layouts/bottom-footer.php');
?>
<script>
function logout()
{
	location.href = "logout.php";
}
</script>

<!-- Modal -->
