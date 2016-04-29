<?php
/**
 * @Purpose: Class for HTML view of the Content Module
*/
class STAFF_HTML_CONTENT
{   private $objFunction, $facebook, $objDbCon;   
   
   public function __construct($objfunc, $objDb) //define Constructor
   {     
   $this->objFunction = $objfunc;
   $this->objDbCon = $objDb;	   
   
   }
   /**       *@Purpose: HTML view of Add Content Form to Admin   */
   
protected function admin_Staff_Form($objRs)   {    
   ?>
<div id="content">			
  <div class="container">    
    <div class="crumbs">	
      <ul id="breadcrumbs" class="breadcrumb">		
        <li>			
        <i class="icon-home"></i>			
        <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Configuration</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Staff Management</a>		
        </li>		
        <li class="current">			
          <a href="#" title="">Add New Staff</a>		
        </li>	
      </ul>
    </div>   
    <div class="row">
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">
          <ul class="nav nav-tabs"> 
            <li class="active">
              <a href="#">
                <h4 class="text-left heding_6">
                  <?php echo ($objRs->id=='')?'Add': 'Edit';?> Staff</h4></a>
            </li> 
<?php
if($this->objFunction->checkPermission('View Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/users-listing/');?>">
              <h4 class="text-left heding_6">Staff Listing</h4></a>
            </li>
<?php
}
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/import/');?>">
              <h4 class="text-left heding_6">Import Staff</h4></a>
            </li> 
<?php
 }
 if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/staff_ivr_log_today/');?>">
              <h4 class="text-left heding_6">Today IVR Log</h4></a>
            </li> 
<?php
 }
?>
          </ul>  
          <div class="tab-content formTableBg widget box box-vas">
            <div class="widget-content formTableBg  widget-content-vls">
              <form name="frmContent" class="form_holder" method="post" onsubmit="return validateFrm(this);" enctype="multipart/form-data">  
                <div class="form-group">    
                  <label for="exampleInputEmail1">Username/IVR Staff ID
                  </label>    
                  <input type="text" name="md_username" class="form-control" value="<?php echo $objRs->username; ?>"  placeholder="Enter Username of staff">   
                </div> 
                
                <div class="form-group">    
                  <label for="exampleInputPassword1">First Name
                  </label>    
                  <input type="text" name="md_f_name" id="md_f_name"  value="<?php echo $objRs->f_name; ?>"   class="form-control"  placeholder="Enter first name of staff">   
                </div>  
                <div class="form-group">    
                  <label for="exampleInputPassword1">Last Name
                  </label>    
                  <input type="text" name="md_l_name" id="md_l_name"  value="<?php echo $objRs->l_name; ?>"   class="form-control"  placeholder="Enter last name of staff">   
                </div>  
                <div class="form-group">    
                  <label for="exampleInputPassword1">Enter Email
                  </label>    
                  <input type="email" name="md_email" id="md_email"  value="<?php echo $objRs->email; ?>"   class="form-control"  placeholder="Enter email address of staff">   
                </div>     
                <div class="form-group">    
                  <label for="exampleInputPassword1">Phone
                  </label>    
                  <input type="text" required name="md_phone" id="md_phone"  value="<?php echo $objRs->phone; ?>"   class="form-control"  placeholder="Enter phone number  of staff">   
                </div>     
                <div class="form-group">    
                  <label for="exampleInputPassword1">Enter Password
                  </label>    
                  <input type="password" name="<?php if($objRs->id >0) echo "password"; else echo "md_password";?>" id="<?php if($objRs->id >0)  echo "password"; else echo "md_password";?>"  value="<?php echo $objRs->password; ?>"  class="form-control"  placeholder="Enter password for staff login">   
                </div>  

<div class="form-group">
<label for="exampleInputPassword1">Allow Auto Login in APP</label>
<select name="md_auto_login" class="form-control">
<option value="1" <?php if($objRs->auto_login==1) echo 'selected';?>>Yes</option>
<option value="0" <?php if($objRs->auto_login==0) echo 'selected';?>>No</option>
</select> 
</div> 

                <div class="form-group">    
                  <label for="exampleInputPassword1">Staff Role
                  </label>    
                  <select name="md_user_type" class="form-control">
<?php $this->objFunction->dropdown_StaffTypes($objRs->user_type);?>
</select>  
                </div>
