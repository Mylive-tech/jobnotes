<?php 

	/* **************************************************
	Project		: Jobnote
	Author		: CT
	Edited By	: Manish Sharma
	Doc Role	: Contains definition and declarations of common functions used for various  in whole website
	************************************************** */

	class ISP {

		private $objDBCon;

		private $strSql;

		private $intId;

		private $strOption;

		private $intSelect;

		private $firstSelect;

		public $intNId;

                public $widgetContentArray;   
                private $propImages;
                public $staff_job_title;

		function __construct() { 
                   $this->objDBCon = new Database();  
                   $this->staff_job_title = array('1'=>'Driver', '2'=>'Sidewalk Staff', '3'=>'Sub-Contractor');    
                   $this->free_default_widget();
		}

    

    public function getCurrentUrl()
    {

      $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === 

      FALSE ? 'http' : 'https';        

      $host     = $_SERVER['HTTP_HOST'];  

      $script   = $_SERVER['REQUEST_URI']; 

      $params   = $_SERVER['QUERY_STRING'];      

      $currentUrl = $protocol . '://' . $host . $script;

      return $currentUrl; 

    }
    
    public function sizeFormatter( $bytes )
    {
        $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
        return( round( $bytes, 2 ) . " " . $label[$i] );
    }

    

    

    public function getPropertyReportStatus($propId, $repId)
    {

      $objRs = $this->objDBCon->fetchRows("SELECT rs.* from ".TBL_REPORTS_SUBMISSION." rs  where rs.report_id='".$repId."' and rs.property_id='".$propId."' order by id desc limit 1");

      return $objRs;

    }

     

    public function _cleanup_header_comment($str)
    {

    	return trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $str));

    }

    

    public function get_file_data( $file)
    {

      	$all_headers = array(

        		'Name' => 'Plugin Name',

        		'PluginURI' => 'Plugin URI',

        		'Version' => 'Version',

        		'Description' => 'Description',

        		'Author' => 'Author',

        		'AuthorURI' => 'Author URI' ,

            'Site Plugin' => 'Site Plugin', 

            'Icon Class' => 'Icon Class'

        	);

  

      	// We don't need to write to the file, so just open for reading.

      	$fp = fopen( $file, 'r' );

      

      	// Pull only the first 8kiB of the file in.

      	$file_data = fread( $fp, 8192 );

      

      	// PHP will close file handle, but we are good citizens.

      	fclose( $fp );

      

      	// Make sure we catch CR-only line endings.

      	$file_data = str_replace( "\r", "\n", $file_data );      	

      

      	foreach ( $all_headers as $field => $regex ) {

      		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )

      			$all_headers[ $field ] = $this->_cleanup_header_comment( $match[1] );

      		else

      			$all_headers[$field] = '';

      	}

      

      	return $all_headers;

    }

    

    public function loadPlugins($objFunction) {

      $moduleArray = $this->objDBCon->dbFetch('select module_dir from '.TBL_MODULES.' where status=1 and id>1 order by id desc'); 
        foreach($moduleArray as $modObj) {
           $modArr[] =  $modObj->module_dir;
        }

         //$directories = scandir(SITE_ABSPATH . '/plugins/');        

          foreach($modArr as $dirName) {

            if(is_dir(SITE_ABSPATH.'/plugins/'.$dirName)) {
              include_once SITE_ABSPATH.'/plugins/'.$dirName.'/'.$dirName.'.php';
              $obj = new $dirName($objFunction);

            }             

          }

    }

    public function free_default_widget() {
        ob_start();
    ?>  
    <script type="text/javascript">
      $(document).ready(function(){       
        $("#untouchedbox div:first-child").mouseenter(function() {
          $("#widgetsorting .sortable" ).sortable('disable');
        });  
        $("#untouchedbox div:first-child").mouseleave(function() {
          $("#widgetsorting .sortable" ).sortable('enable');
        }); 
        $("#inprogressbox div:first-child").mouseenter(function() {
          $("#widgetsorting .sortable" ).sortable('disable');
        });  
        $("#inprogressbox div:first-child").mouseleave(function() {
          $("#widgetsorting .sortable" ).sortable('enable');
        }); 
        $("#completedbox div:first-child").mouseenter(function() {
          $("#widgetsorting .sortable" ).sortable('disable');
        });  
        $("#completedbox div:first-child").mouseleave(function() {
          $("#widgetsorting .sortable" ).sortable('enable');
        }); 
		$("#pausedbox div:first-child").mouseenter(function() {
          $("#widgetsorting .sortable" ).sortable('disable');
        });  
        $("#pausedbox div:first-child").mouseleave(function() {
          $("#widgetsorting .sortable" ).sortable('enable');
        });
      });
    </script>                   
        <div class="widget-header dragwidget_location_status_details">                         
            <i class="icon-reorder"></i>&nbsp;&nbsp;<h4>Location Status & Details</span></h4>                         
            <div class="toolbar no-padding">        	                   
              <div class="btn-group">        		                     
                <span class="btn btn-xs widget-collapse">                      
                  <i class="icon-angle-down"></i>                    
                </span>        	                   
              </div>                         
            </div>                       
        </div>                         
        <div class="widget-content no-padding pinkbg">                         
            <div class="inside_widget_redheading padding10">Untouched locations 
              <i class="icon-angle-down"></i>
            </div>                
            <div class="col-md-12 nopadding" id="untouchedbox">                         
                <div class="col-md-6 full pull-left">                                            
                    <ul class="bxslider">           
                        <?php
                                  $objRs = $this->objDBCon->dbQuery("Select j.*, s.name as section_name from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id and j.site_id='".$_SESSION['site_id']."' where j.progress=0 and j.status=1");
                                  $fistUntouch=0;
                                  while($objProperty = $objRs->fetch_object())
                                  {
                                      if($fistUntouch==0)
                                      $fistUntouch= $objProperty->id;   
                        ?>                                  
                      <li class="<?php if($fistUntouch==$objProperty->id) echo 'activelink';?>"><a class="padding10" href="javascript: void(0);" onclick="showlocactionInfo($(this),<?php echo $objProperty->id;?>);"><b><?php echo $objProperty->job_listing;?></b> (<?php echo $objProperty->section_name;?> )</a></li>           
                        <?php
                                  }
                        ?>                              
                    </ul>                         
                </div>                         
                <div class="col-md-6 nopadding pull-left">         
                    <?php
                    $objRsDetails = $this->objDBCon->dbQuery("Select j.*, s.name as section_name, s.territory from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id where j.progress=0 and j.status=1 and j.site_id='".$_SESSION['site_id']."'");
                    while($objProperty = $objRsDetails->fetch_object())
                    {
                    ?>                            
                    <p id="p_<?php echo $objProperty->id;?>" class="<?php if($fistUntouch==$objProperty->id) echo 'show'; else echo 'hide';?>  insidewidget_locationdetails">
                    <b>Name: </b><?php echo $objProperty->job_listing;?><br>
                    <b>Address: </b><?php echo $objProperty->location_address;?><br>
                    <b>Section: </b><?php echo $objProperty->section_name;?><br>
                    <b>Territory: </b><?php echo $objProperty->territory;?><br>
                    <b>Assigned To: </b><?php  $rowD = $this->iFindAll(TBL_STAFF, array('id'=>$objProperty->assigned_to)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?><br>
                    <b>Phone: </b><?php echo $rowD[0]->phone;?><br>                           
                    </p>         
                    <?php
                    }
                    ?>                                                       
                </div>                 
            </div>                  
            <div class="inside_widget_progressheading padding10">In-Progress Locations 
              <i class="icon-angle-down"></i>
            </div>                  
            <div class="col-md-12 nopadding inprog" id="inprogressbox" style="display:none">                        
              <div class="col-md-6 full pull-left">                                            
                <ul class="bxslider">           
                    <?php
                    $objRs = $this->objDBCon->dbQuery("Select j.*, s.name as section_name from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id and j.site_id='".$_SESSION['site_id']."' where j.progress=1 and j.status=1");
                    $fistUntouch=0;
                    while($objProperty = $objRs->fetch_object())
                    {
                    if($fistUntouch==0)
                    $fistUntouch= $objProperty->id;
                              ?>                                  
                              <li class="<?php if($fistUntouch==$objProperty->id) echo 'activelink';?>"><a class="padding10" href="javascript: void(0);" onclick="showlocactionInfo($(this),<?php echo $objProperty->id;?>);"><b><?php echo $objProperty->job_listing;?></b> (<?php echo $objProperty->section_name;?> )</a></li>           
                    <?php
                    }
                    ?>                              
                </ul>                         
              </div>                         
              <div class="col-md-6 pull-left inprogdiv2">         
                <?php
                $objRsDetails = $this->objDBCon->dbQuery("Select j.*, s.name as section_name, s.territory from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id where j.progress=1 and j.status=1 and j.site_id='".$_SESSION['site_id']."'");
                while($objProperty = $objRsDetails->fetch_object())
                {
                ?>                            
                <p id="p_<?php echo $objProperty->id;?>" class="<?php if($fistUntouch==$objProperty->id) echo 'show'; else echo 'hide';?>  insidewidget_locationdetails">
                <b>Name: </b><?php echo $objProperty->job_listing;?><br>
                <b>Address: </b><?php echo $objProperty->location_address;?><br>
                <b>Section: </b><?php echo $objProperty->section_name;?><br>        
                <b>Territory: </b><?php echo $objProperty->territory;?><br>
                <b>Assigned To: </b><?php $rowD = $this->iFindAll(TBL_STAFF, array('id'=>$objProperty->assigned_to)); echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?><br
                <b>Phone: </b><?php echo $rowD[0]->phone;?><br> 
                <b>Started On: </b> <?php echo strftime("%m/%d/%Y %I:%M %p", strtotime($objProperty->start_date));?><br>        
                </p>         
                <?php
                }
                ?>                                                       
              </div>                            
            </div>                                 
            <div class="inside_widget_greenheading padding10">Completed Locations 
              <i class="icon-angle-down"></i>
            </div>                        
            <div class="col-md-12 nopadding" id="completedbox" style="display:none">                
                <div class="col-md-6 full pull-left">                                            
                    <ul class="bxslider">           
                        <?php
                        $objRs = $this->objDBCon->dbQuery("Select j.*, s.name as section_name from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id and j.site_id='".$_SESSION['site_id']."' where j.progress=2 and j.status=1");
                        $fistUntouch=0;
                        while($objProperty = $objRs->fetch_object())
                        {
                        if($fistUntouch==0)
                        $fistUntouch= $objProperty->id;
                        ?>                                  
                        <li class="<?php if($fistUntouch==$objProperty->id) echo 'activelink';?>"><a class="padding10" href="javascript: void(0);" onclick="showlocactionInfo($(this),<?php echo $objProperty->id;?>);"><b><?php echo $objProperty->job_listing;?></b> (<?php echo $objProperty->section_name;?> )</a></li>           
                        <?php
                        }
                        ?>                              
                    </ul>                         
                </div>                         
                <div class="col-md-6 nopadding pull-left">         
                    <?php
                    $objRsDetails = $this->objDBCon->dbQuery("Select j.*, s.name as section_name, s.territory from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id where j.progress=2 and j.status=1 and j.site_id='".$_SESSION['site_id']."'");
                    while($objProperty = $objRsDetails->fetch_object())
                    {
                    ?>                            
                        <p id="p_<?php echo $objProperty->id;?>" class="<?php if($fistUntouch==$objProperty->id) echo 'show'; else echo 'hide';?>  insidewidget_locationdetails">
                        <b>Name: </b><?php echo $objProperty->job_listing;?><br>
                        <b>Address: </b><?php echo $objProperty->location_address;?><br>
                        <b>Section: </b><?php echo $objProperty->section_name;?><br>
                        <b>Territory: </b><?php echo $objProperty->territory;?><br>
                        <b>Assigned To: </b><?php $rowD = $this->iFindAll(TBL_STAFF, array('id'=>$objProperty->assigned_to));  echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?><br>
                        <b>Phone: </b><?php echo $rowD[0]->phone;?><br>  
                        <b>Completed On: </b><?php echo strftime("%m/%d/%Y %I:%M %p", strtotime($objProperty->completion_date));?><br>
                        </p>         
                    <?php
                    }
                    ?>                                                       
                </div>                
            </div>
            <div class="inside_widget_pauseheading padding10">Properties Paused
              <i class="icon-angle-down"></i>
            </div>                        
            <div class="col-md-12 nopadding" id="pausedbox" style="display:none">                
                <div class="col-md-6 full pull-left">                                            
                    <ul class="bxslider">           
                        <?php
                        $objRs = $this->objDBCon->dbQuery("Select j.*, s.name as section_name from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id and j.site_id='".$_SESSION['site_id']."' where j.progress=3 and j.status=1");
                        $fistUntouch=0;
                        while($objProperty = $objRs->fetch_object())
                        {
                        if($fistUntouch==0)
                        $fistUntouch= $objProperty->id;
                        ?>                                  
                        <li class="<?php if($fistUntouch==$objProperty->id) echo 'activelink';?>"><a class="padding10" href="javascript: void(0);" onclick="showlocactionInfo($(this),<?php echo $objProperty->id;?>);"><b><?php echo $objProperty->job_listing;?></b> (<?php echo $objProperty->section_name;?> )</a></li>           
                        <?php
                        }
                        ?>                              
                    </ul>                         
                </div>                         
                <div class="col-md-6 nopadding pull-left">         
                    <?php
                    $objRsDetails = $this->objDBCon->dbQuery("Select j.*, s.name as section_name, s.territory from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id =s.id where j.progress=3 and j.status=1 and j.site_id='".$_SESSION['site_id']."'");
                    while($objProperty = $objRsDetails->fetch_object())
                    {
                    ?>                            
                        <p id="p_<?php echo $objProperty->id;?>" class="<?php if($fistUntouch==$objProperty->id) echo 'show'; else echo 'hide';?>  insidewidget_locationdetails">
                        <b>Name: </b><?php echo $objProperty->job_listing;?><br>
                        <b>Address: </b><?php echo $objProperty->location_address;?><br>
                        <b>Section: </b><?php echo $objProperty->section_name;?><br>
                        <b>Territory: </b><?php echo $objProperty->territory;?><br>
                        <b>Assigned To: </b><?php $rowD = $this->iFindAll(TBL_STAFF, array('id'=>$objProperty->assigned_to));  echo $rowD[0]->f_name.' '.$rowD[0]->l_name;?><br>
                        <b>Phone: </b><?php echo $rowD[0]->phone;?><br>  
                        <b>Paused On: </b><?php echo strftime("%m/%d/%Y %I:%M %p", strtotime($objProperty->pause_date));?><br>
                        </p>         
                    <?php
                    }
                    ?>                                                       
                </div>                
            </div>                                            
        </div>                     
    <?php 
        $widget_content = ob_get_contents();
        ob_get_clean();
        $this->widgetContentArray[] = $widget_content;
    }

    public function call_widgets() {
        $moduleArray = $this->objDBCon->dbFetch('select module_dir from '.TBL_MODULES.' where status=1 and id>1 order by id desc'); 
        foreach($moduleArray as $modObj) {
            $modArr[] =  $modObj->module_dir;
        }
      
        $directories = scandir(SITE_ABSPATH . '/plugins/');
       // $this->widgetContentArray[] = $this->free_default_widget();
        foreach($directories as $dirName) {
            if ($dirName === '.' or $dirName === '..') continue;      

            if (is_dir(SITE_ABSPATH . '/plugins/'.$dirName) && in_array($dirName, $modArr)) {
              include_once SITE_ABSPATH.'plugins/'.$dirName.'/'.$dirName.'.php';
              $obj = new $dirName();
              $this->widgetContentArray[] = $obj->$dirName();
            }             

        }

    }

    

    public function common_include($file)
    {
      include_once('includes/'.$file);
    }

    

    public function get_string_between($string, $start, $end)
    {

      $string = " ".$string;

      $ini = strpos($string,$start);

      if ($ini == 0) return "";

      $ini += strlen($start);

      $len = strpos($string,$end,$ini) - $ini;

      return substr($string,$ini,$len);

    }  

		

	function checkPermission($access, $module)
		{

		   $strSql = "select rp.* from ".TBL_ROLEPERMISSION." rp inner join ".TBL_USERPERMISSION." up on rp.id = up.role_permission_id where up.role_id ='".$_SESSION['role']."' and rp.permission='".$access."' and rp.module='".$module."'";

		   $objRs = $this->objDBCon->fetchRows($strSql);

		

		 if($_SESSION['role']==1)

		   return true;

		  

		  	

		  if($objRs->id >0)

		     return true;

		  else

		    return false;	

		}

		

	   static function AdminUrl($url)
	   {

	     return SITE_ADMINURL.$url;

	   }	

	   

	   static function FrontendUrl($url)
	   {

	     return SITE_URL.$url;

	   }

	  

		

		public function validateForm()
		{

		    foreach($_POST as $key=>$val)
			   {

				      if((stripos($key, "md_")!==false) )

						{

						  if($val=='')

						   return 1;

						} 

			   }

			   

			  foreach($_FILES as $key=>$val)
			   {

				      if((stripos($key, "md_")!==false) )
						{

						  if($val['name']=='')

						   return 1;

						} 

			   }  		   

			 return 0;  

		}

    

    function check_role_permission($role_id, $permission_id)
    {

        $objRs = $this->objDBCon->fetchRows("select * from ".TBL_USERPERMISSION." where role_id='".$role_id."' and role_permission_id='".$permission_id."'");

        if(intval($objRs->id)>0)
            return 'checked';
        else
            return ''; 

    } 

		

		function checkDirPermission($dir, $admin)
		{

		  $strSql = "SELECT * FROM tbl_permission p inner join tbl_dirtask dt on p.task_id=dt.id where dt.dir='".$dir."' and p.admin_id='".$admin."'";

			$objRs = $this->objDBCon->dbQuery($strSql);

			 if( $admin==1)
		       return true;		  

		  if($this->objDBCon->countRows()>0)
		     return true;
		  else
		    return false;	

		}

		

function checklogin()
{

		$strMsg='';

		if(isset($_POST['md_username'])):

		$strUsername=(trim($_POST['md_username']));

		$strPassword = md5($_POST['md_password']);

		$strQuery ='SELECT password,username,user_type,f_name,l_name,id from '.TBL_STAFF.' where status=1 and username=\'%s\'';

		$objRecord = $this->objDBCon->fetchRows(sprintf($strQuery,$strUsername));

		if($objRecord->password==$strPassword):

		   $_SESSION['adminid']=$objRecord->id;

		   $_SESSION['admin_type']= $objRecord->user_type;

		   $_SESSION['user_id']= $objRecord->id;

		   $_SESSION['user_name']=$objRecord->name;

		   //@header('Location: '.SITE_URL.'pages/index');

		  $this-> __doRedirect(SITE_URL.'dashboard/dashboard'); 

     

		 // $this->objFunction->__doRedirect(SITE_URL.'pages/index');     

		else:

		 $_SESSION['msglogi']="Username or Password is Incorrect !";

		 //echo  $strMsg='<font color="red">Invalid Username/password.</font>';

		endif;

    else:

	  echo '<font color="red">Data Posting Failed.</font>';

	endif;

}

		

public function dropdown_StaffTypes($tmpId)
{

$this->intId = $tmpId;

$res = $this->objDBCon->dbQuery("select * from ".TBL_STAFFTYPE." where id>1");

$txtOption ='<option value="">--Please Select--</option>';

	while($objRow = $res->fetch_object())	
	{

		if($objRow->id == $tmpId) {

			$txtOption .= '<option value="'.$objRow->id.'" selected>'.ucfirst($objRow->label).'</option>';

        }
        else {
            $txtOption .= '<option value="'.$objRow->id.'">'.ucfirst($objRow->label).'</option>';			

        }

	}

	echo  $txtOption; 

}



public function dropdownTimeZone($tmpId)
{

  $list = DateTimeZone::listAbbreviations(DateTimeZone::PER_COUNTRY, 'US');

  $idents = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, 'US');

    $data = $offset = $added = array();

    foreach ($list as $abbr => $info) {

        foreach ($info as $zone) {

            if ( ! empty($zone['timezone_id'])

                AND

                ! in_array($zone['timezone_id'], $added)

                AND 

                  in_array($zone['timezone_id'], $idents)) {

                $z = new DateTimeZone($zone['timezone_id']);

                $c = new DateTime(null, $z);

                $zone['time'] = $c->format('H:i a');

                $data[] = $zone;

                $offset[] = $z->getOffset($c);

                $added[] = $zone['timezone_id'];

            }

        }

    }

    array_multisort($offset, SORT_ASC, $data);

    $options = array();

    

    foreach ($data as $key => $row) 
    {

      $sel ='';

    //$options[$row['timezone_id']] = $row['time'] . ' - '. $this->objFunction->formatOffset($row['offset']). ' ' . $row['timezone_id'];

     if($row['timezone_id'] == $tmpId) 

       $sel ='selected';                                   

      echo '<option value="'.$row['timezone_id'].'" '.$sel.'>'.$this->formatOffset($row['offset']).' ' . $row['timezone_id'].'</option>';                                  

    }

    

}



