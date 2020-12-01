<?php
function storeDataInHistory($id, $tabName,$primKey) {
	$HisTable = 'history_'.$tabName;
	$resQuery = mysqli_query($GLOBALS['db'], "insert into ".$HisTable." select 0,now(),e.*  from ".$tabName." e where ".$primKey." = $id");
}

?>
