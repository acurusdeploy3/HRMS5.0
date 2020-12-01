<?php
session_start();
include('config.php');
$name = $_SESSION['login_user'];

function StoreDatainHistory($Id, $tabName,$primKey)
		{
			
			$HisTable = 'history_'.$tabName;
			$resQuery = mysqli_query($db,"insert into ".$HisTable." SELECT 0,now(),e.*  FROM ".$tabName." e where ".$primKey." = '$Id'");
			return $resQuery;
		}
    
 
 
 