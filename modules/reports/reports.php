<?php
/**
 * @Author: CT
 * @Purpose: Class for Content Module which inherits the HTML view class
*/

class REPORT extends REPORT_HTML_CONTENT
{
  private $intId;
  private $objDatabase;
  private $objMainFrame;
  private $objRecord;
  private $objSet;
  public $url;
  
/**
    *@Purpose: Define the construction of the class and create the instaces of Database class, Mainframe class and Functions class
*/
 public function __construct($intId, $objMainframe, $objFunctions)
  {
    $this->intId = $intId;
  	$this->objDatabase 	= new Database();
  	$this->objMainFrame = $objMainframe;
  	$this->objFunction  = $objFunctions;	
	  parent :: __construct($this->objFunction); // Call the construction of the HTML view class
  }

  public function reportForm()
  {
    if(intval($this->intId)>0)
     $recordSet = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS." where report_id=".$this->intId);
     parent :: reportForm($recordSet, $this->intId);
  }

  public function saveForm()
  {
     $data = json_decode($_POST['fields'], true);
     $fieldContent = json_encode($data['fields']);

    if (intval($this->intId)==0)
    {
        $this->intId = $this->objDatabase->insertQuery("insert into ".TBL_REPORTS." (site_id, report_name,form_description, form_body, status, send_to, mail_subject, submit_button_text, location_box_label, property_box_label) values ('".$_SESSION['site_id']."', '".$_POST['form_name']."','".$_POST['desc']."','".$fieldContent."','1','".$_POST['send_to']."','".$_POST['mail_subject']."', '".$_POST['button_text']."', '".$_POST['location']."', '".$_POST['property']."')");
        echo "New Report Form added successfully.";
    }
    else
    {
        $rs = $this->objDatabase->dbQuery("update ".TBL_REPORTS." set location_box_label='".$_POST['location']."', property_box_label='".$_POST['property']."', report_name='".$_POST['form_name']."', submit_button_text = '".$_POST['button_text']."', form_description='".$_POST['desc']."', form_body='".$fieldContent ."', mail_subject='".$_POST['mail_subject']."', send_to='".$_POST['send_to']."' where report_id='".$this->intId."'");
        echo "Report Form updated successfully.";
    }
    
    if ($_POST['active_in_all'] == 1) {
        $this->objDatabase->dbQuery("update ".TBL_JOBLOCATION." set enabled_reports= CONCAT(enabled_reports, ',', ".$this->intId.") where FIND_IN_SET(".$this->intId.", enabled_reports) = 0");
    }

    if ($_POST['remove_in_all'] == 1) {
        $this->objDatabase->dbQuery("update ".TBL_JOBLOCATION." set  enabled_reports= TRIM(BOTH ',' FROM REPLACE(REPLACE(enabled_reports, ".$this->intId.", ''), ',,', ',')) where FIND_IN_SET(".$this->intId.", enabled_reports) >0");
    }
    
    die();
  }
  
  public function reportNames()
  {
	$strsql = "SELECT * FROM " . TBL_REPORTS . " where report_name != ''";
	$objRs = $this->objDatabase->dbQuery($strsql);
	return $objRs;
  }
  
  public function reportListing()
  {
    /*$rs = $this->objDatabase->dbQuery("select * from ".TBL_REPORTS." where 1=1  and site_id='".$_SESSION['site_id']."'");
    parent:: reportListing($rs);*/
	$strsql = "SELECT r.*,rs.* FROM " . TBL_REPORTS . " r inner join " . TBL_REPORTS_SUBMISSION . " rs on r.report_id=rs.report_id where r.site_id='" . $_SESSION['site_id'] . "' and r.report_id='".$_GET['rid']."'";
	$objRs = $this->objDatabase->dbQuery($strsql);
	parent:: reportListing($objRs);
  } 
  
  public function reportListing_default()
  {
    /*$rs = $this->objDatabase->dbQuery("select * from ".TBL_REPORTS." where 1=1  and site_id='".$_SESSION['site_id']."'");
    return $rs;*/
	$defaultreportnames = $this->reportNames(); $rid = $defaultreportnames->fetch_object();
	$strsql = "SELECT r.*,rs.* FROM " . TBL_REPORTS . " r inner join " . TBL_REPORTS_SUBMISSION . " rs on r.report_id=rs.report_id where r.site_id='" . $_SESSION['site_id'] . "' and r.report_id='".$rid->report_id."'";
	$objRs = $this->objDatabase->dbQuery($strsql);
	return $objRs;
  }
  
   public function reportsmanager()
  {
    $rs = $this->objDatabase->dbQuery("select * from ".TBL_REPORTS." where 1=1  and site_id='".$_SESSION['site_id']."'");
    parent:: reportsmanager($rs);
  } 
  
  public function report_ivr_log()
			{
				$where = "where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
				if(isset($_GET['s']))
				{
					if($_GET['staff_id'] != '')
					{
						$where .= " and s.username = '".$_GET['staff_id']."'";
					}
					if($_GET['fname'] != '')
					{
						$where .= " and s.f_name LIKE '".$_GET['fname']."'";
					}
					if($_GET['lname'] != '')
					{
						$where .= " and s.l_name LIKE '".$_GET['lname']."'";
					}
					$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s inner join " . TBL_STAFFTYPE . " st on s.user_type=st.id $where";
				}
				else
				{
					$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s inner join " . TBL_STAFFTYPE . " st on s.user_type=st.id $where";
				}
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent::admin_report_ivr_log($this->objSet);
	}
	
	public function piechartuserdetails($sids)
			{
				$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s inner join " . TBL_STAFFTYPE . " st on s.user_type=st.id where s.username IN($sids) and s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent::admin_piechartuserdetails($this->objSet);
	}
	
	public function driver_report()
			{
				$where = "where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
				if(isset($_GET['s']))
				{
					if($_GET['staffid'] != '')
					{
						$where .= " and s.username = '".$_GET['staffid']."'";
					}
					if($_GET['date_from'] != '' && $_GET['date_to'] != '')
					{
						$where .= " and UNIX_TIMESTAMP(js.starting_date) >= UNIX_TIMESTAMP('".$_GET['date_from']."') and UNIX_TIMESTAMP(js.closing_date) <= UNIX_TIMESTAMP('".$_GET['date_to']."') ";
					}
					if($_GET['fname'] != '')
					{
						$where .= " and s.f_name LIKE '".$_GET['fname']."'";
					}
					if($_GET['lname'] != '')
					{
						$where .= " and s.l_name LIKE '".$_GET['lname']."'";
					}
					$strSql = "SELECT s.id,s.username,s.f_name,s.l_name,js.starting_date as s_date, js.closing_date as c_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by $where";
					/*else
					{
						$strSql = "SELECT s.id,s.username,s.f_name,s.l_name,js.starting_date as s_date, js.closing_date as c_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "' and UNIX_TIMESTAMP(js.starting_date) >= UNIX_TIMESTAMP('".$_GET['date_from']."') and UNIX_TIMESTAMP(js.closing_date) <= UNIX_TIMESTAMP('".$_GET['date_to']."') "; 
					}*/
				}
				else
				{
					$ys_date = date('Y-m-d',strtotime("-1 days"));
					$cur_date = date('Y-m-d'); 
					//$strSql = "SELECT s.id,s.username,s.f_name,s.l_name, max(js.starting_date),js.starting_date, js.closing_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "' and UNIX_TIMESTAMP(DATE(js.starting_date)) >= UNIX_TIMESTAMP('".$ys_date."') and UNIX_TIMESTAMP(DATE(js.starting_date)) <= UNIX_TIMESTAMP('".$cur_date."') group by js.started_by";
					$strSql = "SELECT s.id,s.username,s.f_name,s.l_name, max(js.starting_date) as s_date, max(js.closing_date) as c_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  $where group by js.started_by";
				}
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent::admin_driver_report($this->objSet);
	}
	
	public function driver_report_log()
			{
				$strSql = "SELECT jl.job_listing, js.* FROM " . TBL_JOBLOCATION . " jl inner join " . TBL_JOBSTATUS . " js on jl.id=js.job_id where js.started_by=".$_GET['driver_id']." and js.closed_by=".$_GET['driver_id'];
				//$strSql = "SELECT * from " .TBL_JOBSTATUS. " where started_by=".$_GET['driver_id']." and closed_by=".$_GET['driver_id'];
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent::admin_driver_report_log($this->objSet);
	}
	
	public function property_report()
	{
			$where = "where 1=1 and site_id='".$_SESSION['site_id']."'";
				if(isset($_GET['s']))
				{
					if($_GET['propertyname'] != '')
					{
						$where .= " and ".TBL_JOBLOCATION.".job_listing = '".$_GET['propertyname']."'";
					}
					if($_GET['locationname'] != '')
					{
						$where .= " and ".TBL_JOBLOCATION.".location_id = '".$_GET['locationname']."'";
					}
					if($_GET['assignedto'] != '')
					{
						$where .= " and ".TBL_ASSIGN_PROPERTY.".user_id = '".$_GET['assignedto']."'";
					}
					//$strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and site_id='".$_SESSION['site_id']."'";
				}
				if(isset($_GET['assignedto']) && $_GET['assignedto'] != '')
				{
					$strSql = "SELECT ".TBL_JOBLOCATION.".*, ".TBL_ASSIGN_PROPERTY.".* FROM ".TBL_JOBLOCATION." inner join ".TBL_ASSIGN_PROPERTY." on ".TBL_JOBLOCATION.".id=".TBL_ASSIGN_PROPERTY.".property_id $where";
				}else{
					$strSql = "SELECT * FROM ".TBL_JOBLOCATION." $where";
				}
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: admin_property_report($this->objSet ,'joblocation');
	}
	
	public function property_report_details()
	{
			$strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and site_id='".$_SESSION['site_id']."'";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			return $this->objSet;
	}
	
	public function property_assign_details()
	{
			//$strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and site_id='".$_SESSION['site_id']."'";
			$strSql = "SELECT DISTINCT(user_id) FROM ".TBL_ASSIGN_PROPERTY;
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			return $this->objSet;
	}
	
	public function resetProperty($id)
	{
		if ($id >0) {
			//
			/*export property reports*/
			require_once 'PHPExcel/Classes/PHPExcel.php';
			$objPHPExcel = new PHPExcel(); 
			$objPHPExcel->getProperties()
						->setCreator("user")
						->setLastModifiedBy("user")
						->setTitle("Office 2007 XLSX Test Document")
						->setSubject("Office 2007 XLSX Test Document")
						->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
						->setKeywords("office 2007 openxml php")
						->setCategory("Test result file");
			chdir($_SERVER['DOCUMENT_ROOT'].'/upload');
			foreach(range('A','D') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			$strsql = "SELECT * FROM ".TBL_JOBLOCATION." where id = '".$id."'";
			$objRs = $this->objDatabase->dbQuery($strsql);
			if($objRs)
			{
				$row=1;
				while($obRow = $objRs->fetch_object())
				{
					if($row != 1){$row = $row+2;}
					$jobdata = $this->report_details($obRow->id);
					
					// Set the active Excel worksheet to sheet 0
					$objPHPExcel->setActiveSheetIndex(0); 
					// Initialise the Excel row number
					$rowCount = 0; 
					$propname = $this->propname($obRow->id);
					$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $propname->job_listing);
					
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
					$objPHPExcel->getActiveSheet()
					->getStyle('A'.$row.':E'.$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
					
					$row = $row+2;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Staff #');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Started By');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Job Start (or unpause)');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Pause');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'Job End');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Completed By');
					
					$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true)->setSize(12);
					// Build cells
					$row++;
					$count =1;
					while( $objRow = $jobdata->fetch_object() )
					{ 
						$col=0;
						$startdate = date('Y-m-d h:i:s a', strtotime($objRow->starting_date));
						if($objRow->pausing_date != '0000-00-00 00:00:00')
							$pausedate = date('Y-m-d h:i:s a', strtotime($objRow->pausing_date));
						else
							$pausedate = '00:00 Null';
						if($objRow->closing_date != '0000-00-00 00:00:00')
							$enddate = date('Y-m-d h:i:s a', strtotime($objRow->closing_date));
						else
							$enddate = '00:00 Null';
						$closedate = strtotime($objRow->closing_date);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $count);$col++;
						$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->started_by));
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$col++; 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $startdate);$col++;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $pausedate);$col++;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $enddate);$col++;
						$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->closed_by));
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$row++; $count++;
						 
					}
					
					$staffuploadsdata = $this->staffuploads($obRow->id);
					$row = $row + 3	;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'StaffName');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Date');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Images');
					
					$objPHPExcel->getActiveSheet()
					->getStyle('B'.$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()
					->getStyle('C'.$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()
					->getStyle('D'.$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
					
					$row++;
					$supload = array();
					while($objRow = $staffuploadsdata->fetch_object())  // Fetch the result in the object array
					{	
						$supload[] = get_object_vars($objRow);	
					}
					foreach($supload as $objRow1)
					{
						$col = 1;
						foreach($objRow1 as $key=>$value) {
							if($key == 'images')
							{
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(95);
								$col = $row;
								if (strpos($value, ',') !== false)
								{
									$imgcell = 'D';
									$img = explode(',', $value);
									foreach($img as $pimg)
									{
										$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
										$objDrawing = new PHPExcel_Worksheet_Drawing();
										$objDrawing->setName('Customer Signature');
										$objDrawing->setDescription('Customer Signature');
										//Path to signature .jpg file
										$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$pimg;     
										$objDrawing->setPath($signature);
										$objDrawing->setOffsetX(25);                     //setOffsetX works properly
										$objDrawing->setOffsetY(10);                     //setOffsetY works properly
										$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
										$objDrawing->setWidth(100);  
										$objDrawing->setHeight(90);                     //signature height  
										$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
										++$imgcell;
									}
								}
								else
								{
									$imgcell = 'D';
									$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
									$image = $value;
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setName('Customer Signature');
									$objDrawing->setDescription('Customer Signature');
									//Path to signature .jpg file
									$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$image;     
									$objDrawing->setPath($signature);
									$objDrawing->setOffsetX(25);                     //setOffsetX works properly
									$objDrawing->setOffsetY(10);                     //setOffsetY works properly
									$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
									$objDrawing->setWidth(100);  
									$objDrawing->setHeight(90);                     //signature height  
									$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
								}
								
							}
							elseif($key == 'staff_id')
							{
								$staffname = $this->staffname($value);
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $staffname->f_name.' '.$staffname->l_name);
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
							}
							$col++;
						}
						$row++;
					}
				}
			}
			
			$property_report = "export_property_report.xlsx";
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_clean();
			$objWriter->save(str_replace(__FILE__,"export_property_report.xlsx",__FILE__));	
			/*export images*/
			$strsql = "select user_gallery as staffuploads from ".TBL_JOBLOCATION." where user_gallery != '' and id = '".$id."'";
			$objRow = $this->objDatabase->fetchRows($strsql);
			$filesarr = array();
			$pimg = explode(',', $objRow->staffuploads);
				foreach($pimg as $img){
					if($img != '')
						$filesarr[] = $img;
				}
			/*export custom report*/
			$objPHPExcel = new PHPExcel(); 
			$objPHPExcel->getProperties()
						->setCreator("user")
						->setLastModifiedBy("user")
						->setTitle("Office 2007 XLSX Test Document")
						->setSubject("Office 2007 XLSX Test Document")
						->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
						->setKeywords("office 2007 openxml php")
						->setCategory("Test result file");
			foreach(range('A','I') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			$strsql = "SELECT r.*,rs.* FROM " . TBL_REPORTS . " r inner join " . TBL_REPORTS_SUBMISSION . " rs on r.report_id=rs.report_id where r.site_id='" . $_SESSION['site_id'] . "' and rs.property_id = '".$id."' order by r.report_id";
			$objRs = $this->objDatabase->dbQuery($strsql);
			if($objRs)
			{
				$row=1;
				while($objRow = $objRs->fetch_object())
				{ 
					$col=0;
					//$objPHPExcel->setActiveSheetIndex(0);
					$fbody = json_decode($objRow->form_body); 
					$report_name = $objRow->report_name;
					if($oldreportname != $report_name){
						$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $report_name);
						
						$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
						$objPHPExcel->getActiveSheet()
						->getStyle('A'.$row.':C'.$row)
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
						$row = $row+2;
						
						if($report_name != 'Equip Problems to Report') { 
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Location');
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Property');
							$i=2;
						}
						else {
							$i=0;
						}
						$x = 0;
						$y = 0;
						foreach($fbody as $fb)
						{  
							if($fb->field_type != 'fieldset')
							{
								if($fb->label == ''){
								} 
								else 
								{
									if($objRow->report_name == 'Subcontractors Equipment Usage') 
									{
										$fb->label = $fbody[$x]->label."-".$fb->label;
									}
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $fb->label);
									$i++;
								} 
								$y++; 
							
							} 
							else 
							{ 
								if($y>0) { $x = $x+3;} 
							} 
						
						}
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Name');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Date');
						$row++;
					}
					$locname = $this->objFunction->iFindAll(TBL_SERVICE, array('id'=>$objRow->location_id));
					$propname = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('id'=>$objRow->property_id));
					$uname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->submitted_by));
					$date = $objRow->submission_date;	 
					$fdata = json_decode($objRow->form_values, true);
					$fields = array('db_location','db_property','rid','user_id','timestamp');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $locname[0]->name);$col++;					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $propname[0]->job_listing);$col++; 
					
					foreach($fdata as $key=>$val)
					{
						if( ($key != 'form_token') && ($key != 'send') ){
							if( in_array($key, $fields) === false){  
								 
								if($objRow->report_name == 'Subcontractors Equipment Usage' )
								{
									
									if($val == '') {
										$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
										$col++;
									} 
									else { 
										$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
										$col++;
									}
								}
								elseif(is_array($val)) 
								{
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, implode(',', $val));		 	$col++;  
								}
								else
								{
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);		 	
									$col++;  
								}
							}
						} 
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $uname[0]->f_name . ' ' .$uname[0]->l_name);$col++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, date('Y-m-d h:i A', strtotime($date)));
					$row++;
					$oldreportname = $report_name;
					
				}
			}
			$custom_reports = 'export_custom_reports.xlsx';
			//header('Content-Type: application/vnd.ms-excel');
			//header('Content-Disposition: attachment;filename="'.$custom_reports.'"');
			//header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(str_replace(__FILE__,"export_custom_reports.xlsx",__FILE__));
			/*export important notes*/
			$strsql = "SELECT * FROM ".TBL_PROPERTY_NOTES."  where property_id = '".$id."'";
			$objRs = $this->objDatabase->dbQuery($strsql);
			$important_notes = 'export_important_notes.csv';
			$fd = fopen ($important_notes, "w");
			$strLines = 'StaffId, Note, Date' . PHP_EOL;
			while($objRow = $objRs->fetch_object())
			{
				$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->staff_id_or_admin));
				$strLines.= $rowD[0]->f_name. ' ' .$rowD[0]->l_name . ', ' .$objRow->notes. ', '.$objRow->date_added . PHP_EOL;
			}
			fputs($fd, $strLines);
			fclose($fd);
			/*export Driver reports*/
			$strsql = "SELECT s.id,s.username,s.f_name,s.l_name, js.starting_date as s_date, js.closing_date as c_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  where js.job_id = '".$id."' and s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
			$objRs = $this->objDatabase->dbQuery($strsql);
			$driver_report = 'export_driver_report.csv';
			$fd = fopen ($driver_report, "w");
			$strLines = 'Username/Staff ID, First Name, Last Name, Start Date, Close Date, Hours Worked' . PHP_EOL;
			while($objRow = $objRs->fetch_object())
			{
				$diff1 = strtotime($objRow->c_date) - strtotime($objRow->s_date);
				$work_hour = round($diff1/3600, 2);
				if($work_hour < 0) { $work_hour = 'N/A';}
				$strLines.= $objRow->username . ', ' .$objRow->f_name. ', '.$objRow->l_name. ', '.$objRow->s_date . ', '.$objRow->c_date. ', '.$work_hour . PHP_EOL;
			}
			fputs($fd, $strLines);
			fclose($fd);
			/*export ivr log
			$array_log = $this->objFunction->getStaffIvrLog();
			$ivr_log_staff = 'export_ivr_log_staff.csv';
			ob_start();
			$fd = fopen ($ivr_log_staff, "w");
			if (count($array_log) > 0) {
				$strLines = 'Name, Date, Time, Status' . PHP_EOL;
					foreach($array_log as $log) {
						$strLines.= $log['staff_full_name'] . ', ' .strftime('%b %d %Y', $log['time_stamp']) . ', ' . $log['time_12_hour_clock'] . ', ' . ucfirst($log['clock_action_description']) . PHP_EOL;
					}
				fputs($fd, $strLines);
				fclose($fd);
			}*/
			//$filesarr[] = $ivr_log_staff; 
			$filesarr[] = $important_notes;
			$filesarr[] = $property_report; $filesarr[] = $custom_reports;
			$filesarr[] =$driver_report;
			$files = $filesarr;
			$valid_files = array();
			if(is_array($files)) {
				foreach($files as $file) {
					if(file_exists($file)) {
						$valid_files[] = $file;
					}
				}
			}
			if(count($valid_files > 0)){
				$zip = new ZipArchive();
				$zip_name = 'propertyreset_'.date('Y-m-d H:i:s').'.zip';
				if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){
				$error .= "* Sorry ZIP creation failed at this time";
				}
				
				foreach($valid_files as $file){
				$zip->addFile($file);
				}
				$zip->close();
				
				copy($zip_name, $_SERVER['DOCUMENT_ROOT'].'/upload/resetproperties/'.$zip_name);
				/*$this->objDatabase->insertQuery("insert into ".TBL_SESSION_RESET." (filename, creation_date) values('".$zip_name."', '".date('Y-m-d H:i:s')."')");*/
				//
				foreach($pimg as $img){
					unlink($_SERVER['DOCUMENT_ROOT'].'/upload/'.$img);
				}
				$this->objDatabase->dbQuery("Update ".TBL_JOBLOCATION." set user_gallery = '', importent_notes = '', progress='0', start_date='0000-00-00 00:00:00', pause_date='0000-00-00 00:00:00', completion_date='0000-00-00 00:00:00' where id='".$id."'");
				$this->objDatabase->dbQuery("delete from ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." where prop_id='".$id."'");
				$this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION." where property_id='".$id."'");
				$this->objDatabase->dbQuery("delete from ".TBL_PROPERTY_NOTES." where property_id='".$id."'");
				$this->objDatabase->dbQuery("delete from ".TBL_JOBSTATUS." where job_id='".$id."'");
				//
				//echo 'upload/resetproperties/'.$zip_name;
				header('Content-disposition: attachment; filename=PropertyReset.zip');
    			header('Content-type: application/zip');
    			readfile($zip_name);
				unlink($zip_name);
				//echo 'upload/resetproperties/'.$zip_name;
				//$this->objFunction->showMessage('Zip file created successfully.', ISP :: AdminUrl('reports/jobhistory/id/'.$id));
				die();
			} 
			
		}
		else {
			$this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='0', start_date='0000-00-00 00:00:00', pause_date='0000-00-00 00:00:00', completion_date='0000-00-00 00:00:00'");
		}
		
		$this->objFunction->showMessage('Record has been updated successfully.',ISP :: AdminUrl('reports/completed-properties')); 
		//$this->objFunction->showMessage('Record has been updated successfully.',ISP :: AdminUrl('reports/manage-properties')); 
	}
	public function property_report_jobHistory()
	{
		$strSql = "SELECT * FROM ".TBL_JOBSTATUS." js inner join ".TBL_JOBLOCATION." jl on js.job_id=jl.id  left join ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." si on jl.id=si.prop_id where js.job_id='".$this->intId."' order by js.id desc";
			$this->objSet = $this->objDatabase->dbQuery($strSql);

		$strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and id='".$this->intId."'";
			$this->objSet1 = $this->objDatabase->fetchRows($strSql);
		  
			parent :: admin_property_jobHistory($this->objSet, $this->objSet1);
	}
	
	public function property_report_m_jobHistory()
	{
		$strSql = "SELECT * FROM ".TBL_JOBSTATUS." js inner join ".TBL_JOBLOCATION." jl on js.job_id=jl.id  left join ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." si on jl.id=si.prop_id where js.job_id='".$this->intId."' order by js.id desc";
			$this->objSet = $this->objDatabase->dbQuery($strSql);

		$strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and id='".$this->intId."'";
			$this->objSet1 = $this->objDatabase->fetchRows($strSql);
		  
			parent :: admin_m_property_jobHistory($this->objSet, $this->objSet1);
	}
	public function getReportsDetails($jid)
	{
		$this->objSet = $this->objDatabase->dbQuery("Select report_id from ".TBL_REPORTS_SUBMISSION." where property_id = '".$jid."'");
		return $this->objSet;
	}
	public function report_details($pid) {
	   
	   $strSql = "SELECT * FROM ".TBL_JOBSTATUS." js inner join ".TBL_JOBLOCATION." jl on js.job_id=jl.id where js.job_id=".$pid." order by js.id desc";
		$this->objSet = $this->objDatabase->dbQuery($strSql);
		$reportdetails = $this->objSet;
		return $reportdetails;

	}
   public function staffuploads($pid)
   {
	   	$strSql = "SELECT staff_id, date, images FROM ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." where prop_id=".$pid;
		$this->objSet = $this->objDatabase->dbQuery($strSql);
		$staffuploadsdetails = $this->objSet;
		return $staffuploadsdetails;
   }
   public function staffname($staffid)
   {
	   $strSql = "SELECT * FROM ".TBL_STAFF." where id=".$staffid;
	   $this->objSet = $this->objDatabase->fetchRows($strSql);
		$staffdetails = $this->objSet;
		return $staffdetails;
   }
   public function propname($propid)
   {
	   $strSql = "SELECT * FROM ".TBL_JOBLOCATION." where id=".$propid;
	   $this->objSet = $this->objDatabase->fetchRows($strSql);
		$propdetails = $this->objSet;
		return $propdetails;
   }
   public function export_property_reports($propid)
   {
	   $strSql = "SELECT * FROM ".TBL_REPORTS." r INNER JOIN ".TBL_REPORTS_SUBMISSION." rs ON r.report_id=rs.report_id where rs.property_id=".$propid;
	   $this->objSet = $this->objDatabase->dbQuery($strSql);
		$repdetails = $this->objSet;
		return $repdetails;
   }
   public function direct($pids)
   {  //print_r(explode(',', $_GET['cjid']));echo '1111'; die;
   		require_once 'PHPExcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
					->setCreator("user")
					->setLastModifiedBy("user")
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");
			//print_r($pids); die;
		foreach(range('A','D') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
   //if(!empty($pids))
   if(isset($_GET['cjid']))
   {
	    $pids = explode(',', $_GET['cjid']);
		$row=1;
		foreach($pids as $pid)
		{
			$oldreportname = '';
			if($row != 1){$row = $row+2;}
			$jobdata = $this->report_details($pid);
			
			// Set the active Excel worksheet to sheet 0
			$objPHPExcel->setActiveSheetIndex(0); 
			// Initialise the Excel row number
			$rowCount = 0; 
			$propname = $this->propname($pid);
			$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $propname->job_listing);
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
			$objPHPExcel->getActiveSheet()
			->getStyle('A'.$row.':E'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
			
			$row = $row+2;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Staff #');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Started By');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Job Start (or unpause)');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Pause');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'Job End');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Completed By');
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true)->setSize(12);
			// Build cells
			$row++;
			$count =1;
			while( $objRow = $jobdata->fetch_object() )
			{ 
				$col=0;
				$startdate = date('Y-m-d h:i:s a', strtotime($objRow->starting_date));
				if($objRow->pausing_date != '0000-00-00 00:00:00')
					$pausedate = date('Y-m-d h:i:s a', strtotime($objRow->pausing_date));
				else
					$pausedate = '00:00 Null';
				if($objRow->closing_date != '0000-00-00 00:00:00')
					$enddate = date('Y-m-d h:i:s a', strtotime($objRow->closing_date));
				else
					$enddate = '00:00 Null';
				$closedate = strtotime($objRow->closing_date);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $count);$col++;
				$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->started_by));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$col++; 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $startdate);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $pausedate);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $enddate);$col++;
				$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->closed_by));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$row++; $count++;
				 
			}
			
			$staffuploadsdata = $this->staffuploads($pid);
			$row = $row + 3	;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'StaffName');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Date');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Images');
			
			$objPHPExcel->getActiveSheet()
			->getStyle('B'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()
			->getStyle('C'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()
			->getStyle('D'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
			
			$row++;
			$supload = array();
			while($objRow = $staffuploadsdata->fetch_object())  // Fetch the result in the object array
			{	
				$supload[] = get_object_vars($objRow);	
			}
			foreach($supload as $objRow1)
			{
				$col = 1;
				foreach($objRow1 as $key=>$value) {
					if($key == 'images')
					{
						$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(95);
						$col = $row;
						if (strpos($value, ',') !== false)
						{
							$imgcell = 'D';
							$img = explode(',', $value);
							foreach($img as $pimg)
							{
								$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('Customer Signature');
								$objDrawing->setDescription('Customer Signature');
								//Path to signature .jpg file
								$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$pimg;     
								$objDrawing->setPath($signature);
								$objDrawing->setOffsetX(25);                     //setOffsetX works properly
								$objDrawing->setOffsetY(10);                     //setOffsetY works properly
								$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
								$objDrawing->setWidth(100);  
								$objDrawing->setHeight(90);                     //signature height  
								$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
								++$imgcell;
							}
						}
						else
						{
							$imgcell = 'D';
							$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
							$image = $value;
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Customer Signature');
							$objDrawing->setDescription('Customer Signature');
							//Path to signature .jpg file
							$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$image;     
							$objDrawing->setPath($signature);
							$objDrawing->setOffsetX(25);                     //setOffsetX works properly
							$objDrawing->setOffsetY(10);                     //setOffsetY works properly
							$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
							$objDrawing->setWidth(100);  
							$objDrawing->setHeight(90);                     //signature height  
							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
						}
						
					}
					elseif($key == 'staff_id')
					{
						$staffname = $this->staffname($value);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $staffname->f_name.' '.$staffname->l_name);
					}
					else
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
					}
					$col++;
				}
				$row++;
			}
			//
			$row = $row+2;
			$propertyreports = $this->export_property_reports($pid);
			while($objRow = $propertyreports->fetch_object())
			{
				$col=0;
				$fbody = json_decode($objRow->form_body); 
				$report_name = $objRow->report_name;
				if($oldreportname != $report_name){
					$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $report_name);
					
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
					$objPHPExcel->getActiveSheet()
					->getStyle('A'.$row.':C'.$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
					$row = $row+2;
					
					if($report_name != 'Equip Problems to Report') { 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Location');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Property');
						$i=2;
					}
					else {
						$i=0;
					}
					$x = 0;
					$y = 0;
					foreach($fbody as $fb)
					{  
						if($fb->field_type != 'fieldset')
						{
							if($fb->label == ''){
							} 
							else 
							{
								if($objRow->report_name == 'Subcontractors Equipment Usage') 
								{
									$fb->label = $fbody[$x]->label."-".$fb->label;
								}
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $fb->label);
								$i++;
							} 
							$y++; 
						
						} 
						else 
						{ 
							if($y>0) { $x = $x+3;} 
						} 
					
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Name');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Date');
					$row++;
				}
				$locname = $this->objFunction->iFindAll(TBL_SERVICE, array('id'=>$objRow->location_id));
				$propname = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('id'=>$objRow->property_id));
				$uname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->submitted_by));
				$date = $objRow->submission_date; 
				$fdata = json_decode($objRow->form_values, true);
				$fields = array('db_location','db_property','rid','user_id','timestamp');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $locname[0]->name);$col++;					
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $propname[0]->job_listing);$col++; 
				
				foreach($fdata as $key=>$val)
				{
					if( ($key != 'form_token') && ($key != 'send') ){
						if( in_array($key, $fields) === false){  
							 
							if($objRow->report_name == 'Subcontractors Equipment Usage' )
							{
								
								if($val == '') {
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
									$col++;
								} 
								else { 
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
									$col++;
								}
							}
							elseif(is_array($val)) 
							{
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, implode(',', $val));		 	$col++;  
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);		 	
								$col++;  
							}
						}
					} 
				}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $uname[0]->f_name . ' ' .$uname[0]->l_name);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, date('Y-m-d h:i A', strtotime($date)));
				$row++;
				$oldreportname = $report_name;
				
			}
			//
		}
	}
   elseif(!isset($_GET['jid']))
	{
		$strSql = "select id from ".TBL_JOBLOCATION;
		$propids = $this->objDatabase->dbQuery($strSql);
		$pids = array();
		foreach($propids as $propid){ $pids[] = $propid['id'];}
		//print_r($pids); die;
		$row=1;
		foreach($pids as $pid)
		{
			$oldreportname = '';
			if($row != 1){$row = $row+2;}
			$jobdata = $this->report_details($pid);
			
			// Set the active Excel worksheet to sheet 0
			$objPHPExcel->setActiveSheetIndex(0); 
			// Initialise the Excel row number
			$rowCount = 0; 
			$propname = $this->propname($pid);
			$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $propname->job_listing);
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
			$objPHPExcel->getActiveSheet()
			->getStyle('A'.$row.':E'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
			
			$row = $row+2;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Staff #');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Started By');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Job Start (or unpause)');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Pause');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'Job End');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Completed By');
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true)->setSize(12);
			// Build cells
			$row++;
			$count =1;
			while( $objRow = $jobdata->fetch_object() )
			{ 
				$col=0;
				$startdate = date('Y-m-d h:i:s a', strtotime($objRow->starting_date));
				if($objRow->pausing_date != '0000-00-00 00:00:00')
					$pausedate = date('Y-m-d h:i:s a', strtotime($objRow->pausing_date));
				else
					$pausedate = '00:00 Null';
				if($objRow->closing_date != '0000-00-00 00:00:00')
					$enddate = date('Y-m-d h:i:s a', strtotime($objRow->closing_date));
				else
					$enddate = '00:00 Null';
				$closedate = strtotime($objRow->closing_date);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $count);$col++;
				$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->started_by));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$col++; 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $startdate);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $pausedate);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $enddate);$col++;
				$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->closed_by));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$row++; $count++;
				 
			}
			
			$staffuploadsdata = $this->staffuploads($pid);
			$row = $row + 3	;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'StaffName');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Date');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Images');
			
			$objPHPExcel->getActiveSheet()
			->getStyle('B'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()
			->getStyle('C'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()
			->getStyle('D'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
			
			$row++;
			$supload = array();
			while($objRow = $staffuploadsdata->fetch_object())  // Fetch the result in the object array
			{	
				$supload[] = get_object_vars($objRow);	
			}
			foreach($supload as $objRow1)
			{
				$col = 1;
				foreach($objRow1 as $key=>$value) {
					if($key == 'images')
					{
						$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(95);
						$col = $row;
						if (strpos($value, ',') !== false)
						{
							$imgcell = 'D';
							$img = explode(',', $value);
							foreach($img as $pimg)
							{
								$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('Customer Signature');
								$objDrawing->setDescription('Customer Signature');
								//Path to signature .jpg file
								$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$pimg;     
								$objDrawing->setPath($signature);
								$objDrawing->setOffsetX(25);                     //setOffsetX works properly
								$objDrawing->setOffsetY(10);                     //setOffsetY works properly
								$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
								$objDrawing->setWidth(100);  
								$objDrawing->setHeight(90);                     //signature height  
								$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
								++$imgcell;
							}
						}
						else
						{
							$imgcell = 'D';
							$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
							$image = $value;
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Customer Signature');
							$objDrawing->setDescription('Customer Signature');
							//Path to signature .jpg file
							$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$image;     
							$objDrawing->setPath($signature);
							$objDrawing->setOffsetX(25);                     //setOffsetX works properly
							$objDrawing->setOffsetY(10);                     //setOffsetY works properly
							$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
							$objDrawing->setWidth(100);  
							$objDrawing->setHeight(90);                     //signature height  
							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
						}
						
					}
					elseif($key == 'staff_id')
					{
						$staffname = $this->staffname($value);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $staffname->f_name.' '.$staffname->l_name);
					}
					else
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
					}
					$col++;
				}
				$row++;
			}
			//
			$row = $row+2;
			$propertyreports = $this->export_property_reports($pid);
			while($objRow = $propertyreports->fetch_object())
			{ 
				$col=0;
				$fbody = json_decode($objRow->form_body); 
				$report_name = $objRow->report_name;
				if($oldreportname != $report_name){
					$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $report_name);
					
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
					$objPHPExcel->getActiveSheet()
					->getStyle('A'.$row.':C'.$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
					$row = $row+2;
					
					if($report_name != 'Equip Problems to Report') { 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Location');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Property');
						$i=2;
					}
					else {
						$i=0;
					}
					$x = 0;
					$y = 0;
					foreach($fbody as $fb)
					{  
						if($fb->field_type != 'fieldset')
						{
							if($fb->label == ''){
							} 
							else 
							{
								if($objRow->report_name == 'Subcontractors Equipment Usage') 
								{
									$fb->label = $fbody[$x]->label."-".$fb->label;
								}
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $fb->label);
								$i++;
							} 
							$y++; 
						
						} 
						else 
						{ 
							if($y>0) { $x = $x+3;} 
						} 
					
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Name');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Date');
					$row++;
				}
				$locname = $this->objFunction->iFindAll(TBL_SERVICE, array('id'=>$objRow->location_id));
				$propname = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('id'=>$objRow->property_id));
				$uname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->submitted_by));
				$date = $objRow->submission_date; 
				$fdata = json_decode($objRow->form_values, true);
				$fields = array('db_location','db_property','rid','user_id','timestamp');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $locname[0]->name);$col++;					
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $propname[0]->job_listing);$col++; 
				
				foreach($fdata as $key=>$val)
				{
					if( ($key != 'form_token') && ($key != 'send') ){
						if( in_array($key, $fields) === false){  
							 
							if($objRow->report_name == 'Subcontractors Equipment Usage' )
							{
								
								if($val == '') {
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
									$col++;
								} 
								else { 
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
									$col++;
								}
							}
							elseif(is_array($val)) 
							{
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, implode(',', $val));		 	$col++;  
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);		 	
								$col++;  
							}
						}
					} 
				}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $uname[0]->f_name . ' ' .$uname[0]->l_name);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, date('Y-m-d h:i A', strtotime($date)));
				$row++;
				$oldreportname = $report_name;
				
			}
			//
		}
	}
   else
   {
	$jobdata = $this->report_details($_GET['jid']);
	// Set the active Excel worksheet to sheet 0
	$objPHPExcel->setActiveSheetIndex(0); 
	// Initialise the Excel row number
	$rowCount = 0; 
	$row=1;
	$propname = $this->propname($_GET['jid']);
	$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', $propname->job_listing);
	
	$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
	$objPHPExcel->getActiveSheet()
    ->getStyle('A1:E1')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
	
	$row = $row+2;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Staff #');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Started By');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Job Start (or unpause)');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Pause');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'Job End');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Completed By');
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true)->setSize(12);
	// Build cells
	$row++;
	$count =1;
	while( $objRow = $jobdata->fetch_object() )
	{ 
		$col=0;
		$startdate = date('Y-m-d h:i:s a', strtotime($objRow->starting_date));
		if($objRow->pausing_date != '0000-00-00 00:00:00')
			$pausedate = date('Y-m-d h:i:s a', strtotime($objRow->pausing_date));
		else
			$pausedate = '00:00 Null';
		if($objRow->closing_date != '0000-00-00 00:00:00')
			$enddate = date('Y-m-d h:i:s a', strtotime($objRow->closing_date));
		else
			$enddate = '00:00 Null';
		$closedate = strtotime($objRow->closing_date);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $count);$col++;
		$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->started_by));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$col++; 
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $startdate);$col++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $pausedate);$col++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $enddate);$col++;
		$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->closed_by));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$row++; $count++;
		 
	}
	
	$staffuploadsdata = $this->staffuploads($_GET['jid']);
	$row = $row + 3	;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'StaffName');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Date');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Images');
	
	$objPHPExcel->getActiveSheet()
    ->getStyle('B'.$row)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()
    ->getStyle('C'.$row)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()
    ->getStyle('D'.$row)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
	
	$row++;
	$supload = array();
	while($objRow = $staffuploadsdata->fetch_object())  // Fetch the result in the object array
	{	
		$supload[] = get_object_vars($objRow);	
	}
	foreach($supload as $objRow1)
	{
		$col = 1;
		foreach($objRow1 as $key=>$value) {
			if($key == 'images')
			{
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(95);
				$col = $row;
				if (strpos($value, ',') !== false)
				{
					$imgcell = 'D';
					$img = explode(',', $value);
					foreach($img as $pimg)
					{
						$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('Customer Signature');
						$objDrawing->setDescription('Customer Signature');
						//Path to signature .jpg file
						$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$pimg;     
						$objDrawing->setPath($signature);
						$objDrawing->setOffsetX(25);                     //setOffsetX works properly
						$objDrawing->setOffsetY(10);                     //setOffsetY works properly
						$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
						$objDrawing->setWidth(100);  
						$objDrawing->setHeight(90);                     //signature height  
						$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
						++$imgcell;
					}
				}
				else
				{
					$imgcell = 'D';
					$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
					$image = $value;
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('Customer Signature');
					$objDrawing->setDescription('Customer Signature');
					//Path to signature .jpg file
					$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$image;     
					$objDrawing->setPath($signature);
					$objDrawing->setOffsetX(25);                     //setOffsetX works properly
					$objDrawing->setOffsetY(10);                     //setOffsetY works properly
					$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
					$objDrawing->setWidth(100);  
					$objDrawing->setHeight(90);                     //signature height  
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
				}
				
			}
			elseif($key == 'staff_id')
			{
				$staffname = $this->staffname($value);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $staffname->f_name.' '.$staffname->l_name);
			}
			else
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
			}
			$col++;
		}
		$row++;
	}
	//
	$row = $row+2;
	$propertyreports = $this->export_property_reports($_GET['jid']);
	while($objRow = $propertyreports->fetch_object())
	{
		$col=0;
		$fbody = json_decode($objRow->form_body); 
		$report_name = $objRow->report_name;
		if($oldreportname != $report_name){
			$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $report_name);
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
			$objPHPExcel->getActiveSheet()
			->getStyle('A'.$row.':C'.$row)
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
			$row = $row+2;
			
			if($report_name != 'Equip Problems to Report') { 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Location');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Property');
				$i=2;
			}
			else {
				$i=0;
			}
			$x = 0;
			$y = 0;
			foreach($fbody as $fb)
			{  
				if($fb->field_type != 'fieldset')
				{
					if($fb->label == ''){
					} 
					else 
					{
						if($objRow->report_name == 'Subcontractors Equipment Usage') 
						{
							$fb->label = $fbody[$x]->label."-".$fb->label;
						}
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $fb->label);
						$i++;
					} 
					$y++; 
				
				} 
				else 
				{ 
					if($y>0) { $x = $x+3;} 
				} 
			
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Name');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Date');
			$row++;
		}
		$locname = $this->objFunction->iFindAll(TBL_SERVICE, array('id'=>$objRow->location_id));
		$propname = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('id'=>$objRow->property_id));
		$uname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->submitted_by));
		$date = $objRow->submission_date; 
		$fdata = json_decode($objRow->form_values, true);
		$fields = array('db_location','db_property','rid','user_id','timestamp');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $locname[0]->name);$col++;					
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $propname[0]->job_listing);$col++; 
		
		foreach($fdata as $key=>$val)
		{
			if( ($key != 'form_token') && ($key != 'send') ){
				if( in_array($key, $fields) === false){  
					 
					if($objRow->report_name == 'Subcontractors Equipment Usage' )
					{
						
						if($val == '') {
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
							$col++;
						} 
						else { 
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
							$col++;
						}
					}
					elseif(is_array($val)) 
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, implode(',', $val));		 	$col++;  
					}
					else
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);		 	
						$col++;  
					}
				}
			} 
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $uname[0]->f_name . ' ' .$uname[0]->l_name);$col++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, date('Y-m-d h:i A', strtotime($date)));
		$row++;
		$oldreportname = $report_name;
		
	}
	//
   }
	$rand = rand(1234, 9898);
	$presentDate = date('YmdHis');
	$fileName = "report_" . $rand . "_" . $presentDate . ".xlsx";

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$fileName.'"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save('php://output');
    die();
   }
			
  public function showForm()
  {
    $rs = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$this->intId."'");
    $fields =  json_decode($rs->form_body);
    $formControls = array();
    $form_token = md5(session_id());

    $fieldCount=0;
    
    $formControls[$form_token]['#value']['db_location'] = 
       array(
              '#type' => 'hidden',
              '#title' => '',
              '#name'=>'db_location'
        ); 
    
    $formControls[$form_token]['#value']['db_property'] = 
       array(
              '#type' => 'hidden',
              '#title' => '',
              '#name'=>'db_property'
        );    
    
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
	 
	   if(!empty($_POST))
       {
            if($_REQUEST['db_location']<>'')
            {
              $strLocation = $this->objFunction->iFind(TBL_SERVICE, 'name', array('id'=>$_REQUEST['db_location'])); 
              $strProperty = $this->objFunction->iFind(TBL_JOBLOCATION, 'job_listing', array('id'=>$_REQUEST['db_property'])); 
               $message ='Location: '.$strLocation."\n";
              $message .='Property: '.$strProperty."\n";
          	}	
            
            foreach($_POST as $key => $val) 
      			{ 
                    if(is_array($val))
                    {
                       $val = implode(", ", $val);
                    }
                    if($key<>'send' && $key<>'db_location' && $key<>'db_property') 
                    {		
                    if (is_array($val))
                        $val = implode(", ", $val);

                    if ($val != '') {
                        $message .= ucfirst($formControls[$_POST['form_token']]['#value'][$key]['#title']).": $val\n"; 
                    }

                    }
      			}
                $postedValues = json_encode($_POST);

            //echo "INSERT INTO ".TBL_REPORTS_SUBMISSION." (report_id,location_id, property_id, form_values,submitted_by) values('".$this->intId."','".$_REQUEST['db_location']."', '".$_REQUEST['db_property']."','".$postedValues."','".$_SESSION['adminid']."')";
      			$this->objDatabase->dbQuery("INSERT INTO ".TBL_REPORTS_SUBMISSION." (report_id,location_id, property_id, form_values, submission_date, submitted_by) values('".$this->intId."','".$_REQUEST['db_location']."', '".$_REQUEST['db_property']."', '".$postedValues."', '".date('Y-m-d H:i:s')."', '".$_SESSION['adminid']."')");
      			$this->objDatabase->dbQuery("update ".TBL_REPORTS." set submissions=submissions+1 where report_id='".$this->intId."'");
                $sendTo = $rs->send_to;
      			$mailSubject = $rs->mail_subject;
                if ($sendTo != '') {
                    mail($sendTo, $mailSubject, $message);
                }
      			 
                $successmsg=1;
    }
   
    parent:: showForm($rs, $formControls, $form_token, $successmsg);
    
  }
  
  public function viewFormPostings()
  {
    $rsData = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS_SUBMISSION." where id='".$this->intId."'");
    $postedValues = (array)json_decode($rsData->form_values);
    $rs = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$rsData->report_id."'");
    $fields =  json_decode($rs->form_body);
    $formControls = array();
    $form_token = md5(session_id());

    $fieldCount=0;
    
    $formControls[$form_token]['#value']['db_location'] = 
       array(
              '#type' => 'hidden',
              '#title' => '',
              '#name'=>'db_location'
        ); 
    
    $formControls[$form_token]['#value']['db_property'] = 
       array(
              '#type' => 'hidden',
              '#title' => '',
              '#name'=>'db_property'
        );    
    
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
			        '#class' => $field->class,
              '#value' => $postedValues['db_field_'.$fieldCount]
        ); 
     }                                                                    
  
    parent:: showFormSubmission($rs, $formControls, $form_token, $postedValues, $rsData);
  }
  
  public function listFormPostings()
  {
    $rs = $this->objDatabase->dbQuery("select * from ".TBL_REPORTS_SUBMISSION." rs inner join ".TBL_REPORTS." r on r.report_id=rs.report_id where rs.report_id='".$this->intId."'");
    parent:: listFormPostings($rs);
  } 
  
  public function modifyFormStatus()
  {
    $intStatus = $_REQUEST['status'];
    $this->objDatabase->dbQuery("UPDATE ".TBL_REPORTS." set status='".$intStatus."' where report_id='".$this->intId."'");
	echo '<script>window.location.href="'.ISP :: AdminUrl('reports/listing').'"</script>';
    //$this->objFunction->showMessage('Form status been updated successfully.', ISP :: AdminUrl('reports/listing'));  //Function to show the message
  }
  
    public function zipbackup() {
        //print_r($_SESSION['filebackup']['files']);
        $export_file = 'upload/zip/property-reports-backup-'.date('Y-m-d').'.zip';
        fileZip($_SESSION['filebackup']['files'], $export_file, $_SESSION['filebackup']['images']);
        
        parent :: zipBackup($export_file);
    }
  
  public function flushReportData()
  {
    $report_id = implode(",", $_POST['delete']);
    
    if ($_POST['btn_backup_reset']) {
        $export_file = 'upload/zip/export-reports-full-backup-'.date('Y-m-d').'.zip';
        
        $propertyData = $this->objFunction->getPropertyData();
        $propertyImages = $this->objFunction->getPropertyImages();
        
        $prop_headings = array_keys($propertyData);
        $property_file = 'property_data_'.date('Y-m-d').'.csv';
        
        $this->objFunction->write2excel($prop_headings, $propertyData, $property_file); 
        
        $export_reports[] = $property_file;
        
        /*
        $objRsReport = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION."");
        $flag = 0;
        $num = mysqli_num_rows($objRsReport);
        
        if($num > 0){
            while ($objReport = $objRsReport->fetch_object())
            {
        */
                $objRs = $this->objDatabase->dbQuery("SELECT rs.*, r.report_name FROM ".TBL_REPORTS_SUBMISSION." rs inner join ".TBL_REPORTS." r on r.report_id = rs.report_id order by rs.report_id asc");
                $num = mysqli_num_rows($objRs);

                //$reportKeys = FALSE;
                //$lastReport = 0;
                //$report_data = array();

                //$reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReport->report_id));
                //$fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                
                $new_report = 0;
                
                while ($objRow = $objRs->fetch_object())
                {
                    $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                    
                    if($new_report != $objRow->report_id && $new_report > 0) {
                       $this->objFunction->write2excel($headings, $report_data, $fileName); 
                    }
                    if ($new_report != $objRow->report_id) {
                        $headings = array_keys($form_data);
                        $new_report = $objRow->report_id;
                        $fileName = $this->objFunction->removeUnsed($objRow->report_name).'-'.date('Y-m-d').'.csv';
                        $export_reports[] = $fileName;
                    }
                    $report_data[] =  $form_data;                   
                }
                   
                
            //}   
       // }  

        $_SESSION['filebackup'] = array();
        $_SESSION['filebackup']['files'] = $export_reports;
        $_SESSION['filebackup']['images'] = $propertyImages;
        //$this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION."");
        //$this->objDatabase->dbQuery("update ".TBL_REPORTS." set submissions='0'");
        $staff_image_condition = "";
        if ($_POST['flush_staffimages']==1) {
          $staff_image_condition = ", user_gallery=''";
        }
        $this->objDatabase->dbQuery("update ".TBL_JOBLOCATION." set progress='0', start_date='0000-00-00 00:00:00',completion_date='0000-00-00 00:00:00'".$staff_image_condition);
        //fileZip($export_reports, $export_file, $propertyImages);   
        $this->objFunction->showMessage('Backup files created successfully. Please wait, we are creating zip of all the backup files to download', ISP :: AdminUrl('reports/zipbackup/'));        
                 
    }
    elseif ($_POST['btn_go']) {
        
        if ($_POST['action'] == 'publish') {
            $this->objDatabase->dbQuery("UPDATE ".TBL_REPORTS." set status='1' where report_id in (".$report_id.")");  
        } 
        elseif ($_POST['action'] == 'unpublish') {
            $this->objDatabase->dbQuery("UPDATE ".TBL_REPORTS." set status='0' where report_id in (".$report_id.")");  
        }
        elseif ($_POST['action'] == 'delete') {
            $this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION." where report_id in (".$report_id.")");
            $this->objDatabase->dbQuery("delete from ".TBL_REPORTS." where report_id in (".$report_id.")");
        }
        elseif ($_POST['action'] == 'flush') {
            $this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION." where report_id in (".$report_id.")");
            $this->objDatabase->dbQuery("update ".TBL_REPORTS." set submissions='0' where report_id in (".$report_id.")");
        }
        $this->objFunction->showMessage('Records updated successfully.', ISP :: AdminUrl('reports/listing'));
    }
    elseif ($_POST['btn_flush'] != '') {
        $report_id = implode(",", $_POST['delete']);
        $this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION." where report_id in (".$report_id.")");
        $this->objDatabase->dbQuery("update ".TBL_REPORTS." set submissions='0' where report_id in (".$report_id.")");
        $this->objFunction->showMessage('Records updated successfully.', ISP :: AdminUrl('reports/listing'));
    }
    elseif($_POST['btn_delete'] != '') {
       $report_id = implode(",", $_POST['delete']);
        $this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION." where report_id in (".$report_id.")");
        $this->objDatabase->dbQuery("delete from ".TBL_REPORTS." where report_id in (".$report_id.")");
        $this->objFunction->showMessage('Records updated successfully.', ISP :: AdminUrl('reports/listing'));
    }
    elseif($_POST['btn_reset'] != '') {
        $this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION."");
        $this->objDatabase->dbQuery("update ".TBL_REPORTS." set submissions='0'");
        $this->objDatabase->dbQuery("update ".TBL_JOBLOCATION." set progress='0'");
        $this->objFunction->showMessage('Records updated successfully.', ISP :: AdminUrl('reports/listing'));
    }
    else {
        if ($this->intId >0) {
            $this->objDatabase->dbQuery("delete from ".TBL_REPORTS_SUBMISSION." where report_id='".$this->intId."'");
            $this->objDatabase->dbQuery("update ".TBL_REPORTS." set submissions='0' where report_id='".$this->intId."'");
        }
        $this->objFunction->showMessage('Records updated successfully.', ISP :: AdminUrl('reports/listing'));
    }
    
    
  }
  
 public function exportReports() 
 {  
    $export_file = '';
    if (isset($_POST['export_btn']))
    {
        $export_reports = array();
        $export_file = 'upload/zip/export-reports-'.date('Y-m-d').'.zip';
        
       foreach ($_POST['export'] as $exportReport)
       {
           if ($exportReport == 'all_state_reports')
           {              
              $duration = $_POST['duration_allstate_report'];
              $objRsReport = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION."");
              $flag = 0;
              $num = mysqli_num_rows($objRsReport);
              if($num > 0){
                while ($objReport = $objRsReport->fetch_object())
                 {
                     if($duration != ''){
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReport->report_id."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now()");
                      $num = mysqli_num_rows($objRs);
                      if($num == 0){
                        $flag=1;
                        break 1;
                      }
                     }else{
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReport->report_id."'");
                      $num = mysqli_num_rows($objRs);
                      if($num == 0){
                        $flag=1;
                        break 1;
                      }
                     }
                     $reportKeys = FALSE;
                     $lastReport = 0;
                     $report_data = array();

                     $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReport->report_id));
                     $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                     
                     while ($objRow = $objRs->fetch_object())
                     {
                       $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                       if ($reportKeys == FALSE)
                       {
                           $headings = array_keys($form_data);
                       }
                       $reportKeys = TRUE; 
                       $report_data[] =  $form_data;
                     }
                     $this->objFunction->write2excel($headings, $report_data, $fileName);   
                     $export_reports[] = $fileName;
                 }   
              }
              else{
                $flag=1;
              }
             
             if($flag == 1){ 
                $export_file = '';
                echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";
				$this->objFunction->showMessage('There are no reports to export.', ISP :: AdminUrl('reports/reportsmanager'));
             }
             else{
                fileZip($export_reports, $export_file);
             } 
           }
           
           if ($exportReport == 'single_location_reports')
           {
               $duration = $_POST['duration_singlelocation_report'];
               $location_id = $_POST['location_singlelocation_report'];
               
               $objRsReport = $this->objDatabase->fetchRows("SELECT enabled_reports from ".TBL_JOBLOCATION." where location_id='".$location_id."'");
               $reportsArray = explode(",", $objRsReport->enabled_reports);
               $flag = 0;

               foreach ($reportsArray  as $objReportId)
               {
                  if($duration != ''){
                    if($objReportId == ''){
                      $flag=1;
                      break 1;
                    }else{
                      if($location_id == ''){
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                      }else{
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and location_id='".$location_id."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                      }
                      //$num_rows = mysqli_num_rows($objRs);
                      //if($num_rows == 0){
                      if (!$objRs) {
                        $flag=1;
                        break 1;
                      }
                    }
                  }else{
                    if($objReportId == ''){
                      $flag=1;
                      break 1;
                    }else{
                      if($location_id == ''){
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' order by submission_date desc");
                      }else{
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and location_id='".$location_id."' order by submission_date desc");
                      }
                      $num_rows = mysql_num_rows($objRs);
                      if(!$objRs){  /*$objRs == 0*/
                        $flag=1;
                        break 1;
                      }
                    }
                  }

                   $reportKeys = FALSE;
                   $lastReport = 0;
                   $report_data = array();

                   $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReportId));
                   $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                   
                   while ($objRow = $objRs->fetch_object())
                   {
                     $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                     
                     if ($reportKeys == FALSE)
                     {
                         $headings = array_keys($form_data);
                     }
                     $reportKeys = TRUE; 
                     $report_data[] =  $form_data;
                   }
                   $this->objFunction->write2excel($headings, $report_data, $fileName);   
                   $export_reports[] = $fileName;
               }   
               if($flag == 1){
                  $export_file = '';
                  echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";
				  $this->objFunction->showMessage('There are no reports to export.', ISP :: AdminUrl('reports/reportsmanager'));
               }
               else{
                  fileZip($export_reports, $export_file);
               }
           }
           
           if ($exportReport == 'individual_reports')
           {
               $duration = $_POST['duration_individual_report'];
               $objReportId = $_POST['report_individual_report'];
               $flag = 0;
               if($duration != ''){
                  if($objReportId != ''){
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }else{
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }
               }else{
                  if($objReportId != ''){
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }else{
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }
               }
                   $reportKeys = FALSE;
                   $lastReport = 0;
                   $report_data = array();

                   $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReportId));
                   $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                   
                   while ($objRow = $objRs->fetch_object())
                   {
                     $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                     
                     if ($reportKeys == FALSE)
                     {
                         $headings = array_keys($form_data);
                     }
                     $reportKeys = TRUE; 
                     $report_data[] =  $form_data;
                   }
                   $this->objFunction->write2excel($headings, $report_data, $fileName);   
                   $export_reports[] = $fileName;
                   if($flag == 1) {
                      $export_file = '';
                      echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";
					  $this->objFunction->showMessage('There are no reports to export.', ISP :: AdminUrl('reports/reportsmanager'));
                   }
                   else{
                      fileZip($export_reports, $export_file);
                   }
           }
          if ($exportReport == 'manager_reports')
           {
               $duration = $_POST['duration_manager_report'];
               $assigned_to_id = $_POST['staff_manager_id'];

               $objRsReport = $this->objDatabase->fetchRows("SELECT enabled_reports from ".TBL_JOBLOCATION." where assigned_to='".$assigned_to_id."'");
               $reportsArray = explode(",", $objRsReport->enabled_reports);
               $flag = 0;
               foreach ($reportsArray  as $objReportId)
               {
                  if($duration != ''){
                   if($objReportId == ''){
                      $flag=1;
                      break 1;
                   }else{
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                      $num_rows = mysqli_num_rows($objRs);
                      if($num_rows == 0){
                        $flag=1;
                        break 1;
                      }
                   }
                  }else{
                    if($objReportId == ''){
                      $flag=1;
                      break 1;
                    }else{
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' order by submission_date desc");
                      if($num_rows == 0){
                        $flag=1;
                        break 1;
                      }
                    }
                  }
                   $reportKeys = FALSE;
                   $lastReport = 0;
                   $report_data = array();

                   $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReportId));
                   $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                   
                   while ($objRow = $objRs->fetch_object())
                   {
                     $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                     
                     if ($reportKeys == FALSE)
                     {
                         $headings = array_keys($form_data);
                     }
                     $reportKeys = TRUE; 
                     $report_data[] =  $form_data;
                   }
                   $this->objFunction->write2excel($headings, $report_data, $fileName);   
                   $export_reports[] = $fileName;
               }  
               if($flag == 1){
                  $export_file = '';
                  echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";
				  $this->objFunction->showMessage('There are no reports to export.', ISP :: AdminUrl('reports/reportsmanager'));
               }else{
                  fileZip($export_reports, $export_file);
               }
           }  
       }
	   $this->objFunction->showMessage('Zip file created successfully.', ISP :: AdminUrl('reports/reportsmanager'));
 }
    
    parent::exportReports($export_file); 
 }
  public function exportReport() 
 {   
    $export_file = '';
    if (isset($_POST['task']))
    {
        $export_reports = array(); $datet = str_replace(' ', '_', date('Y-m-d H:i:s'));
        $export_file = 'upload/zip/export-reports-'.$datet.'.zip';
		$status = ''; $flag = '';
        
       foreach ($_POST['export'] as $exportReport)
       {
           if ($exportReport == 'all_state_reports')
           {              
              $duration = $_POST['duration_allstate_report'];
              $objRsReport = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION."");
              $flag = 0;
              $num = mysqli_num_rows($objRsReport);
              if($num > 0){
                while ($objReport = $objRsReport->fetch_object())
                 {
                     if($duration != '' && $duration != 'as-rep-date'){
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReport->report_id."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now()");
					  $num = mysqli_num_rows($objRs);
                      if($num == 0){
                        $flag=1;
                        break 1;
                      }
					 }
					 elseif($_POST['asdate_from'] != '' && $_POST['asdate_to'] != '')
					 {
						 $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReport->report_id."' and UNIX_TIMESTAMP(submission_date) >= UNIX_TIMESTAMP('".$_POST['asdate_from']."') and UNIX_TIMESTAMP(submission_date) <= UNIX_TIMESTAMP('".$_POST['asdate_to']."')");
						 $num = mysqli_num_rows($objRs);
						  if($num == 0){
							$flag=1;
							break 1;
						  }
                     }
					 else{
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReport->report_id."'");
                      $num = mysqli_num_rows($objRs);
                      if($num == 0){
                        $flag=1;
                        break 1;
                      }
                     }
                     $reportKeys = FALSE;
                     $lastReport = 0;
                     $report_data = array();

                     $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReport->report_id));
                     $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                     
                     while ($objRow = $objRs->fetch_object())
                     {
                       $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                       if ($reportKeys == FALSE)
                       {
                           //$headings = array_keys($form_data);
						 $headings = array();
						 $headings[] = 'Location'; 
						 $headings[] = 'Property';
    					$rs = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$objReport->report_id."'");
    					$fbody =  json_decode($rs->form_body);
						$x = 0;
						$y = 0;
						foreach($fbody as $fb)
						{  
							if($fb->field_type != 'fieldset')
							{
								if($fb->label == ''){
								} 
								else 
								{
									if($reportName == 'Subcontractors Equipment Usage') 
									{
										$fb->label = $fbody[$x]->label."-".$fb->label;
									}
									$headings[] = $fb->label;
								} 
								$y++; 
							
							} 
							else 
							{ 
								if($y>0) { $x = $x+3;} 
							} 
						
						}
						$headings[] = 'Submission Date'; 
						$headings[] = 'Submitted By';
						$headings[] = 'Assigned To';
                       }
                       $reportKeys = TRUE; 
                       $report_data[] =  $form_data;
                     }
                     $this->objFunction->write2excel($headings, $report_data, $fileName);   
                     $export_reports[] = $fileName;
                 }   
              }
              else{
                $flag=1;
              }
             
             if($flag == 1){ 
                $export_file = '';
                /*echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";*/
             }
             else{
                fileZip($export_reports, $export_file);
				$status = 1;
             } 
           }
           
           if ($exportReport == 'single_location_reports')
           {
               $duration = $_POST['duration_singlelocation_report'];
               $location_id = $_POST['location_singlelocation_report'];
               
               $objRsReport = $this->objDatabase->fetchRows("SELECT enabled_reports from ".TBL_JOBLOCATION." where location_id='".$location_id."'");
               $reportsArray = explode(",", $objRsReport->enabled_reports);
               $flag = 0;

               foreach ($reportsArray  as $objReportId)
               {
                  if($duration != '' && $duration != 'sl-rep-date'){
                    if($objReportId == ''){
                      $flag=1;
                      break 1;
                    }else{
                      if($location_id == ''){
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                      }else{
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and location_id='".$location_id."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                      }
                      $num_rows = mysqli_num_rows($objRs);
                      if($num_rows == 0){
                      //if (!$objRs) {
                        $flag=1;
                        break 1;
                      }
                    }
                  }elseif($_POST['sldate_from'] != '' && $_POST['sldate_to'] != ''){
					  if($objReportId == ''){
                      $flag=1;
                      break 1;
                    }else{
                      if($location_id == ''){
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and UNIX_TIMESTAMP(submission_date) >= UNIX_TIMESTAMP('".$_POST['asdate_from']."') and UNIX_TIMESTAMP(submission_date) <= UNIX_TIMESTAMP('".$_POST['asdate_to']."') order by submission_date desc");
                      }else{
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and location_id='".$location_id."' and UNIX_TIMESTAMP(submission_date) >= UNIX_TIMESTAMP('".$_POST['sldate_from']."') and UNIX_TIMESTAMP(submission_date) <= UNIX_TIMESTAMP('".$_POST['sldate_to']."') order by submission_date desc");
                      }
                      $num_rows = mysqli_num_rows($objRs);
                      if($num_rows == 0){
                     // if (!$objRs) {
                        $flag=1;
                        break 1;
                      }
                    }
				}else{
                    if($objReportId == ''){
                      $flag=1;
                      break 1;
                    }else{
                      if($location_id == ''){
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' order by submission_date desc");
                      }else{
                        $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and location_id='".$location_id."' order by submission_date desc");
                      }
                      $num_rows = mysql_num_rows($objRs);
					  if($num_rows == 0){
                      //if(!$objRs){  /*$objRs == 0*/
                        $flag=1;
                        break 1;
                      }
                    }
                  }

                   $reportKeys = FALSE;
                   $lastReport = 0;
                   $report_data = array();

                   $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReportId));
                   $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                   
                   while ($objRow = $objRs->fetch_object())
                   {
                     $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                     
                     if ($reportKeys == FALSE)
                     {
                         //$headings = array_keys($form_data);
						 $headings = array();
						 $headings[] = 'Location'; 
						 $headings[] = 'Property';
                         //$headings = array_keys($form_data);
    					$rs = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$objReportId."'");
    					$fbody =  json_decode($rs->form_body);
						$x = 0;
						$y = 0;
						foreach($fbody as $fb)
						{  
							if($fb->field_type != 'fieldset')
							{
								if($fb->label == ''){
								} 
								else 
								{
									if($reportName == 'Subcontractors Equipment Usage') 
									{
										$fb->label = $fbody[$x]->label."-".$fb->label;
									}
									$headings[] = $fb->label;
								} 
								$y++; 
							
							} 
							else 
							{ 
								if($y>0) { $x = $x+3;} 
							} 
						
						}
						$headings[] = 'Submission Date'; 
						$headings[] = 'Submitted By';
						$headings[] = 'Assigned To';
                     }
                     $reportKeys = TRUE; 
                     $report_data[] =  $form_data;
                   }
                   $this->objFunction->write2excel($headings, $report_data, $fileName);   
                   $export_reports[] = $fileName;
               }   
               if($flag == 1){
                  $export_file = '';
                 /* echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";*/
               }
               else{
                  fileZip($export_reports, $export_file);
				  $status = 1;
               }
           }
           
           if ($exportReport == 'individual_reports')
           {
               $duration = $_POST['duration_individual_report'];
               $objReportId = $_POST['report_individual_report'];
               $flag = 0;
               if($duration != '' && $duration != 'i-rep-date'){
                  if($objReportId != ''){
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }else{
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }
               }elseif($_POST['idate_from'] != '' && $_POST['idate_to'] != ''){
                  if($objReportId != ''){
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and UNIX_TIMESTAMP(submission_date) >= UNIX_TIMESTAMP('".$_POST['idate_from']."') and UNIX_TIMESTAMP(submission_date) <= UNIX_TIMESTAMP('".$_POST['idate_to']."') order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }else{
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where UNIX_TIMESTAMP(submission_date) >= UNIX_TIMESTAMP('".$_POST['idate_from']."') and UNIX_TIMESTAMP(submission_date) <= UNIX_TIMESTAMP('".$_POST['idate_to']."') order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }
               }
			   else{
                  if($objReportId != ''){
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }else{
                    $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." order by submission_date desc");
                    $num_rows = mysqli_num_rows($objRs);
                    if($num_rows == 0){
                      $flag=1;
                    }
                  }
               }
                   $reportKeys = FALSE;
                   $lastReport = 0;
                   $report_data = array();

                   $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReportId));
                   $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                   
                   while ($objRow = $objRs->fetch_object())
                   {
                     $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                     
                     if ($reportKeys == FALSE)
                     {
						 $headings = array();
						 $headings[] = 'Location'; 
						 $headings[] = 'Property';
                         //$headings = array_keys($form_data);
    					$rs = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$objReportId."'");
    					$fbody =  json_decode($rs->form_body);
						$x = 0;
						$y = 0;
						foreach($fbody as $fb)
						{  
							if($fb->field_type != 'fieldset')
							{
								if($fb->label == ''){
								} 
								else 
								{
									if($reportName == 'Subcontractors Equipment Usage') 
									{
										$fb->label = $fbody[$x]->label."-".$fb->label;
									}
									$headings[] = $fb->label;
								} 
								$y++; 
							
							} 
							else 
							{ 
								if($y>0) { $x = $x+3;} 
							} 
						
						}
						$headings[] = 'Submission Date'; 
						$headings[] = 'Submitted By';
						$headings[] = 'Assigned To';
                     }
                     $reportKeys = TRUE; 
                     $report_data[] =  $form_data;
                   }
                   $this->objFunction->write2excel($headings, $report_data, $fileName);   
                   $export_reports[] = $fileName;
                   if($flag == 1) {
                      $export_file = '';
                      /*echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";*/
                   }
                   else{
                      fileZip($export_reports, $export_file);
					  $status = 1;
                   }
           }
          if ($exportReport == 'manager_reports')
           {
               $duration = $_POST['duration_manager_report'];
               $assigned_to_id = $_POST['staff_manager_id'];

               $objRsReport = $this->objDatabase->fetchRows("SELECT enabled_reports from ".TBL_JOBLOCATION." where assigned_to='".$assigned_to_id."'");
               $reportsArray = explode(",", $objRsReport->enabled_reports);
               $flag = 0;
               foreach ($reportsArray  as $objReportId)
               {
                  if($duration != '' && $duration != 'm-rep-date'){
                   if($objReportId == ''){
                      $flag=1;
                      break 1;
                   }else{
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and submission_date between date_sub(now(),INTERVAL " . $duration ." day) and now() order by submission_date desc");
                      $num_rows = mysqli_num_rows($objRs);
                      if($num_rows == 0){
                        $flag=1;
                        break 1;
                      }
                   }
                  }elseif($_POST['mdate_from'] != '' && $_POST['mdate_to'] != '')
				  {
					  
                   if($objReportId == ''){
                      $flag=1;
                      break 1;
                   }else{
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' and UNIX_TIMESTAMP(submission_date) >= UNIX_TIMESTAMP('".$_POST['mdate_from']."') and UNIX_TIMESTAMP(submission_date) <= UNIX_TIMESTAMP('".$_POST['mdate_to']."') order by submission_date desc");
                      $num_rows = mysqli_num_rows($objRs);
                      if($num_rows == 0){
                        $flag=1;
                        break 1;
                      }
                   }
                  
				  }else{
                    if($objReportId == ''){
                      $flag=1;
                      break 1;
                    }else{
                      $objRs = $this->objDatabase->dbQuery("SELECT * FROM ".TBL_REPORTS_SUBMISSION." where report_id = '".$objReportId."' order by submission_date desc");
                      if($num_rows == 0){
                        $flag=1;
                        break 1;
                      }
                    }
                  }
                   $reportKeys = FALSE;
                   $lastReport = 0;
                   $report_data = array();

                   $reportName = $this->objFunction->iFind(TBL_REPORTS, 'report_name', array('report_id'=>$objReportId));
                   $fileName = $this->objFunction->removeUnsed($reportName).'-'.date('Y-m-d').'.csv';
                   
                   while ($objRow = $objRs->fetch_object())
                   {
                     $form_data = $this->objFunction->getFormSubmissionValues($objRow->id);
                     
                     if ($reportKeys == FALSE)
                     {
                         //$headings = array_keys($form_data);
						 $headings = array();
						 $headings[] = 'Location'; 
						 $headings[] = 'Property';
                         //$headings = array_keys($form_data);
    					$rs = $this->objDatabase->fetchRows("select * from ".TBL_REPORTS." where status='1' and report_id='".$objReportId."'");
    					$fbody =  json_decode($rs->form_body);
						$x = 0;
						$y = 0;
						foreach($fbody as $fb)
						{  
							if($fb->field_type != 'fieldset')
							{
								if($fb->label == ''){
								} 
								else 
								{
									if($reportName == 'Subcontractors Equipment Usage') 
									{
										$fb->label = $fbody[$x]->label."-".$fb->label;
									}
									$headings[] = $fb->label;
								} 
								$y++; 
							
							} 
							else 
							{ 
								if($y>0) { $x = $x+3;} 
							} 
						
						}
						$headings[] = 'Submission Date'; 
						$headings[] = 'Submitted By';
						$headings[] = 'Assigned To';
                     }
                     $reportKeys = TRUE; 
                     $report_data[] =  $form_data;
                   }
                   $this->objFunction->write2excel($headings, $report_data, $fileName);   
                   $export_reports[] = $fileName;
               }  
               if($flag == 1){
                  $export_file = '';
                 /* echo "<script type=\"text/javascript\">window.alert('There are no reports matching your criteria');</script>";*/
               }else{
                  fileZip($export_reports, $export_file);
				  $status = 1;
               }
           }  
       }
	   if($status == 1){
	  		echo $export_file; die; 
	   }
	   else
	   {
		   echo 0; die;
	   }
	   /*if($status == 1)
	   		$this->objFunction->showMessage('Zip file created successfully.', ISP :: AdminUrl('reports/reportsmanager'));
	   else
			$this->objFunction->showMessage('There are no reports to export.', ISP :: AdminUrl('reports/reportsmanager'));*/
 }
 } 
    public function removeZip() {
        $file = $_GET['file'];
        unlink('upload/zip/'.$file);
        //$this->objFunction->showMessage('Zip file removed successfully.', ISP :: AdminUrl('reports/export-reports'));
		$this->objFunction->showMessage('Zip file removed successfully.', ISP :: AdminUrl('reports/reportsmanager'));
        
    }
	
	public function export_ivr_log_report()
	{
		$array_log = $this->objFunction->getStaffIvrLog();
		$filename = 'export_ivr_log_staff'.'.csv';
		ob_start();
		if (count($array_log) > 0) {
			$strLines = 'Name, Date, Time, Status' . PHP_EOL;
				foreach($array_log as $log) {
					$strLines.= $log['staff_full_name'] . ', ' .strftime('%b %d %Y', $log['time_stamp']) . ', ' . $log['time_12_hour_clock'] . ', ' . ucfirst($log['clock_action_description']) . PHP_EOL;
				}
			echo $strLines;
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '";');
			die();
		}
	}
	
	public function sessionzip()
	{
		/*export property reports*/
		require_once 'PHPExcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
					->setCreator("user")
					->setLastModifiedBy("user")
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");
		chdir($_SERVER['DOCUMENT_ROOT'].'/upload');
		foreach(range('A','D') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		$strsql = "SELECT id FROM ".TBL_JOBLOCATION;
		$objRs = $this->objDatabase->dbQuery($strsql);
		if($objRs)
		{
			$row=1;
			while($obRow = $objRs->fetch_object())
			{
				if($row != 1){$row = $row+2;}
				$jobdata = $this->report_details($obRow->id);
				
				// Set the active Excel worksheet to sheet 0
				$objPHPExcel->setActiveSheetIndex(0); 
				// Initialise the Excel row number
				$rowCount = 0; 
				$propname = $this->propname($obRow->id);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $propname->job_listing);
				
				$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
				$objPHPExcel->getActiveSheet()
				->getStyle('A'.$row.':E'.$row)
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
				
				$row = $row+2;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Staff #');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Started By');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Job Start (or unpause)');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Pause');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'Job End');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Completed By');
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true)->setSize(12);
				// Build cells
				$row++;
				$count =1;
				while( $objRow = $jobdata->fetch_object() )
				{ 
					$col=0;
					$startdate = date('Y-m-d h:i:s a', strtotime($objRow->starting_date));
					if($objRow->pausing_date != '0000-00-00 00:00:00')
						$pausedate = date('Y-m-d h:i:s a', strtotime($objRow->pausing_date));
					else
						$pausedate = '00:00 Null';
					if($objRow->closing_date != '0000-00-00 00:00:00')
						$enddate = date('Y-m-d h:i:s a', strtotime($objRow->closing_date));
					else
						$enddate = '00:00 Null';
					$closedate = strtotime($objRow->closing_date);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $count);$col++;
					$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->started_by));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$col++; 
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $startdate);$col++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $pausedate);$col++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $enddate);$col++;
					$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->closed_by));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowD[0]->f_name.' '.$rowD[0]->l_name);$row++; $count++;
					 
				}
				
				$staffuploadsdata = $this->staffuploads($obRow->id);
				$row = $row + 3	;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'StaffName');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Date');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Images');
				
				$objPHPExcel->getActiveSheet()
				->getStyle('B'.$row)
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()
				->getStyle('C'.$row)
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()
				->getStyle('D'.$row)
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
				
				$row++;
				$supload = array();
				while($objRow = $staffuploadsdata->fetch_object())  // Fetch the result in the object array
				{	
					$supload[] = get_object_vars($objRow);	
				}
				foreach($supload as $objRow1)
				{
					$col = 1;
					foreach($objRow1 as $key=>$value) {
						if($key == 'images')
						{
							$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(95);
							$col = $row;
							if (strpos($value, ',') !== false)
							{
								$imgcell = 'D';
								$img = explode(',', $value);
								foreach($img as $pimg)
								{
									$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setName('Customer Signature');
									$objDrawing->setDescription('Customer Signature');
									//Path to signature .jpg file
									$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$pimg;     
									$objDrawing->setPath($signature);
									$objDrawing->setOffsetX(25);                     //setOffsetX works properly
									$objDrawing->setOffsetY(10);                     //setOffsetY works properly
									$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
									$objDrawing->setWidth(100);  
									$objDrawing->setHeight(90);                     //signature height  
									$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
									++$imgcell;
								}
							}
							else
							{
								$imgcell = 'D';
								$objPHPExcel->getActiveSheet()->getColumnDimension($imgcell)->setWidth(30);
								$image = $value;
								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('Customer Signature');
								$objDrawing->setDescription('Customer Signature');
								//Path to signature .jpg file
								$signature = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$image;     
								$objDrawing->setPath($signature);
								$objDrawing->setOffsetX(25);                     //setOffsetX works properly
								$objDrawing->setOffsetY(10);                     //setOffsetY works properly
								$objDrawing->setCoordinates($imgcell.$col);             //set image to cell 
								$objDrawing->setWidth(100);  
								$objDrawing->setHeight(90);                     //signature height  
								$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
							}
							
						}
						elseif($key == 'staff_id')
						{
							$staffname = $this->staffname($value);
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $staffname->f_name.' '.$staffname->l_name);
						}
						else
						{
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
						}
						$col++;
					}
					$row++;
				}
			}
		}
		
		$property_report = "export_property_report.xlsx";
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$property_report.'"');
		//header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_clean();
		$objWriter->save(str_replace(__FILE__,"export_property_report.xlsx",__FILE__));	
		/*export images*/
		$strsql = "select group_concat(user_gallery) as staffuploads from ".TBL_JOBLOCATION." where user_gallery != ''";
		$objRow = $this->objDatabase->fetchRows($strsql);
		//$strsql = "SELECT Images FROM ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES;
		//$objRs = $this->objDatabase->dbQuery($strsql);
		$filesarr = array();
		$pimg = explode(',', $objRow->staffuploads);
		//while($objRow = $objRs->fetch_object())
		{
			//$pimg = explode(',', $objRow->Images);
			foreach($pimg as $img){
				if($img != '')
					$filesarr[] = $img;
			}
		}
		/*export custom report*/
		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
					->setCreator("user")
					->setLastModifiedBy("user")
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");
		foreach(range('A','I') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		$strsql = "SELECT r.*,rs.* FROM " . TBL_REPORTS . " r inner join " . TBL_REPORTS_SUBMISSION . " rs on r.report_id=rs.report_id where r.site_id='" . $_SESSION['site_id'] . "' order by r.report_id";
		$objRs = $this->objDatabase->dbQuery($strsql);
		if($objRs)
		{
			$row=1;
			while($objRow = $objRs->fetch_object())
			{ 
				$col=0;
				//$objPHPExcel->setActiveSheetIndex(0);
				$fbody = json_decode($objRow->form_body); 
				$report_name = $objRow->report_name;
				if($oldreportname != $report_name){
					$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $report_name);
					
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
					$objPHPExcel->getActiveSheet()
					->getStyle('A'.$row.':C'.$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(16);
					$row = $row+2;
					
					if($report_name != 'Equip Problems to Report') { 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Location');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Property');
						$i=2;
					}
					else {
						$i=0;
					}
					$x = 0;
					$y = 0;
					foreach($fbody as $fb)
					{  
						if($fb->field_type != 'fieldset')
						{
							if($fb->label == ''){
							} 
							else 
							{
								if($objRow->report_name == 'Subcontractors Equipment Usage') 
								{
									$fb->label = $fbody[$x]->label."-".$fb->label;
								}
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $fb->label);
								$i++;
							} 
							$y++; 
						
						} 
						else 
						{ 
							if($y>0) { $x = $x+3;} 
						} 
					
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Name');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $row, 'Date');
					/*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Location');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Property');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Emp. No');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Salt used');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'Calcium used');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'No. of guys');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, 'Total man hours');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, 'Name');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, 'Date');
					
					$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getFont()->setBold(true)->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getFont()->setBold(true)->setSize(12);*/
					$row++;
				}
				$locname = $this->objFunction->iFindAll(TBL_SERVICE, array('id'=>$objRow->location_id));
				$propname = $this->objFunction->iFindAll(TBL_JOBLOCATION, array('id'=>$objRow->property_id));
				$uname = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->submitted_by));
				$date = $objRow->submission_date;
				//$fdata = json_decode($objRow->form_values);	 
				$fdata = json_decode($objRow->form_values, true);
				$fields = array('db_location','db_property','rid','user_id','timestamp');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $locname[0]->name);$col++;					
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $propname[0]->job_listing);$col++; 
				
				foreach($fdata as $key=>$val)
				{
					if( ($key != 'form_token') && ($key != 'send') ){
						if( in_array($key, $fields) === false){  
							 
							if($objRow->report_name == 'Subcontractors Equipment Usage' )
							{
								
								if($val == '') {
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
									$col++;
								} 
								else { 
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
									$col++;
								}
							}
							elseif(is_array($val)) 
							{
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, implode(',', $val));		 	$col++;  
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);		 	
								$col++;  
							}
						}
					} 
				}
				/*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $fdata->db_field_2);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $fdata->db_field_3);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $fdata->db_field_4);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $fdata->db_field_5);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $fdata->db_field_6);$col++;*/
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $uname[0]->f_name . ' ' .$uname[0]->l_name);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, date('Y-m-d h:i A', strtotime($date)));
				$row++;
				$oldreportname = $report_name;
				
			}
		}
		$custom_reports = 'export_custom_reports.xlsx';
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$custom_reports.'"');
		//header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(str_replace(__FILE__,"export_custom_reports.xlsx",__FILE__));
		/*export important notes*/
		$strsql = "SELECT * FROM ".TBL_PROPERTY_NOTES;
		$objRs = $this->objDatabase->dbQuery($strsql);
		$important_notes = 'export_important_notes.csv';
		$fd = fopen ($important_notes, "w");
		$strLines = 'StaffId, Note, Date' . PHP_EOL;
		while($objRow = $objRs->fetch_object())
		{
			$rowD = $this->objFunction->iFindAll(TBL_STAFF, array('id'=>$objRow->staff_id_or_admin));
			$strLines.= $rowD[0]->f_name. ' ' .$rowD[0]->l_name . ', ' .$objRow->notes. ', '.$objRow->date_added . PHP_EOL;
		}
		fputs($fd, $strLines);
		fclose($fd);
		/*export Driver reports*/
		$strsql = "SELECT s.id,s.username,s.f_name,s.l_name, js.starting_date as s_date, js.closing_date as c_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
		$objRs = $this->objDatabase->dbQuery($strsql);
		$driver_report = 'export_driver_report.csv';
		$fd = fopen ($driver_report, "w");
		$strLines = 'Username/Staff ID, First Name, Last Name, Start Date, Close Date, Hours Worked' . PHP_EOL;
		while($objRow = $objRs->fetch_object())
		{
			$diff1 = strtotime($objRow->c_date) - strtotime($objRow->s_date);
			$work_hour = round($diff1/3600, 2);
			if($work_hour < 0) { $work_hour = 'N/A';}
			$strLines.= $objRow->username . ', ' .$objRow->f_name. ', '.$objRow->l_name. ', '.$objRow->s_date . ', '.$objRow->c_date. ', '.$work_hour . PHP_EOL;
		}
		fputs($fd, $strLines);
		fclose($fd);
		/*export ivr log*/
		$array_log = $this->objFunction->getStaffIvrLog();
		$ivr_log_staff = 'export_ivr_log_staff.csv';
		ob_start();
		$fd = fopen ($ivr_log_staff, "w");
		if (count($array_log) > 0) {
			$strLines = 'Name, Date, Time, Status' . PHP_EOL;
				foreach($array_log as $log) {
					$strLines.= $log['staff_full_name'] . ', ' .strftime('%b %d %Y', $log['time_stamp']) . ', ' . $log['time_12_hour_clock'] . ', ' . ucfirst($log['clock_action_description']) . PHP_EOL;
				}
			fputs($fd, $strLines);
			fclose($fd);
		}
		$filesarr[] = $ivr_log_staff; $filesarr[] = $important_notes;
		$filesarr[] = $property_report; $filesarr[] = $custom_reports;
		$filesarr[] =$driver_report;
		$files = $filesarr;
		$valid_files = array();
		if(is_array($files)) {
			foreach($files as $file) {
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		if(count($valid_files > 0)){
			$zip = new ZipArchive();
			//$zip_name = "session.zip";
             $zip_name = 'session_'.date('Y-m-d H:i:s').'.zip';
			if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){
			$error .= "* Sorry ZIP creation failed at this time";
			}
			
			foreach($valid_files as $file){
			$zip->addFile($file);
			}
			
			$zip->close();
			//$fl_name = 'session_'.date('Y-m-d H:i:s').'.zip';
			copy($zip_name, $_SERVER['DOCUMENT_ROOT'].'/sessionzip/'.$zip_name);
			$this->objDatabase->insertQuery("insert into ".TBL_SESSION_RESET." (filename, creation_date) values('".$zip_name."', '".date('Y-m-d H:i:s')."')");
			unlink($zip_name);
			//
			foreach($pimg as $img){
				unlink($_SERVER['DOCUMENT_ROOT'].'/upload/'.$img);
			}
			$this->objDatabase->dbQuery("Update ".TBL_JOBLOCATION." set user_gallery = '', importent_notes = '', progress='0', start_date='0000-00-00 00:00:00', pause_date='0000-00-00 00:00:00', completion_date='0000-00-00 00:00:00'");
			$this->objDatabase->dbQuery("Truncate ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES);
			$this->objDatabase->dbQuery("Truncate ".TBL_REPORTS_SUBMISSION);
			$this->objDatabase->dbQuery("Truncate ".TBL_PROPERTY_NOTES);
			$this->objDatabase->dbQuery("Truncate ".TBL_JOBSTATUS);
			//
			
			$this->objFunction->showMessage('Zip file created successfully.', ISP :: AdminUrl('reports/reportsmanager/'));
			/*if(file_exists($zip_name)){
				header('Content-type: application/zip');
				header('Content-Disposition: attachment; filename="'.$zip_name.'"');
				readfile($zip_name);
				unlink($zip_name);
			}*/
			
		
		} 
		else {
			echo "No valid files to zip";
			exit;
		}
		die();
	}
	public function removesessionZip() {
        $file = $_GET['file'];
        unlink('sessionzip/'.$file);
		$this->objDatabase->dbQuery("DELETE FROM ".TBL_SESSION_RESET." where filename = '".$file."'");
        $this->objFunction->showMessage('Zip file removed successfully.', ISP :: AdminUrl('reports/reportsmanager/'));   
    }
	
	public function removeBulkSessionzip($sids)
	{
		$ids = explode(',', $sids);
		foreach($ids as $id)
		{
			$filename = $this->objFunction->iFind(TBL_SESSION_RESET, 'filename', array('id'=>$id));
			unlink($_SERVER['DOCUMENT_ROOT'].'/sessionzip/'.$filename);
			$this->objDatabase->dbQuery("DELETE FROM ".TBL_SESSION_RESET." where filename = '".$filename."'");
		}
	}
	
	public function seasonzip()
	{
		$curdir = getcwd();
		chdir($_SERVER['DOCUMENT_ROOT'].'/sessionzip');
		$strsql = "SELECT filename FROM ".TBL_SESSION_RESET;
		$objRs = $this->objDatabase->dbQuery($strsql);
		$filesarr = array();
		while($objRow = $objRs->fetch_object())
		{
				$filesarr[] = $objRow->filename;
		}
		$files = $filesarr;
		$valid_files = array();
		if(is_array($files)) {
			foreach($files as $file) {
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		if(count($valid_files > 0)){
			$zip = new ZipArchive();
			//$zip_name = "season.zip";
			$zip_name = 'season_'.date('Y-m-d H:i:s').'.zip';
			if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){
			$error .= "* Sorry ZIP creation failed at this time";
			}
			
			foreach($valid_files as $file){
				$zip->addFile($file);
			}
			
			$zip->close();
			//$fl_name = 'season_'.date('Y-m-d H:i:s').'.zip';
			copy($zip_name, $_SERVER['DOCUMENT_ROOT'].'/seasonzip/'.$zip_name);
			$this->objDatabase->insertQuery("insert into ".TBL_SEASON_RESET." (filename, creation_date) values('".$zip_name."', '".date('Y-m-d H:i:s')."')");
			/*if(file_exists($zip_name)){
				header('Content-type: application/zip');
				header('Content-Disposition: attachment; filename="'.$zip_name.'"');
				readfile($zip_name);
				unlink($zip_name);
			}*/
			foreach($filesarr as $fa){
				unlink($fa);
				$this->objDatabase->dbQuery("DELETE FROM ".TBL_SESSION_RESET." where filename = '".$fa."'");
			}
			chdir($curdir);
			$this->objFunction->showMessage('Zip file created successfully.', ISP :: AdminUrl('reports/reportsmanager/'));
		}
	}
	
	public function session_season_reset()
	{ 
		$strsql = "SELECT * FROM ".TBL_SESSION_RESET;
		$this->obj = $this->objDatabase->dbQuery($strsql);
		parent::SessionSeasonReset($this->obj);
	}
	
	public function removeseasonZip() {
        $file = $_GET['file'];
        unlink('seasonzip/'.$file);
		$this->objDatabase->dbQuery("DELETE FROM ".TBL_SEASON_RESET." where filename = '".$file."'");
        $this->objFunction->showMessage('Zip file removed successfully.', ISP :: AdminUrl('reports/reportsmanager/'));   
    }
	
	public function season_reset()
	{
		$strsql = "SELECT * FROM ".TBL_SEASON_RESET;
		$this->obj = $this->objDatabase->dbQuery($strsql);
		parent::SeasonReset($this->obj); 
	}
	
	public function propexport()
	{
		echo $_POST['uid']; die;
	}
  	
	public function admin_completedProperties()
	{
		 $strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and progress=2  and site_id='".$_SESSION['site_id']."'";
		$this->objSet = $this->objDatabase->dbQuery($strSql);
		parent :: admin_completedProperties($this->objSet ,'joblocation');
	} 
	public function ugal()
	{
		$strsql = "select group_concat(user_gallery) as staffuploads from ".TBL_JOBLOCATION." where user_gallery != ''";
		$objRow = $this->objDatabase->fetchRows($strsql);
		return $objRow;
	}
	public function jobhistory_property_reports($rid, $pid)
	{
		//echo 'Iindsfds12345'; die;
		$strsql = "SELECT r.*,rs.* FROM " . TBL_REPORTS . " r inner join " . TBL_REPORTS_SUBMISSION . " rs on r.report_id=rs.report_id where r.site_id='" . $_SESSION['site_id'] . "' and rs.report_id ='".$rid."' and rs.property_id = '".$pid."'"; //die;
		$objRs = $this->objDatabase->dbQuery($strsql);
		$rdata = '<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" id="dataTables-example">';
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
			
			$rdata .= '<thead class="cf"><tr> ';
			if($objRow->report_name != 'Equip Problems to Report') {
				 $rdata .= '<th data-class="expand">Location</th>
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
					 $rdata .= '<th data-hide="phone">'.$fb->label.'</th>';
				  } $y++; } else { if($y>0) { $x = $x+3;} } }
				 $rdata .= '<th data-hide="phone">Name</th>
				 <th data-hide="phone">Date</th>
			</tr>
			</thead>
			<tbody>';
			endif;
			$rdata .= '<tr>';
			if($objRow->report_name != 'Equip Problems to Report') {
			 $rdata .= '<td>'.$locname[0]->name.'</td>
			 <td>'.$propname[0]->job_listing.'</td>';
				}
			 foreach($fdata as $key=>$val){ if( ($key != 'form_token') && ($key != 'send') ){
				 if( in_array($key, $fields) === false){
					  $rdata .= '<td>';
					  if($objRow->report_name == 'Subcontractors Equipment Usage' )
					  {
						  if($val == '') {$rdata .= '0';} else { $rdata .= $val;}
					  }
					  elseif(is_array($val)) $rdata .= implode(',', $val);  else $rdata .= $val;
					  $rdata .= '</td>';
				} } }
			 $rdata .= '<td>'.$uname[0]->f_name . ' ' .$uname[0]->l_name.'</td>
			 <td>'.date('Y-m-d h:i A', strtotime($date)).'</td></tr>';
		}
		$rdata .= '</tbody></table>';
		echo $rdata;
		die;	
	}
	
} // End of Class




