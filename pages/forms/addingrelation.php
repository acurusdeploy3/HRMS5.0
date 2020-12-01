<?php 
include("config.php");
if(isset($_REQUEST))
{
error_reporting(E_ALL && ~E_NOTICE);
$addrelation=mysqli_real_escape_string($db,$_REQUEST['inputRelation']);
$sql="INSERT INTO all_relations(relation,created_date_and_time,created_by) VALUES 
('$addrelation',now(),'Acurus')";
$result1=mysqli_query($db,$sql);
}
?>