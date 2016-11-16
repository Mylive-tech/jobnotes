<?php
/**
 * @Author: CT
 * @Purpose: Class for Content Module which inherits the HTML view class
*/

class dashboard extends DSHBOARD_HTML_CONTENT
{
  private $intId;
  private $objDatabase;
  private $objMainFrame;
  private $objRecord;
  private $objSet;
  public $url;
  
/**
    *@Purpose: Define the construction of the class and create the instaces of Database class, Mainframe class and Functions class
*/
  function __construct($intId, $objMainframe, $objFunctions)
  {
    $this->intId = $intId;
	$this->objDatabase 	= new Database();
	$this->objMainFrame = $objMainframe;
	$this->objFunction  = $objFunctions;
	
	
	parent :: __construct($this->objFunction); // Call the construction of the HTML view class
  }
  
  
   public function show_admin_Dashboard()
  {
       
				parent :: show_Dashboard($this->objRecord);    
  		
  }
  
   public function show_Dashboard()
  {
       if($_SESSION['adminid']>1)
	   {
			$strSql = "SELECT s.*,j.location_id, j.assigned_to, j.map_widget, j.lat, j.lag FROM ".TBL_SERVICE." s inner join ".TBL_JOBLOCATION. " j on(s.id=j.location_id) inner join ".TBL_ASSIGN_PROPERTY." ap on(j.id=ap.property_id) where 1=1 and j.site_id='".$_SESSION['site_id']."' and ap.user_id='".$_SESSION['adminid']."' and j.status=1 and s.status=1 group by s.name";
	   }
	   else
	   {
			$strSql = "SELECT s.*,j.location_id, j.assigned_to, j.map_widget, j.lat, j.lag FROM ".TBL_SERVICE." s inner join ".TBL_JOBLOCATION. " j on(s.id=j.location_id) where 1=1 and j.site_id='".$_SESSION['site_id']."' and j.status=1 and s.status=1 group by s.name";	
	   }
				
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent :: show_staff_dashboard($this->objSet);
		
  }
  
