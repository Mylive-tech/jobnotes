<?php
/**
 * @Author: CT
 * @Purpose: Class for Content Module which inherits the HTML view class
*/

class JOBLOCATION extends JOBLOCATION_HTML_CONTENT
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
	function __construct($intId, $objMainframe, $objFunctions)
	{
		$this->intId = $intId;
		$this->objDatabase 	= new Database();
		$this->objMainFrame = $objMainframe;
		$this->objFunction  = $objFunctions;
		
		parent :: __construct($this->objFunction); // Call the construction of the HTML view class
	}
  
  
  
 
  /**
     *@Purpose: Show Admin Content Form to Add/Edit Content
	 *@Input: id will be input for the edit content form
  */
  
   
  public function admin_joblocation_Form()
   {
      if($this->intId <> ''): // condition i.e page for the edit content
        $strSql ='SELECT * FROM '.TBL_JOBLOCATION.' where id=%d';
      $this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql,$this->intId));
      parent :: admin_joblocation_Form($this->objRecord, $_REQUEST['type']);  // show the form with already filled values
    else:
         $this->objFunction->filterPosted();
         $this->objRecord=(object)$_POST; // converion of Posted array to object message
       parent :: admin_joblocation_Form($this->objRecord, $_REQUEST['type']); // // show the form to Add content
    endif;    
   } // End Function 
   
  
   public function loadJobLocations()
   {
       $strSql = "SELECT * FROM ".TBL_JOBLOCATION." where status=1 and location_id='".$this->intId."'";
			 $objRs = $this->objDatabase->dbQuery($strSql); 
       echo '<label>Choose Property</label>
             <select name="db_property" class="form-control inline_button" required>';
       echo '<option value="">--Select Location--</option>';
       while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {
          echo '<option value="'.$objRow->id.'">'.$objRow->job_listing.'</option>';
        }
        echo "</select>";
        die();
   }


  public function admin_joblocationlisting()
   {
      $strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and site_id='".$_SESSION['site_id']."'";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: admin_joblocationlisting($this->objSet ,'joblocation');
        
   } // End Function
   
  public function admin_completedProperties()
  {
     $strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and progress=2  and site_id='".$_SESSION['site_id']."'";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: admin_completedProperties($this->objSet ,'joblocation');
  } 
  
  public function jobHistory()
  {
   $strSql = "SELECT * FROM ".TBL_JOBSTATUS." js inner join ".TBL_JOBLOCATION." jl on js.job_id=jl.id where js.job_id='".$this->intId."' order by js.id desc";
		$this->objSet = $this->objDatabase->dbQuery($strSql);
    
    $strSql = "SELECT * FROM ".TBL_JOBLOCATION." where 1=1 and id='".$this->intId."'";
		$this->objSet1 = $this->objDatabase->fetchRows($strSql);
      
		parent :: admin_jobHistory($this->objSet, $this->objSet1);
  }
   
  public function findphone()
   {
   			$q = intval($_GET['q']);
   			echo $phone=$this->objFunction->iFind(TBL_STAFF,'phone',array('id'=>$q));
			die;
          
        
   } // End Function
   
  
  public function auto_complete()
   {		
   			
			parent :: search_staff();
			die;
        
   } // End Function
   
  
  public function UploadPhotos()
  {
    $fileArray= array();

    if(count($_FILES['gallery'])>0)
    {
            
        foreach ($_FILES['gallery']['name'] as $f => $name) 
         {     
        	    if ($_FILES['gallery']['error'][$f] == 4)
              {
        	        continue; 
        	    }	       
        	    if ($_FILES['gallery']['error'][$f] == 0)
              {	           
        	       if(move_uploaded_file($_FILES["gallery"]["tmp_name"][$f], 'upload/'.$name))
            	    $fileArray[] =$name; 
        	    }
      	 }
         $galleryImages = implode(",", $fileArray);
         $p_res = $this->objDatabase->dbQuery("select * from ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." where staff_id=".$_SESSION['adminid']." and prop_id = ".$_REQUEST['pid']);
		 if($p_res->num_rows != 0)
		 {
			  $this->objDatabase->dbQuery("UPDATE ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." set images = CONCAT(images,',', '".$galleryImages."') where staff_id=".$_SESSION['adminid']." and prop_id = '".$_REQUEST['pid']."'");
		 }
		 else
		 {
			$this->objDatabase->dbQuery("INSERT INTO ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." (staff_id, prop_id, date, images) values('".$_SESSION['adminid']."','".$_REQUEST['pid']."','".date('Y-m-d')."','".$galleryImages."')");
		 }
         $this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set user_gallery = CONCAT(user_gallery,',', '".$galleryImages."') where id='".$_REQUEST['pid']."'");
        $this->objFunction->__doRedirect('index.php?dir=property&task=view&id='.$_REQUEST['pid']);
         die();
     } 
  }
   
    /**
      *@Purpose: SAVE content (Add new Content and Update the existing content
	  *@Description: This function will save the content. IF content is new then content will be added otherwise content data will be updated.
	*/
   public function saveContent($tbl)
   {
    $fileArray = array();
    $fileStaffArray = array();
    $objThumb = new Thumbnail();
    $strDirectory = SITE_UPLOADPATH;
    
    if(count($_POST['ex_gallery'])>0)
    {
       $oldImages =  implode(",", $_POST['ex_gallery']); 
       foreach($_POST['ex_gallery'] as $imgG)
       {
            $fileArray[] = $imgG;            
            $objThumb->create_thumbnail($strDirectory.$imgG, $strDirectory.'mobile/'.$imgG, 450, 450); 
            $objThumb->create_thumbnail($strDirectory.$imgG, $strDirectory.'tablet/'.$imgG, 800, 800);  
         }
     } 
     
     if(count($_POST['ex_staff_gallery'])>0)
     {
       		$oldImages =  implode(",", $_POST['ex_staff_gallery']); 
	        foreach($_POST['ex_staff_gallery'] as $imgG)
	        {
	            $fileStaffArray[] = $imgG;            
	            $objThumb->create_thumbnail($strDirectory.$imgG, $strDirectory.'mobile/'.$imgG, 450, 450); 
	            $objThumb->create_thumbnail($strDirectory.$imgG, $strDirectory.'tablet/'.$imgG, 800, 800);  
	         }
      } 
   
     foreach ($_FILES['gallery']['name'] as $f => $name) 
     {     
    	 if ($_FILES['gallery']['error'][$f] == 4)
          {
    	        continue; // Skip file if any error found
    	  }	       
    	  if ($_FILES['gallery']['error'][$f] == 0)
          {	           
    	     if(move_uploaded_file($_FILES["gallery"]["tmp_name"][$f], 'upload/'.$name))
        	    $fileArray[] =$name; // Number of successfully uploaded file
                
                $objThumb->create_thumbnail($strDirectory.$name, $strDirectory.'mobile/'.$name, 450, 450); 
                $objThumb->create_thumbnail($strDirectory.$name, $strDirectory.'tablet/'.$name, 800, 800);  
  
    	  }
  	 }
        
   $_POST['db_gallery'] = implode(",", $fileArray);
   $_POST['db_user_gallery'] = implode(",", $fileStaffArray);
   
   $_POST['db_enabled_reports'] = implode(",", $_POST['reports']);
                
     if($this->intId==''):  //Check for content posted is "New" or "Existing"	 
	    $this->intId= $this->objDatabase->insertForm($tbl) ;  //Insert new content in table
     
           if($this->intId)
      	 	 {   		    
                $msgAction='added';
      	     }
      	 	 else
      	  	 {
      		       $this->objFunction->filterPosted();
      	     	   $this->objRecord=(object)$_POST;  // converion of Posted array to object message
      		   	   parent :: admin_Form($this->objRecord);
      	  	 }
	 else:
           $this->objDatabase->updateForm($tbl); //Update Exisiting content		  
		       $msgAction='updated';
	 endif;
    
    if($_POST['save_new'])
     {
        $redirectUrl = ISP :: AdminUrl('property/add-property/');
     }
     else
     {
        $redirectUrl = ISP :: AdminUrl('property/edit-property/id/'.$this->intId);
     }
     
     $this->objFunction->showMessage('Record status has been '.$msgAction.' successfully.',$redirectUrl);  //Function to show the message	 	  
   } //end function   
   
  
   
   
     
 /**
      *@Purpose: Modify content Status Activate/Deactivate
	  *@Description: This function will update the content status i.e  through this function active content can be deavtivated and deactivate content can be activated.
   */
   public function modifyContent($tbl)
   {
      $this->intId = is_array($_REQUEST['delete'])?implode(',',$_REQUEST['delete']):$_REQUEST['delete']; // Get the posted id of the selected content pages
	    $intStatus = $_REQUEST['status'];
      if(isset($_REQUEST['priority_status']))
      {
           $strSql = 'Update '.$tbl.' set priority_status=\''.$_REQUEST['priority_status'].'\' where id in ('.$this->intId.')';
           $this->objDatabase->dbQuery($strSql);
           $strWord='Modified';
       }
      else
      {
          if ($intStatus <> 'export') {
  	     $strSql = 'Update '.$tbl.' set status=\''.$intStatus.'\' where id in ('.$this->intId.')';
             $this->objDatabase->dbQuery($strSql);
          }
         else {
             
              $propertyData= $this->objFunction->getPropertyExportData($this->intId);
              $prop_headings = array_keys($propertyData[0]);
              $property_file = 'property_location_'.date('YmdHis').'.csv';
    
              $this->objFunction->write2excel($prop_headings, $propertyData, $property_file); 
               $this->objFunction->showMessage("CSV file created successfully.<br>Please <a href='".SITE_URL."upload/csv/".$property_file."' target='_blank'>click here</a> to download the file.<br><br><a href='".SITE_ADMINURL."index.php?dir=property&task=manage-properties'>Click here</a> to go back to Properties Listing"); 
         }
      }    
	 
	
	  
	  
	  if($intStatus=='-1')
	  {
	    $strWord= 'Deleted'; 
	    $this->objDatabase->dbQuery('DELETE FROM '.$tbl.' where id in ('.$this->intId.')');
          }
         if ($intStatus <> 'export') {
	     $this->objFunction->showMessage('Record has been '.$strWord.' successfully.',ISP :: AdminUrl('property/manage-properties'));  
         }    
	 
   } //end function
   
   public function front_View()
   {
     $row = $this->objDatabase->fetchRows('Select * FROM '.TBL_JOBLOCATION.' where id in ('.$this->intId.')');
     parent :: Front_View($row);
   }
   
   public function loadMapBuilder()
   {
      parent:: loadMapBuilder();
   }
   
   public function loadLocationProperties()
   {
     $row = $this->objDatabase->dbQuery('Select * FROM '.TBL_JOBLOCATION.' where location_id ='.$this->intId.' and status=1 order by priority_status desc');
      
     parent :: loadLocationProperties($row, $this->intId);
   }
   
   public function searchProperties()
   {
     $searchKeyword = trim($_GET['keyword']);
      $row = $this->objDatabase->dbQuery("Select * FROM ".TBL_JOBLOCATION." where 1=1  and site_id='".$_SESSION['site_id']."' and (job_listing like '%".$searchKeyword."%' or location_address like '%".$searchKeyword."%' or importent_notes like '%".$searchKeyword."%') and status=1 order by priority_status desc");
      parent :: loadLocationProperties($row, $this->intId);
   }
   
   public function StartJob()
   {
     if(intval($_GET['s'])==1)
     {
       $this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='".intval($_GET['s'])."', start_date='".date('Y-m-d H:i:s')."' where id='".$this->intId."'");
       $this->objDatabase->dbQuery("INSERT INTO ".TBL_JOBSTATUS." (job_id, started_by, start_date) values('".$this->intId."', '".$_SESSION['adminid']."', '".date('Y-m-d H:i:s')."')");
     }
     elseif(intval($_GET['s'])==2)
     {  
        $this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='".intval($_GET['s'])."', completion_date='".date('Y-m-d H:i:s')."' where id='".$this->intId."'");
         $this->objDatabase->dbQuery("UPDATE ".TBL_JOBSTATUS." set closed_by='".$_SESSION['adminid']."', closing_date='".date('Y-m-d H:i:s')."' where job_id='".$this->intId."' and closed_by='' ");
     }
     $this->objFunction->__doRedirect('index.php?dir=property&task=view&id='.$_REQUEST['id']);
     die();
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
   
   public function reStartJob()
   {
     $this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set progress='1', completion_date='0000-00-00 00:00:00', start_date='".date('Y-m-d H:i:s')."' where id='".$this->intId."'");
     $this->objDatabase->dbQuery("INSERT INTO ".TBL_JOBSTATUS." (job_id, started_by, start_date) values('".$this->intId."', '".$_SESSION['adminid']."', '".date('Y-m-d H:i:s')."')");
     $this->objFunction->__doRedirect('index.php?dir=property&task=view&id='.$this->intId);
   }
   
   
   public function report_details() {
		$imagePath = $_SERVER['DOCUMENT_ROOT'] . 'jobnotes/assets/img/';
		$reportdetails = array(
			array('BrandIcon' => $imagePath . "facebook.png",'Comapany' => "facebook",'Rank' => "2",'Link' => "http://www.facebook.com"),
			array('BrandIcon' => $imagePath . "googleplus.png",'Comapany' => "googleplus",'Rank' => "1",'Link' => "http://www.googleplus.com"),
			array('BrandIcon' => $imagePath . "twitter.png",'Comapany' => "twitter",'Rank' => "3",'Link' => "http://www.twitter.com"),
			array('BrandIcon' => $imagePath . "linkedin.png",'Comapany' => "linkedin",'Rank' => "8",'Link' => "http://www.linkedin.com"),
		);
		return $reportdetails;

	}
   public function direct()
   {
	//echo 'dddd'; 
	//$this->objFunction->my_constants();
	//$this->objFunction->xlscreation_direct();
	$reportdetails = $this->report_details();
	
	//print_r($reportdetails); 
	
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

	// Set the active Excel worksheet to sheet 0
	$objPHPExcel->setActiveSheetIndex(0); 

	// Initialise the Excel row number
	$rowCount = 0; 

	// Sheet cells
	$cell_definition = array(
		'A' => 'BrandIcon',
		'B' => 'Comapany',
		'C' => 'Rank',
		'D' => 'Link'
	);
	

	// Build headers
	foreach( $cell_definition as $column => $value )
	{
		$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
	}

	// Build cells
	while( $rowCount < count($reportdetails) ){ 
		$cell = $rowCount + 2;
		foreach( $cell_definition as $column => $value ) {

			$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 
			
			switch ($value) {
				case 'BrandIcon':
					if (file_exists($reportdetails[$rowCount][$value])) {
				        $objDrawing = new PHPExcel_Worksheet_Drawing();
				        $objDrawing->setName('Customer Signature');
				        $objDrawing->setDescription('Customer Signature');
				        //Path to signature .jpg file
				        $signature = $reportdetails[$rowCount][$value];    
				        $objDrawing->setPath($signature);
				        $objDrawing->setOffsetX(25);                     //setOffsetX works properly
				        $objDrawing->setOffsetY(10);                     //setOffsetY works properly
				        $objDrawing->setCoordinates($column.$cell);             //set image to cell 
				        $objDrawing->setWidth(32);  
				        $objDrawing->setHeight(32);                     //signature height  
				        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
				    } else {
				    	$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 
				    }
				    break;
				case 'Link':
					//set the value of the cell
					$objPHPExcel->getActiveSheet()->SetCellValue($column.$cell, $reportdetails[$rowCount][$value]);
					//change the data type of the cell
					$objPHPExcel->getActiveSheet()->getCell($column.$cell)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING2);
					///now set the link
					$objPHPExcel->getActiveSheet()->getCell($column.$cell)->getHyperlink()->setUrl(strip_tags($reportdetails[$rowCount][$value]));
					break;

				default:
					$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 
					break;
			}
			
		}
	    $rowCount++; 
	}
	$rand = rand(1234, 9898);
	$presentDate = date('YmdHis');
	$fileName = "report_" . $rand . "_" . $presentDate . ".xlsx";

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$fileName.'"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
    die();//die;
   }
   
   public function removePhoto() {
     if ($_REQUEST['action'] =='staffgallery') {
     	$oldImages =  implode(",", $_POST['ex_staff_gallery']); 
        foreach($_POST['ex_staff_gallery'] as $imgG)
        {
            $fileArray[] = $imgG;
        }
        $this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set user_gallery='".implode(',', $fileArray)."' where id='".$_REQUEST['pid']."'");   
     }
     else {
        $oldImages =  implode(",", $_POST['ex_gallery']); 
        foreach($_POST['ex_gallery'] as $imgG)
        {
            $fileArray[] = $imgG;
        }
        $this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." set gallery='".implode(',', $fileArray)."' where id='".$_REQUEST['pid']."'");   
     }  
        
        echo 'Done';
        die();
   }
  
} // End of Class




/**
  @Purpose: Create the object the class "content"
*/
   $objJobLocation = new JOBLOCATION($intId, $objMainframe, $objFunctions);

   $arrPageUrl = explode("/", $arrUrl[1]);
   $objJobLocation->url = $arrPageUrl[0];

switch($strTask)
{
   
   
   case 'edit-property':
   case 'add-property':
       $objJobLocation->admin_joblocation_Form();
   break;
   
   
    case 'savejoblocationlisting':
        $objJobLocation->saveContent(TBL_JOBLOCATION);
   break;
   

 case 'modifyjoblocation':
        $objJobLocation->modifyContent(TBL_JOBLOCATION);
   break;
   
   case 'findphone':
        $objJobLocation->findphone(TBL_STAFF);
   break;
   
   case 'view':
        $objJobLocation->front_View();
   break;

   case 'manage-properties':
        $objJobLocation->admin_joblocationlisting();
   break;
   
   case 'completed-properties':
       $objJobLocation->admin_completedProperties();
   break;
   
   case 'removephoto':
        $objJobLocation->removePhoto();
   break;
   
   case 'autocomplete':
           $objJobLocation->auto_complete();
   break;
   
   case 'ajax-joblocation':
      $objJobLocation->loadJobLocations();
   break;
   
   case 'map-builder':
      $objJobLocation->loadMapBuilder();
   break;

  case 'location-properties':
      $objJobLocation->loadLocationProperties();
  break;
 
  case 'search':
      $objJobLocation->searchProperties();
  break;
  
  case 'uploadphotos':
      $objJobLocation->UploadPhotos();
  break;
  
  case 'start':
      $objJobLocation->StartJob();
  break;
  
  case 'reset-property':
    $objJobLocation->resetProperty($intId);
  break;
  
  case 'reset-all-property':
      $objJobLocation->resetProperty(-1);
  break;
  
  case 'restart-job':
    $objJobLocation->reStartJob();
  break; 
  
  case 'job-history':
    $objJobLocation->jobHistory();
  break;
   case 'direct':
    $objJobLocation->direct();
  break;   	
}
?>