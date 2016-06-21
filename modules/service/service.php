<?php
/**
 * @Author: CT
 * @Purpose: Class for Content Module which inherits the HTML view class
*/

class SERVICE extends SERVICE_HTML_CONTENT
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
  
   
    public function admin_service_Form()
   {
      if($this->intId <> ''): // condition i.e page for the edit content
	    	$strSql ='SELECT * FROM '.TBL_SERVICE.' where id=%d';
			$this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql,$this->intId));
			parent :: admin_service_Form($this->objRecord, $_REQUEST['type']);  // show the form with already filled values
	  else:
	       $this->objFunction->filterPosted();
	       $this->objRecord=(object)$_POST; // converion of Posted array to object message
		   parent :: admin_service_Form($this->objRecord, $_REQUEST['type']); // // show the form to Add content
	  endif;	  
   } // End Function  
   
   public function get_admin_service_Details()
   {
      if($this->intId <> ''): // condition i.e page for the edit content
	    	$strSql ='SELECT * FROM '.TBL_SERVICE.' where id=%d';
			$this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql,$this->intId));
			return $this->objRecord;
	  else:
	       $this->objFunction->filterPosted();
	       $this->objRecord=(object)$_POST; // converion of Posted array to object message
		   return $this->objRecord;
	  endif;	  
   } // End Function 
     public function admin_state_Form()
   {
      if($this->intId <> ''): // condition i.e page for the edit content
	    	$strSql ='SELECT * FROM '.TBL_STATE.' where id=%d';
			$this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql,$this->intId));
			parent :: admin_state_Form($this->objRecord, $_REQUEST['type']);  // show the form with already filled values
	  else:
	       $this->objFunction->filterPosted();
	       $this->objRecord=(object)$_POST; // converion of Posted array to object message
		   parent :: admin_state_Form($this->objRecord, $_REQUEST['type']); // // show the form to Add content
	  endif;	  
   } // End Function  
   
  
   

    public function admin_serviceListing()
   {
           $strSql = "SELECT * FROM ".TBL_SERVICE." where 1=1 and site_id='".$_SESSION['site_id']."' order by id asc";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: admin_serviceListing($this->objSet);
        
   } // End Function
   
    public function admin_stateListing()
   {
           $strSql = "SELECT * FROM ".TBL_STATE." where 1=1";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: admin_stateListing($this->objSet ,'state');
        
   } // End Function
   
    /**
      *@Purpose: SAVE content (Add new Content and Update the existing content
	  *@Description: This function will save the content. IF content is new then content will be added otherwise content data will be updated.
	*/
   public function saveContent($tbl)
   {
        $_POST['db_url_slug'] = $this->objFunction->cleanURL($_POST['md_name']);     
     
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
              $redirectUrl = ISP :: AdminUrl('service/add-location/');
           }
           else
           {
              $redirectUrl = ISP :: AdminUrl('service/edit-location/id/'.$this->intId);
           }
     		return '<h3>Record status has been '.$msgAction.' successfully.</h3>';
          //$this->objFunction->showMessage('Record status has been '.$msgAction.' successfully.',$redirectUrl);  //Function to show the message	 	  
   } //end function   
 
   
     
 /**
      *@Purpose: Modify content Status Activate/Deactivate
	  *@Description: This function will update the content status i.e  through this function active content can be deavtivated and deactivate content can be activated.
   */
   public function modifyContent($tbl)
   {
   		
      $this->intId = is_array($_REQUEST['delete'])?implode(',',$_REQUEST['delete']):$_REQUEST['delete']; // Get the posted id of the selected content pages
	 
	  $intStatus = $_REQUEST['status'];
	  
	$strSql	=	'Update '.$tbl.' set status=\''.$intStatus.'\' where id in ('.$this->intId.')';
        
	  $this->objDatabase->dbQuery($strSql);
	
	  $strWord='Modified';
	  
	  if($intStatus=='-1')
	  {
	    $strWord= 'Deleted'; 
		  $this->objDatabase->dbQuery('DELETE FROM '.$tbl.' where id in ('.$this->intId.')');
    }

	  $this->objFunction->showMessage('Record has been '.$strWord.' successfully.',ISP :: AdminUrl('service/manage-locations/'));      //Function to show the message
	 
   } //end function
   
   public function loadStates()
   {
    $objRs =	$this->objDatabase->dbFetch("select s.* FROM ".TBL_STATE." s inner join ".TBL_COUNTRIES." c on c.country_id=s.country_id  where c.country_name='".$_POST['cname']."'");
    if(count($objRs)>0)
    {
      echo '<select class="form-control" name="db_state" id="db_state">';
       foreach($objRs as $objState)
        {   
          echo'<option value="'.$objState->state_name.'">'.$objState->state_name.'</option>';   
        }   
      echo '</select>';
     }
    else
    {
         echo '<input type="text" class="form-control" name="db_state" id="db_state">';
    } 
      die();
   }
   
  
   
  
  
} // End of Class




/**
  @Purpose: Create the object the class "content"
*/
   $objContent = new SERVICE($intId, $objMainframe, $objFunctions);

   $arrPageUrl = explode("/", $arrUrl[1]);
   $objContent->url = $arrPageUrl[0];

switch($strTask)
{
   
   
   case 'edit-location':
   case 'add-location':
       $objContent->admin_service_Form();
   break;
   
    case 'edit-state':
   case 'add-state':
       $objContent->admin_state_Form();
   break;
   
   case 'savecategory':
        $objContent->saveContent(TBL_LOCATION_CATEGORY);
   break;
    case 'savestate':
        $objContent->saveContent(TBL_STATE);
   break;
   
    case 'saveservice':
        $objContent->saveContent(TBL_SERVICE);
   break;
   

 		case 'modifystate':
        $objContent->modifyContent(TBL_STATE);
  		 break;
   
   case 'modifyservice':
        $objContent->modifyContent(TBL_SERVICE);
  		 break;

   case 'state-listing':
        $objContent->admin_stateListing();
   break;
   
   case 'manage-locations':
   $objContent->admin_serviceListing();
   break;
    
	case 'service':
        $objContent->admin_stateListing();
   break;
   
   case 'load-states':
       $objContent->loadStates();
   break;
}
?>