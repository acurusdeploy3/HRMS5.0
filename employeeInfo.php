<div class="box-body no-padding">
  <div class="row">
    <div class="col-md-2">
      <img src="<?php echo $RELATIVE_PATH.@$personDetailsData['Employee_image']; ?>" class="img-responsive" alt="User Image" id="profileImage" />
      <a style="margin-left: 30px;" data-toggle="modal" data-target="#uploadModal">Change Photo</a>
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


<!-- Modal -->
<div class="modal fade" id="uploadModal" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Photo</h4>
        </div>
        <div class="modal-body">
          <p class="text-red" id="uploadMsg"></p>
          <div class="form-group">
            <label for="exampleInputFile">New Photo</label>
            <input type="file" id="newPhoto" name="newPhoto" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="uploadNewPhoto">Upload</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
