$(document).ready(function () {
	var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

	$('.sidebar-menu').tree();

	$('#dob').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		endDate: today,
		autoclose: true
	});

	$('#workFrom').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		endDate: today,
		autoclose: true
	});
	
	$('#issuedate').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		endDate: today,
		autoclose: true
	});
	

	$('#workTo').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		endDate: today,
		autoclose: true
	});

	$('#validFrom').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		endDate: today,
		autoclose: true
	});

	$('#validTo').datepicker({
		format: 'yyyy-mm-dd',
		setDate: today,
		todayHighlight: true,
		startDate: today,
		autoclose: true
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
 					 addressLine1: {
 							 validators: {
 									 notEmpty: {
 											 message: 'This field is required'
 									 }
 							 }
 					 },
 					 addressLine2: {
 							 validators: {
 									 notEmpty: {
 											 message: 'This field is required'
 									 }
 							 }
 					 },
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
						paddressLine1: {
								validators: {
										notEmpty: {
												message: 'This field is required'
										}
								}
						},
						paddressLine2: {
								validators: {
										notEmpty: {
												message: 'This field is required'
										}
								}
						},
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
			          },
			          offeredBy: {
			               validators: {
			                  notEmpty: {
			                      message: 'This field is required'
			                  }
			              }
			          },
			          courseLevel: {
			              validators: {
			                  notEmpty: {
			                      message: 'This field is required'
			                  }
			              }
			          },
			          certificationName: {
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
								},
								competencyLevel: {
										 validators: {
												notEmpty: {
														message: 'This field is required'
												}
										}
								},
								monthsUsed: {
										validators: {
												notEmpty: {
														message: 'This field is required'
												}
										}
								},
								lastUsed: {
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
