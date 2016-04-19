<?php
/**
 * @Purpose: Class for HTML view of the Content Module
*/
class JOBLOCATION_HTML_CONTENT
{
   private $objFunction, $objDBCon;
   
   public function __construct($objfunc) //define Constructor
   {
     $this->objFunction = $objfunc;
     $this->objDBCon = new Database();	 		
   }
   /**
       *@Purpose: HTML view of Add Content Form to Admin
   */
   
   
protected function loadMapBuilder()
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
			<a href="#">Manage Jobs</a>
		</li>
		<li class="current">
			<a href="#" title="">Map Builder</a>
		</li>
	</ul>
  </div>
  <!---------Map Builder------------>
<script type="text/javascript" src="//scribblemaps.com/api/js/"></script>
<script type="text/javascript"> 
window.onload = function() {
var sm = new scribblemaps.ScribbleMap(document.getElementById('ScribbleMap'));        
}

</script>   
   <div class="row">      
      <div class="col-md-12">        
        <h4 class="text-left heding_6">Map Builder</h4>            
        <div class="add-new-job-l widget box box-vas">                 
          <div class="widget-content formTableBg widget-content-vls">                       
            <div class="form_holder"> 
              <div id="ScribbleMap" style="width: 100%; height: 500px"></div> 
            </div>
           </div>
         </div>
       </div>
     </div>       
  <!------------ END---------------->
   
 </div> 
</div>   
<?php
}  
   
protected function admin_joblocation_Form($objRs)
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
			<a href="#">Manage Jobs</a>
		</li>
    <li>
			<i class="current"></i>
			<a href="<?php echo ISP::AdminUrl('property/manage-properties/');?>">Manage Properties</a>
		</li>
		<li class="current">
			<a href="#" title="">Add New Property</a>
		</li>
	</ul>