  public function admin_dashboard_refresh()
  {
	$cont = '';
	$cont .= '<h4 class="text-left heding_6 dashboardheading">Dashboard <img src="'.SITE_URL.'/assets/img/refresh-loader.gif" id="refresh_loader" style="display:none;width:25px;float:right;" /></h4>	
			
		<div class="col-md-12 nopadding">               
		  <div class="welcome_message widget box drag">                  
			<!--<div class="widget-header">-->
			 <div class="widget-header dragwidget_location_status_details">   
				<i class="icon-reorder"></i> <h4>GETTING STARTED</h4>				          
			<!--</div>-->
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse">
							<i class="icon-angle-down"></i>
						</span>
					</div>
				</div> 
			</div>                       
			<!--<a class="welcome-panel-close" href="javascript: void(0);">          
			  <i class="icon-remove-sign"></i> Dismiss
			</a> -->                          
			<div class="inside_widget col-md-12 padding10 widget-content">Welcome to JobNotes, we&acute;ve assembled links to get you started quickly. You can also use the navigation on left to use this system.                             
			  <ul class="col-md-12">                               
				<li>            
				<i class="icon-map-marker"></i>            
				<a href="'.ISP :: AdminUrl('property/manage-properties/').'">Manage Properties</a>            
				</li>                               
				<li>            
				<i class="icon-map-marker"></i>            
				<a href="'.ISP :: AdminUrl('property/add-property/').'">Add New Property</a>            
				</li>                               
				<li>            
				<i class="icon-map-marker"></i>            
				<a href="'.ISP :: AdminUrl('service/manage-locations/').'">Manage Locations</a>            
				</li>                               
				<li>            
				<i class="icon-map-marker"></i>            
				<a href="'.ISP :: AdminUrl('service/add-location/').'">Add New Location</a>            
				</li>                               
				<li>            
				<i class="icon-user"></i>            
				<a href="'.ISP :: AdminUrl('staff/users-listing/').'">Manage Users</a>            
				</li>                               
				<li>            
				<i class="icon-file-text"></i>            
				<a href="'.ISP :: AdminUrl('reports/add-new-form').'">Add New Reporting Form</a>            
				</li>                               
				<li>            
				<i class="icon-cogs"></i>            
				<a href="'.ISP :: AdminUrl('setting/general-settings/').'">System Configuration</a>            
				</li>                               
				<li>            
				<i class="icon-file-text"></i>            
				<a href="'.ISP :: AdminUrl('reports/listing/').'">See All Reports</a>            
				</li>                            
			  </ul>                            
			</div>               
		  </div> 
		</div>';
				$int_loop_counter=0;
				for($i=0; $i<count($this->objFunction->widgetContentArray); $i++) {
					if($this->objFunction->widgetContentArray[$i]<>'') {
						$int_loop_counter++;
						if($int_loop_counter%2 <> 0){
							$odd_content[] = $this->objFunction->widgetContentArray[$i];
						}
						else {
							$even_content[] = $this->objFunction->widgetContentArray[$i];
						}
					}
				}
		$cont .= '<div class="col-md-12 nopadding clock-in-out-widget" id="widgetsorting">
		<ul class="sortable nopadding col-md-12 piechart_details">';
				$i=1;
					foreach($even_content as $even_widget_value) {
						if($i == 1)
							$cont .= '<li class="'.$i.'_even ui-state-default nopadding widget box col-md-12 col-sm-12 text-left">'.$even_widget_value.'</li>';                    
						$i++;	
					}
		   $cont .= '<div class="overlay ui-sortable-handle"></div><div class="loader ui-sortable-handle" style="display:none;"><img src="'.SITE_URL.'assets/img/ajax-loading-input.gif'.'" /></div>
		  </ul>
		  </div>     
		  <div class="col-md-12 nopadding" id="widgetsorting">
					<div class="col-md-6 col-sm-12 nopadding">
						<ul class="sortable nopadding col-md-12">';
						 $i=1;
							foreach($odd_content as $odd_widget_value) {
								$cont .= '<li class="'.$i.'_odd ui-state-default nopadding col-md-12 col-sm-12 widget box">'.$odd_widget_value.'</li>';
								$i++;                    
							}   
						$cont .= '</ul>
					</div>
					<div class="col-md-6 col-sm-12 text-right rightsidewidget" style="padding-right:0px;">
						<ul class="sortable nopadding col-md-12">';
						$i=1;
							foreach($even_content as $even_widget_value) {
								if($i != 1)
									$cont .= '<li class="'.$i.'_even ui-state-default nopadding widget box col-md-12 col-sm-12 text-left">'.$even_widget_value.'</li>';                    
								$i++;	
							}
						$cont .= '</ul>
					</div>
			</div>                                          
		</div>';
		echo $cont; 
		die;	
  }
  public function getTimeForWidgetAutoRefresh()
  {
	  $strSql ="SELECT config_value FROM ".TBL_CONFIGURATION." WHERE config_key = 'widget_auto_refresh_time'";
	$autorefreshtime = $this->objDatabase->fetchRows($strSql); 
	return $autorefreshtime;
  }
  
  public function admin_ivrlogchart_refresh()
  {
	  echo $this->objFunction->widgetContentArray[$_GET['index']];
	  die;
  }

} // End of Class




/**
  @Purpose: Create the object the class "content"
*/
   $objDashboard = new dashboard($intId, $objMainframe, $objFunctions);

   $arrPageUrl = explode("/", $arrUrl[1]);
   $objDashboard->url = $arrPageUrl[0];

switch($strTask)
{
   case 'dashboard':
        $objDashboard->show_Dashboard();
   break;
   
   case 'admin_dashboard':
        $objDashboard->show_admin_Dashboard();
   break;
   
    case 'admin_dashboard_refresh':
        $objDashboard->admin_dashboard_refresh();
   break;
   
   case 'admin_ivrlogchart_refresh':
        $objDashboard->admin_ivrlogchart_refresh();
   break;
   
}
?>