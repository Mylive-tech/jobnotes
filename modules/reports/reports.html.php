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
protected function reportsmanager($objRs)
   { 
?>
<script type="text/javascript" src="<?php echo SITE_JSURL;?>plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>assets/dp/jquery.simple-dtpicker.js"></script>
<link type="text/css" href="<?php echo SITE_URL;?>assets/dp/jquery.simple-dtpicker.css" rel="stylesheet" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/dp/jquery.datetimepicker.css"/>
<script src="<?php echo SITE_URL;?>assets/js/dp/jquery.datetimepicker.full.js"></script>-->
  <script type="text/javascript">
  	$(document).ready(function() {
		//
		$('li#cr_nav').click(function(){
			$(this).children('.customreports').stop().slideToggle(400);
		});
		//
		
		$(document).on('submit', '#exportform', function(){//alert('ddddd');
			$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({type: "POST", url: '<?php echo ISP :: AdminUrl('reports/export-report/');?>', data: $(this).serialize(), success: function(response){ //alert(response);
					if(response == 0)
					{
						alert("There are no reports matching your criteria");
					}					
          	 		$.ajax({async: false , url: '<?php echo ISP :: AdminUrl('reports/export-reports/');?>', success: function(result){$("#report_content").html(result);}});
					$('#download_link').attr('href','<?php echo SITE_URL;?>'+response);
					if(response != 0){
                   		$('#download_link')[0].click();
						return false;
					}
       			}
			});
			return false;
		});
		
        $('.otherreportstabs').click(function(){
				title = $(this).attr('title');
				$('.tab_bar a').removeClass('active');
				$(this).addClass('active'); 
				$('#custom_report_tabs').hide();
			});
		$('#customreportstab').click(function(){
				$('.tab_bar a').removeClass('active');
				$(this).addClass('active'); 
				$('#custom_report_tabs ul li:first a').addClass('active');
				$('#custom_report_tabs').show();
			});
		$('.customreportsubtabs').click(function(){
				title = $(this).attr('title');
				$('.tab_bar a').removeClass('active');
				$('#custom_report_tabs a').removeClass('active');
				$(this).addClass('active');	
			});
		//$('.datetimepicker').datetimepicker();
		$('.datatable').dataTable( {} );
    });
	function createsessionzip(taburl)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: taburl, success: function(result){ 
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/session_season_reset/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
	
	function removesessionzip(taburl)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: taburl, success: function(result){ 
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/session_season_reset/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
	
	function createseasonzip(taburl)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: taburl, success: function(result){
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/season_reset/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
	function removeseasonzip(taburl)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: taburl, success: function(result){
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/season_reset/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
	
	function removeexportzip(taburl)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: taburl, success: function(result){
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/export-reports/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
	
	function resetpropertydata(taburl, pid)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: taburl, success: function(response){ alert(response);
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/job-history/id/');?>'+pid, success: function(result){$("#report_content").html(result);}});
					$('#download_link').attr('href','<?php echo SITE_URL;?>'+response);
					if(response != 0){
                   		$('#download_link')[0].click();
						return false;
					}
       			}
			});
			return false;
	}
	
  	function loadreportcontent(taburl)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: taburl, success: function(result){
			if(title != '')
          	 {	$("#report_content").html('<h2>'+title+'</h2>'+result);
			 	$('.dropdown-toggle').dropdown();
			}
			 
			else
			 {	$("#report_content").html(result);
			 	$('.dropdown-toggle').dropdown();
			}
       		}
		});
	}
	
	function searchreportcontent(taburl)
	{
		var datefrom = $('#datetimepicker_from').val();
		var dateto = $('#datetimepicker_to').val();
		var staffid = $('#staff_id').val();
		var fname = $('#fname').val();
		var lname = $('#lname').val();
		var reporttype = $('#reporttype').val();
		var clockaction = $('#clock_action').val();
		var propertyname = $('#propertyname').val();
		var locationname = $('#locationname').val();
		var assignedto = $('#assignedto').val();
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		if(reporttype == 'driverlog')
		{
			$.ajax({url: taburl+'?date_from='+datefrom+ '&date_to='+dateto+'&staffid='+staffid+'&fname='+fname+'&lname='+lname+'&s=Search', success: function(result){
          	 	$("#report_content").html(result);
       			}
			});
			return false;
			
		}
		else if(reporttype == 'ivrlog')
		{
			$.ajax({url: taburl+'?date_from='+datefrom+ '&date_to='+dateto+'&staff_id='+staffid+'&fname='+fname+'&lname='+lname+'&clock_action='+clockaction+'&s=Search', success: function(result){
          	 	$("#report_content").html(result);
       			}
			});
			return false;
		}
		else if(reporttype == 'propertylog')
		{
			$.ajax({url: taburl+'?propertyname='+propertyname+ '&locationname='+locationname+'&assignedto='+assignedto+'&s=Search', success: function(result){
          	 	$("#report_content").html(result);
       			}
			});
			return false;
		}
	}
	function viewlog(logurl)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({url: logurl, success: function(result){
          	 	$("#report_content").html(result);
       			}
			});
			return false;
	}
	function fn_exportreports(frmobj)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({type: "POST", url: '<?php echo ISP :: AdminUrl('reports/export-report/');?>', data: frmobj.serialize(), success: function(result){ //alert(result); return false;
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/export-reports/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
	function fn_resetalljob()
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({type: "POST", url: '<?php echo ISP :: AdminUrl('reports/reset-all-property/');?>', data: frmobj.serialize(), success: function(result){
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/completed-properties/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
	function fn_resetjob(pid)
	{
		$("#report_content").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({type: "POST", url: '<?php echo ISP :: AdminUrl('reports/reset-property/id/');?>'+pid, data: frmobj.serialize(), success: function(result){
          	 		$.ajax({url: '<?php echo ISP :: AdminUrl('reports/completed-properties/');?>', success: function(result){$("#report_content").html(result);}});
       			}
			});
			return false;
	}
  </script>
  <?php $reportnames = $this->reportNames(); ?>
<div id="content">
<div class="container">
<h4 class="text-left heding_6">Reports Manager</h4> 
<!--<div class="tab_bar">--> <?php /*$test = $this->ugal(); echo SITE_MEDIA . 'aa'; print_r($test);
$pimg = explode(',', $test->staffuploads); print_r($pimg);
		//while($objRow = $objRs->fetch_object())
		{
			//$pimg = explode(',', $objRow->Images);
			foreach($pimg as $img){
				if($img != '')
					$filesarr[] = $img;
			}
		}foreach($pimg as $img){ echo SITE_MEDIA.$img;
				if (file_exists(SITE_MEDIA.$img)) { echo SITE_MEDIA.$img;
				unlink('upload/'.$img);
			} else {echo 'noimage';}
			}*/
?>
          <div id="navbar" class="tab_bar navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li id="cr_nav"><a href="#">Custom Reports<span class="caret"></span></a>
                     <ul class="customreports">
                    <?php while($rname = $reportnames->fetch_object())
                    { ?>
                    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/listing/?rid='.$rname->report_id);?>')" class="customreportsubtabs" title="<?php echo $rname->report_name;?>"><?php echo $rname->report_name;?></a></li>
                     <?php $i++; }?>
                    </ul>
                </li>
            	<!--<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Custom Reports<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                    <?php while($rname = $reportnames->fetch_object())
                    { ?>
                    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/listing/?rid='.$rname->report_id);?>')" class="customreportsubtabs" title="<?php echo $rname->report_name;?>"><?php echo $rname->report_name;?></a></li>
                     <?php $i++; }?>
                    </ul>
              </li>-->
              <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/report_ivr_log/');?>')" class="otherreportstabs" title="IVR Log">IVR Log</a></li>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/driver_report/');?>')"class="otherreportstabs" title="Driver Reports">Driver Reports</a></li>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/property_report/');?>')" class="otherreportstabs" title="Properties Report">Properties Reports</a></li>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/export-reports/');?>')" class="otherreportstabs" title="Export Reports">Export Reports</a></li>
     <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/session_season_reset/');?>')" class="otherreportstabs" title="Session and Season Reset">Session and Season Reset</a></li>
     <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/completed-properties/');?>')" class="otherreportstabs" title="Completed Jobs">Completed Jobs</a></li>
            </ul>
            
          </div>
	<?php //$defaultreportnames = $this->reportNames(); $rid = $defaultreportnames->fetch_object();?>
    <!--<ul>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/listing/?rid='.$rid->report_id);?>')" id="customreportstab" class="active">Custom Reports</a></li>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/report_ivr_log/');?>')" class="otherreportstabs">IVR Log</a></li>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/driver_report/');?>')"class="otherreportstabs">Driver Reports</a></li>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/property_report/');?>')" class="otherreportstabs">Properties Reports</a></li>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/export-reports/');?>')" class="otherreportstabs">Export Reports</a></li>
     <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/session_season_reset/');?>')" class="otherreportstabs">Session and Season Reset</a></li>
    </ul>
    </div> <hr/>
    <div class="tab_bar" id="custom_report_tabs">
	<?php $reportnames = $this->reportNames(); ?>
    <ul>
    <?php $i=1; while($rname = $reportnames->fetch_object())
    { ?>
    <li><a href="#" onclick="loadreportcontent('<?php echo ISP :: AdminUrl('reports/listing/?rid='.$rname->report_id);?>')" <?php if($i == 1) echo 'class="customreportsubtabs active"'; else echo 'class="customreportsubtabs"'; ?>><?php echo $rname->report_name;?></a></li>
     <?php $i++; }?>
    </ul>
    </div>-->
    <div id="report_content">
        <?php 
        //custom reports
        $objRs = $this->reportListing_default();  $objRs1 = $this->reportListing_default(); $rname = $objRs1->fetch_object(); ?>
