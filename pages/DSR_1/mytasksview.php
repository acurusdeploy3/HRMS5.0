<?php
session_start();
include('config.php');
$name = $_SESSION['login_user'];
$date = date("Y-m-d");
$strval = explode("|",$_POST["wflid"]);

$dval = $strval[1];
$idval = $strval[0];

if($idval==$name)
{
	$getalltasks = mysqli_query($db,"select * from dsr_summary where date='$dval' and is_active='Y' and employee_id=$idval"); 
//echo "select * from dsr_summary where date=$dval and is_active='Y' and employee_id=$idval";
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_summary_id']?>">
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
else {
$getalltasks = mysqli_query($db,"select * from dsr_summary where date='$dval' and is_active='Y' and employee_id=$idval"); 
//echo "select * from dsr_summary where date=$dval and is_active='Y' and employee_id=$idval";
										if(mysqli_num_rows($getalltasks) < 1){
										}
										else{
											$i = 1;
											while($row = mysqli_fetch_assoc($getalltasks)){ ?>
												<table class="table" id="tskstable" style="font-size:14px;width:100%">
													<tbody>
													<?php if($row['manager_comments']=='' and $row['is_approved']=='Y') { } 
													else if($row['manager_comments']<>'' and $row['is_approved']=='Y') {?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_summary_id']?>">
															<td colspan="1" style="width: 4%;"><?php echo $i;?></td> 
															<td colspan="5">
																<textarea style='resize:none;width: 90%;' rows='2' cols='120' id='empcomm' name='empcomm' readonly disabled><?php echo $row['manager_comments']; ?></textarea>&emsp;
															</td>
														</tr>
														<?php $i++;  } else { ?>
														<tr id="<?php echo $name.'|'.$date.'|'.$row['dsr_summary_id']?>">
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