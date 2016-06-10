<?php

/**

 * @Date: 17/02/2015

 * @Purpose: Class for Setting Module which inherits the HTML view class

*/

class setting extends HTML_SETTING

{

  

   private $strBackUpFile;

   private $objDatabase;

   public $objFunction, $intId;



     

function __construct($intId, $objMainframe, $objFunctions)

  {

     $this->intId = $intId;

     $this->strBackUpFile    ='backup/db_backup';

	   $this->objDatabase 	= new Database();

	   $this->objMainFrame = $objMainframe;

	   $this->objFunction  = $objFunctions;

	

  	parent :: __construct($this->objFunction); // Call the construction of the HTML view class

  }

/**

     *@Purpose: Change Admin password

	 *@Description: Through this function, change password form will appear in the admin section and password will be changed

*/

public function changePassword()

   {

   Functions:: checkSession();

      if($_POST['action']=="save"): // condition i.e. form is posted to change the password

	     	 $strSql ='SELECT password FROM '.TBL_ADMIN.' where username=\''.$_SESSION['admin_id'].'\'';

			 $this->objRecord = $this->objDatabase->fetchRows($strSql);

			if($this->objRecord): // condition i.e. entered username is correct and session is not expired

			 	   if($this->objRecord->password==$_POST['md_old_password']): // condition that matched the old password entered by user and password saved in database

				   

			           $strSql	=	'Update '.TBL_ADMIN.' set password=\''.$_POST['md_new_password'].'\' where username =\''.$_SESSION['admin_id'].'\'';

	                   

					   $this->objDatabase->dbQuery($strSql); // Update the password with new one

					   

					   $this->objFunction->displayMessage('Administrative password has been reset to "'.$_POST['md_new_password'].'" successfully',$_SERVER['HTTP_REFERER']);  // Function to show the message

					   

			  	  else:

			         $this->objFunction->displayMessage('Invalid old Password. Please try again!',$_SERVER['HTTP_REFERER']);   // Function to show the message

			 	  endif;

				  

			else:

			   

			         $this->objFunction->displayMessage('Your session has been expired. Please login again !',SITE_ADMINURL.'index.php');  // Function to show the message

			 	

			endif;  

	  else:

	 

	 		parent :: changePassword();  // Call HTML function to view the Change password Form

	  endif;	

   } // end function

   

   

/**

      *@Purpose: Set config settings

	  *@Description: Show the configuation form to edit the configuartion values like Flyer limit, contact email address etc.

*/

   public function config()

   {

      Functions:: checkSession();

     $strSql ='SELECT * FROM '.TBL_CONFIGURATION." WHERE 1=1 AND status ='1'";

	   $this->objSet = $this->objDatabase->dbQuery($strSql);

	   parent :: admin_Config($this->objSet);

   } // end function

   

/**

      *@Purpose: Save config settings  

	  *@Description: This function will update the configuation values entered by the administration

 */

   public function saveConfig()

   {

     foreach($_POST as $key=>$value) // Loop to get all the posted values

	   {

	     foreach($value as $key1=>$val)

	     {

	      $strSql	=	"Update ".TBL_CONFIGURATION." set config_value='".$val."' where config_key ='".$key1."'";

	       $this->objDatabase->dbQuery($strSql);

	     }

	   }

     

     if($_FILES['logo']['name']<>'')

     {

       $logoName = $_FILES['logo']['name'];

       $logo= move_uploaded_file($_FILES['logo']['tmp_name'], 'upload/'.$logoName);

       $this->objDatabase->dbQuery("update ".TBL_CONFIGURATION." set config_value='".$logoName."' where config_key ='site_logo'");

     }

	   $this->objFunction->showMessage('Settings have been saved successfully.',$_SERVER['REQUEST_URI']);  // Function to show the message

   } // end function

   

   

/**

    *@Purpose: Create backup of the database

	*@Description: This function will create a sql file script and save it into the physical directory and will produce a link to download the database script.

*/

   public function backupDb($tables = '*')  

   {

   $strBackUpFile = 'Db_backup';

    $server_name=DB_HOST;

	$user_name=DB_USER;

	$password=DB_PASS;

	$db_name=DB_NAME;

	$con=mysql_connect($server_name,$user_name,$password) or die("can not be connected to the database server");

	$db=mysql_select_db($db_name,$con) or mysql_error();

     

      Functions:: checkSession();         

     //get all of the tables  

      if($tables == '*')  

       {  

           $tables = array();  

           $result = mysql_query('SHOW TABLES');  

          while($row = mysql_fetch_row($result))  

          {  

               $tables[] = $row[0];  // store all the table name in the array 

          }  

      }  

       else  

      {  

           $tables = is_array($tables) ? $tables : explode(',',$tables);   // convert the comma sepeated table names in to array

      }  

         

       //cycle through  

      foreach($tables as $table)  

      {  

           $result = mysql_query('SELECT * FROM '.$table);  

          $num_fields = mysql_num_fields($result);  

           

          $return.= 'DROP TABLE '.$table.';';  

          $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));  

         $return.= "\n\n".$row2[1].";\n\n";  

           

       for ($i = 0; $i < $num_fields; $i++)   

