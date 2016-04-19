<?php
$strMsg='';   
if($_REQUEST['admin_preview']==1 && $_SESSION['adminid']>0)
{
  $_SESSION['adminid_original']= $_SESSION['adminid'];
  $_SESSION['role_original']= $_SESSION['role'];

  $_SESSION['adminid']= $_POST['user_id'];

  $_SESSION['role']= $_POST['user_role_id'];

  $_SESSION['preview']=1;

}



if($_REQUEST['close_preview']==1 && $_SESSION['adminid']>0) 
{

  $_SESSION['adminid'] = $_SESSION['adminid_original'];

	$_SESSION['role'] = $_SESSION['role_original'];

  unset($_SESSION['preview']);

  $objFunctions->__doRedirect(SITE_ADMINURL.'staff/user-permissions');

}



if(isset($_SESSION['adminid'])) 
{

  $objFunctions->__doRedirect(SITE_ADMINURL.'dashboard/admin_dashboard'); 	

}





if($_POST):

 

 if($_POST['action']=='forgetpassword') 
 {

         $strEmail=(trim($_POST['forget_email']));

         $strPassword=$_POST['md_password'];

         $strQuery ='SELECT * from '.TBL_STAFF.' where email=\'%s\'';

         $objRecord = $objDatabase->fetchRows(sprintf($strQuery,$strEmail));

        

        	if($objRecord->password<>''):

           $newPassword = mt_rand(12564, 98483737); 

            $objDatabase->dbQuery("UPDATE ".TBL_STAFF." set password='".md5($newPassword)."' where email='".$strEmail."'");     

        	  

            $body='<p>Dear '.$objRecord->f_name.'<br><br>Your Password has been reset. Please find your new Password below:<br><br>

                    <b>New Password:</b> '.$newPassword.'

                   </p>';

            $objFunctions->mosMail('', '', $strEmail, 'Forget Password', $body);

            

             $strMsg='New Password sent on your email id. Please check your email inbox.';

          else:

        	 $strMsg='Invalid Email Id, Please try again...';

        	endif;

 }

 else 
 {  

    if ( $_POST['cap_has']<> md5($_POST['captcha']) )
    {

       $strMsg='Invalid Captcha Sum. Please try again...';

    } 
    else 
    {

         $strUsername=(trim($_POST['md_username']));

         $strPassword=$_POST['md_password'];

         $strQuery ='SELECT password,username,id,user_type,f_name from '.TBL_STAFF.' where username=\'%s\'';

         $objRecord = $objDatabase->fetchRows(sprintf($strQuery,$strUsername));        

        	if($objRecord->password==md5($strPassword)): 
                $_SESSION['adminid']= $objRecord->id;
        	    $_SESSION['role']= $objRecord->user_type;
                $_SESSION['fname']= $objRecord->f_name;
                
                if($_POST['remember'] == 1) {
                    $password_hash = md5($strPassword); // will result in a 32 characters hash             
                    setcookie ('siteAuth', 'usr='.$objRecord->id.'&role='.$objRecord->user_type.'&fname='.$objRecord->f_name, time() + cookie_time);
                }
    
           
                if($_SESSION['page_refer']<>'') {
                    $objFunctions->__doRedirect($_SESSION['page_refer']);
                    unset($_SESSION['page_refer']);
                }
                else {
                    $objFunctions->__doRedirect(SITE_ADMINURL.'dashboard/admin_dashboard');     
                }
        	else:
                $strMsg='Invalid Username or Password, please try again...';
        	endif;

     }

  }    

endif;

?>







<!-- Login Box -->



	<div class="box">



		<div class="content">



			<!-- Login Formular -->



      <form id="form1" class="form-vertical login-form"  name="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">



				<!-- Title -->



				<h3 class="form-title" style="display:none">Sign In to your Account</h3>







				<!-- Error Message -->

                <p align="center" class="alert-danger"><?php echo $strMsg;?></p>

				<div class="alert fade in alert-danger" style="display: none;">



					<i class="icon-remove close" data-dismiss="alert"></i>



					Enter any username and password.



				</div>







				<!-- Input Fields -->



				<div class="form-group">



					<!--<label for="username">Username:</label>-->



					<div class="input-icon">



						<i class="icon-user"></i>



						<input type="text" name="md_username" class="form-control" placeholder="Username" autofocus="autofocus" data-rule-required="true" data-msg-required="Please enter your username." />



					</div>



				</div>



				<div class="form-group">



					<!--<label for="password">Password:</label>-->



					<div class="input-icon">



						<i class="icon-lock"></i>



						<input type="password" name="md_password" class="form-control" placeholder="Password" data-rule-required="true" data-msg-required="Please enter your password." />



					</div>



				</div>
<?php

$number1 = rand(1,5);

$number2 = rand(1,5);

$sum = $number1 + $number2;

$_SESSION['captcha_sum'] = $sum;
?>
<input type="hidden" name="cap_has" value="<?php echo md5($_SESSION['captcha_sum']);?>">
         <div class="form-group">

					<!--<label for="password">Password:</label>-->

					<div class="input-icon">

						<i style="width:75px; color:#000;font-size:15px !important; font-style:normal;"><?php echo $number1." + ".$number2." = ";?></i>

						<input style="padding-left:80px !important;font-size:15px !important;" type="text" name="captcha" required class="form-control" placeholder="" data-rule-required="true" autocomplete="off" data-msg-required="Please enter your password." />

					</div>

				</div>



				<!-- /Input Fields -->







				<!-- Form Actions -->



				<div class="form-actions">


					<label class="checkbox pull-left"><input type="checkbox" class="uniform" name="remember" value="1"> Remember Me</label>



					<button type="submit" class="submit btn btn-info btn-primary pull-right">



						Sign In <i class="icon-angle-right"></i>



					</button>



				</div>



			</form>



			<!-- /Login Formular -->







			







		<!-- Forgot Password Form -->



		<div class="inner-box">



			<div class="content">



				<!-- Close Button -->



				<i class="icon-remove close hide-default"></i>







				<!-- Link as Toggle Button -->



				<a href="#" class="forgot-password-link">Forgot Password?</a>







				<!-- Forgot Password Formular -->



				<form id="forgetpass" class="form-vertical hide-default" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">

         <input type="hidden" name="action" value="forgetpassword">

					<!-- Input Fields -->



					<div class="form-group">



						<!--<label for="email">Email:</label>-->



						<div class="input-icon">



							<i class="icon-envelope"></i>



							<input type="email" name="forget_email" required class="form-control" placeholder="Enter email address" data-rule-required="true" data-rule-email="true" data-msg-required="Please enter your email." />



						</div>



					</div>



					<!-- /Input Fields -->







					<button type="submit" class="submit btn btn-info">



						Reset your Password



					</button>



				</form>



				<!-- /Forgot Password Formular -->







				<!-- Shows up if reset-button was clicked -->



				<div class="forgot-password-done hide-default">



					<i class="icon-ok success-icon"></i> <!-- Error-Alternative: <i class="icon-remove danger-icon"></i> -->



					<span>Great. We have sent you an email.</span>



				</div>



			</div> <!-- /.content -->



		</div>



		<!-- /Forgot Password Form -->



	</div>



	<!-- /Login Box -->



 </div> 



  <div class="footer">



		<a href="tel:+18445622283" class="sign-up1">For support call: 1 (844) 562-6683</a>

      <br/>  <br/>

    <a href="http://www.jobnotes.net" target="_blank" class="sign-up1">Online Support at JobNotes.net</a>

	</div>



  



  



  











