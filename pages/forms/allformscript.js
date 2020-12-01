// Dropdown selection for displaying additional input field
//start
    function yesnoCheck(that) {
        if (that.value == "Married") {
            
            document.getElementById("ifYes").style.display = "block";
        } else {
            document.getElementById("ifYes").style.display = "none";
        }
    }
//end

//restricting user from entering number and alphabet
//start
$(document).ready(function() {
	$('#inputMiddleName,#inputLastName,#inputcontactrelation').on('keypress', function(key) {
		if((key.charCode < 97 || key.charCode > 122) && (key.charCode < 65 || key.charCode > 90) && (key.charCode != 45)) {
			return false;	
		}
	});
	$('#Primary_Mobile_Number,#inputEmergencyNo,#Presentpincode,#Permanentpincode,#previousctc,#phone, #work_duration_months,#fromyear,#toyear,#duration').on('keypress', function(key) {
        if(key.charCode < 48 || key.charCode > 57) {
			return false;
		}
    });
	$('#percentage_obtained').on('keypress', function(key) {
        if(key.charCode < 46 || key.charCode > 57)  {
			return false;
		}
    });
	$('#First_Name,#inputcontactname,#name,#inputSpouseName').on('keypress', function(key) {
		if((key.charCode < 97 || key.charCode > 122) && (key.charCode < 65 || key.charCode > 90) && (key.charCode < 32 || key.charCode > 33)) {
			return false;	
		}
	});
	
});
 
 

function addEvent(node, type, callback) {
  if (node.addEventListener) {
    node.addEventListener(
      type,
      function(e) {
        callback(e, e.target);
      },
      false
    );
  } else if (node.attachEvent) {
    node.attachEvent("on" + type, function(e) {
      callback(e, e.srcElement);
    });
  }
}

//identify whether a field should be validated
//ie. true if the field is neither readonly nor disabled,
//and has either "pattern", "required" or "aria-invalid"
function shouldBeValidated(field) {
  return (
    !(field.getAttribute("readonly") || field.readonly) &&
    !(field.getAttribute("disabled") || field.disabled) &&
    (field.getAttribute("pattern") || field.getAttribute("required"))
  );
}

//field testing and validation function
function instantValidation(field) {
  //if the field should be validated
  if (shouldBeValidated(field)) {
    //the field is invalid if:
    //it's required but the value is empty
    //it has a pattern but the (non-empty) value doesn't pass
    var invalid =
      (field.getAttribute("required") && !field.value) ||
      (field.getAttribute("pattern") &&
        field.value &&
        !new RegExp(field.getAttribute("pattern")).test(field.value));

    //add or remove the attribute is indicated by
    //the invalid flag and the current attribute state
    if (!invalid && field.getAttribute("aria-invalid")) {
      field.removeAttribute("aria-invalid");
    } else if (invalid && !field.getAttribute("aria-invalid")) {
      field.setAttribute("aria-invalid", "true");
    }
  }
}

//now bind a delegated change event
//== THIS FAILS IN INTERNET EXPLORER <= 8 ==//
//addEvent(document, 'change', function(e, target)
//{
//  instantValidation(target);
//});

//now bind a change event to each applicable for field
var fields = [
  document.getElementsByTagName("input"),
  document.getElementsByTagName("textarea")
];
for (var a = fields.length, i = 0; i < a; i++) {
  for (var b = fields[i].length, j = 0; j < b; j++) {
    addEvent(fields[i][j], "change", function(e, target) {
      instantValidation(target);
    });
  }
}



// Entering same address on check box selection
function SameAddress(f) {
  if(f.sameaddress.checked == true) {
    f.Permanent_Address_Line_1.value = f.Present_Address_Line_1.value;
    f.Permanent_Address_Line_2.value = f.Present_Address_Line_2.value;     
    f.Permanent_Street.value = f.Present_Street.value;
    f.Permanent_District.value = f.Present_District.value;
    f.Permanent_City.value = f.Present_City.value;
    f.Permanent_Country.value = f.Present_Country.value;
    f.Permanent_State.value = f.Present_State.value;
    f.Permanent_Address_Zip.value = f.Present_Address_ZipCode.value;
  }
  else{
  f.Permanent_Address_Line_1.value="";
   f.Permanent_Address_Line_2.value="";
   f.Permanent_Street.value="";
   f.Permanent_District.value="";
   f.Permanent_City.value ="";
   f.Permanent_Country.value="";
   f.Permanent_State.value ="";
   f.Permanent_Address_Zip.value="";
  }
}

