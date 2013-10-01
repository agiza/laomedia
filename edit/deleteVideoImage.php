<?php

//THIS PAGE CALLED BY FORMAT/IMAGE FORM ON SETTINGS.PHP PAGE
//AJAX CALL ONCLICK RADIO BUTTON

//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLE
$mediaID = $_GET['mediaID'];

//update DB
$stmt = $db->prepare("UPDATE media SET posterimage=0 WHERE mediaID = $mediaID");
$stmt->execute();

$videoposterimage="../playerimage/" . $mediaID . ".jpg";

	if (!chmod($videoposterimage,0777)){echo "No file found.</br>";}
	unlink($videoposterimage);
	//DELETE THUMBNAIL
	$videoposterimage="../playerimage/thumbs/" . $mediaID . ".jpg";
	if (!chmod($videoposterimage,0777)){echo "No file found.</br>";}
	unlink($videoposterimage);

//GO TO
$goto = "../settings.php?vid=" . $mediaID;
echo "<script> window.location.href = '$goto' </script>";

?>

