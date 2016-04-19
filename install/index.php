<?php
$step=1;
if(isset($_REQUEST['step']))
{
   $step = $_REQUEST['step'];
}

if($step>1 && !file_exists( '../config/config.php' )) 
{
 @header("Location: index.php?step=1");
  die();
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if($step==1)
  {
    $dbSettingArray['dbname']=trim($_POST['dbname']);
    $dbSettingArray['dbhost']=trim($_POST['dbhost']);
    $dbSettingArray['dbport']=trim($_POST['dbport']);
    $dbSettingArray['username']=trim($_POST['uname']);
    $dbSettingArray['passsword']=trim($_POST['pwd']);
    $dbSettingArray['prefix']=trim($_POST['prefix']); 
    $dbConfigJson = json_encode($dbSettingArray);
    
      $filename = 'config.php';
      $configLocation ='../config/'.$filename;
      if (is_writable(dirname($configLocation))) 
      {
         if (!$handle = fopen($configLocation, 'w'))
          {
               echo "Cannot open file ($filename)";
               exit;
          }           
         if (fwrite($handle, $dbConfigJson) === FALSE)
          {
              echo "Cannot write to file ($filename)";
              exit;
          }            
          fclose($handle); 
          @header("Location: index.php?step=2");
          die();
      
      } 
      else
      {
          echo "The file $filename could not be created in config folder. Please provide write permission to config folder.";
      }
  } 
  
  
  /* Action on submissions of Step 2*/
  
  if($step==2)
  {
    include('../settings.php');
    $siteUrl = trim($_POST['siteurl']);
    $siteTitle = trim($_POST['site_title']);
    $siteEmail = trim($_POST['site_email']);
    $adminUser = trim($_POST['username']);
    $adminPwd = trim($_POST['pwd']);
    include('db.sql.php');
    
    /* Add Configuration Values in DB */
    $objDatabase->dbQuery("INSERT INTO ".TBL_CONFIGURATION." (config_key, config_label, config_value) 
                          values ('site_url', 'Site Address', '".$siteUrl."')");
    $objDatabase->dbQuery("INSERT INTO ".TBL_CONFIGURATION." (config_key, config_label, config_value) 
                          values ('site_title', 'Site Title', '".$siteTitle."')");
    $objDatabase->dbQuery("INSERT INTO ".TBL_CONFIGURATION." (config_key, config_label, config_value) 
                          values ('site_email', 'Contact Email Address', '".$siteEmail."')");
    $objDatabase->dbQuery("INSERT INTO ".TBL_CONFIGURATION." (config_key, config_label, config_value) 
                          values ('site_timezone', 'Timezone', '')");                          
    $objDatabase->dbQuery("INSERT INTO ".TBL_CONFIGURATION." (config_key, config_label, config_value) 
                          values ('site_logo', 'Logo', '')");
    $objDatabase->dbQuery("INSERT INTO ".TBL_CONFIGURATION." (config_key, config_label, config_value) 
                          values ('sidebar_welcome_message', 'Welcome Message', '')");
    
    $objDatabase->dbQuery("INSERT INTO ".TBL_STAFF." (site_id, username, f_name, l_name, email, password, user_type) 
                          values ('0', '".$adminUser."', 'Web', 'Administrator', '".$siteEmail."', '".md5($adminPwd)."','1')");                                                                                                                
    
    @header("Location: index.php?step=3");
    die();                      
  }
}

if($step>1)
{
  include('settings.php');
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Installation of JobNots</title>
<style type="text/css">
body
{
  background-color: #f9f9f9;
  padding-top: 60px;
  font-family:arial;
  font-size:12px;
}
th{text-align:left;}
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">
<?php
if($step==1)
{
?>
<table width="70%" cellpadding="10" cellspacing="0" style="border:1px solid #4d7496;">
 <tr>
  <td style="background:#4d7496; color:#fff;" height="30">Installation Step#1</td> 
 </tr>
 <tr>
 <td>Below you should enter your database connection details. If you're not sure about these, contact your host.</td>
 </tr>
 <tr>
  <td>
  <form method="post" action="index.php">
  <input type="hidden" name="step" value="1">
    <table class="form-table">
        <tbody>
        <tr>
        	<th scope="row" width="20%"><label for="dbname">Database Name</label></th>
        	<td><input name="dbname" id="dbname" required type="text" size="25" value="jobnotes"></td>
        	<td>The name of the database you want to run in.</td>
        </tr>
        <tr>
        	<th scope="row"><label for="uname">User Name</label></th>
        	<td><input name="uname" id="uname" required type="text" size="25" value="username"></td>
        	<td>Your MySQL username</td>
        </tr>
        <tr>
        	<th scope="row"><label for="pwd">Password</label></th>
        	<td><input name="pwd" id="pwd" required type="text" size="25" value="password"></td>
        	<td>…and your MySQL password.</td>
        </tr>
        <tr>
        	<th scope="row"><label for="dbhost">Database Host</label></th>
        	<td><input name="dbhost" id="dbhost" required type="text" size="25" value="localhost"></td>
        	<td>You should be able to get this info from your web host, if <code>localhost</code> does not work.</td>
        </tr> 
        <tr>
        	<th scope="row"><label for="dbhost">Database Port No</label></th>
        	<td><input name="dbport" id="dbport" required type="text" size="25" value="3306"></td>
        	<td>By Default MySQL DB Port No is 3306.</td>
        </tr>
        <tr>
        	<th scope="row"><label for="prefix">Table Prefix</label></th>
        	<td><input name="prefix" id="prefix" type="text" value="jn_" size="25"></td>
        	<td>If you want to run multiple JobNotes installations in a single database, change this.</td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td><input type="submit" value="Finish"></td>
          <td></td>
        </tr>
        </tbody>
    </table>
   </form> 
  </td>
 </tr>
</table>
<?php
}

if($step==2)
{
?>
<table width="70%" cellpadding="10" cellspacing="0" style="border:1px solid #4d7496;">
 <tr>
    <td style="background:#4d7496; color:#fff;" height="30">Installation Step#2</td> 
 </tr>
 
 <tr>
  <td>
  <form method="post" action="index.php">
  <input type="hidden" name="step" value="2">
    <table class="form-table">
        <tbody>
        <tr>
        	<th scope="row" width="20%"><label for="dbname">Site URL</label></th>
        	<td><input name="siteurl" id="siteurl" required type="text" size="25" placeholder="http://www.example.com"></td>
        </tr>
        
        <tr>
        	<th scope="row" width="20%"><label for="dbname">Site Title</label></th>
        	<td><input name="site_title" id="site_title" required type="text" size="25" value="JobNotes"></td>
        </tr>     
        
        <tr>
        	<th scope="row" width="20%"><label for="dbname">Contact Email Address</label></th>
        	<td><input name="site_email" id="site_email" required type="text" size="25" placeholder="contact@example.com"></td>
        </tr> 
        
        <tr>
        	<th scope="row"><label for="uname">Admin Username</label></th>
        	<td><input name="username" id="username" required type="text" size="25" value="administrator"></td>
        </tr>
        
        <tr>
        	<th scope="row"><label for="uname">Admin Login Password</label></th>
        	<td><input name="pwd" id="pwd" required type="text" size="25" value=""></td>
        </tr>
        
        <tr>
          <th>&nbsp;</th>
          <td><input type="submit" value="GO"></td>
          <td></td>
        </tr>
        </tbody>
    </table>
   </form> 
  </td>
 </tr>
</table>
<?php
}

if($step==3)
{
?>
<table width="70%" cellpadding="10" cellspacing="0" style="border:1px solid #4d7496;">
 <tr>
    <td style="background:#4d7496; color:#fff;" height="30">Installation Finish</td> 
 </tr>
 <tr>
   <td>
    <p>Congratulations! Installation of system has been completed successfully.</p>
    <p align="center">
     <a href="<?php echo SITE_ADMINURL;?>"><button>Go To Admin</button>
      &nbsp;&nbsp;&nbsp;
     <a href="<?php echo SITE_URL;?>"><button>Go To Front-end</button>
    </p>
   </td>
 </tr>
</table> 
<?php
}
?>
</td>
</tr>
</body>
</html>