<?php
/*
Plugin Name: IVR Log for Logged In Staff
Description:  This plug-in will show the graphical comparison of IVR Staff logged-in
Author: CT
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class ivr_staff_clock_log
{
  private $objDBCon, $objFunction, $staff_obj, $staff_contractor, $staff_regular;
  private $staff_contractor_login, $staff_regular_login;
  
  function __construct($objFunctions)
  {
    $this->objDBCon = new Database();	
    $this->objFunction = $objFunctions;	
    $this->staff_regular =  $this->staff_contractor = $this->staff_contractor_login = $this->staff_regular_login = $this->scsinid = $this->scsoutid = array();
	$this->driver_login =  $this->driver_logout = $this->sub_contractor_login = $this->sub_contractor_logout = $this->sidewalk_login = $this->sidewalk_logout = $this->drvinid = $this->drvoutid = $this->sdwinid = $this->sdwoutid = array();
    $this->staff_obj = file_get_contents('http://www.ivrhq.me/applications/10042/api/select.php?account_key=3KoD1T56&table=staff_activity');
    $staff_array = array_reverse(json_decode($this->staff_obj, true));
	$new_sa = array(); $b = '';
	$driver_array = $sidewalk_array = $subcontractor_array = array();
	foreach($staff_array as $sa)
	{
		$user_type = $this->objFunction->getUserRoleUsingId($sa['staff_id']);
		if($user_type == 4)
		{
			if(!isset($subcontractor_array[$sa['staff_id']])){
			$subcontractor_array[$sa['staff_id']] = $sa;
			}
		}
		elseif($user_type == 6)
		{
			if(!isset($driver_array[$sa['staff_id']])){
			$driver_array[$sa['staff_id']] = $sa;
			}
		}
		elseif($user_type == 7)
		{
			if(!isset($sidewalk_array[$sa['staff_id']])){
			$sidewalk_array[$sa['staff_id']] = $sa;
			}
		}
		else
		{
			if(!isset($new_sa[$sa['staff_id']])){
				$new_sa[$sa['staff_id']] = $sa;
			}
		}
	} 
	/*Subcontractor*/
	foreach($subcontractor_array as $key => $val) {
     
       if($val['clock_action_description'] == 'clock in') {
              $this->subcontractor_login[$val['staff_id']]= 1;
			  $this->scsinid[] = $val['staff_id'];
       }
       elseif($val['clock_action_description'] == 'clock out') {
               $this->subcontractor_logout[$val['staff_id']]= 1;
			   $this->scsoutid[] = $val['staff_id'];
       }  
    }
	/*Drivers*/
	foreach($driver_array as $key => $val) {
     
       if($val['clock_action_description'] == 'clock in') {
              $this->driver_login[$val['staff_id']]= 1;
			  $this->drvinid[] = $val['staff_id'];
       }
       elseif($val['clock_action_description'] == 'clock out') {
               $this->driver_logout[$val['staff_id']]= 1;
			   $this->drvoutid[] = $val['staff_id'];
       }  
    }
	/*Sidewalk*/
	foreach($sidewalk_array as $key => $val) {
     
       if($val['clock_action_description'] == 'clock in') {
              $this->sidewalk_login[$val['staff_id']]= 1;
			  $this->sdwinid[] = $val['staff_id'];
       }
       elseif($val['clock_action_description'] == 'clock out') {
               $this->sidewalk_logout[$val['staff_id']]= 1;
			   $this->sdwoutid[] = $val['staff_id'];
       }  
    }
	/**/
    foreach($new_sa as $key => $val) {
     
       if($val['clock_action_description'] == 'clock in') {
              $this->staff_regular_login[$val['staff_id']]= 1;
       }
       elseif($val['clock_action_description'] == 'clock out') {
               $this->staff_contractor_login[$val['staff_id']]= 1;
       }  
    }
    $this->ivr_staff_clock_log_init();
  }
  
  function ivr_staff_clock_log_init()
  { 
    $widgetContent='
                <div class="widget-header">
                  <i class="icon-reorder"></i>&nbsp;&nbsp;<h4>IVR - Clocked in / out</span></h4>
                    <div class="toolbar no-padding">
    									<div class="btn-group">
    										<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
    									</div>
    								</div>
                 </div>
     <div class="inside_widget widget-content no-padding">             
          <div class="col-md-12 nopadding clearfix">
			  <div class="col-lg-4 col-xs-12 col-sm-6 clk_graph" id="visualization_clock_subcontactor" style="height: 230px; overflow:hidden;"></div>
			  <div class="col-lg-4 col-xs-12 col-sm-6 clk_graph" id="visualization_clock_driver" style="height: 230px; overflow:hidden;"></div>
			  <div class="col-lg-4 col-xs-12 col-sm-6 clk_graph" id="visualization_clock_sidewalk" style="height: 230px; overflow:hidden;"></div><div id="piechartusers"></div>
          </div>
      </div>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
        
        <script type="text/javascript">
            google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
        </script>
 
        <script type="text/javascript">
            function drawVisualization() {
				var data_sc = google.visualization.arrayToDataTable([
                    [\'Clock In\', \'Number\'],';
                        $widgetContent.='[\'Clocked in '.count($this->subcontractor_login).'\', '.count($this->subcontractor_login).'],';
                        $widgetContent.='[\'Clocked out '.count($this->subcontractor_logout).'\', '.count($this->subcontractor_logout).'],';
  $widgetContent.=']);
				var chart = new google.visualization.PieChart(document.getElementById(\'visualization_clock_subcontactor\'));
				chart.draw(data_sc, {title:"Sub-contractor Clocked In Vs Clocked Out", is3D: true});
				
				google.visualization.events.addListener(chart, "select", function(){
					var str = data_sc.getValue(chart.getSelection()[0].row, 0);
					var res_in = str.match(/Clocked in/g); 
					var res_out = str.match(/Clocked out/g); 
					if(res_in == "Clocked in")
					{
						$(".overlay").show();$(".loader").show();
						$.ajax({
							url: "'.SITE_URL.'plugins/ivr_staff_clock_log/ivr_staff_clock_log_piechart.php",
							method: "POST",
							  data: { customerids: "'.implode(',', $this->scsinid).'"}
							})
							  .done(function( response ) {
								  $("#piechartusers").html("<h3>Sub-contractor Clocked In</h3>"+response);
								  $(".overlay").hide();$(".loader").hide();
						});
					}
					else if(res_out == "Clocked out")
					{
						$(".overlay").show();$(".loader").show();
						$.ajax({
							url: "'.SITE_URL.'plugins/ivr_staff_clock_log/ivr_staff_clock_log_piechart.php",
							method: "POST",
							  data: { customerids: "'.implode(',', $this->scsoutid).'"}
							})
							  .done(function( response ) {
								  $("#piechartusers").html("<h3>Sub-contractor Clocked Out</h3>"+response);
								  $(".overlay").hide();$(".loader").hide();
						});
					}
				});
				
				var data_d = google.visualization.arrayToDataTable([
                    [\'Clock In\', \'Number\'],';
                        $widgetContent.='[\'Clocked in '.count($this->driver_login).'\', '.count($this->driver_login).'],';
                        $widgetContent.='[\'Clocked out '.count($this->driver_logout).'\', '.count($this->driver_logout).'],';
  $widgetContent.=']);
				var chart2 = new google.visualization.PieChart(document.getElementById(\'visualization_clock_driver\'));
                chart2.draw(data_d, {title:"Drivers Clocked In Vs Clocked Out", is3D: true});
				
				google.visualization.events.addListener(chart2, "select", function(){
					var str = data_d.getValue(chart2.getSelection()[0].row, 0);
					var res_in2 = str.match(/Clocked in/g); 
					var res_out2 = str.match(/Clocked out/g); 
					if(res_in2 == "Clocked in")
					{
						$(".overlay").show();$(".loader").show();
						$.ajax({
							url: "'.SITE_URL.'plugins/ivr_staff_clock_log/ivr_staff_clock_log_piechart.php",
							method: "POST",
							  data: { customerids: "'.implode(',', $this->drvinid).'"}
							})
							  .done(function( response ) {
								  $("#piechartusers").html("<h3>Drivers Clocked In</h3>"+response);
								  $(".overlay").hide();$(".loader").hide();
						});
					}
					else if(res_out2 == "Clocked out")
					{
						$(".overlay").show();$(".loader").show();
						$.ajax({
							url: "'.SITE_URL.'plugins/ivr_staff_clock_log/ivr_staff_clock_log_piechart.php",
							method: "POST",
							  data: { customerids: "'.implode(',', $this->drvoutid).'"}
							})
							  .done(function( response ) {
								  $("#piechartusers").html("<h3>Drivers Clocked Out</h3>"+response);
								  $(".overlay").hide();$(".loader").hide();
						});
					}
				});
				
				var data_s = google.visualization.arrayToDataTable([
                    [\'Clock In\', \'Number\'],';
                        $widgetContent.='[\'Clocked in '.count($this->sidewalk_login).'\', '.count($this->sidewalk_login).'],';
                        $widgetContent.='[\'Clocked out '.count($this->sidewalk_logout).'\', '.count($this->sidewalk_logout).'],';
  $widgetContent.=']);
				var chart3 = new google.visualization.PieChart(document.getElementById(\'visualization_clock_sidewalk\'));
                chart3.draw(data_s, {title:"Sidewalk Clocked In Vs Clocked Out", is3D: true});
				
				google.visualization.events.addListener(chart3, "select", function(){
					var str = data_s.getValue(chart3.getSelection()[0].row, 0);
					var res_in3 = str.match(/Clocked in/g); 
					var res_out3 = str.match(/Clocked out/g); 
					if(res_in3 == "Clocked in")
					{
						$(".overlay").show();$(".loader").show();
						$.ajax({
							url: "'.SITE_URL.'plugins/ivr_staff_clock_log/ivr_staff_clock_log_piechart.php",
							method: "POST",
							  data: { customerids: "'.implode(',', $this->sdwinid).'"}
							})
							  .done(function( response ) {
								  $("#piechartusers").html("<h3>Sidewalk Clocked In</h3>"+response);
								  $(".overlay").hide();$(".loader").hide();
						});
					}
					else if(res_out3 == "Clocked out")
					{
						$(".overlay").show();$(".loader").show();
						$.ajax({
							url: "'.SITE_URL.'plugins/ivr_staff_clock_log/ivr_staff_clock_log_piechart.php",
							method: "POST",
							  data: { customerids: "'.implode(',', $this->sdwoutid).'"}
							})
							  .done(function( response ) {
								  $("#piechartusers").html("<h3>Sidewalk Clocked Out</h3>"+response);
								  $(".overlay").hide();$(".loader").hide();
						});
					}
				});
            }  
            google.setOnLoadCallback(drawVisualization);
        </script>             
             ';                
    $this->objFunction->widgetContentArray[] = $widgetContent;
  }
}
?>