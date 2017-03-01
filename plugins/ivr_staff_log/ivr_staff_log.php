<?php
/*
Plugin Name: IVR Staff Log
Description:  This plug-in will show the graphical comparison of IVR Regular Staff and Contractors
Author: CT
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class ivr_staff_log
{
  private $objDBCon, $objFunction, $staff_obj, $staff_contractor, $staff_regular;
  private $staff_contractor_login, $staff_regular_login;
  
  function __construct($objFunctions)
  {
    $this->objDBCon = new Database();	
    $this->objFunction = $objFunctions;	
    $this->staff_regular =  $this->staff_contractor = $this->staff_contractor_login = $this->staff_regular_login = array();
    $this->staff_obj = file_get_contents('http://www.ivrhq.me/applications/10042/api/select.php?account_key=3KoD1T56&table=staff_activity');
    $staff_array = json_decode($this->staff_obj, true);
    
    foreach($staff_array as $key => $val) {
     
       if($val['payroll_log'] == 'yes') {
         $this->staff_regular[$val['staff_id']] = 1; 
       }
       elseif($val['payroll_log'] == 'no') {
         $this->staff_contractor[$val['staff_id']]=1; 
       }  
    }
    $this->ivr_staff_log_init();
  }
  
  function ivr_staff_log_init()
  {  
    $titleClass = $this->objFunction->cleanURL("IVR Staff Log");
    $widgetContent='
                <div class="widget-header dragwidget_'.$titleClass.'">
                  <i class="icon-reorder"></i>&nbsp;&nbsp;<h4>IVR Staff Log</span></h4>
                    <div class="toolbar no-padding">
    									<div class="btn-group">
    										<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
    									</div>
    								</div>
                 </div>
     <div class="inside_widget widget-content no-padding">             
          <div class="col-md-12 nopadding">
              <div id="visualization" style="width: 100%; height: 230px; overflow:hidden;"></div>
          </div>
      </div>
 
        <script type="text/javascript">
            {
                var data = google.visualization.arrayToDataTable([
                    [\'Clock In\', \'Number\'],';
                        $widgetContent.='[\'Regular '.count($this->staff_regular).'\', '.count($this->staff_regular).'],';
                        $widgetContent.='[\'Contractor '.count($this->staff_contractor).'\', '.count($this->staff_contractor).'],';
  $widgetContent.=']);
                new google.visualization.PieChart(document.getElementById(\'visualization\')).
                draw(data, {title:"Regular Vs Contractors Staff", is3D: true});
            }
        </script>             
             ';                
    $this->objFunction->widgetContentArray[] = $widgetContent;            
  }
}
?>