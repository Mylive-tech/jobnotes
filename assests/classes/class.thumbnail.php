<?php


/*


  Tye Shavik (tye at tye . ca)


*/





class Thumbnail


{


  function __construct()


  {


  }


function create_thumbnail($infile,$outfile,$maxw,$maxh,$bolWater=FALSE,$stretch = FALSE) {


  clearstatcache();


  if (!is_file($infile)) {


    trigger_error("Cannot open file: $infile",E_USER_WARNING);


    return FALSE;


  }


  if (is_file($outfile)) 


  {


      chmod($outfile,0777);


	  unlink($outfile);


      ///trigger_error("Output file already exists: $outfile",E_USER_WARNING);


    //return FALSE;


  }





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


  list($neww,$newh) = $this->scale_dimensions($size[0],$size[1],$maxw,$maxh,$stretch);





  if ($size['mime'] == 'image/png') {


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


    if ($color_transparent >= 0) {


      // Copy transparency


      imagefill($new_img,0,0,$color_transparent);


      imagecolortransparent($new_img, $color_transparent);


    }


  } else {


    $new_img = imagecreatetruecolor($neww,$newh);


  }


  


  // Copy and resize image


  imagecopyresampled($new_img,$source_img,0,0,0,0,$neww,$newh,$size[0],$size[1]);





  // Save output file


  if ($save_function == 'imagejpeg') {


      // Change the JPEG quality here


      if (!$save_function($new_img,$outfile,75)) {


          trigger_error("Unable to save output image",E_USER_WARNING);


          return FALSE;


      }


  } else {


      if (!$save_function($new_img,$outfile)) {


          trigger_error("Unable to save output image",E_USER_WARNING);


          return FALSE;


      }


  }





 if($bolWater==true)


 {


  // $this->waterMark($infile,SITE_ABSPATH.'images/logo_watermark.png');


   $this->waterMark($outfile,SITE_ABSPATH.'images/logo_watermark.png');


 } 


  


  // Cleanup


  imagedestroy($source_img);


  imagedestroy($new_img);





  return TRUE;


}


// Scales dimensions


function scale_dimensions($w,$h,$maxw,$maxh,$stretch = FALSE) {


    if (!$maxw && $maxh) {


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


    return array(round($neww),round($newh));


}








function waterMark11( $dst_img1, $watermark_img1, $alpha = 100 ) {


        $alpha    /= 100;    # convert 0-100% user-friendly alpha to decimal








    $imagesource =  $dst_img1; 


	$filetype = substr($imagesource,strlen($imagesource)-4,4); 


	$filetype = strtolower($filetype); 


	if($filetype == ".gif")  $dst_img = @imagecreatefromgif($imagesource);  


	if($filetype == ".jpg")  $dst_img = @imagecreatefromjpeg($imagesource);  


	if($filetype == ".png")  $dst_img = @imagecreatefrompng($imagesource);  


	if (!$dst_img) die("Image does not exist or GD Library support is disabled on the server."); 


	$watermark_img = @imagecreatefrompng($watermark_img1); 


	


	


	


        # calculate our images dimensions


        $dst_img_w    = imagesx( $dst_img );


        $dst_img_h    = imagesy( $dst_img );


        $watermark_img_w    = imagesx( $watermark_img );


        $watermark_img_h    = imagesy( $watermark_img );


        


        # create new image to hold merged changes


        $return_img    = imagecreatetruecolor( $dst_img_w, $dst_img_h );


#        $return_img    = imagecreate( $dst_img_w, $dst_img_h );


        


        # determine center position coordinates


        $dst_img_min_x    = floor( ( $dst_img_w / 2 ) - ( $watermark_img_w / 2 ) );


        $dst_img_max_x    = ceil( ( $dst_img_w / 2 ) + ( $watermark_img_w / 2 ) );


        $dst_img_min_y    = floor( ( $dst_img_h / 2 ) - ( $watermark_img_h / 2 ) );


        $dst_img_max_y    = ceil( ( $dst_img_h / 2 ) + ( $watermark_img_h / 2 ) );


        


        # walk through main image


        for( $y = 0; $y < $dst_img_h; $y++ ) {


            for( $x = 0; $x < $dst_img_w; $x++ ) {


                $return_color    = NULL;


                


                # determine the correct pixel location within our watermark


                $watermark_x    = $x - $dst_img_min_x;


                $watermark_y    = $y - $dst_img_min_y;


                


                # fetch color information for both of our images


                $dst_rgb = imagecolorsforindex( $dst_img, imagecolorat( $dst_img, $x, $y ) );


                


                # if our watermark has a non-transparent value at this pixel intersection


                # and we're still within the bounds of the watermark image


                if (    $watermark_x >= 0 && $watermark_x < $watermark_img_w &&


                            $watermark_y >= 0 && $watermark_y < $watermark_img_h ) {


                    $watermark_rbg = imagecolorsforindex( $watermark_img, imagecolorat( $watermark_img, $watermark_x, $watermark_y ) );


                    


                    # using image alpha, and user specified alpha, calculate average


                    $watermark_alpha    = round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );


                    $watermark_alpha    = $watermark_alpha * $alpha;


                


                    # calculate the color 'average' between the two - taking into account the specified alpha level


                    $avg_red        = $this->get_ave_color( $dst_rgb['red'],        $watermark_rbg['red'],        $watermark_alpha );


                    $avg_green    = $this->get_ave_color( $dst_rgb['green'],    $watermark_rbg['green'],    $watermark_alpha );


                    $avg_blue        = $this->get_ave_color( $dst_rgb['blue'],    $watermark_rbg['blue'],        $watermark_alpha );


                    


                    # calculate a color index value using the average RGB values we've determined


                    $return_color    = $this->imagegetcolor( $return_img, $avg_red, $avg_green, $avg_blue );


                    


                # if we're not dealing with an average color here, then let's just copy over the main color


                } else {


                    $return_color    = imagecolorat( $dst_img, $x, $y );


                    


                } # END if watermark





                # draw the appropriate color onto the return image


				


				


				   $wmInfo = getimagesize($watermark_img1); 


				   $waterMarkDestWidth=$wmInfo[0];


				   $waterMarkDestHeight=$wmInfo[1];


				 


				   $origInfo = getimagesize($dst_img1); 


                   $origWidth = $origInfo[0]; 


				   $origHeight = $origInfo[1]; 


				  


				  $placementX = $origWidth - $waterMarkDestWidth - 15;


				  $placementY = $origHeight - $waterMarkDestHeight - 15;


				


                imagesetpixel( $return_img, $placementX, $placementY, $return_color );





            } # END for each X pixel


        } # END for each Y pixel


        


        # return the resulting, watermarked image for display


        //return $return_img;


	


	//die($return_img);


	


	$imagesource =  $dst_img1; 


	$filetype = substr($imagesource,strlen($imagesource)-4,4); 


	$filetype = strtolower($filetype); 	


	if($filetype == ".gif")  imagegif($return_img,$dst_img1,100); 


	if($filetype == ".jpg")  imagejpeg($return_img,$dst_img1,100);   


	if($filetype == ".png")  imagepng($return_img,$dst_img1,100); 


	


	imagedestroy($dst_img); 


	


    } # END create_watermark()


    