<h2><?php echo $rname->report_name;?></h2>
<div class="row">
<div class="col-md-12">  
<!--<h4 class="text-left heding_6">Custom Reports</h4>-->
<div class="widget box box-vas">
<div class="widget-content widget-content-vls">
<div class="form_holder">
	
      <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">
          <!--<thead class="cf">
            <tr>  
             <th data-class="expand">Location</th>
             <th data-hide="phone">Property</th>
             <th data-hide="phone">Emp No.</th>
             <th data-hide="phone">Salt Used</th>
             <th data-hide="phone">Calcium Used</th>
             <th data-hide="phone">No. of Guys</th>
             <th data-hide="phone">Total Man Hours</th>
             <th data-hide="phone">Name</th>
             <th data-hide="phone">Date</th>
             
            </tr>
          </thead>
          <tbody>--> 
          <?php $thead = 0; //echo 'dddd'; print_r($objRs); print_r($objRs->fetch_object());
            while($objRow = $objRs->fetch_object())
            {
				$thead++; 
				$locname = $this->objFunction->iFindAll(TBL_SERVICE, array('id'=>$objRow->location_id));
				$propname = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('id'=>$objRow->property_id));
				$uname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->submitted_by));
				$date = $objRow->submission_date;
				$fdata = json_decode($objRow->form_values, true);
				$fbody = json_decode($objRow->form_body);
				if($thead == 1):
				//print_r($fdata);
				$fields = array('db_location','db_property','rid','user_id','timestamp');
				?>
                <thead class="cf">
                    <tr>  
                         <th data-class="expand">Location</th>
                         <th data-hide="phone">Property</th>
                         <?php foreach($fbody as $fb){ if($fb->field_type != 'fieldset'){
						 if($fb->label != ''){ ?>
                             <th data-hide="phone"><?php if($fb->label == '') echo '&nbsp;'; else echo $fb->label;?></th>
                         <?php } } }?>
                         <th data-hide="phone">Name</th>
                         <th data-hide="phone">Date</th>
                   </tr>
				</thead>
                <tbody>
               <?php endif;?>
                
                <tr>
                	 <td><?php echo $locname[0]->name; ?></td>
                     <td><?php echo $propname[0]->job_listing; ?></td>
                     <?php foreach($fdata as $key=>$val){  if( ($key != 'form_token') && ($key != 'send') ){
						 if( in_array($key, $fields) === false ){ ?>
                              <td><?php if(is_array($val)) echo implode(',', $val);  else echo $val; ?></td>
                         <?php } } }?>
                    <!-- <td><?php echo $fdata->db_field_2; ?></td>
                     <td><?php echo $fdata->db_field_3; ?></td>
                     <td><?php echo $fdata->db_field_4; ?></td>
                     <td><?php echo $fdata->db_field_5; ?></td>
                     <td><?php echo $fdata->db_field_6; ?></td>-->
                     <td><?php echo $uname[0]->f_name . ' ' .$uname[0]->l_name; ?></td>
                     <td><?php echo date('Y-m-d h:i A', strtotime($date)); ?></td>
                </tr>
                
            <?php }
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
</div>
<?php   
   }  
