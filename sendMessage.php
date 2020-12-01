<?php  
include('config.php');
session_start();
$to = $_POST['mail'];
$Subject = $_POST['Subject'];
$Msg = $_POST['Msg'];
$receiverName = $_POST['MessageName'];
$MessageEmpId = $_POST['MessageEmpId'];
$sender = $_SESSION['login_user'];
$year = date('Y');
mysqli_query($db,"insert into bday_wishes (employee_id,wished_by,message,year) values ('$MessageEmpId','$sender','$Msg','$year')");
$getSenderDetails = mysqli_query($db,"select employee_id,employee_name,department from employee where employee_id='$sender'");
$getSenderDetailsrow = mysqli_fetch_array($getSenderDetails);
$senderName = $getSenderDetailsrow['employee_name'];
$senderDept =  $getSenderDetailsrow['department'];
$context ='Hey '.$receiverName.', ';
$context1 = $senderName.' of '.$senderDept.' has sent you a Message.';

$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password
 $recipients = 
			array(
                 $to => $to,

            );
			
			require 'pages/LeaveMgmt/PHPMailerAutoload.php';

           $mail = new PHPMailer();

     
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = $Subject;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='pages/LeaveMgmt/AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$context."</td>
					</tr>
                    <tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$context1."</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'><strong>".$Msg."</strong></td>
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
							  
							  <h3 style='color:#286090;font-family: 'Open Sans', sans-serif;'>Your Message has been Sent Successfully.</h3>
							  <br>
							  </div>
                              </div>";

?>