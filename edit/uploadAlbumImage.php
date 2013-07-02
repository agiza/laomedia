<?php

//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLES
import_request_variables("pg","p_");
$albumID = $p_albumID;
$album = strip_tags($p_album);

$filename = $_FILES['image_file']['name'];
if($filename == null){
	$posterimage = 0;
	echo "<script>alert('Select a file to upload.');history.go(-1)</script>";	
	}else{
	$posterimage = 1;
	}



$stmt = $db->prepare("UPDATE albums SET posterimage=:posterimage WHERE albumID = :albumID");
	$stmt->execute(array(':posterimage'=>$posterimage,':albumID' => $albumID));


//POSTERIMAGE
$image_tempname = $_FILES['image_file']['name'];

//IF A JPEG FILE HAS BEEN SELECTED THEN DO THIS
if(($_FILES['image_file']["type"] == "image/jpeg")
|| ($_FILES['image_file']["type"] == "image/pjpeg")){

//DIRECTORY PATHS FOR IMAGES
	$ImageDir ="../playerimage/";
	$ImageThumbDir = $ImageDir . "thumbs/";
	
 	$newfilename = $ImageDir . "album" . $albumID . ".jpg";
 	$newthumbname = $ImageThumbDir . "album" . $albumID . ".jpg";

$uploadedfile = $_FILES['image_file']['tmp_name'];
list($width,$height)=getimagesize($uploadedfile);
		

		//SIZE THE LARGER IMAGE
		$new_height = 360;
		$new_width = $width * ($new_height/$height);

		$largeimage = imagecreatefromjpeg($uploadedfile);
		$image_resized = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($image_resized, $largeimage, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($image_resized, $newfilename,80);
		imagedestroy($image_resized);
		imagedestroy($largeimage);

		
		//CREATE THUMBNAIL IMAGE

		//get the dimensions for the thumbnail
 		$thumb_height = 80;
  		$thumb_width = $width * ($thumb_height/$height);
		//create the thumbnail
		$largeimage =imagecreatefromjpeg($uploadedfile);
  		$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
  		imagecopyresampled($thumb, $largeimage, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
 		imagejpeg($thumb, $newthumbname, 80);
 		imagedestroy($largeimage);
  		imagedestroy($thumb);
  		
	}else{
		//echo "ERROR READING FILE";
	}

//GO TO
$goto = "editAlbum.php?albumID=" . $albumID;
echo "<script> window.location.href = '$goto' </script>";

?>