protected function reportListing($objRs){
	$customreportsdata = '';
	$customreportsdata .='<script type="text/javascript">
			$(".datatable").dataTable( {} );
</script><div class="row">
	<div class="col-md-12">  
	<div class="widget box box-vas">
	<div class="widget-content widget-content-vls">
	<div class="form_holder">
		  <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">';
				$thead = 0;
				while($objRow = $objRs->fetch_object())
				{
					$thead++; 
					$locname = $this->objFunction->iFindAll(TBL_SERVICE, array('id'=>$objRow->location_id));
					$propname = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('id'=>$objRow->property_id));
					$uname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->submitted_by));
					$date = $objRow->submission_date;
					$fdata = json_decode($objRow->form_values, true);
					$fbody = json_decode($objRow->form_body);
					if($thead == 1):
					$fields = array('db_location','db_property','rid','user_id','timestamp');
					$customreportsdata .='<thead class="cf">
						<tr>';
						if($objRow->report_name != 'Equip Problems to Report') {
							 $customreportsdata .='<th data-class="expand">Location</th>
							 <th data-hide="phone">Property</th>';
							 }
							 $x = 0;
							 $y = 0;
							 foreach($fbody as $fb){  if($fb->field_type != 'fieldset'){
								 if($fb->label == ''){ 
								  } else {
																 if($objRow->report_name == 'Subcontractors Equipment Usage') {
																	 $fb->label = $fbody[$x]->label."<br>".$fb->label;
																 }
								 $customreportsdata .='<th data-hide="phone">'.$fb->label.'</th>';
							 } $y++; } else { if($y>0) { $x = $x+3;} } }
							 $customreportsdata .='<th data-hide="phone">Name</th>
							 <th data-hide="phone">Date</th>
					   </tr>
					  </thead>
					  <tbody>';
					 endif;
					$customreportsdata .='<tr>';
					if($objRow->report_name != 'Equip Problems to Report') {
						 $customreportsdata .='<td>'.$locname[0]->name.'</td>
						 <td>'.$propname[0]->job_listing.'</td>';
						 }
						 foreach($fdata as $key=>$val){ if( ($key != 'form_token') && ($key != 'send') ){
							 if( in_array($key, $fields) === false){
								  $customreportsdata .='<td>';
								  if($objRow->report_name == 'Subcontractors Equipment Usage' )
								  {
									  if($val == '') {$customreportsdata .= '0';} else { $customreportsdata .= $val;}
								  }
								  elseif(is_array($val)) $customreportsdata .= implode(',', $val);  else $customreportsdata .= $val; 
								  $customreportsdata .='</td>';
						  } } }
						 $customreportsdata .='<td>'. $uname[0]->f_name . ' ' .$uname[0]->l_name.'</td>
						 <td>'.date('Y-m-d h:i A', strtotime($date)).'</td>
					</tr>';
					 }
			  $customreportsdata .='</tbody>
		  </table> 
		  </div>
	</div>
	</div> 
	</div>
	</div>'; 
	echo $customreportsdata; die;
}
protected function admin_report_ivr_log($objRs) {
	$ivrdata = '';
$ivrdata .='<script type="text/javascript">
			$(".datetimepicker").appendDtpicker({
				dateFormat: "YYYY-MM-DD H:mmTT", 	
				"amPmInTimeList": true,
				"closeOnSelected": true,
				"autodateOnStart": false
				});
		$(".datatable").dataTable( {} );
</script><!--=== Normal ===--> 				
    <div class="row">		
      <div class="col-md-12">
        <div class="tabbable tabbable-custom">						
          <div class="widget box box-vas">							 							
            <div class="widget-content widget-content-vls">';                             
			   	if(isset($_GET['staffid']))
				{
					$user_details = $this->objFunction->getUserDetailsUsingUsername($_GET['staffid']);
					while($objRow = $user_details->fetch_object()) {
						$user_role = $this->objFunction->UserRoleUsingUserType($objRow->user_type);
						//print_r($user_role);
						$ivrdata .= '<table width="100%">
						<tr>
							<td width="25%">Username/IVR staffId: '.$objRow->username.'</td>
							<td width="25%">Name: '.$objRow->f_name.' '.$objRow->l_name.'</td>
							<td width="50%">Email: '.$objRow->email.'</td>
						</tr>
						<tr>
							<td width="25%">Phone: '.$objRow->phone.'</td>
							<td width="25%">Role: '.$user_role->label.'</td>
							<td width="50%">&nbsp;</td>
						</tr>
						</table>';
						break;
					}
					
					$array_log = $this->objFunction->getStaffIvrLog($_GET['staffid']);
                    
                    $ivrdata .= '<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">                    
                  <thead class="cf">											
                    <tr>                          
                        <th data-class="expand">S NO</th>												  
                        <th data-hide="phone">Date</th>                          
                        <th data-hide="phone">Time</th>
                        <th data-hide="phone">Status</th>
                    </tr>									
                  </thead>									
                  <tbody>';
					$intI = 1; $rowCount=0;
					foreach ($array_log as $log) 
					{
						//$rowCount++;
						if($intI++%2==0)  // Condition for alternate rows color
							$strCss='evenTr';
						else
							$strCss='oddTr';
						if(isset($_GET['s'])):
							if($log['time_stamp'] >= strtotime($_GET['date_from']) && $log['time_stamp'] <= strtotime($_GET['date_to'])):
							$rowCount++;
                          $ivrdata .= '<tr> 
                            <td align="center">'.$rowCoun.'</td>             
                            <td align="center">'.$log['date'].'</td>
                            <td align="center">'.date('h:i A', $log['time_stamp']).'</td>
                            <td align="center">'.ucfirst($log['clock_action_description']).'</td>
                          </tr>';
							endif;
						else:
							$rowCount++;
                          $ivrdata .= '<tr> 
                            <td align="center">'.$rowCoun.'</td>             
                            <td align="center">'.$log['date'].'</td>
                            <td align="center">'.date('h:i A', $log['time_stamp']).'</td>
                            <td align="center">'.ucfirst($log['clock_action_description']).'</td>
                          </tr>';
						endif;		
					}
                  $ivrdata .= '</tbody>
                  </table>';
                 }
				else { 
               $ivrdata .= '<div class="col-md-12 text-right" style="padding-bottom:10px"><a href="'.ISP::AdminUrl('index.php?dir=reports&amp;task=export_ivr_log_report').'" class="btn btn-info">Export</a></div>
               <p>
                    <form action="" method="get">
                        <label> Date:  </label>
                        <input type="text" class="datetimepicker" id="datetimepicker_from" name="date_from" placeholder="From" value="'.$_GET['date_from'].'" />
                        <input type="text" class="datetimepicker" id="datetimepicker_to" name="date_to" placeholder="To" value="'.$_GET['date_to'].'" />
                        <input type="text" name="staff_id" id="staff_id" value="'.$_GET['staff_id'].'" placeholder="Username/StaffId" />
        <input type="text" name="fname" id="fname" value="'.$_GET['fname'].'" placeholder="First Name" />
        <input type="text" name="lname" id="lname" value="'.$_GET['lname'].'" placeholder="Last Name" />
        <select name="clock_action" id="clock_action">
        <option value="">Select Status</option>
        <option value="clock in">Clock In</option>
        <option value="clock out">Clock Out</option></select>
                        <input type="hidden" name="reporttype" id="reporttype" value="ivrlog" />
                        <input type="button" name="s" id="s" value="Search" onclick=searchreportcontent("'.ISP :: AdminUrl("reports/report_ivr_log/").'"); return false; />
                    </form>
                    </p>
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
                  <tbody>';	
			if($objRs): // Check for the resource exists or not
                $intI=1;
                $staff_log = $this->objFunction->getAllStaffLastLog();  
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{
				
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
                $lastUser = '';
                if ($staff_log[$objRow->username] <> '' ) { 
                $rowCount=0;
                            $lastUser = $objRow->username;
                            $rowCount++;
							$ivrdatetimestatus = explode(',', $staff_log[$objRow->username]);
						
						if(isset($_GET['s'])){
							if($_GET['date_from'] != '' && $_GET['date_to'] != '' && $_GET['clock_action'] != '')
							{
								if($ivrdatetimestatus[3] >= strtotime($_GET['date_from']) && $ivrdatetimestatus[3] <= strtotime($_GET['date_to']) && $ivrdatetimestatus[2] == $_GET['clock_action']){
								$ivrdata .= '<tr> 
									<td align="center">'.$objRow->username.'</td>             
									 <td align="center">'.$objRow->f_name.' '.$objRow->l_name.'</td>              
									<td align="center">'.$ivrdatetimestatus[0].'</td>
									<td align="center">'.date('h:i A',strtotime($ivrdatetimestatus[1])).'</td>
									<td align="center">'.$ivrdatetimestatus[2].'</td> 
									<td align="center"><a href="#" onclick=viewlog("'.ISP :: AdminUrl("reports/report_ivr_log/?staffid=".$objRow->username).'")>View</a></td>
									<td align="center"><a href="'.ISP :: AdminUrl('index.php?dir=staff&task=import_ivr_log&user='.$objRow->username).'" >Export</a></td>
								</tr>';
								}
							}
							elseif($_GET['date_from'] != '' && $_GET['date_to'] != '')
							{
								if($ivrdatetimestatus[3] >= strtotime($_GET['date_from']) && $ivrdatetimestatus[3] <= strtotime($_GET['date_to'])){
								$ivrdata .= '<tr> 
									<td align="center">'.$objRow->username.'</td>             
									 <td align="center">'.$objRow->f_name.' '.$objRow->l_name.'</td>              
									<td align="center">'.$ivrdatetimestatus[0].'</td>
									<td align="center">'.date('h:i A',strtotime($ivrdatetimestatus[1])).'</td>
									<td align="center">'.$ivrdatetimestatus[2].'</td> 
									<td align="center"><a href="#" onclick=viewlog("'.ISP :: AdminUrl("reports/report_ivr_log/?staffid=".$objRow->username).'")>View</a></td>
									<td align="center"><a href="'.ISP :: AdminUrl('index.php?dir=staff&task=import_ivr_log&user='.$objRow->username).'" >Export</a></td>
								</tr>';
								}
							}
							elseif($_GET['clock_action'] != '')
							{
								if($ivrdatetimestatus[2] == $_GET['clock_action']){
								$ivrdata .= '<tr> 
									<td align="center">'.$objRow->username.'</td>             
									 <td align="center">'.$objRow->f_name.' '.$objRow->l_name.'</td>              
									<td align="center">'.$ivrdatetimestatus[0].'</td>
									<td align="center">'.date('h:i A',strtotime($ivrdatetimestatus[1])).'</td>
									<td align="center">'.$ivrdatetimestatus[2].'</td> 
									<td align="center"><a href="#" onclick=viewlog("'.ISP :: AdminUrl("reports/report_ivr_log/?staffid=".$objRow->username).'")>View</a></td>
									<td align="center"><a href="'.ISP :: AdminUrl('index.php?dir=staff&task=import_ivr_log&user='.$objRow->username).'" >Export</a></td>
								</tr>';
								}
							}
							else
							{
								$ivrdata .= '<tr> 
									<td align="center">'.$objRow->username.'</td>             
									 <td align="center">'.$objRow->f_name.' '.$objRow->l_name.'</td>              
									<td align="center">'.$ivrdatetimestatus[0].'</td>
									<td align="center">'.date('h:i A',strtotime($ivrdatetimestatus[1])).'</td>
									<td align="center">'.$ivrdatetimestatus[2].'</td> 
									<td align="center"><a href="#" onclick=viewlog("'.ISP :: AdminUrl("reports/report_ivr_log/?staffid=".$objRow->username).'")>View</a></td>
									<td align="center"><a href="'.ISP :: AdminUrl('index.php?dir=staff&task=import_ivr_log&user='.$objRow->username).'" >Export</a></td>
								</tr>';      
							}
						}
						else{
                        	$ivrdata .= '<tr> 
									<td align="center">'.$objRow->username.'</td>             
									 <td align="center">'.$objRow->f_name.' '.$objRow->l_name.'</td>              
									<td align="center">'.$ivrdatetimestatus[0].'</td>
									<td align="center">'.date('h:i A',strtotime($ivrdatetimestatus[1])).'</td>
									<td align="center">'.$ivrdatetimestatus[2].'</td> 
									<td align="center"><a href="#" onclick=viewlog("'.ISP :: AdminUrl("reports/report_ivr_log/?staffid=".$objRow->username).'")>View</a></td>
									<td align="center"><a href="'.ISP :: AdminUrl('index.php?dir=staff&task=import_ivr_log&user='.$objRow->username).'" >Export</a></td>
								</tr>';
								 }       
                }                     
			}          
                   // $ivrdata .= '</tr>';
			 else:
			        $ivrdata .= '<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;           
                  $ivrdata .= '</tbody>        
                </table> 
                </form> ';
				}      	  
             			
            $ivrdata .= '</div>    
          </div>
        </div>
      </div>
    </div>	';
echo $ivrdata;
die;	
} 
protected function admin_driver_report($objRs) {$driverdata = '';
$driverdata .='';
$driverdata .='<script type="text/javascript">
			$(".datetimepicker").appendDtpicker({
				dateFormat: "YYYY-MM-DD H:mmTT", 	
				"amPmInTimeList": true,
				"closeOnSelected": true,
				"autodateOnStart": false
				});
			$(".datatable").dataTable( {} );
</script>			
    <div class="row">		
      <div class="col-md-12">
      <p><form action="" method="get">
      <label> Date:  </label>
      	<input type="text" class="datetimepicker" id="datetimepicker_from" name="date_from" placeholder="From" value="'.$_GET['date_from'].'" />
		<input type="text" class="datetimepicker" id="datetimepicker_to" name="date_to" placeholder="To" value="'.$_GET['date_to'].'" />
        <input type="text" name="staff_id" id="staff_id" value="'. $_GET['staffid'].'" placeholder="Username/StaffId" />
        <input type="text" name="fname" id="fname" value="'.$_GET['fname'].'" placeholder="First Name" />
        <input type="text" name="lname" id="lname" value="'.$_GET['lname'].'" placeholder="Last Name" />
        <input type="hidden" name="reporttype" id="reporttype" value="driverlog" />
        <input type="submit" name="s" id="s" value="Search" onclick=searchreportcontent("'.ISP :: AdminUrl("reports/driver_report/").'"); return false; />
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
                  <tbody>';
			if($objRs): // Check for the resource exists or not
                $intI=1;
                $staff_log = $this->objFunction->getAllStaffLastLog();  
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{
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
                                  
                            $driverdata .='<tr> 
                                <td align="center">'.$objRow->username.'</td>             
                                <td align="center">'.$objRow->f_name.'</td>              
                                <td align="center">'.$objRow->l_name.'</td>              
                                <td align="center">';
                                 	$diff1 = strtotime($objRow->c_date) - strtotime($objRow->s_date);
									if($diff1 > 0)
										$driverdata .= round($diff1/3600, 2);
									else 
									$driverdata .= 'N/A';
                                $driverdata .='</td>                             
                                <td align="center"><a href="#" onclick=viewlog("'.ISP :: AdminUrl('reports/driver_report_log/?driver_id='.$objRow->id).'")>View</a></td>
                            </tr> ';               
            endif;                       
			}          
                    $driverdata .='</tr>';       
			 else:
			       $driverdata .='<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;           
       $driverdata .='</tbody>        
                </table>       	  
              </form>			
            </div>    
          </div>
        </div>
      </div>
    </div> '; 
	echo $driverdata; die;
	}