    # average two colors given an alpha


    function get_ave_color( $color_a, $color_b, $alpha ) {


        return round( ( ( $color_a * ( 1 - $alpha ) ) + ( $color_b    * $alpha ) ) );


    } # END get_ave_color()


    


    # return closest pallette-color match for RGB values


    function imagegetcolor($im, $r, $g, $b) {


        $c=imagecolorexact($im, $r, $g, $b);


        if ($c!=-1) return $c;


        $c=imagecolorallocate($im, $r, $g, $b);


        if ($c!=-1) return $c;


        return imagecolorclosest($im, $r, $g, $b);


    } # EBD imagegetcolor()








function waterMark($orgImage,$waterImage)


{


   $imagesource =  $orgImage; 


	$filetype = substr($imagesource,strlen($imagesource)-4,4); 


	$filetype = strtolower($filetype); 


	if($filetype == ".gif")  $image = @imagecreatefromgif($imagesource);  


	if($filetype == ".jpg")  $image = @imagecreatefromjpeg($imagesource);  


	if($filetype == ".png")  $image = @imagecreatefrompng($imagesource);  


	if (!$image) die("Image does not exist or GD Library support is disabled on the server."); 


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


				 


	$origInfo = getimagesize($orgImage); 


                   $origWidth = $origInfo[0]; 


				   $origHeight = $origInfo[1]; 


				  


				  $placementX = $origWidth - $waterMarkDestWidth - 10;


				  $placementY = $origHeight - $waterMarkDestHeight - 10;


				


				


				


	imagecopy($image, $watermark,  $placementX, $placementY, 0, 0, $watermarkwidth, $watermarkheight); 


	//imagealphablending($image,true);


	 //imagesavealpha($image,TRUE);


	 


	if($filetype == ".gif")  imagegif($image,$orgImage,100); 


	if($filetype == ".jpg")  imagejpeg($image,$orgImage,100);   


	if($filetype == ".png")  imagepng($image,$orgImage,100); 


	


	


	//imagejpeg($image,$orgImage,100); 


	imagedestroy($image); 


	imagedestroy($watermark); 





}





