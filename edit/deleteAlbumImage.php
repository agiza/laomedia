<?php

//THIS PAGE CALLED BY FORMAT/IMAGE FORM ON SETTINGS.PHP PAGE
//AJAX CALL ONCLICK RADIO BUTTON

//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$albumID = $p_albumID;


//update DB
$stmt = $db->prepare("UPDATE albums SET posterimage=0 WHERE albumID = $albumID");
$stmt->execute();

$albumposterimage="../playerimage/album" . $albumID . ".jpg";
	if (!chmod($albumposterimage,0777)){echo "No file found.</br>";}
	unlink($albumposterimage);
	//DELETE THUMBNAIL
	$albumposterimage="../playerimage/thumbs/album" . $albumID . ".jpg";
	if (!chmod($albumposterimage,0777)){echo "No file found.</br>";}
	unlink($albumposterimage);

//GO TO
$goto = "editAlbum.php?albumID=" . $albumID;
echo "<script> window.location.href = '$goto' </script>";

?>

