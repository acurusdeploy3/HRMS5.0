<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);
$id=$_POST['ItemIdOwner'];
session_start();
$name = $_SESSION['login_user'];
$owners = count($_POST['EditItemOwners']);
if($owners>0)
{
	mysqli_query($db,"delete from credentials_ownership where item_id='$id'");	
	for($n=0; $n<$owners; $n++)  
		{  
			$ownerid = $_POST["EditItemOwners"][$n];
			mysqli_query($db,"insert into `credentials_ownership` (item_id,owner_id,created_by) values ($id,$ownerid,$name);");	
		}
}
}
?>