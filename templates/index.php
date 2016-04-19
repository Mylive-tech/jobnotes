<?php
	global $objMainframe;
	global $objFunctions;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo SITE_NAME;?></title>
<meta name="SKYPE_TOOLBAR" content ="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>
  		  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	      <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <!--=== CSS ===-->

	<!-- Bootstrap -->
	<link href="<?php echo SITE_URL;?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!-- Theme -->

	<link href="<?php echo SITE_URL;?>assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo SITE_URL;?>assets/css/icons.css" rel="stylesheet" type="text/css" />

	<!-- Login -->
	<link href="<?php echo SITE_URL;?>assets/css/login.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo SITE_URL;?>assets/css/front.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo SITE_URL;?>assets/css/main_front.css" rel="stylesheet" type="text/css" />
  
 	<link href="<?php echo SITE_URL;?>assets/css/responsive_front.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo SITE_URL;?>assets/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php echo SITE_URL;?>assets/css/fontawesome/font-awesome.min.css">
  
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link href="<?php echo SITE_URL;?>assets/css/animate.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo SITE_URL;?>assets/js/plugins/lightbox2-master/dist/css/lightbox.css" rel="stylesheet" type="text/css" /> 
   
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo SITE_URL;?>assets/css/fontawesome/font-awesome-ie7.min.css">
	<![endif]-->

	<!--[if IE 8]>
		<link href="<?php echo SITE_URL;?>assets/css/ie8.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php echo SITE_URL;?>assets/jquery.mCustomScrollbar.css">


	<!--=== JavaScript ===-->

	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/libs/jquery-1.10.2.min.js"></script>
  
	<script type="text/javascript" src="<?php echo SITE_URL;?>bootstrap/js/bootstrap.min.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/libs/lodash.compat.min.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/plugins/lightbox2-master/dist/js/lightbox.js"></script> 

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="assets/js/libs/html5shiv.js"></script>
	<![endif]-->
  
  	<!-- Smartphone Touch Events -->
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/touchpunch/jquery.ui.touch-punch.min.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/event.swipe/jquery.event.move.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/event.swipe/jquery.event.swipe.js" defer="defer"></script>
  
  	<!-- General -->
<?php if($_SESSION['adminid']<>'')
{
?>
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/libs/breakpoints.js" defer="defer"></script>
<?php
}
?>  
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/respond/respond.min.js" defer="defer"></script> <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/cookie/jquery.cookie.min.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/slimscroll/jquery.slimscroll.min.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/slimscroll/jquery.slimscroll.horizontal.min.js" defer="defer"></script>

  	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/fullcalendar/fullcalendar.min.js" defer="defer"></script>

	<!-- Noty -->
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/noty/jquery.noty.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/noty/layouts/top.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/noty/themes/default.js" defer="defer"></script>
  
  <script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/daterangepicker/moment.min.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/daterangepicker/daterangepicker.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/blockui/jquery.blockUI.min.js" defer="defer"></script>

	<!-- Forms -->
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/uniform/jquery.uniform.min.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/select2/select2.min.js" defer="defer"></script>

  	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/datatables/jquery.dataTables.min.js" defer="defer"></script>
      
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/datatables/DT_bootstrap.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/datatables/responsive/datatables.responsive.js" defer="defer"></script> <!-- optional -->
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/plugins.js" defer="defer"></script>
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/plugins.form-components.js" defer="defer"></script>

	<!-- Form Validation -->
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/validation/jquery.validate.min.js"></script>

	<!-- Slim Progress Bars -->
	<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/nprogress/nprogress.js" defer="defer"></script>

	<!-- App -->
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/login.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script type="text/javascript">
	$(function(){
          lightbox.init();
        });
	$(document).ready(function(){
		$( ".navbar-toggle" ).click(function() {
		  $( '#hidethemenu' ).toggleClass( "shivmenu" );
		  $('#content').toggleClass("mleftzero");
		});

	});

	</script>

	<script type="text/javascript">
	var colCount = 6;
    var colWidth = 0;
    var margin = 0;
    var windowWidth = 0;
    var blocks = [];

  function setupBlocks() {
        
        if ($('.propertyouterblock').width() > 768) {
            windowWidth = $('.propertyouterblock').width()+10; 
            windowHeight = $('.propertyouterblock').height();
            
            $('.propertyouterblock').css({
                    'height': windowHeight+'px',
                    'min-height': '600px'
                });
            
            colWidth = $('.propertyblock').outerWidth();
            blocks = [];
            
            colCount = Math.floor(windowWidth/(colWidth+margin));
            console.log(colCount);
            for(var i=0;i<colCount;i++){
                blocks.push(margin);
            }
            positionBlocks();   
        }    
    }

    function positionBlocks() {
        $('.propertyblock').each(function(){ 
            var min = Array.min(blocks);
            var index = $.inArray(min, blocks);
            var leftPos = margin+(index*(colWidth+margin));
            $(this).css({
                'left':leftPos+'px',
                'top':(min+20)+'px',
                'position': 'absolute'
            });
            blocks[index] = min+$(this).outerHeight()+margin;
        });	
        
        //$('.propertyouterblock').height($('.propertyblock').outerHeight());
    }

    // Function to get the Min value in Array
    Array.min = function(array) {
        return Math.min.apply(Math, array);
    };
    
    $(document).ready(function(){
	"use strict";
		Login.init(); // Init login JavaScript

		App.init(); // Init layout and core plugins
		Plugins.init(); // Init all plugins
		FormComponents.init(); // Init all form-specific plugins      
    
	});
</script>

<script type="text/javascript" src="<?php echo SITE_JSURL ?>validation.js"></script>
<script type="text/javascript">
var alert_type=1;
var SITE_URL = '<?php echo SITE_URL;?>';
</script>
</head>
<body<?php if($_SESSION['adminid']=='') echo ' class="login"';?>>
       
<?php 
$objMainframe->setTopFile('frontend.top.inc.php');
$objMainframe->mosFunction('top');

if($_SESSION['adminid']<>'')
{
?>
<div id="container" class="staff_container">
<?php 
} 

if($_SESSION['adminid']<>'') 
{
   $objMainframe->setLeftFile('frontend.left.inc.php');
   $objMainframe->mosFunction('left');
}

utf8_encode($objMainframe->mosBody());

if($_SESSION['adminid']<>'')
{
?>
</div>
<?php 
}

$objMainframe->setLeftFile('frontend.footer.inc.php');
$objMainframe->mosFunction('footer');?>
</body>
</html>
