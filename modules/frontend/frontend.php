<?php
/**
 * @Author: CT
 * @Purpose: Class for Content Module which inherits the HTML view class
*/

class frontend extends FRONTEND_HTML_CONTENT
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
  
  
  
   public function show_explore()
   {
           $strSql = "SELECT * FROM ".TBL_HUB." where 1=1";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: show_explore($this->objSet ,'frontend');
        
   } // End Function
   
   
   public function show_home()
   {
          parent :: show_home();
        
   } // End Function
     public function show_index()
   {
          $strSql = "SELECT * FROM ".TBL_EPISODE." where 1=1 order by date desc limit 5";
			$this->objSet = $this->objDatabase->dbQuery($strSql);
			parent :: show_index($this->objSet ,'frontend');
        
   } // End Function

} // End of Class




/**
  @Purpose: Create the object the class "content"
*/
   $objContent = new frontend($intId, $objMainframe, $objFunctions);

   $arrPageUrl = explode("/", $arrUrl[1]);
   $objContent->url = $arrPageUrl[0];

switch($strTask)
{
	case 'home':
   			$objContent->show_home();
   			break;
	case 'index':
   			$objContent->show_index();
   			break;
   
   case 'explore':
   			$objContent->show_explore();
   			break;

    case 'episode':
        $objContent->show_episode();
   break;
   
   case 'sprituality':
        $objContent->show_sprituality();
   break;
  

}
?>