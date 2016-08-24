<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo SITE_NAME;?></title>
    <meta name="SKYPE_TOOLBAR" content ="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>	
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />	

    <!--=== CSS ===-->	
    <!-- Bootstrap -->	
    <link href="<?php echo SITE_URL;?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />	
    <!-- Theme -->	
    <link href="<?php echo SITE_URL;?>assets/css/main.css" rel="stylesheet" type="text/css" />	
    <link href="<?php echo SITE_URL;?>assets/css/plugins.css" rel="stylesheet" type="text/css" />	
    <link href="<?php echo SITE_URL;?>assets/css/responsive.css" rel="stylesheet" type="text/css" />	
    <link href="<?php echo SITE_URL;?>assets/css/icons.css" rel="stylesheet" type="text/css" />	
    <!-- Login -->	
    <link href="<?php echo SITE_URL;?>assets/css/login.css" rel="stylesheet" type="text/css" />   	
    <link href="<?php echo SITE_URL;?>assets/css/admin.css" rel="stylesheet" type="text/css" />	
    <link rel="stylesheet" href="<?php echo SITE_URL;?>assets/css/fontawesome/font-awesome.min.css">     
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">    	
    <!--[if IE 7]>
    		<link rel="stylesheet" href="<?php echo SITE_URL;?>assets/css/fontawesome/font-awesome-ie7.min.css">
    	<![endif]--> 	
    <!--[if IE 8]>
    		<link href="<?php echo SITE_URL;?>assets/css/ie8.css" rel="stylesheet" type="text/css" />
    	<![endif]--> 	
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>	
    <!--=== JavaScript ===-->	
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/libs/jquery-1.10.2.min.js"></script>  
<script src="<?php echo SITE_URL;?>dist/vendor/js/vendor.js"></script>  

<script type="text/javascript">
var alert_type=1;
var SITE_URL = '<?php echo SITE_URL;?>'; 
</script>      
  </head>
  <body<?php if($_SESSION['adminid']=='') echo ' class="login"';?>>        
    <?php $objMainframe->mosFunction('top');?> 
    <?php if($_SESSION['adminid']<>''){?> 
    <div id="container">
      <?php } ?>
      <?php if($_SESSION['adminid']<>'') {?>
      <?php $objMainframe->mosFunction('left');?> 
      <?php } ?>  
      <?php utf8_encode($objMainframe->mosBody()); ?> 
      <?php if($_SESSION['adminid']<>''){?> 
    </div>
    <?php } ?>
    <?php $objMainframe->mosFunction('footer');?>          
    </body>
<script src="<?php echo SITE_URL;?>dist/formbuilder.js"></script>   	
<script type="text/javascript" src="<?php echo SITE_URL;?>bootstrap/js/bootstrap.min.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/libs/lodash.compat.min.js"></script> 	
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements --> 	
    <!--[if lt IE 9]>
    		<script src="assets/js/libs/html5shiv.js"></script>
    	<![endif]-->      	
   <!-- General --> 
<?php if($_SESSION['adminid']<>'')
{
    ?> 	
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/libs/breakpoints.js"></script> 
<?php
}
    ?>    	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/respond/respond.min.js"></script>  
    <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) --> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/cookie/jquery.cookie.min.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/slimscroll/jquery.slimscroll.min.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>   	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/fullcalendar/fullcalendar.min.js"></script> 	
    <!-- Noty --> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/noty/jquery.noty.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/noty/layouts/top.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/noty/themes/default.js"></script>      
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/daterangepicker/moment.min.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/daterangepicker/daterangepicker.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/blockui/jquery.blockUI.min.js"></script> 	
    <!-- Forms --> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/uniform/jquery.uniform.min.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/select2/select2.min.js"></script>   	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/datatables/jquery.dataTables.min.js"></script>        	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/datatables/DT_bootstrap.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/datatables/responsive/datatables.responsive.js"></script>  
    <!-- optional -->    	
    <!-- App --> 	
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/app.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/plugins.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/plugins.form-components.js"></script> 	
    <!-- Form Validation --> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/validation/jquery.validate.min.js"></script> 	
    <!-- Slim Progress Bars --> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/nprogress/nprogress.js"></script> 	
    <!-- App --> 	
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/login.js"></script>        	
<script>
	$(document).ready(function(){
	"use strict"; 
		Login.init(); // Init login JavaScript
		App.init(); // Init layout and core plugins
		Plugins.init(); // Init all plugins
		FormComponents.init(); // Init all form-specific plugins
     
	});
</script> 
<!-- Smartphone Touch Events --> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/touchpunch/jquery.ui.touch-punch.min.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/event.swipe/jquery.event.move.js"></script> 	
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/event.swipe/jquery.event.swipe.js"></script>      	
 
<script type="text/javascript" src="<?php echo SITE_JSURL ?>validation.js"></script> 
</html>