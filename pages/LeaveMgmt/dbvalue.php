<!DOCTYPE html>
<html>
<body>

<?php
session_start();
$login_session = $_SESSION["login_user"];
include_once("db.php");
$sql = "SELECT  First_Name,MI, Last_Name  FROM employee_details where employee_id = '".$login_session."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo  "". $row["First_Name"]. " ".$row["MI"]." " . $row["Last_Name"] . " <br> ";        
    }
} 
?> 
</body>
</html>