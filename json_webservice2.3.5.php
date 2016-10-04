<?php
require_once( 'settings.php' );
ob_end_clean();
$action = $_REQUEST['action'];
function list_all_properties_reports($login_id=0, $auto_login='') {
    global $objDatabase, $objFunctions;
    //$strsql = "SELECT s.name as location_name,s.show_locations_home, p.*, DATE_FORMAT(p.start_date,'%d %b %Y') as start_date,DATE_FORMAT(p.completion_date,'%d %b %Y') as completion_date from ".TBL_JOBLOCATION." p inner join ".TBL_SERVICE." s on s.id = p.location_id where p.status=1 and p.site_id='".$_SESSION['site_id']."' order by p.priority_status desc";    
	//$strsql = "SELECT s.name as location_name,s.show_locations_home, p.*, pn.notes, DATE_FORMAT(p.start_date,'%d %b %Y') as start_date,DATE_FORMAT(p.completion_date,'%d %b %Y') as completion_date from ".TBL_JOBLOCATION." p inner join ".TBL_SERVICE." s on s.id = p.location_id left join ".TBL_PROPERTY_NOTES." pn on p.id = pn.property_id where p.status=1 and p.site_id='1' order by s.name asc"; 
	if($login_id > 1)
	{
		$strsql = "SELECT s.name as location_name,s.show_locations_home, p.*, DATE_FORMAT(p.start_date,'%Y-%m-%d %h:%i %p') as start_date, DATE_FORMAT(p.completion_date,'%Y-%m-%d %h:%i %p') as completion_date, pn.staff_id_or_admin, pn.notes, DATE_FORMAT(pn.date_added,'%Y-%m-%d %h:%i %p') as date_added, js.job_id, js.started_by, js.starting_date, js.pausing_date, js.closed_by, js.closing_date from ".TBL_JOBLOCATION." p inner join ".TBL_SERVICE." s on s.id = p.location_id inner join ".TBL_ASSIGN_PROPERTY." ap on p.id = ap.property_id left join ".TBL_JOBSTATUS." js on p.id = js.job_id left join ".TBL_PROPERTY_NOTES." pn on p.id = pn.property_id where p.status=1 and p.site_id='1' and ap.user_id = '".$login_id."' order by s.name asc, p.priority_status desc"; 
	}
	else
	{
		$strsql = "SELECT s.name as location_name,s.show_locations_home, p.*,DATE_FORMAT(p.start_date,'%Y-%m-%d %h:%i %p') as start_date, DATE_FORMAT(p.completion_date,'%Y-%m-%d %h:%i %p') as completion_date, pn.staff_id_or_admin, pn.notes, DATE_FORMAT(pn.date_added,'%Y-%m-%d %h:%i %p') as date_added, js.job_id, js.started_by, js.starting_date, js.pausing_date, js.closed_by, js.closing_date from ".TBL_JOBLOCATION." p inner join ".TBL_SERVICE." s on s.id = p.location_id left join ".TBL_JOBSTATUS." js on p.id = js.job_id left join ".TBL_PROPERTY_NOTES." pn on p.id = pn.property_id where p.status=1 and p.site_id='1' order by s.name asc, p.priority_status desc";
	}
    $objRs =  $objDatabase->dbFetch($strsql);
	//
	/*$notes=array();
	foreach($objRs as $objRow){
		$notes[$objRow->id][]=$objRow->notes;
	}*/
	$notes=array();
	foreach($objRs as $objRow){
		$staffname = $objFunctions->iFindAll(TBL_STAFF, array('id'=>$objRow->staff_id_or_admin));
		$name = $staffname[0]->f_name . ' ' . $staffname[0]->l_name;
		$notes[$objRow->id][]=array('name'=>$name, "note"=>$objRow->notes, "date_added"=>$objRow->date_added);
	}
	$prop_id = array();
    foreach ($objRs as $key => $objRow) {
		if(!in_array($objRow->id, $prop_id))
		{
			//$objRow->gallery = $objRow->gallery.",".$objRow->user_gallery;
			if($objRow->gallery != "" && $objRow->user_gallery != "")
				$objRow->gallery = $objRow->gallery.",".$objRow->user_gallery;
			elseif($objRow->gallery != "" && $objRow->user_gallery == "")
				$objRow->gallery = $objRow->gallery;
			elseif($objRow->gallery == "" && $objRow->user_gallery != "")
				$objRow->gallery = $objRow->user_gallery;
			else
				$objRow->gallery = '';
			/*if($objRow->gallery == '')
			{
				unset($objRow->gallery);
			}*/
			$array_data1['property'][$key]= $objRow;
			$objRow->notes = $notes[$objRow->id];
			$prop_id[] = $objRow->id;
		}
			
    }
	$array_data = array('property'=>array_values($array_data1['property']));
    /*foreach ($objRs as $key => $objRow) {
        $objRow->gallery = $objRow->gallery.",".$objRow->user_gallery;        
        $array_data['property'][$key]= $objRow;     
    }*/ 
    
    $properties_reports = json_decode(getAllReports());
	$properties_reports_info = getAllReportsInfo();
    if ($login_id >0) {
        return json_encode(array("status"=>true , "message"=>"","login_info"=>array('id'=>$login_id, 'auto_login'=>$auto_login), "properites"=>$array_data, "reports"=>$properties_reports, "report_info"=>$properties_reports_info));
    }
    else {
        return json_encode(array("properites"=>$array_data, "reports"=>$properties_reports, "report_info"=>$properties_reports_info));
    }        
}

