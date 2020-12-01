 <?php
 include("pages/forms/dbcontroller.php");
 //include("pages/forms/config.php");
 
 //$keyfeaturequery = mysql_query("select description from enterprise_services.application_info where application_id = 14");
 //$sysmessagequery = mysql_query("select message from enterprise_services.system_message where application_id = 14");
 ?>
 <?php
if (isset($_POST['submit']))
{   
	session_start();
	require_once("changepass.php");
	$db_handle = new DBController();
	$objLdap = new LDAPAuth();
	$objLdap->connect_ldap();

	$var1=$_POST["username"];
	$_SESSION['login_user']=$var1; 
	$password =$_POST['password'];

	$objLdap->user_name = $var1;
	$objLdap->user_pass = $password;
	$result = $objLdap->login();
	
	/*	
	
	$query= "SELECT count(*) as viewcount FROM employee_details where employee_id='921'";
					$result2 = $db_handle->runQuery($query);
					$count3= $result2[0]['viewcount']; 
	*/

	$var_is_personal_data_filled=false;
	if ($result) 
	{		
		$result1 = $db_handle->runQuery("SELECT is_personal_data_filled  FROM employee_details where employee_id='".$_SESSION['login_user']."'");
		
		$var_is_personal_data_filled=false;
		if($result1[0]["is_personal_data_filled"] == 'Y')
		{
			$var_is_personal_data_filled=true;
		}
		else
		{
				$var_is_personal_data_filled=false;
		}
		$count1 = $objLdap->validategroup();
		$count2 = $objLdap->validategroupmanager();
	
		
		if($count1>0)
		{
			if(!$var_is_personal_data_filled)
			{
				header("Location: pages/forms/firstform.php");
			}
			else
			{
				header("Location: DashboardFinal.php"); 
			}
			$_SESSION['login_user_group']='HR'; 
		}
		
		elseif($count2>0)
		{
			if(!$var_is_personal_data_filled)
			{
				header("Location: pages/forms/firstform.php");
			}
			else
			{
				header("Location: DashboardFinal.php");
			}
			$_SESSION['login_user_group']='Manager';
			//$errorMessage= "Sorry! You are not allowed in this group";
		}
		else
		{
			if(!$var_is_personal_data_filled)
			{
				header("Location: pages/forms/firstform.php");
			}
			else
			{
				header("Location: DashboardFinal.php");
			}
				$_SESSION['login_user_group']='Employee';
		}
	
	}
	else 
	{
		$errorMessage= "User Name Or Password Invalid!";
	} 
}

