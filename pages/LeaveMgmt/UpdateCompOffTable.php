<?php
include("config.php");
include("Attendance_Config.php");
$employeeid=$_GET['id'];
include("config.php");
include("Attendance_Config.php");
$getExtraHours = mysqli_query($att,"select extra_hours_tracker_id,employee_id,date,from_time,to_time,SUBSTRING(extra_hours, 1, 5) as Extra_hours,
((SUBSTRING(extra_hours, 1, 2)*60)+SUBSTRING(extra_hours, 4, 2)) as extrahours from extra_hours_tracker where employee_id in
 ($employeeid) and is_approved='N' and is_applied='N'");
if(mysqli_num_rows($getExtraHours)>0)
				{
					while($getExtraHoursRow=mysqli_fetch_assoc($getExtraHours))
					{
				echo "<form id='update' name='update' method='POST'>";
				echo "<tr>";
				echo " <td style='display:none;'><input type='hidden' name='idtxt' class='idtxt' value='".$getExtraHoursRow['extra_hours_tracker_id']."'/></td>";
				echo "<td style='display:none;'><input type='hidden' name='extraHoursinMins' id='extraHoursinMins' class='extraHoursinMins' value='".$getExtraHoursRow['extrahours']."'/></td>";
					echo " <td>".$getExtraHoursRow['employee_id']."</td>";
					echo " <td>";
					 $getName = mysqli_query($db,"select concat(First_name,' ',last_name) as name from employee_Details where employee_id='".$getExtraHoursRow['employee_id']."'");
								$getNameRow = mysqli_fetch_array($getName);
				    echo $getNameRow['name'];
					echo " </td>";
					echo "<td>".$getExtraHoursRow['date']."</td>";
					echo "<td><span class='badge bg-green'>".$getExtraHoursRow['Extra_hours']."</span></td>	";
					echo "<td>";
					echo "<select id='Hours' name='Hours' class='Hours'> ";
					echo "<option value='00'>00</option>";
					echo "<option value='01'> 01</option>";
					echo" <option value='02'> 02</option>";
					echo "<option value='03'> 03</option>";
					echo "<option value='04'> 04</option>";
					echo "<option value='05'> 05</option>";
					echo" <option value='07'> 07</option>";
					echo "<option value='09'> 09</option>";
					echo "<option value='11'>11</option>";
					 echo "<option value='12'> 12</option>";
					 echo "</select> : &nbsp;&nbsp; ";
					echo " <select id='Minutes' name='Minutes' class='Minutes'> ";
					 echo "<option value='00'>00</option>";
					echo "<option value='05'> 05</option>";
					echo "<option value='10'> 10</option>";
					echo "<option value='15'> 15</option>";
					echo "<option value='20'> 20</option>";
					echo "<option value='25'> 25</option>";
					echo "<option value='30'> 30</option>";
					echo "<option value='35'> 35</option>";
					echo "<option value='40'>40</option>";
					echo "<option value='45'> 45</option>";
					 echo "<option value='50'> 50</option>";
					echo "<option value='55'> 55</option>";
					echo "</select>";
					echo "</td>	";
					echo   "<td>   <textarea name='reason' id='reason' rows='3' cols='13' maxlength='200' class='reason' required='required' required style='width: 100%;'></textarea></td>";
					 echo "<td><input type='submit' name='save_btn' class='Approve' value='Approve' style='font-size:1em;'/></td>";
				  echo "</form>";
				echo "</tr>";
					   }
				 
					}
				else
				{
			
				echo "<tr>";
				echo "<td>No Data Found!</td>";
				echo "</tr>";
				
				}
		
?>