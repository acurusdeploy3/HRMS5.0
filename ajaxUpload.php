<?php
require_once("config.php");
require_once("queries.php");

function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

if(isset($_POST['action']) && $_POST['action'] == 'updateDeliverablesPerformance'){

  $measure_id = (isset($_POST['measure_id']) && $_POST['measure_id'])?$_POST['measure_id']:0;
  $criteria_id = (isset($_POST['criteria_id']) && $_POST['criteria_id'])?$_POST['criteria_id']:0;
  $score = (isset($_POST['score']) && $_POST['score'])?$_POST['score']:0;
  $comments = (isset($_POST['comments']) && $_POST['comments'])?$_POST['comments']:'';
  $category_id = (isset($_POST['category_id']) && $_POST['category_id'])?$_POST['category_id']:1;

  $performanceScoreQuery = mysqli_query($db,"select performance_score_id,a.measure_id as measure_id from performance_scores a, performance_measures b where a.measure_id = b.measure_id and employee_id = $empId and criteria_id = $criteria_id and a.is_Active = 'Y' and a.review_id = $currentReviewId");

  if(mysqli_num_rows($performanceScoreQuery) > 0){
    $performanceScoreData = mysqli_fetch_assoc($performanceScoreQuery);
    if($measure_id == $performanceScoreData['measure_id']){
      mysqli_query($db,"update performance_scores set score = $score, appraisee_comments = '$comments' where performance_score_id = ".$performanceScoreData['performance_score_id']);
    }else{
      mysqli_query($db,"update performance_scores set is_Active = 'N' where performance_score_id = ".$performanceScoreData['performance_score_id']);
      mysqli_query($db,"insert into performance_scores(score,measure_id,appraisee_comments,employee_id,review_id) values($score,$measure_id,'$comments',$empId,$currentReviewId)");
    }
  }else{
    mysqli_query($db,"insert into performance_scores(score,measure_id,appraisee_comments,employee_id,review_id) values($score,$measure_id,'$comments',$empId,$currentReviewId)");
  }

  $overallQuery = mysqli_query($db,"select perfromance_criteria_sub_category_name,SUM(score) as totalScore from performance_criteria_sub_categories_master a,performance_criteria_master b,performance_measures c,performance_scores d where a.perfromance_criteria_sub_category_id = b.perfromance_criteria_sub_category_id and b.criteria_id = c.criteria_id and c.measure_id = d.measure_id and perfromance_criteria_category_id = $category_id and employee_id = $empId and d.is_Active = 'Y' group by perfromance_criteria_sub_category_name");

  $str = "";
  $totalScore = 0;
  while($overallData = mysqli_fetch_assoc($overallQuery)){
    $str .= "<th>".$overallData['perfromance_criteria_sub_category_name']."</th><td>".$overallData['totalScore']."</td>";
    $totalScore += $overallData['totalScore'];
  }

  if($category_id == 1){
    $title = 'Deliverables(80%)';
    $percentage = number_format(($totalScore * 0.8), 2, '.', '');
  }else{
    $title = 'Culture(20%)';
    $percentage = number_format(($totalScore * 0.2), 2, '.', '');
  }

  $satisfactory = getPercentage($totalScore);

  $str .= "#<th>Total Weightage</th><td>".$totalScore."</td><th>Weightage of ".$title."</th><td>".$percentage."</td><th>Assessment</th><td>".$satisfactory."</td>";

  echo $str;
}

