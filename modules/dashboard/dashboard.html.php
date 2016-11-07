<?php
class DSHBOARD_HTML_CONTENT
{
   private $objFunction;
   private $objDatabase;
   
    public function __construct($objfunc) {
        $this->objFunction = $objfunc;		
        $this->objDatabase 	= new Database();
    }
  
    protected function show_Dashboard() {
		$autorefreshtime = $this->getTimeForWidgetAutoRefresh(); 
?>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="<?php echo SITE_JSURL;?>jquery.bxslider.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js"></script>
    <link rel="stylesheet" href="<?php echo SITE_CSSURL;?>jquery.bxslider.css" type="text/css" />
    <script type="text/javascript">
        $(document).ready(function(){       
            $("#widgetsorting .sortable" ).sortable({connectWith: '#widgetsorting .sortable'});
            $("#widgetsorting .sortable" ).disableSelection();

            $(".drag h4").each(function() {   
                $(this).on('click',function() {
                $(this).parent().toggleClass('closed');
                }
                );
            });
            $(".welcome-panel-close").on("click", function() {
                $(".welcome_message").hide();
                $(".dashboardheading").css( "margin-bottom", "30px" );
            });
        });
		function IvrlogchartRefresh(index, clas)
		{ 
			$("."+clas).html('<div class="rpt_loader" style="margin-bottom: 15%;"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
				$.ajax({url: '<?php echo ISP :: AdminUrl('dashboard/admin_ivrlogchart_refresh/?index=');?>'+index, success: function(result){$("."+clas).html(result);
				if(index == 1)
					drawVisualization();
					}
				});
		}
        function showlocactionInfo(currentlink, locationid) {
            $(".insidewidget_locationdetails").removeClass("show").addClass("hide");
            $("#p_"+locationid).removeClass("hide").addClass("show");
            $("a.padding10").parent().removeClass("activelink");
            currentlink.parent().addClass("activelink");
        }   
		
		//
		function getdetailsfromzip(wurl)
		{
			$(".weather").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
			$.ajax({url: wurl, 
					method: "POST",
					data: { zipcode: $('#zip').val()},
					success: function(result){ 
						$(".3_odd ").html(result);
					}
				});
				return false;
		}
		//
		$(function() {
			var SANAjax = function(){
			//Add here anticache modificator:
			//var rnd=Math.random();
			//$('#narrowSidebar').fadeOut("slow").load('ads/innerpage_120x600.php?nc='+rnd).fadeIn("slow");
			//alert("test 02nov2016");
			$("#refresh_loader").show();
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('dashboard/admin_dashboard_refresh/');?>', success: function(result){$("#content .container").html(result);
					drawVisualization();
					$("#refresh_loader").hide();
					}
				});
       		
			}
			setInterval(SANAjax, <?php echo $autorefreshtime->config_value;?>*1000 ); 
		});
		//
    </script>                      
<div id="content">			   
  <div class="container">           
    <h4 class="text-left heding_6 dashboardheading">Dashboard <img src="<?php echo SITE_URL.'/assets/img/refresh-loader.gif' ?>" id="refresh_loader" style="display:none;width:25px;float:right;" /></h4>	
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
        <div class="inside_widget col-md-12 padding10 widget-content">Welcome to JobNotes, we've assembled links to get you started quickly. You can also use the navigation on left to use this system.                             
          <ul class="col-md-12">                               
            <li>            
            <i class="icon-map-marker"></i>            
            <a href="<?php echo ISP :: AdminUrl('property/manage-properties/');?>">Manage Properties</a>            
            </li>                               
            <li>            
            <i class="icon-map-marker"></i>            
            <a href="<?php echo ISP :: AdminUrl('property/add-property/');?>">Add New Property</a>            
            </li>                               
            <li>            
            <i class="icon-map-marker"></i>            
            <a href="<?php echo ISP :: AdminUrl('service/manage-locations/');?>">Manage Locations</a>            
            </li>                               
            <li>            
            <i class="icon-map-marker"></i>            
            <a href="<?php echo ISP :: AdminUrl('service/add-location/');?>">Add New Location</a>            
            </li>                               
            <li>            
            <i class="icon-user"></i>            
            <a href="<?php echo ISP :: AdminUrl('staff/users-listing/');?>">Manage Users</a>            
            </li>                               
            <li>            
            <i class="icon-file-text"></i>            
            <a href="<?php echo ISP :: AdminUrl('reports/add-new-form');?>">Add New Reporting Form</a>            
            </li>                               
            <li>            
            <i class="icon-cogs"></i>            
            <a href="<?php echo ISP :: AdminUrl('setting/general-settings/');?>">System Configuration</a>            
            </li>                               
            <li>            
            <i class="icon-file-text"></i>            
            <a href="<?php echo ISP :: AdminUrl('reports/listing/');?>">See All Reports</a>            
            </li>                            
          </ul>                            
        </div>               
      </div> 
    </div>
     <?php //print_r($this->objFunction->widgetContentArray);
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
            ?>
    <div class="col-md-12 nopadding clock-in-out-widget" id="widgetsorting">
    <ul class="sortable nopadding col-md-12 piechart_details">
            <?php $i=1;
                foreach($even_content as $even_widget_value) {
                    if($i == 1)
                        echo '<li class="'.$i.'_even ui-state-default nopadding widget box col-md-12 col-sm-12 text-left">'.$even_widget_value.'</li>';                    
                    $i++;	
                }
            ?>
       <div class="overlay"></div><div class="loader"><img src="<?php echo SITE_URL.'assets/img/ajax-loading-input.gif'; ?>" /></div>
      </ul>
      </div>     
      <div class="col-md-12 nopadding" id="widgetsorting">
                <div class="col-md-6 col-sm-12 nopadding">
                    <ul class="sortable nopadding col-md-12">
                    <?php $i=1;
                        foreach($odd_content as $odd_widget_value) {
                            echo '<li class="'.$i.'_odd ui-state-default nopadding col-md-12 col-sm-12 widget box">'.$odd_widget_value.'</li>';
							$i++;                    
                        }
                    ?>    
                    </ul>
                </div>
                <div class="col-md-6 col-sm-12 text-right rightsidewidget" style="padding-right:0px;">
                    <ul class="sortable nopadding col-md-12">
                    <?php $i=1;
                        foreach($even_content as $even_widget_value) {
							if($i != 1)
                            	echo '<li class="'.$i.'_even ui-state-default nopadding widget box col-md-12 col-sm-12 text-left">'.$even_widget_value.'</li>';                    
							$i++;	
                        }
                    ?>
                    </ul>
                </div>
        </div>                                          
    </div>			  