</div>       
    <div class="row">      
      <div class="col-md-12">        
        <h4 class="text-left heding_6">          
          <?php echo ($objRs->id=='')?'Add New': 'Edit';?> Property</h4>            
        <div class="add-new-job-l widget box box-vas">                 
          <div class="widget-content formTableBg widget-content-vls">                       
            <div class="form_holder">                           
              <form name="frmContent" method="post" onsubmit="return validateFrm(this);" enctype="multipart/form-data">                                 
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Add New Job Listing                   
                  </label>                                    
                  <input type="text" class="form-control" required name="md_job_listing" id="md_job_listing" value="<?php echo $objRs->job_listing;?>" placeholder="Enter Title Here">                                  
                </div>                                                
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Address                   
                  </label>                                     
                  <input type="text" class="form-control" required id="md_location_address" onblur="showAddress(this.value);" name="md_location_address" value="<?php echo $objRs->location_address;?>" placeholder="W Diversey Ave, More Details,Zip Code">                                  
                </div>                				 		                 
                <div class="form-group inline_srarch">                                     
                  <label for="exampleInputEmail1" class="full_width font-Bold">Latitude                   
                  </label>                                      
                  <input type="flot" class="form-control inline_button" required id="lat" name="md_lat" min="0" value="<?php echo $objRs->lat;?>" placeholder="12,23467909125000">                                  
                </div>                                 
                <div class="form-group inline_srarch2">                  
                  <label for="exampleInputEmail1" class="full_width font-Bold">Longtitude                   
                  </label>                                      
                  <input type="flot" class="form-control  inline_button2" required id="lag" name="md_lag" min="0" value="<?php echo $objRs->lag;?>" placeholder="20,10000,4560000">                                  
                </div> 
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="full_width font-Bold">Main Image</label>                                      
                  <input type="file" accept="image/*" name="db_map_widget" class="form-control inline_button" id="db_map_widget">
                 <?php if($objRs->map_widget<>'')
                        echo '<img src="'.SITE_URL.'upload/'.$objRs->map_widget.'" border="0" width="100" height="100">';
                 ?>                                 
                </div>
                
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="full_width font-Bold">Additional Image(s)</label>                                      
                  <input type="file" accept="image/*" name="gallery[]" multiple class="form-control inline_button" id="gallery">
                  <small>You can select multipe images by holding Ctrl button and click on images.</small>                             
                  <div class="clearfix"></div> 
                <?php
                if ($objRs->gallery <> '') {
                     $arrImg = explode(",", $objRs->gallery);
                     foreach($arrImg as $key=>$galImage)
                     {
                ?>
                    <input type="hidden" name="ex_gallery[]" value="<?php echo $galImage;?>" id="gal_<?php echo $key;?>">
                     <div class="gal_thum" id="galdiv_<?php echo $key;?>">
                     <img src="<?php echo SITE_URL;?>upload/<?php echo $galImage;?>" style="max-width:100%; max-height:100%;"  border="0">
                      <a href="javascript: void(0);" onclick="removePhoto(<?php echo $key;?>);"><i class="icon-remove"></i></a>
                     </div>
                <?php
                    }
                }
                ?>
                	<div class="clearfix"></div> 
                </div>
                <div class="clearfix"></div> 
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="full_width font-Bold">Staff Uploads</label>     
                  <div class="clearfix"></div>                                  
                <?php
                if ($objRs->user_gallery <> '') {
                     $arrImg = explode(",", $objRs->user_gallery);
                     foreach($arrImg as $key=>$galImage)
                     {
                ?>
                    <input type="hidden" name="ex_staff_gallery[]" value="<?php echo $galImage;?>" id="staff_gal_<?php echo $key;?>">
                     <div class="gal_thum" id="staff_galdiv_<?php echo $key;?>">
                     <img src="<?php echo SITE_URL;?>upload/<?php echo $galImage;?>" style="max-width:100%; max-height:100%;"  border="0">
                      <a href="javascript: void(0);" onclick="removeStaffPhoto(<?php echo $key;?>);"><i class="icon-remove"></i></a>
                     </div>
                <?php
                    }
                }
                else {
                 echo 'No Image found uploaded by Staff till now.';   
                }
                ?>
                	<div class="clearfix"></div> 
                </div>
                                                                
                <div class="form-group inline_srarch ">                                     
                  <label class="font-Bold">Assigned To                   
                  </label>                                 
                  <select name="db_assigned_to" id="tag" onchange="showUser(this.value)" class="form-control">   
                    <?php echo $this->objFunction->staffDropDown($objRs->assigned_to); ?>                                
                  </select>                                                    
                </div>
                <div class="form-group inline_srarch2">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Onsite Contact Person                   
                  </label>                                      
                  <input type="text" class="form-control" id="db_onsite_contact_person" name="db_onsite_contact_person" value="<?php echo $objRs->onsite_contact_person;?>" maxlength="100" placeholder="Display from associate DB">                                  
                </div>
                <div class="form-group inline_srarch2">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Onsite Contact Phone Number                   
                  </label>                                      
                  <input type="nomber" class="form-control" id="db_phn_no" name="db_phn_no" value="<?php echo $objRs->phn_no;?>" maxlength="10" placeholder="Display from associate DB">                                  
                </div>                                 
                <div class="form-group">                                     
                  <label for="exampleInputPassword1" class="font-Bold">Important Note                   
                  </label>                   
                  <textarea class="form-control" rows="3" required name="md_importent_notes" id="md_importent_notes" placeholder="Enter detailed Special notes"><?php echo $objRs->importent_notes;?></textarea>                                  
                </div>                                
                                                   
                <div class="form-group inline_srarch2">
                  <label for="exampleInputEmail1" class="full_width font-Bold">Assign Location</label>                                       
                  <select class="form-control inline_button" name="md_location_id" required>  
                    <?php echo $this->objFunction->locationDropdown($objRs->location_id); ?>                                               
                  </select>                                 
                </div>                               
                <div class="clearfix"></div> 
                
                <div class="form-group">                                     
                  <label for="Reports" class="full_width font-Bold">Enable Reports</label>
                  <br/>
                <div class="col-md-12 nopadding">  
                  <?php
                  $reportEnabledArray = explode(",", $objRs->enabled_reports);
                  $objReports = $this->objDBCon->dbFetch("SELECT * FROM ".TBL_REPORTS." where status=1 and site_id='".$_SESSION['site_id']."' order by report_name asc");
                  foreach($objReports as $reportArray)
                     {
                       if($reportArray->report_id<>'') 
                        $reportInfo[$reportArray->report_id] = $reportArray->report_name; 
                     }
                  foreach($reportInfo as $repId=>$repName)     
                  {
                 ?>
                  <div class="col-md-4">
                    <label class="checkbox">
						<input name="reports[]" type="checkbox" <?php if(in_array($repId, $reportEnabledArray)) echo 'checked';?> class="uniform" value="<?php echo $repId;?>"> <?php echo $repName;?>
					</label>                   
                 </div>
                  <?php   
                  }                   
                  ?>
                  </div>
                <div class="clearfix"></div> 
                </div>                    
               <div class="form-group">                                 
                <button type="submit" value="New" name="save_new" class="btn btn-default sumit_bottom">Save & Add New</button>                                 
                <button type="submit" value="back" name="save_back" class="btn btn-default sumit_bottom pull-right">Save & Go Back                 
                </button>                                 
                <input type="hidden" name="task" value="savejoblocationlisting" />
                <input type="hidden" name="db_site_id" value="<?php echo $_SESSION['site_id'];?>">
                </div>                                            
              </form>                     
            </div>                 
          </div>                 
          <!-- /.container -->              
        </div>	        
      </div>    
    </div>  
  </div>
