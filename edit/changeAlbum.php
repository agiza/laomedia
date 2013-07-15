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

//delete from any other album
	$stmt = $db->prepare("DELETE FROM albummedia WHERE mediaID= :mediaID");
	$stmt->execute(array(':mediaID'=>$mediaID));

//insert new association
if($albumID > 0){
	$stmt = $db->prepare("INSERT INTO albummedia(albumID,mediaID) VALUES(:albumID,:mediaID)");
	$stmt->execute(array(':mediaID' => $mediaID, ':albumID' => $albumID));
	
	//change media permissions status to 'album'
	$stmt = $db->prepare("UPDATE media SET permission='album' WHERE mediaID = :mediaID");
	$stmt->execute(array(':mediaID'=>$mediaID));
	
	echo "<span style='color:green;'>Added to " . $albumname . "<span>";
}else{//no album
	//change media permissions status to 'public'
$stmt = $db->prepare("UPDATE media SET permission='public' WHERE mediaID = :mediaID");
$stmt->execute(array(':mediaID'=>$mediaID));
	echo "<span style='color:red;'>Removed from albums.<span>";
}



//GO TO admin.php
$goto = "../settings.php?vid=" . $mediaID . "&showpane=pane2";
echo "<script> window.location.href = '$goto' </script>";

?>

