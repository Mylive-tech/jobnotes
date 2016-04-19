<?php
/**
 * @Author: CT
 * @Purpose: Class for Content Module which inherits the HTML view class
*/

class content extends HTML_CONTENT
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
  
   public function show_Page()
  {
        $strSql ='SELECT * FROM '.TBL_PAGES.' where id=%d';
	      $this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql,$this->intId));
	       parent :: show_Page($this->objRecord);    
  
  }
  
  /**
     *@Purpose: Show Admin Content Form to Add/Edit Content
	 *@Input: id will be input for the edit content form
  */
   public function admin_Form()
   {
      if($this->intId <> ''): // condition i.e page for the edit content
	   	$strSql ='SELECT * FROM '.TBL_PAGES.' where id=%d';
			$this->objRecord = $this->objDatabase->fetchRows(sprintf($strSql,$this->intId));
			parent :: admin_Form($this->objRecord, $_REQUEST['type']);  // show the form with already filled values
	  else:
	       $this->objFunction->filterPosted();
	       $this->objRecord=(object)$_POST; // converion of Posted array to object message
		   parent :: admin_Form($this->objRecord, $_REQUEST['type']); // // show the form to Add content
	  endif;	  
   } // End Function  
   
   
   
   /**
     *@Purpose: Show Content Listing to Modify/Delete 
	 *@Description: This function will list all the contents in Grid Format with edit link.
   */
   public function admin_showListing()
   {
           $strSql = "SELECT * FROM ".TBL_PAGES." where 1=1";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: admin_showListing($this->objSet ,'pages');
        
   } // End Function
   
  
    /**
      *@Purpose: SAVE content (Add new Content and Update the existing content
	  *@Description: This function will save the content. IF content is new then content will be added otherwise content data will be updated.
	*/
   public function saveContent($tbl)
   {
   
    
     if($this->intId==''):  //Check for content posted is "New" or "Existing"	 
	    $this->intId= $this->objDatabase->insertForm($tbl) ;  //Insert new content in table
      	if($this->intId)
	 	 {
		    
	   		 $this->objFunction->showMessage('Record has been added successfully.',$_SERVER['REQUEST_URI']);  //Function to show the message
			 
	  	 }
	 	 else
	  	 {
		      $this->objFunction->filterPosted();
	     	  $this->objRecord=(object)$_POST;  // converion of Posted array to object message
		   	  parent :: admin_Form($this->objRecord);
	  	 }
	 else:
	          $this->objDatabase->updateForm($tbl); //Update Exisiting content
		  
		   $this->objFunction->showMessage('Record status has been updated successfully.',$_SERVER['REQUEST_URI']);  //Function to show the message
	 endif;	 	  
   } //end function   
   
  
   
   
     
 /**
      *@Purpose: Modify content Status Activate/Deactivate
	  *@Description: This function will update the content status i.e  through this function active content can be deavtivated and deactivate content can be activated.
   */
   public function modifyContent($tbl)
   {
    $this->intId = is_array($_POST['delete'])?implode(',',$_POST['delete']):$_POST['delete']; // Get the posted id of the selected content pages
	 
	  $intStatus = $_POST['status'];
	  
	   $strSql	=	'Update '.$tbl.' set status=\''.$intStatus.'\' where id in ('.$this->intId.')';
        
	  $this->objDatabase->dbQuery($strSql);
	
	  $strWord='Modified';
	  
	  if($intStatus=='-1')
	  {
	    $strWord= 'Deleted'; 
		$this->objDatabase->dbQuery('DELETE FROM '.$tbl.' where id in ('.$this->intId.')');
      }
     // $this->updatecache(); 
	  $this->objFunction->showMessage('Record has been '.$strWord.' successfully.',$_SERVER['REQUEST_URI']);      //Function to show the message
	 
   } //end function
   
   public function technicalEnquiryForm()
   {
     if(!empty($_POST))
     {
            $to = 'jobnotes@mylive-tech.com';
            $subject = "Technical Support Enquiry";
            $message = "";
            $encoded_content='';
            $mainFile = '';
            # Open a file
            if(isset($_FILE['db_file']['tmp_name']) )
            {
              $mainFile = $_FILE['db_file']['name'];
              move_uploaded_file($_FILE['db_file']['tmp_name'], 'upload/'.$mainFile);
              
              $file = fopen( 'upload/'.$mainFile, "r" );
              $size = filesize('upload/'.$mainFile);
              $content = fread( $file, $size);
              $encoded_content = chunk_split( base64_encode($content));
            }            
            $message ='<table width="600" cellpadding="0" cellspacing="0" style="border:1px solid #4D7496;">
                        <tr>
                          <td colspan="2" align="left" style="padding-left:20px;background-color:#4D7496; border-bottom:3px solid #2A4053;">
                          <img src="'.$this->objFunction->getCompanyLogo().'" alt="logo" style="vertical-align:top; max-height:48px;" />
                          </td>
                         </tr>
                         <tr><td colspan="2" height="30"></td></tr>';
            
            $message .='<tr style="border-bottom:1px solid #d9d9d9;"><td width="30%" style="padding-left:20px;" valign="top"><b>Name:</b></td><td valign="top" style="padding-bottom:10px;">'.$_POST['md_name'].'</td></tr>';
            $message .='<tr style="border-bottom:1px solid #d9d9d9;"><td width="30%" style="padding-left:20px;" valign="top"><b>Company Name:</b></td><td valign="top" style="padding-bottom:10px;">'.$_POST['md_cmp_name'].'</td></tr>';
            $message .='<tr style="border-bottom:1px solid #d9d9d9;"><td width="30%" style="padding-left:20px;" valign="top"><b>Phon Number:</b></td><td valign="top" style="padding-bottom:10px;">'.$_POST['md_phone'].'</td></tr>';
            $message .='<tr style="border-bottom:1px solid #d9d9d9;"><td width="30%" style="padding-left:20px;" valign="top"><b>Email Id:</b></td><td valign="top" style="padding-bottom:10px;">'.$_POST['md_email'].'</td></tr>';
            $message .='<tr style="border-bottom:1px solid #d9d9d9;"><td width="30%" style="padding-left:20px;" valign="top"><b>License Info:</b></td><td valign="top" style="padding-bottom:10px;">'.nl2br($_POST['md_lisc_info']).'</td></tr>';
            $message .='<tr style="border-bottom:1px solid #d9d9d9;"><td width="30%" style="padding-left:20px;" valign="top"><b>Server Status Specs:</b></td><td valign="top" style="padding-bottom:10px;">'.nl2br($_POST['md_server_specs']).'</td></tr>';
            $message .='<tr><td width="30%" style="padding-left:20px;" valign="top"><b>Comments:</b></td><td valign="top" style="padding-bottom:10px;">'.nl2br($_POST['md_comments']).'</td></tr>';
            $message .='</table>';       
            
            # Get a random 32 bit number using time() as seed.
            $num = md5( time() );
            
            # Define the main headers.
            $header = "From: jobnotes@mylive-tech.com\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: multipart/mixed; ";
            $header .= "boundary=$num\r\n";
            $header .= "--$num\r\n";
            
            # Define the message section
            $header .= "Content-Type: text/html\r\n";
            $header .= "Content-Transfer-Encoding:8bit\r\n\n";
            $header .= "$message\r\n";
            $header .= "--$num\r\n";
           if($mainFile<>'')
           { 
            # Define the attachment section
            $header .= "Content-Type:  multipart/mixed; ";
            $header .= "name=\"".$mainFile."\"\r\n";
            $header .= "Content-Transfer-Encoding:base64\r\n";
            $header .= "Content-Disposition:attachment; ";
            $header .= "filename=\"".$mainFile."\"\r\n\n";
            $header .= "$encoded_content\r\n";
            $header .= "--$num--";
            }
            # Send email now
            $retval = mail ( $to, $subject, $message, $header );
            if( $retval == true )
            {
             $this->objFunction->showMessage('Message has been sent successfully.',$_SERVER['REQUEST_URI']);      //Function to show the message
            }
            else
            {
              $this->objFunction->showMessage('Message sending failed!!',$_SERVER['REQUEST_URI']);      //Function to show the message
            }
     }
     else
     {  
       parent::technicalEnquiryForm();
     }  
   }
   
  
  
} // End of Class




/**
  @Purpose: Create the object the class "content"
*/
   $objContent = new content($intId, $objMainframe, $objFunctions);

   $arrPageUrl = explode("/", $arrUrl[1]);
   $objContent->url = $arrPageUrl[0];

switch($strTask)
{
   
   case 'edit':
   case 'add':
       		 $objContent->admin_Form();
   			 break;

    case 'show-page':
        	 $objContent->show_Page();
  			 break;
   
   	case 'save':
       		 $objContent->saveContent(TBL_PAGES);
  			 break;
  
   
   	case 'modify':
        	$objContent->modifyContent(TBL_PAGES);
   			break;
	   case 'listing':
       		 $objContent->admin_showListing();
   		 	 break;
         
     case 'technical-enquiry':
        	$objContent->technicalEnquiryForm();
   			break;    

}
?>