<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$department = $_POST['department'];
$getData = mysql_query("select employee_id,concat(First_Name,' ',Last_Name,' ',MI) as Name from employee_details where department='$department' and job_role not in ('Employee','HR','Accountant','System Admin') and is_active='Y'");
if(mysql_num_rows($getData) > 0) {
    echo "<option value=''>Select from Below</option>";
    while($row = mysql_Fetch_assoc($getData)) {
      echo "<option value='".$row['employee_id']."'>".$row['Name']."</option>";
    }
}

?>