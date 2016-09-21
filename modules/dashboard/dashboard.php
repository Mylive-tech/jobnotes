<?php
/**
 * @Author: CT
 * @Purpose: Class for Content Module which inherits the HTML view class
*/

class dashboard extends DSHBOARD_HTML_CONTENT
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
  
  
   public function show_admin_Dashboard()
  {
       
				parent :: show_Dashboard($this->objRecord);    
  		
  }
  
   public function show_Dashboard()
  {
       if($_SESSION['adminid']>1)
	   {
			$strSql = "SELECT s.*,j.location_id, j.assigned_to, j.map_widget, j.lat, j.lag FROM ".TBL_SERVICE." s inner join ".TBL_JOBLOCATION. " j on(s.id=j.location_id) inner join ".TBL_ASSIGN_PROPERTY." ap on(j.id=ap.property_id) where 1=1 and j.site_id='".$_SESSION['site_id']."' and ap.user_id='".$_SESSION['adminid']."' and j.status=1 and s.status=1 group by s.name";
	   }
	   else
	   {
			$strSql = "SELECT s.*,j.location_id, j.assigned_to, j.map_widget, j.lat, j.lag FROM ".TBL_SERVICE." s inner join ".TBL_JOBLOCATION. " j on(s.id=j.location_id) where 1=1 and j.site_id='".$_SESSION['site_id']."' and j.status=1 and s.status=1 group by s.name";	
	   }
				
				$this->objSet = $this->objDatabase->dbQuery($strSql);
				parent :: show_staff_dashboard($this->objSet);
		
  }

} // End of Class




/**
  @Purpose: Create the object the class "content"
*/
   $objDashboard = new dashboard($intId, $objMainframe, $objFunctions);

   $arrPageUrl = explode("/", $arrUrl[1]);
   $objDashboard->url = $arrPageUrl[0];

switch($strTask)
{
   case 'dashboard':
        $objDashboard->show_Dashboard();
   break;
   
   case 'admin_dashboard':
        $objDashboard->show_admin_Dashboard();
   break;
}
?>