<?php 
if(isset($_REQUEST))
{
include("config.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$id=$_POST['ItemIdHidden'];
$qry1 = "select id, category, physical_location, logical_location, user_name, comments, title, expiry_cycle, last_renewed_date, created_date_time, modified_date_time, created_by, modified_by, next_renewal_date, is_active
from credentials_master where id='$id'";
$rec1 = mysqli_query($db,$qry1);
$res1 = mysqli_fetch_array($rec1);
$category = $res1['category'];
if($category == 'Application Accounts')
{
	$lbl = 'Application Name';
	$dis = 'disabled';
}
else
{
	$lbl = 'Physical Location';
	$dis = '';
}
$getCategoryNames = mysqli_query($db,"select category from all_categories");
$getExpiryCycle = mysqli_query($db,"select cycle from expiry_cycle");
$getOwners = mysqli_query($db,"select owner_id from credentials_ownership where item_id='$id'");
$getEmployees = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name) as Name FROM `employee_details` where is_Active='Y'");
$outVariable='';
$outVariable .= '<div class="box box-info">

          
		  <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
			  
                 <label>Category</label>
				 <input type="hidden" value="'.$res1['id'].'" name="ItemId" id = "ItemId">
                <select class="form-control select2" id="CategorySelEdit" name="CategorySel" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>';
				
				while ($getCategoryNamesRow = mysqli_fetch_assoc($getCategoryNames))
				{ 
					if($getCategoryNamesRow['category']==$res1['category'])
					{
				$outVariable.= '<option value="'.$getCategoryNamesRow['category'].'" selected>'.$getCategoryNamesRow['category'].'</option>';
					}
					else
					{
							$outVariable.= '<option value="'.$getCategoryNamesRow['category'].'">'.$getCategoryNamesRow['category'].'</option>';
					}
				}	 
		$outVariable.='</select>
              </div>
			</div>	
		
			<div class="col-md-6">
					<div class="form-group">
						 <label id="phyloclblEdit">'.$lbl.' <span class="text-red">*</span></label>
						<input type="text" name="PhysicalLocationText" value="'.$res1['physical_location'].'" class="form-control" required id="PhysicalLocationText" placeholder="Enter Physical Location">
					  </div>
			</div>	
			<div class="col-md-6">
					<div class="form-group">
						 <label>Logical Location <span class="text-red">*</span></label>
						<input type="text" name="LogicalLocationText" value="'.$res1['logical_location'].'" class="form-control" required id="LogicalLocationTextEdit" placeholder="Enter Logical Location" '.$dis.'>
					  </div>
			</div>
			<div class="col-md-6">
					<div class="form-group">
						 <label>UserName <span class="text-red">*</span></label>
						<input type="text" name="userNameText" class="form-control" value="'.$res1['user_name'].'" required id="userNameText" placeholder="Enter UserName">
					  </div>
			</div>
			<div class="col-md-6">
              <div class="form-group">
			  
                 <label>Expiry Cycle (In days)<span class="text-red">*</span></label>
                <select class="form-control select2" id="ExpiryCycle" name="ExpiryCycle" style="width: 100%;" required disabled>
				';
				while ($getExpiryCycleRow = mysqli_fetch_assoc($getExpiryCycle))
				{
				 if($getExpiryCycleRow['cycle']==$res1['expiry_cycle'])
					{		
                  $outVariable.= '<option value="'.$getExpiryCycleRow['cycle'].'" selected>'.$getExpiryCycleRow['cycle'].'</option>';
					}
					else
					{
							$outVariable.= '<option value="'.$getExpiryCycleRow['cycle'].'">'.$getExpiryCycleRow['cycle'].'</option>';
					}
				}
				
                $outVariable .= '</select>
              </div>
			</div>			
			<div class="col-md-6">
			 <div class="form-group">
                <label>Next Renewal Date <span class="text-red">*</span></label>

             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text"  name="NextRenewalDate" value="'.$res1['next_renewal_date'].'" class="form-control pull-right" id="datepicker11" placeholder="Pick a date" disabled required>
                </div>
              </div>
			  </div>';
				
				

			$outVariable.=' 
          <br>
<div class="col-md-12">
<div class="col-md-12">
<div class="form-group">
                 <label>Comments / SOP <span class="text-red">*</span></label>
                <textarea rows="8" column="20" id="Comments" name="Comments" maxlength="2000" class="is-maxlength" required resize="no" style="width:100%;resize:none;">'.$res1['comments'].'</textarea>
              </div>
</div>
</div>
<br>
          </div>
</div>
</div>';

echo $outVariable;
}
?>