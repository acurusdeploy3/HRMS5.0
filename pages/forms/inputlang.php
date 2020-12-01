<?php
   include('session.php');
?>
<?php
  include("config.php");
 session_start();
 $name=$_SESSION['login_user'];
 $emp=$_SESSION['login_user'];
 $DropdownLanguages = mysqli_query($db,"select distinct(language_name) as name from all_languages where language_name IS NOT NULL order by language_name asc");
 ?>
 <style>
 #clearfields{
	background-color: #da3010;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: Nwrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#da3010;
	color:white;
	border: 1px solid transparent;
}
 </style>
 
<div class="input_language">
 <br><br>
	<div  class="form-group">
				<div class="col-sm-3">
				<label>Name</label>
			    <select class="form-control" name="language_new[]" id="language_new">
				<option value="">Select Language Name</option>
				<?php
					while($row1 = mysqli_fetch_assoc($DropdownLanguages))
						{
  				 ?>
					<option value= "<?php echo $row1['name']." ";?>" ><?php  echo $row1['name']." "; ?></option> 
				<?php 
				    }
			   	 ?>
				 </select>
				 </div>
				<div class="col-sm-2">
				<label>Speak</label> 
				 <select class="form-control" name="can_speak[]" id ="can_speak" >	
				  <option value="Y">Y</option>
				  <option value="N">N</option>
				 </select>
				</div>
				<div class="col-sm-2">
				<label>Read</label> 
				 <select class="form-control" name="can_read[]" id ="can_read">	
                  <option value="Y">Y</option> 
				  <option value="N">N</option>     
                 </select>
				</div>
				<div class="col-sm-2">
				<label>Write</label> 
				 <select class="form-control" name="can_write[]"  id ="can_write">	
				  <option value="Y">Y</option>
				  <option value="N">N</option>   
                 </select>
				</div>
				<div class="col-sm-1">
				<br>
				</div>
			   </div>
			
</div>		
			

	
