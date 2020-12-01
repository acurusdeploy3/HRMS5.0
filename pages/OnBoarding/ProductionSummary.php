</html>
<style>
table{
	 margin-bottom: 0px ! important;
}
td {
    border: 1px solid white ! important;
}
#headtitle{
	text-align: center;
    font-weight: bold;
	background-color: #2d7bb7;
    text-align: -webkit-center;
    color: white;
}
#sidetitle{
	background-color: antiquewhite;
}

</style>
<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$userid = $_SESSION['login_user'];
include('config3.php');
include('config.php');
include('config2.php');
if (isset($_POST['employeeid'])) {
   $Employee_ID = $_POST['employeeid'];
					$RFD = $_POST['doj'];
					$RTD = $_POST['doc'];
   }
//$Employee_ID='1051';
$mydetailsquery=mysqli_query($db,"select concat(First_Name,' ',MI,' ',Last_Name,'-',Employee_ID) as person_name,employee_designation,department,employee_image from employee_details where employee_id ='$Employee_ID'");
$mydetailsrow=mysqli_fetch_array($mydetailsquery);
$mydetval = $mydetailsrow['person_name'];
$desgval = $mydetailsrow['employee_designation'];
$deptval = $mydetailsrow['department'];
$ProfPic = $mydetailsrow['employee_image'];
$profPicPath = '../../uploads/'.$ProfPic;

$mandetailsquery=mysqli_query($db,"select concat(First_Name,' ',MI,' ',Last_Name,'-',Employee_ID) as person_name from employee_details where employee_id in(
select manager_id from cos_master where employee_id ='$Employee_ID')");
$mandetailsrow=mysqli_fetch_array($mandetailsquery);
$mandetval = $mandetailsrow['person_name'];


$date=date_create($RFD);
$date1=date_create($RTD);


$AimsDet = mysqli_query($db7,"SELECT EmpId FROM employee_list where process in ('CHARGE POSTING', 'PAYMENT POSTING','CODING') and TL<>'TL' and empid='$Employee_ID'");

$QualityDet = mysqli_query($db7,"SELECT EmpId FROM employee_list where process in ('Quality') and TL<>'TL' and empid='$Employee_ID'");

$NonAimsDet = mysqli_query($db7,"SELECT EmpId FROM employee_list where process = 'AR' and TL<>'TL' and empid='$Employee_ID'");

$CallersDet = mysqli_query($db7,"SELECT EmpId FROM employee_list where process = 'AR F/Up' and TL<>'TL' and empid='$Employee_ID'");
?>
<div class="box box-widget widget-user" style="margin-bottom: 0px;">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active" style="height: 77px ! important;">
            <h3 class="widget-user-username"><?php echo $mydetval; ?><span style="float:right;">Productivity Details</span></h3>
              <h5 class="widget-user-desc"><?php echo $desgval." - ".$deptval; ?></h5>
</div>             
            <div class="box-footer" style="padding-top: 0px ! important;    height: 60px;background-color: #a9a9a929;">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">Period From</h5>
                    <span class="description-text"><?php echo date_format($date,"d M Y"); ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">Period To</h5>
                    <span class="description-text"><?php echo date_format($date1,"d M Y"); ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">Manager Details</h5>
                    <span class="description-text"><?php echo $mandetval ; ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>