<div class="form-group">
<label for="">Job Title</label>
<select name="md_job_title" class="form-control">
<option value="">--Please Select--</option>
<?php
foreach($this->objFunction->staff_job_title as $key=>$value) {
?>
<option value="<?php echo $key;?>" <?php if($objRs->job_title==$key) echo 'selected';?>><?php echo $value;?></option>
<?php
}
?>
</select> 
</div> 

                
                <input type="hidden" name="task" value="savestaff" />    
                <input type="hidden" name="id" value="<?php echo $objRs->id;?>" />     
                <input type="hidden" name="db_site_id" value="<?php echo $_SESSION['site_id'];?>">  
                <button type="submit" name="save" class="btn btn-default">Save Staff
                </button>  
                <div class="clearfix">
                </div>  	
              </form>  
            </div>   
            <div class="clearfix">
            </div>   	 			  			
            <!-- /.container --> 

             <div class="clearfix"></div>
    <?php 
    $array_log = $this->objFunction->getStaffIvrLog($objRs->username);

    if (intval($objRs->username) > 0 && count($array_log) >0 ) { ?>
        <div class="col-md-12">
            <h4 class="text-left heding_6">IVR Time Log</h4> 
            <div class="col-md-12 no-padding">
            <div class="col-md-2" style="min-height: auto;"><b>S.N</b></div><div class="col-md-4" style="min-height: auto;"><b>Date</b></div><div class="col-md-3" style="min-height: auto;"><b>Time</b></div><div style="min-height: auto;" class="col-md-3"><b>Status</b></div>
            <div class="col-md-12 no-padding" style="height: 1px; background: #444; margin: 10px 0px;"></div>
            <div class="clearfix"></div> 
            <?php                
                $rowCount=0;
                foreach ($array_log as $log) {
                    $rowCount++;
                ?>
                <div class="col-md-2" style="min-height: auto;"><?php echo $rowCount;?>.</div>
                <div class="col-md-4" style="min-height: auto;"><?php echo strftime('%b, %d %Y', $log['time_stamp']);?></div>
                <div class="col-md-3" style="min-height: auto;"><?php echo $log['time_12_hour_clock'];?></div>
                <div class="col-md-3" style="min-height: auto;"><?php echo ucfirst($log['clock_action_description']);?></div>
                <div class="col-md-12 no-padding" style="height: 1px; background: #ccc; margin: 10px 0px;"></div>
                <div class="clearfix"></div> 
                <?php
                }
            ?>
            </div>
        </div>
    <?php } ?>
          </div> 
        </div>    
      </div>
    </div>  
  </div>
</div>   
</div>
</div>  
<?php 
   }  // End of Function 
  
 protected function import_staff()
   {    
   ?>     
<div id="content">			
  <div class="container">        
    <div class="crumbs">	
      <ul id="breadcrumbs" class="breadcrumb">		
        <li>			
        <i class="icon-home"></i>			
        <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Configuration</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Staff Management</a>		
        </li>		
        <li class="current">			
          <a href="#" title="">Import Staff</a>		
        </li>	
      </ul>
    </div>   		
    <div class="row">		 		 		
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">
          <ul class="nav nav-tabs">
<?php 
if($this->objFunction->checkPermission('Add & Edit Staff User', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/add-staff/');?>">
              <h4 class="text-left heding_6">
                <?php echo ($objRs->id=='')?'Add': 'Edit';?> Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('View Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/users-listing/');?>">
              <h4 class="text-left heding_6">Staff Listing</h4></a>
            </li>
<?php
}
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li class="active">
              <a href="#">
                <h4 class="text-left heding_6">Import Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/staff_ivr_log_today/');?>">
              <h4 class="text-left heding_6">Today IVR Log</h4></a>
            </li>  
<?php
 }
?>
          </ul>		
          <div class="col-img tab-content formTableBg">				   
            <img src="<?php echo SITE_IMAGEURL; ?>importdemo.png" />				   
            <br>				   
            <a href="<?php echo SITE_MEDIA;?>sample_import.csv" target="_blank">Download Sample CSV File</a>				    
            <br>				   
            <form name="frmContent" method="post" onsubmit="return validateFrm(this);" enctype="multipart/form-data">
              <div class="form-group">    
                <label for="exampleInputEmail1">Please import csv file in a format as shown above demo in image
                </label>      
              </div>     
              <div class="form-group">    
                <label for="exampleInputEmail1">Select File(in CSV Format only)
                </label>    
                <input type="file" name="md_file" class="form-control"   placeholder="choose file">  
              </div>       
              <input type="hidden" name="task" value="importstaff" />              
              <button type="submit" name="save" class="btn btn-default sumit_bottom">Import Staff
              </button>
            </form>				   
          </div>				   
        </div>         
      </div>   		 
    </div>
    <div class="clearfix">
    </div> 	 			  			
  </div>			
  <!-- /.container -->		
</div>
<?php 
   }  // End of Function 
  
   
   
   //function to edit user form