?> 
 
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="bootstrap.css" />
<title>HRMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  
  
    <style>
        @media screen and (min-width: 0px) and (max-width: 720px) {

            .mobile-hide {
                display: none;
            }
        }

        #carouselButtons {
            margin-left: 100px;
            position: absolute;
            bottom: 0px;
        }

        body, html {
            /*height: 100%;
            background-repeat: no-repeat;
            background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
        }

        .left-inner-addon {
            position: relative;
        }

        .card-container.card {
            /*max-width: 360px;*/
            max-width: 600px;
            padding: 40px 40px;
        }

        .btn {
            font-weight: 700;
            height: 36px;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            cursor: default;
        }

        /*
 * Card component
 */
        .card {
            background-color: #F7F7F7;
            /* just in case there no content*/
            padding: 20px 25px 30px;
            /*margin: 0 auto 25px;
            margin-top: 50px;*/
            /* shadows and rounded borders */
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        }

        .profile-img-card {
            width: 96px;
            height: 96px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        /*
 * Form styles
 */
        .profile-name-card {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 0;
            min-height: 1em;
        }

        .reauth-email {
            display: block;
            color: #404040;
            line-height: 2;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin #inptUsername,
        .form-signin #inptPassword {
            direction: ltr;
            height: 37px;
            font-size: 16px;
        }

        .form-signin input[type=email],
        .form-signin input[type=password],
        .form-signin input[type=text],
        .form-signin select,
        .form-signin button {
            width: 100%;
            display: block;
            margin-bottom: 10px;
            z-index: 1;
            position: relative;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin .form-control:focus {
            border-color: rgb(104, 145, 162);
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
        }

        .rowspace {
            margin-top: 0.5%;
            margin-bottom: 0.5%;
        }

        .loginimage {
            height: 70px;
            width: 180px;
        }

        .pagerLink {
            text-decoration: none !important;
            font-size: 14px;
            font-weight: normal;
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            line-height: 1.42857143;
            color: #333;
        }


        /*#ulKnowledgecenter li {
            background: url("./Images/1436347083_sign-in.png") no-repeat;
            list-style-type: none;
        }

        #ulSystemMessages li {
            background: url("./Images/1436347083_sign-in.png") no-repeat;
            list-style-type: none;
        }*/

        .fixed-panel {
            /*overflow-y: scroll;*/
            height: 340px;
        }


        .rcorners3 {
            border-radius: 5px;
            background: url('../Images/ACURUS Logo_Modified.png');
            /*background-position: left top;
            background-repeat: repeat;*/
            width: 380px;
            height: 343px;
            padding-left: 30px;
        }

        .transparent {
        }

        .support {
        }

        .Priority {
            font-size: 14px;
            border-radius: 3px;
            padding-top: 1px;
            margin-left: 3%;
        }

        .panel-success > .panel-heading {
            color: #0951AD !important;
            background-color: #bfdbff !important;
            border-color: #bfdbff !important;
        }

        .panel-success {
            border-color: #bfdbff !important;
        }

        #ulSystemMessages li {
            list-style-type: disc !important;
        }
		h5{
	color:red ;
}
    </style>
</head>
<body style="width: 100%;">
<div>
  <style type='text/css'>
.errorMessage {
	color:red !important;
}
</style>
<p class="alignleft"> <img alt='Acurus' title='Acurus' 
		src='images/acurus-logo.png' width="135" height="45"></p>
<div>
<div class="container-fluid">           

	<div class="col-md-11">
		<div>
			<table>
				<tbody><tr>
					<td>
						<label for="capella" style="height: 28px; background-color: white; font-family: Mongolian Baiti; color: Red; font-weight: bold; font-size: large">
							HRMS  - V1.0.
							    <?php 
							  

                                ?>
								</label>
					</td>
				</tr>
				
				</tbody>
			</table>
		</div>
	</div>

	<div class="container">
		<div>
			<br>
			<br>
		<div class="row">   
				<div class="col-md-6 mobile-hide col-lg-6 col-sm-6">
					<div class="panel panel-success">
						<div class="panel-heading">                                    
						Key Features
						</div>
						<div class="panel-body fixed-panel">
							<br>
							<ul id="ulFeatures">
							 <?php
							while($row1 = mysqli_fetch_assoc($keyfeaturequery))
							{
  							 
						?>
							<li style="padding-top: 5px;color:green;" class="fa fa-check">&nbsp;&nbsp;&nbsp;<span class="pagerLink"><?php echo $row1['description']." "; ?></span></li><br>
							 <?php
						 }
					?>
						
						</ul>
						</div>
					</div>
				</div>
			

							<!--  LOGIN CODE    -->			
		<div class="col-md-6 col-lg-6 col-sm-6">
			<div class="card card-container">
						<div class="container">
							<div style="float: left;">
								<span style="text-align: center; font-weight: bold; color: #428bca; font-size: 25px;">LOGIN
								</span>
							</div>
						</div>
						<hr size="16">  	
						
			<form id="login-form"  method="post" action="">					
				<div class="form-group has-feedback">
					<input class="form-control" placeholder="Username" name="username" id="username" type="text" />			
					<div class="errorMessage" id="username" style="display:none"></div>							
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input class="form-control" placeholder="Password" name="password" id="password" type="password" />				
					<div class="errorMessage" id="password" style="display:none"></div>				
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				
					
				<div class="row">
							<!-- /.col -->
							
					<div class="col-xs-12">
						<input class="btn btn-lg btn-primary btn-block btn-signin btnclass" type="submit" name="submit" value="Sign In" />							
					</div>
						<!-- /.col -->
				</div>	
								<br>
								<center><h5><?php echo $errorMessage; ?>	</h5></center>
								<center><h5><?php echo $errorMessage1; ?>	</h5></center>
												      
			</form>		



			</div>
		</div>
		</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-4 mobile-hide col-lg-4 col-sm-4">
				<div class="panel panel-success">
					<div class="panel-heading">                                
					System Messages
					</div>
					<div class="panel-body fixed-panel">
						<ul id="ulSystemMessages" style="margin-left:-24px">
							
							<?php
							while($row_temp = mysql_fetch_array($sysmessagequery))
							{
  							 //echo hi;
							 //echo $row_temp;
							?>
							
							<li style="padding-top: 5px;color:green;" class="fa fa-check">&nbsp;&nbsp;<span class="pagerLink"><?php echo $row_temp['message']." ";  ?></span></li><br>
							 <?php
							}
							?>
															
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4 mobile-hide col-lg-4 col-sm-4">
				<div class="panel panel-success">
					<div class="panel-heading">						
					Contact Details
					</div>
					<div class="panel-body fixed-panel">
						<p id="pContactDetails">
							<b>Address:</b><br/>160 South Old Springs Road<br/>Suite# 280<br/>Anaheim Hills<br/>CA 92808<br/>USA.<br/><br/><b>Phone:</b> 714-221-6311<br/><br/><b>Fax:</b> 909-348-8194<br/><br/><b>Email:</b> capellasupport@acurussolutions.com 						</p>
					</div>
				</div>
			</div>
			<div class="col-md-4 mobile-hide col-lg-4 col-sm-4">
				<div class="panel panel-success">
					<div class="panel-heading">						
					Knowledge Center
					</div>
					<div class="panel-body fixed-panel">
						<!--<ul id="ulKnowledgecenter">
													<li style="color:green;" class="fa fa-check">&nbsp;&nbsp;&nbsp;
													<a href="https://www.cms.gov/Medicare/Health-Plans/MedicareAdvtgSpecRateStats/Risk-Adjustors.html" target="_blank">Risk Adjustment</a></li><br>
													<li style="color:green;" class="fa fa-check">&nbsp;&nbsp;&nbsp;
													<a href="https://www.cms.gov/Medicare/Health-Plans/MedicareAdvtgSpecRateStats/Downloads/Announcement2017.pdf" target="_blank">RAF  - Announcement 2017</a></li><br>
													<li style="color:green;" class="fa fa-check">&nbsp;&nbsp;&nbsp;
													<a href="/RAF/RAF_FILES/sample_file.csv" target="_blank">RAF - Template</a></li><br>
												</ul> -->
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
           
</div></div>



</body>
</html>
