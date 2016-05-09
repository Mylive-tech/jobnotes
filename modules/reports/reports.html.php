<?php
/**
 * @Purpose: Class for HTML view of the Content Module
*/

class REPORT_HTML_CONTENT
{
   private $objFunction;
     
public function __construct($objfunc) //define Constructor
   {
     //$this->objDatabase 	= new Database();
	 $this->objFunction = $objfunc; 	     	
   }


protected function listFormPostings($objRs)
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
			<a href="#">Reporting</a>
		</li>
    <li>
			<i class="current"></i>
			<a href="<?php echo ISP::AdminUrl('reports/listing/');?>">Reports Manager</a>
		</li>
    
    <li>
			<i class="current"></i>
			<a href="#">Report Submissions</a>
		</li>
		
	</ul>
</div>  
 
<div class="row">
<div class="col-md-12">  
<h4 class="text-left heding_6">Report Submissions</h4>
<div class="widget box box-vas">
<div class="widget-content widget-content-vls">
<div class="form_holder">
  <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">
  <thead class="cf">
    <tr>
     <th data-class="expand">Report Name</th>
     <th data-hide="phone">Submitted By</th>
     <th>Submission Date</th>
     <th data-hide="phone">Actions</th>
    </tr>
  </thead>
  <tbody>
 <?php
    while($objRow = $objRs->fetch_object())
    {
 ?>   
    <tr>
     <td><a href="<?php echo ISP :: AdminUrl('reports/form-posting-details/id/'.$objRow->id);?>"><?php echo $objRow->report_name;?></a></td>
     <td><?php echo $this->objFunction->iFind(TBL_STAFF, 'CONCAT(f_name," ", l_name)', array('id'=>$objRow->submitted_by));?></td>
     <td><?php echo strftime("%m/%d/%Y %I:%M %p", strtotime($objRow->submission_date));?></td>
     <td><a href="<?php echo ISP :: AdminUrl('reports/form-posting-details/id/'.$objRow->id);?>"><i class="icon-search"></i></a></td>
    </tr>
<?php 
    }
?>   
  </tbody>
  </table>
</div>
</div> 
</div>
</div> 
</div>
</div>
</div>
<?php   
   }
   
protected function reportListing($objRs)
   {
?>
<form method="post" name="frmListing">
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
			<a href="#">Reporting</a>
		</li>
    <li>
			<i class="current"></i>
			<a href="#">Reports Manager</a>
		</li>
     <li>
			<i class="current"></i>
			<a href="#">Custom Reports</a>
		</li>
		
	</ul>
</div>  
 
<div class="row">
<div class="col-md-12">  
<h4 class="text-left heding_6">Reports Manager</h4>
<div class="widget box box-vas">
<div class="widget-content widget-content-vls">
<div class="form_holder">
  <table class="table table-striped table-bordered table-hover table-checkable table-responsive">
  <thead class="cf">
    <tr>  
     <th align="center" style="text-align:center;" data-hide="phone" class="hideexport divchecker1">Select/Deselect All <input type="checkbox" class="uniform1" name="del[]" onclick="selectDeselect('delete[]', 'del[]');"/> 
     <th data-class="expand">Form Name</th>
     <th>Submissions</th>
     <th data-hide="phone">Flush Data</th>
     <th data-hide="phone">Status</th>
     <th data-hide="phone">Actions</th>
     
    </tr>
  </thead>
  <tbody>
 <?php
    while($objRow = $objRs->fetch_object())
    {
     $strStatus = ($objRow->status)?'0':'1';
 ?>   
    <tr>
     <td align="center" class="hideexport divchecker1"><input type="checkbox" class="uniform1" name="delete[]" value="<?php echo $objRow->report_id;?>"  /></td>
     <td><?php echo $objRow->report_name;?></td>
     <td>
     <a href="<?php echo ISP :: AdminUrl('reports/form-postings/id/'.$objRow->report_id);?>">
     <?php echo $objRow->submissions;?>
     </a>
     </td>
     <td>
     <a href="<?php echo ISP :: AdminUrl('reports/flush-data/id/'.$objRow->report_id);?>" onclick="return confirm('Are you sure to Flush the data?\n\n Once it is flushed, can not be recovered.');">
     Flush Data
     </a>
     </td>
     <td align="center" class="hideexport">
            <a href="<?php echo ISP :: AdminUrl('reports/update-form-status/id/'.$objRow->report_id.'/status/'.$strStatus);?>" alt="Click to <?php echo ($objRow->status==1)?'Deactivate':'Activate';?>" title="Click to <?php echo ($objRow->status==1)?'Deactivate':'Activate';?>">
                  <i class="fa fa-toggle-<?php if($objRow->status==1) echo 'on'; else echo 'off';?>"></i>
            </a>
     </td>
     <td><a href="<?php echo ISP :: AdminUrl('reports/edit-form/id/'.$objRow->report_id);?>">Edit Fields</a></td>
        
     
    </tr>
<?php 
    }
?>   
    <tr>
    <td colspan="2">
    Action: <select name="action">
    <option value="publish">Publish</option>
    <option value="unpublish">UnPublish</option>
    <option value="delete">Delete</option>
    <option value="flush">Flush Data</option>
    </select>
    <input type="submit" value="Go" class="btn btn-info btn-ms" name="btn_go"></td>
    
    <td align="center" colspan="5">
    <input type="checkbox" name="flush_staffimages" value="1"> Flush Staff Uploads &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type="submit" class="btn btn-danger btn-ms" name="btn_backup_reset" value="Backup and Master Reset" onclick="return confirm('Are you sure to Reset all the reports data and properties status?');">
    </td>
    
    <!--<td align="center" colspan="3">
    <input type="submit" class="btn btn-danger btn-ms" name="btn_reset" value="Master Reset" onclick="return confirm('Are you sure to Reset all the reports data and properties status?');">
    </td>-->
    <!--<td align="center">
    <input type="submit" class="btn btn-info btn-ms" name="btn_delete" value="Delete Report" onclick="return confirm('Are you sure to Delete all the selected Report(s)? \n If you will confirm then Reports can not be recovered.');"></td>
    <td>
    <input type="submit" class="btn btn-warning btn-ms" name="btn_flush" value="Flush Data" onclick="return confirm('Are you sure to Flush the Data of all the selected Report(s)?');"></td>
    -->
    </tr>
  </tbody>
  </table>
</div>

</div> 
</div>
</div> 
</div>
</div>
</div>
<input type="hidden" name="task" value="flush-data">
</form>
<?php   
   }
