<?php
  require_once("config.php");
  require_once('queries.php');
  require_once('layouts/top-header.php');
  require_once('layouts/main-header.php');
  require_once('layouts/main-sidebar.php');

  if(isset($_POST['submit'])){
    $name = (isset($_POST['name']) && $_POST['name'])?$_POST['name']:'';
    $personalMail = (isset($_POST['personalMail']) && $_POST['personalMail'])?$_POST['personalMail']:'';
    $mobile1 = (isset($_POST['mobile1']) && $_POST['mobile1'])?$_POST['mobile1']:'';
    $dob = (isset($_POST['dob']) && $_POST['dob'])?$_POST['dob']:'';
    $mobile = (isset($_POST['mobile']) && $_POST['mobile'])?$_POST['mobile']:'';
    $maritalStatus = (isset($_POST['maritalStatus']) && $_POST['maritalStatus'])?$_POST['maritalStatus']:'';
    $addressLine1 = (isset($_POST['addressLine1']) && $_POST['addressLine1'])?$_POST['addressLine1']:'';
    $city = (isset($_POST['city']) && $_POST['city'])?$_POST['city']:'';
    $country = (isset($_POST['country']) && $_POST['country'])?$_POST['country']:'';
    $addressLine2 = (isset($_POST['addressLine2']) && $_POST['addressLine2'])?$_POST['addressLine2']:'';
    $state = (isset($_POST['state']) && $_POST['state'])?$_POST['state']:'';
    $zip = (isset($_POST['zip']) && $_POST['zip'])?$_POST['zip']:'';
    $paddressLine1 = (isset($_POST['paddressLine1']) && $_POST['paddressLine1'])?$_POST['paddressLine1']:'';
    $pcity = (isset($_POST['pcity']) && $_POST['pcity'])?$_POST['pcity']:'';
    $pcountry = (isset($_POST['pcountry']) && $_POST['pcountry'])?$_POST['pcountry']:'';
    $paddressLine2 = (isset($_POST['paddressLine2']) && $_POST['paddressLine2'])?$_POST['paddressLine2']:'';
    $pstate = (isset($_POST['pstate']) && $_POST['pstate'])?$_POST['pstate']:'';
    $pzip = (isset($_POST['pzip']) && $_POST['pzip'])?$_POST['pzip']:'';
  }
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Personal Details
        <small>user info</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Personal Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Personal Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <form role="form" method="post">
                <div class="box-body">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Name<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="name" name="name" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Personal E-mail<span class="astrick">*</span></label>
                      <input type="email" class="form-control" id="personalMail" name="personalMail" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Alternate Mobile<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="mobile1" name="mobile1" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Date of Birth<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="dob" name="dob" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Primary Mobile<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="mobile" name="mobile" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Marital Status<span class="astrick">*</span></label>
                      <select class="form-control" id="maritalStatus" name="maritalStatus">
                        <option value="">Marital Status</option>
                        <option value="Married">Married</option>
                        <option value="Single">Single</option>
                      </select>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
              <!-- </form> -->
            </div>

            <div class="border-class">
              <h4>Present Address</h4>
              <!-- form start -->
              <!-- <form role="form"> -->
                <div class="box-body">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Address Line1<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="addressLine1" name="addressLine1" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">City<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="city" name="city" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Country<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="country" name="country" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Address Line2<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="addressLine2" name="addressLine2" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">State<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="state" name="state" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Zip Code<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="zip" name="zip" />
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
              <!-- </form> -->
            </div>

            <div class="border-class">
              <h4>Permanent Address</h4>
              <!-- form start -->
              <!-- <form role="form"> -->
                <div class="box-body">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Address Line1<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="paddressLine1" name="paddressLine1" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">City<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="pcity" name="pcity" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Country<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="pcountry" name="pcountry" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Address Line2<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="paddressLine2" name="paddressLine2" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">State<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="pstate" name="pstate" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Zip Code<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="pzip" name="pzip" />
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box-footer">
              <div class="text-center">
                <input type="submit" class="btn btn-success" value="Submit" name="submit" />
                <input type="reset" class="btn btn-defaulr" value="Reset" />
              </div>
            </div>
            </form>
          </div>
          <!-- /.box -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
