<?php
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");
$mediaID = $p_mediaID;
$albumID=1;//default PSU Only album

//delete
	$stmt = $db->prepare("DELETE FROM albummedia WHERE mediaID= :mediaID");
	$stmt->execute(array(':mediaID'=>$mediaID));


	$stmt = $db->prepare("INSERT INTO albummedia(albumID,mediaID) VALUES(:albumID,:mediaID)");
	$stmt->execute(array(':mediaID' => $mediaID, ':albumID' => $albumID));
	
	
echo "This video is now included in the PSU Only album.";
	

?>