protected function users_Edit_Form($objRs)
{    
?>
<div id="content">
  <div class="container <?php if(!strstr($_SERVER['REQUEST_URI'],ADMIN_FOLDER)) echo 'staff_section';?>">
<?php
if(strstr($_SERVER['REQUEST_URI'],ADMIN_FOLDER))
{ 
    ?>    
    <div class="crumbs">    
      <ul id="breadcrumbs" class="breadcrumb">    
        <li>    
        <i class="icon-home"></i>    
        <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>    
        </li>    
        <li>    
        <i class="current"></i>    
        <a href="#">My Profile</a>    
        </li>         
      </ul>    
    </div>
<?php 
}
    ?>        
    <h4 class="text-left heding_6">Edit Profile</h4>                     
    <form name="frmContent" class="form_holder" method="post" onsubmit="return validateFrm(this);" enctype="multipart/form-data">    
      <div class="form-group">    
        <label for="exampleInputEmail1">Username
        </label>    
        <input type="text" name="md_username" class="form-control" readonly value="<?php echo $objRs->username; ?>"  placeholder="Enter Username of staff">     
      </div>    
      <div class="form-group">    
        <label for="exampleInputPassword1">First Name
        </label>    
        <input type="text" name="md_f_name" id="md_f_name"  value="<?php echo $objRs->f_name; ?>"   class="form-control"  placeholder="Enter first name of staff">     
      </div>    
      <div class="form-group">    
        <label for="exampleInputPassword1">Last Name
        </label>    
        <input type="text" name="md_l_name" id="md_l_name"  value="<?php echo $objRs->l_name; ?>"   class="form-control"  placeholder="Enter last name of staff">     
      </div>    
      <div class="form-group">    
        <label for="exampleInputPassword1">Enter Email
        </label>    
        <input type="email" name="md_email" id="md_email"  value="<?php echo $objRs->email; ?>"   class="form-control"  placeholder="Enter email address of staff">     
      </div>         
      <div class="form-group">    
        <label for="exampleInputPassword1">Phone
        </label>    
        <input type="number" name="md_phone" id="md_phone"  value="<?php echo $objRs->phone; ?>"   class="form-control"  placeholder="Enter phone number  of staff">     
      </div>    
      
      <input type="hidden" name="task" value="savestaff" />      
      <input type="hidden" name="id" value="<?php echo $objRs->id;?>" />          
      <button type="submit" name="save" class="btn btn-default">Update Profile
      </button>    
      <div class="clearfix"></div>    
    </form>
    <div class="clearfix"></div>
    <?php 
    $array_log = $this->objFunction->getStaffIvrLog($objRs->username);

    if (intval($objRs->username) > 0 && count($array_log) >0 ) { ?>
        <div class="col-md-12">
            <h4 class="text-left heding_6">IVR Time Log</h4> 
            <div class="col-md-12 no-padding">
            <div class="col-md-2" style="min-height: auto;"><b>S.N</b></div><div class="col-md-4" style="min-height: auto;"><b>Date</b></div><div class="col-md-3" style="min-height: auto;"><b>Time</b></div><div style="min-height: auto;" class="col-md-3"><b>Status</b></div>
            <div class="col-md-12 no-padding" style="height: 1px; background: #444; margin: 10px 0px;"></div>
            <div class="clearfix"></div> 
            <?php                
                $rowCount=0;
                foreach ($array_log as $log) {
                    $rowCount++;
                ?>
                <div class="col-md-2" style="min-height: auto;"><?php echo $rowCount;?>.</div>
                <div class="col-md-4" style="min-height: auto;"><?php echo strftime('%b, %d %Y', $log['time_stamp']);?></div>
                <div class="col-md-3" style="min-height: auto;"><?php echo $log['time_12_hour_clock'];?></div>
                <div class="col-md-3" style="min-height: auto;"><?php echo ucfirst($log['clock_action_description']);?></div>
                <div class="col-md-12 no-padding" style="height: 1px; background: #ccc; margin: 10px 0px;"></div>
                <div class="clearfix"></div> 
                <?php
                }
            ?>
            </div>
        </div>
    <?php } ?>
  </div>
</div>     
<?php 
}  // End of Function 
   
/**
     *@Purpose: Show All registered users
*/  
protected function admin_staffListing($objRs, $type='')
   {
   ?> 
<script type="text/javascript">
$(document).ready(function() {
    $('.datatable').dataTable( {} );
} ); 
</script>         
<div id="content">			
  <div class="container">               
    <div class="crumbs">	
      <ul id="breadcrumbs" class="breadcrumb">		
        <li>			
        <i class="icon-home"></i>			
        <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Configuration</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Staff Management</a>		
        </li>		
        <li class="current">			
          <a href="#" title="">Staff Listing</a>		
        </li>	
      </ul>
    </div>   				
    <!--=== Normal ===--> 				
    <div class="row">		
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">
          <ul class="nav nav-tabs">
<?php 
if($this->objFunction->checkPermission('Add & Edit Staff User', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/add-staff/');?>">
              <h4 class="text-left heding_6">
                <?php echo ($objRs->id=='')?'Add': 'Edit';?> Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('View Staff', 'staff'))
{
            ?> 
            <li class="active">
              <a href="#">
                <h4 class="text-left heding_6">Staff Listing</h4></a>
            </li>
<?php
}
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/import/');?>">
              <h4 class="text-left heding_6">Import Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/staff_ivr_log_today/');?>">
              <h4 class="text-left heding_6">Today IVR Log</h4></a>
            </li>  
<?php
 }
?>
          </ul>						
          <div class="widget box box-vas">							 							
            <div class="widget-content widget-content-vls">                
              <form method="post" name="frmListing">                
                <div class="col-md-12 text-right" style="padding-bottom:10px">                 
                  <input type="submit" class="btn btn-success btn-ms" name="btn_Publish" value="Activate" onclick="document.frmListing.status.value='1';" />                 
                  <input type="submit" class="btn btn-warning btn-ms" name="btn_UnPublish" value="Deactivate" onclick="document.frmListing.status.value='0';" />                                                                         
                  <input type="submit" class="btn btn-danger btn-ms" name="btn_delete" value="Delete" onclick="document.frmListing.status.value='-1';" />               
                  										
                    <a href="javascript: void(0);" onclick="exportTableToCSV.apply(this, [$('#dataTables-example'), 'export-users.csv']);" class="btn btn-info">Export</a>								
                                  
                </div>                  
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                      <th align="center" data-hide="phone">ID
                      </th>											    
                      <th data-class="expand">Username
                      </th>												  
                      <th data-hide="phone">Name
                      </th>                          
                                                
                      <th data-hide="phone">Email
                      </th>                          
                      <th data-hide="phone">Phone
                      </th>
                      <th data-hide="phone">Job Title</th>                         
                      <th data-hide="phone">Registration Date
                      </th>                          
                      <th>Role
                      </th>                          
                      <th data-hide="phone" class="hideexport">Status
                      </th>        
                      <!--<th data-hide="phone" class="hideexport">Availability
                      </th>-->                   
                      <th data-hide="phone" class="hideexport">Edit
                      </th>                          
                      <th data-hide="phone" class="hideexport divchecker">
                        <input class="uniform" type="checkbox" name="del[]" onclick="selectDeselect('delete[]');"/>
                      </th>											
                    </tr>									
                  </thead>									
                  <tbody>                                                                                                    					                         
<?php
			
			if($objRs): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {
			    $strStatus = ($objRow->status)?'0':'1';
          $availabilityLabel = ($objRow->is_login==1)?'<span class="label label-success">Online</span>':'<span class="label label-danger">Offline</span>';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
                    			?>           
                    <tr> 
                      <td>
                        <?php echo $objRow->id;?></td>            
                      <td align="center">
                        <?php echo $objRow->username;?></td>             
                      <td align="center">
                        <?php echo ucfirst($objRow->f_name);?><br><?php echo ucfirst($objRow->l_name);?></td>              
                                   
                      <td align="center">
                        <?php echo $objRow->email;?></td>              
                      <td align="center">
                      <a href="tel:<?php echo $objRow->phone;?>"><?php echo $objRow->phone;?></a></td>
                      <td align="center"><?php echo $this->objFunction->staff_job_title[$objRow->job_title];?></td>          
                      <td align="center">
                        <?php echo $objRow->reg_date;?></td>              
                      <td align="center">
                        <?php echo $objRow->userRole; //$this->objFunction->iFind(TBL_STAFFTYPE, "label", array("id"=>$objRow->user_type));?>
                      </td>                         
                      <td align="center" class="hideexport">            
                        <a href="<?php echo ISP :: AdminUrl('staff/modifystaff/delete/'.$objRow->id.'/status/'.$strStatus);?>" alt="Click to <?php echo ($objRow->status==1)?'Deactivate':'Activate';?>" title="Click to <?php echo ($objRow->status==1)?'Deactivate':'Activate';?>">                   
                          <i class="fa fa-toggle-<?php if($objRow->status==1) echo 'on'; else echo 'off';?>"></i>  </a> 
                      </td>            
                      <!--<td align="center">
                        <?php echo $availabilityLabel;?></td> --> 
                      <td align="center" class="hideexport">            
                        <a href="<?php echo ISP :: AdminUrl('staff/edit-staff/type/'.$type.'/id/'.$objRow->id);?>">              
                          <i class="fa fa-pencil-square-o"></i>            </a>              </td>            
                      <td align="center" class="hideexport divchecker">
                        <input class="uniform" type="checkbox" name="delete[]" value="<?php echo $objRow->id;?>"  /></td>          
                    </tr>          
<?php
			   }
                    			   ?>           
                    </tr>          
<?php
			 else:
			       echo '<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
                    			 ?>          
                  </tbody>        
                </table>         
                <input type="hidden" name="status" value="1" />        
                <input type="hidden" name="task" value="modifystaff" />                  	  
              </form>			
            </div>    
          </div>
        </div>
      </div>
    </div>				
    <!-- /Normal -->			
  </div>			
  <!-- /.container -->		
</div>    
<?php 
   }
   
