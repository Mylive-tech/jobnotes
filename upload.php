<?php
include('config/config.php');
$uploaddir = 'upload/tmp/';
$uploadfile = $uploaddir . basename($_FILES['image_file']['name']);
if($_REQUEST['action']==1)
{
	$image = imagecreatefrompng($_POST['image']);
	$id = uniqid();

	imagealphablending($image, false);
	imagesavealpha($image, true);
	imagepng($image, 'upload/wPaint-' . $id . '.png');

	// return image path
	echo '{"img": "upload/wPaint-' . $id . '.png"}';	
}
else
{
	if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadfile)) 
	{
	   list($width, $height, $type, $attr) = getimagesize($uploadfile);
	   
		echo '<iframe frameborder="0" src="'.SITE_URL.'jQuery-image-edit-tool/test/dev.php?siteurl='.SITE_URL.'&img='.$uploadfile.'" width="100%" height="700"></iframe>';
	} 
	else 
	{
		echo "File could not upload. Please try again";
	}
}	
?> 