if(isset($_POST['action']) && $_POST['action'] == 'getMeasureWeightage'){
  $measure_id = (isset($_POST['measure_id']) && $_POST['measure_id'])?$_POST['measure_id']:0;
  $criteria_id = (isset($_POST['criteria_id']) && $_POST['criteria_id'])?$_POST['criteria_id']:0;

 // $measureScoreQuery = mysqli_query($db,"select measure_score,measure_weightage from performance_measures where measure_id = $measure_id and criteria_id = $criteria_id");
$measureScoreQuery = mysqli_query($db,"select measure_score,criteria_weightage from performance_measures a,performance_criteria_master b where a.criteria_id = b.criteria_id and measure_id = $measure_id and a.criteria_id = $criteria_id");

  $data = mysqli_fetch_assoc($measureScoreQuery);
 //echo @$data['measure_score']."#".@$data['measure_weightage']."#".(@$data['measure_score'] * @$data['measure_weightage']);
 
 echo @$data['measure_score']."#".@$data['criteria_weightage']."#".(@$data['measure_score'] * @$data['criteria_weightage'] / 5);

}

if(isset($_POST['action']) && $_POST['action'] == 'updateManagerDeliverablesPerformance'){

  $measure_id = (isset($_POST['measure_id']) && $_POST['measure_id'])?$_POST['measure_id']:0;
  $criteria_id = (isset($_POST['criteria_id']) && $_POST['criteria_id'])?$_POST['criteria_id']:0;
  $score = (isset($_POST['score']) && $_POST['score'])?$_POST['score']:0;
  $score_id = (isset($_POST['score_id']) && $_POST['score_id'])?$_POST['score_id']:0;
  $appraisee_comments = (isset($_POST['appraisee_comments']) && $_POST['appraisee_comments'])?$_POST['appraisee_comments']:'';
  $manager_comments = (isset($_POST['manager_comments']) && $_POST['manager_comments'])?$_POST['manager_comments']:'';
  $category_id = (isset($_POST['category_id']) && $_POST['category_id'])?$_POST['category_id']:1;
  $appraiseeId = (isset($_POST['employee_id']) && $_POST['employee_id'])?$_POST['employee_id']:$empId;

  $performanceScoreQuery = mysqli_query($db,"select * from performance_scores where employee_id = $appraiseeId and performance_score_id = $score_id and a.is_Active = 'Y'");

  mysqli_query($db,"update performance_scores set measure_id = $measure_id, manager_comments = '$manager_comments', score = $score, appraisee_comments = '$appraisee_comments' where performance_score_id = ".$score_id);

  $overallQuery = mysqli_query($db,"select perfromance_criteria_sub_category_name,SUM(score) as totalScore from performance_criteria_sub_categories_master a,performance_criteria_master b,performance_measures c,performance_scores d where a.perfromance_criteria_sub_category_id = b.perfromance_criteria_sub_category_id and b.criteria_id = c.criteria_id and c.measure_id = d.measure_id and perfromance_criteria_category_id = $category_id and employee_id = $appraiseeId and d.is_Active = 'Y' group by perfromance_criteria_sub_category_name");

  $str = "";
  $totalScore = 0;
  while($overallData = mysqli_fetch_assoc($overallQuery)){
    $str .= "<th>".$overallData['perfromance_criteria_sub_category_name']."</th><td>".$overallData['totalScore']."</td>";
    $totalScore += $overallData['totalScore'];
  }

  if($category_id == 1){
    $title = 'Deliverables(80%)';
    $percentage = number_format(($totalScore * 0.8), 2, '.', '');
  }else{
    $title = 'Culture(20%)';
    $percentage = number_format(($totalScore * 0.2), 2, '.', '');
  }

  $satisfactory = getPercentage($totalScore);

  $str .= "#<th>Total Weightage</th><td>".$totalScore."</td><th>Weightage of ".$title."</th><td>".$percentage."</td><th>Assessment</th><td>".$satisfactory."</td>";

  echo $str;
}