protected function admin_staff_ivr_log($objRs) {
?>
<script type="text/javascript">
$(document).ready(function() {
    $('.datatable').dataTable( {} );
} ); 
</script>         
<div id="content">			
  <div class="container">               
    <div class="crumbs">	
      <ul id="breadcrumbs" class="breadcrumb">		
        <li>			
        <i class="icon-home"></i>			
        <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Configuration</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Staff Management</a>		
        </li>		
        <li class="current">			
          <a href="<?php echo ISP::AdminUrl('staff/staff_ivr_log_today/');?>" title="">Today IVR Log</a>		
        </li>	
      </ul>
    </div>   				
    <!--=== Normal ===--> 				
    <div class="row">		
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">
          <ul class="nav nav-tabs">
<?php 
if($this->objFunction->checkPermission('Add & Edit Staff User', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/add-staff/');?>">
              <h4 class="text-left heding_6">
                <?php echo ($objRs->id=='')?'Add': 'Edit';?> Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('View Staff', 'staff'))
{
            ?> 
            <li>
              <a href="<?php echo ISP :: AdminUrl('staff/users-listing/');?>">
                <h4 class="text-left heding_6">Staff Listing</h4></a>
            </li>
<?php
}
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/import/');?>">
              <h4 class="text-left heding_6">Import Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
             <li>			
          		<a href="<?php echo ISP :: AdminUrl('staff/staff_ivr_log_today/');?>"><h4 class="text-left heding_6">Today IVR Log</h4></a>		
        	</li>	
<?php
 }
?>
          </ul>						
          <div class="widget box box-vas">							 							
            <div class="widget-content widget-content-vls">                
              <form method="post" name="frmListing">             
                   
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                        <th data-class="expand">Username/IVR Staff ID</th>												  
                        <th data-hide="phone">First Name</th>                          
                        <th data-hide="phone">Last Name</th>
                        <th data-hide="phone">Last Action</th>
                        <th>View Full Log</th>
                        <th data-hide="phone">Export to CSV</th>
                    </tr>									
                  </thead>									
                  <tbody>                                                                                                    					                         
<?php
			
			if($objRs): // Check for the resource exists or not
                $intI=1;
                $staff_log = $this->objFunction->getAllStaffLastLog();
            //print_r($staff_log);    
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';

                //$array_log = $this->objFunction->getStaffIvrLog($objRow->username, 1);
                $lastUser = '';
                if ($staff_log[$objRow->username] <> '' ) { 
                $rowCount=0;
                    //foreach ($array_log as $log) {
                        //if($lastUser <> $objRow->username) {
                            $lastUser = $objRow->username;
                            $rowCount++;
                        ?>
                                  
                            <tr> 
                                <td align="center"><?php echo $objRow->username;?></td>             
                                <td align="center"><?php echo $objRow->f_name;?></td>              
                                <td align="center"><?php echo $objRow->l_name;?></td>              
                                <td align="center">
                                <?php
                                echo $staff_log[$objRow->username];
                                //echo strftime('%b, %d %Y', $log['time_stamp']);?> <?php //echo ucfirst($log['clock_action_description']);?>
                                </td>                             
                                <td align="center"><a href="<?php echo ISP :: AdminUrl('index.php?dir=staff&task=edit-staff&id='.$objRow->id);?>">View</a></td>
                                <td align="center"><a href="<?php echo ISP :: AdminUrl('index.php?dir=staff&task=import_ivr_log&user='.$objRow->username);?>">Export</a></td>
                            </tr> 
<?php               
                        //}
                    //}
                }                     
			}