protected function admin_driver_report_log($objRs) {
		$driverlogdata = '';
$driverlogdata .='<script type="text/javascript">
			$(".datatable").dataTable( {} );
</script>				
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
                    </tr>									
                  </thead>									
                  <tbody>';
			$i=0;
			if($objRs): // Check for the resource exists or not
                $intI=1;
                $staff_log = $this->objFunction->getAllStaffLastLog();   
			while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			{
			    $i++;
				$strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';
                                  
                            $driverlogdata .='<tr> 
                                <td align="center">'.$i.'</td>             
                                <td align="center">'.$objRow->job_listing.'</td>              
                                 <td align="center">';
								if($objRow->starting_date > 0)
								{
									$driverlogdata .=date('Y-m-d h:i A', strtotime($objRow->starting_date));
								}
								else
								{
									$driverlogdata .='N/A';
								}
								$driverlogdata .='</td>
								<td align="center">';
								 if($objRow->closing_date > 0)
								 {
								 	$driverlogdata .= date('Y-m-d h:i A', strtotime($objRow->closing_date));
								 }
								 else
								 {
									 $driverlogdata .= 'N/A';
								 }
								$driverlogdata .='</td>              
                                <td align="center">';
								$diff1 = strtotime($objRow->closing_date) - strtotime($objRow->starting_date);
								if($diff1 > 0)
									$driverlogdata .= round($diff1/3600, 2);
								else 
									$driverlogdata .= 'N/A';
                                $driverlogdata .='</td>';                                                     
			}           
                    $driverlogdata .='</tr>';          
			 else:
			       $driverlogdata .= '<tr><td  class="errNoRecord">No Record Found!</td></tr>';
			 endif;     
                  $driverlogdata .='</tbody>        
                </table>       	  
              </form>			
            </div>    
          </div>
        </div>
      </div>
    </div>';
	echo $driverlogdata; die;
}
protected function admin_property_report($objRs){
	$propertydata = '';
	$propertydata .='<script type="text/javascript">
		$(".export_props").click(function(){
				var ahval = $(this).attr("href");
				var pids = $(".propcheck:checked").map(function() {
    			return this.value;
				}).get().join(",");
				if(pids != ""){
					$(this).attr("href", ahval+"&cjid="+pids); 
				}
				else
				{
					$(this).attr("href", ahval); 
				}
				return true;
			});
		$(".datatable").dataTable( {} );	
</script>      
    <p>
    <form action="" method="get">
    	
        <select name="locationname" id="locationname">
        	<option value="">Select Location</option>';
             $reportdetails = $this->property_report_details(); $loc = array();
			while($rd = $reportdetails->fetch_object())  // Fetch the result in the object array
			  {
				  if(!in_array($rd->location_id, $loc)){
					  if($rd->location_id == $_GET['locationname']) {$sel = 'selected="selected"';}
					  else {$sel='';}
					 $propertydata .='<option value="'.$rd->location_id.'" '.$sel.'>'.$this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$rd->location_id)).'</option>';
					 $loc[] = $rd->location_id; 
				  }
			  }
        $propertydata .='</select>
        
        <select name="propertyname" id="propertyname">
        	<option value="">Select Property</option>';
            $reportdetails = $this->property_report_details();
			while($rd = $reportdetails->fetch_object())  // Fetch the result in the object array
			  {
				 if($rd->job_listing == $_GET['propertyname']){$sel = 'selected="selected"';} 
				 else {$sel='';}
				 $propertydata .='<option value="'.$rd->job_listing.'" '.$sel.'>'.$rd->job_listing.'</option>'; 
			  }
        $propertydata .='</select>
        
        <select name="assignedto" id="assignedto">
        	<option value="">Assigned to</option>';
             $reportdetails = $this->property_assign_details();
			while($rd = $reportdetails->fetch_object())  // Fetch the result in the object array
			  {
				  $staffname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$rd->user_id));
				  if($rd->user_id == $_GET['assignedto']) {$sel = 'selected="selected"';}
				  else {$sel='';}
				 $propertydata .='<option value="'.$rd->user_id.'" '.$sel.'>'.$staffname[0]->f_name.' '.$staffname[0]->l_name.'</option>'; 
			  }
			$propertydata .='</select>
        <input type="hidden" name="reporttype" id="reporttype" value="propertylog" />
        <input type="submit" name="s" id="s" value="Search" onclick=searchreportcontent("'.ISP :: AdminUrl("reports/property_report/").'"); return false; />
      </form></p>				     
    <div class="row">					       
      <div class="col-md-12">					         
        <div class="widget box box-vas">							 							           
          <div class="widget-content widget-content-vls">';
          if(isset($_POST['btn_export'])) {print_r($_POST['delete']); die;
			   	$this->direct($_POST['delete']);
		  }                                             
            $propertydata .='<form method="post" name="frmListing" action=""> 
            <div class="col-md-12 text-right" style="padding-bottom:10px">
                <a href="'.ISP :: AdminUrl('reports/direct/').'?exp=all" class="btn btn-info export_props">Export All</a>  <a href="'.ISP :: AdminUrl('reports/direct/').'?exp=prop" class="btn btn-info export_props">Export</a>
            </div><table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                                     
                <thead class="cf">							                   
                  <tr>
                  	<th class="hiddencolumn"></th>                                     
                    <th align="center">S.N.</th>
                     <th align="center" data-hide="phone" class="hideexport divchecker"><input type="checkbox" class="uniform" name="del[]" onclick="selectDeselect(\'delete[]\');" /> 								                     
                    <th align="center"  data-class="expand">Job Location</th>								                     
                    <th align="center" data-hide="phone">Address</th>                                     
                    <th align="center" data-hide="phone">Assigned To</th>                                     
                    <th align="center" data-hide="phone">Phone Number</th>                                     
                    <th align="center" data-hide="phone">Important Notes</th>                                     
                    <th align="center" data-hide="phone">Assigned Location</th>                                                                                                                               <th align="center" data-hide="phone" class="hideexport">&nbsp;</th>                                     
                    <th align="center" data-hide="phone" class="hideexport">&nbsp;</th>                    
                    </th>						                   
                  </tr>						                 
                </thead>						                 
                <tbody> ';                      
			if($objRs): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';       			                   
                  $propertydata .='<tr>
                  <td class="hiddencolumn"></td>
                  <td>'.($intI-1).'</td>
                   <td align="center" class="hideexport divchecker"><input type="checkbox" class="uniform propcheck" name="delete[]" value="'.$objRow->id.'" /></td> 
                      <td>'.$objRow->job_listing.'
                      <br> <br>
                      </td> 
                      <td>'.$objRow->location_address.'</td>
                      <td>';
                      if($objRow->assigned_to != '') { 
						$propertydata .= ucfirst(str_replace('_', ' ', $objRow->assigned_to));
                      }
                      else{
                        $propertydata .= 'Unassigned';    
                      }
                      $propertydata .='</td>
                      <td><a href="tel:'.$objRow->phn_no.'">'.$objRow->phn_no.'</a></td> 
                      <td>'.$objRow->importent_notes.'</td>
                      <td>'.$this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRow->location_id)).'</td>                                    
                   
                    <td align="center" class="hideexport"> <a class="btn btn-danger btn-ms" href="#" onclick=viewlog("'.ISP :: AdminUrl("reports/job-history/id/".$objRow->id).'")>View</a></td>
                    <td align="center" class="hideexport"><a href="'.ISP :: AdminUrl('reports/direct/?exp=all&jid='.$objRow->id).'" class="btn btn-info">Export All</a> <a href="'.ISP :: AdminUrl('reports/direct/?jid='.$objRow->id).'" class="btn btn-info">Export</a></td>                                    
                  </tr>';                           
			   }                         
                $propertydata .='</tbody>                       
              </table> ';                          
			 else:
			       $propertydata .='<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
			 endif;                   
            $propertydata .='</form>						 		           
          </div>						         
        </div>					       
      </div>				     
    </div>';
	echo $propertydata; die;
}  // End of Function 
protected function admin_m_property_jobHistory($objRs1, $objRow){
	$report_ids = $this->getReportsDetails($objRow->id);
	//print_r($report_ids);
   ?>
   <script type="text/javascript"> 
   function jobhistoryloadreportcontent(taburl)
	{
		$("#jobhistoryreportdata").html('<div class="rpt_loader"><img src="<?php echo SITE_URL.'/assets/img/ajax-loader.gif' ?>" /></div>');
		$.ajax({
			url: taburl, 
			success: function(result){
				$("#jobhistoryreportdata").html(result);
			}
		});
		return false;
	}
	</script>
<!--<script type="text/javascript">
$(document).ready(function() {
    $('.datatable').dataTable( {} );
} );
</script> -->     
<div id="content">			   
  <div class="container">	
  <!--<div class="crumbs">
	<ul id="breadcrumbs" class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>
		</li>
    <li>
			<i class="current"></i>
			<a href="<?php echo ISP::AdminUrl('reports/property_report/');?>">Property Reports</a>
		</li>
		<li class="current">
			<a href="#" title="">Job History</a>
		</li>
	</ul>
</div>
-->			     
    <!--=== Normal ===--> 				     
    <div class="row">					       
      <div class="col-md-12">						         
        <h4 class="text-left heding_6">Job History</h4>						         
        <div class="widget box box-vas">							 							           
          <div class="widget-content widget-content-vls">                                             
            <form method="post" name="frmListing"> 
            <div class="col-md-12 text-right" style="padding-bottom:10px">
            
              <span>										                 
               <!-- <a href="javascript: void(0);" onclick="exportTableToCSV.apply(this, [$('#dataTables-example'), 'export-history-<?php echo $objRow->job_listing;?>.xls']);" class="btn btn-info">Export</a>-->
                <a href="<?php echo ISP :: AdminUrl('reports/direct/?jid='.$objRow->id);?>" class="btn btn-info">Export</a>								               
              </span> 
            </div>                                                 
              <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable1">                                                     
                <thead class="cf">							                   
                  <tr> 
                  <th class="hiddencolumn"></th>                                    
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Location</th>								                     
                    <th align="center" data-hide="phone">Address</th>                                     
                    <th align="center" data-hide="phone">Assigned To</th>                                     
                    <th align="center" data-hide="phone">Phone Number</th>                                     
					<!--th align="center" data-hide="phone">Important Notes</th-->                                     
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
                  <td class="hiddencolumn"></td>
                  <td><?php echo $intI-1;?></td>
                      <td><?php echo $objRow->job_listing;?></td> 
                      <td><?php echo $objRow->location_address;?></td>
                      <td><?php /*$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->assigned_to)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name;*/ echo ucfirst(str_replace('_',' ',$objRow->assigned_to));?></td>
                      <td><?php echo $objRow->phn_no;?></td> 
                      <!--td><?php echo $objRow->importent_notes;?></td-->
                      <td><?php echo $this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRow->location_id));?>                      
                      </td>                                    
                     <td align="center" class="hideexport"><?php
					 if($objRow->completion_date != '0000-00-00 00:00:00') 
					 	echo date('Y-m-d h:i A',strtotime($objRow->completion_date));
					 else
					 	echo 'N/A';?></td>
                     <td align="center" class="hideexport"><a href="<?php echo ISP :: AdminUrl('reports/reset-property/id/'.$objRow->id);?>">Reset</a>
                    <!-- <a onclick="resetpropertydata('<?php echo ISP :: AdminUrl('reports/reset-property/id/'.$objRow->id);?>'); return false;" href="javascript:void(0)">Reset</a>-->
                     </td>                               
                   </tr>                            
         
                </tbody>                       
              </table>
              <br/>  
              <p><strong>Submitted Reports :</strong></p>
              	<ul class="jobhistoryproperty">
					<?php $repid = array();
					 while($rids = $report_ids->fetch_object())
                    { 
					if(!in_array($rids->report_id, $repid)){?>
                        <li><a href="javascript:void(0)" onclick="jobhistoryloadreportcontent('<?php echo ISP :: AdminUrl('reports/jobhistory-property-reports/?rid='.$rids->report_id.'&pid='.$objRow->id);?>'); return false;"><?php echo $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$rids->report_id)); ?></a></li>
                     <?php $repid[] = $rids->report_id;
					}
					}?>
                </ul>
                <div id="jobhistoryreportdata"></div>
              
              <table id="dataTables-example" class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                                     
                <thead class="cf">							                   
                  <tr>    
                  <th class="hiddencolumn"></th>                                 
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Started On</th>								                     
                    <th align="center" data-hide="phone">Job Started By</th>                                     
                    <th align="center" data-hide="phone">Completed On</th>                                     
                    <th align="center" data-class="expand">Completed By</th>                      
                    <th align="center" data-class="expand">Uploaded Images</th>                                     
                          
                  </tr>						                 
                </thead>						                 
                <tbody>                       