function getAllPropertiesReports() {
    global $objDatabase, $objFunctions;
    
    $strsql = "SELECT * from ".TBL_JOBLOCATION." where site_id='".$_SESSION['site_id']."' and status=1";
    $objPropSet =  $objDatabase->dbFetch($strsql);
    
    foreach ($objPropSet as $key => $objProperty) {
        if ($objProperty->enabled_reports != '')  { 
           $strsql = "SELECT distinct(report_id), report_name from ".TBL_REPORTS." where status=1 and report_id in (".$objProperty->enabled_reports.")";    
            $objSet =  $objDatabase->dbFetch($strsql);    
            
            foreach ($objSet as $keyReport => $objRow) { 
                if ($objRow->report_id <> '') {
                  $objProperty->id.",".$objRow->report_id;
                   
                   $reptObj =  $objFunctions->getPropertyReportStatus($objProperty->id, $objRow->report_id);
                   
                   if ($reptObj->id <> '')
                        $objRow->submission_date = date('m/d/Y', strtotime($reptObj->submission_date));
                    
                    //$array_data['property_reports'][$objProperty->id][$keyReport] = $objRow;
					$array_data[$objProperty->id][$keyReport] = $objRow;
                }
       
            }
        }
    }
    
    return json_encode(array("status"=>true , "message"=>"", "property_reports"=>$array_data));
}
function getAllReports() {
    global $objDatabase;
    $strsql = "SELECT * from ".TBL_REPORTS." where status=1";
    $objSet =  $objDatabase->dbFetch($strsql);
	$rowValue = array();
    foreach ($objSet as $key => $objRow) {
		$rowValue = array(
			'report_id' => $objRow->report_id,
			'site_id' => $objRow->site_id,
			'report_name' => $objRow->report_name,
			'form_description' => $objRow->form_description,
			'form_body' => json_decode($objRow->form_body),
			'send_to' => $objRow->send_to,
			'mail_subject' => $objRow->mail_subject,
			'submit_button_text' => $objRow->submit_button_text,
			'location_box_label' => $objRow->location_box_label,
			'property_box_label' => $objRow->property_box_label,
			'submissions' => $objRow->submissions,
			'date_added' => $objRow->date_added,
			'status' => $objRow->status,
		);
        $reportArray[$key] = $rowValue;
    }
    return json_encode(array("status"=>true , "message"=>"", "report"=>$reportArray));
}

function getAllReportsInfo() {
    global $objDatabase;
    $strsql = "SELECT * FROM (SELECT MAX(submission_date) as MaxTime FROM tbl_report_submission GROUP BY report_id, property_id) r INNER JOIN tbl_report_submission t ON t.submission_date = r.MaxTime";
    $objSet =  $objDatabase->dbFetch($strsql);
	$rowValue = array();
	$check = array();
    foreach ($objSet as $key => $objRow) {
			$rowValue = array(
				'report_id' => $objRow->report_id,
				'property_id' => $objRow->property_id,
				'submission_date' => $objRow->submission_date,
			);
			$reportinfoArray[$key] = $rowValue;
    }
    return $reportinfoArray;
}