<?php if(mysqli_num_rows($AimsDet)>0)
{
$getProdSummary = mysqli_query($db7,"
select d.*,((target*Percentage)/100) as `% of Target`,((Actual_Prod-Errors_Rec)/Target_prod)*100 as Eff_Prod,(Actual_Prod/Target_prod)*100 as Prod,
(1-(Errors_Rec/Actual_Prod))*100 as Quality_Perc
from (select Doc_Type,Doc_Sub_Type,Process,level_grp,grpdays,Percentage,Target,sum(Total_Hours)*((target*Percentage)/100) as Target_prod,
sum(Total_Hours) as Actual_Hours,
sum(case when process='QC' and doc_type='PAYMENT_POSTING' then Total_Units/2 else total_units end) as Actual_Prod,
sum(case when process='CAPTURE' and doc_type='PAYMENT_POSTING' then Total_Errors*2 else total_errors end) as Errors_Rec,
sum(Total_Audited_Units) as Aud_Units,Project
from(select if(level_grp='E',GroupDays_Exp,GroupDays_Fresh) as grpdays,
if(level_grp='E',if(GroupDays_Exp='0-6 Days','30',if(GroupDays_Exp='7-30 Days','50',if(GroupDays_Exp='31-45 Days','75','100'))),
if(GroupDays_Fresh='0-15 Days','30',if(GroupDays_Fresh='16-45 Days','50',if(GroupDays_Fresh='46-75 Days','75','100')))) as Percentage,
b.*
from
(SELECT if(date_Add(doj , interval 366 day)<=date('$RTD'),'E','F') as level_grp
,a.*
FROM `pms_report_old_rampup_75` a
where substring_index(user_name,'_',-1)='$Employee_ID' and date(dop) between '$RFD' and '$RTD') as b) as c
group by target,process,doc_type,doc_sub_type,project,grpdays order by doc_type, doc_sub_type, process, project, grpdays) as d
union
Select '' As Doc_type,
'' As Doc_sub_type,
'Total' As Process,
'' As level_grp,
'' As grpdays,
'' As percentage,
'' As Target,
sum(a.target_prod),
sum(a.Actual_Hours),
sum(a.Actual_prod),
sum(a.Errors_rec),
sum(a.aud_units),
'' As Project,
'' As `% of Target`,
((sum(a.Actual_Prod)-sum(a.Errors_Rec))/sum(a.Target_prod))*100 as Eff_Prod,
(sum(a.Actual_Prod)/sum(a.Target_prod))*100 as Prod,
(1-(sum(Errors_Rec)/sum(Actual_Prod)))*100 as Quality_Perc from (select d.*,((target*Percentage)/100) as `% of Target`,((Actual_Prod-Errors_Rec)/Target_prod) as Eff_Prod,(Actual_Prod/Target_prod) as Prod,
(1-(Errors_Rec/Actual_Prod)) as Quality_Perc
from(select Doc_Type,Doc_Sub_Type,Process,level_grp,grpdays,Percentage,Target,sum(Total_Hours)*((target*Percentage)/100) as Target_prod,
sum(Total_Hours) as Actual_Hours,
sum(case when process='QC' and doc_type='PAYMENT_POSTING' then Total_Units/2 else total_units end) as Actual_Prod,
sum(case when process='CAPTURE' and doc_type='PAYMENT_POSTING' then Total_Errors*2 else total_errors end) as Errors_Rec,
sum(ifnull(Total_Audited_Units,0)) as Aud_Units,Project
from(select if(level_grp='E',GroupDays_Exp,GroupDays_Fresh) as grpdays,
if(level_grp='E',if(GroupDays_Exp='0-6 Days','30',if(GroupDays_Exp='7-30 Days','50',if(GroupDays_Exp='31-45 Days','75','100'))),
if(GroupDays_Fresh='0-15 Days','30',if(GroupDays_Fresh='16-45 Days','50',if(GroupDays_Fresh='46-75 Days','75','100')))) as Percentage,
b.*
from
(SELECT if(date_Add(doj , interval 366 day)<=('$RTD'),'E','F') as level_grp
,a.*
FROM `pms_report_old_rampup_75` a
where substring_index(user_name,'_',-1)='$Employee_ID' and date(dop) between '$RFD' and '$RTD') as b) as c
group by target,process,doc_type,doc_sub_type,project,grpdays) as d) a;");
$getProdDateSummary = mysqli_query($db7,"select
Type,
Operator_Name,
Doc_type,
Doc_sub_type,
process,
target,
round(target*(sum(Total_Hour)),2) as Target_basis_actual_hour,
sum(Total_units) AS Total_units,
sum(Total_Hour) AS Total_Hour,
Project
from pms_non_aims where DOP between '$RFD' and '$RTD'
and substring_index(operator_name,'_',-1)='$Employee_ID'
Group by
doc_type, doc_sub_type, process, project");
?>
<div style="height: -webkit-fill-available;">
	<table class="table" style="font-size:14px;width:100%">
	<tbody>
			<tr style="font-weight: bold;text-align: -webkit-center;background-color: #31607c;color:white">
				<td>#</td>
				<td>Document Type</td>
				<td>Sub Document Type</td>
				<td>Process</td>
				<td>Day Group</td>
				<td>% of Target to be achieved</td>
				<td>Plan Target Per Hour</td>
				<td>Target Production  Based on Actual Hours</td>
				<td>Actual Hours Spent</td>
				<td>Actual Production</td>
				<td>Errors Recorded</td>
				<td>Audited Units</td>
				<td>Effective Productivity Per Hour</td>
				<td>Productivity</td>
				<td>Quality Percentage</td>
				<td>Project</td>
			</tr>
			 <?php
                if(mysqli_num_rows($getProdSummary) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row1 = mysqli_fetch_assoc($getProdSummary)){
					  if($row1['Process']=='Total'){
					  echo "<tr><td style='width:1%'></td>";
					  echo "<td style='width:25%;border-left: none ! important;border-right: none ! important;'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Process']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['grpdays']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;
					  border-right: none ! important;'>".$row1['Percentage']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;
					  border-right: none ! important;'>".$row1['% of Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Errors_Rec']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Aud_Units']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Eff_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".ceil(round($row1['Prod'],2))."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Quality_Perc'],2)."</td>";
					  echo "<td style='width:25%'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
					  else{
					  echo "<tr><td style='width:1%'>".$i.".</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Process']."</td>";
					  echo "<td style='width:25%'>".$row1['grpdays']."</td>";
					  echo "<td style='width:25%'>".$row1['Percentage']."</td>";
					  echo "<td style='width:25%'>".$row1['% of Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Errors_Rec']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Aud_Units']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Eff_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Quality_Perc'],2)."</td>";
					  echo "<td style='width:25%;'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
                    $i++;
				  }
				}
                ?>
				<?php
                if(mysqli_num_rows($getProdDateSummary) < 1){
                }else{
					?>
					<tr>
						<td colspan="16" style="font-weight: bold;background-color: #31607c ! important;color: #fff;font-size: 18px;"><span>Non Aims Data</span></td>
					</tr>
					<tr style="font-weight: bold;text-align: -webkit-center;background-color: #31607c;color:white">
					<td>#</td>
				<td colspan="2">Document Type</td>
				<td colspan="2">Sub Document Type</td>
				<td colspan="3">Process</td>
				<td colspan="2">Target</td>
				<td colspan="2">Plan Target Per Hour</td>
				<td>Total Units</td>
				<td>Total Hours</td>
				<td colspan="2">Project</td>
					</td>
					</tr>
                  <?php $j = 1;
                  while($row2 = mysqli_fetch_assoc($getProdDateSummary)){
					  echo "<tr><td style='width:1%'>".$j.".</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Doc_type']."</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Doc_sub_type']."</td>";
					  echo "<td style='width:25%;' colspan='3'>".$row2['process']."</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['target']."</td>";
					  echo "<td style='width:25%;text-align:right;' colspan='2'>".$row2['Target_basis_actual_hour']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row2['Total_units'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row2['Total_Hour'],2)."</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Project']."</td>";
					  echo "</tr>";
                    $j++;
				  }
				}
                ?>
	</tbody>
	</table>
</div>
<?php
}
else if(mysqli_num_rows($QualityDet)>0)
{
$getProdSummary = mysqli_query($db7,"
select d.*,((target*Percentage)/100) as `% of Target`,((Actual_Prod-Errors_Rec)/Target_prod)*100 as Eff_Prod,(Actual_Prod/Target_prod)*100 as Prod,
(1-(Errors_Rec/Actual_Prod))*100 as Quality_Perc
from(select Doc_Type,Doc_Sub_Type,Process,level_grp,grpdays,Percentage,Target,sum(Total_Hours)*Target as Target_prod,
sum(Total_Hours) as Actual_Hours,
sum(case when process='QC' and doc_type='PAYMENT_POSTING' then Total_Units/2 else total_units end) as Actual_Prod,
sum(case when process='CAPTURE' and doc_type='PAYMENT_POSTING' then Total_Errors*2 else total_errors end) as Errors_Rec,
sum(Total_Audited_Units) as Aud_Units,Project
from(select if(level_grp='E',GroupDays_Exp,GroupDays_Fresh) as grpdays,
if(level_grp='E',if(GroupDays_Exp='0-6 Days','30',if(GroupDays_Exp='7-30 Days','50',if(GroupDays_Exp='31-45 Days','75','100'))),
if(GroupDays_Fresh='0-6 Days','30',if(GroupDays_Fresh='7-30 Days','50',if(GroupDays_Fresh='31-45 Days','75','100')))) as Percentage,
b.*
from
(SELECT if(date_Add(doj , interval 366 day)<=date(now()),'E','F') as level_grp
,a.*
FROM `pms_report_old_rampup_75` a
where substring_index(user_name,'_',-1)='$Employee_ID' and date(dop) between '$RFD' and '$RTD') as b) as c
group by target,process,doc_type,doc_sub_type,project,grpdays) as d
union
Select '' As Doc_type,
'' As Doc_sub_type,
'Total' As Process,
'' As level_grp,
'' As grpdays,
'' As percentage,
'' As Target,
sum(a.target_prod),
sum(a.Actual_Hours),
sum(a.Actual_prod),
sum(a.Errors_rec),
sum(a.aud_units),
'' As Project,
'' As `% of Target`,
((sum(a.Actual_Prod)-sum(a.Errors_Rec))/sum(a.Target_prod))*100 as Eff_Prod,
(sum(a.Actual_Prod)/sum(a.Target_prod))*100 as Prod,
(1-(sum(Errors_Rec)/sum(Actual_Prod)))*100 as Quality_Perc from (select d.*,((target*Percentage)/100) as `% of Target`,((Actual_Prod-Errors_Rec)/Target_prod) as Eff_Prod,(Actual_Prod/Target_prod) as Prod,
(1-(Errors_Rec/Actual_Prod))*100 as Quality_Perc
from(select Doc_Type,Doc_Sub_Type,Process,level_grp,grpdays,Percentage,Target,sum(Total_Hours)*Target as Target_prod,
sum(Total_Hours) as Actual_Hours,
sum(case when process='QC' and doc_type='PAYMENT_POSTING' then Total_Units/2 else total_units end) as Actual_Prod,
sum(case when process='CAPTURE' and doc_type='PAYMENT_POSTING' then Total_Errors*2 else total_errors end) as Errors_Rec,
sum(ifnull(Total_Audited_Units,0)) as Aud_Units,Project
from(select if(level_grp='E',GroupDays_Exp,GroupDays_Fresh) as grpdays,
if(level_grp='E',if(GroupDays_Exp='0-6 Days','30',if(GroupDays_Exp='7-30 Days','50',if(GroupDays_Exp='31-45 Days','75','100'))),
if(GroupDays_Fresh='0-6 Days','30',if(GroupDays_Fresh='7-30 Days','50',if(GroupDays_Fresh='31-45 Days','75','100')))) as Percentage,
b.*
from
(SELECT if(date_Add(doj , interval 366 day)<=date(now()),'E','F') as level_grp
,a.*
FROM `pms_report_old_rampup_75` a
where substring_index(user_name,'_',-1)='$Employee_ID' and date(dop) between '$RFD' and '$RTD') as b) as c
group by target,process,doc_type,doc_sub_type,project,grpdays) as d) a"); 
$getProdDateSummary = mysqli_query($db7,"select
Type,Operator_Name,Doc_Type,Doc_Sub_Type,Process,Target,round(target*(sum(Total_Hour)),2) as Target_basis_actual_hour,sum(Total_units) AS Total_units,sum(Total_Hour) AS Total_Hour,
Project from pms_non_aims where DOP between '$RFD' and '$RTD' and substring_index(operator_name,'_',-1) = '$Employee_ID'
Group by doc_type, doc_sub_type, process, project");?>
<body>
<div style="height: -webkit-fill-available;">
	<table class="table" style="font-size:14px;width:100%">
	<tbody>
			<tr style="font-weight: bold;text-align: -webkit-center;background-color: #31607c;color:white">
				<td>#</td>
				<td>Document Type</td>
				<td>Sub Document Type</td>
				<td>Process</td>
				<td>Day Group</td>
				<td>% of Target to be achieved</td>
				<td>Plan Target Per Hour</td>
				<td>Target Production  Based on Actual Hours</td>
				<td>Actual Hours Spent</td>
				<td>Actual Production</td>
				<td>Errors Recorded</td>
				<td>Audited Units</td>
				<td>Productivity</td>
				<td>Project</td>
			</tr>
			 <?php
                if(mysqli_num_rows($getProdSummary) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row1 = mysqli_fetch_assoc($getProdSummary)){
					  if($row1['Process']=='Total'){
					  echo "<tr><td style='width:1%'></td>";
					  echo "<td style='width:25%;border-left: none ! important;border-right: none ! important;'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Process']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['grpdays']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;
					  border-right: none ! important;'>".$row1['Percentage']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;
					  border-right: none ! important;'>".$row1['% of Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Errors_Rec']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Aud_Units']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".ceil(round($row1['Prod'],2))."</td>";
					  echo "<td style='width:25%;'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
					  else{
					  echo "<tr><td style='width:1%'>".$i.".</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Process']."</td>";
					  echo "<td style='width:25%'>".$row1['grpdays']."</td>";
					  echo "<td style='width:25%'>".$row1['Percentage']."</td>";
					  echo "<td style='width:25%'>".$row1['% of Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Errors_Rec']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Aud_Units']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Prod'],2)."</td>";
					  echo "<td style='width:25%;'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
                    $i++;
				  }
				}
                ?>
				<?php
                if(mysqli_num_rows($getProdDateSummary) < 1){
                }else{
					?>
					<tr>
						<td colspan="15" style="font-weight: bold;background-color: #31607c ! important;color: #333;font-size: 18px;"><span>Non Aims Data</span></td>
					</tr>
					<tr style="font-weight: bold;text-align: -webkit-center;background-color: #31607c;color:white">
					<td>#</td>
				<td colspan="2">Document Type</td>
				<td colspan="2">Sub Document Type</td>
				<td colspan="3">Process</td>
				<td colspan="2">Target</td>
				<td colspan="2">Plan Target Per Hour</td>
				<td>Total Units</td>
				<td>Total Hours</td>
				<td colspan="2">Project</td>
					</tr>
                  <?php $j = 1;
                  while($row2 = mysqli_fetch_assoc($getProdDateSummary)){
					  echo "<tr><td style='width:1%'>".$j.".</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Doc_Type']."</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%;' colspan='3'>".$row2['Process']."</td>";
					  echo "<td style='width:25%;'>".$row2['Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row2['Target_basis_actual_hour']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row2['Total_units'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row2['Total_Hour'],2)."</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Project']."</td>";
					  echo "</tr>";
                    $j++;
				  }
				}
                ?>
	</tbody>
	</table>
</div>
</body>
<?php
}
else if(mysqli_num_rows($NonAimsDet)>0)
{
	
	$getProdSummary = mysqli_query($db7,"
select d.*,((target*Percentage)/100) as `% of Target`,((Actual_Prod-Errors_Rec)/Target_prod)*100 as Eff_Prod,(Actual_Prod/Target_prod)*100 as Prod,
(1-(Errors_Rec/Actual_Prod))*100 as Quality_Perc
from(select Doc_Type,Doc_Sub_Type,Process,level_grp,grpdays,Percentage,Target,sum(Total_Hours)*Target as Target_prod,
sum(Total_Hours) as Actual_Hours,
sum(case when process='QC' and doc_type='PAYMENT_POSTING' then Total_Units/2 else total_units end) as Actual_Prod,
sum(case when process='CAPTURE' and doc_type='PAYMENT_POSTING' then Total_Errors*2 else total_errors end) as Errors_Rec,
sum(Total_Audited_Units) as Aud_Units,Project
from(select if(level_grp='E',GroupDays_Exp,GroupDays_Fresh) as grpdays,
if(level_grp='E',if(GroupDays_Exp='0-6 Days','30',if(GroupDays_Exp='7-30 Days','50',if(GroupDays_Exp='31-45 Days','75','100'))),
if(GroupDays_Fresh='0-6 Days','30',if(GroupDays_Fresh='7-30 Days','50',if(GroupDays_Fresh='31-45 Days','75','100')))) as Percentage,
b.*
from
(SELECT if(date_Add(doj , interval 366 day)<=date(now()),'E','F') as level_grp
,a.*
FROM `pms_report_old_rampup_75` a
where substring_index(user_name,'_',-1)='$Employee_ID' and date(dop) between '$RFD' and '$RTD') as b) as c
group by target,process,doc_type,doc_sub_type,project,grpdays) as d
union
Select '' As Doc_type,
'' As Doc_sub_type,
'Total' As Process,
'' As level_grp,
'' As grpdays,
'' As percentage,
'' As Target,
sum(a.target_prod),
sum(a.Actual_Hours),
sum(a.Actual_prod),
sum(a.Errors_rec),
sum(a.aud_units),
'' As Project,
'' As `% of Target`,
((sum(a.Actual_Prod)-sum(a.Errors_Rec))/sum(a.Target_prod))*100 as Eff_Prod,
(sum(a.Actual_Prod)/sum(a.Target_prod))*100 as Prod,
(1-(sum(Errors_Rec)/sum(Actual_Prod)))*100 as Quality_Perc from (select d.*,((target*Percentage)/100) as `% of Target`,((Actual_Prod-Errors_Rec)/Target_prod) as Eff_Prod,(Actual_Prod/Target_prod) as Prod,
(1-(Errors_Rec/Actual_Prod))*100 as Quality_Perc
from(select Doc_Type,Doc_Sub_Type,Process,level_grp,grpdays,Percentage,Target,sum(Total_Hours)*Target as Target_prod,
sum(Total_Hours) as Actual_Hours,
sum(case when process='QC' and doc_type='PAYMENT_POSTING' then Total_Units/2 else total_units end) as Actual_Prod,
sum(case when process='CAPTURE' and doc_type='PAYMENT_POSTING' then Total_Errors*2 else total_errors end) as Errors_Rec,
sum(ifnull(Total_Audited_Units,0)) as Aud_Units,Project
from(select if(level_grp='E',GroupDays_Exp,GroupDays_Fresh) as grpdays,
if(level_grp='E',if(GroupDays_Exp='0-6 Days','30',if(GroupDays_Exp='7-30 Days','50',if(GroupDays_Exp='31-45 Days','75','100'))),
if(GroupDays_Fresh='0-6 Days','30',if(GroupDays_Fresh='7-30 Days','50',if(GroupDays_Fresh='31-45 Days','75','100')))) as Percentage,
b.*
from
(SELECT if(date_Add(doj , interval 366 day)<=date(now()),'E','F') as level_grp
,a.*
FROM `pms_report_old_rampup_75` a
where substring_index(user_name,'_',-1)='$Employee_ID' and date(dop) between '$RFD' and '$RTD') as b) as c
group by target,process,doc_type,doc_sub_type,project,grpdays) as d) a");
$getProdSummaryVal = mysqli_query($db7,"select Type,Operator_Name,Doc_Type,Doc_Sub_Type,
Process,Target,round(target*(sum(Total_Hour)),2) as Target_basis_actual_hour,
sum(Total_units) AS Total_units,round(sum(Total_Hour),2) AS Total_Hour,
Project from pms_non_aims where DOP between '$RFD' and '$RTD'
and substring_index(operator_name,'_',-1) = '$Employee_ID'
Group by doc_type, doc_sub_type, process, project"); 
?>
<body>
<div style="height: -webkit-fill-available;">
	<table class="table" style="font-size:14px;width:100%">
	<tbody>
			<tr style="font-weight: bold;text-align: -webkit-center;background-color: #31607c;color:white">
				<td>#</td>
				<td>Document Type</td>
				<td>Sub Document Type</td>
				<td>Process</td>
				<td>Day Group</td>
				<td>% of Target to be achieved</td>
				<td>Plan Target Per Hour</td>
				<td>Target Production  Based on Actual Hours</td>
				<td>Actual Hours Spent</td>
				<td>Actual Production</td>
				<td>Errors Recorded</td>
				<td>Audited Units</td>
				<td>Productivity</td>
				<td>Project</td>
			</tr>
			 <?php
                if(mysqli_num_rows($getProdSummary) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row1 = mysqli_fetch_assoc($getProdSummary)){
					  if($row1['Process']=='Total'){
					  echo "<tr><td style='width:1%'></td>";
					  echo "<td style='width:25%;border-left: none ! important;border-right: none ! important;'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Process']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['grpdays']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;
					  border-right: none ! important;'>".$row1['Percentage']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;
					  border-right: none ! important;'>".$row1['% of Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Errors_Rec']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Aud_Units']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".ceil(round($row1['Prod'],2))."</td>";
					  echo "<td style='width:25%;'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
					  else{
					  echo "<tr><td style='width:1%'>".$i.".</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Process']."</td>";
					  echo "<td style='width:25%'>".$row1['grpdays']."</td>";
					  echo "<td style='width:25%'>".$row1['Percentage']."</td>";
					  echo "<td style='width:25%'>".$row1['% of Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Errors_Rec']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row1['Aud_Units']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Prod'],2)."</td>";
					  echo "<td style='width:25%;'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
                    $i++;
				  }
				}
                ?>
				<?php
                if(mysqli_num_rows($getProdSummaryVal) < 1){
                }else{
					?>
					<tr>
						<td colspan="14" style="font-weight: bold;background-color: #31607c ! important;color: #333;font-size: 18px;"><span>Non Aims Data</span></td>
					</tr>
					<tr style="font-weight: bold;text-align: -webkit-center;background-color: #31607c;color:white">
					<td>#</td>
				<td colspan="2">Document Type</td>
				<td colspan="2">Sub Document Type</td>
				<td colspan="3">Process</td>
				<td>Target</td>
				<td>Plan Target Per Hour</td>
				<td>Total Units</td>
				<td>Total Hours</td>
				<td colspan="2">Project</td>
					</td>
					</tr>
                  <?php $j = 1;
                  while($row2 = mysqli_fetch_assoc($getProdSummaryVal)){
					  echo "<tr><td style='width:1%'>".$j.".</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Doc_Type']."</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%;' colspan='3'>".$row2['Process']."</td>";
					  echo "<td style='width:25%;'>".$row2['Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".$row2['Target_basis_actual_hour']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row2['Total_units'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row2['Total_Hour'],2)."</td>";
					  echo "<td style='width:25%;' colspan='2'>".$row2['Project']."</td>";
					  echo "</tr>";
                    $j++;
				  }
				}
                ?>
	</tbody>
	</table>
</div>
</body>

<?php }

else if(mysqli_num_rows($CallersDet)>0)
{
	$getProdSummary = mysqli_query($db7,"select Doc_Type,Doc_Sub_Type,Process,'5' As Target,count(distinct date(dop))*40 As Target_prod,count(distinct date(dop))*8 As Actual_Hours,sum(total_calls) As Actual_prod,'' As Error, '' As Aud_units,Project,
	sum(total_calls)/(count(distinct date(dop))*40)*100 As Productivity from caller_data where emp_id ='$Employee_ID' and dop between '$RFD' and '$RTD' group by process,doc_type,doc_sub_type,project union Select '' As Doc_type, '' As Doc_sub_type, '' As Process, '5' As Target, sum(a.Target_prod) As Target_prod, sum(a.Actual_Hours) As Actual_Hours, sum(Actual_prod) As Actual_prod,'' As Errors, '' As Aud_units, '' As Project, sum(a.Actual_prod)/sum(a.Target_prod) As Productivity from (select Doc_Type,Doc_Sub_Type,Process,'5' As Target,count(distinct date(dop))*40 As Target_prod,count(distinct date(dop))*8 As Actual_Hours,sum(total_calls) As Actual_prod,'' As Error, '' As Aud_units,Project,sum(total_calls)/(count(distinct date(dop))*40) As Productivity from caller_data where emp_id ='$Employee_ID' and dop between '$RFD' and '$RTD' group by process,doc_type,doc_sub_type,project) a group by a.Doc_Type;"); ?>

	<body>
<div style="height: -webkit-fill-available;">
	<table class="table" style="font-size:14px;width:100%">
	<tbody>
			<tr style="font-weight: bold;text-align: -webkit-center;background-color: #31607c;color:white">
				<td>#</td>
				<td>Document Type</td>
				<td>Sub Document Type</td>
				<td>Process</td>
				<td>Plan Target Per Hour</td>
				<td>Target Production  Based on Actual Hours</td>
				<td>Actual Hours Spent</td>
				<td>Actual Production</td>
				<td>Errors Recorded</td>
				<td>Audited Units</td>
				<td>Project</td>
				<td>Productivity</td>
			</tr>
			 <?php
                if(mysqli_num_rows($getProdSummary) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row1 = mysqli_fetch_assoc($getProdSummary)){
					  if($row1['Process']=='Total'){
					  echo "<tr><td style='width:1%'></td>";
					  echo "<td style='width:25%;border-left: none ! important;border-right: none ! important;'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;    border-right: none ! important;'>".$row1['Process']."</td>";
					  echo "<td style='width:25%;border-left: none ! important;
					  border-right: none ! important;'>".$row1['Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".ceil(round($row1['Productivity'],2))."</td>";
					  echo "<td style='width:25%;'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
					  else{
					  echo "<tr><td style='width:1%'>".$i.".</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Doc_Sub_Type']."</td>";
					  echo "<td style='width:25%'>".$row1['Process']."</td>";
					  echo "<td style='width:25%'>".$row1['Target']."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Target_prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Hours'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Actual_Prod'],2)."</td>";
					  echo "<td style='width:25%;text-align:right;'>".round($row1['Productivity'],2)."</td>";
					  echo "<td style='width:25%;'>".$row1['Project']."</td>";
					  echo "</tr>";
					  }
                    $i++;
				  }
				}
                ?>
	</tbody>
	</table>
</div>

<?php }


else  {
	echo "<span style='color: red;font-size: larger;margin-left: 35%;'>NO PRODUCTIVITY DATA AVAILABLE</span>";
}
?>

<script>
$(document).ready(function(){
ajaxindicatorstop();
});
</script>
</html>