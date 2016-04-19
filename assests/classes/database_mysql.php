<?php

	/* **************************************************

	@Project		: AdScript

	@Author			: CT

	@Created		:  22/09/2013

	@Edited By		: 

	@Doc Role		: Database class, responsible for database connection and database intraction(Fetch/Update).

	************************************************** */

	

	require_once(SITE_ABSPATH.'/library/classes/functions.php');

	require_once(SITE_ABSPATH.'/library/classes/thumbnail-script.php');

	

	class Database

	{

		private $host;

		private $user;

		private $pwd;

		public $db;

		private $port;



		public $sql;

		private $res;

		

		public $con;

		public $countRec;

		public $insertId;

		public $affectedRows;

		public $objFunction;

		public $objThumb;

		

		private $arrayData;



	  public function __construct()

		{						

			if(is_null(DB_HOST)) {

				die('Set the Database server in conf.php please');

			}

			

			if(is_null(DB_USER)) {

			die('Set the Database User Id in conf.php please');

			}



			if(is_null(DB_NAME)) {

			die('Set the Database Name in conf.php please');

			}		

			$this->host	= DB_HOST;

			$this->user	= DB_USER;

			$this->pwd	= DB_PASS;

			$this->db	= DB_NAME;	

			$this->port	= DB_PORT;			

			

			//$this->con = new mysqli($this->host, $this->user, $this->pwd, $this->db,$this->port	);

			mysql_connect($this->host, $this->user, $this->pwd);

			mysql_select_db($this->db);
		}

		



		/* **************************************************		

		Purpose: Return database connection object.		

		*************************************************** */

		public function sqlCon()

		{

			return $this->con;

		} //	End: sqlCon()



		/* **************************************************		

		Purpose: Responsible for data extraction and return resultset.		

		*************************************************** */

		public function dbQuery($s)

		{

			//echo $s;

			$this->sql = $s;

			$this->res = mysql_query($this->sql);

			//if(mysqli_connect_errno() > 0) {				

				//die(mysqli_connect_errno());

			//}

			$this->countRec = mysql_num_rows;

			return $this->res;

		} //	End: dbQuery()

		

		

		

		

		

		/* **************************************************		

		Purpose: Responsible for data extraction, store in the array and return array of resultset.		

		*************************************************** */

		public function dbFetch($s)

		{

			$intCount = 0;

			

			

			$this->sql = $s;

			$this->res = mysql_query($this->sql);

			

			//if(mysqli_connect_errno() > 0) {				

				//die(mysqli_connect_errno());

			//}

			

			while ( $row = $this->res->fetch_object())	

			{



				$this->arrayData[$intCount] = $row;



				$intCount++;



			}



			//mysqli_free_result(mysqli_result $this->arrayData);



			return $this->arrayData;

		} //	End: dbFetch()

		

		public function dbFetchObject($s)

		{

			$intCount = 0;

		

			return $s->fetch_object();

		

		} //	End: dbFetch()

		

		

		



		/* **************************************************		

		Purpose: Responsible for data manupulation and return true/false according to function success.		

		*************************************************** */

		public function updateQuery($s)

		{

			 $this->sql = $s;

			

			mysql_query($this->sql);

			$this->affectedRows = mysql_affected_rows;			

								

			//if(mysqli_connect_errno() > 0) {

				//return false;

			//}

			return $this->affectedRows;

		} //	End: updateQuery()



		/* **************************************************		

		Purpose: Responsible for fetch row from latest slecet query.		

		*************************************************** */

		public function fetchRows($s = '')

		{



			if($s <> '') {

				$this->dbQuery($s);

			}

			return mysql_fetch_object($this->res);

		} //	End: fetchRows()



		/* **************************************************		

		Purpose: Responsible for return no of record in a select query.		

		*************************************************** */

		public function countRows()

		{

			return $this->countRec;

		} //	End: countRows()



		/* **************************************************		

		Purpose: Use for query execution.		

		*************************************************** */

		public function execQuery($s)

		{

			$this->sql = $s;

			mysql_query($this->sql);

			

			/*if(mysqli_connect_errno() > 0) {				

				echo (mysqli_connect_errno());

			}*/

			

			return 2;

			

		} //	End: execQuery()



		/* **************************************************		

		Purpose: Responsible for data insurtion and return insert Id.		

		*************************************************** */

		public function insertQuery($s)

		{

			$this->sql = $s;

			$res = mysql_query($this->sql);

			$this->affectedRows = mysql_affected_rows;		

			if($this->affectedRows >0)

			  $this->insertId = mysql_insert_id;

			else

			   $this->insertId =0;

			return $this->insertId;

		} //	End: insertQuery()



		/* **************************************************		

		Purpose: Return insert Id of last inserted record.		

		*************************************************** */

		public function insertId()

		{

			return $this->insertId;

		} //	End: insertId()



		/* **************************************************		

		Purpose: Insert entire HTML form data in to database  table, only those HTML entiry will be consider which have name start with `db_`.

				 And return insert id.

		*************************************************** */

		public function insertForm($tmpTableName)

		{

			$arrKey = array();

			$arrValue = array();

			$arrTblFields = array();

			$strMessage = '';

			

			 $strQuery = "Select * from ".$tmpTableName;

			 $result = $this->dbQuery($strQuery);

			 $finfo = mysql_fetch_field($result);

			 foreach ($finfo as $tblCol) 

			  {

                $arrTblFields[] = $tblCol->name;

              }



			

			foreach($_POST as $key => $value)

			{

				//echo(substr($key,3)." ".$value);

				if(substr($key,3)=='sellingprice' and $value=='')

							 $value =0;

				if(substr($key,3)=='buyingprice' and $value=='')

							 $value =0;

				if((substr($key, 0, 3) == 'db_' || substr($key, 0, 3) == 'md_') && in_array(substr($key, 3),$arrTblFields) )

				{

				

				   if( substr($key, 0, 3) == 'md_')

				   {

				     if(trim($value)=="")

				       $strMessage= '<li>Required fields can not be left blank</li>';

				   }

				

					$strKeyTmp = substr($key, 3);

					$arrKey[] = $strKeyTmp;

					if($strKeyTmp == 'password') 

					{

						$arrValue[] = "'".$value."'";

					}

					else if($strKeyTmp == 'startdate' && $value[4]!='-')

					{

						$strDate=explode("/",$value);

						$date=$strDate[2]."/".$strDate[0]."/".$strDate[1];

						$arrValue[] = "'".$date."'";

					}

					else if($strKeyTmp == 'enddate' && $value[4]!='-') 

					{

						$strDate=explode("/",$value);

						$date=$strDate[2]."/".$strDate[0]."/".$strDate[1];

						$arrValue[] = "'".$date."'";

					}

					

					else 

					{

						$arrValue[] = "'".mysql_real_escape_string(htmlentities(trim($value)))."'";

					}

				}

			}

			foreach($_FILES as $key=>$val)

			{

 				

				if($val['name']<>"")

 				{

				   $strDirectory=$_POST['dir_'.substr($key,3)];

				   @chmod($strDirectory,0777);

				   $val['name'] = $this->removeSpace($val['name']);

  				   move_uploaded_file($val['tmp_name'],$strDirectory.$val['name']);

				  

				  $arrSize= explode(',', $_POST[substr($key,3).'_thumb_size']);

				 

				if($arrSize[0]<>'')

				 { 

				  	foreach($arrSize as $tmpSize)

				  	{

				    	$thName= str_replace(":","_",$tmpSize);

						$tmpArr=explode(':',$tmpSize);

						$strFileName= $strDirectory.$thName.$val['name'];

				  	} 

				 }

   				   if((stripos($key, "db_")!==false || stripos($key, "md_")!==false)  && in_array(substr($key, 3),$arrTblFields) )

	 				{

	  				         $arrKey[]=substr($key,3);

							 $arrValue[] ="'".$val['name']."'";

		   				

	 			    } 

					

  		        }	  

			}			

			$strKey = implode(', ', $arrKey);

			$strValue = implode(', ', $arrValue);

			

			if( $strMessage=='')

			{

			     $strQuery = "INSERT INTO ".$tmpTableName." (".$strKey.") VALUES (".$strValue.")";

				 return $this->insertQuery($strQuery);

			}	

		    else

			{

			   return $this->showServerValidationMessage( $strMessage);

			  }		

			//die($strQuery);

			//exit;

			

		} //	End: insertForm()

		

		public function removeSpace($text)

		{

		   $code_entities_match = array(' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','/','*','+','~','`','=');

		

		  $code_entities_replace = array('-','-','','','','','','','','','','','','','','','','','','','','','','','');

		

		  $text = str_replace($code_entities_match, $code_entities_replace, $text); 

		  return $text;

		}





	/* **************************************************		

	Purpose: Update entire HTML form data in to database table, only those HTML entiry will be consider which have name start with `db_`.

	Where And return true/false according to function success.

		*************************************************** */

		public function updateForm($tmpTableName,$tmpId='', $primaryId='')

		{

		   if($tmpId=='') $tmpId= $_REQUEST['id'];

		   if($primaryId=='') $primaryId= 'id';

		

			$arrQueryValue = array();

			$arrTblFields = array();

			$strMessage = '';

			

			 $strQuery = "Select * from ".$tmpTableName;

			 $result = $this->dbQuery($strQuery);

			 $finfo = mysql_fetch_field($result);

			 foreach ($finfo as $tblCol) 

			  {

                $arrTblFields[] = $tblCol->name;

              }

	

			foreach($_POST as $key => $value)

			{

			      

				if((substr($key, 0, 3) == 'db_' || substr($key, 0, 3) == 'md_') && in_array(substr($key, 3),$arrTblFields) )

				{

				  if( substr($key, 0, 3) == 'md_')

				   {

				     if(trim($value)=="")

				       $strMessage.= '<li>'.sprintf(PLEASE_ENTER,substr($key, 3))."</li>";

				   }

				   

				   

					$strDBKey =  substr($key, 3);

					if($strDBKey == 'password' ) {

					  if($value<>'')

						$arrQueryValue[] = $strDBKey." = '".$value."'";

					}

					else if($strDBKey == 'startdate' && $value[4]!='-') {

						$strDate=explode("/",$value);

						$date=$strDate[2]."/".$strDate[0]."/".$strDate[1];

						$arrQueryValue[] = $strDBKey." = '".$date."'";

					}

					else if($strDBKey == 'enddate' && $value[4]!='-') {

						$strDate=explode("/",$value);

						$date=$strDate[2]."/".$strDate[0]."/".$strDate[1];

						$arrQueryValue[] = $strDBKey." = '".$date."'";

					}

					

					else {

						$arrQueryValue[] = $strDBKey." = '".mysql_real_escape_string(trim($value))."'";

					}

				}

			}

			foreach($_FILES as $key=>$val)

			{

 				if($val['name']<>"")

 				{

				   $strDirectory=$_POST['dir_'.substr($key,3)];

				   @chmod($strDirectory,0777);

				   $val['name'] = $this->removeSpace($val['name']);

  				   move_uploaded_file($val['tmp_name'],$strDirectory.$val['name']);

				  			  

				   

				  $arrSize= explode(',', $_POST[substr($key,3).'_thumb_size']);

				

			 if($arrSize[0]<>'')

			  { 	  

				  foreach($arrSize as $tmpSize)

				  {

				  

				    $thName= str_replace(":","_",$tmpSize);

					

					$tmpArr=explode(':',$tmpSize);

					

					$strFileName= $strDirectory.$thName.$val['name'];

					
			   }

			  }	   

				  

				   

   				  if((stripos($key, "db_")!==false || stripos($key, "md_")!==false) && in_array(substr($key, 3),$arrTblFields) )

	 				{

	  				         $arrQueryValue[]=substr($key,3)."='".$val['name']."'";

		        	} 

  		        }	  

			}				

			$strQueryValue = implode(', ', $arrQueryValue);

			

			if( $strMessage=='')

			{

		          $strQuery = "UPDATE ".$tmpTableName." SET ".$strQueryValue." WHERE $primaryId = '".$tmpId."'";	

				

				 return $this->updateQuery($strQuery);

			}	

		    else

			{

			   return $this->showServerValidationMessage( $strMessage);

			}		

			

	 

		} //	End: updateForm()	

		

		

		public function setOrder($tmpTableName,$intId)

		{

		   $sql= $this->dbQuery("Select id  from $tmpTableName");

		   $intMorder= $this->countRows()+1;

		   $this->updateQuery("UPDATE $tmpTableName set order=$intMorder where id=".$intId);

		   

		}	

 

		public function __destruct()

		{

			mysql_close();

		}

		

		

	function showServerValidationMessage($strMessage)

	{

	   echo '<div style="padding-left:10px; width:auto; border: 1px solid #cc0000; background-color:#ffffcc; color:#C21834; font-weight: bold; font-family:verdana; font-size:11px; padding-top:10px; padding-bottom:10px;">

	       '.$strMessage.'

	   </div>';

	   

	   return false;

	}

}

?>