<?php
include("config2.php");

$leave = $_POST['leavetype'];
$cl = $_POST['Casual'];
$pl = $_POST['Previlege'];
$sick = $_POST['Sick'];
$Comp = $_POST['COff'];
$NoDays = $_POST['Days'];
$sp =  $_POST['SP'];
$sc =  $_POST['SC'];
$out= "not_taken";
if($leave == 'Casual')
{
	if($NoDays>$cl)
	{
		 $out = "taken";
	}
}
if($leave =='Sick')
{
	if($NoDays>$sick)
	{
		 $out = "	";
	}
}
if($leave =='Privilege')
{
	if($NoDays>$pl)
	{
		 $out = "taken";
	}
}

if($leave =='Compensatory-Off')
{
	if($NoDays>$Comp)
	{
		 $out = "taken";
	}
}
if($leave =='Privilege & Sick')
{
	if($NoDays>$sp)
	{
		 $out = "taken";
	}
}
if($leave =='Casual & Sick')
{
	if($NoDays>$sc)
	{
		 $out = "taken";
	}
}

  	
  	  echo $out;
  	exit();

?>