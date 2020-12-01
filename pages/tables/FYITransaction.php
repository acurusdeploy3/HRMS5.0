<?php 
 include("config.php");
 function FYITransaction($employeeid,$transaction,$module,$message,$date)
 {
	 $getEmpName = mysql_query("select concat(First_name,' ',last_name) as name from employee_details where employee_id=$employeeid");
	 $getEmpNameRow = mysql_fetch_array($getEmpName);
  
		$EmpName = $getEmpNameRow['name']; 
		mysql_query("insert into fyi_transaction

		(employee_id,employee_name,transaction,module_name,message,date_of_message,is_active,created_Date_and_time,created_by)

			values

		('$employeeid','$EmpName','$transaction','$module','$message','$date','Y',now(),'Acurus')");
}


?>