if(isset($_POST['action']) && $_POST['action'] == 'updateProjectComments'){

  $projectId = (isset($_POST['project_id']) && $_POST['project_id'])?$_POST['project_id']:'';
  $appraisee_comments = (isset($_POST['appraisee_comments']) && $_POST['appraisee_comments'])?$_POST['appraisee_comments']:'';
  $manager_comments = (isset($_POST['manager_comments']) && $_POST['manager_comments'])?$_POST['manager_comments']:'';
  $appraiseeId = (isset($_POST['employee_id']) && $_POST['employee_id'])?$_POST['employee_id']:$empId;

  $checkQuery = mysqli_query($db,"select * FROM performance_project_scores WHERE project_id = '".$projectId."' and employee_id = ".$appraiseeId);

  if(mysqli_num_rows($checkQuery) > 0){
    mysqli_query($db,"update performance_project_scores set manager_comments = '".$manager_comments."', appraisee_comments = '".$appraisee_comments."' WHERE project_id = '".$projectId."' and employee_id = ".$appraiseeId);
  }else{
    mysqli_query($db,"insert into performance_project_scores(project_id,employee_id,appraisee_comments,manager_comments) values('$projectId',$appraiseeId,'$appraisee_comments','$manager_comments')");
  }

  if(mysqli_affected_rows($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'updateManagerProjectComments'){

  $projectId = (isset($_POST['project_id']) && $_POST['project_id'])?$_POST['project_id']:'';
  $comments = (isset($_POST['comments']) && $_POST['comments'])?$_POST['comments']:'';

  $checkQuery = mysqli_query($db,"select * FROM performance_project_scores WHERE project_id = '".$projectId."' and employee_id = ".$empId);

  if(mysqli_num_rows($checkQuery) > 0){
    mysqli_query($db,"update performance_project_scores set manager_comments = '".$comments."' WHERE project_id = '".$projectId."' and employee_id = ".$empId);
  }else{
    mysqli_query($db,"insert into performance_project_scores(project_id,employee_id,appraisee_comments,manager_comments) values('$projectId',$empId,'','$comments')");
  }

  if(mysqli_affected_rows($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'appraiseeStrengths'){

  $appraisee_comments = (isset($_POST['appraisee_comments']) && $_POST['appraisee_comments'])?$_POST['appraisee_comments']:'';
  $manager_comments = (isset($_POST['manager_comments']) && $_POST['manager_comments'])?$_POST['manager_comments']:'';
  $appraisee_strength = (isset($_POST['appraisee_strength']) && $_POST['appraisee_strength'])?$_POST['appraisee_strength']:'';
  $appraiseeId = (isset($_POST['employee_id']) && $_POST['employee_id'])?$_POST['employee_id']:$empId;
  $reviewId = (isset($_POST['review_id']) && $_POST['review_id'])?$_POST['review_id']:$currentReviewId;
  mysqli_query($db,"insert into performance_appraisee_strengths(employee_id,appraisee_strength,appraisee_comments,manager_comments,review_id) values($appraiseeId,'$appraisee_strength','$appraisee_comments','$manager_comments',$reviewId)");

  if(mysqli_affected_rows($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'developmentAreas'){

  $appraisee_comments = (isset($_POST['appraisee_comments']) && $_POST['appraisee_comments'])?$_POST['appraisee_comments']:'';
  $manager_comments = (isset($_POST['manager_comments']) && $_POST['manager_comments'])?$_POST['manager_comments']:'';
  $development_area = (isset($_POST['development_area']) && $_POST['development_area'])?$_POST['development_area']:'';
  $appraiseeId = (isset($_POST['employee_id']) && $_POST['employee_id'])?$_POST['employee_id']:$empId;
  $reviewId = (isset($_POST['review_id']) && $_POST['review_id'])?$_POST['review_id']:$currentReviewId;
  mysqli_query($db,"insert into performance_appraisal_development_areas(employee_id,development_area,appraisee_comments,manager_comments,review_id) values($empId,'$development_area','$appraisee_comments','$manager_comments',$reviewId)");

  if(mysqli_affected_rows($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'appraiseeStrengthsComments'){

  $appraisee_comments = (isset($_POST['appraisee_comments']) && $_POST['appraisee_comments'])?$_POST['appraisee_comments']:'';
  $manager_comments = (isset($_POST['manager_comments']) && $_POST['manager_comments'])?$_POST['manager_comments']:'';
  $id = (isset($_POST['id']) && $_POST['id'])?$_POST['id']:'';
  $appraiseeId = (isset($_POST['employee_id']) && $_POST['employee_id'])?$_POST['employee_id']:$empId;
if($appraiseeId == $empId){
    mysqli_query($db,"update performance_appraisee_strengths set appraisee_comments = '".$appraisee_comments."' WHERE performance_appraisee_strength_id = ".$id);
  }else{
    mysqli_query($db,"update performance_appraisee_strengths set manager_comments = '".$manager_comments."' WHERE performance_appraisee_strength_id = ".$id);
  }


  if(mysqli_affected_rows($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'developmentAreasComments'){

  $appraisee_comments = (isset($_POST['appraisee_comments']) && $_POST['appraisee_comments'])?$_POST['appraisee_comments']:'';
  $manager_comments = (isset($_POST['manager_comments']) && $_POST['manager_comments'])?$_POST['manager_comments']:'';
  $id = (isset($_POST['id']) && $_POST['id'])?$_POST['id']:'';
  $appraiseeId = (isset($_POST['employee_id']) && $_POST['employee_id'])?$_POST['employee_id']:$empId;

 
if($appraiseeId == $empId){
    mysqli_query($db,"update performance_appraisal_development_areas set appraisee_comments = '".$appraisee_comments."' WHERE performance_development_area_id = ".$id);
  }else{
    mysqli_query($db,"update performance_appraisal_development_areas set manager_comments = '".$manager_comments."' WHERE performance_development_area_id = ".$id);
  }


  if(mysqli_affected_rows($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'addGoals'){
  $goal_title = (isset($_POST['goal_title']) && $_POST['goal_title'])?$_POST['goal_title']:'';
  $q1_plan = (isset($_POST['q1_plan']) && $_POST['q1_plan'])?$_POST['q1_plan']:'';
  $q1_measure = (isset($_POST['q1_measure']) && $_POST['q1_measure'])?$_POST['q1_measure']:'';
  $q2_plan = (isset($_POST['q2_plan']) && $_POST['q2_plan'])?$_POST['q2_plan']:'';
  $q2_measure = (isset($_POST['q2_measure']) && $_POST['q2_measure'])?$_POST['q2_measure']:'';
  $q3_plan = (isset($_POST['q3_plan']) && $_POST['q3_plan'])?$_POST['q3_plan']:'';
  $q3_measure = (isset($_POST['q3_measure']) && $_POST['q3_measure'])?$_POST['q3_measure']:'';
  $q4_plan = (isset($_POST['q4_plan']) && $_POST['q4_plan'])?$_POST['q4_plan']:'';
  $q4_measure = (isset($_POST['q4_measure']) && $_POST['q4_measure'])?$_POST['q4_measure']:'';

  mysqli_query($db,"insert into performance_goals(employee_id, goal_title, qtr_one_plan, qtr_one_measure, qtr_two_plan, qtr_two_measure, qtr_three_plan, qtr_three_measure, qtr_four_plan, qtr_four_measure, created_date, created_by) values ($empId,'$goal_title','$q1_plan','$q1_measure','$q2_plan','$q2_measure','$q3_plan','$q3_measure','$q4_plan','$q4_measure',$empId, '$currentDate')");
  if(mysqli_insert_id($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'addDevelopmentAreas'){
  $development_area = (isset($_POST['development_area']) && $_POST['development_area'])?$_POST['development_area']:'';
  $q1_plan = (isset($_POST['q1_plan']) && $_POST['q1_plan'])?$_POST['q1_plan']:'';
  $q1_measure = (isset($_POST['q1_measure']) && $_POST['q1_measure'])?$_POST['q1_measure']:'';
  $q2_plan = (isset($_POST['q2_plan']) && $_POST['q2_plan'])?$_POST['q2_plan']:'';
  $q2_measure = (isset($_POST['q2_measure']) && $_POST['q2_measure'])?$_POST['q2_measure']:'';
  $q3_plan = (isset($_POST['q3_plan']) && $_POST['q3_plan'])?$_POST['q3_plan']:'';
  $q3_measure = (isset($_POST['q3_measure']) && $_POST['q3_measure'])?$_POST['q3_measure']:'';
  $q4_plan = (isset($_POST['q4_plan']) && $_POST['q4_plan'])?$_POST['q4_plan']:'';
  $q4_measure = (isset($_POST['q4_measure']) && $_POST['q4_measure'])?$_POST['q4_measure']:'';

  mysqli_query($db,"insert into performance_development_areas(employee_id, development_area_title, qtr_one_plan, qtr_one_measure, qtr_two_plan, qtr_two_measure, qtr_three_plan, qtr_three_measure, qtr_four_plan, qtr_four_measure, created_date, created_by) values ($empId,'$development_area','$q1_plan','$q1_measure','$q2_plan','$q2_measure','$q3_plan','$q3_measure','$q4_plan','$q4_measure',$empId, '$currentDate')");
  if(mysqli_insert_id($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'addGoalComments'){
  $goal_plan_id = (isset($_POST['goal_plan_id']) && $_POST['goal_plan_id'])?$_POST['goal_plan_id']:'';
  $appraisee_comments = (isset($_POST['appraisee_comments']) && $_POST['appraisee_comments'])?$_POST['appraisee_comments']:'';
  $manager_comments = (isset($_POST['manager_comments']) && $_POST['manager_comments'])?$_POST['manager_comments']:'';
  $appraiseeId = (isset($_POST['employee_id']) && $_POST['employee_id'])?$_POST['employee_id']:$empId;

  mysqli_query($db,"update employee_goal_plans set manager_comments = '".$manager_comments."', appraisee_comments = '".$appraisee_comments."',modified_by = $appraiseeId, modified_date = '".$currentDate."' where goal_plan_id = $goal_plan_id");

  if(mysql_affected_rows($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'addNewFieldValue'){
  $table = (isset($_POST['table']) && $_POST['table'])?$_POST['table']:'';
  $column = (isset($_POST['column']) && $_POST['column'])?$_POST['column']:'';
  $field = (isset($_POST['field']) && $_POST['field'])?mysqli_real_escape_string($db,$_POST['field']):'';

  mysqli_query($db,"insert into $table ($column) values ('$field')");
  if(mysqli_insert_id($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'uploadNewPhoto'){
  $fileExtension = strtolower(pathinfo(basename($_FILES['file']["name"]),PATHINFO_EXTENSION));
  $targetFile = $empId."_".time()."_profileImage.".$fileExtension;
  move_uploaded_file($_FILES['file']['tmp_name'],$RELATIVE_PATH.$targetFile);
  mysqli_query($db,"update employee_details set Employee_image = '$targetFile' where employee_id = $empId");
  if(mysqli_affected_rows($db)){
    storeDataInHistory($empId, 'employee_details','employee_id');
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'upload'){

  $date = date("Y-m-d h:i:s");
  $docType = (isset($_POST['doc_type']) && $_POST['doc_type'])?$_POST['doc_type']:'';
  $currentDocId = (isset($_POST['doc_id']) && $_POST['doc_id'])?$_POST['doc_id']:'';

  if($currentDocId){
    mysqli_query($db,"update employee_documents_tracker set is_active = 'N', Modified_date_and_time = '$date',modified_by = $empId where doc_id = $currentDocId");
    mysqli_query($db,"update employee_documents set is_Active = 'N', Modified_date_and_time = '$date',modified_by = $empId where doc_id = $currentDocId");
    storeDataInHistory($currentDocId, 'employee_documents_tracker','doc_id');
    storeDataInHistory($currentDocId, 'employee_documents','doc_id');
  }

  $fileArray = reArrayFiles($_FILES['files']);

  mysqli_query($db,"insert into employee_documents_tracker(employee_id, document_type, created_date_and_Time, created_by, is_active) values ($empId,'$docType','$date',$empId,'N')");
  $docId = mysqli_insert_id($db);

  foreach ($fileArray as $key => $file) {
    $fileExtension = strtolower(pathinfo(basename($file["name"]),PATHINFO_EXTENSION));
    $fileName = str_replace(" ","_",$docType);
    $targetFile = $empId."_".time()."_".$fileName."_".$key.".".$fileExtension;
    move_uploaded_file($file["tmp_name"],$RELATIVE_PATH.$targetFile);
    mysqli_query($db, "insert into employee_documents (document_name,doc_id,created_by,is_Active) values('$targetFile',$docId,$empId,'N')");
  }

  echo $docId;
}

if(isset($_POST['action']) && $_POST['action'] == 'getFile'){

  $docId = (isset($_POST['doc_id']) && $_POST['doc_id'])?$_POST['doc_id']:'';

  $docIdQuery = mysqli_query($db,"SELECT document_name FROM employee_documents_tracker WHERE is_active = 'Y' and doc_id = ".$docId);
  $data = mysqli_fetch_assoc($docIdQuery);

  echo '<img src="'.$DOCUMENT_PATH.@$data['document_name'].'" class="img-responsive" />';
}

if(isset($_POST['action']) && $_POST['action'] == 'displayForm'){

  $docType = (isset($_POST['doc_type']) && $_POST['doc_type'])?$_POST['doc_type']:'';
  $docId = (isset($_POST['doc_id']) && $_POST['doc_id'])?$_POST['doc_id']:0;

  $docTypesQuery = mysqli_query($db,"SELECT document_type FROM all_document_types WHERE document_type LIKE '%".$docType."%'");
  $data = mysqli_fetch_assoc($docTypesQuery);

  echo '<div class="row">
    <div class="col-md-6">
      <span id="msgSpan" class="astrick">Please upload file</span>
      <form role="form" id="uploadForm" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="exampleInputEmail1">Document Type<span class="astrick">*</span>:</label>
          <input type="text" class="form-control" id="doc_type" name="doc_type" value="'.@$data['document_type'].'" readonly>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Document Attachment<span class="astrick">*</span>:</label>
          <input type="file" class="form-control" name="filesToUpload[]" id="filesToUpload"  accept="application/pdf,image/*,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
          <input type="hidden" class="form-control" name="doc_id" id="doc_id" value="'.@$docId.'"/>
        </div>
        <input type="button" class="btn btn-primary" name="uploadDoc" id="uploadDoc" value="Upload" />
      </form>
    </div>
  </div>';
?>
<script type="text/javascript">
$("#msgSpan").hide();
$("#uploadDoc").click(function() {

  var fd = new FormData();
  var doc_type = $("#doc_type").val();
  var doc_id = $("#doc_id").val();
  var files = $('#filesToUpload')[0].files;

  if(files.length <= 0){
    $("#msgSpan").show();
    return false;
  }

  //fd.append('file',files);
  fd.append('doc_type',doc_type);
  fd.append('doc_id',doc_id);
  fd.append('action','upload');
  for (var x = 0; x < files.length; x++) {
    $("#msgSpan").hide();
    fd.append("files[]", files[x]);
  }
  $.ajax({
         url: "ajaxUpload.php",
         type: "POST",
         data:  fd,
         contentType: false,
         processData:false,
         cache: false,
         success: function(data){
           $("#uploadForm")[0].reset();
           $("#"+type+"_doc_id").val(data);
           $("#edit-modal").modal('hide');
		   $("#"+type+"_doc_id").trigger('change');
         }
  });
});
</script>
<?php } ?>
