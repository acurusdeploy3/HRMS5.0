<?php
//require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

$id = $_POST['EmpIdvalue'];
$res = mysqli_query($db,"select first_name from employee_details where employee_id=$id ");
}
?>
<style>
.modal-backdrop {
    position: unset ! important;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed ! important; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
}
/* The Close Button 1  */

.close1 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close1:hover,
.close1:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
<div id="myModal" class="modal">
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close1">&times;</span>
						<p></p>
						<form id="showlistofreports" >
						<label for="inputStatusChange" class="col-sm-3 control-label" style="text-align:left">List Of Reports<span class="error">*  </span></label>	
				<?php
                if(mysqli_num_rows($res) < 1){
                  echo "No Results Found";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($res)){
					echo $row['Report_ID'];
                    $i++;
                  }
				}
                ?>	
				
					<br>
					<input id="addallbtn"  name="addallbtn" type="submit" class = "btn btn-primary" value = "Save"/>
						</form>
							</div>
			</div>
			
				<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close1")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
	$('.modal-backdrop').remove();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>