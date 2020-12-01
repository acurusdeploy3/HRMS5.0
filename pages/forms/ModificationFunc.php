<?php
session_start();
include('config.php');
$login_session = $_SESSION['login_user'];
function StoreDatainHistory($Id_column, $Id_value, $tabName)
		{
			$HisTable = $tabName.'_history';
			$resQuery = mysql_query("insert into ".$HisTable." SELECT 0,e.*  FROM ".$tabName." e where ".$Id_column." =$Id_value");
				
				
			}
    
 
 
 