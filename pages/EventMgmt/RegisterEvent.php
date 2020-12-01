<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$IsAttending = 'Yes';
$isMemEligible='N';
$getFamily = mysqli_query($db,"select replace(family_member_name,' ','') as postVariableFam,family_member_name,relationship_with_employee,if(date_of_birth='0001-01-01','--',timestampdiff(year,date_of_birth,curdate())) as age from employee_family_particulars where is_Active='Y' and employee_id=$name;");
if($IsAttending=='Yes')
{
	$ismemtoEligible = mysqli_query($db,"select * from event_common_memento where event_id=$EventID");
	if (mysqli_num_rows($ismemtoEligible)>0)
	{
		$isMemEligible='Y';
	}
	mysqli_query($db,"insert into event_invitation_acceptors
					   select 0,'$EventID','$name','','','$isMemEligible','N','','Y'");
}
$isFamEligible = mysqli_query($db,"select * from event_invitees where event_id='$EventID' and is_family_included='Y'");
if(mysqli_num_rows($isFamEligible)>0)
{
	if(mysqli_num_rows($getFamily)>0)
	{
		while($getFamilyRow = mysqli_fetch_assoc($getFamily))
		{
				$MemberName = $_POST[$getFamilyRow['postVariableFam']];
				if($MemberName=='Yes')
				{
					mysqli_query($db,"insert into event_invitation_acceptors_family (event_id,name,employee_in_relation,relationship,age)
									values
									('$EventID','".$getFamilyRow['family_member_name']."','$name','".$getFamilyRow['relationship_with_employee']."','".$getFamilyRow['age']."')");				
				}
		}
	}
}
			echo "<div class='box-body'>
                              <div class='col-sm-12'>
							  <div class='tab-content'>
							  
							  <h3 style='color:#286090'>Thank you for your Interest. We Appreciate your Input.</h3>
							  <br>
							  <h4 style='color:#286090'>Hope you would have a Great Time!</h4>
								<br>
							  </div>
								</div>
                              </div>";
?>