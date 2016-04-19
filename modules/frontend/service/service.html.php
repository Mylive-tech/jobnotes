<?php
/**
 * @Purpose: Class for HTML view of the Content Module
*/
class SERVICE_HTML_CONTENT
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
  protected function admin_service_Form($objRs)
   {    
   ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>   
<script type="text/javascript">
 function loadStates(cname)
 {
  $("#state_ajax").html('Loading.....'); 
   $.ajax({
            method: "POST",
            url: "<?php echo ISP :: AdminUrl('service/load-states/');?>",
            data: { cname: cname }
         })
      .done(function( datahtml ) {
        $("#state_ajax").html(datahtml);
      });
 }
<?php
$arrTerr = '';
$objTerrio = $this->objFunction->iFindAll(TBL_SERVICE,'',' group by territory order by territory asc');
foreach($objTerrio as $objRecrodset)
{
    $arrTerr[] = $objRecrodset->territory;
}

$arrBranch = '';
$objBranch = $this->objFunction->iFindAll(TBL_SERVICE,'',' group by branch order by branch asc');
foreach($objBranch as $objRecrodset)
{
    $arrBranch[] = $objRecrodset->territory;
}
?> 
$(function() {
    var availableTerritory = ["<?php echo implode('","', $arrTerr);?>"];
    $( "#md_territory" ).autocomplete({
      source: availableTerritory
    });
    
    var availableBranchs = ["<?php echo implode('","', $arrBranch);?>"];
    $( "#md_branch" ).autocomplete({
      source: availableBranchs
    });
    
    
  }); 
</script>   
<div id="content">
  <div class="container">   
<div class="crumbs">
      <ul id="breadcrumbs" class="breadcrumb">
      	<li>
      		<i class="icon-home"></i>
      		<a href="<?php echo ISP :: AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>
      	</li>
        <li>
      		<i class="current"></i>
      		<a href="#">Manage Jobs</a>
      	</li>
        <li>
      		<i class="current"></i>
      		<a href="<?php echo ISP::AdminUrl('service/manage-locations/');?>">Manage Locations</a>
      	</li>
      	<li class="current">
      		<a href="#" title="">Add New Location</a>
      	</li>
      </ul>
      </div>
 
    <div class="row">
      <div class="col-md-12">   
        <h4 class="text-left heding_6"><?php if(intval($objRs->id)>0) echo 'Edit'; else echo 'Add New';?> Location</h4>
        <div class="widget box box-vas">
          <div class="widget-content formTableBg widget-content-vls">
            <div class="form_holder">      
              <form name="frmContent" method="post" onsubmit="return validateFrm(this);" enctype="multipart/form-data">		
                <div class="form-group">			    
                  <label for="exampleInputEmail1">Name of Location
                  </label>			    
                  <input type="text" name="md_name" class="form-control" value="<?php echo $objRs->name; ?>" placeholder="Enter Location Name Here"> 			
                </div>            	
              
              
               <div class="form-group nopadding search_selection1 col-md-3">			    
                  <label for="exampleInputEmail1">Country
                  </label>			    
                  <select name="md_country" id="md_country" class="form-control" onchange="loadStates(this.value);"> 
                   <?php echo $this->objFunction->country($objRs->country);?>
                  </select>			
                </div>
                
                 <div class="form-group search_selection2 col-md-3">				
                  <label>State</label>	
                  <div id="state_ajax">		
                  <select class="form-control" name="db_state">    
                    <?php 
                    $stateArr=$this->objFunction->iFindAll(TBL_STATE,array('state_name'=>$objRs->state));
                    	foreach($stateArr as $stateObj)
                      {
                    ?>    
                    <option value="<?php  echo $stateObj->state_name?>" <?php if($objRs->state==$stateObj->state_name) echo 'selected';?>><?php  echo $stateObj->state_name?></option>    
                    <?php } ?>    
                  </select>
                  </div>				  				 			
                  <div class="clearfix">
                  </div>			
                </div>
                
                <div class="form-group  search_selection2 col-md-3">			    
                  <label for="exampleInputEmail1">Territory
                  </label>			    
                  <input type="text" class="form-control" name="md_territory" id="md_territory" value="<?php echo $objRs->territory;?>">
                  		
                </div>
                
                 <div class="form-group nopadding col-md-3">			    
                  <label for="exampleInputEmail1">Branch
                  </label>			    
                  <input type="text" class="form-control" name="md_branch" id="md_branch" value="<?php echo $objRs->branch;?>">
                 </div> 		
                                
                <div class="clearfix"></div> 	
                
                <div class="form-group nopadding col-md-6">				
                  <label>Upload Image</label>				  				 
                  <input type="file" class="form-control" name="db_image" id="db_image" accept="image/*">
                  <?php if($objRs->image<>'') { ?>
                  <img src="<?php echo SITE_URL;?>upload/<?php echo $objRs->image;?>" border="0" width="100" height="100">         			
                 <?php } ?>
                </div>			
                
                <div class="form-group col-md-6">
                    <label></label>
                  <label class="checkbox">				  				 
                    <input type="checkbox" <?php if ($objRs->enable_image == 1) echo 'checked';?> class="uniform" onclick="if($(this).is(':checked')) {$('#db_enable_image').val(1);} else {$('#db_enable_image').val(0);}" name="chk" id="chk" value="1">
                    <b>Show Image in Front-end</b>
                  </label>
                  <input type="hidden" value="<?php echo intval($objRs->enable_image);?>" name="db_enable_image" id="db_enable_image">
                </div>			
                <div class="clearfix"></div> 
                
                		
                <div class="form-group nopadding search_selection1">				
                  <label>Priority</label>				  				 
                  <select name="db_priority" class="form-control">                            
                    <option value="normal-listing">Standard Priority</option>                            
                    <option value="priority">Priority</option>
                    <option value="top-priority">Top Priority</option>                            
                  </select>                            
                  <input type="hidden" name="task" value="saveservice" />        
                  <input type="hidden" name="id" value="<?php echo $objRs->id;?>" />         			
                  <div class="clearfix">
                  </div>			
                </div>
                <div class="form-group nopadding search_selection2">			    
                  <label for="exampleInputEmail1">Comments</label>			    
                  <textarea class="form-control" name="db_comments" id="db_comments"><?php echo $objRs->comments;?></textarea>
               </div>
               <div class="clearfix"></div> 			 
                <button type="submit" name="save_new" value="new" class="btn btn-default section_button ">Save & Add New
                </button>		
                <button type="submit" name="save_back" value="back"  class="btn btn-default pull-right section_button pull-right">Save & Go Back
                </button>
                <input type="hidden" name="db_site_id" value="<?php echo $_SESSION['site_id'];?>">	 				  		
              </form>			   		    
              <div class="clearfix"></div>  			 
            </div>   
            <div class="clearfix">
            </div>   
          </div>   	 			  			
        </div>			
        <!-- /.container -->		
      </div>
    </div>
  </div>
</div>
<?php 
   }  // End of Function 
   
   protected function admin_state_Form($objRs)
   {    
   ?>   
<div class="edit_form">   
  <div class="col-md-12"><h2>Add New State</h2>   		
    <form name="frmContent" method="post" onsubmit="return validateFrm(this);" >		 
      <div class="form-group col-md-6 nopadding search_selection">					    	
        <label for="exampleInputEmail1">Add this state operations
        </label>   							  
        <select class="form-control" name="md_name" size="1">
<?php 
				  $stateObj=$this->objFunction->iFindAll(TBL_STATE);
                  foreach($stateObj as $state)
                  { 
				    if($state->name<>''){
                                                  				  ?>                                                             
          <option value="<?php echo $state->id; ?>"                      
          <?php if($objRs->name==$state->name) echo 'selected';?> >                     
          <?php echo $state->name; ?>                     
          </option>                    
<?php                   } 
                  } 
          ?> 
        </select>					    	 					  	
        <div class="clearfix">
        </div>					  	
      </div>					  	
      <div class="form-group col-md-6 nopadding pull-right search_selection">					    	
        <label for="exampleInputEmail1">State Incharge Name
        </label>                             
        <select class="form-control" name="md_incharge_name">    
<?php $category=$this->objFunction->iFindAll(TBL_STAFF);
          	foreach($category as $categories){?>    
          <option value="<?php  echo $categories->id?>">
          <?php  echo $categories->f_name?>  
          <?php  echo $categories->l_name?>
          </option>    
          <?php } ?>    
        </select>					    	 					   	
        <div class="clearfix">
        </div>					  	
      </div>						 
      <input type="hidden" name="task" value="savestate" />        
      <input type="hidden" name="id" value="<?php echo $objRs->id;?>" />          					  	
      <button type="submit" name="save" class="btn btn-default section_button col-xs-6">SUBMIT & ADD ANOTHER
      </button>					  	  
      <button type="submit" class="btn btn-default pull-right section_button col-xs-6">SUBMIT & GO BACK
      </button>		
    </form>   
    <div class="clearfix">
    </div>   
  </div>   
</div>       
<?php 
   }
   
