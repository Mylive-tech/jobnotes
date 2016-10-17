<?php
/* **************************************************
@Project        : AdScript
@Author            : CT
@Created        : 22/09/2013
@Edited By        : 
@Doc Role        : Database class, responsible for database connection and database intraction(Fetch/Update).
************************************************** */
class Database
{
    private $host;
    private $user;
    private $pwd;
    public $db;
    private $port;
    public $sql;
    private $res;
    public $objFunctions;
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
        
        $this->host    = DB_HOST;
        $this->user    = DB_USER;
        $this->pwd    = DB_PASS;
        $this->db    = DB_NAME;    
        $this->port    = DB_PORT;    

        $this->objThumb = new Thumbnail();
        $this->con = new mysqli($this->host, $this->user, $this->pwd, $this->db,$this->port    );
    }
    
  private function mpeg2Mp4($video_file, $convertName)
    {
        $ffmpegPath = "/usr/local/bin/ffmpeg";
        $flvtool2Path = "/usr/local/bin/flvtool2";
        
        $videoWebFile = "upload/".$convertName.".webm"; 
        $videoThumbFile = "upload/".$convertName.".jpg"; 
        $videoMp4File = "upload/".$convertName.".mp4"; 
        $videoFlv = "upload/".$convertName.".swf"; 
        
        if(!file_exists($videoMp4File))
        {
          exec("$ffmpegPath -i $video_file -sameq -ar 22050  $videoMp4File");
        } 
        
         exec("$ffmpegPath -i $video_file -an -r 4 -y -s 1024x768 $videoThumbFile");
        
        return $convertName;
    }

    
    /* **************************************************        
    Purpose: Return database connection object.        
    *************************************************** */
    public function sqlCon()
    {
        return $this->con;
    } 
    /* **************************************************        
    Purpose: Responsible for data extraction and return resultset.        
    *************************************************** */
    public function dbQuery($s)
    {
        $this->sql = $s;
        $this->res = $this->con->query($this->sql);
        if(mysqli_connect_errno() > 0) {                
            return false;
        }
        $this->countRec = $this->res->num_rows;
        return $this->res;
    }
    
    
    
    
    
    /* **************************************************        
    Purpose: Responsible for data extraction, store in the array and return array of resultset.        
    *************************************************** */
    public function dbFetch($s)
    {
        $intCount = 0;
        
        
        $this->sql = $s;
        $this->res = $this->con->query($this->sql);
        
        if(mysqli_connect_errno() > 0) {                
            die(mysqli_connect_errno());
        }
        
        while ( $row = $this->res->fetch_object())    
        {
            $this->arrayData[$intCount] = $row;
            $intCount++;
        }
        mysqli_free_result($this->arrayData);
        return $this->arrayData;
    }
    
    public function dbFetchObject($s)
    {
        $intCount = 0;
    
        return $s->fetch_object();
    
    }
    
    
    
    /* **************************************************        
    Purpose: Responsible for data manupulation and return true/false according to function success.        
    *************************************************** */
    public function updateQuery($s)
    {
         $this->sql = $s;

        
        
        $this->con->query($this->sql);
        $this->affectedRows = $this->con->affected_rows;            
                            
        if(mysqli_connect_errno() > 0) {
            return false;
        }
        return $this->affectedRows;
    }
    /* **************************************************        
    Purpose: Responsible for fetch row from latest slecet query.        
    *************************************************** */
    public function fetchRows($s = '')
    {
     
        if($s <> '') 
        {
             if($this->dbQuery($s))
                return $this->res->fetch_object();
    
        }
        
    }
    /* **************************************************        
    Purpose: Responsible for return no of record in a select query.        
    *************************************************** */
    public function countRows()
    {
        return $this->countRec;
    }
    /* **************************************************        
    Purpose: Use for query execution.        
    *************************************************** */
    public function execQuery($s)
    {
        $this->sql = $s;
        $this->con->query($this->sql);
        
        if(mysqli_connect_errno() > 0) {                
            echo (mysqli_connect_errno());
        }
        
        return 2;
        
    }
    /* **************************************************        
    Purpose: Responsible for data insurtion and return insert Id.        
    *************************************************** */
    public function insertQuery($s)
    {
        $this->sql = $s;
        $res = $this->con->query($this->sql);
        $this->affectedRows = $this->con->affected_rows;        
        if($this->affectedRows >0)
          $this->insertId = $this->con->insert_id;
        else
           $this->insertId =0;
        return $this->insertId;
    }
    /* **************************************************        
    Purpose: Return insert Id of last inserted record.        
    *************************************************** */
    public function insertId()
    {
        return $this->insertId;
    }
    
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
         $finfo = $result->fetch_fields();
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
                 if(trim($value)=="" && trim($key) != "md_job_title" && trim($key) != "md_importent_notes")
                   $strMessage= '<li>Required fields can not be left blank</li>';
               }
            
                $strKeyTmp = substr($key, 3);
                $arrKey[] = $strKeyTmp;
                if($strKeyTmp == 'password') 
                {
                    $arrValue[] = "'".md5($value)."'";
                }
                else 
                {
                    $arrValue[] = "'".$this->con->real_escape_string(htmlentities(trim($value)))."'";
                }
            }
        }
        foreach($_FILES as $key=>$val)
        {
            if($val['name']<>"")
            {
               $strDirectory = SITE_UPLOADPATH;
               
               @chmod($strDirectory,0777);
               
               $val['name'] = mktime().$this->removeSpace($val['name']); 
               
               move_uploaded_file($val['tmp_name'],$strDirectory.$val['name']);
               
               if (isset($_POST['optimize']))                     
               {                        
                    $this->objThumb->create_thumbnail($strDirectory.$val['name'], $strDirectory.'mobile/'.$val['name'], 450, 450);                        $this->objThumb->create_thumbnail($strDirectory.$val['name'], $strDirectory.'tablet/'.$val['name'], 800, 800);  
                         
               }    
            }      
            
        
        }    
        
        foreach($arrKey as $val)
        {
           $Keyarr[]="`".$val."`";
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
    }
    
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
         $finfo = $result->fetch_fields();
         foreach ($finfo as $tblCol) 
          {
            $arrTblFields[] = $tblCol->name;
          }

        foreach($_POST as $key => $value)
        {
          if($value<>'')
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
                
                else {
                    $arrQueryValue[] = $strDBKey." = '".$this->con->real_escape_string(trim($value))."'";
                }
            }
          }    
        }
    
        foreach($_FILES as $key=>$val)
        {
 
            if($val['name']<>"")
            {
        
              $strDirectory=$_POST['dir_'.substr($key,3)];
               if($strDirectory=="")
                 $strDirectory = SITE_UPLOADPATH;
               @chmod($strDirectory,0777);
                move_uploaded_file($val['tmp_name'],$strDirectory.$val['name']);
                @chmod(    $strDirectory.$val['name'], 0644);
                if (isset($_POST['optimize']))
                    {
                        $this->objThumb->create_thumbnail($strDirectory.$val['name'], $strDirectory.'mobile/'.$val['name'], 450, 450);                        $this->objThumb->create_thumbnail($strDirectory.$val['name'], $strDirectory.'tablet/'.$val['name'], 800, 800);  
                                           
                    }                                      
                $FileNameArray = pathinfo('upload/'.$val['name']);
                        
              if((stripos($key, "db_")!==false || stripos($key, "md_")!==false) && in_array(substr($key, 3),$arrTblFields) )
                {
                    $arrQueryValue[]='`'.substr($key,3).'`=\''.$val['name'].'\'';
                        
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
    }
    
    
public function updateBannerForm($tmpTableName,$tmpId='', $primaryId='')
{
       if($tmpId=='') $tmpId= $_REQUEST['config_id'];
       if($primaryId=='') $primaryId= $_REQUEST['config_title'];
        
        $arrQueryValue = array();
        $arrTblFields = array();
        $strMessage = '';
        
         $strQuery = "Select * from ".$tmpTableName;
         $result = $this->dbQuery($strQuery);
         $finfo = $result->fetch_fields();
         foreach ($finfo as $tblCol) 
          {
            $arrTblFields[] = $tblCol->name;
          }

        foreach($_POST as $key => $value)
        {
          if($value<>'')
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
                
                else {
                    $arrQueryValue[] = $strDBKey." = '".$this->con->real_escape_string(trim($value))."'";
                }
            }
          }    
        }
    
        foreach($_FILES as $key=>$val)
        {
         
            if($val['name']<>"")
            {
        
              $strDirectory=$_POST['dir_'.substr($key,3)];
               if($strDirectory=="")
                 $strDirectory = SITE_UPLOADPATH;
               @chmod($strDirectory,0777);
      
               $val['name'] = mktime().$this->removeSpace($val['name']);
              
             move_uploaded_file($val['tmp_name'],$strDirectory.$val['name']);
      
       
               @chmod(    $strDirectory.$val['name'], 0644);          
                 
              $arrSize= explode(',', $_POST[substr($key,3).'_thumb_size']);
              
              $FileNameArray = pathinfo('upload/'.$val['name']);
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
                         $arrQueryValue[]='`'.substr($key,3).'`=\''.$val['name'].'\'';
                        
                } 
      }      
        }    
    

        $strQueryValue = implode(', ', $arrQueryValue);
        if( $strMessage=='')
        {
              $strQuery = "UPDATE ".$tmpTableName." SET ".$strQueryValue." WHERE config_id = '".$tmpId."' and config_title = '".$primaryId."'";    
              return $this->updateQuery($strQuery);
        }    
        else
        {
           return $this->showServerValidationMessage( $strMessage);
        }     
    } 
    
    public function setOrder($tmpTableName,$intId)
    {
       $sql= $this->dbQuery("Select id  from $tmpTableName");
       $intMorder= $this->countRows()+1;
       $this->updateQuery("UPDATE $tmpTableName set order=$intMorder where id=".$intId);
       
    }    

    public function __destruct()
    {
        $this->con->close();
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