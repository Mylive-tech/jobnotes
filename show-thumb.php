<?php
error_reporting(0);

 //////////////FUNCTION  TO Create THUMB IMAGE///////////////////////////////////

function scale_dimensions($w,$h,$maxw,$maxh,$stretch = true) {
 if($stretch)
 {
   $neww = $maxw;
   $newh = $maxh;
 }
 else
 {
    if (!$maxw && $maxh)
	{
      // Width is unlimited, scale by width
      $newh = $maxh;
      if ($h < $maxh && !$stretch) { $newh = $h; }
      else { $newh = $maxh; }
      $neww = ($w * $newh / $h);
    } elseif (!$maxh && $maxw) {
      // Scale by height
      if ($w < $maxw && !$stretch) { $neww = $w; }
      else { $neww = $maxw; }
      $newh = ($h * $neww / $w);
    } elseif (!$maxw && !$maxh) {
      return array($w,$h);
    } else {
      if ($w / $maxw > $h / $maxh) {
        // Scale by height

        if ($w < $maxw && !$stretch) { $neww = $w; }

        else { $neww = $maxw; }

        $newh = ($h * $neww / $w);

      } elseif ($w / $maxw <= $h / $maxh) {

        // Scale by width

        if ($h < $maxh && !$stretch) { $newh = $h; }

        else { $newh = $maxh; }

        $neww = ($w * $newh / $h);

      }

    }

}	

    return array(round($neww),round($newh));

}



function create_thumbnail($infile,$outfile,$maxw,$maxh, $bolWater=false, $stretch = true) {

  clearstatcache();

  if (!is_file($infile)) {

    trigger_error("Cannot open file: $infile",E_USER_WARNING);

    return FALSE;

  }

  if (is_file($outfile)) {

      trigger_error("Output file already exists: $outfile",E_USER_WARNING);

    return FALSE;

  }

  

  

  $waterImage = "images/watermark.png";



  $functions = array(

    'image/png' => 'ImageCreateFromPng',

    'image/jpeg' => 'ImageCreateFromJpeg',

  );

  

  









  // Add GIF support if GD was compiled with it

  if (function_exists('ImageCreateFromGif')) { $functions['image/gif'] = 'ImageCreateFromGif'; }



  $size = getimagesize($infile);



  // Check if mime type is listed above

  if (!$function = $functions[$size['mime']]) {

      trigger_error("MIME Type unsupported: {$size['mime']}",E_USER_WARNING);

    return FALSE;

  }



  // Open source image

  if (!$source_img = $function($infile)) {

      trigger_error("Unable to open source file: $infile",E_USER_WARNING);

    return FALSE;

  }

  





  $save_function = "image" . strtolower(substr(strrchr($size['mime'],'/'),1));



  // Scale dimensions

  list($neww,$newh) = scale_dimensions($size[0],$size[1],$maxw,$maxh,$stretch);



  if ($size['mime'] == 'image/png')

  {

    // Check if this PNG image is indexed

    $temp_img = imagecreatefrompng($infile);

    if (imagecolorstotal($temp_img) != 0) {

      // This is an indexed PNG

      $indexed_png = TRUE;

    } else {

      $indexed_png = FALSE;

    }

    imagedestroy($temp_img);

  }

  

  // Create new image resource

  if ($size['mime'] == 'image/gif' || ($size['mime'] == 'image/png' && $indexed_png)) {

    // Create indexed 

    $new_img = imagecreate($neww,$newh);

    // Copy the palette

    imagepalettecopy($new_img,$source_img);

    

    $color_transparent = imagecolortransparent($source_img);

    if ($color_transparent >= 0)

	{

      // Copy transparency

      imagefill($new_img,0,0,$color_transparent);

      imagecolortransparent($new_img, $color_transparent);

    }

  } else {

    $new_img = imagecreatetruecolor($neww,$newh);

  }

  

  // Copy and resize image

  imagecopyresampled($new_img,$source_img,0,0,0,0,$neww,$newh,$size[0],$size[1]);

  imagealphablending($new_img, TRUE);



 

  // Save output file

  if ($save_function == 'imagejpeg') {

      // Change the JPEG quality here

      if (!$save_function($new_img)) {

          trigger_error("Unable to save output image",E_USER_WARNING);

          return FALSE;

      }

  } else

   {

      if (!$save_function($new_img)) 

	  {

          trigger_error("Unable to save output image",E_USER_WARNING);

          return FALSE;

      }

  }



  

}

// Scales dimensions





function waterMark($image,$waterImage, $filetype)

{



  $imagesource =  $image; 



	//$filetype = substr($imagesource,strlen($imagesource)-4,4); 



	//$filetype = strtolower($filetype); 



	if($filetype == ".gif")  $image = @imagecreatefromgif($imagesource);  



	if($filetype == ".jpg")  $image = @imagecreatefromjpeg($imagesource);  



	if($filetype == ".png")  $image = @imagecreatefrompng($imagesource);  



	//if (!$image) die("Image does not exist or GD Library support is disabled on the server."); 



	$watermark = @imagecreatefrompng($waterImage); 



	$imagewidth = imagesx($image); 



	$imageheight = imagesy($image);  



	$watermarkwidth =  imagesx($watermark); 



	$watermarkheight =  imagesy($watermark); 



	$startwidth = (($imagewidth - $watermarkwidth)/2); 



	$startheight = (($imageheight - $watermarkheight)/2); 



	        $wmInfo = getimagesize($waterImage); 



				   $waterMarkDestWidth=$wmInfo[0];



				   $waterMarkDestHeight=$wmInfo[1];



	               

				   //$origInfo = getimagesize($orgImage); 



                   $origWidth = $_GET['w']; 



				   $origHeight = $_GET['h']; 



				  $placementX = $origWidth - $waterMarkDestWidth - 10;



				  $placementY = $origHeight - $waterMarkDestHeight - 10;



	imagecopy($image, $watermark,  $placementX, $placementY, 0, 0, $watermarkwidth, $watermarkheight); 



	if($filetype == ".gif")  imagegif($image); 



	if($filetype == ".jpg")  imagejpeg($image);   



	if($filetype == ".png")  imagepng($image); 



}





$srcFile	= $_GET['file'];

$srcWidth 	= $_GET['w'];

$srcHeight 	= $_GET['h'];



$system=explode('.',$srcFile);

$outfile = $system[0].'_tmp'.$system[1];



$waterMark = $_GET['wm'];



create_thumbnail($srcFile,$outfile,$srcWidth,$srcHeight,$waterMark,intval($_GET['s']));

?>