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
  
  public function reportListing()
  {
    $rs = $this->objDatabase->dbQuery("select * from ".TBL_REPORTS." where 1=1  and site_id='".$_SESSION['site_id']."'");
    parent:: reportListing($rs);
  } 
  
  public function report_ivr_log()
			{
				$strSql = "SELECT s.*,st.label as userRole FROM " . TBL_STAFF . " s inner join " . TBL_STAFFTYPE . " st on s.user_type=st.id where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "'";
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
				if(isset($_GET['s']))
				{
					$strSql = "SELECT s.id,s.username,s.f_name,s.l_name,js.start_date as s_date, js.closing_date as c_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "' and UNIX_TIMESTAMP(js.start_date) >= UNIX_TIMESTAMP('".$_GET['date_from']."') and UNIX_TIMESTAMP(js.closing_date) <= UNIX_TIMESTAMP('".$_GET['date_to']."') "; 
				}
				else
				{
					$ys_date = date('Y-m-d',strtotime("-1 days"));
					$cur_date = date('Y-m-d'); 
					//$strSql = "SELECT s.id,s.username,s.f_name,s.l_name, max(js.start_date),js.start_date, js.closing_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "' and UNIX_TIMESTAMP(DATE(js.start_date)) >= UNIX_TIMESTAMP('".$ys_date."') and UNIX_TIMESTAMP(DATE(js.start_date)) <= UNIX_TIMESTAMP('".$cur_date."') group by js.started_by";
					$strSql = "SELECT s.id,s.username,s.f_name,s.l_name, max(js.start_date) as s_date, max(js.closing_date) as c_date FROM " . TBL_STAFF . " s inner join " . TBL_JOBSTATUS . " js on s.id=js.started_by and s.id=js.closed_by  where s.user_type >1  and s.site_id='" . $_SESSION['site_id'] . "' group by js.started_by";
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
			$strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and site_id='".$_SESSION['site_id']."'";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: admin_property_report($this->objSet ,'joblocation');
	}
	
	public function resetProperty($id)
	{
		if ($id >0) {
			$this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='0', start_date='0000-00-00 00:00:00', completion_date='0000-00-00 00:00:00' where id='".$id."'");
		}
		else {
			$this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='0', start_date='0000-00-00 00:00:00', completion_date='0000-00-00 00:00:00'");
		}
		
		$this->objFunction->showMessage('Record has been updated successfully.',ISP :: AdminUrl('property/completed-properties')); 
	}
	public function property_report_jobHistory()
	{
		$strSql = "SELECT * FROM ".TBL_JOBSTATUS." js inner join ".TBL_JOBLOCATION." jl on js.job_id=jl.id where js.job_id='".$this->intId."' order by js.id desc";
			$this->objSet = $this->objDatabase->dbQuery($strSql);

		$strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and id='".$this->intId."'";
			$this->objSet1 = $this->objDatabase->fetchRows($strSql);
		  
			parent :: admin_property_jobHistory($this->objSet, $this->objSet1);
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
   public function direct($pids)
   { 
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
   if(!empty($pids))
	{
		$row=1;
		foreach($pids as $pid)
		{
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
      			$this->objDatabase->dbQuery("INSERT INTO ".TBL_REPORTS_SUBMISSION." (report_id,location_id, property_id, form_values,submitted_by) values('".$this->intId."','".$_REQUEST['db_location']."', '".$_REQUEST['db_property']."', '".$postedValues."','".$_SESSION['adminid']."')");
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
               }else{
                  fileZip($export_reports, $export_file);
               }
           }  
       } 
 }
    
    parent::exportReports($export_file); 
 } 
    public function removeZip() {
        $file = $_GET['file'];
        unlink('upload/zip/'.$file);
        $this->objFunction->showMessage('Zip file removed successfully.', ISP :: AdminUrl('reports/export-reports'));
        
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
   
   case 'zipbackup':
        $objContent->zipbackup();
   break;
   
   case 'removezip':
        $objContent->removeZip();
   break;
   case 'direct':
    $objContent->direct();
  break;
  case 'piechartuserdetails':
    $objContent->piechartuserdetails($_GET['sid']);
  break;
   case 'reset-property':
    $objContent->resetProperty($intId);
  break;
  case 'job-history':
    $objContent->property_report_jobHistory();
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
