<?php    
//$url = $_SERVER['REQUEST_URI'];
$url = $objFunctions->getCurrentUrl();
if(VC_SEF==1)
{
  if(strstr($_SERVER['REQUEST_URI'],ADMIN_FOLDER)){
    $arrPageUrl = explode(ADMIN_FOLDER."/", $url);
  }
  else
  {
   $arrPageUrl = explode(SITE_URL,$url,2);
  } 

  $arrUrl = explode("/", $arrPageUrl[1]);
  
  for($i=0; $i<count($arrUrl); )
  {
     if($i==0)
      {
        if(!isset($_REQUEST['dir']))
        {
  	     $_GET['dir'] = $_REQUEST['dir'] = $arrUrl[$i];
  	    } 
         $i++;
  	  }
      elseif($i==1)
      {
       if(!isset($_REQUEST['task']))
        {
  	     $_GET['task'] =  $_REQUEST['task'] = $arrUrl[$i];
  	    } 
       $i++;
  	  }
      else
      {
        $_GET[$arrUrl[$i]] = $_REQUEST[$arrUrl[$i]] = $arrUrl[$i+1];
        $i = $i+2;
      }	
  }
$_SERVER['QUERY_STRING']=http_build_query($_GET);
}

function sefToAbs($tmpLink)
{ 
  if(VC_SEF==1):

 	  $myArr = parse_url($tmpLink);

   	   parse_str($myArr['query'], $arrUrl);

   	   $tmpMArr = $arrUrl;

   		$tmpUrl =$arrUrl['dir'];

 		 foreach($arrUrl as $key=>$val)

  		 {

   		    switch($key)

			 {

     			  case 'dir':

				  case 'task':

				 	 unset($tmpMArr[$key]);

					break;

				  default:

		  					//do nothing

					break;

	 		}

   		}

       if($arrUrl['task']<>'')



       { 



   		 	$tmpUrl.='/'.$arrUrl['task'].'';



   	   }
$tmpQSUrl='';

foreach( $tmpMArr as $key=>$val)



   			$tmpQSUrl.='/'.$key.'/'.$val;
                        //$strSUrl =	($tmpQSUrl<>'')?'?'.substr($tmpQSUrl,1):'';
                        return SITE_URL.$tmpUrl.$tmpQSUrl;



             else:



                          return $tmpLink;



  endif;   



}

$strDir = isset($_POST['dir'])?$_POST['dir']:$_GET['dir'];

$strTask = isset($_POST['task'])?$_POST['task']:$_GET['task'];

$intId = isset($_POST['id'])?$_POST['id']:$_GET['id'];

if($strDir=='' & $strTask=='')
 $strTask='home';
 
?>



