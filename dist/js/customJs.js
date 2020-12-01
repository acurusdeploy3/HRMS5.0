$(document).ready(function () {
	// $.fn.bootstrapValidator.validators.validateDocNum = {
  //   validate: function(validator, $field, options) {
	// 		var value = $field.val();
	// 		console.log($("form#addKYE select[name='docType']").val(),value)
	// 	}
	// };
	var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

	$('.sidebar-menu').tree();
// http://bootstrapvalidator.votintsev.ru/developing/

	$('#workFrom').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		endDate: today,
		autoclose: true
	}).on('changeDate', function (selected) {
      var minDate = new Date(selected.date.valueOf());
      $('#workTo').datepicker('setStartDate', minDate);
	  $('#addWorkHistory').bootstrapValidator('revalidateField', 'from');
  });

	$('#workTo').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		endDate: today,
		autoclose: true
	}).on('changeDate', function (selected) {
	  $('#addWorkHistory').bootstrapValidator('revalidateField', 'to');
  });

	$('#tab-menu > ul > li').click(function(){
    window.location.href = $(this).find('a').attr('href');
  });

	$('#personalDetailsEdit').bootstrapValidator({
			 message: 'This value is not valid',
			 feedbackIcons: {
					 valid: 'glyphicon glyphicon-ok',
					 invalid: 'glyphicon glyphicon-remove',
					 validating: 'glyphicon glyphicon-refresh'
			 },
			 fields: {
					 dob: {
							 validators: {
									 notEmpty: {
											 message: 'Date of Birth is required'
									 }
							 }
					 },
					 gender: {
							 validators: {
									 notEmpty: {
											 message: 'Gender is required'
									 }
							 }
					 },
					 personalMail: {
							 validators: {
									 notEmpty: {
											 message: 'The email address is required'
									 },
									 emailAddress: {
											 message: 'The input is not a valid email address'
									 }
							 }
					 },
					 mobile: {
							 validators: {
									 notEmpty: {
											 message: 'Mobile Number is required'
									 }
							 }
					 },
					 maritalStatus: {
							 validators: {
									 notEmpty: {
											 message: 'Marital Status is required'
									 }
							 }
					 },
					 bloodGroup: {
							 validators: {
									 notEmpty: {
											 message: 'Blood Group is required'
									 }
							 }
					 }
			 }
   });

	 $('#presentAddressEdit').bootstrapValidator({
 			 message: 'This value is not valid',
 			 feedbackIcons: {
 					 valid: 'glyphicon glyphicon-ok',
 					 invalid: 'glyphicon glyphicon-remove',
 					 validating: 'glyphicon glyphicon-refresh'
 			 },
 			 fields: {
 					 // addressLine2: {
 						// 	 validators: {
 						// 			 notEmpty: {
 						// 					 message: 'This field is required'
 						// 			 }
 						// 	 }
 					 // },
					 city: {
 							 validators: {
 									 notEmpty: {
 											 message: 'This field is required'
 									 }
 							 }
 					 },
					 state: {
 							 validators: {
 									 notEmpty: {
 											 message: 'This field is required'
 									 }
 							 }
 					 },
					 country: {
 							 validators: {
 									 notEmpty: {
 											 message: 'This field is required'
 									 }
 							 }
 					 },
					 zip: {
 							 validators: {
 									 notEmpty: {
 											 message: 'This field is required'
 									 }
 							 }
 					 }
 			 }
    });

		$('#permanentAddressEdit').bootstrapValidator({
				message: 'This value is not valid',
				feedbackIcons: {
						valid: 'glyphicon glyphicon-ok',
						invalid: 'glyphicon glyphicon-remove',
						validating: 'glyphicon glyphicon-refresh'
				},
				fields: {
						// paddressLine2: {
						// 		validators: {
						// 				notEmpty: {
						// 						message: 'This field is required'
						// 				}
						// 		}
						// },
						pcity: {
								validators: {
										notEmpty: {
												message: 'This field is required'
										}
								}
						},
						pstate: {
								validators: {
										notEmpty: {
												message: 'This field is required'
										}
								}
						},
						pcountry: {
								validators: {
										notEmpty: {
												message: 'This field is required'
										}
								}
						},
						pzip: {
								validators: {
										notEmpty: {
												message: 'This field is required'
										}
								}
						}
				}
		 });

		 $('#addWorkHistory').bootstrapValidator({
 				message: 'This value is not valid',
 				feedbackIcons: {
 						valid: 'glyphicon glyphicon-ok',
 						invalid: 'glyphicon glyphicon-remove',
 						validating: 'glyphicon glyphicon-refresh'
 				},
 				fields: {
 						company: {
 								validators: {
 										notEmpty: {
 												message: 'This field is required'
 										}
 								}
 						},
 						designation: {
 								validators: {
 										notEmpty: {
 												message: 'This field is required'
 										}
 								}
 						},
 						from: {
 								validators: {
 										notEmpty: {
 												message: 'This field is required'
 										}
 								}
 						},
 						to: {
 								validators: {
 										notEmpty: {
 												message: 'This field is required'
 										}
 								}
 						}
 				}
 		 });

		 $('#addEducation').bootstrapValidator({
					message: 'This value is not valid',
					feedbackIcons: {
							valid: 'glyphicon glyphicon-ok',
							invalid: 'glyphicon glyphicon-remove',
							validating: 'glyphicon glyphicon-refresh'
					},
					fields: {
							education: {
									validators: {
											notEmpty: {
													message: 'This field is required'
											}
									}
							},
							institute: {
									validators: {
											notEmpty: {
													message: 'This field is required'
											}
									}
							},
							from: {
									validators: {
											notEmpty: {
													message: 'This field is required'
											}
									}
							},
							to: {
									validators: {
											notEmpty: {
													message: 'This field is required'
											},
											greaterThan: {
												value: 'from',
												message: 'Please enter a value greater than or equal to %s'
											}
									}
							},
							percentage: {
									validators: {
											notEmpty: {
													message: 'This field is required'
											}
									}
							}
					}
			 });

			 $('#addCertifications').bootstrapValidator({
			      message: 'This value is not valid',
			      feedbackIcons: {
			          valid: 'glyphicon glyphicon-ok',
			          invalid: 'glyphicon glyphicon-remove',
			          validating: 'glyphicon glyphicon-refresh'
			      },
			      fields: {
			          course: {
			              validators: {
			                  notEmpty: {
			                      message: 'This field is required'
			                  }
			              }
			          }
			      }
			 });

			 $('#addSkills').bootstrapValidator({
						message: 'This value is not valid',
						feedbackIcons: {
								valid: 'glyphicon glyphicon-ok',
								invalid: 'glyphicon glyphicon-remove',
								validating: 'glyphicon glyphicon-refresh'
						},
						fields: {
								skill: {
										validators: {
												notEmpty: {
														message: 'This field is required'
												}
										}
								}
						}
			 });

			 $('#addKYE').bootstrapValidator({
						message: 'This value is not valid',
						feedbackIcons: {
								valid: 'glyphicon glyphicon-ok',
								invalid: 'glyphicon glyphicon-remove',
								validating: 'glyphicon glyphicon-refresh'
						},
						fields: {
								docType: {
										validators: {
												notEmpty: {
														message: 'This field is required'
												}
										}
								},
								docId: {
										validators: {
												notEmpty: {
														message: 'This field is required'
												}
										}
								}
						}
			 });

			 $("form#addKYE select[name='docType']").change(function(){
				 var val = $(this).val();
				 if(val == ''){
					 val = $("form#addKYE select[name='docType'] option:eq(1)").val();
				 }

				 var selectArray = val.split(" ");
				 $("form#addKYE div.btn_div a[href='#myModal']").attr("id",selectArray[0]);
				 $("form#addKYE input[name='docId']").attr("id",selectArray[0]+"_doc_id");
			 });

			// $('#personalDetailsEdit').dirrty().on("dirty", function(){
			// 	$("#personalDetailsEdit input[type='submit']").removeAttr("disabled");
			// }).on("clean", function(){
			// 	$("#personalDetailsEdit input[type='submit']").attr("disabled", "disabled");
			// });
});
$('#edit-modal').on('show.bs.modal', function(e) {

	var $modal = $(this);
	type = e.relatedTarget.id;

	var id = $("#"+type+"_doc_id").val();
	if(!id){
		id = 0;
	}

	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'doc_type='+type+'&action=displayForm&doc_id='+id,
		success: function(data)
		{
				 $modal.find('.edit-content').html(data);
		}
	});
});

