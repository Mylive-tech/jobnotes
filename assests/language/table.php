<?php
  /* **************************************************
	*@Project		: Jobnote
	*@Author		: CT
	*@Doc Role		: Defining  database tables name and respective constants for them to use throughout the site
	************************************************** */ 
	define('TBL_PAGES',         			    $configArray->prefix.'pages');
	define('TBL_STAFF',          			    $configArray->prefix.'staff');
	define('TBL_STATE',         			    $configArray->prefix.'state');
  define('TBL_COUNTRIES',         			$configArray->prefix.'countries');
	define('TBL_SERVICE',        			    $configArray->prefix.'service');
	define('TBL_REPORTS',        			    $configArray->prefix.'reports');
	define('TBL_REPORTS_SUBMISSION',      $configArray->prefix.'report_submission ');
	define('TBL_STAFFTYPE',      			    $configArray->prefix.'staff_type');
	define('TBL_ROLEPERMISSION', 			    $configArray->prefix.'role_permission');
	define('TBL_USERPERMISSION', 			    $configArray->prefix.'staff_permissions');
 	define('TBL_JOBLOCATION' ,         		$configArray->prefix.'joblocation');
	define('TBL_CONFIGURATION' ,      		$configArray->prefix.'configuration');
  define('TBL_SITES' ,      		        $configArray->prefix.'sites');
  define('TBL_MODULES' ,      		      $configArray->prefix.'modules');
  define('TBL_JOBSTATUS' ,      		      $configArray->prefix.'jobstatus');
  
   
  
?>