?>           
                    </tr>          
<?php
			 else:
			       echo '<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
?>          
                  </tbody>        
                </table>       	  
              </form>			
            </div>    
          </div>
        </div>
      </div>
    </div>				
    <!-- /Normal -->			
  </div>			
  <!-- /.container -->		
</div>  
<?php    
}      
   
protected function admin_staff_ivr_log_today($objRs) {
?>
<script type="text/javascript">
$(document).ready(function() {
    $('.datatable').dataTable( {} );
} ); 
</script>         
<div id="content">			
  <div class="container">               
    <div class="crumbs">	
      <ul id="breadcrumbs" class="breadcrumb">		
        <li>			
        <i class="icon-home"></i>			
        <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Configuration</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Staff Management</a>		
        </li>		
        <li>
        <i class="current"></i>				
          <a href="<?php echo ISP::AdminUrl('staff/staff_ivr_log/');?>" title="">Staff IVR Log</a>		
        </li>	
      </ul>
    </div>   				
    <!--=== Normal ===--> 				
    <div class="row">		
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">
          <ul class="nav nav-tabs">
<?php 
if($this->objFunction->checkPermission('Add & Edit Staff User', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/add-staff/');?>">
              <h4 class="text-left heding_6">
                <?php echo ($objRs->id=='')?'Add': 'Edit';?> Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('View Staff', 'staff'))
{
            ?> 
            <li>
              <a href="<?php echo ISP :: AdminUrl('staff/users-listing/');?>">
                <h4 class="text-left heding_6">Staff Listing</h4></a>
            </li>
<?php
}
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li>
            <a href="<?php echo ISP :: AdminUrl('staff/import/');?>">
              <h4 class="text-left heding_6">Import Staff</h4></a>
            </li> 
<?php
 }
if($this->objFunction->checkPermission('Import Staff', 'staff'))
{
            ?> 
            <li class="active">
            <a href="#">
              <h4 class="text-left heding_6">Today IVR Log</h4></a>
            </li> 
<?php
 }
?>
          </ul>						
          <div class="widget box box-vas">							 							
            <div class="widget-content widget-content-vls">                
              <form method="post" name="frmListing">             
                   
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                        <th data-class="expand">Username/IVR Staff ID</th>												  
                        <th data-hide="phone">First Name</th>                          
                        <th data-hide="phone">Last Name</th>
                        <th data-hide="phone">Hours worked today</th>
                        <th data-hide="phone">Start</th>
                        <th data-hide="phone">Stop</th>
                        <th>Status</th>
                        <th>View Full Log</th>
                        <th data-hide="phone">Export to CSV</th>
                    </tr>									
                  </thead>									
                  <tbody>                                                                                                    					                         
<?php
			
			if($objRs): // Check for the resource exists or not
                $intI=1;
                //$staff_log = $this->objFunction->getAllStaffLastLog();
            //print_r($staff_log);    
            $this->objFunction->storeIVRLog();
            
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
				$array_log = array();
                //$array_log = $this->objFunction->getStaffIvrLog($objRow->username);
                //$array_log = $this->objFunction->iFindAll('tbl_ivr_log', array('staff_id' => $objRow->username, 'date'=>date('Y-m-d')), 'order by `date` desc limit 2');
                $logRs = $this->objDbCon->dbQuery("SELECT * from tbl_ivr_log where staff_id = '".$objRow->username."' and `date`='".date('Y-m-d')."' order by `date` desc limit 2");
				while ($logObj = $logRs->fetch_object()) {
					$array_log[] = $logObj;
				}
				$lastUser = '';
                //if ($staff_log[$objRow->username] <> '' ) { 
                if (count($array_log)>0) {
                	$rowCount=0;
                    //foreach ($array_log as $log) {
                        //if($lastUser <> $objRow->username) {
                            $lastUser = $objRow->username;
                            $rowCount++;
                        ?>
                                  
                            <tr> 
                                <td align="center"><?php  echo $objRow->username;?></td>             
                                <td align="center"><?php echo $objRow->f_name;?></td>              
                                <td align="center"><?php echo $objRow->l_name;?></td>              
                                <td align="center">
                                <?php
                                if ($array_log[0]->date == date('Y-m-d')) {
                                	   if ($array_log[0]->clock_action_description == 'clock out') {
                                	       $total_time = $array_log[0]->time_stamp - $array_log[1]->time_stamp;
                                	   }
                                	   else {
                                	       $total_time = time() - $array_log[0]->time_stamp;
                                	   }
                                	   	$hours      = floor($total_time /3600);     
										$minutes    = intval(($total_time/60) % 60);
									    echo $hours.':'.$minutes;
                                }
                                
                                //echo $staff_log[$objRow->username];
                                //echo strftime('%b, %d %Y', $log['time_stamp']);?> <?php //echo ucfirst($log['clock_action_description']);
                                ?>
                                </td> 
                                <td><?php 
                                $clock_status='<span class="label label-danger">Offline</span>';
                                if ($array_log[0]->clock_action_description == 'clock out') {
                                	echo $array_log[1]->time_24_hour_clock;
                                }
                                else {
                                    echo $array_log[0]->time_24_hour_clock;
                                }
                                ?></td>
                                <td><?php 
                                 if ($array_log[0]->clock_action_description == 'clock out') {
                                	echo $array_log[0]->time_24_hour_clock;
                                 }
                                 else {
                                 $clock_status='<span class="label label-success">Online</span>';
                                     echo "At Work";
                                 }
                                ?></td> 
                                <td><?php echo $clock_status;?></td>                           
                                <td align="center"><a href="<?php echo ISP :: AdminUrl('index.php?dir=staff&task=edit-staff&id='.$objRow->id);?>">View</a></td>
                                <td align="center"><a href="<?php echo ISP :: AdminUrl('index.php?dir=staff&task=import_ivr_log&user='.$objRow->username);?>">Export</a></td>
                            </tr> 
<?php               
                        //}
                    //}
                }                     
			}