$('#view-modal').on('show.bs.modal', function(e) {

	var $modal = $(this),
	type = e.relatedTarget.id,
	id = $("#"+type+"_doc_id").val();


	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'doc_id='+id+'&action=getFile',
		success: function(data)
		{
				 $modal.find('.view-content').html(data);
		}
	});
});

$("#uploadNewPhoto").click(function(){
	var url = window.location.href;
	var fd = new FormData();
  var file = $('#newPhoto')[0].files[0];

  fd.append('file',file);
  fd.append('action','uploadNewPhoto');

	$.ajax({
     url: 'ajaxUpload.php',
     type: "POST",
     data:  fd,
     contentType: false,
     processData:false,
     cache: false,
     success: function(data){
       if(data == 'success'){
				 window.location.href = url;
			 }else{
				 $("#uploadMsg").html("file upload failed, please try again");
			 }
     }
  });
});

$("#addNewField").click(function(){
	var fd = new FormData();
  var table = $('#tableName').val();
	var column = $('#tableColumn').val();
	var field = $('#newField').val();
	var id = $('#replaceField').val();

	if(field == ''){
		alert("Please enter value");
		return false;
	}

  fd.append('table',table);
	fd.append('column',column);
	fd.append('field',field);
  fd.append('action','addNewFieldValue');

	$.ajax({
     url: 'ajaxUpload.php',
     type: "POST",
     data:  fd,
     contentType: false,
     processData:false,
     cache: false,
     success: function(data){
       if(data == 'success'){
				 $("#"+id).append('<option value="'+field+'">'+field+'</option>');
				 $("#addNewFieldModal").modal("hide");
			 }else{
				 $("#fieldMsg").html("failed to add value, please try again");
			 }
     }
  });
});