if($action == 'user_authentication') {
    
    $strsql = "SELECT * from ".TBL_STAFF." where username= '".$_POST['uname']."' and password = '".md5($_POST['password'])."'";
    $objSet =  $objDatabase->fetchRows($strsql); 
    if ($objSet->username !='') {
        //$arr = array('login_info' => json_encode(array('id'=>$objSet->id, 'auto_login'=>$objSet->auto_login)));
        echo $property_data = list_all_properties_reports($objSet->id, $objSet->auto_login);
        
        //echo $objSet->id.','.$objSet->auto_login;
    }
    else {
        echo json_encode(array('status'=>false , 'message'=>'Invalid credential','login_info'=>'failed'));
    }
    
}
elseif($action == 'updatejob') {
    
    if(intval($_GET['status'])==1) {
        $objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='1', start_date='".date('Y-m-d H:i:s')."' where id='".$_GET['pid']."'");
        $objDatabase->dbQuery("INSERT INTO ".TBL_JOBSTATUS." (job_id, started_by, starting_date) values('".$_GET['pid']."', '".$_GET['uid']."', '".date('Y-m-d H:i:s')."')");
        echo json_encode(array('status'=>true , 'message'=>'Job start','result'=>date('Y-m-d H:i:s')));
		//echo date('Y-m-d H:i:s');
    }
    elseif(intval($_GET['status'])==2) {  
        $objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='2', completion_date='".date('Y-m-d H:i:s')."' where id='".$_GET['pid']."'");
        $objDatabase->dbQuery("UPDATE ".TBL_JOBSTATUS." set closed_by='".$_GET['uid']."', closing_date='".date('Y-m-d H:i:s')."' where job_id='".$_GET['pid']."' and closed_by='' and pausing_date = '0000-00-00 00:00:00' ");
        echo json_encode(array('status'=>true , 'message'=>'Job complete','result'=>date('Y-m-d H:i:s')));
		//echo date('Y-m-d H:i:s');
    }
    elseif(intval($_GET['status'])==3) {
		$objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='3', pause_date='".date('Y-m-d H:i:s')."' where id='".$_GET['pid']."'");
        $objDatabase->dbQuery("UPDATE ".TBL_JOBSTATUS." set pausing_date='".date('Y-m-d H:i:s')."', closed_by='".$_GET['uid']."' where job_id='".$_GET['pid']."' and closing_date = '0000-00-00 00:00:00' ");
        //$objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='0', start_date='0000-00-00 00:00:00', completion_date='0000-00-00 00:00:00' where id='".$_GET['pid']."'");
        echo json_encode(array('status'=>true , 'message'=>'Job Paused','result'=>'success'));
		//echo 'success';
    }
    
}
elseif ($action == 'locations') {
    
    $strSql = "SELECT s.*,j.location_id, j.assigned_to, j.map_widget, j.lat, j.lag FROM ".TBL_SERVICE." s inner join ".TBL_JOBLOCATION. " j on(s.id=j.location_id) where 1=1 and j.site_id='".$_SESSION['site_id']."' and j.status=1 and s.status=1 group by s.name";
	$objSet = $objDatabase->dbFetch($strSql);
    
    foreach ($objSet as $key=>$objRow) {
        $array_data[$key] = $objRow; 
    }
    echo json_encode(array("status"=>true , "message"=>"", "locations"=>$array_data));
    
}
elseif ($action == 'dashboard') {
    
    $strSql = "SELECT s.*,j.location_id, j.assigned_to, j.map_widget, j.lat, j.lag FROM ".TBL_SERVICE." s inner join ".TBL_JOBLOCATION. " j on(s.id=j.location_id) where 1=1 and j.site_id='".$_SESSION['site_id']."' and j.status=1 and s.status=1 group by s.name";
	$objSet = $objDatabase->dbFetch($strSql);
    $data_content .= '<div class="slide col-xs-12- col-sm-12" style="display: block;" id="dashboard">';
    
    foreach ($objSet as $key=>$objRow) {
        $data_content .= '<div class="col-xs-12 col-sm-12 nopadding"><div class="text_overimg"><a onclick="load_location('.$objRow->id.', \''.$objRow->name.'\', 1);">'.$objRow->name.'</a></div>';
        
        if ($objRow->show_locations_home == 1) {
            $strsql1 = "SELECT * from ".TBL_JOBLOCATION." where location_id= '".$objRow->id."' and site_id='".$_SESSION['site_id']."' and status=1 order by id asc limit 4";
            $objSet1 =  $objDatabase->dbQuery($strsql1);
            $iCount = 0 ;
            while ($objRow1 = $objSet1->fetch_object()) {
                $data_content .= '<div class="col-sm-12 col-xs-12 listing" style="padding-top:5px; padding-bottom: 5px;"><a class="more sking" onclick="load_property('.$objRow1->id.', 1);"> '.$objRow1->job_listing.'</a></div><div class="clearfix"></div>';
                $iCount++;
            }
            $data_content .='</div>';
        }
    } 
    $data_content .='</div>';    
    echo $data_content;    
    
} 
elseif($action == 'proplisting') {    
    $strsql = "SELECT * from ".TBL_JOBLOCATION." where location_id= '".$_GET['lid']."' and site_id='".$_SESSION['site_id']."' and status=1 order by priority_status asc";
    $objSet =  $objDatabase->dbFetch($strsql);
    foreach ($objSet as $key => $objRow) {
        $array_data[$key] = $objRow;
    }
    echo json_encode(array("status"=>true , "message"=>"", "property"=>$array_data));
}