?>           
                    </tr>          
<?php
			 else:
			       echo '<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
?>          
                  </tbody>        
                </table>       	  
              </form>			
            </div>    
          </div>
        </div>
      </div>
    </div>				
    <!-- /Normal -->			
  </div>			
  <!-- /.container -->		
</div>  
<?php    
}   
  
   
   //html form for users sign in
protected function users_login()
   { ?>   
<div class="col-md-12">   
  <div class="col-md-8">   
    <div class="row">   &nbsp;    
    </div>  
    <div class="row">  
      <form class="form-inline" action="<?php echo ISP :: FrontendUrl('users/checklogin'); ?>" method="post" name="contact_form">  
        <div class="form-group">    
          <label for="exampleInputEmail1">
          </label>    
          <input type="text" class="form-control" name="md_username" id="exampleInputEmail1" placeholder="Enter username">  
        </div>  
        <div class="form-group">    
          <label for="exampleInputPassword1">Password
          </label>    
          <input type="password" class="form-control" name="md_password" id="exampleInputPassword1" placeholder="Password">  
        </div>     
        <div class="row">   &nbsp;    
        </div>  
        <button type="submit" class="btn btn-default">Submit
        </button>
      </form>
    </div> 
    <div class="row">   &nbsp;    
    </div>
  </div>
</div>	                                    
<?php 
   }//end function
   
  
   
   //html form for users sign in
   protected function users_signup()
   { ?>    
<style type="text/css">    .wrappersign{background-color: #EFE1E1;}     .wrapper_box{width: 100%;margin: auto;}     .form_box{ padding-top: 17px;background-color: #FFF;}     .clerfix{clear: both;}    .drop_down {width: 48%; position: absolute; top: 25px; right: 15px; background-color: #1998DE !important; font-size: 1.3em !important; font-weight: bold;}     .email{margin-bottom: 22px !important;}    .m_margin{margin-top: 23px !important;}    .pass{color: #388333;}    .placeholder_color{background-color: #F9F7F7; color:#F5EBEB!important; }    .logo_holder{width: 100%;}    .logo{float: right; width: 200px;margin-top: 23px;}    .logo img{width: 100%;}    .top_text{font-size: 1.2em; color: #5B5B60; opacity: 0.8; font-family: arial;margin-bottom: 20px;margin-top: 20px;}   .placeholder_color::-webkit-input-placeholder {    color: #1998DE; }.placeholder_color:-moz-placeholder { /* Firefox 18- */    color: #1998DE;   }.placeholder_color::-moz-placeholder {  /* Firefox 19+ */    color: #1998DE;   }.placeholder_color:-ms-input-placeholder {      color: #1998DE;   }     
</style> 
<div class="wrappersign">   
  <div class="wrapper_box">        
    <div class="logo_holder">          
      <div class="logo">            
        <img src="<?php echo SITE_IMAGEURL;?>snowlogo.png">          
        <div class="clearfix">
        </div>          
      </div>        
      <div class="clearfix">
      </div>        
    </div>        
    <div class="top_text">          Get Started with your Snow Management System         
      <div class="clearfix">
      </div>        
    </div>
    <div class="form_box">          
      <form method="post" action="<?php echo ISP :: FrontendUrl('users/save-user'); ?>" onsubmit="return validateFrm(this);">            
        <div class="email">              
          <div class="form-group col-md-6 col-xs-6 ">                
            <label for="exampleInputEmail1">EMAIL-ADDRESS
            </label>                
            <input type="email" class="form-control placeholder_color" id="md_email" name="md_email" placeholder="admin@company1.com">               
            <div class="clearfix">
            </div>                 
          </div>                
          <div class="col-md-6 m_margin col-xs-6"> well's send you an Email to active your account,so please triple-check that you've typed it correctly.               
            <div class="clearfix">
            </div>              
          </div>            
          <div class="clearfix">
          </div>            
        </div>				 
        <div class="email">                    
          <div class="form-group col-md-6 col-xs-6">                      
            <label for="exampleInputEmail1">USERNAME
            </label>                      
            <input type="text" class="form-control placeholder_color" id="md_username"  name="md_username" placeholder="company1_admin">                     
            <div class="clearfix">
            </div>                       
          </div>                     
          <div class="col-md-6 m_margin col-xs-6">                    Your Username Should be a minimum of four characters and can only include lowercase letters and numbers.                     
            <div class="clearfix">
            </div>                    
          </div>              
          <div class="clearfix">
          </div>                
        </div>              
        <div class="email">                  
          <div class="form-group col-md-6 col-xs-6">                    
            <label for="exampleInputPassword1">PASSWORD
            </label>                    
            <input type="password" class="form-control placeholder_color" name="md_password" id="md_password" placeholder="********">                   
            <div class="clearfix">
            </div>                     
          </div>                   
          <div class="col-md-6 m_margin col-xs-6">                 
            <span class="pass"> Great passwords
            </span> use uppercase and lowercase characters,numbers and symbols like!#$%*.                   
            <div class="clearfix">
            </div>                  
          </div>              
          <div class="clearfix">
          </div>                
        </div>              
        <div class="email">              
          <div class="form-group col-md-6  col-xs-6">                
            <label for="exampleInputEmail1">SNOWBOOK ADDRESS
            </label>                
            <input type="text" name="md_snow_address" class="form-control placeholder_color" id="exampleInputEmail1" placeholder="company1">                
            <select name="md_snow_address_ap" class="form-control drop_down placeholder_color ">                      
              <option value=".snowbook.com">.snowbook.com
              </option>                     	
              <option value=".snowbook.com">.snowbook.com
              </option>                      	
              <option value=".snowbook.com">.snowbook.com
              </option>                    	 
              <option value=".snowbook.com">.snowbook.com
              </option>                      	
              <option value=".snowbook.com">.snowbook.com
              </option>                
            </select>               
            <div class="clearfix">
            </div>                 
          </div>                            
          <div class="col-md-6 m_margin col-xs-6">                choose your portal address.This will have your entire system installed& used for further access.                
            <div class="clearfix">
            </div>              
          </div>              
          <div class="clearfix">
          </div>                
        </div>                             
        <div class="email">                  
          <div class="form-group col-md-6 col-xs-6">                                       
            <input type="submit" class="btn btn-primary"  name="submit" value="Sign Up" />                   
            <div class="clearfix">
            </div>                                                     
          </div>            
      </form>          
      <div class="clearfix">
      </div>             
    </div>      
    <div class="clearfix">
    </div>      
  </div>  	                 
</div> 
<!-- /.form-box -->         
<div class="clearfix">
</div>    
</div>             
<?php 
   }//end function
   
   protected function recover_password()
   { ?>    
<div class="wrap">        
  <!-- strat-contact-form -->	
  <!-- end-account -->        
  <div class="col-md-12">                         
    <div class="span">            	
      <a href="<?php echo $fb_loginUrl;?>" target="_top">                	
        <img src="<?php echo SITE_IMAGEURL;?>fbimg.png" alt="Facebook Login" title="Facebook Login"/>                    <i>Sign In with Facebook</i>            
        <div class="clearfix">
        </div>            	</a>              
    </div>	             
    <div class="span1"> 
      <a href="<?php echo sefToAbs('index.php?dir=users&task=twitter_signup&ajax=1');?>" target="_top">
        <img src="<?php echo SITE_IMAGEURL;?>twimg.png" alt="Twitter Login" title="Twitter Login"/><i>Sign In with Twitter</i>
        <div class="clearfix">
        </div></a>
    </div>            
    <div class="span2">
      <a href="<?php echo $gplusUrl;?>" target="_top">
        <img src="<?php echo SITE_IMAGEURL;?>gplusimg.png" alt="Google+ Login" title="Google+ Login"/><i>Sign In with Google+</i>
        <div class="clearfix">
        </div></a>
    </div>        
  </div>        
  <div class="col-md-12 text-center">          
    <h1 class="heading5">
      <br/>          	
      <a class="heading5" href="<?php echo ISP :: FrontendUrl('users/signup/ajax/1'); ?>">login with email or <u>just try a demo</u></a>          
      <br/></h1>        
    <div class="clearfix">
    </div>        
  </div>        
  <div class="col-md-6">                 
    <form class="contact_form1" action="<?php echo ISP :: FrontendUrl('users/reset_password/ajax/1'); ?>" method="post" name="contact_form">                
      <!--<h4 class="heading3">Enter your email address</h4>-->                
      <ul>                    
        <li>                        
        <input type="text" class="textbox1" name="email" id="exampleInputEmail1" placeholder="Enter Your Registered Email" required />                                                  
        <p>
          <img src="<?php echo SITE_IMAGEURL;?>contactimg.png" alt="">
        </p>                    
        </li>                                     
      </ul>                 
      <div class="clearfix">
      </div>                
      <div class="col-md-12 nopadding">                	
        <div class="col-md-8 left-col">                    	forgotten password                     
          <div class="clearfix">
          </div>                    
        </div>                    
        <div class="col-md-4 btn-send">                       
          <input type="submit"  name="submit" value="Send Password" />                    
          <div class="clearfix">
          </div>                    
        </div>                   
        <!--<div class="clearfix"></div> 
                            <h5 class="heading4">Need help? Please contact Vibeoo Support.</h5>  
                        </div>--> 					                 	             
    </form>                        
  </div>        
  <div class="clearfix">
  </div>	         
</div>        
<div class="clearfix">
</div>        
</div> 
<!-- /.form-box -->        
<div class="clearfix">
</div>                       
<?php 
   }//end function
   
   protected function admin_userRoles()
   {
     $objSetRoles = $this->objFunction->getAllStaffTypes();
     $objSetPermissions = $this->objFunction->iFindAll(TBL_ROLEPERMISSION, '', ' order by module');
?> 
<link href="<?php echo SITE_CSSURL ?>magnific_popup.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SITE_JSURL ?>magnific_popup.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.popup-with-form').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#name',
		// When elemened is focused, some mobile browsers in some cases zoom in
		// It looks not nice, so we disable it:
		callbacks: {
			beforeOpen: function() {
				if($(window).width() < 700) {
					this.st.focus = false;
				} else {
					this.st.focus = '#name';
				}
			}
		}
	});
});
function loadUsers(roleId)
{
  $(".roleusers").hide();
  $("#tmp_"+roleId).show();
}
</script>  
<form method="post" id="view-as-form" class="mfp-hide white-popup-block" action="<?php echo SITE_ADMINURL;?>">
  <div class="col-md-12"> 
    <div class="col-md-3">
    </div> 
    <div class="col-md-6 popupmodal">
    <h2>Preview System As</h2>	
      <div class="col-md-12">   
        <div class="col-md-6">				
          <label for="name">Choose Role
          </label>				
          <select name="user_role_id" class="form-control" onchange="loadUsers(this.value);">            
