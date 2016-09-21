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
        
        $strSql = "SELECT * FROM ".TBL_JOBLOCATION."";
		$objRs = $this->objDatabase->dbQuery($strSql); 
		while ($objRow = $objRs->fetch_object()) {
			if ($objRow->gallery<>'')
				$gallery[] = $objRow->gallery;
			if ($objRow->user_gallery<>'')
			$user_gallery[] = $objRow->user_gallery;
		}
		
		$main_images = implode(",", $gallery);
		$user_images = implode(",", $user_gallery);
        parent :: medialist($main_images, $user_images);
        
    }
    
    public function optimizeImage() {
        
        $strSql = "SELECT * FROM ".TBL_JOBLOCATION."";
		$objRs = $this->objDatabase->dbQuery($strSql); 
		while ($objRow = $objRs->fetch_object()) {
			if ($objRow->gallery<>'')
				$gallery[] = $objRow->gallery;
			if ($objRow->user_gallery<>'')
			$user_gallery[] = $objRow->user_gallery;
		}
		
		$main_images = implode(",", $gallery);
		$user_images = implode(",", $user_gallery);
		
		$main_images = str_replace(",,", ",", $main_images);
        $user_images = str_replace(",,", ",", $user_images);
        $totalPropertyImages = $main_images.",".$user_images;
        $glob = explode(",", $totalPropertyImages);
		
		
        //$glob = glob("upload/{*.jpg,*.gif,*.png,*.PNG,*.JPG,*.JPEG,*.GIF}", GLOB_BRACE);
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
				//$this->objDatabase->dbQuery("UPDATE ".TBL_JOBLOCATION." SET gallery = '' where gallery = 0");
				
					
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
    
    case 'export':
        $objMedia->exportImages();
    break;
}    
?>
