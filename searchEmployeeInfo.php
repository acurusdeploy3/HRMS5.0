<div class="box-body no-padding">
  <div class="row">
    <div class="col-md-2">
      <img src="<?php echo $RELATIVE_PATH.@$personDetailsData['Employee_image']; ?>" class="img-responsive" alt="User Image" id="profileImage" />
    </div>
    <div class="col-md-10">
      <div id="personalDetailsDiv">
        <p><b>Employee Name </b>&nbsp;:&nbsp;<?php echo @$personDetailsData['First_Name']." ".@$personDetailsData['MI']." ".@$personDetailsData['Last_Name']; ?></p>
        <p><b>Employee Id </b>&nbsp;:&nbsp;<?php echo @$personDetailsData['employee_id']; ?></p>
        <p><b>Email Id </b>&nbsp;:&nbsp;<?php echo @$personDetailsData['Official_Email']; ?></p>
        <p><b>Contact Number </b>&nbsp;:&nbsp;<?php echo @$personDetailsData['country_code'].@$personDetailsData['Primary_Mobile_Number']; ?></p>
      </div>
    </div>
  </div>
</div>
