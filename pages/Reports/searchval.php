<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$userid = $_SESSION['login_user'];
include('config.php');
if (isset($_POST['search'])) {
   $Name = $_POST['search'];
   $usid=$_POST['usid'];
   $rept=$_POST['rept'];
   $valid=",".$usid.",";
   	if($rept=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT_DETAILED')
	{
	$Query2 = "SELECT concat(First_Name,' ',MI,' ',Last_Name,' | ID:',employee_id) as name FROM employee_details WHERE concat(First_Name,Last_Name) LIKE '%$Name%' and (reporting_manager_id='$userid')  and department = '".$_POST["dept"]."'";
   $ExecQuery2 = MySQLi_query($db, $Query2);
//Creating unordered list to display result.
	if(mysqli_num_rows($ExecQuery2) < 1)
	{?>	
	<span style="color:tomato;" id="nodetails">No records found</span>
	<?php }
	else
	{
   echo '
<ul>
   ';
   //Fetching result from database.
   while ($Result1 = MySQLi_fetch_array($ExecQuery2)) {
       ?>
   <!-- Creating unordered list items.
        Calling javascript function named as "fill" found in "script.js" file.
        By passing fetched result as parameter. -->
   <li onclick='fill("<?php echo $Result1['name']; ?>")'>
   <a>
   <!-- Assigning searched result in "Search box" in "search.php" file. -->
       <?php echo $Result1['name']; ?>
   </li></a>
   <!-- Below php code is just for closing parenthesis. Don't be confused. -->
   <?php
}}
	}
	else{
	$chkQuery = "SELECT * FROM `all_fields` where category='Reports_UI' and field_name='Data_Filter'
	and locate('$valid',value);";
   $chkExecQuery = MySQLi_query($db, $chkQuery);
	if(mysqli_num_rows($chkExecQuery) < 1){	
//Creating unordered list to display result.
if($userid == '265') 
    {
    	$Query = "select concat(employee_name,' | ID:',employee_id) as name from (select * from employee order by manager_id, employee_id)manager_sorted,(select @pv := '$userid') initial	where   find_in_set(manager_id, @pv) and length(@pv := concat(@pv, ',', employee_id))and employee_name like '%$Name%' union SELECT concat(First_Name,' ',MI,' ',Last_Name,' | ID:',employee_id) as name FROM employee_details WHERE concat(First_Name,Last_Name) LIKE '%$Name%' and  employee_id='$userid'"; 
   		$ExecQuery = MySQLi_query($db, $Query);
    
    }
    else {
     	$Query = "SELECT concat(First_Name,' ',MI,' ',Last_Name,' | ID:',employee_id) as name FROM employee_details WHERE concat(First_Name,Last_Name) LIKE '%$Name%' and (reporting_manager_id='$userid' or  employee_id='$userid')";
  		$ExecQuery = MySQLi_query($db, $Query);
    }
	if(mysqli_num_rows($ExecQuery) < 1)
	{?>	
	<span style="color:tomato;" id="nodetails">No records found</span>
	<?php }
	else
	{
   echo '
<ul>
   ';
   //Fetching result from database.
   while ($Result = MySQLi_fetch_array($ExecQuery)) {
       ?>
   <!-- Creating unordered list items.
        Calling javascript function named as "fill" found in "script.js" file.
        By passing fetched result as parameter. -->
   <li onclick='fill("<?php echo $Result['name']; ?>")'>
   <a>
   <!-- Assigning searched result in "Search box" in "search.php" file. -->
       <?php echo $Result['name']; ?>
   </li></a>
   <!-- Below php code is just for closing parenthesis. Don't be confused. -->
   <?php
}}}
else
{
	$Query1 = "SELECT concat(First_Name,' ',MI,' ',Last_Name,' | ID:',employee_id) as name FROM employee_details WHERE concat(First_Name,Last_Name) LIKE '%$Name%'  and department = '".$_POST["dept"]."'";
   $ExecQuery1 = MySQLi_query($db, $Query1);
//Creating unordered list to display result.
	if(mysqli_num_rows($ExecQuery1) < 1)
	{?>	
	<span style="color:tomato;" id="nodetails">No records found</span>
	<?php }
	else
	{
   echo '
<ul>
   ';
   //Fetching result from database.
   while ($Result = MySQLi_fetch_array($ExecQuery1)) {
       ?>
   <!-- Creating unordered list items.
        Calling javascript function named as "fill" found in "script.js" file.
        By passing fetched result as parameter. -->
   <li onclick='fill("<?php echo $Result['name']; ?>")'>
   <a>
   <!-- Assigning searched result in "Search box" in "search.php" file. -->
       <?php echo $Result['name']; ?>
   </li></a>
   <!-- Below php code is just for closing parenthesis. Don't be confused. -->
   <?php
}}
}}}

?>
</ul>