</div>
<div id="overlay_loader" style="z-index:10000; display: none; position: fixed; left:0; top:0; width:100%; height:100%; opacity:0.5; background:#000000; color:#fff; font-weight: bold; align:center;">Loading....</div>	 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=geometry"></script>
<script type="text/javascript">
function showUser(uid)
{
    $.ajax({
            method: "POST",
            url: "<?php echo ISP :: AdminUrl('staff/getuserinfo/');?>",
            data: { uid: uid }
         })
      .done(function( datahtml ) {
        $("#md_phn_no").val(datahtml);
    });
}

function removePhoto(imgkey) {
    if(confirm('Are you sure to remove this image?')) {
        jQuery('#gal_'+imgkey).remove();
        jQuery('#galdiv_'+imgkey).remove();
    }
    else {
        return false;
    }
    
    $("#overlay_loader").show();
    
    $.ajax({
            method: "POST",
            url: "<?php echo ISP :: AdminUrl('property/removephoto/pid/'.$objRs->id);?>/action/gallery",
            data: $("input[name='ex_gallery[]']").serialize()
         })
      .done(function( datahtml ) {
        $("#overlay_loader").hide();
      })
      .error(function() {
        alert('There is some error in image removing process. Please try again.');
        window.location.reload();
      });
}

function removeStaffPhoto(imgkey) {
    if(confirm('Are you sure to remove this image?')) {
        jQuery('#staff_gal_'+imgkey).remove();
        jQuery('#staff_galdiv_'+imgkey).remove();
    }
    else {
        return false;
    }
    
    $("#overlay_loader").show();
    
    $.ajax({
            method: "POST",
            url: "<?php echo ISP :: AdminUrl('property/removephoto/pid/'.$objRs->id);?>/action/staffgallery",
            data: $("input[name='ex_staff_gallery[]']").serialize()
         })
      .done(function( datahtml ) {
        $("#overlay_loader").hide();
      })
      .error(function() {
        alert('There is some error in image removing process. Please try again.');
        window.location.reload();
      });
}

function showAddress(addressValue)
{

 $.ajax({
            url: "http://maps.googleapis.com/maps/api/geocode/json",
            data: { sensor: false, address: addressValue }
         })
      .done(function( data ) 
      {
        $("#lat").val(data.results[0].geometry.location.lat);
        $("#lag").val(data.results[0].geometry.location.lng);
      })
      .fail(function(){alert("Latitude and Longtitude could not found.");});
     
}
</script> 
<?php 
   }  // End of Function 
   
  
   
   
   
   
protected function admin_joblocationlisting($objRs)
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
			<a href="#">Manage Jobs</a>
		</li>
		<li class="current">
			<a href="#" title="">Manage Properties</a>
		</li>
	</ul>
