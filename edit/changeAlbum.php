<?php

//THIS PAGE CALLED BY FORMAT/IMAGE FORM ON SETTINGS.PHP PAGE
//AJAX CALL ONCLICK RADIO BUTTON

include "../dbconnect.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$mediaID = $p_mediaID;
$albumID= $p_albumID;


$stmt = $db->prepare("SELECT album FROM albums WHERE albumID = :albumID");
$stmt->execute(array(':albumID'=>$albumID));
$row = $stmt->fetch();
$albumname = $row['album'];

//delete
	$stmt = $db->prepare("DELETE FROM albummedia WHERE mediaID= :mediaID");
	$stmt->execute(array(':mediaID'=>$mediaID));

//insert
if($albumID > 0){
	$stmt = $db->prepare("INSERT INTO albummedia(albumID,mediaID) VALUES(:albumID,:mediaID)");
	$stmt->execute(array(':mediaID' => $mediaID, ':albumID' => $albumID));
	
	echo "<span style='color:green;'>Added to " . $albumname . "<span>";
}else{
	echo "<span style='color:red;'>Removed from albums.<span>";
}

?>