protected function admin_report_ivr_log($objRs) {
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/dp/jquery.datetimepicker.css"/>
<script src="<?php echo SITE_URL;?>assets/js/dp/jquery.datetimepicker.full.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.datetimepicker').datetimepicker();
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
                <a href="#">Reporting</a>
            </li>
            <li>
                <i class="current"></i>
                <a href="#">Reports Manager</a>
            </li>
        
        </ul>
    </div>  				
    <!--=== Normal ===--> 				
    <div class="row">		
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">						
          <div class="widget box box-vas">							 							
            <div class="widget-content widget-content-vls">                             
               <?php 
			   	if(isset($_GET['staffid']))
				{
					$user_details = $this->objFunction->getUserDetailsUsingUsername($_GET['staffid']);
					while($objRow = $user_details->fetch_object()) {
						$user_role = $this->objFunction->UserRoleUsingUserType($objRow->user_type);
						//print_r($user_role);
						echo '<p>Username/IVR staffId: '.$objRow->username.'</p>';
						echo '<p>Name: '.$objRow->f_name.' '.$objRow->l_name.'</p>';
						echo '<p>Email: '.$objRow->email.'</p>';
						echo '<p>Phone: '.$objRow->phone.'</p>';
						echo '<p>Role: '.$user_role->label.'</p>';
					}
					
					$array_log = $this->objFunction->getStaffIvrLog($_GET['staffid']);
					//print_r($array_log);
					?>
                    <p>
                    <form action="" method="get">
                        <label> Date:  </label>
                        <input type="text" class="datetimepicker" id="datetimepicker_from" name="ivr_date_from" placeholder="From" value="<?php if(isset($_GET['ivr_date_from'])) echo $_GET['ivr_date_from'];?>" />
                        <input type="text" class="datetimepicker" id="datetimepicker_to" name="ivr_date_to" placeholder="To" value="<?php if(isset($_GET['ivr_date_to'])) echo $_GET['ivr_date_to'];?>" />
                        <input type="hidden" name="staffid" id="staffid" value="<?php echo $_GET['staffid'];?>" />
                        <input type="submit" name="s" id="s" value="Search" />
                    </form>
                    </p>
                    <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                        <th data-class="expand">S NO</th>												  
                        <th data-hide="phone">Date</th>                          
                        <th data-hide="phone">Time</th>
                        <th data-hide="phone">Status</th>
                    </tr>									
                  </thead>									
                  <tbody>
                  	<?php
					$intI = 1; $rowCount=0;
					foreach ($array_log as $log) 
					{
						//$rowCount++;
						if($intI++%2==0)  // Condition for alternate rows color
							$strCss='evenTr';
						else
							$strCss='oddTr';
						if(isset($_GET['s'])):
							if($log['time_stamp'] >= strtotime($_GET['ivr_date_from']) && $log['time_stamp'] <= strtotime($_GET['ivr_date_to'])):
							$rowCount++;
						?>
                          <tr> 
                            <td align="center"><?php echo $rowCount;?></td>             
                            <td align="center"><?php echo $log['date'];?></td>
                            <td align="center"><?php echo $log['time_12_hour_clock'];?></td>
                            <td align="center"><?php echo ucfirst($log['clock_action_description']);?></td>
                          </tr>
                        <?php 
							endif;
						else:
							$rowCount++;
						?>
                          <tr> 
                            <td align="center"><?php echo $rowCount;?></td>             
                            <td align="center"><?php echo $log['date'];?></td>
                            <td align="center"><?php echo $log['time_12_hour_clock'];?></td>
                            <td align="center"><?php echo ucfirst($log['clock_action_description']);?></td>
                          </tr>
                        <?php
						endif;
						
						
					}
				    ?>
                  </tbody>
                  </table>
                <?php }
				else { ?> 
                 <form method="post" name="frmListing"> 
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                        <th data-class="expand">Username/IVR Staff ID</th>												  
                        <th data-hide="phone">Name</th>                          
                        <th data-hide="phone">Date</th>
                        <th data-hide="phone">Time</th>
                        <th data-hide="phone">Clock Action</th>
                        <th>View Full Log</th>
                        <th data-hide="phone">Export to CSV</th>
                    </tr>									
                  </thead>									
                  <tbody>                                                                                                    					                         
<?php 
			
			if($objRs): // Check for the resource exists or not
                $intI=1;
                $staff_log = $this->objFunction->getAllStaffLastLog();
            //echo 'ddd'; print_r($staff_log);    
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
							$ivrdatetimestatus = explode(',', $staff_log[$objRow->username])
                        ?>
                                  
                            <tr> 
                                <td align="center"><?php echo $objRow->username;?></td>             
                                 <td align="center"><?php echo $objRow->f_name.' '.$objRow->l_name;?></td>              
                                <td align="center"><?php echo $ivrdatetimestatus[0];?></td>
                                <td align="center"><?php echo $ivrdatetimestatus[1];?></td>
                                <td align="center">
                                <?php
                                echo $ivrdatetimestatus[2];
                                //echo strftime('%b, %d %Y', $log['time_stamp']);?> <?php //echo ucfirst($log['clock_action_description']);?>
                                </td> 
                                <!-- <td align="center"><a href="<?php echo ISP :: AdminUrl('index.php?dir=staff&task=edit-staff&id='.$objRow->id);?>">View</a></td>-->
                                <td align="center"><a href="<?php echo ISP::AdminUrl('reports/report_ivr_log/?staffid='.$objRow->username);?>">View</a></td>
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
              <?php } ?>      	  
             			
            </div>    
          </div>
        </div>
      </div>
    </div>				
    <!-- /Normal -->			
  </div>
 </div>			
<?php    
} 
protected function admin_driver_report($objRs) {
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/dp/jquery.datetimepicker.css"/>
<!--<script src="<?php echo SITE_URL;?>assets/js/dp/jquery.js"></script>-->
<script src="<?php echo SITE_URL;?>assets/js/dp/jquery.datetimepicker.full.js"></script>

<script type="text/javascript">

$(document).ready(function() {
	$('.datetimepicker').datetimepicker();
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
                <a href="#">Reporting</a>
            </li>
            <li>
                <i class="current"></i>
                <a href="#">Reports Manager</a>
            </li>
            <li>
                <i class="current"></i>
                <a href="#">Driver Reports</a>
            </li>
        
        </ul>
    </div>  				
    <!--=== Normal ===--> 				
    <div class="row">		
      <div class="col-md-12">
      <p><form action="" method="get">
      <label> Date:  </label>
      	<input type="text" class="datetimepicker" id="datetimepicker_from" name="date_from" placeholder="From" value="<?php if(isset($_GET['date_from'])) echo $_GET['date_from'];?>" />
		<input type="text" class="datetimepicker" id="datetimepicker_to" name="date_to" placeholder="To" value="<?php if(isset($_GET['date_to'])) echo $_GET['date_to'];?>" />
        <input type="submit" name="s" id="s" value="Search" />
      </form></p>
        <div class="tabbable tabbable-custom">						
          <div class="widget box box-vas">							 							
            <div class="widget-content widget-content-vls">                
              <form method="post" name="frmListing">             
                   
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                        <th data-class="expand">Username/Staff ID</th>												  
                        <th data-hide="phone">First Name</th>                          
                        <th data-hide="phone">Last Name</th>
                        <th data-hide="phone">Hours Worked</th>
                        <th>View Log</th>
                        <!--<th data-hide="phone">Export to CSV</th>-->
                    </tr>									
                  </thead>									
                  <tbody>                                                                                                    					                         
<?php
			if($objRs): // Check for the resource exists or not
                $intI=1;
                $staff_log = $this->objFunction->getAllStaffLastLog();
            //print_r($staff_log);    
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{ //print_r($objRow);
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
				if(isset($_GET['s']))
				{
					$startdate = date("Y-m-d", strtotime($objRow->s_date));
					$ys_date = date($startdate,strtotime("-1 days"));
					$cur_date = $startdate;
				}
				else
				{
					$startdate = date("Y-m-d", strtotime($objRow->s_date));
					$ys_date = date('Y-m-d',strtotime("-1 days"));
					$cur_date = date('Y-m-d');
				}
				if(strtotime($startdate) >= strtotime($ys_date) && strtotime($startdate) <= strtotime($cur_date)):
                        ?>
                                  
                            <tr> 
                                <td align="center"><?php echo $objRow->username;?></td>             
                                <td align="center"><?php echo $objRow->f_name;?></td>              
                                <td align="center"><?php echo $objRow->l_name;?></td>              
                                <td align="center">
                                <?php  
                                 $diff1 = strtotime($objRow->c_date) - strtotime($objRow->s_date);
								echo round($diff1/3600, 2);
                                //echo strftime('%b, %d %Y', $log['time_stamp']);?> <?php //echo ucfirst($log['clock_action_description']);?>
                                </td>                             
                                <td align="center"><a href="<?php echo ISP :: AdminUrl('reports/driver_report_log/?driver_id='.$objRow->id);?>">View</a><!--a href="<?php //echo ISP :: AdminUrl('index.php?dir=staff&task=edit-staff&id='.$objRow->id);?>">View</a--></td>
                            </tr> 
<?php               
            endif;                       
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
 </div>			
<?php    
}
protected function admin_driver_report_log($objRs) {
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
                <a href="#">Reporting</a>
            </li>
            <li>
                <i class="current"></i>
                <a href="#">Reports Manager</a>
            </li>
            <li>
                <i class="current"></i>
                <a href="#">Driver Reports</a>
            </li>
            <li>
                <i class="current"></i>
                <a href="#">Log</a>
            </li>
        
        </ul>
    </div>  				
    <!--=== Normal ===--> 				
    <div class="row">		
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">						
          <div class="widget box box-vas">							 							
            <div class="widget-content widget-content-vls">                
              <form method="post" name="frmListing">             
                   
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                        <th data-class="expand">S No</th>												  
                        <th data-hide="phone">Property Name</th>                          
                        <th data-hide="phone">Start Date</th>
                        <th data-hide="phone">closing Date</th>
                        <th>Hours</th>
                        <!--<th data-hide="phone">Export to CSV</th>-->
                    </tr>									
                  </thead>									
                  <tbody>                                                                                                    					                         
<?php
			$i=0;
			if($objRs): // Check for the resource exists or not
                $intI=1;
                $staff_log = $this->objFunction->getAllStaffLastLog();
            //print_r($staff_log);    
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{
			    $i++;
				$strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
                        ?>
                                  
                            <tr> 
                                <td align="center"><?php echo $i;?></td>             
                                <td align="center"><?php echo $objRow->job_listing;?></td>              
                                <td align="center"><?php echo $objRow->start_date;?></td>              
                                 <td align="center"><?php echo $objRow->closing_date;?></td>              
                                <td align="center">
                                <?php $diff1 = strtotime($objRow->closing_date) - strtotime($objRow->start_date);
								echo round($diff1/3600, 2);
                                //echo strftime('%b, %d %Y', $log['time_stamp']);?> <?php //echo ucfirst($log['clock_action_description']);?>
                                </td>                             
                                <!--<td align="center"><a href="<?php //echo ISP :: AdminUrl('index.php?dir=staff&task=edit-staff&id='.$objRow->id);?>">View</a></td>
                            </tr> -->
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
              </form>			
            </div>    
          </div>
        </div>
      </div>
    </div>				
    <!-- /Normal -->			
  </div>
 </div>			
<?php    
}
   protected function reportForm($recordSet, $form_id)
   {
?>
  <link rel="stylesheet" href="<?php echo SITE_URL;?>vendor/css/vendor.css" />
  <link rel="stylesheet" href="<?php echo SITE_URL;?>dist/formbuilder.css" />
  
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
			<a href="#">Reporting</a>
		</li>
        <li>
			<i class="current"></i>
			<a href="#">Reporting Editor</a>
		</li>
		
	</ul>
</div>  

<div class="row">
<div class="col-md-12">  
<h4 class="text-left heding_6"><?php if(intval($form_id)>0) echo 'Update'; else echo 'Add New';?> Form</h4>
<div class="widget box box-vas">
<div class="widget-content formTableBg widget-content-vls">
<div class="form_holder">
<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Report Name</label>                                    
 <input type="text" class="form-control" name="form_name" id="form_name" value="<?php echo $recordSet->report_name;?>" placeholder="Custom Report">                                  
</div>
<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Description</label>                                    
 <textarea class="form-control" name="form_desc" id="form_desc" rows="3" cols="30"><?php echo $recordSet->form_description;?></textarea>                                  
</div>
<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Send Email To</label>                                    
 <input class="form-control" name="md_send_to" id="form_send_to" value="<?php echo $recordSet->send_to;?>"> <small>For multiple email address, add email id with comma seperated</small>                                
</div>

<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Mail Subject</label>                                    
 <input class="form-control" name="md_mail_subject" id="form_mail_subject" value="<?php echo $recordSet->mail_subject;?>">                                 
</div>

<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Location Dropdown Label</label>                                    
 <input class="form-control" name="md_location_box_label" id="location_box_label" value="<?php echo ($recordSet->location_box_label)? $recordSet->location_box_label: 'Location';?>">                                 
</div>

<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Property Dropdown Label</label>                                    
 <input class="form-control" name="md_property_box_label" id="property_box_label" value="<?php echo ($recordSet->property_box_label)? $recordSet->property_box_label: 'Property';?>">                                 
</div>

<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Submit Button Text</label>                                    
 <input class="form-control" name="md_submit_button_text" id="button_text" value="<?php echo ($recordSet->submit_button_text)? $recordSet->submit_button_text: 'Submit';?>">                                 
</div>

<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Activate in all Properties</label>                                    
 <input type="checkbox" name="active_in_all" id="active_in_all" value="1">                                 
</div>
<div class="form-group">                                     
 <label for="exampleInputEmail1" class="font-Bold">Remove From all Properties</label>                                    
 <input type="checkbox" name="remove_in_all" id="remove_in_all" value="1">                                 
</div>

<div class='fb-main'></div>
</div>
</div> 
</div>
</div> 
</div>
</div>
</div>

  

  <script>
    $(function(){
      fb = new Formbuilder({
        selector: '.fb-main',
       <?php if($recordSet->form_body<>'') { ?>
        bootstrapData: <?php echo $recordSet->form_body;?>
<?php } else {?>
          bootstrapData: [{
            "label": "Name",
            "field_type": "website",
            "required": false,
            "field_options": {},
            "cid": "c1"
          }]
 <?php 
}?>
      });

      fb.on('save', function(payload)
       {
         saveForm(payload);
      })
    });
function saveForm(data)
{
  var form_name  =  $("#form_name").val();
  var form_desc  =  $("#form_desc").val();
  var form_send_to  =  $("#form_send_to").val();
  var form_mail_subject  =  $("#form_mail_subject").val();
  var location_box_label  =  $("#location_box_label").val();
  var property_box_label  =  $("#property_box_label").val();
  var button_text  =  $("#button_text").val();
  var active_in_all = $("#active_in_all:checked").val();
  var remove_in_all = $("#remove_in_all:checked").val();
  
var jqxhr = $.post( "<?php echo ISP :: AdminUrl('reports/save-form/');?>", {fields: data, location: location_box_label, property: property_box_label, send_to:form_send_to, mail_subject:form_mail_subject,button_text:button_text,remove_in_all:remove_in_all, active_in_all:active_in_all, form_name:form_name, desc:form_desc, id:<?php echo intval($form_id);?>}, function(dat) {
  alert( dat);
})
  .fail(function() {
    alert( "Error: Form could not saved. Please try again!" );
  });
}
  </script>
<?php   
   }
   
  
protected function showFormSubmission($objRow, $formControls, $form_token, $postedValues, $rsData)
 {
    $reportId = $rsData->report_id;
    $fields =  json_decode($objRow->form_body);
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
			<a href="#">Reporting</a>
		</li>
    <li>
			<i class="current"></i>
			<a href="<?php echo ISP::AdminUrl('reports/listing/');?>">Reports Manager</a>
		</li>
    
    <li>
			<i class="current"></i>
			<a href="<?php echo ISP::AdminUrl('reports/form-postings/id/'.$reportId);?>">Report Submissions Listing</a>
		</li>
    
     <li>
			<i class="current"></i>
			<a href="#">Report Submissions Details</a>
		</li>
		
	</ul>
</div>       			
		<div class="row row-bg staff_section">
        <h4><?php echo $objRow->report_name;?></h4>
        <p><?php echo nl2br($objRow->form_description);?></p>
  <form method="post" class="jobnote_forms">
<?php
 if($objRow->report_id<>12)
 {
 ?>  
  <fieldset>
  <p class="col-md-4">
  <label>Location</label>
  <?php echo $this->objFunction->iFind(TBL_SERVICE, 'name', array('id'=>$postedValues['db_location']));?>
  </p>   
   <p id="joblocationtag" class="col-md-4"> 
    <label>Property</label>
    <?php echo $this->objFunction->iFind(TBL_JOBLOCATION, 'job_listing', array('id'=>$postedValues['db_property']));?>
  </p>
  <p class="col-md-4">
  <label>Submission Date</label>
  <?php echo strftime("%m/%d/%Y %I:%M %p", strtotime($rsData->submission_date));?>
  </p>
  
  </fieldset>
  <?php
 }
 
  $fieldsetStart=0; 
   foreach($formControls[$form_token]['#value'] as $fieldArray)
   {
     $required= ($fieldArray['#required']==1)?'required':'';
	 
	 if($fieldArray['#type']=='fieldset')
	 {
		if( $fieldsetStart==1)
		{
          echo '</fieldset>';
		}	
		$fieldsetStart=1;
      ?> <fieldset class="<?php echo  $fieldArray['#class'];?>">
	     <legend><?php echo $fieldArray['#title'];?></legend>
	  <?php
     }
	else
	{
     ?> <p class="nopadding <?php echo  $fieldArray['#class'];?>">
	    <label><?php echo $fieldArray['#title'];?></label>
	 <?php
	}	
  ?>
   <?php
    if (is_array($fieldArray['#value']))
        echo implode("<br>", $fieldArray['#value']);
    else
      echo $fieldArray['#value'];
      
    if($fieldArray['#type']<>'fieldset')
	 {
      ?> </p>
	 <?php
	}	
  ?>
  <?php
   }
   ?> 
   </fieldset>
   <p class="clearfix"></p>
   <p align="center">
    <a href="<?php echo ISP::AdminUrl('reports/form-postings/id/'.$reportId);?>" class="btn btn-info">
     Go Back
    </a>
   </p>
  </form>
  </div>
  </div>
  </div>
 <?php
 }  




   
 protected function showForm($objRow, $formControls, $form_token, $successmsg='')
 {
   $fields =  json_decode($objRow->form_body);
 ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> 
 <script type="text/javascript">
  function loadLocation(sectionId)
  {
     $("#joblocationtag").html('Loading.....');
     $.ajax({
              method: "POST",
              url: "<?php echo ISP :: AdminUrl('property/ajax-joblocation/');?>",
              data: { id: sectionId }
            })
            .done(function( datahtml ) {
                $("#joblocationtag").html(datahtml);
               });
  }
 $(function() {
    $( ".datetimepicker" ).datepicker();
 });
</script>
 <div id="content">
			<div class="container">				
				<div class="row row-bg staff_section">
        <?php
        if($successmsg<>'') {?>
        <div class="alert alert-info fade in">
				<i class="icon-remove close" data-dismiss="alert"></i>
				Form has been submitted successfully. 
				</div>
        <?php } ?>
        <h4>
        <a href="javascript: window.history.go(-1);" style="text-decoration:none; margin-right:10px;">
    <button id="btn-load" class="btn btn-primary btn-info"><i class="icon-chevron-left"></i>&nbsp;Go Back</button>
    </a>
    &nbsp;&nbsp;
        <?php echo $objRow->report_name;?></h4>
        <p><?php echo nl2br($objRow->form_description);?></p>
  <form method="post" class="jobnote_forms">

<?php
 if($objRow->report_id<>12)
 {
?>  
  <fieldset>
  <p class="col-md-6">
    <label><?php echo $objRow->location_box_label;?></label>
    <select class="form-control inline_button" name="db_location" required onchange="loadLocation(this.value);">   
        <?php echo  $this->objFunction->locationDropdown($_GET['location']); ?>
                                 
    </select>
  </p>   
   <p id="joblocationtag" class="col-md-6"> 
    <label><?php echo $objRow->property_box_label;?></label>
    <select class="form-control inline_button" name="db_property" required>                                       
      <option value="">---Please select---</option>                   
      <?php 
    $sectionProp = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('status'=>1, 'location_id'=>$_GET['location']));
    if(count($sectionProp)>0)
    {
        foreach($sectionProp as $sect)
        { 
          if($sect->job_listing<>''){
          ?>                                        
          <option value="<?php echo $sect->id; ?>" <?php if(isset($_GET['prop']) && $_GET['prop']==$sect->id) echo 'selected';?>><?php echo $sect->job_listing; ?></option>                   
        <?php 
           }
        }
     }   
      ?>                                
    </select>
  </p>
  </fieldset>
  <?php
}
  
  $fieldsetStart=0; 
   foreach($formControls[$form_token]['#value'] as $fieldArray)
   {
     $required= ($fieldArray['#required']==1)?'required':'';
	 
	 if($fieldArray['#type']=='fieldset')
	 {
		if( $fieldsetStart==1)
		{
          echo '</fieldset>';
		}	
		$fieldsetStart=1;
      ?> <fieldset class="<?php echo  $fieldArray['#class'];?>">
	     <legend><?php echo $fieldArray['#title'];?></legend>
	  <?php
     }
	elseif($fieldArray['#type'] <> 'hidden' && $fieldArray['#type'] <> 'fieldset')
	{
        if ($fieldArray['#class'] == 'col-md-12') {
            echo '<div style="clear: both;"></div>';
        }
     ?> 
     <p class="<?php echo  ($fieldArray['#class'])?$fieldArray['#class'] : 'col-md-4';?>">
	    <label><?php echo $fieldArray['#title'];?></label>
	 <?php
	}	
  ?>
   <?php
      if($fieldArray['#type']=='address' || $fieldArray['#type']=='text' || $fieldArray['#type']=='website')
      {
        echo '<input type="text" name="'.$fieldArray['#name'].'" '.$required.'>';
      }
      elseif($fieldArray['#type']=='paragraph')
      {
        echo '<textarea name="'.$fieldArray['#name'].'" '.$required.' class="'.$fieldArray['#class'].'"></textarea>';
      } 
      elseif($fieldArray['#type']=='date')
      {
        echo '<input type="text" class="datetimepicker" name="'.$fieldArray['#name'].'" '.$required.'>';
      }
      elseif($fieldArray['#type']=='time')
      {
        echo '<input type="text" placeholder="Time in 24 hours format for Ex. 16:45" name="'.$fieldArray['#name'].'" '.$required.'>';
      }
      elseif($fieldArray['#type']=='number')
      { 
        echo '<input type="number" placeholder="" name="'.$fieldArray['#name'].'" '.$required.'>'.$fieldArray['#units'];
      }
      elseif($fieldArray['#type']=='dropdown')
      {
        echo '<select name="'.$fieldArray['#name'].'" '.$required.'>';
        if($fieldArray['include_blank']==1)
         echo '<option value="">Please Select</option>';
        
        foreach($fieldArray['#field_options'] as $fieldOption)
        {
          $selected= ($fieldOption->checked==1)?" selected":"";
           echo '<option value="'.$fieldOption->label.'" '.$selected.'>'.$fieldOption->label.'</option>';
        }
      }      
      elseif($fieldArray['#type']=='radio')
      {           
        foreach($fieldArray['#field_options'] as $fieldOption)
        {
            $selected= ($fieldOption->checked==1)?" selected":"";
            if ($fieldOption->label <> '')
                echo '<input type="radio" value="'.$fieldOption->label.'" name="'.$fieldArray['#name'].'" '.$required.$selected.'>'.$fieldOption->label;
        }
      }
      elseif($fieldArray['#type']=='checkboxes')
      {           
        foreach($fieldArray['#field_options'] as $fieldOption)
        {
            $checked= ($fieldOption->checked==1)?" checked":"";
            if ($fieldOption->label <> '')
                echo '<input type="checkbox" value="'.$fieldOption->label.'" name="'.$fieldArray['#name'].'[]" '.$checked.'>'.$fieldOption->label;
        }
      } 
    if($fieldArray['#type']<>'fieldset' && $fieldArray['#type'] <> 'hidden')
	 {
      ?> </p>
	 <?php
	}	
  ?>
  <?php
   }
   ?> 
   </fieldset>
   <p class="clearfix"></p>
    <input type="hidden" name="form_token" value="<?php echo $form_token;?>">
    <p align="center">
    <input type="submit" name="send" class="bluebutton" value="<?php echo ($objRow->submit_button_text)? $objRow->submit_button_text : 'Submit Report';?>">
   </p>
  </form>
  </div>
  </div>
  </div>
 <?php
 } 
 
    protected function zipBackup($file) {
    ?>
        <div id="content">      
            <div class="container"> 
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-left heding_6">Backup Created</h4>
                        <div class="widget box box-vas">
                            <div class="widget-content widget-content-vls">
                                <div class="form_holder">
                                    <p>Files Backup has been created successfully. Please click on below Download link to download the file.<br><br>
                                    <a href="<?php echo SITE_URL.$file;?>" target="_blank">Download Backup</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    
    
    
 protected function exportReports($export_file) {
?>
<form method="post">
<div id="content">      
	 <div class="container"> 
		<div class="crumbs"> 
			<ul id="breadcrumbs" class="breadcrumb">
				<li><i class="icon-home"></i> <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/'); ?>">Dashboard</a>
				</li>
				<li><i class="current"></i><a href="#">Reporting</a></li>
				<li><i class="current"></i><a href="#">Export Reports</a></li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-left heding_6">Export Reports Wizard</h4>
				<div class="widget box box-vas">
					<div class="widget-content widget-content-vls">
						<div class="form_holder">
							<fieldset>
								<legend>Select what you want to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">
									<tr>
										<!--<td>
											<input type="checkbox" class="uniform" value="all_reports" name="export[]">Export All Reports
										</td>-->
										<td>
											<input type="checkbox"  class="uniform"  value="all_state_reports" name="export[]" onclick=" $('#allstate_report').toggle(); $('#allstate_report').find('select').attr('disabled',!this.checked);">All State Reports
										</td>
										<td>
											<input type="checkbox" class="uniform"  value="single_location_reports" name="export[]" onclick=" $('#singlelocation_report').toggle(); $('#singlelocation_report').find('select').attr('disabled',!this.checked);">Single Location Reports
										</td>  
                                        <td>                            
											<input type="checkbox" class="uniform"  value="individual_reports" name="export[]" onclick=" $('#individual_report').toggle(); $('#individual_report').find('select').attr('disabled',!this.checked);">Individual Reports
										</td>                                         
									</tr>                    
									<tr>                        
										                       
										<td>                            
											<input class="uniform"  type="checkbox" value="manager_reports" name="export[]" onclick=" $('#manager_report').toggle(); $('#manager_report').find('select').attr('disabled',!this.checked);">Manager Reports                      
										</td>                        
										<td>                            
											<!--<input type="checkbox" class="uniform" value="employee_reports" name="export[]">Employee Reports    -->
										</td>   
                                        <td>&nbsp;</td>                                        
									</tr>                    
								</table>                
							</fieldset>                                                   
						</div>  

                        <div class="form_holder">
                            <fieldset id="individual_report" style="display: none;">
								<legend>Select Individual Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">
									<tr>
										<td>
											<select name="duration_individual_report">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                            </select>
										</td>
										<td>
                                            <select name="report_individual_report">
                                                <?php echo $this->objFunction->reportsDropDown();?>
                                            </select>
										</td>                  
									</tr>                    
                  
								</table>                
							</fieldset> 

                            <fieldset id="allstate_report" style="display: none;">
								<legend>All States Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">
									<tr>
										<td>
											<select name="duration_allstate_report">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                            </select>
										</td>               
									</tr>              
                                </table>                
							</fieldset> 
                            
                            <fieldset id="singlelocation_report" style="display: none;">
								<legend>Single Location Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">
									<tr>
										<td>
											<select name="duration_singlelocation_report">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                            </select>
										</td>  

                                        <td>
											<select name="location_singlelocation_report">
                                              <?php echo $this->objFunction->locationDropdown(); ?>
                                            </select>
										</td>                                        
									</tr>              
                                </table>                
							</fieldset>
                            
                            <fieldset id="manager_report" style="display: none;">
								<legend>Manager Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">
									<tr>
										<td>
											<select name="duration_manager_report">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                            </select>
										</td>  

                                        <td>
											<select name="staff_manager_id">
                                              <?php echo $this->objFunction->staffDropDown(); ?>
                                            </select>
										</td>                                        
									</tr>              
                                </table>                
							</fieldset>
                            <input type="submit" value="Export Report(s)" name="export_btn" class="btn btn-info">
                            <?php
                            if ($export_file != '') {
                            ?>
                            <a href="<?php echo SITE_URL.$export_file;?>" target="_blank">Download File</a>
                            <?php
                            }
                            ?>
                        </div>
					
                        <div class="form_holder">
                         <table class="table table-striped table-bordered table-hover table-checkable table-responsive">
                          <thead class="cf">
                            <tr>  
                                <th data-class="expand">File Name</th>
                                <th>Creation Date</th>
                                <th data-hide="phone">Download File</th>
                                <th data-hide="phone">Remove File</th>
                            </tr>
                          </thead>
                          <tbody>
                         <?php
                            $glob = glob("upload/zip/{*.zip}", GLOB_BRACE);
                            for($i=0; $i< count($glob); $i++) {            
                                $file = $glob[$i];
                                $filename = basename($file);
                                if(strstr($filename, "export-reports-")) {
                         ?>   
                            <tr>
                                <td><?php echo $filename;?></td>
                                <td><?php echo date("m/d/Y", strtotime(substr($filename, -14,-4)));?></td>
                                <td><a href="<?php echo SITE_URL.$file;?>" target="_blank">Download</a></td>
                                <td><a onclick="return confirm('Are you sure to Delete the file permanently?');" href="<?php echo SITE_ADMINURL.'index.php?dir=reports&task=removezip&file='.$filename;?>">Remove</a></td>
                            </tr>
                        <?php 
                                }
                            }
                        ?>   
                          </tbody>
                          </table>
                        </div>
                    </div>                         
				</div>                   
			</div>            		
		</div>         
	</div>
</div>
</form>
<?php      
    }
   
} // End of Class
?>