public function formatOffset($offset) {

        $hours = $offset / 3600;

        $remainder = $offset % 3600;

        $sign = $hours > 0 ? '+' : '-';

        $hour = (int) abs($hours);

        $minutes = (int) abs($remainder / 60);



        if ($hour == 0 AND $minutes == 0) {

            $sign = ' ';

        }

        return 'GMT' . $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) 

                .':'. str_pad($minutes,2, '0');



}



public function dropdown_StaffUsers($userType,$tmpId='')
{

$this->intId = $tmpId;

$res = $this->objDBCon->dbQuery("select * from ".TBL_STAFF." where user_type='".$userType."'");

$txtOption ='<option value="">--Please Select--</option>';

	while($objRow = $res->fetch_object())	
	{

		if($objRow->id == $tmpId) {

            $txtOption .= '<option value="'.$objRow->id.'" selected>'.ucfirst($objRow->f_name).' '.ucfirst($objRow->l_name).'</option>';

        }
        else {
            $txtOption .= '<option value="'.$objRow->id.'">'.ucfirst($objRow->f_name).' '.ucfirst($objRow->l_name).'</option>';				
        }

	}

	echo  $txtOption; 

}



public function search_staff() { 
	$q=$_GET['q'];

		$my_data=mysql_real_escape_string($q);

		$sql="SELECT f_name FROM ".TBL_STAFF." WHERE f_name LIKE '%$my_data%' ORDER BY f_name";

		$this->objSet =  $this->objDBCon->dbQuery($sql_res);

			while($objRow = $this->objSet->fetch_object())  // Fetch the result in the object array
			{ 		  		 

                $staff=$objRow->f_name;
                echo "$staff\n";
            }
}            

