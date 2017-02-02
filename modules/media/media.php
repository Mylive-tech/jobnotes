<?php
class media extends HTML_MEDIA
{
    private $objDatabase;
    private $objFunction;
  
/**
    *@Purpose: Define the construction of the class and create the instaces of Database class, Mainframe class and Functions class
*/
    public function __construct($objFunctions)
    {
        $this->objDatabase 	= new Database();
        $this->objMainFrame = $objMainframe;
        $this->objFunction  = $objFunctions;
        parent :: __construct($this->objFunction);
    }
  
    public function getImages() {
        
        $glob = glob("upload/{*.jpg,*.gif,*.png,*.PNG,*.JPG,*.JPEG,*.GIF}", GLOB_BRACE);
        parent :: medialist($glob);
        
    }
    
    public function optimizeImage() {
        $glob = glob("upload/{*.jpg,*.gif,*.png,*.PNG,*.JPG,*.JPEG,*.GIF}", GLOB_BRACE);
        $index = $_POST['index']-1;
        $next = $index+10;
        if ($next >count($glob)) {
            $next = count($glob);
        }
        for($i=$index; $i< $next; $i++) {
            
            $file = $glob[$i];
            $objThumb = new Thumbnail();
            $filename = basename($file);
        
            $objThumb->create_thumbnail("upload/".$filename, 'upload/mobile/'.$filename, 450, 450); 
            $objThumb->create_thumbnail("upload/".$filename, 'upload/tablet/'.$filename, 800, 800);
        }
        
        die("done");
    }
    
    public function exportImages() {       
        $export = 'upload/zip/export-images-'.date('Y-m-d').'.zip';
        $arrFiles = array();
        
        $glob = glob("upload/tablet/{*.jpg,*.gif,*.png,*.PNG,*.JPG,*.JPEG,*.GIF}", GLOB_BRACE);
        for($i=0; $i< count($glob); $i++) {            
            $file = $glob[$i];
            $filename = basename($file);
            $arrFiles[] = 'upload/tablet/'.$filename;
        }
        $this->downloadFile($export, $arrFiles);
    }
    
