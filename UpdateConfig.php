  <?php  
include("config.php");

            $parameter = $_POST['parameter'];
			$value = $_POST['value'];
			$sql="update application_configuration set value='$value' where parameter='$parameter'";
			;
			if(mysqli_query($db,$sql))
			{
				echo "Success";
			}
			else
			{
				echo "fail";
			}
			

//header("Location: DashboardFinal.php");
?>