          {  

              while($row = mysql_fetch_row($result))  

              {  

                $return.= 'INSERT INTO '.$table.' VALUES(';  

                  for($j=0; $j<$num_fields; $j++)   

                 {  

                      $row[$j] = addslashes($row[$j]);  

                     $row[$j] = ereg_replace("\n","\\n",$row[$j]);  

                      if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }  

                      if ($j<($num_fields-1)) { $return.= ','; }  

                  }  

                  $return.= ");\n";  

              }  

           }  

           $return.="\n\n\n";  

      }  

       

      //save file  

     $handle = fopen('backup/'.$strBackUpFile.'.sql','w+');  

       fwrite($handle,$return);  

       fclose($handle); 

	  

	    $this->objFunction->showMessage('Database backup has been created successfully.<br /><br /> To download your database script <a href="'.SITE_URL.'download.php?f='.$strBackUpFile.'.sql&file=1">Click here</a>.',$_SERVER['REQUEST_URI']); // Function to show the message and link to download the sql script file. 

   }  // end function

   

   

   public function forgotpassword(){

			$strSql = 'SELECT * FROM '.TBL_ADMIN.' WHERE email=\''.$_POST['md_email'].'\'';

			$objRowData = $this->objDatabase->dbQuery($strSql);

			if(intval($objRowData->num_rows)==0){

				 $_SESSION['admin_f_pass_msg'] = 'Email Address does not exists';	

			}else{

					$objRs = $objRowData->fetch_object();

					$arrUser['password'] = $objRs->password;

					$arrUser['email'] = $objRs->email;

					$arrUser['username'] = $objRs->username;

					$this->objFunction->_mailForgotPasswordToUser($arrUser);

					$_SESSION['admin_f_pass_msg'] = "Password has been sent to your email id";	

			}

		

		$emailAdd =  $this->objFunction->iSetting('admin_email');

		

		header('location:'.SITE_ADMINURL.'forgot_password.php');

		

	}

  

  public function listModules()

  {

    $strSql ='select * from '.TBL_MODULES.' order by id asc';

    $res =  $this->objDatabase->dbQuery($strSql);

    parent :: listModules($res);

  }

   

   

		/**************************************************************************

	* @Purpose: Set config settings

	* @Description: Show the configuation form to edit the configuartion values like Flyer limit, contact email address etc.

 ***************************************************************************/  

   public function changeSetting()

   {

      $strSql ='SELECT * FROM '.TBL_CONFIGURATION." WHERE 1=1";

	    $this->objSet = $this->objDatabase->dbQuery($strSql);

	    parent :: admin_Config($this->objSet);

   } // end function

   

   public function addPlugin()

   {

     $msgStatus='';

     if(!empty($_POST))

     {

        $zip = new ZipArchive;

          if ($zip->open($_FILES['md_plugin']['tmp_name'])===TRUE)

          {

             $zip->extractTo(SITE_ABSPATH.'/plugins/');

             $name =substr($zip->getNameIndex(0), 0, -1);

             $pluginInfo = $this->objFunction->get_file_data(SITE_ABSPATH.'/plugins/'.$name.'/'.$name.'.php');

            

             $modID = $this->objDatabase->insertQuery("INSERT INTO ".TBL_MODULES." (title, description, module_dir, version, status)

              values ('".$pluginInfo['Name']."', '".addslashes($pluginInfo['Description'])."', '".$name."', '".$pluginInfo['Version']."', '1')");

            

             if($pluginInfo['Site Plugin']=='Yes')

             {

               $this->objDatabase->insertQuery("INSERT INTO ".TBL_SITES." (site_title, module_id, icon_class) VALUES ('".trim($pluginInfo['Name'])."', '".$modID."', '".trim($pluginInfo['Icon Class'])."')");

             }

             

             $this->objFunction->showMessage('Plugin has been created and activated successfully.', ISP :: AdminUrl('setting/manage-plugins')); 

          } 

          else 

          {

            $this->objFunction->showMessage('Plugin import has been failed. Please try again.', ISP :: AdminUrl('setting/addplugin'));

          }

     }

     else

       parent::addPlugin($msgStatus);

   }

   

   public function updatePlugin()

   {

     $this->objDatabase->dbQuery("update ".TBL_MODULES." set status='".$_GET['st']."' where id='".$this->intId."'");

     $this->objFunction->showMessage('Plugin status has been updated successfully.', ISP :: AdminUrl('setting/manage-plugins'));

   }

   

   public function removeplugin()

   {

     $objRow = $this->objDatabase->fetchRows('select module_dir from '.TBL_MODULES.' where id='.$this->intId);

     unlink(SITE_ABSPATH.'/plugins/'.$objRow->module_dir.'/'.$objRow->module_dir.'.php');

     unlink(SITE_ABSPATH.'/plugins/'.$objRow->module_dir);

     rmdir(SITE_ABSPATH.'/plugins/'.$objRow->module_dir);

     $this->objDatabase->dbQuery('DELETE from '.TBL_MODULES.' where id='.$this->intId);

     $this->objFunction->showMessage('Plugin has been removed successfully.', ISP :: AdminUrl('setting/manage-plugins'));

   } 

   

   

} // end of Class

/**

  @Purpose: Create the object the class "setting"

*/

$objSetting = new setting($intId, $objMainframe, $objFunctions);

switch($strTask)

{

   case 'backup':

        $objSetting->backupDb();

    break;

	

	case 'change':

       $objSetting->changePassword();

    break;

	

  case 'config':

       $objSetting->config();

    break;

	

  case 'saveConfig':

	    $objSetting->saveConfig();

	break;

	

  case 'forgot':

		  $objSetting->forgotpassword();

	break;

	

  case 'general-settings':

		  $objSetting->changeSetting();

	break;

  

  case 'manage-plugins':

      $objSetting->listModules();

  break;

  

  case 'addplugin':

      $objSetting->addPlugin();

  break;

  

  case 'updateplugin':

     $objSetting->updatePlugin();

  break;

  

  case 'removeplugin':

     $objSetting->removePlugin();

  break;

	

}   

?>