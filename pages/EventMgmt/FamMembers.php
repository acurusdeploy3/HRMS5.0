<?php
include("config.php");
include("Attendance_Config.php");
$employeeid=$_GET['EmpID'];
$EventID = $_SESSION['eventid'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$employeeid'");
$getNameRow = mysqli_Fetch_array($getName);
$EmpName = $getNameRow['name'];
$getFamily=mysqli_query($db,"select name,age,employee_in_relation,relationship,gender from event_invitation_Acceptors_family where employee_in_relation='$employeeid' and event_id='$EventID' and is_active='Y'");
?>
<h4 class='modal-title'>Guests along with <strong><?php   echo  $EmpName  ?></strong></h4>
<br>
    <table id='FamilyTable' style='padding: 0px;' class='table table-striped'>
							<tr>
								<th>Name</th>
								<th>Relationship</th>
								<th>Age</th>
							</tr>
						<?php
						if(mysqli_num_rows($getFamily)>0)
						{
						while($getFamilyRow = mysqli_fetch_assoc($getFamily))
						{
						?>
						<tr>
						<td><?php  echo $getFamilyRow['name']; ?></td>
						<td><?php  echo $getFamilyRow['relationship']; ?></td>
						<td><?php  echo $getFamilyRow['age']; ?></td>
					</tr>
				<?php
				}
			}
			else
			{
				?>
				
                <tr>
                  <td>No Data Found!</td>
                </tr>
			
				<?php
			}
				?>
              </table>