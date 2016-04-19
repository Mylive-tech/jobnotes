<?php

/**

 * @Author: CT

 * @Date: 29-Oct-2009

 * @Class Name: mainframe

 * @Purpose:   set and get the page title, meta keywords and meta description and include files.

*/



class mainframe
{



    private $objDbCon;

    public $outputBuffer;

	private $title;



	private $keywords;



	private $description;



	public  $incRight;



	private $incTop;



	public $incLeft;



	private $incFooter;



  

/**

    *@Purpose: Define the construction of the class and create the instaces of Database class.

*/

   public function __construct()
   {

	  $this->objDbCon = new Database();

	  $this->incRight=''; 

	   $this->incLeft=''; 

	  $this->incTop='';

   } // End Of Function



   

/**

 *@Function Name: setPageTitle

 *@Purpose:   Set the Page Title of the Web Page.

*/

   public function setPageTitle($strTitle='')

   {

     $this->title=$strTitle;

   }// End Of Function



   

/**

 *@Function Name: setMetaKeywords

 *@Purpose:   Set the Meta Keywords of the Web Page.

*/ 

   public function setMetaKeywords($keywords='')

   {

      $this->keywords=$keywords;

   }// End Of Function



   



/**

 *@Function Name: setMetaDescription

 *@Purpose:   Set the Meta Description of the Web Page.

*/  

  public  function setMetaDescription($description='')

   {

     $this->description=$description;

   }// End Of Function



   public function iFind($table, $find, $match)
		{
		   $strSQL ='1=1';
		 
		  if(count($match) > 0):
		     foreach($match as $key => $val)
			 {
			   $strSQL .= " AND ".$key."='".$val."' ";
		     }
		  endif;
 	     // echo "SELECT ".$find." from ".$table." where ".$strSQL;
		 
		 
		 $row = $this->objDbCon->fetchRows("SELECT ".$find." from ".$table." where ".$strSQL);
		   
		   //die;
		   return $row->$find;

		}

   



/**

 *@Function Name: getPageTitle

 *@Purpose:   Show the Page Title of the Web Page.

*/



   public function getPageTitle()

   {

     if($this->title=="") $this->title=META_TITLE;

     echo '<title>'.$this->title.'</title>';

   }// End Of Function











/**

 *@Function Name: getMetaKeywords

 *@Purpose:   Show the Meta Keywords of the Web Page.

*/   



   public function getMetaKeywords()

   {

       if($this->keywords=="") $this->keywords=META_KEYWORDS;

       echo '<meta name="keywords" content="'.$this->keywords.'">';

    }// End Of Function









/**

 *@Function Name: getMetaDescription

 *@Purpose:   Show the Meta Description of the Web Page.

*/   



   public function getMetaDescription()

   {

      if($this->description=="") $this->description=META_DESCRIPTION;

      echo '<meta name="description" content="'.$this->description.'">';

   }// End Of Function



   



   



/**

 *@Function Name: setLeftFile

 *@Purpose:   Assign the Left side include file

*/   



   public function setLeftFile($tmpFile)

   {

     $this->incLeft = $tmpFile;
   

   }   // End Of Function



   



/**

 *@Function Name: setRightFile

 *@Purpose:   Assign the Right side include file

*/   



   public function setRightFile($tmpFile)

   {

     $this->incRight = $tmpFile;

   }// End Of Function



   



/**

 *@Function Name: seTopFile

 *@Purpose:   Assign the Top Header include file

*/   



   public function setTopFile($tmpFile)

   {

     $this->incTop = $tmpFile;

   }   // End Of Function



   



/**

 *@Function Name: setFooterFile

 *@Purpose:   Assign the Footer include file

*/   



   public function setFooterFile($tmpFile)

   {

     $this->incFooter = $tmpFile;

   }  // End Of Function



 



   



/**

   * @Function Name: mosHead

   * @Purpose:   Show the head section in the page. Show the page title, meta keywords and meta description on the page.

*/



   public function mosHead()
   {

      global $database;

		$this->getPageTitle();

		$this->getMetaKeywords();

		$this->getMetaDescription();

        //echo '<base href="'.SITE_URL.'" />';		

   }// End Of Function











/**

    * @Function Name: mosBody

    * @Purpose:   Show the main body section of the page.

	* @Description: This function will show the body the site. This is used to print the main conteent/date in the template.

*/



	public function mosBody()
	{
 		 echo $this->outputBuffer;
	}// End Of Function







/**

 *@Function Name: mosFunction

 *@Purpose:   Include the File

*/   



  function mosFunction($strPosition)

  { 

			switch ($strPosition)

			{

			  case "head":

				  $this->mosHead();

			  break;

		

			 case "left":

				 if($this->incLeft<>'')
				 {
					require_once('includes/'.$this->incLeft);

				 }

				break;

		

			  case "right":

				 if($this->incRight<>'')
				 {
					require_once('includes/'.$this->incRight);

				 }	

			  break;

			  		

			  case "footer":

				 if($this->incFooter<>'')

					require_once('includes/'.$this->incFooter);

			  break;
			  
			    case "bg":

				echo  $this->iFind(TBL_CONFIG,'config_value',array('config_id'=>2,'config_type'=>2));

			  break;

			  		

			  case "top":

				 if($this->incTop<>'')

				   require_once('includes/'.$this->incTop);

			  break;

		   } // End Swtich Case

   

  } // End mosFunction

  

} // End Class 'mainframe'



?>
