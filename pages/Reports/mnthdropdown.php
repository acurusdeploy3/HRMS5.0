<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$userid = $_SESSION['login_user'];
$date = date("Y");
include('config.php');
if (!empty($_POST["Year"])) {
        $Year = $_POST["Year"]; 
		$selectmonth = mysqli_query($db,"SELECT * FROM `month_lookup` where id=month(date_sub(date(now()),interval 1 month))");
		$monthvalue =  mysqli_fetch_array($selectmonth);
		if($Year==$date)
		{
			$mnthlist=mysqli_query($db,"SELECT ID,month_name FROM `month_lookup` where id<month(now()) and id <>'".$monthvalue['ID']."' order by ID");
		}
		else
		{
			$mnthlist = mysqli_query($db,"SELECT * FROM `month_lookup` where id <>'".$monthvalue['ID']."' order by ID");
		}
		
		?>
		 <option value="<?php echo $monthvalue['ID']; ?>" selected><?php echo $monthvalue	['month_name']; ?> </option>  
		<?php
		        foreach ($mnthlist as $val){
?>
            <option value="<?php echo $val["ID"];?>"><?php echo $val["month_name"];?>
    </option>       
<?php
        }
}
?>
</ul>