</div>
			     
    <!--=== Normal ===--> 				     
    <div class="row">					       
      <div class="col-md-12">						         
        <h4 class="text-left heding_6">Manage Properties</h4>						         
        <div class="widget box box-vas">							 							           
          <div class="widget-content widget-content-vls">                                             
            <form method="post" name="frmListing"> 
            <div class="col-md-12 text-right" style="padding-bottom:10px">
             <span style="margin-right:10px; border-right:1px solid #ccc; padding-right:10px;">											
                <a href="<?php echo ISP :: AdminUrl('property/add-property/');?>" class="btn btn-info">Add New Property</a>									
              </span> 
              
                <input type="submit" class="btn btn-success btn-ms" name="btn_Publish" value="Publish" onclick="document.frmListing.status.value='1';" />                          
                                      
                <input type="submit" class="btn btn-warning btn-ms" name="btn_UnPublish" value="Unpublish" onclick="document.frmListing.status.value='0';" />                           
                <input type="submit" class="btn btn-danger btn-ms" name="btn_delete" value="Delete" onclick="document.frmListing.status.value='-1';" />                 
              
             	<input type="submit" class="btn btn-info" name="btn_export" value="Export" onclick="document.frmListing.status.value='export';">	



						                 
                <!--<a href="javascript: void(0);" onclick="exportTableToCSV.apply(this, [$('#dataTables-example'), 'export-joblocations.csv']);" class="btn btn-info">Export</a>-->									               
               
            </div>                                                 
              <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                                     
                <thead class="cf">							                   
                  <tr>                                     
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Location</th>								                     
                    <th align="center" data-hide="phone">Address</th>                                     
                    <th align="center" data-hide="phone">Assigned To</th>                                     
                    <th align="center" data-hide="phone">Phone Number</th>                                     
                    <th align="center" data-hide="phone">Important Notes</th>                                     
                    <th align="center" data-hide="phone">Assigned Location</th> 
                    <th align="center" data-hide="phone">Priority</th>                                    
                    <th align="center" data-hide="phone" class="hideexport">Status</th>                                     
                    <th align="center" data-hide="phone" class="hideexport">Edit</th>                                                                                                                                                                                                                                                                                               
                    <th align="center" data-hide="phone" class="hideexport divchecker"><input type="checkbox" class="uniform" name="del[]" onclick="selectDeselect('delete[]');"/>                     
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
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
				
                                    			?>         			                   
                  <tr>
                  <td><?php echo $intI-1;?></td>
                      <td><?php echo $objRow->job_listing;?>
                      <br> <br>
                      <a class="btn btn-danger btn-ms" href="<?php echo ISP :: AdminUrl('property/job-history/id/'.$objRow->id);?>">History</a>
                      </td> 
                      <td><?php echo $objRow->location_address;?></td>
                      <td><?php
                      if($objRow->assigned_to >0) {
                        $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->assigned_to));
                        echo $rowD[0]->f_name.' '.$rowD[0]->l_name;  
                      }
                      else{
                        echo 'Unassigned';    
                      }
                      ?>
                      </td>
                      <td><a href="tel:<?php echo $objRow->phn_no;?>"><?php echo $objRow->phn_no;?></a></td> 
                      <td><?php echo $objRow->importent_notes;?></td>
                      <td><?php
                      echo $this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRow->location_id));
                      //echo $rowD[0]->f_name.' '.$rowD[0]->l_name;
                      ?>                      
                      <?php //echo $objRow->assigned_under_1;?></td>                                    
                    
                    <td align="cente"><a href="<?php echo ISP :: AdminUrl('property/modifyjoblocation/priority_status/'.(($objRow->priority_status==1)?"0":"1").'/delete/'.$objRow->id);?>" class="<?php echo ($objRow->priority_status==1)?'high_priority':'normal_priority';?>"><?php echo ($objRow->priority_status==1)?'High':'Normal';?></a></td>
                    <td align="center" class="hideexport">
                    <a href="<?php echo ISP :: AdminUrl('property/modifyjoblocation/delete/'.$objRow->id.'/status/'.$strStatus);?>" alt="Click to <?php echo ($objRow->status==1)?'Deactivate':'Activate';?>" title="Click to <?php echo ($objRow->status==1)?'Deactivate':'Activate';?>">
                     <i class="fa fa-toggle-<?php if($objRow->status==1) echo 'on'; else echo 'off';?>"></i>
                    </a>
                    </td>                                               
                    <td align="center" class="hideexport">
                    <a href="<?php echo ISP :: AdminUrl('property/edit-property/id/'.$objRow->id);?>">
                    
                    <i class="fa fa-pencil-square-o"></i></a>
                    </td>                               
                    <td align="center" class="hideexport divchecker"><input type="checkbox" class="uniform" name="delete[]" value="<?php echo $objRow->id;?>"  /></td>                             
                  </tr>                            
<?php
			   }
                                    			   ?>                            
                </tbody>                       
              </table>             
                              
<?php
			 else:
			       echo '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
                            			 ?>                          
              <input type="hidden" name="status" value="1" />                       
              <input type="hidden" name="task" value="modifyjoblocation" />                   
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
   }  // End of Function 
   
   
protected function admin_jobHistory($objRs1, $objRow)
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
			<a href="<?php echo ISP::AdminUrl('property/manage-properties/');?>">Manage Properties</a>
		</li>
		<li class="current">
			<a href="#" title="">Job History</a>
		</li>
	</ul>
</div>
			     
    <!--=== Normal ===--> 				     
    <div class="row">					       
      <div class="col-md-12">						         
        <h4 class="text-left heding_6">Job History</h4>						         
        <div class="widget box box-vas">							 							           
          <div class="widget-content widget-content-vls">                                             
            <form method="post" name="frmListing"> 
            <div class="col-md-12 text-right" style="padding-bottom:10px">
            
              <span>										                 
                <a href="javascript: void(0);" onclick="exportTableToCSV.apply(this, [$('#dataTables-example'), 'export-history-<?php echo $objRow->job_listing;?>.csv']);" class="btn btn-info">Export</a>									               
              </span> 
            </div>                                                 
              <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable1">                                                     
                <thead class="cf">							                   
                  <tr>                                     
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Location</th>								                     
                    <th align="center" data-hide="phone">Address</th>                                     
                    <th align="center" data-hide="phone">Assigned To</th>                                     
                    <th align="center" data-hide="phone">Phone Number</th>                                     
                    <th align="center" data-hide="phone">Important Notes</th>                                     
                    <th align="center" data-hide="phone">Assigned Location</th>                                            
                    <th align="center" data-hide="phone" class="hideexport">Completion Date</th>                                                                                                                                                                                                                                                                                               
				            <th align="center" data-hide="phone" class="hideexport">Action</th>       
                  </tr>						                 
                </thead>						                 
                <tbody>                       