function cleanURL($urlString) {

    $delimiter ='-';

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $urlString);

    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);

    $clean = strtolower(trim($clean, '-'));

    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);  

    return $clean;
}

	

	

function removeUnsed($urlString)
{

    $arrSearch = array("!", "@", "#", "$", "%", "^", "&", "*", "(", ")", ":", "?",",","<",">","/", "~","`", "'"," ",'"',"=");

    $arrReplace = array("-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-", "-","-","-");

    $cleanURL = str_replace($arrSearch, $arrReplace, $urlString);
    
    $arrSe = array("--", "---", "----", "-----");

    $arrRe = array("-", "-", "-", "-");

    $cleanURL = str_replace($arrSe, $arrRe, $cleanURL);

    $cleanURL = str_replace("-", "", $cleanURL);  

    return strtolower($cleanURL);

}
  
static function checkSession() 
{
    if(isSet($_COOKIE['siteAuth']))
    {
        parse_str($_COOKIE['siteAuth']);
        $_SESSION['adminid']= $usr;
        $_SESSION['role']= $role;
        $_SESSION['fname']= $fname;
    }
    

    if(!isset($_SESSION['adminid'])) 
    {				
        $_SESSION['page_refer'] = $_SERVER['HTTP_REFERER'];
        echo "<script>window.top.location.href='".SITE_ADMINURL."msg/session';</script>"; 
    }	

}	//End Function

		

