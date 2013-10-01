<?php

//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLE
$albumID = $_POST['albumID'];


//CHECK FILE
if($_FILES['image_file']['name'] == null){
	echo "<script>alert('Select a file to upload.');history.go(-1)</script>";
	exit();
}elseif(($_FILES['image_file']["type"] != "image/jpeg")
&& ($_FILES['image_file']["type"] != "image/pjpeg")){
		echo "<script>alert('File must be a JPEG image file. Please select another.');history.go(-1);</script>";
		exit();
	}


//DIRECTORY PATHS FOR IMAGES
	$ImageDir ="../playerimage/";
	$ImageThumbDir = $ImageDir . "thumbs/";
	
 	$newfilename = $ImageDir . "album" . $albumID . ".jpg";
 	$newthumbname = $ImageThumbDir . "album" . $albumID . ".jpg";

$uploadedfile = $_FILES['image_file']['tmp_name'];
list($width,$height)=getimagesize($uploadedfile);
		
	if($height > 550){
		//SIZE THE LARGER IMAGE
		$new_height = 540;
		$new_width = $width * ($new_height/$height);

		$largeimage = imagecreatefromjpeg($uploadedfile);
		$image_resized = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($image_resized, $largeimage, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($image_resized, $newfilename,100);
		imagedestroy($image_resized);
		imagedestroy($largeimage);
	}else{//upload image size as is
		$originalimage = imagecreatefromjpeg($uploadedfile);
		imagejpeg($originalimage, $newfilename,100);
	}
		
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
  		

	
//UPDATE DB
$stmt = $db->prepare("UPDATE albums SET posterimage=1 WHERE albumID = :albumID");
	$stmt->execute(array(':albumID' => $albumID));

//GO TO
$goto = "editAlbum.php?albumID=" . $albumID;
echo "<script> window.location.href = '$goto' </script>";

?>