function updateDeliverablesPerformance(criteria_id, category_id){
	var measure_id = $('#measure_'+criteria_id).val();
	var score = $('#apparisee_score_'+criteria_id).text();
	var comments = escape($('#comments_'+criteria_id).val());
	if(comments == ''){
		alert("please enter comments");
		return false;
	}

	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'measure_id='+measure_id+'&score='+score+'&comments='+comments+'&criteria_id='+criteria_id+'&category_id='+category_id+'&action=updateDeliverablesPerformance',
		success: function(data)
		{
			var str = data.split("#");
			$("#overAllData").html(str[0]);
			$("#overAllPerformance").html(str[1]);
		}
	});
}

function getMeasureWeightage(val,criteria_id){
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'measure_id='+val+'&criteria_id='+criteria_id+'&action=getMeasureWeightage',
		success: function(data)
		{
			var str = data.split("#");
			$('#measured_score_'+criteria_id).html(str[0]);
			$('#weighted_score_'+criteria_id).html(str[1]);
			$('#apparisee_score_'+criteria_id).html(str[2]);
		}
	});
}

function projectComments(project,id,user){
	var val = escape($('#comments_'+id).val());
	if(project == ''){
		alert("Please Move to Employee");
		return false;
	}
	if(val == ''){
		alert("Please Enter Recommendations")
		return false;
	}
	if(user == "employee"){
		var data = 'project_id='+project+'&appraisee_comments='+val+'&action=updateProjectComments';
	}else{
		var data = 'project_id='+project+'&manager_comments='+val+'&employee_id='+user+'&action=updateProjectComments';
	}
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: data,
		success: function(data)
		{
				 console.log(data);
		}
	});
}

function appraiseeStrengths(review_id){
	var val = $('#appraisee_strength').val();
	var comments = escape($('#appraisee_strength_comments').val());
	var pageId = $('#appraisee_strength_page_id').val();

	if(val == ''){
		alert("Please add Appraisee Strength");
		return false;
	}
	if(comments == ''){
		alert("Please add Appraisee Recommendations");
		return false;
	}
	if(pageId == 'appraisee'){
		var data = 'review_id='+review_id+'&appraisee_comments='+comments+'&appraisee_strength='+val+'&action=appraiseeStrengths';
	}else{
		var data = 'review_id='+review_id+'&employee_id='+pageId+'&manager_comments='+comments+'&appraisee_strength='+val+'&action=appraiseeStrengths';
	}
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: data,
		success: function(data)
		{
				 window.location.reload();
		}
	});
}

