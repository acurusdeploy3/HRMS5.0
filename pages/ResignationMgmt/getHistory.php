<?php 
require_once("config.php");
$resid= $_POST['id'];
$gethrcommentsquery=mysqli_query($db,"SELECT substring_index(substring_index(change_to,'|',-1),'--',-1)  as name,date_format(modified_date_and_time,'%d %b %Y %h:%i:%s %p') as Modified_Date,Modified_By FROM `audit` where module_name='Resignation Management' and description='HR Comments' and module_id='".$resid."' ");
$ret = '<table class="table" style="font-size:14px;">	
										<thead>
											<th>Comments</th>
											<th>Created Date & Time</th>
											<th>Created By</th>
										</thead>';
while($row3 = mysqli_fetch_assoc($gethrcommentsquery)){
										
			$ret.='<tr>
                        <td style="width:60%">'.$row3['name'].'</td>
						<td style="width:25%">'.$row3['Modified_Date'].'</td>
						<td style="width:15%">'.$row3['Modified_By'].'</td>
										</tr>';

			$i++;
		}
								$ret.='</table>';
echo $ret;
?>