<?php 
include("config.php");
$EmpId = $_POST['EmployeeID'];
$getMail  = mysqli_query($db,"select concat(first_name,' ',last_name,' ',MI) as Name,official_email from employee_Details where employee_id='$EmpId'");
$getMailrow = mysqli_fetch_array($getMail);
$MailAddress = $getMailrow['official_email'];
$Name = $getMailrow['Name'];
$senderName = 'Acurus HRMS'; //Enter the sender name
$username = 'notifications@acurussolutions.com'; //Enter your Email
$password = 'ukkupzzernjykeap';// Enter the Password
$n=10;
 
function getPassword($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
} 
$newPassword = getPassword($n);
$AD_server = "ldap://172.18.0.150"; 
		$ds = ldap_connect($AD_server);
		if ($ds) {
					ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
					$result = ldap_bind($ds, "cn=Manager,dc=acurusldap,dc=com","Acurus@123"); //BIND
					if (!$result)
						{
							echo 'Not Binded';
						}
						$var1=$EmpId;
						$userDn = "cn=".$var1.",ou=Users,dc=acurusldap,dc=com";
						$userdata = array("userPassword" => "$newPassword");	
						$result = ldap_modify($ds, $userDn, $userdata);
   		 ldap_close($ds);
			}
	else
	 {
		echo "Unable to connect to LDAP server";
	 }
 $recipients = 
			array(
                 $MailAddress => $MailAddress,
            );
			
			require 'PHPMailerAutoload.php';

$mail = new PHPMailer();

            // Set up SMTP
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'AHRMS Credential Support';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$Name.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your One Time Password (OTP) is : <strong>$newPassword</strong>.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly Change the Password after logon.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Regards,</td>
					</tr>

					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>AHRMS Support.</td>
					</tr>
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'></td>
					</tr>
				</tbody>
			
	</table>
	</body></html>");
            $mail->AltBody = 'This is a plain-text message body';
            //$mail->addAttachment('images/phpmailer_mini.gif');

            // Set the from/to
            $mail->setFrom($username, $senderName);
            // foreach ($recipients as $address => $name) {
                // $mail->addAddress($address, $name);
            // }
			foreach ($recipients as $address => $name) {
                $mail->addAddress($address, $name);
             }
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
			
			echo "<div class='modal-content'>
					 <div class='tab-content'>
							  
							  <h3 style='color:#286090;font-family: calibri;'>Your One Time Password (OTP) has been shared to your Official Email.</h3>
							  <br>
							  <h4 style='color:#286090;font-family: calibri;'>Kindly Change the Password after Logon.</h4>
								<br>
								<a href='MainLogin.php' >Back Home</a>
							  </div>
                              </div>";
?>