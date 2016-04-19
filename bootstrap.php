<?php 
$objMainframe = new mainframe();
if($strDir<>'' && is_dir('modules/'.$strDir))
{
  if(strstr($_SERVER['REQUEST_URI'],ADMIN_FOLDER))
	{
	   $objFunctions->checkSession();
     $objMainframe->setLeftFile('admin_left-bar.php');
     $objMainframe->setTopFile('admin.top.inc.php');
     $objMainframe->setFooterFile('admin.bottom.inc.php');
     
     require_once('modules/'.$strDir.'/'.$strDir.'.html.php');
     require_once('modules/'.$strDir.'/'.$strDir.'.php');
	} 
  else  
  {
    if(isset($_SESSION['adminid']))  
      {  
        $objMainframe->setTopFile('frontend.top.inc.php');
        $objMainframe->setFooterFile('frontend.bottom.inc.php'); 
        require_once('modules/'.$strDir.'/'.$strDir.'.html.php');
        require_once('modules/'.$strDir.'/'.$strDir.'.php');
      }
     else
     {
        require_once('modules/home.php');
     } 
  }
}
else
{
    if(strstr($_SERVER['REQUEST_URI'],ADMIN_FOLDER))
    {
        $objMainframe->setTopFile('admin.top.inc.php');
	      require_once('modules/login.php');
    }
  	else
    {	
      require_once('modules/home.php');
    }
}
$outputBuffer = ob_get_contents();
ob_end_clean();
$objMainframe->outputBuffer = $outputBuffer;
?>