static function __frontSession() {

   if(!isset($_SESSION['user_id'])) 
   {       

          $_SESSION['page_refer'] = $_SERVER['HTTP_REFERER'];		

            Functions::__doRedirect($this->FrontendUrl('users/login'));

    }	

}	

public function setSession($strVariable, $strValue)
{

   $_SESSION[$strVariable] = $strValue;		 

} // End Function

		

public function iFind($table, $find, $match)
{

   $strSQL ='1=1';


  if(count($match) > 0):

     foreach($match as $key => $val)

     {

       $strSQL .= " AND ".$key."='".$val."' ";

     }

  endif;
 $row = $this->objDBCon->fetchRows("SELECT ".$find." from ".$table." where ".$strSQL);
   return $row->$find;

}

public function getCount($table)
{

     $strSQL ='1=1';    

     $item_per_page=10;

     $this->objSet = $this->objDBCon->dbQuery("SELECT count(*) from ".$table." where ".$strSQL);

     $get_total_rows = mysqli_fetch_array($this->objSet); //total records

     return ceil($get_total_rows[0]/$item_per_page);
}

	

public function iFindAll($table,  $match, $orderby='', $customMatch='')
{

    $strSQL ='1=1';
    if(count($match) > 0 && $match<>''):
        foreach($match as $key => $val)
        {
            $strSQL .= " AND ".$key."='".$val."' ";
         }
         
    endif;

    if($customMatch<>'')
    {
        $strSQL .= ' AND '.$customMatch;

    } 
    $row = $this->objDBCon->dbFetch("SELECT * from ".$table." where ".$strSQL.$orderby);
    return $row;
}