 function emboss($pic1, $patt)


  {


    


     $imagesource =  $pic1; 


    $filetype = substr($imagesource,strlen($imagesource)-4,4); 


	$filetype = strtolower($filetype); 


	if($filetype == ".gif")  $source = @imagecreatefromgif($imagesource);  


	if($filetype == ".jpg")  $source = @imagecreatefromjpeg($imagesource);  


	if($filetype == ".png")  $source = @imagecreatefrompng($imagesource);  


	 





      $pattern = imagecreatefrompng($patt); // pattern





      list ($width, $height) = getimagesize($pic1);


      list ($widthpatt, $heightpatt) = getimagesize($patt);





      $new_width = $width;


      $new_height = $width / $widthpatt * $heightpatt;





      if ($new_height > $height) {


    $offset = intval(($new_height - $height) / 2);


      }elseif ($new_height < $height) {


    $offset = intval(($new_height - $height) / 2);


       } else {


       $offset = 0;


        }





    $image_p = imagecreatetruecolor($new_width, $new_height);


    $pattern = imagecreatefrompng($patt);


     imagecopyresampled($image_p, $pattern, 0, 0, 0, 0, $new_width, $new_height, $widthpatt, $heightpatt);





    $im = imagecreatetruecolor($width, $height);


        for ($y = 0; $y < $height; $y ++) {


       for ($x = 0; $x < $width; $x ++) {


         $colors = imagecolorsforindex($source, imagecolorat($source, $x, $y));


         $pattern_color = imagecolorsforindex($image_p, imagecolorat($image_p, $x, ($y + $offset)));


        


             //changes brightness depending on luminance


        if (($y + $offset +1) > 0 && ($y + $offset) < ($new_height -1)) {


          $brightness = abs(($pattern_color['red'] * 50 / 255) - 50);


           if ($pattern_color['red'] < 150) {


             $change = true;


           } else {


              $change = false;


              $tally = 0;


           }





           if ($change && $tally < 2) {


             $highlight = 1.8;


             $tally ++;


           } else {


             $highlight = 1;


           }


           $brightness = $brightness * $highlight;


        } else {


            $brightness = 0;


        }


        $r = (($colors['red'] + $brightness) > 255) ? 255 : ($colors['red'] + $brightness);


        $g = (($colors['green'] + $brightness) > 255) ? 255 : ($colors['green'] + $brightness);


        $b = (($colors['blue'] + $brightness) > 255) ? 255 : ($colors['blue'] + $brightness);


        $test = imagecolorallocate($im, $r, $g, $b);


        imagesetpixel($im, $x, $y, $test);


       }


        }





    if($filetype == ".gif")  imagegif($im,$pic1);  


	if($filetype == ".jpg")  imagejpeg($im,$pic1);  


	if($filetype == ".png")  imagepng($im,$pic1);  


	


   // imagepng($im);


    imagedestroy($im);


   }








}


?>


