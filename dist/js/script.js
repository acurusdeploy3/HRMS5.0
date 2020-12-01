$(document).ready(function() {
	alert("hello");
	$("form[name='education-form']").validate({
        rules: {
            programme: "required",
			specialization: "required",
			passyear: "required",
        },
        messages: {
			programme: "This field is required",
			specialization: "This field is required",
			passyear: "This field is required"
        },
        submitHandler: function(e) {
            e.preventDefault(), e.submit()
        }
    })
});