public function iShowAll($table)
{
    $strSQL ='1=1';
    $this->objSet= $this->objDBCon->dbFetch("SELECT * from ".$table." where ".$strSQL." order by id asc  ");
    return $this->objSet;
}

public function iFindAllCount($table,$match,$count)
{
    if(count($match) > 0):
        foreach($match as $key => $val)
        {
            $strSQL .= " AND ".$key."='".$val."' ";
        }
     endif;

    $row = $this->objDBCon->dbQuery("SELECT * from ".$table." where 1=1 ".$strSQL." limit 0, ".$count);

    if($row)
    {
        return $row;
    }
  return false;
}

public function iFindOption($table,$find,$match, $selected)
{

    $strSQL ='1=1';

    if(count($match) > 0):

        foreach($match as $key => $val)
           {

             $strSQL .= " AND ".$key."='".$val."' ";

            }

    endif;
?>
      <option value="">--Please Select--</option>
      <?php
			$this->objSet = $this->objDBCon->dbQuery("SELECT * from ".$table." where ".$strSQL." order by ".$find." asc");
			while($objRow = $this->objSet->fetch_object())  // Fetch the result in the object array
			{ 
                if($selected==$objRow->id)
                    $sel='selected';
                else
                    $sel=''; 
?>
        <option value="<?php echo $objRow->id;?>" <?php echo $sel;?>><?php echo $objRow->$find; ?></option>
<?php       }	

}

		
public function add_comments()
{
    $strSQL ='1=1';
    $show_id = $_POST['show_id'];
    $comments = $_POST['comments'];
    $user_id =   $this->__getSession('user_id');
    $row = $this->objDBCon->dbQuery("INSERT INTO ".TBL_COMMENTS." (comments, user_id, show_id) VALUES ('$comments', '$user_id', '$show_id')");
}
	
/* **************************************************

 *@Purpose: get the session value store in the variable

*******************************************************  */

public function __getSession($strVariable)
{
   return $_SESSION[$strVariable];	

} // End Function
		

function __doRedirect($url)
{
    echo '<META http-equiv="refresh" content="0;URL='.$url.'"> ';  
    exit;
}

function __storeMessage($msg,$type='error')
{
    $_SESSION['store_message'] = $msg;
    $_SESSION['error'] = $type;
}

		
function __showMessage()
{
    if(isset($_SESSION['store_message']))
    {
        if($_SESSION['error']=="error")
            echo "<div style='width:90%; float:left; border:1px dashed #FF0000; line-height:30px;padding-left:10px;margin-top:10px;color:#FF0000; text-align:left;font-weight:bold;'>".$_SESSION['store_message']."</div><div class='clear'></div>";
        if($_SESSION['error']=="success")
            echo "<div style='width:90%; float:left; border:1px dashed #009900; line-height:30px; padding-left:10px; margin-top:10px; color:#009900; text-align:left;font-weight:bold;'>".$_SESSION['store_message']."</div><div class='clear'></div>"; 
    }
    unset($_SESSION['store_message']);

}

function showError($msg)
{
    echo '<div style="font-weight:bold; background:#ff0000; padding:5px 0px; border:1px solid #000; color:#fff; text-align:center;">'.$msg.'</div>';
}

function status($tmpId = '1', $tmpOption = 'Select')
{
    $this->intId = $tmpId;
    $arrYesNo = array('UnPublished', 'Published');
    for($i = 0; $i <=1; $i++)
    {
        if($i == $tmpId) {
            $txtOption .= '<option value="'.$i.'" selected>'.ucfirst($arrYesNo[$i]).'</option>';
        }
        else {
            $txtOption .= '<option value="'.$i.'">'.ucfirst($arrYesNo[$i]).'</option>';		
        }
    }
    echo  $txtOption;
}

