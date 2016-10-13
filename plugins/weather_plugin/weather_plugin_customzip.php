<?php
require_once('../../settings.php');
	if(isset($_POST['zipcode']))
	{
		$zip = $_POST['zipcode'];
	  //Function to retrieve the contents of a webpage and put it into $pgdata
	  $pgdata =""; //initialize $pgdata
	  $fd = fopen("http://zipinfo.com/cgi-local/zipsrch.exe?zip=$zip","r"); //open the url based on the user input and put the data into $fd
	  while(!feof($fd)) {//while loop to keep reading data into $pgdata till its all gone
		$pgdata .= fread($fd, 1024); //read 1024 bytes at a time
	  }
	  fclose($fd); //close the connection
	  if (preg_match("/is not currently assigned/", $pgdata)) {
		$city = "N/A";
			$state = "N/A";
	  }
	  else {
		  $citystart = strpos($pgdata, "Code</th></tr><tr><td align=center>");
		$citystart = $citystart + 35;
		$pgdata = substr($pgdata, $citystart);
		$cityend = strpos($pgdata, "</font></td><td align=center>");
		$city = substr($pgdata, 0, $cityend);
	  
		$statestart = strpos($pgdata, "</font></td><td align=center>");
		$statestart = $statestart + 29;
		$pgdata = substr($pgdata, $statestart);
		$stateend = strpos($pgdata, "</font></td><td align=center>");
		$state = substr($pgdata, 0, $stateend);
		}
		 $zipinfo['zip'] = $zip;
		 $zipinfo['city'] = $city;
		 $zipinfo['state'] = $state;

    $objWeatherCityInfo = $objDatabase->fetchRows("SELECT * FROM ".TBL_CONFIGURATION." where config_key='weather_forecast_city'");		
    $titleClass = $objFunctions->cleanURL("Weather Plugin");
	$objWeatherCityInfo->config_value = $zipinfo['city'].", ".$zipinfo['state'];
	} 
	else
	{
		$objWeatherCityInfo->config_value=$objWeatherCityInfo->config_value;
	}
	
    $widgetContent='<link href="'.SITE_URL.'plugins/weather_plugin/weather.css" rel="stylesheet" type="text/css">
                    <script src="'.SITE_URL.'plugins/weather_plugin/weather.js"></script>
					<script>$(document).ready(function(e) {$("#zip").click(function(){this.focus();})});</script>
                <div class="widget-header dragwidget_'.$titleClass.'">
                  <i class="icon-reorder"></i>&nbsp;&nbsp;<h4>Weather Forecast ('.$objWeatherCityInfo->config_value.')</span></h4><form action="" method="post"><input type="text" id="zip" name="zip" maxlength="5" size="10" placeholder="Zipcode" required><input type="submit" value="View" name="getzipinfo" onclick="getdetailsfromzip(\''.SITE_URL.'plugins/weather_plugin/weather_plugin_customzip.php\'); return false;"></form>	
                    <div class="toolbar no-padding">
    									<div class="btn-group">
    										<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
    									</div>
    								</div>
                 </div>
     <div class="inside_widget widget-content no-padding">
      <div class="weather">'.$objWeatherCityInfo->config_value.'</div>
     </div>
     <script type="text/javascript">
      $(document).ready(function () { 
           $(".weather").weatherFeed({relativeTimeZone:true, pluginPath:\''.SITE_URL.'plugins/weather_plugin/\'});
      });
     </script>    
     ';                
	echo $widgetContent; die;
?>