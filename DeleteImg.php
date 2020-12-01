<?php
include("config.php");
$img = $_POST['id'];
$getDailyInfo = mysqli_query($db,"select value from application_configuration where config_type='DAILY_IMAGE' and module='DASHBOARD'");
			$getDailyInforRow = mysqli_fetch_array($getDailyInfo);
			$DailyInfo = $getDailyInforRow['value'];
			$DailyInfoArray = explode("-",$DailyInfo);
			foreach($DailyInfoArray as $row)
            {
            		if($row!=$img)
                    {
                    	$imgs.= $row.'-';
                    }
            }
mysqli_query($db,"update application_configuration set value='".$imgs."' where config_type='DAILY_IMAGE' and module='DASHBOARD'");
			$getDailyInfo = mysqli_query($db,"select value from application_configuration where config_type='DAILY_IMAGE' and module='DASHBOARD'");
			$getDailyInforRow = mysqli_fetch_array($getDailyInfo);
			$DailyInfo = $getDailyInforRow['value'];
			$DailyInfoArray = explode("-",$DailyInfo);
 foreach ($DailyInfoArray as $key => $value) {
 	if($value!='')
    {
 	$echov .= "
              		<tr data-src=".$value." style='cursor:pointer'>
					
					<td><a target='_blank' href=".$value.">
  						<img src=".$value." style='border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 135px;margin-left: 100px;' alt='Forest' style='width:150px'></a></td>
                    
					<td><a href='#' id='deleteReceipts' class='deleteReceipt'><i class='fa fa-trash-o' ></i></a></td>
	 </tr>";
    }
   } 	
echo $echov;
?>