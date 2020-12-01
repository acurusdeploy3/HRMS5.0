<?php
session_start();
include('config.php');
$name = $_SESSION['login_user'];
function StoreDatainHistory($Id, $tabName,$primKey)
		{
			
			$HisTable = 'history_'.$tabName;
			$resQuery = mysql_query("insert into ".$HisTable." SELECT 0,now(),e.*  FROM ".$tabName." e where ".$primKey." =$Id");
		
		}
    
 
 
 