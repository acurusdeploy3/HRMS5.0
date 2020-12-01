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
        Educational Details
        <small>qualifications</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Educational Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Educational Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <h4>Educational Details</h4>
              <div class="box-body no-padding">
                <table class="table">
                  <tbody><tr>
                    <th style="width: 10px">#</th>
                    <th>Programme</th>
                    <th>Specialization</th>
                    <th>Year of Passing</th>
                    <th style="width: 40px"></th>
                  </tr>
                  <tr>
                    <td>1.</td>
                    <td>BTech</td>
                    <td>Computer Science</td>
                    <td>2017</td>
                    <td><span class="badge bg-red">55%</span></td>
                  </tr>
                  <tr>
                    <td>2.</td>
                    <td>Higher Secondary</td>
                    <td>Computer Science</td>
                    <td>2013</td>
                    <td><span class="badge bg-red">55%</span></td>
                  </tr>
                </tbody></table>
              </div>

              <input type="button" class="btn btn-info" value="Add New Qualification" data-toggle="modal" data-target="#modal-education" />
            </div>

            <div class="border-class">
              <!-- form start -->
              <h4>Skills and Certifications</h4>
              <div class="box-body no-padding">
                <table class="table">
                  <tbody><tr>
                    <th style="width: 10px">#</th>
                    <th>Certified In</th>
                    <th>Year</th>
                    <th>Expiry Date</th>
                    <th style="width: 40px"></th>
                  </tr>
                  <tr>
                    <td>1.</td>
                    <td>HIPPA</td>
                    <td>2017</td>
                    <td>April 2022</td>
                    <td><span class="badge bg-red">55%</span></td>
                  </tr>
                  <tr>
                    <td>2.</td>
                    <td>R Language</td>
                    <td>2017</td>
                    <td>NA</td>
                    <td><span class="badge bg-red">55%</span></td>
                  </tr>
                </tbody></table>
              </div>
              <input type="button" class="btn btn-info" value="Add New Certification" data-toggle="modal" data-target="#modal-skill" />
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
  <div class="modal fade" id="modal-education">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Educational Details</h4>
        </div>
        <form role="form" name="education-form" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Programme<span class="astrick">*</span></label>
              <input type="text" class="form-control" id="programme" name="programme" />
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Specialization<span class="astrick">*</span></label>
              <input type="text" class="form-control" id="specialization" name="specialization" />
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Year of Passing<span class="astrick">*</span></label>
              <input type="text" class="form-control" id="passyear" name="passyear" />
            </div>
          </div>
            <!-- /.box-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <input type="button" class="btn btn-primary" name="educationSubmit" value="Save changes" />
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal-skill">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Skills and Certifications</h4>
        </div>
        <form role="form" name="education-form" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Certified In<span class="astrick">*</span></label>
              <input type="text" class="form-control" id="certified" name="certified" />
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Year<span class="astrick">*</span></label>
              <input type="text" class="form-control" id="year" name="year" />
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Expiry Date<span class="astrick">*</span></label>
              <input type="text" class="form-control" id="expiry" name="expiry" />
            </div>
          </div>
            <!-- /.box-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
  		      <input type="button" class="btn btn-primary" name="skillSubmit" value="Save changes" />
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
