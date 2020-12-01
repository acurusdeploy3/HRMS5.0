<?php
session_start();
include('config.php');
$name = $_SESSION['login_user'];
$date = date("Y-m-d");
$employeeid = $_POST['employeeid'];
$date1 = $_POST['date'];
$strength = mysqli_real_escape_string($db,$_POST['strength']);
//$employeeid = mysqli_real_escape_string($db,$_POST['employeeid']);
$shiftcode = mysqli_real_escape_string($db,$_POST['shiftcode']);
$strengthcheck = "select * from dsr_summary where employee_comments ='$strength' and employee_id='$employeeid' and is_active='Y' and date='$date'";
$result1 =mysqli_query($db,$strengthcheck);
if(mysqli_num_rows($result1)<1 && $strength !='')
{
	$strengthareas = "Insert into `dsr_summary` (employee_id, date, shift_code, employee_comments,manager_id,manager_comments, created_date_and_time, created_by, modified_date_and_time, modified_by) value ('$employeeid','$date','$shiftcode','$strength',(select reporting_manager_id from employee_details where employee_id='$employeeid'),'',now(),'$name','0001-01-01 00:00:00','')";
	$str=mysqli_query($db,$strengthareas);
}
else {}
if($employeeid==$name)
{
$getalltasks = mysqli_query($db,"select * from dsr_summary where date=date(now()) and is_active='Y' and employee_id=$employeeid"); 
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ 
											?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													
													
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_summary_id']?>">
															<input type='hidden' id='employeeid' value="<?php echo $name; ?>"></input>
															<input type='hidden' id='employeedate' value="<?php echo $date; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='empcomm_<?php echo $row['dsr_summary_id'] ?>' name='empcomm_<?php echo $row['dsr_summary_id'] ?>' readonly disabled><?php echo $row['employee_comments']; ?></textarea>&emsp;
																<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													</tbody>
												</table>
										<?php 	$i++; } ?>
									 
										
												&nbsp;&nbsp;
										<div class="col-md-12">
												<button type="button" class="btn btn-info pull-right"  onclick="return location.reload();">Close</button>&emsp;
												<input type="button" id="btnSave" value="Finish" name="btnSave" class="btn btn-success pull-right" style = "margin-right: 7px;"></input>  
												
												</div>
										<?php }
}
else { 
$getalltasks = mysqli_query($db,"select * from dsr_summary where is_active='Y' and employee_id=$employeeid and date='$date1'"); 
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													<?php if($row['manager_comments']=='' and $row['is_approved']=='Y') { } 
													else if($row['manager_comments']<>'' and $row['is_approved']=='Y') {?>
														<tr id="<?php echo $employeeid.'|'.$date1.'|'.$row['dsr_summary_id']?>">
															<input type='hidden' id='employeeid1' value="<?php echo $userid; ?>"></input>
															
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='mngrcomm_<?php echo $row['dsr_summary_id'] ?>' name='mngrcomm_<?php echo $row['dsr_summary_id'] ?>'><?php echo $row['manager_comments']; ?></textarea>&emsp;
																	<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													<?php $i++;  } else { ?>
														<tr id="<?php echo $employeeid.'|'.$date1.'|'.$row['dsr_summary_id']?>">
															<input type='hidden' id='employeeid1' value="<?php echo $userid; ?>"></input>
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='mngrcomm_<?php echo $row['dsr_summary_id'] ?>' name='mngrcomm_<?php echo $row['dsr_summary_id'] ?>'><?php echo $row['employee_comments']; ?></textarea>&emsp;
																<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													<?php  $i++; }?>
													</tbody>
												</table>
										<?php } ?>
									 
										
												&nbsp;&nbsp;
										<div class="col-md-12">
												<button type="button" class="btn btn-info pull-right" onclick="return location.reload();">Close</button>&emsp;
												<input type="button" id="btnFinish" value="Save" name="btnFinish" class="btn btn-success pull-right" style = "margin-right: 7px;"></input>  
												
												</div>
												<input type='text' id='count' name='count' value="<?php echo mysqli_num_rows($getalltasks); ?>" style="display:none;"></input>
												<input type='text' id='employeeid' name='employeeid' value="<?php echo $employeeid; ?>" style="display:none;"></input>
												<input type='text' id='edate' name='edate' value="<?php echo $date1; ?>" style="display:none;"></input>
										<?php }
	
	
}
										?>
										
	<script>
	$('.deletetasks').click(function(){
ajaxindicatorstart("Please Wait..");
var tr=$(this).closest("tr");
var wflid=tr[0].id;
var employeeid=tr[0].id.split('|')[0];
var date=tr[0].id.split('|')[1];
      $.ajax({
               type: "POST",
               url: "deletetasks.php",
               data: {
                   wflid: wflid,
               },
               success: function(html) {
				  $.ajax({
						url: "mytasks.php",
						type: "POST",
						data: {
							employeeid:employeeid,
							date:date,
							  },
						success: function(html) {
						$("#display").html(html).show();
						$('#datamodel').modal({
							backdrop: 'static',
							keyboard: false
						})
						}
						
						});
					ajaxindicatorstop();
               }
      });
	});
	</script>
	<script>
$('#btnSave').click(function(e){
	if(!confirm('Are you sure you want to submit your DSR to you Manager?')){
		e.preventDefault();
	}
	else{
		var saveid = $('#employeeid').val();
		var dateval= $('#employeedate').val();
		$.ajax({
        url: "sendTasks.php",
		type: "POST",
		data: {
			saveid:saveid,
			dateval:dateval,
		},
		success: function(data) {
			ajaxindicatorstart();
			location.reload();
			alert('Your Daily Status Report has been sent to your Manager');
		}
});
		
	}
});
$('#btnFinish').click(function(e){
	if(!confirm('Are you sure you want to approve the DSR to the employee?')){
		e.preventDefault();
	}
	else{
		ajaxindicatorstart('Processing... Please Wait....');
		var formData = $('#form').serialize();
		var saveid1 = $('#employeeid').val();
		var dateval1= $('#employeedate').val();
		$.ajax({
			url:"confirmTasks.php",
			cache: false,
			dataType: "json",
			type: "POST",
			data: formData,
			success: function() {
			}
			});
		location.reload();
	}
});

 $('#closeforrelaod').on('click', function(e) {
	  ajaxindicatorstart('Please Wait....');
	  location.reload();
	  //ajaxindicatorstop();
  });
</script>