function developmentAreas(review_id){
	var val = $('#development_areas').val();
	var comments = escape($('#development_areas_comments').val());
	var pageId = $('#development_areas_page_id').val();

	if(val == ''){
		alert("Please add Developement Area");
		return false;
	}
	if(comments == ''){
		alert("Please add Appraisee Recommendations");
		return false;
	}
	if(pageId == 'appraisee'){
		var data = 'review_id='+review_id+'&appraisee_comments='+comments+'&development_area='+val+'&action=developmentAreas';
	}else{
		var data = 'review_id='+review_id+'&employee_id='+pageId+'&manager_comments='+comments+'&development_area='+val+'&action=developmentAreas';
	}
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: data,
		success: function(data)
		{
				 window.location.reload();
		}
	});
}

function projectManagerComments(id,project){
	var val = escape($('#'+id).val());
	if(val == ''){
		return false;
	}
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'project_id='+project+'&comments='+val+'&action=updateManagerProjectComments',
		success: function(data)
		{
				 console.log(data);
		}
	});
}

function appraiseeStrengthsComments(fieldId,id){
	var val = escape($('#'+fieldId).val());
	if(val == ''){
		alert("Please enter Manager Recommendations")
		return false;
	}
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'comments='+val+'&id='+id+'&action=appraiseeStrengthsComments',
		success: function(data)
		{
				 window.location.reload();
		}
	});
}

function developmentAreasComments(fieldId,id){
	var val = escape($('#'+fieldId).val());
	if(val == ''){
		alert("Please enter Manager Recommendations")
		return false;
	}
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'comments='+val+'&id='+id+'&action=developmentAreasComments',
		success: function(data)
		{
				 window.location.reload();
		}
	});
}

function appraiseeStrengthRecommendation(id,user){
	var val = escape($('#appraisee_strength_recommendation_'+id).val());
	if(val == ''){
		alert("Please enter Appraisee Recommendations")
		return false;
	}

	if(user == 'employee'){
		var data = 'appraisee_comments='+val+'&id='+id+'&action=appraiseeStrengthsComments';
	}else{
		var data = 'employee_id='+user+'&manager_comments='+val+'&id='+id+'&action=appraiseeStrengthsComments';
	}

	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: data,
		success: function(data)
		{
				// window.location.reload();
				console.log(data);
		}
	});
}

function appraiseeDevelopmentRecommendation(id,user){
	var val = escape($('#appraisee_da_recommendation_'+id).val());
	if(val == ''){
		alert("Please enter Appraisee Recommendations")
		return false;
	}
	if(user == "employee"){
		var data = 'appraisee_comments='+val+'&id='+id+'&action=developmentAreasComments';
	}else{
		var data = 'employee_id='+user+'&manager_comments='+val+'&id='+id+'&action=developmentAreasComments';
	}
	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: data,
		success: function(data)
		{
				// window.location.reload();
				console.log(data);
		}
	});
}

function addGoals(){
	var goal = $("#goal_name").val();
	var q1_plan = $("#goal_q1_paln").val();
	var q1_measure = $("#goal_q1_measuere").val();
	var q2_plan = $("#goal_q2_paln").val();
	var q2_measure = $("#goal_q2_measuere").val();
	var q3_plan = $("#goal_q3_paln").val();
	var q3_measure = $("#goal_q3_measuere").val();
	var q4_plan = $("#goal_q4_paln").val();
	var q4_measure = $("#goal_q4_measuere").val();

	if(goal == ''){ alert("Please enter Goal name");return false;}
	if(q1_plan == ''){ alert("Please enter Quater One plan");return false;}
	if(q1_measure == ''){ alert("Please enter Quater One Measure");return false;}
	if(q2_plan == ''){ alert("Please enter Quater Two plan");return false;}
	if(q2_measure == ''){ alert("Please enter Quater Two Measure");return false;}
	if(q3_plan == ''){ alert("Please enter Quater Three plan");return false;}
	if(q3_measure == ''){ alert("Please enter Quater Three Measure");return false;}
	if(q4_plan == ''){ alert("Please enter Quater Four plan");return false;}
	if(q4_measure == ''){ alert("Please enter Quater Four Measure");return false;}

	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'goal='+goal+'&q1_plan='+q1_plan+'&q1_measure='+q1_measure+'&q2_plan='+q2_plan+'&q2_measure='+q2_measure+'&q3_plan='+q3_plan+'&q3_measure='+q3_measure+'&q4_plan='+q4_plan+'&q4_measure='+q4_measure+'&action=addGoals',
		success: function(data)
		{
				 window.location.reload();
		}
	});
}

