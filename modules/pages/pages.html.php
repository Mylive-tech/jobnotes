<?php
/**
 * @Purpose: Class for HTML view of the Content Module
*/
class HTML_CONTENT
{
   private $objFunction;   
   public function __construct($objfunc) //define Constructor
   {
     $this->objFunction = $objfunc;		
   }

   protected function technicalEnquiryForm($objRs)
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
			<a href="#">About & Extras</a>
		</li>
    <li>
			<i class="current"></i>
			<a href="#">Technical Support</a>
		</li>
		
	</ul>
</div>  
      
    <div class="row">      
      <div class="col-md-12">        
        <h4 class="text-left heding_6">Technical Support Form</h4>            
        <div class="add-new-job-l widget box box-vas">                 
          <div class="widget-content formTableBg widget-content-vls">                       
            <div class="form_holder">                           
              <form name="frmContent" method="post" onsubmit="return validateFrm(this);" enctype="multipart/form-data">                                 
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Your Name*</label>                                    
                  <input type="text" class="form-control" required name="md_name" id="md_name" value="<?php echo $objRs->name;?>">                                  
                </div>
                
                <div class="form-group">
                    <div class="row">
											<div class="col-md-4">
                       <label for="exampleInputEmail1" class="font-Bold">Company Name*</label>
												<input type="text" required class="form-control" name="md_cmp_name" id="md_cmp_name" value="Snowplow Company" readonly>
											</div>
											<div class="col-md-4">
                      <label for="exampleInputEmail1" class="font-Bold">Phone Number*</label>
												<input type="text" required name="md_phone" class="form-control">
											</div>
											<div class="col-md-4">
                      <label for="exampleInputEmail1" class="font-Bold">Email Id*</label>
												<input type="text" required name="md_email" class="form-control">
											</div>
										</div>                                     
                                                      
                                                    
                </div>
                
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="font-Bold">License Info*</label>                                    
                  <input type="text" class="form-control" required name="md_lisc_info" id="md_lisc_info" value="ANQUI230158MGZU" readonly>                                  
                </div>
                
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Server Status Specs*</label>                                    
                  <textarea class="form-control" required name="md_server_specs" id="md_server_specs">System active</textarea>                                  
                </div>
                
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Comments*</label>                                    
                  <textarea class="form-control" required name="md_comments" id="md_comments" value="<?php echo $objRs->comments;?>"></textarea>                                  
                </div>
                
                <div class="form-group">                                     
                  <label for="exampleInputEmail1" class="font-Bold">Attach File</label>                                    
                  <input type="file" class="form-control" name="db_file" id="db_file"> 
                  <br>
                  <small>
                  (Formats allowed: .jpg, .gif, .png, .bmp, .tiff .pdf, .odp, .ppt, .pps, .pptx, .ppsx, .pot, .potx, .doc, .docx, .rtf, .odt, .txt, xls, .xlsx)
                  </small>                                 
                </div>
                
                <div class="clearfix"></div>                                 
                <button type="submit" name="save" class="btn btn-default sumit_bottom">Submit</button>
                
                <button type="reset" name="reset" class="btn btn-default sumit_bottom">Reset</button>
                <div class="clearfix"></div> 
                
                <div class="form-group" style="margin-top:20px;">
                 <p>Support Powered by Live-Tech</p>
                </div>
              </form>
              <p><strong>Hard drive usage :</strong> 
			  		<?php
					$dt = disk_total_space(getcwd());
					$df =  disk_free_space(getcwd());
					$du = $dt-$df;
					$dp = sprintf('%.2f',($du / $dt) * 100);
					$dt = number_format($dt / 1073741824, 2) . ' GB';
					$df = number_format($df / 1073741824, 2) . ' GB';
					$du = number_format($du / 1073741824, 2) . ' GB';
					?>
                    <style type='text/css'>
                .progress_bar {border: 2px solid #5E96E4;height: 36px;width: 540px;}
                .progress_bar .prgbar {background: #A7C6FF;width: <?php echo $dp; ?>%;position: relative;height: 32px;z-index: 999;}
                .progress_bar .prgtext {color: #286692;text-align: center;font-size: 13px;padding: 9px 0 0;width: 540px;position: absolute;z-index: 1000; }
                .progress_bar .prginfo { margin: 3px 0; }
                </style>
                <div class='progress_bar'>
                    <div class='prgtext'><?php echo $dp; ?>% Disk Used</div>
                    <div class='prgbar'></div>
                    <div class='prginfo'>
                        <span style='float: left;'><?php echo "$du of $dt used"; ?></span>
                        <span style='float: right;'><?php echo "$df of $dt free"; ?></span>
                        <span style='clear: both;'></span>
                    </div>
                </div>
                <div class="clear"></div>  </p>
              <p><strong>Memory usage :</strong> 
			  <?php  $mem_usage = memory_get_usage(false);
						if ($mem_usage < 1024)
						echo $mem_usage." Bytes";
						elseif ($mem_usage < 1048576)
						echo round($mem_usage/1024,2)." KB";
						else
						echo round($mem_usage/1048576,2)." MB"; ?>
             	</p>
              <p><strong>PHP Version :</strong>  <?php echo phpversion(); ?></p>
             </div>
            </div>
          </div>
        </div>
       </div>
      </div>
     </div>            
                
   <?php
   }
  
protected function show_Page($objRecord)
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
			<a href="#">About & Extras</a>
		</li>
    <li>
			<i class="current"></i>
			<a href="#"><?php echo $objRecord->page_title;?></a>
		</li>
		
	</ul>
</div> 
        
    <div class="row">      
      <div class="col-md-12">        
        <h4 class="text-left heding_6"><?php echo $objRecord->page_title;?></h4>            
        <div class="add-new-job-l widget box box-vas">                 
          <div class="widget-content widget-content-vls">
            <div class="form_holder">
            <?php
            $objActivePlugins = $this->objFunction->iFindAll(TBL_MODULES, array('status'=>1),' order by id asc');
             if($objRecord->id==1)
             {
             ?>
              <table class="table table-striped table-hover table-bordered table-responsive">
              <tr>
               <td>Registered To</td>
               <td><?php echo $this->objFunction->iFind(TBL_CONFIGURATION, 'config_value', array('config_key'=>'site_title'));?></td>
              </tr>
              <tr>
               <td>License Type</td>
               <td>Annual</td>
              </tr>
              <tr>
               <td>Expires</td>
               <td>05/12/2016</td>
              </tr>
              <tr>
               <td>Version</td>
               <td>1.0.0.3</td>
              </tr>
              <tr>
               <td>Active Plugins</td>
               <td>
               <?php
                foreach($objActivePlugins as $objPlugin)
                {
                  $pluginArr[]= $objPlugin->title;
                }
                echo implode(", ", $pluginArr);
               ?>
               </td>
              </tr>               
              </table>
             <?php
             }
             else
             {
                echo $objRecord->detailed_description;
             }    
            ?>
            </div>  
          </div>
        </div>
      </div>
     </div>
   </div>
 </div>          
          
          
<?php       
   }
   
} // End of Class
?>