    public function downloadImage() {
        
        if (isset($_POST['saveselected'])) {
            $img = array();
            
            foreach($_POST['imgdownload'] as $imgName) {
                $img[] = 'upload/tablet/'.$imgName;
            }
           
            $export = 'upload/zip/export-property-images-'.date('YmdHis').'.zip';
            $this->downloadFile($export, $img);
        }
        elseif(isset($_POST['rmselected'])) {
            foreach($_POST['imgdownload'] as $imgName) {
				$this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." SET gallery = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', gallery, ','), ',".$imgName.",', ',')) WHERE FIND_IN_SET('".$imgName."', gallery)");
				$this->objDatabase->dbQuery("UPDATE ".TBL_STAFF_UPLOADED_PROPERTY_IMAGES." SET Images = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', Images, ','), ',".$imgName.",', ',')) WHERE FIND_IN_SET('".$imgName."', Images)");
                unlink('upload/tablet/'.$imgName);
                unlink('upload/mobile/'.$imgName);
                unlink('upload/'.$imgName);
            }
            $this->objFunction->showMessage('Selected Images removed successfully.', ISP :: AdminUrl('media/list'));  //Function to show the message            
        }
    }
    
    public function downloadFile($export, $files) {
        $zip = new ZipArchive();
        
        if ($zip->open($export, ZIPARCHIVE::CREATE )!==TRUE) { 
            exit("cannot open <$export>\n"); 
        }
        
        for($i=0; $i< count($files); $i++) {            
            $file = $files[$i];
            $filename = basename($file);
            $zip->addFile($file, $filename);
        }
        $zip->close(); 
        @header("Location: ".SITE_URL.$export);
        exit;
/*
        header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=".$export); 
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile($export); 
        exit; */
    }
	//
	public function recursiveRemoveDirectory($dir) {
		$files = glob($dir.'/*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
			unlink($file); // delete file
		}
	 }
	public function delete_directory($dirname) 
	{ 
		echo " <hr> calling with <b>".$dirname ."</b><br>";
		
		//if (is_dir($dirname))
		//{		
		   $dir_handle = opendir($dirname);
		//}
		   
		//if (!$dir_handle)
		//  return false;
		  
		while($file = readdir($dir_handle)) 
		{
		   if ($file != "." && $file != "..") 
		   { 
				if (!is_dir($dirname.'/'.$file)) 
					{ 
						unlink($dirname.'/'.$file);
					}
				else
					{
						$this->delete_directory($dirname.'/'.$file);
					}
		   }
		   
		}
		//closedir($dir_handle);
		if (strpos($dirname, 'propertyimageexport/') == true) 
		{
			rmdir($dirname);
		}
		else {
			//echo " found main dir<br>";
		}
		return true;
	}
	public function downloadPropertyImage() {
		$dir = $_SERVER['DOCUMENT_ROOT'].'/upload/propertyimageexport';
		$this->delete_directory($dir);
        if (isset($_POST['saveselected_z'])) { 
			$dr = 'propimageexport_'.date('ymdhis');
			mkdir('upload/propertyimageexport/'.$dr, 0777);
            foreach($_POST as $key=>$value) {
				if(is_array($value))
				{
					$key = str_replace('/', '',$key);
					mkdir('upload/propertyimageexport/'.$dr.'/'.$key, 0777);
					foreach($value as $imgName) {
						copy('upload/'.$imgName, 'upload/propertyimageexport/'.$dr.'/'.$key.'/'.$imgName);
            		}
					
				}
                
            }
	        $source = 'upload/propertyimageexport/'.$dr.'/';
            $destination = 'upload/propertyimageexport/exportpropertyimages-'.date('Y_m_d_H_i_s').'.zip';
            $this->downloadPropertyFile($source, $destination);
        }
		//
		elseif (isset($_POST['saveselected_p'])) {
			$dr = 'propimageexport_'.date('ymdhis');
			mkdir('upload/propertyimageexport/'.$dr);
			ob_start();
			require_once('tcpdf/tcpdf.php');
			foreach($_POST as $key=>$value) {
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
				if(is_array($value))
				{
						$key = str_replace('/', '',$key); 
						$heading = 'Property Name : '.str_replace('_', ' ', $key);
						$pdf->SetHeaderData('', '', $heading, '', '');
						$pdf->AddPage();
						$pdf->setJPEGQuality(75);
						/* $x = 15;
						$y = 35;
						$w = 30;
						$h = 30; */
						$x = 15;
						$y = 35;
						$w = 50;
						$h = 50;
							$x = 15;
							for ($j = 0; $j < count($value); ++$j) 
							{
								if( ($j != 0) && $j%3 == 0) { $x = 15; $y += 55; }
								$filesplit = explode('.', $value[$j]);
								$fileext = strtoupper($filesplit[1]);
								$pdf->Rect($x, $y, $w, $h, 'F', array(), array(128,255,255));
								$pdf->Image($_SERVER['DOCUMENT_ROOT'].'upload/'.$value[$j], $x, $y, $w, $h, $fileext, SITE_URL.'upload/'.$value[$j], '', false, 300, '', false, false, 0, false, false);
								$x += 60; // new column
							}
							$y += 55; // new row
						$pdf->Output($_SERVER['DOCUMENT_ROOT'].'upload/propertyimageexport/'.$dr.'/'.$key.'.pdf', 'F');
				}
			}
			$source = 'upload/propertyimageexport/'.$dr.'/';
            $destination = 'upload/propertyimageexport/exportpropertyimages-'.date('Y_m_d_H_i_s').'.zip';
			$this->downloadPropertyFile($source, $destination);
			die;
		}
	}
	public function downloadPropertyFile($source, $destination) {
		if (!extension_loaded('zip') || !file_exists($source)) {
			return false;
		}
		
		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}
		
		$source = str_replace('\\', '/', realpath($source));
		
		if (is_dir($source) === true)
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		
			foreach ($files as $file)
			{
				$file = str_replace('\\', '/', $file);
		
				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;
		
				$file = realpath($file);
		
				if (is_dir($file) === true)
				{
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true)
				{
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		
		else if (is_file($source) === true)
		{
			$zip->addFromString(basename($source), file_get_contents($source));
		} 
		@header("Location: ".SITE_URL.$destination);
		unlink($destination);
		return $zip->close();
        exit;
	}
	public function exportPropertyImages() {
		$strSql = "SELECT * FROM ".TBL_JOBLOCATION." order by location_id asc";
		$objRs = $this->objDatabase->dbQuery($strSql); 
		parent :: exportPropertyImages($objRs);   
    }
}

$objMedia = new media($objFunctions);

switch($strTask)
{
    case 'list':
        $objMedia->getImages();
    break;
    
    case 'optimize':
        $objMedia->optimizeImage();
    break;
    
    case 'download-image':
        $objMedia->downloadImage();
    break;
	
	case 'download-property-image':
        $objMedia->downloadPropertyImage();
    break;
    
    case 'export':
        $objMedia->exportImages();
    break;
	
	case 'exportpropertyimages':
        $objMedia->exportPropertyImages();
    break;
	
	case 'makepdf':
        $objMedia->pdfmaker();
    break;
}    
?>
