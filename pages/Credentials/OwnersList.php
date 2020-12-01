<?php 
include('config.php');
$id = $_GET['id'];
$getAlllist = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name) as Name FROM `employee_details` where is_Active='Y'");
$getOnersList = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name) as Name FROM `employee_details` where is_Active='Y' and employee_id in (select owner_id from credentials_ownership where item_id='$id')");

	if(mysqli_num_rows($getOnersList)>0)
	 {
	   foreach ($getAlllist as $row1) 
	   {
		 foreach ($getOnersList as $row) 
	   
		  {
			   if($row1['employee_id']==$row['employee_id'])
				 {
					 $sel='Y';
					 break;
				 }
				 else
				 {
					 $sel='N';
				 }
		   }
	 if($sel=='Y')
{
?>
<option value="<?php echo $row1['employee_id'] ?>" selected><?php echo $row1['Name']; ?></option>
<?php
}
else {
?>
 <option value="<?php echo $row1['employee_id'] ?>"><?php echo $row1['Name']; ?></option>
<?php

}
}
}
else
{
	while($getAlllistRow = mysqli_fetch_assoc($getAlllist))
	   
		  {
			  
			  ?>
			<option value="<?php echo $getAlllistRow['employee_id'] ?>"><?php echo $getAlllistRow['Name']; ?></option>  
			  <?php 
			  
		  }
}
			  ?>