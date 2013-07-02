<?php

//THIS PAGE CALLED BY FORMAT/IMAGE FORM ON SETTINGS.PHP PAGE
//AJAX CALL ONCLICK RADIO BUTTON

include "../dbconnect.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$mediaID = $p_mediaID;


//update DB
$stmt = $db->prepare("UPDATE media SET posterimage=0 WHERE mediaID = $mediaID");
$stmt->execute();

$videoposterimage="../playerimage/" . $mediaID . ".jpg";

	if (!chmod($videoposterimage,0777)){echo "No file found.</br>";}
	unlink($videoposterimage);
	//DELETE THUMBNAIL
	$videoposterimage="vidframes/thumbs/" . $mediaID . ".jpg";
	if (!chmod($videoposterimage,0777)){echo "No file found.</br>";}
	unlink($videoposterimage);

//GO TO
$goto = "../settings.php?vid=" . $mediaID;
echo "<script> window.location.href = '$goto' </script>";

?>