<?php
			
			if($objRs1): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow1 = $objRs1->fetch_object())  // Fetch the result in the object array
			  { //print_r($objRow1);
    				if($intI++%2==0)  // Condition for alternate rows color
    				   $strCss='evenTr';
    				else
    				   $strCss='oddTr';
				
                                    			?>         			                   
                  <tr>
                  <td class="hiddencolumn"></td>
                  <td><?php echo $intI-1;?></td>
                      <td><?php
					  if($objRow1->starting_date == '0000-00-00 00:00:00'){echo 'N/A';}
					   else {echo date('Y-m-d h:i A', strtotime($objRow1->starting_date));}?></td> 
                      <td><?php $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow1->started_by)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?></td>
                      <td><?php 
                      if($objRow1->closed_by>0){
                      echo date('Y-m-d h:i A', strtotime($objRow1->closing_date)); } else echo 'In Progress';?></td>
                      <td>
                      <?php if($objRow1->closed_by>0){ $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow1->closed_by)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name; } else echo 'N/A'; ?></td>
                      <td>
                      	<?php //echo SITE_URL.'upload/'.$objRow1->Images; 
						if ($objRow1->Images != '' && strpos($objRow1->Images, ',') !== false) { 
						$simg = explode(',', $objRow1->Images);
						foreach($simg as $si){ echo '<img src="'.SITE_URL.'/upload/'.$si.'">&nbsp;'.$rowD[0]->f_name.' '.$rowD[0]->l_name.'('.date('Y-m-d h:i A',strtotime($objRow1->date)).') &nbsp;&nbsp;&nbsp;';
						}
						}
						elseif ($objRow1->Images != '' && strpos($objRow1->Images, ',') === false)
						{
							echo '<img src="'.SITE_URL.'/upload/'.$objRow1->Images.'" width="60">&nbsp;' .$rowD[0]->f_name.' '.$rowD[0]->l_name.'('.date('Y-m-d h:i A',strtotime($objRow1->date)).')';
						}
						else { echo '&nbsp;';}?>
                      </td>
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
  <!--/.container -->