<?php
            $this->objFunction->dropdown_StaffTypes();
?>       
          </select>	  
        </div>         
        <div class="col-md-6">				
          <label for="email">Choose User
          </label>        
          <select class="roleusers form-control" id="tmp_" style="display:block;">        
          </select>        
<?php
         foreach($objSetRoles as $roleId=>$roleName)
          {
                  ?>				
          <select class="roleusers form-control" id="tmp_<?php echo $roleId;?>" onchange="$('#user_id').val(this.value);" class="form-control">         
            <?php echo $this->objFunction->dropdown_StaffUsers($roleId);?>        
          </select>       
<?php
          }
                  ?>     
          <input type="hidden" name="user_id" id="user_id" value="">    		
        </div>   
      </div>  	
      <div class="col-md-12" style="text-align:center; padding-top:20px; padding-bottom:20px;">   
        <input type="submit" value="Confirm" class="btn btn-default sumit_bottom">  
      </div>  
    </div>  
    <div class="col-md-3">
    </div>
  </div>
  <input type="hidden" value="1" name="admin_preview">
</form>                  
<div id="content">
  <div class="container">
    <div class="crumbs">	
      <ul id="breadcrumbs" class="breadcrumb">		
        <li>			
        <i class="icon-home"></i>			
        <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Configuration</a>		
        </li>    
        <li>			
        <i class="current"></i>			
        <a href="#">Staff Management</a>		
        </li>		
        <li class="current">			
          <a href="#" title="">Staff Permissions</a>		
        </li>	
      </ul>
    </div>   				
    <!--=== Normal ===--> 				
    <div class="row">					
      <div class="col-md-12">						
        <h4 class="text-left heding_6">Manage User Roles & Permissions             
          <span class="pull-right">
            <a class="popup-with-form" href="#view-as-form">View As</a>
          </span>            </h4>            
        <div class="widget box box-vas">						 							
          <div class="widget-content widget-content-vls">                
            <div class="col-md-12 padded" style="padding-bottom:30px;">                Permissions let you control what users can do on your site. Each user role (defined on the 
              <a href="@role">user roles page</a>) has its own set of permissions. For example, you could give users classified as "Administrators" permission to "administer nodes" but deny this power to ordinary, "authenticated" users. You can use permissions to reveal new features to privileged users (those with subscriptions, for example). Permissions also allow trusted users to share the administrative burden of running a busy site.                 
            </div>						               
            <form method="post" name="frmListing">                
              <table class="table table-striped table-bordered table-hover cf" id="rt1">                  
                <thead class="cf">											
                  <tr>                          
                    <th align="center">Permission
                    </th>                          