function country($tmpId = 'United States', $tmpOption = '- Select Country -')
{
    $this->strOption = $tmpOption;
    $strSql = "SELECT * FROM ".TBL_COUNTRIES." where country_name in ('Canada', 'United States') ORDER BY country_name";
    $objRs = $this->objDBCon->dbQuery($strSql);           

    $txtOption = '<option value="">'.$tmpOption.'</option>';
    while($objRow = $objRs->fetch_object())	
    {
        if($objRow->country_name == $tmpId)	{
            $txtOption .= '<option value="'.$objRow->country_name.'" selected>'.$objRow->country_name.'</option>';
        }
        else {
            $txtOption .= '<option value="'.$objRow->country_name.'">'.$objRow->country_name.'</option>';				
        }
    }
    echo  $txtOption;		
}	//End function

		

function yesNooption($tmpId = '1', $tmpOption = 'Select')
{

    $this->intId = $tmpId;
    $arrYesNo = array('No', 'Yes');
    for($i = 0; $i <=1; $i++)	
    {
        if($i == $tmpId) {
            $txtOption .= '<option value="'.$i.'" selected>'.ucfirst($arrYesNo[$i]).'</option>';
        }
        else {
            $txtOption .= '<option value="'.$i.'">'.ucfirst($arrYesNo[$i]).'</option>';
        }
    }
    echo  $txtOption;
}

function showMessage($strMessage, $strUrl='')
{
	$strImage= stristr($strMessage,'successfully')?'activepage.jpg':'error.gif';
?>

<div class="row">
  <div class="col-lg-9">
    <h1 class="page-header"></h1>
  </div>
</div>
<!--<table width="50%" border="0" cellspacing="0" cellpadding="0" class="tableBorder" align="center">
  <tr>
    <td class="headertext">Redirecting...</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><img src="<?php echo SITE_IMAGEURL.$strImage;?>" align="absmiddle" alt="" /><?php echo $strMessage;?></td>
  </tr>
  <?php if($strUrl<>'') : ?>
  <tr>
    <td align="center">If you are not redirected automatically, please <a href="<?php echo $strUrl;?>">click here</a>.</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>-->
 <?php if($strUrl<>'') : ?>
<META http-equiv="refresh" content="2;URL=<?php echo $strUrl;?>">
<?php endif; ?>
<?php
}

/**

* Function to create a mail object for futher use (uses phpMailer)

* @param string From e-mail address

* @param string From name

* @param string E-mail subject

* @param string Message body

* @return object Mail object

*/		

		

public function mosCreateMail( $from='', $fromname='', $subject, $body ) {

	$mail = new mosPHPMailer();



	$mail->PluginDir = SITE_ABSPATH .'/library/classes/phpmailer/';

	$mail->SetLanguage( 'en', SITE_ABSPATH . '/library/classes/phpmailer/language/' );

	//$mail->CharSet 	= substr_replace(_ISO, '', 0, 8);

	$mail->IsMail();

	$mail->From 	= $from ? $from : mosConfig_mailfrom;

	$mail->FromName = $fromname ? $fromname : mosConfig_fromname;

	$mail->Mailer 	= mosConfig_mailer;





	$mail->Subject 	= $subject; 

	

	$mail->Body 	= $body;



	return $mail;

}



/**

* Mail function (uses phpMailer)

* @param string From e-mail address

* @param string From name

* @param string/array Recipient e-mail address(es)

* @param string E-mail subject

* @param string Message body

* @param boolean false = plain text, true = HTML

* @param string/array CC e-mail address(es)

* @param string/array BCC e-mail address(es)

* @param string/array Attachment file name(s)

* @param string/array ReplyTo e-mail address(es)

* @param string/array ReplyTo name(s)

* @return boolean

*/

public function mosMail( $from, $fromname, $recipient, $subject, $body, $mode=1, $cc='', $bcc='', $attachment=NULL, $replyto=NULL, $replytoname=NULL ) {

		// Allow empty $from and $fromname settings (backwards compatibility)

	if ($from == '') {

		$from = mosConfig_mailfrom;

	}

	if ($fromname == '') {

		$fromname = mosConfig_fromname;

	}



	$mail = $this->mosCreateMail( $from, $fromname, $subject, $body );



	// activate HTML formatted emails

	if ( $mode ) {

		$mail->IsHTML(true);

	}



	if (is_array( $recipient ))

	 {

		foreach ($recipient as $to)

		 {

				$mail->AddAddress( $to );

		 }

	} 

	else 

	{

		$mail->AddAddress( $recipient );

	}

	

	if (isset( $cc ))

	 {

		if (is_array( $cc ))

		 {

			foreach ($cc as $to)

			 {

				$mail->AddCC($to);

			}

		} 

		else 

		{

			$mail->AddCC($cc);

		}

	}

	

	if (isset( $bcc ))

	 {

		if (is_array( $bcc )) 

		{

			foreach ($bcc as $to)

			 {

				$mail->AddBCC( $to );

			}

		} 

		else 

		{

			$mail->AddBCC( $bcc );

		}

	}

	

	if ($attachment) {

		if (is_array( $attachment )) {

			foreach ($attachment as $fname) {

				$mail->AddAttachment( $fname );

			}

		} else {

			$mail->AddAttachment($attachment);

		}

	}

	//Important for being able to use mosMail without spoofing...

	if ($replyto) {

		if (is_array( $replyto )) {

			reset( $replytoname );

			foreach ($replyto as $to)

			 {

				$toname = ((list( $key, $value ) = each( $replytoname )) ? $value : '');

				$mail->AddReplyTo( $to, $toname );

			}

        } 

		else 

		{

			$mail->AddReplyTo($replyto, $replytoname);

		}

    }

	$mailssend = $mail->Send();

	return $mailssend;

 } // mosMail

 

 

 public function filterPosted() {
    foreach($_POST as $key=>$val)
    {
        if((stripos($key, "db_")!==false || stripos($key, "md_")!==false) )
            {
                $_POST[substr($key,3)] =$val;
            } 

    }

}

