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
$strengthcheck = "select s.dsr_summary_id,s.employee_id,c.employee_comments from dsr_summary s left join dsr_comments c on s.dsr_summary_id=c.summary_id where employee_comments ='$strength' and employee_id='$employeeid' and is_active='Y' and date='$date'";
$result1 =mysqli_query($db,$strengthcheck);
$list = mysqli_query($db,"select e.reporting_manager_id as tl_id,p.manager_id from employee_details e left join pms_manager_lookup p on e.employee_id=p.employee_id where e.employee_id=$employeeid and e.is_active='Y'");
$getlist=mysqli_fetch_assoc($list);
$tlid=$getlist['tl_id'];
$managerid = $getlist['manager_id'];

 $pmsid = mysqli_query($db,"SELECT reporting_manager_id FROM `employee_details` where reporting_manager_id=$employeeid and is_active='Y' group by reporting_manager_id");
$getid = mysqli_fetch_array($pmsid);
$mngid = $getid['reporting_manager_id'];	 



if(mysqli_num_rows($result1)<1 && $strength !='')
{
	$strengthareas = "Insert into `dsr_comments`(summary_id,employee_comments,tl_comments,manager_comments,created_by,	created_date_time)value ((select dsr_summary_id from dsr_summary where employee_id='$employeeid' and date='$date1'),'$strength','','','$name',now())";
	$str=mysqli_query($db,$strengthareas);
echo "success";
}
else {}
if($employeeid==$name)
{
$getalltasks = mysqli_query($db,"select ds.dsr_summary_id,dc.dsr_id,ds.employee_id,ds.date,dc.employee_comments from dsr_summary ds left join dsr_comments dc on ds.dsr_summary_id=dc.summary_id where date='$date1' and is_active='Y' and ds.employee_id=$employeeid"); 
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ 
											?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													
													
														<tr id="<?php echo $name.'|'.$date1.'|'.$row['dsr_id']?>">
															<input type='hidden' id='employeeid' value="<?php echo $name; ?>"></input>
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='empcomm_<?php echo $row['dsr_id'] ?>' name='empcomm_<?php echo $row['dsr_id'] ?>' readonly disabled><?php echo $row['employee_comments']; ?></textarea>&emsp;
																<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													</tbody>
												</table>
										<?php 	$i++; } ?>
									 
										
												&nbsp;&nbsp;
												
										<?php $getalltask = mysqli_query($db,"select dc.dsr_id,dc.employee_comments from dsr_comments dc left join dsr_summary ds on dc.summary_id=ds.dsr_summary_id where date='$date1' and is_active='Y' and ds.employee_id=$employeeid"); 
										
                                        if($name== $mngid && mysqli_num_rows($getalltask)>=1 ){?> 						
										<div class="col-md-12">
												<button type="button" class="btn btn-info pull-right"  class="close btn-close" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close"  onclick="return location.reload();">Save & Close</button>&emsp;
												</div>
										<?php } elseif(mysqli_num_rows($getalltask)>=1 ) {?>
										<div class="col-md-12">
										        <button type="button" class="btn btn-info pull-right"  class="close btn-close" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close">Save & Close</button>&emsp;
												<input type="button" id="btnSave" value="Submit" name="btnSave" class="btn btn-success pull-right" style = "margin-right: 7px;"></input>  	
												</div>
												
										<?php } else{?>
											<div class="col-md-12">
												<button type="button" class="btn btn-info pull-right" class="close btn-close" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close" onclick="return location.reload();">Close</button>&emsp; 	
												</div>
										<?php } ?>
										<?php }
}
elseif($name==$managerid) { 
$getalltasks = mysqli_query($db,"select ds.dsr_summary_id,dc.dsr_id,ds.employee_id,ds.date,ds.is_approved,dc.employee_comments,dc.tl_comments,dc.manager_comments  from dsr_summary ds left join dsr_comments dc on ds.dsr_summary_id=dc.summary_id where is_active='Y' and ds.employee_id=$employeeid and date='$date1'"); 
if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													<?php  
													if($row['manager_comments']<>'') {?>
														<tr id="<?php echo $employeeid.'|'.$date1.'|'.$row['dsr_id']?>">
															<input type='hidden' id='employeeid1' value="<?php echo $userid; ?>"></input>
															
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='mngrcomm_<?php echo $row['dsr_id'] ?>' name='mngrcomm_<?php echo $row['dsr_id'] ?>'><?php echo $row['manager_comments']; ?></textarea>&emsp;
																	<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													<?php $i++;  } else if($row['tl_comments']<>'') { ?>
														<tr id="<?php echo $employeeid.'|'.$date1.'|'.$row['dsr_id']?>">
															<input type='hidden' id='employeeid1' value="<?php echo $userid; ?>"></input>
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='mngrcomm_<?php echo $row['dsr_id'] ?>' name='mngrcomm_<?php echo $row['dsr_id'] ?>'><?php echo $row['tl_comments']; ?></textarea>&emsp;
																<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													<?php  $i++; } else if($row['tl_comments']=='') { ?>
														<tr id="<?php echo $employeeid.'|'.$date1.'|'.$row['dsr_id']?>">
															<input type='hidden' id='employeeid1' value="<?php echo $userid; ?>"></input>
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='mngrcomm_<?php echo $row['dsr_id'] ?>' name='mngrcomm_<?php echo $row['dsr_id'] ?>'><?php echo $row['employee_comments']; ?></textarea>&emsp;
																<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													<?php  $i++; }?>
													</tbody>
												</table>
										<?php } ?>
									 
										
												&nbsp;&nbsp;
										<div class="col-md-12">
												<button type="button" class="btn btn-info pull-right" class="close btn-close" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close">Close</button>&emsp;
												<input type="button" id="btnFinish" value="Save" name="btnFinish" class="btn btn-success pull-right" style = "margin-right: 7px;"></input>  
												
												</div>
												<input type='text' id='count' name='count' value="<?php echo mysqli_num_rows($getalltasks); ?>" style="display:none;"></input>
												<input type='text' id='employeeid' name='employeeid' value="<?php echo $employeeid; ?>" style="display:none;"></input>
												<input type='text' id='edate' name='edate' value="<?php echo $date1; ?>" style="display:none;"></input>
										<?php }
	
	
}

										
else { 
$getalltasks = mysqli_query($db,"select dc.summary_id,dc.dsr_id,ds.employee_id,ds.date,ds.is_approved,dc.employee_comments,dc.tl_comments  from dsr_summary ds left join dsr_comments dc on ds.dsr_summary_id=dc.summary_id where is_active='Y' and ds.employee_id=$employeeid and date='$date1'"); 
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													<?php 
													if($row['tl_comments']<>'') {?>
														<tr id="<?php echo $employeeid.'|'.$date1.'|'.$row['dsr_id']?>">
															<input type='hidden' id='employeeid1' value="<?php echo $userid; ?>"></input>
															
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='tlcomm_<?php echo $row['dsr_id'] ?>' name='tlcomm_<?php echo $row['dsr_id'] ?>'><?php echo $row['tl_comments']; ?></textarea>&emsp;
																	<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													<?php $i++;  } else if($row['tl_comments']==''){ ?>
														<tr id="<?php echo $employeeid.'|'.$date1.'|'.$row['dsr_id']?>">
															<input type='hidden' id='employeeid1' value="<?php echo $userid; ?>"></input>
															<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='110' id='tlcomm_<?php echo $row['dsr_id'] ?>' name='tlcomm_<?php echo $row['dsr_id'] ?>'><?php echo $row['employee_comments']; ?></textarea>&emsp;
																<a id='deletetasks' class='deletetasks'><i class='fa fa-trash'></i></a>
															</td>
														</tr>
													<?php  $i++; }?>
													</tbody>
												</table>
										<?php } ?>
									 
										
												&nbsp;&nbsp;
										<div class="col-md-12">
												<button type="button" class="btn btn-info pull-right" class="close btn-close" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close">Close</button>&emsp;
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
var dsrid=tr[0].id.split('|')[2];
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
	if(!confirm('Are you sure you want to submit your DSR')){
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
			location.reload();
			alert('Your Daily Status Report has been sent');
			
		}
});
		
	}
});
$('#btnFinish').click(function(e){
	if(!confirm('Are you sure you want to change the DSR to the employee?')){
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
		//location.reload();
		ajaxindicatorstop();
	}
});

 $('#closeforrelaod').on('click', function(e) {
	  //ajaxindicatorstart('Please Wait....');
	  location.reload();
	  //ajaxindicatorstop();
  });
</script>