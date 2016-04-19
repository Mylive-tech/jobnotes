<?php
/**
 * @Purpose: Validation for Config file existence and include it
*/
if (!file_exists('config/config.php')) 
{
	@header("Location: install/index.php?step=1");
  die();
}
else
{
    require_once( 'settings.php' );
    
    require_once('bootstrap.php'); // Include Frontend File
    
    if(strstr($_SERVER['REQUEST_URI'],ADMIN_FOLDER))
    { 
        require_once('templates/adminindex.php');
    }
    else
    {  
        require_once('templates/index.php');
    }
} 
?>