public function randomPassowrd($length) 
{
    $rstr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $nstr = "";
    mt_srand ((double) microtime() * 1000000);
    while(strlen($nstr) < $length) 	{

        $random = mt_rand(0,(strlen($rstr)-1));
        $nstr .= $rstr{$random};
    }
    return($nstr);
}

public function getCompanyLogo()
{
    $objRow = $this->objDBCon->dbFetch("select * from ".TBL_CONFIGURATION." where config_key='site_logo'");
    return ($objRow[0]->config_value<>'')?SITE_URL.'upload/'.$objRow[0]->config_value: SITE_URL.'assets/img/logo.png';
} 

public function reportsDropDown()
{
    $res = $this->objDBCon->dbQuery("select * from ".TBL_REPORTS." where site_id = '".$_SESSION['site_id']."' order by report_name");
    $option = '<option value="">Select Report</option>';
    
    while($objRow = $res->fetch_object()) {
        $option .= '<option value="'.$objRow->report_id.'">'.$objRow->report_name.'</option>';;
    }
    return $option;
} 

public function locationDropdown( $default_id = false )
{
    $res = $this->objDBCon->dbQuery("select * from ".TBL_SERVICE." where site_id = '".$_SESSION['site_id']."' and status='1' order by name");
    $option = '<option value="">Select Location</option>';
    
    while($objRow = $res->fetch_object()) {
        if ($default_id == $objRow->id) 
            $selected = 'selected';
        else   
            $selected = '';
        
        $option .= '<option value="'.$objRow->id.'" '.$selected.'>'.$objRow->name.'</option>';;
    }
    return $option;
} 

public function staffDropDown($default_id = 0  )
{
    $res = $this->objDBCon->dbQuery("select * from ".TBL_STAFF." where site_id = '".$_SESSION['site_id']."' and status='1' and user_type>1 order by f_name");
    $option = '<option value="">Select Staff</option>';
    $option .= '<option value="0" '.(($default_id==0)?"selected":"").'>Unassigned</option>';
    while($objRow = $res->fetch_object()) {
        if ($default_id == $objRow->id) 
            $selected = 'selected';
        else   
            $selected = '';
        
        $option .= '<option value="'.$objRow->id.'" '.$selected.'>'.$objRow->f_name.' '.$objRow->l_name.'</option>';;
    }
    return $option;
} 

public function getPropertiesByLocation($location_id=0)
{
   $res = $this->objDBCon->dbQuery("select * from ".TBL_JOBLOCATION." where site_id = '".$_SESSION['site_id']."' and location_id ='".$location_id."'");
   while($objRow = $res->fetch_object()) {
    $properties[] =  $objRow->job_listing;
   }
   return $properties; 
}

public function getAllStaffTypes()
{
  $res = $this->objDBCon->dbQuery("select * from ".TBL_STAFFTYPE." where id >1");
   while($objRow = $res->fetch_object()) {
        $staffTypes[$objRow->id] =  $objRow->label;
   }
   return $staffTypes;  
}

private function setPropertyImages($images) {
    $this->propImages[] = $images;
}

public function getPropertyImages() {
    $propertyImages = implode(",", $this->propImages);
    $imagesArray = explode(",", $propertyImages);
    return $imagesArray;
}

public function getPropertyExportData($pid='') {
     $condition = '';

    if ($pid<>'')
      $condition = ' and j.id in ('.$pid.')';
 $excelRows = array();

     $sql = "select j.*, s.name as location_name, u.email from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id= s.id left join ".TBL_STAFF." u on j.assigned_to=u.id where j.status='1' ".$condition;

    $rsSet = $this->objDBCon->dbQuery($sql);
    $intRow = 0;
    while ($row = $rsSet->fetch_object()) {
        $excelRows[$intRow]['Location'] = $row->location_name;
        $excelRows[$intRow]['Property'] = $row->job_listing;
        $excelRows[$intRow]['Latitude'] = $row->lat;
        $excelRows[$intRow]['Longtitude'] = $row->lag;
        $excelRows[$intRow]['Property Address'] = $row->location_address;
        $excelRows[$intRow]['Assiged To'] = $row->email;
        $excelRows[$intRow]['Onsite Contact Person'] = $row->onsite_contact_person;
        $excelRows[$intRow]['Phone Number'] = $row->phn_no;
        $excelRows[$intRow]['Priority Status'] = ($row->priority_status==1)?'High':'Low';
        $excelRows[$intRow]['Important Notes'] = $row->importent_notes;
        $excelRows[$intRow]['Status'] = ($row->status==1)?'Active':'Inactive';
        //$images= $row->map_widget.",".$row->gallery;
        $images= $row->user_gallery;
        $this->setPropertyImages($images);
        $intRow = $intRow+1;
    }
    return $excelRows;
}

public function getPropertyData($pid='') {
     $condition = '';

    if ($pid<>'')
      $condition = ' and j.id in ('.$pid.')';


   echo $sql = "select j.*, s.name as location_name, u.email from ".TBL_JOBLOCATION." j inner join ".TBL_SERVICE." s on j.location_id= s.id inner join ".TBL_REPORTS_SUBMISSION." rs on rs.property_id=j.id left join ".TBL_STAFF." u on j.assigned_to=u.id where j.status='1' ".$condition;
    $rsSet = $this->objDBCon->dbQuery($sql);
    $intRow = 0;
    while ($row = $rsSet->fetch_object()) {
        $excelRows[$intRow]['Location'] = $row->location_name;
        $excelRows[$intRow]['Property'] = $row->job_listing;
        $excelRows[$intRow]['Latitude'] = $row->lat;
        $excelRows[$intRow]['Longtitude'] = $row->lag;
        $excelRows[$intRow]['Property Address'] = $row->location_address;
        $excelRows[$intRow]['Assiged To'] = $row->email;
        $excelRows[$intRow]['Onsite Contact Person'] = $row->onsite_contact_person;
        $excelRows[$intRow]['Phone Number'] = $row->phn_no;
        $excelRows[$intRow]['Priority Status'] = ($row->priority_status==1)?'High':'Low';
        $excelRows[$intRow]['Important Notes'] = $row->importent_notes;
        $excelRows[$intRow]['Status'] = ($row->status==1)?'Active':'Inactive';
        $images= $row->map_widget.",".$row->gallery;
        
        $this->setPropertyImages($images);
        $intRow = $intRow+1;
    }
    return $excelRows;
}

