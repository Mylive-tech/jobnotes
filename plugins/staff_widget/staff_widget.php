<?php
/*
Plugin Name: Staff Widget
Description:  This plug-in will list latest 5 staff records on admin dashboard.
Author: CT
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class staff_widget
{
  private $objDBCon, $objFunction;
  
  function __construct($objFunctions)
  {
    $this->objDBCon = new Database();	
    $this->objFunction = $objFunctions;	
    $this->staff_widget_init();
  }
  
  function staff_widget_init()
  {  
    $titleClass = $this->objFunction->cleanURL("Staff Widget");
    $widgetContent='
                <div class="widget-header dragwidget_'.$titleClass.'">
                  <i class="icon-reorder"></i>&nbsp;&nbsp;<h4>Staff</span></h4>
                    <div class="toolbar no-padding">
						<i class="fa fa-cog ivrlogrefresh" aria-hidden="true" onclick="IvrlogchartRefresh(2, \'2_odd\'); return false;"></i>
						<div class="btn-group">
							<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
						</div>
    				</div>
                 </div>
     <div class="inside_widget widget-content no-padding">             
          <table class="table table-striped table-bordered table-hover">
                    <thead class="cf">
					<tr>
                          <th data-class="expand">First Name</th>
                          <th data-hide="phone" class="visible-desktop">Last Name</th>
                          <th data-class="expand">User ID</th>
                          <th data-class="phone">Phone</th>
                          <th data-hide="expand" class="visible-desktop">Status</th>
                          <!--th data-hide="expand" class="visible-desktop">Availability</th-->
                      </tr>
									</thead>
									<tbody>';
$objStaffArr = $this->objDBCon->dbFetch("SELECT * FROM ".TBL_STAFF." where site_id='".$_SESSION['site_id']."' order by id desc limit 5");	

$this->staff_obj = file_get_contents('http://www.ivrhq.me/applications/10042/api/select.php?account_key=3KoD1T56&table=staff_activity');
$staff_array = array_reverse(json_decode($this->staff_obj, true));	
//print_r($staff_array);
		
    	if(count($objStaffArr)>0): // Check for the resource exists or not
			 $intI=1;
			 
			  foreach($objStaffArr as $objRow)
			    { 				
        
          //$statusLabel = ($objRow->status==1)?'<span class="label label-success">Approved</span>':'<span class="label label-danger">Blocked</span>';
          $availabilityLabel = ($objRow->is_login==1)?'<span class="label label-success">Online</span>':'<span class="label label-danger">Offline</span>';

$widgetContent .='<tr>
              <td align="left"><a href="'.ISP :: AdminUrl('staff/edit-staff/type/staff/id/'.$objRow->id).'">'.$objRow->f_name.'</a></td>
              <td align="left" class="visible-desktop">'.$objRow->l_name.'</td>
              <td align="left">
              <a href="'.ISP :: AdminUrl('staff/edit-staff/type/staff/id/'.$objRow->id).'">
              '.$objRow->id.'
              </a>
              </td>
              <td align="left" class="visible-desktop">'.$objRow->phone.'</td>';
			  foreach($staff_array as $sa)
			  {
				  if($sa['staff_id'] == $objRow->username)
				  {
					  $statusLabel = ucfirst($sa['clock_action_description']);
					  break;
				  }
			  }
			  $widgetContent .='<td align="left" class="visible-desktop">'.$statusLabel.'</td>';
              //$widgetContent .='<td align="left" class="visible-desktop">'.$availabilityLabel.'</td>';
              $widgetContent .='</tr>';
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
												<a href="'.ISP :: AdminUrl('staff/users-listing/').'">View More</a>
											</div>
										</div>
									</div>
								</div>
             </div>';                
    $this->objFunction->widgetContentArray[] = $widgetContent;            
  }
}
?>