function addDevelopmentAreas(){
	var development_area = $("#development_area_name").val();
	var q1_plan = $("#development_area_q1_paln").val();
	var q1_measure = $("#development_area_q1_measuere").val();
	var q2_plan = $("#development_area_q2_paln").val();
	var q2_measure = $("#development_area_q2_measuere").val();
	var q3_plan = $("#development_area_q3_paln").val();
	var q3_measure = $("#development_area_q3_measuere").val();
	var q4_plan = $("#development_area_q4_paln").val();
	var q4_measure = $("#development_area_q4_measuere").val();

	if(development_area == ''){ alert("Please enter Development Area");return false;}
	if(q1_plan == ''){ alert("Please enter Quater One plan");return false;}
	if(q1_measure == ''){ alert("Please enter Quater One Measure");return false;}
	if(q2_plan == ''){ alert("Please enter Quater Two plan");return false;}
	if(q2_measure == ''){ alert("Please enter Quater Two Measure");return false;}
	if(q3_plan == ''){ alert("Please enter Quater Three plan");return false;}
	if(q3_measure == ''){ alert("Please enter Quater Three Measure");return false;}
	if(q4_plan == ''){ alert("Please enter Quater Four plan");return false;}
	if(q4_measure == ''){ alert("Please enter Quater Four Measure");return false;}

	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'development_area='+development_area+'&q1_plan='+q1_plan+'&q1_measure='+q1_measure+'&q2_plan='+q2_plan+'&q2_measure='+q2_measure+'&q3_plan='+q3_plan+'&q3_measure='+q3_measure+'&q4_plan='+q4_plan+'&q4_measure='+q4_measure+'&action=addDevelopmentAreas',
		success: function(data)
		{
				 window.location.reload();
		}
	});
}

function goalsComments(goal_plan_id,user){
	var val = escape($("#comments_"+goal_plan_id).val());

	if(goal_plan_id == 0){
		alert("please move to Employee");
		return false;
	}

	if(val == ''){
		alert("Please Enter Goal Recommendations");
		return false;
	}

	if(user == "employee"){
		var data = 'goal_plan_id='+goal_plan_id+'&appraisee_comments='+val+'&action=addGoalComments';
	}else{
		var data = 'employee_id='+user+'&goal_plan_id='+goal_plan_id+'&manager_comments='+val+'&action=addGoalComments';
	}

	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: data,
		success: function(data)
		{
			window.location.reload();
		}
	});
}

function updateManagerDeliverablesPerformance(criteria_id,score_id,category_id,employee_id){
	var measure_id = $('#measure_'+criteria_id).val();
	var score = $('#apparisee_score_'+criteria_id).text();
	var appraisee_comments = escape($('#appraisee_comments_'+criteria_id).text());
	var manager_comments = escape($('#manager_comments_'+criteria_id).val());

	if(score_id == 0){
		alert("Please Move to Employee");
		return false;
	}

	if(manager_comments == ''){
		alert("Please enter your Recommendations");
		return false;
	}

	$.ajax({
		cache: false,
		type: 'POST',
		url: 'ajaxUpload.php',
		data: 'employee_id='+employee_id+'&measure_id='+measure_id+'&score='+score+'&appraisee_comments='+appraisee_comments+'&criteria_id='+criteria_id+'&category_id='+category_id+'&manager_comments='+manager_comments+'&score_id='+score_id+'&action=updateManagerDeliverablesPerformance',
		success: function(data)
		{
			var str = data.split("#");
			$("#overAllData").html(str[0]);
			$("#overAllPerformance").html(str[1]);
		}
	});
}