public function getFormSubmissionValues($submission_id)
{
    $rsData = $this->objDBCon->fetchRows("select * from ".TBL_REPORTS_SUBMISSION." where id='".$submission_id."'");
    
    $postedValues = (array)json_decode($rsData->form_values);
   
    $rs = $this->objDBCon->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$rsData->report_id."'");
    
    $fields =  json_decode($rs->form_body);
    $formControls = array();
    $formControls['Location'] = $this->iFind(TBL_SERVICE, 'name', array('id'=>$postedValues['db_location']));
    $formControls['Property'] = $this->iFind(TBL_JOBLOCATION, 'job_listing', array('id'=>$postedValues['db_property']));
    $location_assigned_to = $this->iFind(TBL_JOBLOCATION, 'assigned_to', array('id'=>$postedValues['db_property']));
    $fieldCount=0;
   
    foreach($fields as $field)
    {
        $fieldCount = $fieldCount+1;        
        
        if($field->field_type=='fieldset')
        {
            $label_prefix = $field->label.' - ';	
            $fieldsetStart=1;
        }
        else {
            if ($field->field_type <> 'fieldset')
            {
                if (is_array($postedValues['db_field_'.$fieldCount]))
                    $array_val =  implode(",", $postedValues['db_field_'.$fieldCount]);
                else
                    $array_val = $postedValues['db_field_'.$fieldCount];
                
                $formControls[$label_prefix.$field->label] = $array_val;
            }
        }
    } 
     
     $formControls['Submission Date'] =  strftime("%m/%d/%Y %I:%M %p", strtotime($rsData->submission_date));
     $formControls['Submitted By'] =  $this->iFind(TBL_STAFF, "CONCAT(f_name, ' ', l_name)", array("id"=>$rsData->submitted_by));
     $formControls['Assigned To'] =   $this->iFind(TBL_STAFF, "CONCAT(f_name, ' ', l_name)", array("id"=>$location_assigned_to));
     
    return $formControls;
}

    public function write2excel($headings, $data, $fileName)
    {
        $fp = fopen("upload/csv/".$fileName, "w"); 
        
        fputcsv($fp, $headings);
        
        foreach ($data as $value)
        {
            fputcsv($fp, $value);   
        }
        fclose($fp); 
    }
    
    public function getStaffIvrLog($staff_id) {
        $json_output = file_get_contents('http://www.ivrhq.me/applications/10042/api/select.php?account_key=3KoD1T56&table=staff_activity');
        $staff_array = json_decode($json_output, true);
        $staff_data = array();
        foreach($staff_array as $key => $val) {
            if ($val['staff_id'] == $staff_id)
                $staff_data[] = $val;
        }        
        return array_reverse($staff_data);
    }
    
    public function storeIVRLog() {
        $json_output = file_get_contents('http://www.ivrhq.me/applications/10042/api/select.php?account_key=3KoD1T56&table=staff_activity');
        $staff_array = json_decode($json_output, true);
     	$this->objDBCon->dbQuery("TRUNCATE tbl_ivr_log");
     	
     	foreach($staff_array as $key => $val) {
     		$this->objDBCon->dbQuery("INSERT INTO tbl_ivr_log(staff_id, time_stamp, `date`, time_24_hour_clock, clock_action_description, payroll_log, staff_full_name) values (".$val['staff_id'].", '".$val['time_stamp']."', '".$val['date']."', '".$val['time_24_hour_clock']."', '".$val['clock_action_description']."', '".$val['payroll_log']."', '".$val['staff_full_name']."')");
     	   
     	}
    }
    
    public function getAllStaffLastLog() {
        $json_output = file_get_contents('http://www.ivrhq.me/applications/10042/api/select.php?account_key=3KoD1T56&table=staff_activity');
        $staff_array = json_decode($json_output, true);
        $staff_data = array();
        foreach($staff_array as $key => $val) {
                //$staff_data[$val['staff_id']] = strftime('%b, %d %Y', $val['time_stamp']).' '.$val['clock_action_description'];
				$staff_data[$val['staff_id']] = $val['date'].','.$val['time_24_hour_clock'].','.$val['clock_action_description'];
        }        
        return $staff_data;
    }
	/*Staff Role*/
	public function getUserRoleUsingId($sid) {
        $staff_id = $sid;
		$res = $this->objDBCon->dbQuery("select * from ".TBL_STAFF." where username =".$staff_id );
		while($objRow = $res->fetch_object()) {
			$staffTypes = $objRow->user_type;
		}
		return $staffTypes;

    }
	/*User details using username*/
	public function getUserDetailsUsingUsername($staffid) {
		$res = $this->objDBCon->dbQuery("select * from ".TBL_STAFF." where username =".$staffid );
		return $res;

    }
	/*User Role based on user type*/
	public function UserRoleUsingUserType($user_type)
	{	
		$res = $this->objDBCon->dbQuery("select label from ".TBL_STAFFTYPE." where id = ".$user_type);
		return $res->fetch_object();
	}
	
	public function saveExcelToLocalFile($objWriter) {
	
		$rand = rand(1234, 9898);
		$presentDate = date('YmdHis');
		$fileName = "report_" . $rand . "_" . $presentDate . ".xlsx";
		
		// make sure you have permission to write to directory
		$filePath = SITEPATH . 'reports/' . $fileName;
		$objWriter->save($filePath);
		$data = array(
			'filename' => $fileName,
			'filePath' => $filePath
		);
		return $data;
	
	}
}
?>



  