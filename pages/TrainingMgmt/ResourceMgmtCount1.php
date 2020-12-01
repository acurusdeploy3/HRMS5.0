<?php
require_once("Layouts/config.php");
require_once('queries.php');
require_once('Layouts/top-header.php');
require_once('Layouts/main-header.php');
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
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
      </ol>
    </section>
	<?php 
	include("config.php");
	$desgnQuery = mysql_query("select designation,count(designation) as count from resource_management_table where is_active='Y' group by designation");
	$deptQuery = mysql_query("select department,count(department) as count from resource_management_table where is_active='Y' group by department;");
	$projQuery = mysql_query("select a.project_id,b.project_name,count(a.project_id) as count from resource_management_table a
join all_projects b on a.project_id = b.project_id
where is_active='Y'
group by a.project_id;");
	?>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Department based Employee Count</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Department</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($deptRow = mysql_fetch_assoc($deptQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                  <td><a href="ViewbyDept.php?id=<?php echo $deptRow['department'] ?>"><?php echo $deptRow['department'];  ?></td>
                 <td><a href="ViewbyDept.php?id=<?php echo $deptRow['department'] ?>"><span class="badge bg-yellow"><?php echo $deptRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>
            </div>
            <!-- /.box-body -->
            
          </div>
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Project Based Employee Count</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Project</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($ProRow = mysql_fetch_assoc($projQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                  <td><a href="ViewbyProject.php?id=<?php echo $ProRow['project_id'] ?>"><?php echo $ProRow['project_name'];  ?></td>
                 <td><a href="ViewbyProject.php?id=<?php echo $ProRow['project_id'] ?>"><span class="badge bg-green"><?php echo $ProRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>     
            </div>
            <!-- /.box-body -->
          </div>
		  <button OnClick="window.location='ResourceMgmt.php'" style="width: 150px; align:right" type="button" class="btn btn-block btn-primary btn-flat">Search by Employee</button>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Role Based Employee Count</h3>

             
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
               <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Role</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($RoleRow = mysql_fetch_assoc($desgnQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                  <td><a href="ViewbyRole.php?id=<?php echo $RoleRow['designation'] ?>"><?php echo $RoleRow['designation'];  ?></td>
                 <td><a href="ViewbyRole.php?id=<?php echo $RoleRow['designation'] ?>"><span class="badge bg-light-blue"><?php echo $RoleRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>   
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box --
          <!-- /.box -->
		  
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('Layouts/documentModals.php');
require_once('Layouts/main-footer.php');
require_once('Layouts/control-sidebar.php');
require_once('Layouts/bottom-footer.php');
?>