<?php
	    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
				
                                    			?>         			                   
                  <tr>
                  <td><?php echo $intI-1;?></td>
                      <td><?php echo $objRow->job_listing;?></td> 
                      <td><?php echo $objRow->location_address;?></td>
                      <td><?php $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->assigned_to)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?></td>
                      <td><?php echo $objRow->phn_no;?></td> 
                      <td><?php echo $objRow->importent_notes;?></td>
                      <td><?php echo $this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRow->location_id));?>                      
                      </td>                                    
                     <td align="center" class="hideexport"><?php echo $objRow->completion_date;?></td>
                     <td align="center" class="hideexport"><a href="<?php echo ISP :: AdminUrl('property/reset-property/id/'.$objRow->id);?>">Reset</a></td>                               
                   </tr>                            
         
                </tbody>                       
              </table>  
              
              
              <table id="dataTables-example" class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                                     
                <thead class="cf">							                   
                  <tr>                                     
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Started On</th>								                     
                    <th align="center" data-hide="phone">Job Started By</th>                                     
                    <th align="center" data-hide="phone">Completed On</th>                                     
                    <th align="center" data-class="expand">Completed By</th>                                     
                          
                  </tr>						                 
                </thead>						                 
                <tbody>                       
<?php
			
			if($objRs1): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow1 = $objRs1->fetch_object())  // Fetch the result in the object array
			  {
    				if($intI++%2==0)  // Condition for alternate rows color
    				   $strCss='evenTr';
    				else
    				   $strCss='oddTr';
				
                                    			?>         			                   
                  <tr>
                  <td><?php echo $intI-1;?></td>
                      <td><?php echo $objRow1->start_date;?></td> 
                      <td><?php $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow1->started_by)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?></td>
                      <td><?php 
                      if($objRow1->closed_by>0){
                      echo $objRow1->closing_date; } else echo 'In Progress';?></td>
                      <td>
                      <?php if($objRow1->closed_by>0){ $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow1->closed_by)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name; } else echo 'N/A'; ?></td>
                     </tr>                            
<?php
			   }
      else:
			       echo '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
			endif;
?>                            
                </tbody>                       
              </table>           
                              
<?php
			  
                            			 ?>                          
              <input type="hidden" name="status" value="1" />                       
              <input type="hidden" name="task" value="modifyjoblocation" />                   
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
   }  // End of Function    
   
   
   
protected function admin_completedProperties($objRs)
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
			<a href="#">Manage Jobs</a>
		</li>
		<li class="current">
			<a href="#" title="">Completed Jobs</a>
		</li>
	</ul>
</div>
			     
    <!--=== Normal ===--> 				     
    <div class="row">					       
      <div class="col-md-12">						         
        <h4 class="text-left heding_6">Completed Jobs</h4>						         
        <div class="widget box box-vas">							 							           
          <div class="widget-content widget-content-vls">                                             
            <form method="post" name="frmListing"> 
            <div class="col-md-12 text-right" style="padding-bottom:10px">
            
              <span>
                <a href="<?php echo ISP :: AdminUrl('property/reset-all-property/');?>" onclick="return confirm('Are you sure, you want to Reset All results?\n\nThis step can not be Undone. Please use carefully.');" class="btn btn-danger btn-ms">Reset All</a>									               
                <a href="javascript: void(0);" onclick="exportTableToCSV.apply(this, [$('#dataTables-example'), 'export-joblocations.csv']);" class="btn btn-info">Export</a>									               
              </span> 
            </div>                                                 
              <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                                     
                <thead class="cf">							                   
                  <tr>                                     
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Location</th>								                     
                    <th align="center" data-hide="phone">Address</th>                                     
                    <th align="center" data-hide="phone">Assigned To</th>                                     
                    <th align="center" data-hide="phone">Phone Number</th>                                     
                    <th align="center" data-hide="phone">Important Notes</th>                                     
                    <th align="center" data-hide="phone">Assigned Location</th>                                                    
                                                         
                    <th align="center" data-hide="phone" class="hideexport">Completion Date</th>                                                                                                                                                                                                                                                                                               
				            <th align="center" data-hide="phone" class="hideexport">Action</th>       
                  </tr>						                 
                </thead>						                 
                <tbody>                       