/**
  @Purpose: Create the object the class "content"
*/
   $objContent = new REPORT($intId, $objMainframe, $objFunctions);

   $arrPageUrl = explode("/", $arrUrl[1]);
   $objContent->url = $arrPageUrl[0];

switch($strTask)
{    
 case 'add-new-form':  
   case 'edit-form':
       $objContent->reportForm();
   break;
   case 'reportsmanager':
      $objContent->reportsmanager();
   break;
   
   case 'listing':
      $objContent->reportListing();
   break;
   
   case 'report_ivr_log':
	  $objContent->report_ivr_log();
	break;
	
	case 'driver_report':
      $objContent->driver_report();
   break;
   
   case 'property_report':
      $objContent->property_report();
   break;
   
   case 'driver_report_log':
      $objContent->driver_report_log();
   break;

   case 'save-form':
      $objContent->saveForm();
   break;
   
   case 'update-form-status':
      $objContent->modifyFormStatus();
   break;
   
   case 'show-form':
      $objContent->showForm();
   break;
   
   case 'form-postings':
     $objContent->listFormPostings();
   break;
   
   case 'flush-data':
      $objContent->flushReportData();
   break;
   
   case 'form-posting-details':
     $objContent->viewFormPostings();
   break;      
   
   case 'export-reports':        
     $objContent->exportReports();    
   break;
   
   case 'export-report':        
     $objContent->exportReport();    
   break;
   
   case 'zipbackup':
        $objContent->zipbackup();
   break;
   
   case 'removezip':
        $objContent->removeZip();
   break;
   
    case 'removesessionzip':
        $objContent->removesessionZip();
   break;
   
   case 'removeseasonzip':
        $objContent->removeseasonZip();
   break;
   
   case 'direct':
    $objContent->direct();
  break;
  case 'piechartuserdetails':
    $objContent->piechartuserdetails($_GET['sid']);
  break;
  case 'job-history':
    $objContent->property_report_jobHistory();
  break;
  
  case 'jobhistory':
    $objContent->property_report_m_jobHistory();
  break;
  
  case 'export_ivr_log_report':
    $objContent->export_ivr_log_report();
  break;
  
  case 'session_season_reset':
    $objContent->session_season_reset();
  break;
  
  case 'season_reset':
    $objContent->season_reset();
  break;
  
  case 'sessionzip':
    $objContent->sessionzip();
  break;
  
  case 'seasonzip':
    $objContent->seasonzip();
  break;
  
  case 'propexport':
    $objContent->propexport();
  break;
  
  case 'completed-properties':
       $objContent->admin_completedProperties();
   break;
   
   case 'reset-property':
    $objContent->resetProperty($intId);
  break;
  
  case 'reset-all-property':
      $objContent->resetProperty(-1);
  break;
  
   case 'remove-bulk-sessionzip':
      $objContent->removeBulkSessionzip($_GET['sszipid']);
  break;
  case 'jobhistory-property-reports':
      $objContent->jobhistory_property_reports($_GET['rid'], $_GET['pid']);
  break;
  
  
}

function fileZip($export_reports, $export_file, $images = array()){
  $zip = new ZipArchive(); 
  $archive_file_name = $export_file;

  if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) { 
      exit("cannot open <$archive_file_name>\n"); 
  } 
  //add each files of $file_name array to archive 
  foreach($export_reports as $files) 
  { 
      $zip->addFile('upload/csv/'.$files, $files); 
  } 
  if (count($images) >0) {
    foreach($images as $image) 
  { 
      $zip->addFile('upload/mobile/'.$image, $image); 
  }  
  }
  $zip->close(); 
  /*
  //then send the headers to foce download the zip file 
  header("Content-type: application/zip"); 
  header("Content-Disposition: attachment; filename=$archive_file_name"); 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
  readfile("$archive_file_name"); 
  exit;
  */
}
?>