protected function admin_serviceListing($objRs){?><script type="text/javascript">
$(document).ready(function() {
    $('.datatable').dataTable( {
        "order": [[ 1, "asc" ]]
    } );} );
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
			<a href="#" title="">Manage Locations</a>
		</li>
	</ul>
</div>
       			
    <!--=== Normal ===-->				
    <div class="row">					
      <div class="col-md-12">						
        <h4 class="text-left heding_6">Manage Locations</h4>						
        <div class="widget box">							 							
                                                    
            <form method="post" name="frmListing"> 
            <div class="widget-header">
            <div class="col-md-12 text-right" style="padding:5px 0px 7px 0px">                       
             <span style="margin-right:10px; border-right:1px solid #ccc; padding-right:10px;">										
                <a href="<?php echo ISP :: AdminUrl('service/add-location/');?>" class="btn btn-info">Add New Location</a>									
              </span> 
              
              <input type="submit" class="btn btn-info btn-success btn-ms" name="btn_Publish" value="Publish" onclick="document.frmListing.status.value='1';" />             
              
              <input type="submit" class="btn btn-info btn-warning btn-ms" name="btn_UnPublish" value="Unpublish" onclick="document.frmListing.status.value='0';" />             
              <input type="submit" class="btn btn-info btn-danger btn-ms" name="btn_delete" value="Delete" onclick="document.frmListing.status.value='-1';" />                
              
              <span>										
                <a href="javascript: void(0);" onclick="exportTableToCSV.apply(this, [$('#dataTables-example'), 'export-sections.csv']);" class="btn btn-info">Export</a>									
              </span>          
             </div>
            </div>
             
             <div class="widget-content no-padding">               
              <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                   
                <thead class="cf">							
                  <tr>
                  <th align="center" class="checkbox-column hideexport">
                  <!--<input type="checkbox" name="del[]" onclick="selectDeselect('delete[]');"/> Select-->
                  <input type="checkbox" class="uniform">
                  </th>                                
                    <th align="center">ID</th>								
                    <th align="center" data-class="expand">NAME</th>
                    <th align="center" data-hide="phone">STATE</th>
                    <th align="center" data-hide="phone">COUNTRY</th>	
                    <th align="center" data-hide="phone">TERRITORY</th>	
                    <th align="center" data-hide="phone">BRANCH</th>								
                                           
                    <th align="center" data-hide="phone" class="hideexport">Status</th>                                
                    <th align="center" data-hide="phone" class="hideexport">EDIT</th>                                
                    							
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
                  <td align="center" class="checkbox-column hideexport">
                  <input type="checkbox"  class="uniform" name="delete[]" value="<?php echo $objRow->id;?>"  /></td>
                  <td><?php echo $intI-1; //$objRow->id;?></td>
                  <td>                  <?php $location_properties = $this->objFunction->getPropertiesByLocation($objRow->id); ?>                  <a href="javascript: void(0);" title="<?php echo PHP_EOL.'Properties:'.PHP_EOL.PHP_EOL; $propCount=0; foreach ($location_properties as $prop) { $propCount++; echo $propCount.'. '.$prop.PHP_EOL.PHP_EOL;}?>">                  <?php echo $objRow->name;?></a></td>
                  <td align="left"><?php echo $objRow->state;?></td> 
                  <td align="left"><?php echo $objRow->country;?></td>
                  <td align="left"><?php echo $objRow->territory;?></td> 
                  <td align="left"><?php echo $objRow->branch;?></td>                                
                                              
                  <td align="left" class="hideexport">
                  <a href="<?php echo ISP :: AdminUrl('service/modifyservice/delete/'.$objRow->id.'/status/'.$strStatus);?>">
                  <i class="fa fa-toggle-<?php if($objRow->status==1) echo 'on'; else echo 'off';?>"></i>
                  </a>
                  <!--<img src="<?php echo SITE_IMAGEURL.$strStatus;?>" />-->
                  </td>                          
                  <td align="left" class="hideexport"><a href="<?php echo ISP :: AdminUrl('service/edit-location/id/'.$objRow->id);?>">
                  <i class="fa fa-pencil-square-o"></i></a> </td>          
                            
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
              <input type="hidden" name="task" value="modifyservice" /> 
               </div>    
            </form>						 							
          						
        </div>					
      </div>				
    </div>				
    <!-- /Normal -->			
  </div>			
  <!-- /.container -->		
</div>       
<?php 
   }
     protected function admin_stateListing($objRs)
   {
   ?>    
<div class="row">                
  <div class="col-lg-8">                    
    <h1 class="page-header">STATES</h1>                
  </div>                
  <!-- /.col-lg-12 -->            
</div>   
<div class="row">                
  <div class="col-lg-12">                    
    <div class="panel panel-primary">                        
      <div class="panel-heading">                       STATES                         
      </div>                        
      <!-- /.panel-heading -->                        
      <div class="panel-body">                            
        <div class="table-responsive">                                
          <form method="post" name="frmListing">                                    
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">                                    
              <thead>												
                <tr>                                                   
                  <th align="center">S.N.
                  </th>												   
                  <th align="center">STATE NAME
                  </th>												   
                  <th align="center">STATE INCHARGE
                  </th>                                                   
                  <th align="center">PHONE
                  </th>                                                   
                  <th align="center">STATUS
                  </th>                                                   
                  <th align="center">EDIT
                  </th>                                                   
                  <th align="center">
                    <input type="checkbox" name="del[]" onclick="selectDeselect('delete[]');"/>              Select
                  </th>												
                </tr>						
              </thead>						
              <tbody>                      
<?php
			
			if($objRs): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {
			    $strStatus = ($objRow->status)?'activepage.jpg':'inactivepage.gif';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
				
                			?>        			
                <tr>            <td>
                    <?php echo $intI-1;?></td>            <td>
                    <?php echo $objRow->name;?></td>                           
                  <td align="center">
<?php echo  $inchargeName= $this->objFunction->iFind(TBL_STAFF,'f_name',array('id'=>$objRow->incharge_name)); 
			
                    			?></td>            
                  <td align="center">
                    <?php echo $this->objFunction->iFind(TBL_STAFF,'phone',array('id'=>$objRow->incharge_name));?></td>            
                  <td align="center">
                    <img src="<?php echo SITE_IMAGEURL.$strStatus;?>" /></td>            
                  <td align="center"> 
                    <a href="<?php echo ISP :: AdminUrl('service/edit-state/id/'.$objRow->id);?>">
                      <img src="<?php echo SITE_IMAGEURL;?>editicon.gif" /></a> </td>          
                  <td align="center">
                    <input type="checkbox" name="delete[]" value="<?php echo $objRow->id;?>"  /></td>          
                </tr>                           
<?php
			   }
                			   ?>          
              </tbody>        
            </table>            
          
            <input type="submit" class="btn btn-warning btn-xs" name="btn_UnPublish" value="Unpublish" onclick="document.frmListing.status.value='0';" />              &nbsp;              
            <input type="submit" class="btn btn-success btn-xs" name="btn_Publish" value="Publish" onclick="document.frmListing.status.value='1';" />              &nbsp;              
            <input type="submit" class="btn btn-danger btn-xs" name="btn_delete" value="Delete" onclick="document.frmListing.status.value='-1';" />                
                             
<?php
			 else:
			       echo '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
            			 ?>          
            <input type="hidden" name="status" value="1" />        
            <input type="hidden" name="task" value="modifystate" />      
          </form>						 	
        </div>       
      </div>             
    </div>                        
    <!-- /.panel-body -->                    
  </div>                    
  <!-- /.panel -->                
</div>       
<?php 
   }
  
} // End of Class
?>