elseif($action == 'prop_view') {    
    $strsql = "SELECT * from ".TBL_JOBLOCATION." where id= '".$_GET['pid']."'";
    $objRs =  $objDatabase->dbFetch($strsql);
    foreach ($objRs as $key => $objRow)
    $array_data[$key]= $objRow;
    echo json_encode(array("status"=>true , "message"=>"", "property_view"=>$array_data));
}

elseif($action == 'all_properties') {    
    $result = list_all_properties_reports();
    echo $result;
}
elseif($action == 'prop_reports') {    
    $strsql = "SELECT * from ".TBL_REPORTS." where report_id in (".$_GET['rid'].") and status=1";    
    $objSet =  $objDatabase->dbFetch($strsql);    
    foreach ($objSet as $key => $objRow) {        
        $reptObj =  $objFunctions->getPropertyReportStatus($_GET['prop_id'], $objRow->report_id);
        if ($reptObj->id <> '')
            $objRow->submission_date = date('d M Y', strtotime($reptObj->submission_date));
        $array_data['reports'][$key] = $objRow;
    }    
    echo json_encode(array("status"=>true , "message"=>"", "reports"=>$array_data));
}
elseif($action == 'all_prop_reports') {  
    echo getAllPropertiesReports();
}
elseif($action == 'all_reports') {
    echo getAllReports();
    
}
elseif($action == 'load_report') {
    
    $reportObj = $objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$_GET['rid']."'");
    echo $reportObj->form_body;
    
}
elseif($action == 'file_upload') {
    $uploaded= FALSE;
    
    if($_FILES[0]['name'] <>'')
    {     
        $name = $_FILES[0]['name'];
        
        if(move_uploaded_file($_FILES[0][tmp_name], 'upload/'.$name))
            $uploaded = TRUE; // Number of successfully uploaded file

        if ($uploaded == TRUE) {
			$usrgal = $objFunctions->iFind(TBL_JOBLOCATION, 'user_gallery', array('id' => $_REQUEST['pid'])); 
			if($usrgal != '')
            	$objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set user_gallery= CONCAT(user_gallery,',', '".$name."') where id='".$_REQUEST['pid']."'");
			else
				$objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set user_gallery= '".$name."' where id='".$_REQUEST['pid']."'");
			
			$objDatabase->dbQuery("INSERT INTO ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." (staff_id, prop_id, date, images) values('".$_REQUEST['uid']."','".$_REQUEST['pid']."','".date('Y-m-d H:i:s')."','".$name."')");
            //$objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set user_gallery= CONCAT(user_gallery,',', '".$name."') where id='".$_REQUEST['pid']."'");
            echo json_encode(array("status"=>true , "message"=>"Image has been uploaded successfully", "result"=>"sucess"));
			//echo 'success';   
        }
        else {
            echo 'Sorry, No file(s) uploaded.';
        } 
        
    } 
    else {
        echo 'Sorry, No file(s) uploaded.';
    }
}
elseif($action == 'save_report') {
    $post = $_POST;
    
    $rs = $objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$post['rid']."'");
    $fields =  json_decode($rs->form_body);
    $formControls = array();
    $form_token = $post['rid'];
    $fieldCount=0;
    
    foreach($fields as $field)
    {
        $fieldCount = $fieldCount+1;

        $formControls[$form_token]['#value']['db_field_'.$fieldCount] = 
        array(
              '#type' => $field->field_type,
              '#title' => $field->label,
              '#name'=>'db_field_'.$fieldCount,
              '#required' => $field->required,
              '#include_blank' => $field->field_options->include_blank_option,
              '#field_options' => $field->field_options->options,
              '#units' => $field->field_options->units,
              '#class' => $field->class
        ); 
    }
    
    if($post['db_location']<>'')
    {
        $strLocation = $objFunctions->iFind(TBL_SERVICE, 'name', array('id' => $post['db_location'])); 
        $strProperty = $objFunctions->iFind(TBL_JOBLOCATION, 'job_listing', array('id' => $post['db_property']));

        $message ='Location: '.$strLocation."\n";
        $message .='Property: '.$strProperty."\n";
    }	
    
    foreach($_POST as $key => $val) { 
        if(is_array($val))
        {
           $val = implode(", ", $val);
        }
        if($key<>'send' && $key<>'rid' && $key<>'timestamp' && $key<>'db_location' && $key<>'db_property' && $key<>'user_id') 
        {		
          if (is_array($val))
            $val = implode(", ", $val);
          $message .= ucfirst($formControls[$post['rid']]['#value'][$key]['#title']).": $val\n"; 
        }
    }
    $postedValues = json_encode($post);
    "INSERT INTO ".TBL_REPORTS_SUBMISSION." (report_id,location_id, property_id, form_values, submission_date, submitted_by) values('".$post['rid']."','".$post['db_location']."', '".$post['db_property']."', '".$postedValues."', '".date('Y-m-d H:i:s')."', '".$post['user_id']."')";
    $objDatabase->dbQuery("INSERT INTO ".TBL_REPORTS_SUBMISSION." (report_id,location_id, property_id, form_values, submission_date, submitted_by) values('".$post['rid']."','".$post['db_location']."', '".$post['db_property']."', '".$postedValues."', '".date('Y-m-d H:i:s')."', '".$post['user_id']."')");
    $objDatabase->dbQuery("update ".TBL_REPORTS." set submissions=submissions+1 where report_id='".$post['rid']."'");
    $sendTo = $rs->send_to;
    $mailSubject = ($rs->mail_subject<>'')? $rs->mail_subject: 'Report Submission';
    $success = mail($sendTo, $mailSubject, $message); 
    //echo 'success';
	if($sendTo != "")
	{
		if($success){
			echo json_encode(array("status"=>true , "message"=>"Form has been submitted successfully", "result"=>"sucess"));}
		else{
			echo json_encode(array("status"=>false , "message"=>"", "result"=>"failer"));}
	}
	else
	{
		echo json_encode(array("status"=>true , "message"=>"Form has been submitted successfully", "result"=>"sucess"));
	}
}
elseif($action == 'add_note')
{
	 $msg = $objDatabase->dbQuery("INSERT INTO ".TBL_PROPERTY_NOTES."(property_id, staff_id_or_admin, notes, date_added) values('".$_GET['pid']."', '".$_GET['uid']."', '".$_GET['note']."', '".date('Y-m-d H:i:s')."') ");
	 if($msg)
	 {
	  	echo json_encode(array("status"=>true , "message"=>"Note has been added successfully", "result"=>"sucess"));
	 }
	 else
	 {
		echo json_encode(array("status"=>false , "message"=>"", "result"=>"failed")); 
	 }
}
elseif($action == 'display_notes')
{
	
	 $strsql ="SELECT * from ".TBL_PROPERTY_NOTES." where property_id = '".$_GET['pid']."'";
	 $objSet =  $objDatabase->dbFetch($strsql);
	$rowValue = array();
    foreach ($objSet as $key => $objRow) {
		$staffname = $objFunctions->iFindAll(TBL_STAFF, array('id'=>$objRow->staff_id_or_admin));
		$name = $staffname[0]->f_name . ' ' . $staffname[0]->l_name;
		$rowValue = array(
			'staff_name' => $name,
			'note' => $objRow->notes,
			'date' => $objRow->date_added,
		);
        $reportArray[$key] = $rowValue;
	}
	echo json_encode(array("status"=>true , "message"=>"", "result"=>$reportArray));
}
die();   
?>