</div>
<?php
}  // End of Function
protected function admin_property_jobHistory($objRs1, $objRow){
	$propertyhstrydata = '';
	$report_ids = $this->getReportsDetails($objRow->id);
		$propertyhstrydata .= '
   <script type="text/javascript"> 
   function jobhistoryloadreportcontent(taburl)
	{
		$("#jobhistoryreportdata").html(\'<div class="rpt_loader"><img src="'.SITE_URL.'/assets/img/ajax-loader.gif" /></div>\');
		$.ajax({
			url: taburl, 
			success: function(result){
				$("#jobhistoryreportdata").html(result);
			}
		});
		return false;
	}
	$(\'.datatable\').dataTable( {} );
	</script> 			     
    <div class="row">					       
      <div class="col-md-12">						         
        <h4 class="text-left heding_6">Job History</h4>						         
        <div class="widget box box-vas">							 							           
          <div class="widget-content widget-content-vls">                                             
            <form method="post" name="frmListing"> 
            <div class="col-md-12 text-right" style="padding-bottom:10px">
            
              <span>										                 
                <a href="'.ISP :: AdminUrl('reports/direct/?jid='.$objRow->id).'" class="btn btn-info">Export</a>								               
              </span> 
            </div>                                                 
              <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable1">                                                     
                <thead class="cf">							                   
                  <tr> 
                  <th class="hiddencolumn"></th>                                    
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Location</th>								                     
                    <th align="center" data-hide="phone">Address</th>                                     
                    <th align="center" data-hide="phone">Assigned To</th>                                     
                    <th align="center" data-hide="phone">Phone Number</th>                                     
					<!--th align="center" data-hide="phone">Important Notes</th-->                                     
                    <th align="center" data-hide="phone">Assigned Location</th>                                            
                    <th align="center" data-hide="phone" class="hideexport">Completion Date</th>                                                                                                                                                                                                                                                                                               
				            <th align="center" data-hide="phone" class="hideexport">Action</th>       
                  </tr>						                 
                </thead>						                 
                <tbody>';                       
	    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';       			                   
                  $propertyhstrydata .= '<tr>
                  <td class="hiddencolumn"></td>
                  <td><?php echo $intI-1;?></td>
                      <td>'.$objRow->job_listing.'</td> 
                      <td>'.$objRow->location_address.'</td>
                      <td>'.ucfirst(str_replace('_',' ',$objRow->assigned_to)).'</td>
                      <td>'.$objRow->phn_no.'</td> 
                      <td>'.$this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRow->location_id)).'                      
                      </td>                                    
                     <td align="center" class="hideexport">';
					 if($objRow->completion_date != '0000-00-00 00:00:00') 
					 	$propertyhstrydata .=  date('Y-m-d h:i A',strtotime($objRow->completion_date));
					 else
					 	$propertyhstrydata .=  'N/A';
						$propertyhstrydata .= '</td>
                     <td align="center" class="hideexport"><a href="'.ISP :: AdminUrl('reports/reset-property/id/'.$objRow->id).'">Reset</a>
                     </td>                               
                   </tr>                            
                </tbody>                       
              </table>  
               <br/>  
              <p><strong>Submitted Reports :</strong></p>
              	<ul class="jobhistoryproperty">';
				$repid = array();
					 while($rids = $report_ids->fetch_object())
                    { 
					if(!in_array($rids->report_id, $repid)){
                        $propertyhstrydata .= '<li><a href="javascript:void(0)" onclick="jobhistoryloadreportcontent(\''.ISP :: AdminUrl('reports/jobhistory-property-reports/?rid='.$rids->report_id.'&pid='.$objRow->id).'\'); return false;">'.$this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$rids->report_id)).'</a></li>';
                     $repid[] = $rids->report_id;
					}
					}
                $propertyhstrydata .= '</ul>
                <div id="jobhistoryreportdata"></div>
              <table id="dataTables-example" class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                                     
                <thead class="cf">							                   
                  <tr>    
                  <th class="hiddencolumn"></th>                                 
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Started On</th>								                     
                    <th align="center" data-hide="phone">Job Started By</th>                                     
                    <th align="center" data-hide="phone">Completed On</th>                                     
                    <th align="center" data-class="expand">Completed By</th>                      
                    <th align="center" data-class="expand">Uploaded Images</th>                                     
                          
                  </tr>						                 
                </thead>						                 
                <tbody> ';                      	
			if($objRs1): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow1 = $objRs1->fetch_object())  // Fetch the result in the object array
			  { 
    				if($intI++%2==0)  // Condition for alternate rows color
    				   $strCss='evenTr';
    				else
    				   $strCss='oddTr';       			                   
                  $propertyhstrydata .= '<tr>
                  <td class="hiddencolumn"></td>
                  <td>'.($intI-1).'</td>
                      <td>';
					  if($objRow1->starting_date == '0000-00-00 00:00:00'){$propertyhstrydata .=  'N/A';}
					   else {$propertyhstrydata .=  date('Y-m-d h:i A', strtotime($objRow1->starting_date));}
					   $propertyhstrydata .= '</td> 
                      <td>'.$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow1->started_by)) . $rowD[0]->f_name.' '.$rowD[0]->l_name.'</td>
                      <td>'; 
                      if($objRow1->closed_by>0){
                      $propertyhstrydata .=  date('Y-m-d h:i A', strtotime($objRow1->closing_date)); } else $propertyhstrydata .=  'In Progress';
					  $propertyhstrydata .= '</td>
                      <td>';
                      if($objRow1->closed_by>0){ $rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow1->closed_by)); $propertyhstrydata .=  $rowD[0]->f_name.' '.$rowD[0]->l_name; } else $propertyhstrydata .=  'N/A';
					  $propertyhstrydata .= '</td>
                      <td>';
						if ($objRow1->Images != '' && strpos($objRow1->Images, ',') !== false) { 
						$simg = explode(',', $objRow1->Images);
						foreach($simg as $si){ $propertyhstrydata .=  '<img src="'.SITE_URL.'/upload/'.$si.'">&nbsp;'.$rowD[0]->f_name.' '.$rowD[0]->l_name.'('.date('Y-m-d h:i A',strtotime($objRow1->date)).')&nbsp;&nbsp;&nbsp;';
						}
						}
						elseif ($objRow1->Images != '' && strpos($objRow1->Images, ',') === false)
						{
							$propertyhstrydata .=  '<img src="'.SITE_URL.'/upload/'.$objRow1->Images.'" width="60">&nbsp;'.$rowD[0]->f_name.' '.$rowD[0]->l_name.'('.date('Y-m-d h:i A',strtotime($objRow1->date)).')';
						}
						else { $propertyhstrydata .=  '&nbsp;';}
                      $propertyhstrydata .= '</td>
                     </tr> ';                           
			   }
      else:
			       $propertyhstrydata .=  '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
			endif;                            
                $propertyhstrydata .= '</tbody>                       
              </table>           
              <input type="hidden" name="status" value="1" />                       
              <input type="hidden" name="task" value="modifyjoblocation" />                   
            </form>
          </div>						         
        </div>					       
      </div>				                                       
    </div>';
	echo $propertyhstrydata; die;
}  // End of Function 
   protected function admin_piechartuserdetails($objRs)
   {
	   			$sid_array = explode(',', $_GET['sid']);
						 ?> 
                 <script type="text/javascript">
$(document).ready(function() {
	//$('.datetimepicker').datetimepicker();
	$('.datetimepicker').datetimepicker({
        format : 'Y-m-d h:i A'
    });
    $('.datatable').dataTable( {} );
} ); 
</script>         
<div id="content">			
  <div class="container">               
    <h3><?php if(isset($_GET['title'])) echo $_GET['title'];?></h3>				
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
				foreach($sid_array as $sa){
                $staff_log = $this->objFunction->getStaffLogDetails($sid_array);
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
                                <td align="center"><?php echo date('h:i A',strtotime($ivrdatetimestatus[1]));?></td>
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
    <!-- /Normal --> 			   
  </div>			   
  <!-- /.container --> 		 
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
	 $exportdata = '';
$exportdata .= '<script type="text/javascript">
			$(".datetimepicker").appendDtpicker({
				dateFormat: "YYYY-MM-DD H:mmTT", 	
				"amPmInTimeList": true,
				"closeOnSelected": true,
				"autodateOnStart": false
				});
		$(".del_exprepzip").click(function(){ 
				var sids = $(".exprepzipcheck:checked").map(function() {
    			return this.value;
				}).get().join(",");
				if(sids != ""){
					$(".del_exprepzip").attr("href", "'.ISP :: AdminUrl('reports/remove-bulk-exportreport/?erzipid="+sids').');
					$("#report_content").html("<div class=\"rpt_loader\"><img src=\"'.SITE_URL.'/assets/img/ajax-loader.gif \" /></div>");
					$.ajax({url: "'.ISP :: AdminUrl('reports/remove-bulk-exportreport/').'?erzipid="+sids, success: function(result){ 
          	 		$.ajax({url: "'.ISP :: AdminUrl('reports/export-reports/').'", success: function(result){$("#report_content").html(result);}});
       			}
				});
			}
				return false;
			});
	$(".cdaterange").click(function(){
			var id = $(this).val();
			$("#"+id).show();
		});
   	$(".datatable").dataTable( {} ); 
</script>
<form method="post" id="exportform">
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-left heding_6">Export Reports Wizard</h4>
				<div class="widget box box-vas">
					<div class="widget-content widget-content-vls">
						<div class="form_holder">
							<fieldset>
								<legend>Select what you want to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive">
									<tr>
										<td>
											<input type="checkbox"  class="uniform"  value="all_state_reports" name="export[]" onclick=" $(\'#allstate_report\').toggle(); $(\'#allstate_report\').find(\'select\').attr(\'disabled\',!this.checked);">All Property Reports
										</td>
										<td>
											<input type="checkbox" class="uniform"  value="single_location_reports" name="export[]" onclick=" $(\'#singlelocation_report\').toggle(); $(\'#singlelocation_report\').find(\'select\').attr(\'disabled\',!this.checked);">Single Location Reports
										</td>  
                                        <td>                            
											<input type="checkbox" class="uniform"  value="individual_reports" name="export[]" onclick=" $(\'#individual_report\').toggle(); $(\'#individual_report\').find(\'select\').attr(\'disabled\',!this.checked);">Individual Reports
										</td>                                         
									</tr>                    
									<tr>                        
										                       
										<td>                            
											<input class="uniform"  type="checkbox" value="manager_reports" name="export[]" onclick=" $(\'#manager_report\').toggle(); $(\'#manager_report\').find(\'select\').attr(\'disabled\',!this.checked);">Manager Reports                      
										</td>                        
										<td>                            
										</td>   
                                        <td>&nbsp;</td>                                        
									</tr>                    
								</table>                
							</fieldset>                                                   
						</div>  

                        <div class="form_holder">
                            <fieldset id="individual_report" style="display: none;">
								<legend>Select Individual Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive">
									<tr>
										<td>
											<select name="duration_individual_report" class="cdaterange">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                                <option value="i-rep-date">Custom</option>
                                            </select>
										</td>
                                        <td id="i-rep-date" style="display:none;">
                                           <input type="text" class="datetimepicker" name="idate_from" placeholder="From" value="" />
                        <input type="text" class="datetimepicker" name="idate_to" placeholder="To" value="" />
                                        </td>
										<td>
                                            <select name="report_individual_report">
                                               '.$this->objFunction->reportsDropDown().'
                                            </select>
										</td>                  
									</tr>                    
                  
								</table>                
							</fieldset> 

                            <fieldset id="allstate_report" style="display: none;">
								<legend>All States Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive">
									<tr>
										<td>
											<select name="duration_allstate_report" class="cdaterange">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                                <option value="as-rep-date">Custom</option>
                                            </select>
										</td>
                                        <td id="as-rep-date" style="display:none;">
                                           <input type="text" class="datetimepicker" name="asdate_from" placeholder="From" value="" />
                        <input type="text" class="datetimepicker" name="asdate_to" placeholder="To" value="" />
                                        </td>               
									</tr>              
                                </table>                
							</fieldset> 
                            
                            <fieldset id="singlelocation_report" style="display: none;">
								<legend>Single Location Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive">
									<tr>
										<td>
											<select name="duration_singlelocation_report" class="cdaterange">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                                <option value="sl-rep-date">Custom</option>
                                            </select>
										</td>  
										<td id="sl-rep-date" style="display:none;">
                                           <input type="text" class="datetimepicker" name="sldate_from" placeholder="From" value="" />
                        <input type="text" class="datetimepicker" name="sldate_to" placeholder="To" value="" />
                                        </td>
                                        <td>
											<select name="location_singlelocation_report">
                                              '.$this->objFunction->locationDropdown().'
                                            </select>
										</td>                                        
									</tr>              
                                </table>                
							</fieldset>
                            
                            <fieldset id="manager_report" style="display: none;">
								<legend>Manager Report to export</legend>
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive">
									<tr>
										<td>
											<select name="duration_manager_report" class="cdaterange">
                                                <option value="">Select Duration</option>
                                                <option value="7">Last 7 days</option>
                                                <option value="15">Last 15 days</option>
                                                <option value="30">Last 1 Month</option>
                                                <option value="m-rep-date">Custom</option>
                                            </select>
										</td>  
										<td id="m-rep-date" style="display:none;">
                                           <input type="text" class="datetimepicker" name="mdate_from" placeholder="From" value="" />
                        <input type="text" class="datetimepicker" name="mdate_to" placeholder="To" value="" />
                                        </td>
                                        <td>
											<select name="staff_manager_id">
                                              '.$this->objFunction->staffDropDown().'
                                            </select>
										</td>                                        
									</tr>              
                                </table>                
							</fieldset>
                            <input type="hidden" value="export-report" name="task">
							<input type="submit" value="Export Report(s)" name="export_btn" class="btn btn-info" id="expreportform">';
                            if ($export_file != '') {
                            $exportdata .= '<a href="'.SITE_URL.$export_file.'" target="_blank">Download File</a>';
                            }
                            $exportdata .= '<a href="'.ISP :: AdminUrl('reports/remove-bulk-exportreport/').'" class="btn btn-danger btn-ms del_exprepzip" style="float: right; margin-right: 140px; margin-bottom: 10px;">Delete</a>
                        </div>
					
                        <div class="form_holder">
                         <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">
                          <thead class="cf">
                            <tr> 
                            	<th class="hiddencolumn"></th> 
                           		<th align="center" data-hide="phone" class="hideexport divchecker"><input type="checkbox" class="uniform" name="del[]" onclick="selectDeselect(\'delete[]\');"/></th>
                                <th data-class="expand">File Name</th>
                                <th data-hide="phone">Creation Date</th>
                                <th data-hide="phone">Download File</th>
                                <th data-hide="phone">Remove File</th>
                            </tr>
                          </thead>
                          <tbody>';
                            $glob = glob("upload/zip/{*.zip}", GLOB_BRACE);
                            for($i=0; $i< count($glob); $i++) {            
                                $file = $glob[$i];
                                $filename = basename($file);
                                if(strstr($filename, "export-reports-")) {  
                            $exportdata .= '<tr>
                            	<td class="hiddencolumn"></td>
                            	<td class="hideexport divchecker"><input type="checkbox" class="uniform exprepzipcheck" name="delete[]" value="'.$filename.'" /></td>
                                <td>'.$filename.'</td>
                                <td>'.date("Y-m-d h:i A", strtotime(str_replace('_', ' ', substr($filename, 15,-4)))).'</td>
                                <td><a href="'.SITE_URL.$file.'" target="_blank">Download</a></td>
                                <td>
                                <a href="javascript:void(0)" onclick="removeexportzip(\''.SITE_ADMINURL.'index.php?dir=reports&task=removezip&file='.$filename.'\'); return false;">Remove</a>
                                </td>
                            </tr>';
                                }
                            }  
                          $exportdata .= '</tbody>
                          </table>
                        </div>
                    </div>                         
				</div>                   
			</div>            		
		</div>
</form>
<a href="" id="download_link" style="display:none;"></a>';
echo $exportdata; die;
}
protected function SessionSeasonReset($objRs) {
	$sessionresetdata = '';
$sessionresetdata .= '<script type="text/javascript">
		$(".del_sessionzip").click(function(){ 
				var sids = $(".sessionzipcheck:checked").map(function() {
    			return this.value;
				}).get().join(",");
				if(sids != ""){
					$(".del_sessionzip").attr("href", "'.ISP :: AdminUrl('reports/remove-bulk-sessionzip/').'?sszipid="+sids); 
					$("#report_content").html("<div class=\"rpt_loader\"><img src=\"'.SITE_URL.'/assets/img/ajax-loader.gif\" /></div>");
					$.ajax({url: "'.ISP :: AdminUrl('reports/remove-bulk-sessionzip/').'?sszipid="+sids, success: function(result){ 
          	 		$.ajax({url: "'.ISP :: AdminUrl('reports/session_season_reset/').'", success: function(result){$("#report_content").html(result);}});
       			}
				});
			}
				return false;
			});
			$(".datatable").dataTable( {} );
</script>
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-left heding_6">Session Reset</h4>
				<div class="widget box box-vas">
					<div class="widget-content widget-content-vls">
                        <ul class="sub_tab_nav">
                           <li class="active">
                            	<a onclick="loadreportcontent(\''.ISP :: AdminUrl('reports/session_season_reset/').'\')" href="#">
                            Session Reset
                            </a>
                            </li>
                            <li>
                           		<a onclick="loadreportcontent(\''.ISP :: AdminUrl('reports/season_reset/').'\')" href="#">
                            Season Reset 
                            </a>
                            </li>
                        </ul>
                        <div class="col-md-12 text-right" style="padding-bottom:10px">
                        	<a href="'.ISP :: AdminUrl('reports/remove-bulk-sessionzip/').'" class="btn btn-danger btn-ms del_sessionzip">Delete</a>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">	 	
                        	<thead class="cf">							                   
                        		<tr>
                                    <th align="center"  data-class="expand">File Name</th>
                                    <th align="center" data-hide="phone">Creation Date</th>
                                    <th align="center" data-hide="phone">Download File</th>
                                    <th align="center" data-hide="phone">Remove File</th>
                                    <th align="center" data-hide="phone" class="hideexport divchecker"><input type="checkbox" class="uniform" name="del[]" onclick="selectDeselect(\'delete[]\');"/></th>					                   
                        		</tr>						                 
                        	</thead>						                 
                        	<tbody>'; 
                                if($objRs): // Check for the resource exists or not
                                $intI=1;
                                
                                while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
                                {
                                if($intI++%2==0)  // Condition for alternate rows color
                                $strCss='evenTr';
                                else
                                $strCss='oddTr';        			                   
                                $sessionresetdata .= '<tr>
                                <td>'.$objRow->filename.'</td>
                                <td>'.date('Y-m-d h:i A', strtotime($objRow->creation_date)).'</td>
                                <td><a target="_blank" href="'.SITE_URL.'sessionzip/'.$objRow->filename.'">Download</a></td>
                                <td>
                                
                                 <a href="javascript:void(0)" onclick="removesessionzip(\''.ISP :: AdminUrl().'/index.php?dir=reports&task=removesessionzip&file='.$objRow->filename.'\'); return false;">Remove</a>
                                </td>
                                 <td class="hideexport divchecker"><input type="checkbox" class="uniform sessionzipcheck" name="delete[]" value="<?php echo $objRow->id;?>" /></td>                        
                                </tr>';                           
                                }
                                else:
                                $sessionresetdata .= '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
                                endif;  
                            $sessionresetdata .= '</tbody>                       
                        </table> 
                        <a class="btn btn-info" href="javascript:void(0)" onclick="createsessionzip(\''.ISP :: AdminUrl().'index.php?dir=reports&task=sessionzip\'); return false;">Reset Session</a>
                    
                    </div>                         
				</div>                   
			</div>            		
		</div>';
		echo $sessionresetdata; die;
}
protected function SeasonReset($objRs) {
	$seasonresetdata = '';
$seasonresetdata .= '<script type="text/javascript">
			$(".datatable").dataTable( {} );
		</script>
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-left heding_6">Season Reset</h4>
				<div class="widget box box-vas">
					<div class="widget-content widget-content-vls">
                        <ul class="sub_tab_nav">
                            <li>
                            	<a onclick="loadreportcontent(\''.ISP :: AdminUrl('reports/session_season_reset/').'\')" href="#">
                            Session Reset
                            </a>
                            </li>
                            <li class="active">
                           		<a onclick="loadreportcontent(\''.ISP :: AdminUrl('reports/season_reset/').'\')" href="#">
                            Season Reset 
                            </a>
                            </li>
                        </ul>
                        <p>
                    </p>
                        <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">	 	
                        	<thead class="cf">							                   
                        		<tr>
                                    <th align="center"  data-class="expand">File Name</th>
                                    <th align="center" data-hide="phone">Creation Date</th>
                                    <th align="center" data-hide="phone">Download File</th>
                                    <th align="center" data-hide="phone">Remove File</th>					                   
                        		</tr>						                 
                        	</thead>						                 
                        	<tbody>'; 
                                if($objRs): // Check for the resource exists or not
                                $intI=1;
                                
                                while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
                                {
                                if($intI++%2==0)  // Condition for alternate rows color
                                $strCss='evenTr';
                                else
                                $strCss='oddTr';
								if(isset($_GET['s'])){
									if(strtotime($objRow->creation_date) >= strtotime($_GET['date_from']) && strtotime($objRow->creation_date) <= strtotime($_GET['date_to'])):         			                   
                                $seasonresetdata .= '<tr>
                                <td>'.$objRow->filename.'</td>
                                <td>'.date('Y-m-d h:i A', strtotime($objRow->creation_date)).'</td>
                                <td><a target="_blank" href="'.SITE_URL.'seasonzip/'.$objRow->filename.'">Download</a></td>
                                <td><a href="javascript:void(0)" onclick="removeseasonzip(\''.ISP :: AdminUrl().'/index.php?dir=reports&task=removeseasonzip&file='.$objRow->filename.'\'); return false;">Remove</a>
                                </td>                        
                                </tr>';                            
                                endif;
								}
								else
								{
								$seasonresetdata .= '<tr>
                                <td>'.$objRow->filename.'</td>
                                <td>'.date('Y-m-d h:i A', strtotime($objRow->creation_date)).'</td>
                                <td><a target="_blank" href="'.SITE_URL.'seasonzip/'.$objRow->filename.'">Download</a></td>
                                <td><a href="javascript:void(0)" onclick="removeseasonzip(\''.ISP :: AdminUrl().'/index.php?dir=reports&task=removeseasonzip&file='.$objRow->filename.'\'); return false;">Remove</a>
                                </td>                        
                                </tr> ';
								}
                                }
                                else:
                                $seasonresetdata .=  '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
                                endif;  
                            $seasonresetdata .=  '</tbody>                       
                        </table> 
                      
                        <a class="btn btn-info" href="javascript:void(0)" onclick="createseasonzip(\''.ISP :: AdminUrl().'index.php?dir=reports&task=seasonzip\'); return false;">Reset Season</a>
                    </div>                        
				</div>                   
			</div>            		
		</div>';
		echo $seasonresetdata; die;
}

protected function admin_completedProperties($objRs){
	$completedjobdata = '';
$completedjobdata .= '<script type="text/javascript">
    $(\'.datatable\').dataTable( {} );
</script>		     
    <div class="row">					       
      <div class="col-md-12">						         				         
        <div class="widget box box-vas">							 							           
          <div class="widget-content widget-content-vls">                                             
            <form method="post" name="frmListing"> 
            <div class="col-md-12 text-right" style="padding-bottom:10px">
            
              <span>
                <a href="javascript: void(0);" onclick="fn_resetalljob(); return false;" class="btn btn-danger btn-ms">Reset All</a>									               
                <a href="javascript: void(0);" onclick="exportTableToCSV.apply(this, [$(\'#dataTables-example\'), \'export-joblocations.csv\']);" class="btn btn-info">Export</a>									
              </span> 
            </div>                                                 
              <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable">                                                     
                <thead class="cf">							                   
                  <tr>
                  <th class="hiddencolumn"></th>                                     
                    <th align="center">S.N.</th>								                     
                    <th align="center"  data-class="expand">Job Location</th>								                     
                    <th align="center" data-hide="phone">Address</th>                                     
                    <th align="center" data-hide="phone">Assigned To</th>                                     
                    <th align="center" data-hide="phone">Phone Number</th>                                     
                    <th align="center" data-hide="phone">Important Notes</th>                                     
                    <th align="center" data-hide="phone">Assigned Location</th>                                                                
                    <th align="center" data-hide="phone" class="hideexport">Completion Date</th>                     <th align="center" data-hide="phone" class="hideexport">Action</th>       
                  </tr>						                 
                </thead>						                 
                <tbody> ';                    
			if($objRs): // Check for the resource exists or not
			 $intI=1;
			 
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {
			    $strStatus = ($objRow->status)?'0':'1';
				
				if($intI++%2==0)  // Condition for alternate rows color
				   $strCss='evenTr';
				else
				   $strCss='oddTr';         			                   
                 $completedjobdata .= ' <tr>
                  <td class="hiddencolumn"></td>
                  <td>'.($intI-1).'</td>
                      <td>'.$objRow->job_listing.'</td> 
                      <td>'.$objRow->location_address.'</td>
                      <td>'.$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->assigned_to)) .$rowD[0]->f_name.' '.$rowD[0]->l_name.'
                      </td>
                      <td>'.$objRow->phn_no.'</td> 
                      <td>'.$objRow->importent_notes.'</td>
                      <td>'.$this->objFunction->iFind(TBL_SERVICE,'name', array('id'=>$objRow->location_id)).'
                      </td>                                    
                     <td align="center" class="hideexport">'.$objRow->completion_date.'</td>
                     <td align="center" class="hideexport"><a href="javascript: void(0);" onclick="fn_resetjob(\''.$objRow->id.'\'); return false;">Reset</a></td>                               
                   </tr>';                            
			   }                           
			 else:
			       $completedjobdata .= '<tr><td colspan="5" class="errNoRecord">No Record Found!</td></tr>';
			 endif;  
              $completedjobdata .= '</tbody>                       
              </table><input type="hidden" name="status" value="1" />                       
              <input type="hidden" name="task" value="modifyjoblocation" />                   
            </form>						 		           
          </div>						         
        </div>					       
      </div>				                                       
    </div>'; 
	echo $completedjobdata; die;
}  // End of Function
   
} // End of Class
?>