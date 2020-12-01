<html>
<body>
<input type="Button" id="GenerateButton" value="Generate" name="GenerateButton" class="btn btn-info pull-right" style = "margin-right: 40px;" onclick="openbirtreport();"></input> 
<div class="form-group" id="iframeid" style="display:none;">
<iframe id="reportframe" height="800" width="900" src="http://172.18.1.119:8080/birt/frameset?__report=Report%20Templates/ARS/5270_RCM_PRODUCTIVITY_REPORT.rptdesign" ></iframe></div>

<script>
function openbirtreport () {
  document.getElementById('iframeid').style.display = "block";
}
	</script>
	</body>
</html>