<?php
			
			if($objRs): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
				
                                    			?>         			                   
                  <tr>
                  <td><?php echo $intI-1;?></td>
                      <td><?php echo $objRow->job_listing;?></td> 
                      <td><?php echo $objRow->location_address;?></td>
                      <td><?php
                      $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->assigned_to));
                      echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?>
                      </td>
                      <td><?php echo $objRow->phn_no;?></td> 
                      <td><?php echo $objRow->importent_notes;?></td>
                      <td><?php
                      echo $this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRow->location_id));
                      ?>                      
                      </td>                                    
                     <td align="center" class="hideexport"><?php echo $objRow->completion_date;?></td>
                     <td align="center" class="hideexport"><a href="<?php echo ISP :: AdminUrl('property/reset-property/id/'.$objRow->id);?>">Reset</a></td>                               
                   </tr>                            
<?php
			   }
?>                            
                </tbody>                       
              </table>             
                              
<?php
			 else:
			       echo '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
                            			 ?>                          
              <input type="hidden" name="status" value="1" />                       
              <input type="hidden" name="task" value="modifyjoblocation" />                   
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
   }  // End of Function    
   
   
protected function loadLocationProperties($objRs, $locationId)
{
?>
<div id="content">
	<div class="container">
        <div style="margin: 0px 0px 20px -15px;">
            <a href="javascript: window.history.go(-1);" style="text-decoration:none;">
                <button id="btn-load" class="btn btn-primary btn-info"><i class="icon-chevron-left"></i>&nbsp;Go Back</button>
            </a>
        </div>    
    <div class="row mid_text">
        <div class="col-md-12">
        <h3>
            <?php
            if($locationId<>'')
               echo $this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$locationId));
            else
             echo 'Search Result(s)';
            ?>        
        </h3>
		</div>
	</div>

				<div class="row">
				<div class="col-md-12 nopadding">
					<div class="widget_staff">
          <?php
           while($objRecordSet = $objRs->fetch_object())
           {
          ?>
						<div class="box_inner" <?php if($objRecordSet->priority_status==1) {?>style="box-shadow: inset -9px -12px 225px -6px rgba(77,116,150,1);"<?php }?>>
							 <div class="col-md-4 col-lg-3 col-sm-6">	
							 <div class="img_holder2">
                             <?php if($objRecordSet->map_widget == '') $objRecordSet->map_widget = 'no_map_available.jpg'; ?>
							 	<img src="<?php echo SITE_URL.'upload/'.$objRecordSet->map_widget;?>" style="max-width:272px; max-height:150px;">
							 </div>
							</div>

							<div class="col-md-8">
								<div class="col-md-12 nopadding">
                                    <h2 class="label-staff col-md-4 nopadding"><strong>Property:</strong></h2>
                                    <h2 class="label-staff-detail col-md-8"><?php echo $objRecordSet->job_listing;?></h2>
                                    <div class="clearfix"></div>

                                    <h2 class="label-staff col-md-4 nopadding"><strong>Address:</strong></h2>
                                    <h2 class="label-staff-detail col-md-8"><?php echo $objRecordSet->location_address;?></h2>
                                    <div class="clearfix"></div>

                                    <h2 class="label-staff col-md-4 nopadding"><strong>Manager Phone:</strong></h2>
                                    <h2 class="label-staff-detail phone col-md-8">
                                    <a href="tel:<?php echo $objRecordSet->phn_no;?>">
                                    <?php echo $objRecordSet->phn_no;?>
                                    </a>
                                    </h2>
                                    <div class="clearfix"></div>                   


                                                    <h2 class="label-staff col-md-4 nopadding"><strong>GPS Points:</strong></h2>
                                    <h2 class="label-staff-detail gps-points col-md-8">
                                    <a href="http://maps.google.com/maps?q=<?php echo $objRecordSet->lat.','.$objRecordSet->lag;?>" target="_blank">
                                    <?php echo $objRecordSet->lat.', '.$objRecordSet->lag;?>
                                    </a>
                                    </h2>
                                    <div class="clearfix"></div> 
                                                    
                                    <h2 class="label-staff col-md-4 nopadding"><strong>Special Notes:</strong></h2>                  
                                    <h2 class="label-staff-detail col-md-8"><?php echo $objRecordSet->importent_notes;?></h2>
                                    <div class="clearfix"></div>

								</div>			

							<div class="clearfix"></div>
							</div>
                            <a href="<?php echo SITE_URL;?>index.php?dir=property&task=view&id=<?php echo $objRecordSet->id;?>">
                                <button class="btn btn-info view_btn">View</button>
                            </a>
                            <div class="clearfix"></div>	
						</div>
            <?php
            }
            ?>

					<div class="clearfix"></div>
					</div>
				</div>
				</div> <!-- row -->    
  </div>
</div>
<?php      
}   
   
