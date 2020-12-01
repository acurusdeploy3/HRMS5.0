<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Employment Details
        <small>employee info</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Employment Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Employment Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <form role="form">
                <div class="box-body">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Employee ID<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="employeeId" name="employeeId" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Official mail<span class="astrick">*</span></label>
                      <input type="email" class="form-control" id="OfficialMail" name="OfficialMail" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Reporting Manager<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="rm" name="rm" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Date of Joining<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="doj" name="doj" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Designation<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="designation" name="designation" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Department<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="department" name="department" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Months in Acurus<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="acurusMonths" name="acurusMonths" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Project<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="project" name="department" />
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->

              </form>
            </div>

            <div class="border-class">
              <h4>KYE Information</h4>
              <!-- form start -->
              <form role="form">
                <div class="box-body">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Salary Bank Account<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="bankAccount" name="bankAccount" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">IFSC Code<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="ifsc" name="ifsc" />
                    </div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">PAN<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="pan" name="pan" />
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">DocID<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="PERMANANT_doc_id" name="PERMANANT_doc_id" readonly/>
							</div>
						</div>
						<div class="col-md-2 btn_div">
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="PERMANANT" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="PERMANANT" data-target="#view-modal"><i class="fa fa-search" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">Voter ID<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="voterId" name="voterId" />
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">DocID<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="VOTER_doc_id" name="VOTER_doc_id" readonly/>
							</div>
						</div>
						<div class="col-md-2 btn_div">
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="VOTER" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="VOTER" data-target="#view-modal"><i class="fa fa-search" aria-hidden="true"></i></a>
						</div>
					</div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">ESIC<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="esic" name="esic" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Passport Expiry<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="passportExpiry" name="passportExpiry" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Account Number<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="accountNumber" name="accountNumber" />
                    </div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">Aadhar Number<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="aadhar" name="aadhar" />
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">DocID<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="AADHAR_doc_id" name="AADHAR_doc_id" readonly/>
							</div>
						</div>
						<div class="col-md-2 btn_div">
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="AADHAR" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="AADHAR" data-target="#view-modal"><i class="fa fa-search" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">Driving License<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="drivingLicense" name="drivingLicense" />
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">DocID<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="DRIVING_doc_id" name="DRIVING_doc_id" readonly/>
							</div>
						</div>
						<div class="col-md-2 btn_div">
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="DRIVING" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="DRIVING" data-target="#view-modal"><i class="fa fa-search" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">Ration Card<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="rationCard" name="rationCard" />
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">DocID<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="RATION_doc_id" name="RATION_doc_id" readonly/>
							</div>
						</div>
						<div class="col-md-2 btn_div">
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="RATION" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="RATION" data-target="#view-modal"><i class="fa fa-search" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">Passport<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="passport" name="passport" />
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
							  <label for="exampleInputEmail1">DocID<span class="astrick">*</span></label>
							  <input type="text" class="form-control" id="PASSPORT_doc_id" name="PASSPORT_doc_id" readonly/>
							</div>
						</div>
						<div class="col-md-2 btn_div">
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="PASSPORT" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="PASSPORT" data-target="#view-modal"><i class="fa fa-search" aria-hidden="true"></i></a>
						</div>
					</div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Visa Details<span class="astrick">*</span></label>
                      <input type="text" class="form-control" id="visa" name="visa" />
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
				<div class="box-footer">
				  <div class="text-center">
					<input type="submit" class="btn btn-success" value="Submit" name="submit" />
					<input type="reset" class="btn btn-defaulr" value="Reset" />
				  </div>
				</div>
              </form>
            </div>

            <div class="box-footer">
            </div>
          </div>
          <!-- /.box -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/documentModals.php');
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
