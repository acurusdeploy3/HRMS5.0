<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$IsMemntoProvided= mysqli_real_escape_string($db,$_POST['IsMemntoProvided']);
$IsFoodServed= mysqli_real_escape_string($db,$_POST['IsFoodServed']);
$ApproxFoodCount = $_POST['ApproxFoodRequired'];
$AdditionalFoodCount = $_POST['AdditionalRequired'];
$TotalCount =$AdditionalFoodCount+$ApproxFoodCount;

$MementoDesc= mysqli_real_escape_string($db,$_POST['MementoDesc']);
$QuantityBought= mysqli_real_escape_string($db,$_POST['QuantityBought']);
$QuantityRequired= mysqli_real_escape_string($db,$_POST['QuantityRequired']);
$TotalQuantity = $QuantityRequired+$QuantityBought;
if($IsMemntoProvided=='Yes')
{
	$checkExist = mysqli_query($db,"select * from event_common_memento where event_id='$EventID'");
	if(mysqli_num_rows($checkExist)==0)
		{
		mysqli_query($db,"insert into event_common_memento (event_id,memento_desc,quantity_bought,approx_required,additional_required) values
					('$EventID','$MementoDesc','$TotalQuantity','$QuantityBought','$QuantityRequired')");
		}
		else
		{
				mysqli_query($db,"update event_common_memento set memento_desc='$MementoDesc',quantity_bought='$TotalQuantity',approx_required='$QuantityBought',additional_required='$QuantityRequired' where event_id='$EventID'");
		}
}
else
{
	mysqli_query($db,"delete from event_common_memento where event_id='$EventID'");
}
if($IsFoodServed=='Yes')
{
	$FoodType = count($_POST["FoodType"]);
	mysqli_query($db,"update active_events set is_lunch_included='N',is_snacks_included='N',is_breakfast_included='N',is_dinner_included='N',approx_food_count='$ApproxFoodCount',total_food_count='$TotalCount',additional_food_count='$AdditionalFoodCount' where event_id='$EventID'");
		if($FoodType > 0)
			{
				for($m=0; $m<$FoodType; $m++)  
				{  	
					$Ty = $_POST["FoodType"][$m];	
					mysqli_query($db,"update active_events set $Ty='Y' where event_id='$EventID'");
					echo "update active_events set $Ty='Y' where event_id='$EventID'";
				}
			}
}
else
{
	mysqli_query($db,"update active_events set is_lunch_included='N',is_snacks_included='N',is_breakfast_included='N',is_dinner_included='N' where event_id='$EventID'");
}



	header("Location: EventReq.php?id=$EventID");
?>