protected function Front_View($objRecordSet)
   {
  ?>
<link rel="stylesheet" type="text/css"  href="<?php echo SITE_URL ?>assets/css/lightslider.css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="<?php echo SITE_URL ?>assets/js/lightslider.js"></script> 
<script>
$(document).ready(function() 
{
    $("#content-slider").lightSlider({
        loop:true,
        keyPress:true
    });
});

function attachPhotos()
{
    $("#imgprogress").show();
    $('#uploadForm').submit();
}

function reloadPage()
{
    window.location.reload();
}   
</script>  
  <div id="content">			   
  <div class="container">         
    <div class="row">
    <h1>
    <a href="javascript: window.history.go(-1);" style="text-decoration:none; margin-right:10px;">
    <button id="btn-load" class="btn btn-primary btn-info"><i class="icon-chevron-left"></i>&nbsp;Go Back</button>
    </a>
    <?php echo $objRecordSet->job_listing;?></h1>
    <div class="col-md-12">
			<div style="text-align:left;">
				<a data-lightbox="property_2" href="<?php echo SITE_URL.'upload/'.$objRecordSet->map_widget;?>"><img src="<?php echo SITE_URL.'upload/'.$objRecordSet->map_widget;?>" style="max-width:100%;"></a>
			</div>
		</div>
   
    <div class="col-md-12" style="margin-top:10px;"> 			
      <div class="col-md-6 yellowbg">
			 <div class="col-md-12">
                	<h2 class="col-md-4 label-staff nopadding-left"><strong>Name:</strong></h2>
                  <h2 class="label-staff-detail col-md-8 nopadding"><?php echo $objRecordSet->job_listing;?></h2>
                  <div class="clearfix"></div>
									<h2 class="col-md-4 label-staff nopadding-left"><strong>Location:</strong></h2>
                  <h2 class="label-staff-detail col-md-8 nopadding">
                  <?php
                  echo $this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRecordSet->location_id));
                  ?>
                  </h2>
                  <div class="clearfix"></div>
                  <h2 class="col-md-4 label-staff nopadding-left"><strong>Address:</strong></h2>
                  <h2 class="label-staff-detail nopadding col-md-8">
                  <?php echo $objRecordSet->location_address;?>
                  </h2>
                  <div class="clearfix"></div>
				 <h2 class="col-md-4 label-staff nopadding-left"><strong>Assigned To:</strong></h2>
                  <h2 class="label-staff-detail nopadding col-md-8">
                   <?php
                      $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRecordSet->assigned_to));
                      echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?>
                  </h2>
                  <div class="clearfix"></div> 
                  <h2 class="col-md-4 label-staff nopadding-left"><strong>Onsite Contact Person:</strong></h2>
                  <h2 class="label-staff-detail nopadding col-md-8">
                   <?php echo $objRecordSet->onsite_contact_person;?>
                  </h2>
                  <div class="clearfix"></div>
                  <h2 class="col-md-4 label-staff nopadding-left"><strong>Onsite Contact Phone Number:</strong></h2>
                  <h2 class="label-staff-detail nopadding col-md-8">
                  <a href="tel:<?php echo $objRecordSet->phn_no;?>">
                  <?php echo $objRecordSet->phn_no;?>
                  </a>
                  </h2>
                  <div class="clearfix"></div>
									<h2 class="col-md-4 label-staff nopadding-left"><strong>GPS Points:</strong></h2>              
									<h2 class="label-staff-detail nopadding gps-points">
                  <a href="http://maps.google.com/maps?q=<?php echo $objRecordSet->lat.','.$objRecordSet->lag;?>" target="_blank">
                  <?php echo $objRecordSet->lat.', '.$objRecordSet->lag;?>
                  </a>
                  </h2>
				</div>	
			</div>
      
      <div class="col-md-6 bluebg">
  			<div class="col-md-12">
        <h4>Important Notes</h4>
        <?php echo $objRecordSet->importent_notes;?>
        <div class="clearfix"></div>         
        </div>	
			</div>
      <div class="clearfix"></div>
       <div class="col-md-12 nopadding">
        <div class="col-md-6 nopadding propertystatus">
          <div class="col-md-6 nopadding text-center startbutton">
         <?php
          if($objRecordSet->progress==0)
          {
         ?>
          <a href="<?php echo SITE_URL;?>index.php?dir=property&task=start&s=1&id=<?php echo intval($_GET['id']);?>">
           <button type="button" class="btn btn-info">Start</button>
          </a>
         <?php
         }
         elseif($objRecordSet->progress==1)
         { 
         ?>
         Started on <?php echo $objRecordSet->start_date;?><br/>
         <a href="<?php echo SITE_URL;?>index.php?dir=property&task=start&s=2&id=<?php echo intval($_GET['id']);?>">
           <button type="button" class="btn btn-info">Complete</button>
          </a>
         <?php
          }
          elseif($objRecordSet->progress==2)
          { 
         ?>
         Completed on <?php echo $objRecordSet->completion_date;?><br/>
         <a href="<?php echo SITE_URL;?>index.php?dir=property&task=restart-job&s=1&id=<?php echo intval($_GET['id']);?>">
           <button type="button" class="btn btn-info">Restart</button>
          </a>
         <!--button type="button" class="btn btn-info">Completed on <?php echo $objRecordSet->completion_date;?></button>-->
         <?php
          }
         ?> 
          </div>
          <div class="col-md-6 nopadding attachphotos text-center">
          <div id="imgprogress" style="display:none; position:absolute; top:0; left:0; width:100%; height:90px; background:#666; color:#fff; text-align:center;">
           Uploading.....
          </div>
          <form id="uploadForm" action="<?php echo SITE_URL;?>index.php" method="post" enctype="multipart/form-data">
          	  <input type="hidden" name="dir" value="property">
	          <input type="hidden" name="task" value="uploadphotos">
	          <input type="hidden" name="pid" value="<?php echo intval($_GET['id']);?>">
	          <a href="javascript:;" id="attachicon">
	            <i class="fa fa-camera"></i><br/>
	            <span>Attach Photos</span>
	          </a>
	           <input type="file" onchange="attachPhotos();" id="ajggallery" accept="image/*" multiple name="gallery[]" style="opacity:0; position:absolute; top:0; right:0; height:200px; width:100%;">          </form>
          <iframe id="uploadframe" action="<?php echo SITE_URL;?>index.php?dir=property&task=uploadphotos" name="uploadframe" style="display:none;"></iframe>
          </div>
        </div>
        <div class="col-md-6 nopadding propertygallery item">
         <ul id="content-slider" class="col-md-12 nopadding content-slider">
             <?php
             $arrImg = explode(",", $objRecordSet->gallery);
             $arrStaffImg = explode(",", $objRecordSet->user_gallery);
             foreach($arrImg as $galImage)
             {
               if($galImage<>'') {
            ?>
             <li>
              <a class="fancybox" data-fancybox-group="gallery" href="<?php echo SITE_URL;?>upload/<?php echo $galImage;?>">
               <img src="<?php echo SITE_URL;?>upload/<?php echo $galImage;?>" style="max-width:100%;"  border="0">
              </a>
             </li>
            <?php
               }
             }
             
             foreach($arrStaffImg as $galImage)
             {
               if($galImage<>'') {
            ?>
             <li>
              <a class="fancybox" data-fancybox-group="gallery" href="<?php echo SITE_URL;?>upload/<?php echo $galImage;?>">
               <img src="<?php echo SITE_URL;?>upload/<?php echo $galImage;?>" style="max-width:100%;"  border="0">
              </a>
             </li>
            <?php
               }
             }
            ?>
          </ul> 
        </div>
       </div>
       <div class="clearfix"></div>
       
       
         <?php
         if($objRecordSet->enabled_reports<>'') { 
         $objReports = $this->objFunction->iFindAll(TBL_REPORTS, '', ' order by report_name asc', ' report_id in ('.$objRecordSet->enabled_reports.')');   
         if($objReports){
         ?>
        <div class="col-md-12 nopadding staff_section">
        <h4>ALL REPORTS FOR THIS PROPERTY</h4>
        <ul class="property_reports col-md-12 nopadding"> 
        <?php
        foreach($objReports as $reportArray)
          {
            $reptObj =  $this->objFunction->getPropertyReportStatus($objRecordSet->id, $reportArray->report_id);
            if($reportArray->report_id<>'') {
         ?>
          <li class="vcenter col-md-12 nopadding">
            <a href="<?php echo sefToAbs('index.php?dir=reports&task=show-form&id='.$reportArray->report_id.'&location='.$objRecordSet->location_id.'&prop='.$objRecordSet->id);?>">
							<?php echo $reportArray->report_name;?>
						</a>
            
             <?php if($reptObj->id=='') {?> 
            <span style="line-height:40px; color:#ff0000;">Not Reported</span>
             <?php } else { ?>
             <span style="color:#547a59;"><b>Reported</b><br>Last on <?php echo date('m/d/Y', strtotime($reptObj->submission_date));?></span>
             <?php } ?>  
            
          </li>  
         <?php
              }
           }
          ?>
        </ul>
       </div>
       <?php } 
       }
       ?>
		</div>
    
    </div>
    </div>
    </div>
    <p>&nbsp;</p>
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/libs/jquery-1.10.2.min.js"></script>    
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/jquery.fancybox.js"></script>   
<link href="<?php echo SITE_URL;?>assets/css/jquery.fancybox.css" rel="stylesheet">  

<script type="text/javascript">
$(document).ready(function() {
	$('.fancybox').fancybox();
});
</script>    
  <?php 
   }
} // End of Class
?>