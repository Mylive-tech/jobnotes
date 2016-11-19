<?php
require_once('settings.php');
//
$dt = disk_total_space(getcwd());
$df =  disk_free_space(getcwd());
$du = $dt-$df;
$dp = sprintf('%.2f',($du / $dt) * 100);
$dt = number_format($dt / 1073741824, 2) . ' GB';
$df = number_format($df / 1073741824, 2) . ' GB';
$du = number_format($du / 1073741824, 2) . ' GB';
/*?>
<span style='float: left;'><?php echo "$du of $dt used"; ?></span>
<span style='float: right;'><?php echo "$df of $dt free"; ?></span>
<div></div>
<?php */
if( $dp == 50 || $dp == 80 || $dp == 90 ):
	//$strEmail = 'krathore@raveinfosys.com';
	$objRow = $objFunctions->getSystemNotificationEmail();
	$strEmail = $objRow->config_value;
	$body = 'Your system is using '.$dp.'% of your hard drive.';
	$subject = 'Hard Drive Usage';
	$objFunctions->mosMail('', '', $strEmail, $subject, $body);
	echo 'System alert email has been sent successfully...';
elseif( $dp == 95 ):
	$objRow = $objFunctions->getSystemNotificationEmail();
	$strEmail = $objRow->config_value;
	$body = 'Your system is using '.$dp.'% of your hard drive.';
	$subject = 'Hard Drive Usage';
	$objFunctions->mosMail('', '', $strEmail, $subject, $body);
	echo 'System alert email has been sent successfully...';
endif;

die;