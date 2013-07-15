<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$albumID = $p_albumID;

$stmt = $db->prepare("SELECT * FROM albums WHERE albumID=:albumID");
$stmt->execute(array(':albumID'=> $albumID));
$row = $stmt->fetch();

//DELETE POSTERIMAGE IF THERE IS ONE
if($row['posterimage']==1){
	$albumposterimage="../playerimage/album" . $albumID . ".jpg";
	if (!chmod($albumposterimage,0777)){echo "No file found.</br>";}
	unlink($albumposterimage);
	//DELETE THUMBNAIL
	$albumposterimage="../playerimage/thumbs/album" . $albumID . ".jpg";
	if (!chmod($albumposterimage,0777)){echo "No file found.</br>";}
	unlink($albumposterimage);
}

//CHANGE PERMISSION OF ALL MEDIA IN ALBUM TO PUBLIC
$stmt = $db->prepare("SELECT mediaID FROM albummedia WHERE albumID = $albumID");
		$stmt->execute();		
		$videocount = $stmt->rowCount(); 
		$result = $stmt->fetchAll();
		foreach($result as $row){
		$mediaID=$row['mediaID'];
			//set new permission on media
			$changePermission = $db->prepare("UPDATE media SET permission='public' WHERE mediaID = :mediaID");
			$changePermission->execute(array(':mediaID'=>$mediaID));
		} 


//DELETE DATA FROM DB
$stmt = $db->prepare("DELETE FROM albums WHERE albumID=:albumID");
$stmt->execute(array(':albumID'=>$albumID));

$stmt = $db->prepare("DELETE FROM albummedia WHERE albumID=:albumID");
$stmt->execute(array(':albumID'=>$albumID));

$stmt = $db->prepare("DELETE FROM albumpermission WHERE albumID=:albumID");
$stmt->execute(array(':albumID'=>$albumID));

//GO TO index.php
$goto = "../index.php";
echo "<script> window.location.href = '$goto' </script>";

?>