<?php
                           foreach($objSetRoles as $roleId=>$roleName)
                           {
							   if($roleId <> 1)
							   {
                                              ?>                           
                    <th>
                      <?php echo ucfirst($roleName);?>
                    </th>                            
<?php
							   }
                          }
?> 											
                  </tr>									
                </thead>									
                <tbody> 
<?php
			
			if(count($objSetPermissions)>0): // Check for the resource exists or not
			 $intI=1;
			$lastModule='';
			foreach($objSetPermissions as $objPermission)
			{
         
         if($lastModule<>$objPermission->module)
         {
           $lastModule = $objPermission->module;  
                           ?>         
                  <tr class="headingTr">          
                    <td colspan="<?php echo count($objSetRoles)+1;?>">
                      <?php echo ucfirst($objPermission->module);?> Module</td>        
                  </tr>         
<?php           
         }                              
                  			?>          
                  <tr>            <td>
                      <?php echo $objPermission->permission;?></td>            
<?php
               foreach($objSetRoles as $roleId=>$roleName)
               {
				 if($roleId<>1)
					{   
                                ?>             <td>
                      <input type="checkbox" value="1" name="permission_<?php echo $roleId;?>_
                      <?php echo $objPermission->id;?>" 
                      <?php echo $this->objFunction->check_role_permission($roleId, $objPermission->id);?>></td>            
<?php
					}
               }
?>            
                  </tr>          
<?php
			   }
                  			   ?>                      
<?php
			 else:
			       echo '<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
                  			 ?>           
                </tbody>        
              </table>         
              <div class="col-md-12 btn-send">                       
                <input type="submit"  name="submit" value="Save Permissions" />          
                <div class="clearfix">
                </div>          
              </div>        
              <input type="hidden" name="task" value="savepermissions" />    	  
            </form>								
          </div>						
        </div>					
      </div>				
    </div>				
    <!-- /Normal -->			
  </div>			
  <!-- /.container -->		
</div>
<?php
   }
   
} // End of Class
?>