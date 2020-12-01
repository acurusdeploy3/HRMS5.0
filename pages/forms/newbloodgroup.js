
       $(document).on('click','#addBloodgroupbtn',function(e) {
		   var data = $("#inputBloodGroup").serialize();
//  var data = $("#BandForm").serialize();
  //ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "index.php",
         success: function(data){
			//alert("Added Successfully");
			AddingBloodGroup();
			 //ajaxindicatorstop();
			 
         }
		 
});
 });
  

       function AddingBloodGroup() {
          
			var modal = document.getElementById('modal-default-Blood-Group');
            var ddl = document.getElementById("BloodGroupSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputBloodGroup").value;
            option.value = document.getElementById("inputBloodGroup").value;
            ddl.options.add(option);
			document.getElementById("closeBloodGroup").click();
			//document.getElementById("inputBloodGroup").value="";
        
			     
        }

		
		
		
		x({
         data: data,
         type: "post",
         url: "index.php",
         success: function(data){
			//alert("Added Successfully");
			AddingBloodGroup();
			 //ajaxindicatorstop();
			 
         }
		 
});
 });
  

       function AddingBloodGroup() {          
			var modal = document.getElementById('modal-default-Blood-Group');
            var ddl = document.getElementById("BloodGroupSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputBloodGroup").value;
            option.value = document.getElementById("inputBloodGroup").value;
            ddl.options.add(option);
			document.getElementById("closeBloodGroup").click();
			//document.getElementById("inputBloodGroup").value="";
        
			     
        }