</div>			 
<!-- /.container --> 
</div>
<script type="text/javascript">
/*$('.bxslider').bxSlider11({
  mode: 'vertical',
  slideMargin: 5
}); */
//$(".inside_widget_greenheading").click(function()
$(document).on("click",".inside_widget_greenheading", function()
{
   $("#completedbox").toggle();
   $("#untouchedbox").hide();
   $("#inprogressbox").hide();
   $("#pausedbox").hide();
});
//$(".inside_widget_progressheading").click(function()
$(document).on("click",".inside_widget_progressheading", function()
{
   $("#completedbox").hide();
   $("#untouchedbox").hide();
   $("#inprogressbox").toggle();
   $("#pausedbox").hide();
});
//$(".inside_widget_redheading").click(function()
$(document).on("click",".inside_widget_redheading", function()
{
   $("#completedbox").hide();
   $("#untouchedbox").toggle();
   $("#inprogressbox").hide();
   $("#pausedbox").hide();
});
//$(".inside_widget_pauseheading").click(function()
$(document).on("click",".inside_widget_pauseheading", function()
{
   $("#completedbox").hide();
   $("#untouchedbox").hide();
   $("#inprogressbox").hide();
   $("#pausedbox").toggle();
});
</script>       
<?php }
  
protected function show_staff_dashboard($objRs)
  {
?>    		 
<div id="content">			   
  <div class="container">				 				     
    <div class="row row-bg staff_section propertyouterblock">        
      <!-- .row-bg -->          
<?php 
        if($objRs)
        { // Check for the resource exists or not
				   $intI=1;
			 
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
            	{
?> 					       
      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 propertyblock">						         
        <div class="statbox widget box box-shadow">							           
          <div class="widget-content">	
            <?php if($objRow->enable_image == 1) { ?>          
                <div class="img_holder">								               
                  <a href="<?php echo SITE_URL;?>index.php?dir=property&task=location-properties&id=<?php echo $objRow->id;?>">                                             
                    <img src="<?php echo SITE_URL.'upload/'.$objRow->image;?>" style="max-height:112px;">                  
                  </a>								               
                  <div class="clearfix">              
                  </div>								             
                </div> 
            <?php } ?>
            <div class="text_overimg">
            <a href="<?php echo SITE_URL;?>index.php?dir=property&task=location-properties&id=<?php echo $objRow->id;?>">
            <?php echo $objRow->name; ?>
            </a>
            </div>
<?php
if ($objRow->show_locations_home == 1) {
	$strsql="SELECT * from ".TBL_JOBLOCATION." where location_id= '".$objRow->id. "' and site_id='".$_SESSION['site_id']."' and status=1 order by id asc limit 4";  //and assigned_to='".$_SESSION['adminid']."'
	$objSet =  $this->objDatabase->dbQuery($strsql);
    while($objRow1 = $objSet->fetch_object())  // Fetch the result in the object array
	 { 								
            ?>                                         								             
            <a class="more sking" href="<?php echo SITE_URL;?>index.php?dir=property&task=view&id=<?php echo $objRow1->id;?>">               
              <?php echo $objRow1->job_listing; ?>               
              <i class="pull-right icon-long-arrow-right"></i></a>								                                             
<?php } 
}
?>  							           
          </div>						         
        </div>          
        <!-- /.smallstat --> 					       
      </div>        
      <!-- /.col-md-3 --> 				                              
      <?php }  }?>  				     
    </div>      
    <!-- /.row --> 				     
    <!-- /Statboxes -->             				     
    <div class="row mid_text">					       
      <div class="col-md-12 text-center"><h3>PRIORITY LOCATIONS/REQUIRES IMMEDIATE ATTENTION</h3>					       
      </div>				     
    </div>				     
    <div class="row">				       
      <div class="col-md-12 nopadding">					         
        <div class="widget_staff">        
<?php          
       //$PriorityLocationRs = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('status'=>1, 'priority_status'=>1)); 
           $PriorityLocationRs = $this->objDatabase->dbFetch("Select * FROM ".TBL_JOBLOCATION." where status=1 and priority_status=1 and site_id='".$_SESSION['site_id']."' and assigned_to='".$_SESSION['adminid']."'");
           foreach($PriorityLocationRs as $objRecordSet)
           {
              ?>						           
          <div class="box_inner">							             
            <div class="col-md-4 col-lg-3 col-sm-6">							                
              <div class="img_holder2">	
		<?php if($objRecordSet->map_widget == '') $objRecordSet->map_widget = 'no_map_available.jpg'; ?>				 	                 
                <a data-lightbox="property" href="<?php echo SITE_URL.'upload/'.$objRecordSet->map_widget;?>"><img src="<?php echo SITE_URL.'upload/'.$objRecordSet->map_widget;?>" style="max-width:272px; max-height:150px;"></a> 							                
              </div>							             
            </div>							             
            <div class="col-md-8">								               
              <div class="col-md-12 nopadding">									                 
                <h2 class="label-staff col-md-4 nopadding"><strong>Property:</strong></h2>                                   
                <h2 class="label-staff-detail col-md-8">                  
                  <?php echo $objRecordSet->job_listing;?></h2>                                   
                <div class="clearfix">                
                </div>                   									                 
                <h2 class="label-staff col-md-4 nopadding"><strong>Address:</strong></h2>                                   
                <h2 class="label-staff-detail col-md-8"><?php echo $objRecordSet->location_address;?></h2>                                   
                <div class="clearfix">                
                </div>                                                      
                <h2 class="label-staff col-md-4 nopadding"><strong>Manager Phone:</strong></h2>                                   
                <h2 class="label-staff-detail phone col-md-8">                                      
                  <a href="tel:<?php echo $objRecordSet->phn_no;?>">                                        
                    <?php echo $objRecordSet->phn_no;?>                    </a>                  </h2>                                   
                <div class="clearfix">                
                </div>                                                          									                 
                <h2 class="label-staff col-md-4 nopadding"><strong>GPS Points:</strong></h2>                                   
                <h2 class="label-staff-detail gps-points col-md-8">                                     
                  <a href="http://maps.google.com/maps?q=<?php echo $objRecordSet->lat.','.$objRecordSet->lag;?>" target="_blank">                                        
                    <?php echo $objRecordSet->lat.', '.$objRecordSet->lag;?>                    </a>                  </h2>                                   
                <div class="clearfix">                
                </div>                                                                          
                <h2 class="label-staff col-md-4 nopadding"><strong>Special Notes:</strong></h2>                                                      
                <h2 class="label-staff-detail col-md-8">                  
                  <?php echo $objRecordSet->importent_notes;?></h2>                                   
                <div class="clearfix">                
                </div>								               
              </div>							 							               
              <div class="clearfix">              
              </div>							             
            </div>                           
            <a href="<?php echo SITE_URL;?>index.php?dir=property&task=view&id=<?php echo $objRecordSet->id;?>"> 							               
              <button class="btn btn-info view_btn">View</button>
            </a>          	             
            <div class="clearfix">            
            </div>	 						           
          </div>             
<?php
            }
                                ?> 					           
          <div class="clearfix">          
          </div>					         
        </div>				       
      </div>				     
    </div>      
    <!-- row --> 				     
    <!-- /Page Content --> 			   
  </div>			   
  <!-- /.container --> 		 
</div> 

<script type="text/javascript" src="http://osvaldas.info/examples/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/masonry.js"></script>
<script>
$(document).ready(function() {
/*
	    var columns    = 3,
	    setColumns = function() { columns = $( window ).width() > 640 ? 3 : $( window ).width() > 320 ? 2 : 1; };

	    setColumns();
	    $( window ).resize( setColumns );

	    $( '.propertyouterblock' ).masonry(
	    {
	        itemSelector: '.propertyblock',
	        columnWidth:  function( containerWidth ) { return containerWidth / columns; }
	    });
*/

//setupBlocks();
//var timer = setInterval(setupBlocks, 2000);
});
</script>

<?php }
}
?>