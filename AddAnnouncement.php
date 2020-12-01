<?php 
 include("config.php");
session_start();
$AddedDept= $_POST['inputDept'];
$Businessunit= $_POST['BuSelect'];
$Content= $_POST['ContentText'];
$user = $_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(First_name,' ',Last_name) as Name from employee_details where employee_id='$user'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['Name'];
$sql="INSERT INTO company_announcements(date_of_news,news_content,status,business_unit,content_full,created_by) 
VALUES (curdate(),'$AddedDept','Active','$Businessunit','$Content','$EmpName')";
$result=mysqli_query($db,$sql);
header("Location: DashboardFinal.php");
?>