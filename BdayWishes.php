<?php session_start(); 
	$employeeId = $_SESSION['login_user'];
	include('config.php');
	$getBdayWishes = mysqli_query($db,'select a.employee_id,e.First_Name,e.employee_image,a.wished_by,a.message,date_format(created_time,"%h:%i %p %d %b %Y") as date from bday_wishes a
inner join employee_details e on a.wished_by=e.employee_id where a.employee_id='.$employeeId.' and year=year(now())');
$i=1;
?>
<div class="direct-chat-messages" style="height: 400px;">
<?php
if(mysqli_num_rows($getBdayWishes)>0)
{
while($row= mysqli_fetch_assoc($getBdayWishes))
{
$userImage = $row['employee_image'];
$imgPath = 'uploads/'.$userImage;
if($i%2==0)
{

?>

                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left"><?php echo $row['First_Name'];  ?></span>
                        <span class="direct-chat-timestamp pull-right"><?php echo $row['date'];  ?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<?php echo $imgPath; ?>" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text" style="padding: 10px 15px!important;">
                       <?php echo $row['message'];  ?>
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
<?php
}
else
{
?>
                    <!-- /.direct-chat-msg -->

                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right"><?php echo $row['First_Name'];  ?></span>
                        <span class="direct-chat-timestamp pull-left"><?php echo $row['date'];  ?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<?php echo $imgPath; ?>" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text" style="background-color: #ffbf99;border-color: #ffbf99;padding: 10px 15px!important;">
                         <?php echo $row['message'];  ?>
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>

<?php 
}
?>
                   

                 
<?php
 $i++;
}
}
else
{
?>
 		 <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">Acurus Solutions</span>
                        <span class="direct-chat-timestamp pull-right"></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="uploads/avatar5.png" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                       Have a Great day and a year ahead !
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
<?php 
}
 ?>
 </div>