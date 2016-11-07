<?php    
/*
Plugin Name: Completed Reports Widget
Description:  This plug-in will list last 5 Reports Completed on admin dashboard.
Author: CT
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class completejob_widget
{
  private $objDBCon, $objFunction;
  function __construct($objFunctions)
  { 
     $this->objDBCon = new Database();
     $this->objFunction = $objFunctions;
     $this->completejob_widget_init();
  }
  function completejob_widget_init()
  {
    $titleClass = $this->objFunction->cleanURL("Completed Reports Widget");
    $widgetContent='
                <div class="widget-header dragwidget_'.$titleClass.'">
                  <i class="icon-reorder"></i>&nbsp;&nbsp;<h4>Completed Reports</span></h4>
                    <div class="toolbar no-padding">
					<i class="fa fa-cog ivrlogrefresh" aria-hidden="true" onclick="IvrlogchartRefresh(3, \'2_even\'); return false;"></i>
    									<div class="btn-group">
    										<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
    									</div>
    								</div>
                 </div>
                <div class="inside_widget widget-content no-padding">             
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="cf">
                        <tr>
                            <th data-class="expand">Job Location</th>
                            <th data-hide="phone"  class="visible-desktop">Assigned Location</th>
                            <th data-hide="expand">Assigned To</th>
                            <th data-hide="phone"  class="visible-desktop">Completion Date</th>
                        </tr>
						</thead>
						<tbody>';
            $objLocationArr = $this->objDBCon->dbFetch("select * from ".TBL_JOBLOCATION." where site_id='".$_SESSION['site_id']."' and progress=2 order by completion_date desc limit 5");		
			if(count($objLocationArr)>0): // Check for the resource exists or not
			 $intI=1;
			 
			  foreach($objLocationArr as $objRow)
			    { 
            $jobLocation = $this->objFunction->iFind(TBL_SERVICE, 'name', array('id'=>$objRow->location_id));
            $jobAssignedTo = $this->objFunction->iFind(TBL_STAFF, 'CONCAT_WS(" ",f_name, l_name)', array('id'=>$objRow->assigned_to));				
            $widgetContent .='<tr>
                                <td align="left">'.$objRow->job_listing.'</td>
                                <td align="left"  class="visible-desktop">'.$jobLocation.'</td>
                                <td align="left">'.$jobAssignedTo.'</td>
                                <td align="left"  class="visible-desktop">'.strftime("%m/%d/%Y %I:%M %p", strtotime($objRow->completion_date)).'</td>
                            </tr>';
			     }
			 else:
			  $widgetContent .='<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
       
$widgetContent .='</tbody>
                </table>
                <div class="row">
									<div class="table-footer">
										<div class="col-md-12">
											<div class="table-actions">
												<a href="'.ISP :: AdminUrl('property/completed-properties/').'">View More</a>
											</div>
										</div>
									</div>
								</div>
             </div>';
    $this->objFunction->widgetContentArray[] = $widgetContent;                  
  }
}
?>