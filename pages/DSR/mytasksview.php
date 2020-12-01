<?php
session_start();
include('config.php');
$name = $_SESSION['login_user'];
$date = date("Y-m-d");
$strval = explode("|",$_POST["wflid"]);

$dval = $strval[1];
$idval = $strval[0];

$list = mysqli_query($db,"select e.reporting_manager_id as tl_id,p.manager_id from employee_details e left join pms_manager_lookup p on e.employee_id=p.employee_id where e.employee_id=$idval and e.is_active='Y'");
$getlist=mysqli_fetch_assoc($list);

$tlid=$getlist['tl_id'];
$managerid = $getlist['manager_id'];

if($idval==$name)
{
	$getalltasks = mysqli_query($db,"select s.dsr_summary_id,c.dsr_id,s.is_approved,c.employee_comments,c.manager_comments from dsr_summary s left join  dsr_comments c on s.dsr_summary_id=c.summary_id where date='$dval' and  employee_id=$idval"); 
//echo "select * from dsr_summary where date=$dval and is_active='Y' and employee_id=$idval";
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['employee_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php  $i++;  ?>
													</tbody>
												</table>
										<?php 	}
										}
}
elseif($name==$managerid) {
$getalltasks = mysqli_query($db,"select s.dsr_summary_id,c.dsr_id,s.is_approved,c.employee_comments,c.manager_comments from dsr_summary s left join  dsr_comments c on s.dsr_summary_id=c.summary_id where date='$dval' and employee_id=$idval"); 
//echo "select * from dsr_summary where date=$dval and is_active='Y' and employee_id=$idval";
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													<?php  
													 if($row['manager_comments']<>'' and $row['is_approved']=='Y') {?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['manager_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php $i++;  } else { ?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['employee_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php  $i++; } ?>
													</tbody>
												</table>
										<?php 	}
										}
}
elseif ($name==$tlid)  {
$getalltasks = mysqli_query($db,"select s.dsr_summary_id,c.dsr_id,s.is_sent,c.employee_comments,c.manager_comments,c.tl_comments from dsr_summary s left join  dsr_comments c on s.dsr_summary_id=c.summary_id where date='$dval' and employee_id=$idval"); 
//echo "select * from dsr_summary where date=$dval and is_active='Y' and employee_id=$idval";
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													<?php 
													if($row['tl_comments']<>'' and $row['is_sent']=='Y') {?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['tl_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php $i++;  } else { ?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['employee_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php  $i++; } ?>
													</tbody>
												</table>
										<?php 	}
										}
}
else {
$getalltasks = mysqli_query($db,"select s.dsr_summary_id,c.dsr_id,s.is_sent,c.employee_comments,c.manager_comments,c.tl_comments from dsr_summary s left join  dsr_comments c on s.dsr_summary_id=c.summary_id where date='$dval' and employee_id=$idval"); 
//echo "select * from dsr_summary where date=$dval and is_active='Y' and employee_id=$idval";
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													<?php 
													if($row['manager_comments']<>'') {?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['manager_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php $i++;  }
														
														elseif($row['tl_comments']<>'') {?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['tl_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php $i++;  }

														else { ?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['employee_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php  $i++; } ?>
													</tbody>
												</table>
										<?php 	}
										}
}

										?>
										