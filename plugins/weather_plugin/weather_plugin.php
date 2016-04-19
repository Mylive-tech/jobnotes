<?php
/*
Plugin Name: Weather Plugin
Description:  This plug-in will list Weather forecast.
Author: CT
Version: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class weather_plugin
{
  private $objDBCon, $objFunction;
  
  function __construct($objFunctions)
  {
    $this->objDBCon = new Database();	
    $this->objFunction = $objFunctions;	
    $this->weather_widget_init();
    $this->objDBCon->dbQuery("INSERT INTO ".TBL_CONFIGURATION." (config_key, config_label, config_value)
          SELECT * FROM (SELECT 'weather_forecast_city', 'Weather Forecast City(<small>Cityname, State code</small>)', 'Miami, FL') AS tmp
              WHERE NOT EXISTS (
                  SELECT config_key FROM ".TBL_CONFIGURATION." WHERE config_key = 'weather_forecast_city') LIMIT 1");
  }
  
  function weather_widget_init()
  { 
    $objWeatherCityInfo = $this->objDBCon->fetchRows("SELECT * FROM ".TBL_CONFIGURATION." where config_key='weather_forecast_city'");		
    $titleClass = $this->objFunction->cleanURL("Weather Plugin");
    
    $widgetContent='<link href="'.SITE_URL.'plugins/weather_plugin/weather.css" rel="stylesheet" type="text/css">
                    <script src="'.SITE_URL.'plugins/weather_plugin/weather.js"></script>
                <div class="widget-header dragwidget_'.$titleClass.'">
                  <i class="icon-reorder"></i>&nbsp;&nbsp;<h4>Weather Forecast ('.$objWeatherCityInfo->config_value.')</span></h4>
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
    $this->objFunction->widgetContentArray[] = $widgetContent;            
  }
}
?>