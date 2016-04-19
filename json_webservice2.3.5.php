<?php
require_once( 'settings.php' );
ob_end_clean();
$action = $_REQUEST['action'];

function list_all_properties_reports($login_id=0, $auto_login='') {
    global $objDatabase, $objFunctions;
    $strsql = "SELECT s.name as location_name,s.show_locations_home, p.*, DATE_FORMAT(p.start_date,'%d %b %Y') as start_date,DATE_FORMAT(p.completion_date,'%d %b %Y') as completion_date from ".TBL_JOBLOCATION." p inner join ".TBL_SERVICE." s on s.id = p.location_id where p.status=1 and p.site_id='".$_SESSION['site_id']."' order by p.priority_status desc";    
    $objRs =  $objDatabase->dbFetch($strsql);    
    foreach ($objRs as $key => $objRow) {
        $objRow->gallery = $objRow->gallery.",".$objRow->user_gallery;        
        $array_data['property'][$key]= $objRow;     
    } 
    
    $properties_reports = getAllReports();
    if ($login_id >0) {
        return json_encode(array("login_info"=>json_encode(array('id'=>$login_id, 'auto_login'=>$auto_login)), "properites"=>json_encode($array_data), "reports"=>$properties_reports));
    }
    else {
        return json_encode(array("properites"=>json_encode($array_data), "reports"=>$properties_reports));
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
                    
                    $array_data['property_reports'][$objProperty->id][$keyReport] = $objRow;
                }
       
            }
        }
    }
    
    return json_encode($array_data);
}

function getAllReports() {
    global $objDatabase;
    $strsql = "SELECT * from ".TBL_REPORTS." where status=1";
    $objSet =  $objDatabase->dbFetch($strsql);
    foreach ($objSet as $key => $objRow) {
        $reportArray['report'][$key] = $objRow;
    }
    return json_encode($reportArray);
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
        echo json_encode(array('login_info'=>'failed'));
    }
    
}
elseif($action == 'updatejob') {
    
    if(intval($_GET['status'])==1) {
        $objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='1', start_date='".date('Y-m-d H:i:s')."' where id='".$_GET['pid']."'");
        $objDatabase->dbQuery("INSERT INTO ".TBL_JOBSTATUS." (job_id, started_by, start_date) values('".$_GET['pid']."', '".$_GET['uid']."', '".date('Y-m-d H:i:s')."')");
        echo date('Y-m-d H:i:s');
    }
    elseif(intval($_GET['status'])==2) {  
        $objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='2', completion_date='".date('Y-m-d H:i:s')."' where id='".$_GET['pid']."'");
        $objDatabase->dbQuery("UPDATE ".TBL_JOBSTATUS." set closed_by='".$_GET['uid']."', closing_date='".date('Y-m-d H:i:s')."' where job_id='".$_GET['pid']."' and closed_by='' ");
        echo date('Y-m-d H:i:s');
    }
    elseif(intval($_GET['status'])==3) {
        $objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='0', start_date='0000-00-00 00:00:00', completion_date='0000-00-00 00:00:00' where id='".$_GET['pid']."'");
        echo 'success';
    }
    
}
elseif ($action == 'locations') {
    
    $strSql = "SELECT s.*,j.location_id, j.assigned_to, j.map_widget, j.lat, j.lag FROM ".TBL_SERVICE." s inner join ".TBL_JOBLOCATION. " j on(s.id=j.location_id) where 1=1 and j.site_id='".$_SESSION['site_id']."' and j.status=1 and s.status=1 group by s.name";
	$objSet = $objDatabase->dbFetch($strSql);
    
    foreach ($objSet as $key=>$objRow) {
        $array_data['locations'][$key] = $objRow; 
    }
    echo json_encode($array_data);
    
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
        $array_data['property'][$key] = $objRow;
    }
    echo json_encode($array_data);
}

elseif($action == 'prop_view') {    
    $strsql = "SELECT * from ".TBL_JOBLOCATION." where id= '".$_GET['pid']."'";
    $objRs =  $objDatabase->dbFetch($strsql);
    foreach ($objRs as $key => $objRow)
    $array_data[$key]= $objRow;
    echo json_encode($array_data);
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
    echo json_encode($array_data);
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
        
        if(move_uploaded_file($_FILES[0]["tmp_name"], 'upload/'.$name))
            $uploaded = TRUE; // Number of successfully uploaded file

        if ($uploaded == TRUE) {
            $objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set user_gallery= CONCAT(user_gallery,',', '".$name."') where id='".$_REQUEST['pid']."'");
            echo 'success';   
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
    echo "INSERT INTO ".TBL_REPORTS_SUBMISSION." (report_id,location_id, property_id, form_values, submitted_by) values('".$post['rid']."','".$post['db_location']."', '".$post['db_property']."', '".$postedValues."','".$post['user_id']."')";
    $objDatabase->dbQuery("INSERT INTO ".TBL_REPORTS_SUBMISSION." (report_id,location_id, property_id, form_values, submitted_by) values('".$post['rid']."','".$post['db_location']."', '".$post['db_property']."', '".$postedValues."','".$post['user_id']."')");
    $objDatabase->dbQuery("update ".TBL_REPORTS." set submissions=submissions+1 where report_id='".$post['rid']."'");
    $sendTo = $rs->send_to;
    $mailSubject = ($rs->mail_subject<>'')? $rs->mail_subject: 'Report Submission';
    mail($sendTo, $mailSubject, $message); 
    echo 'success';
}

die();   
?>