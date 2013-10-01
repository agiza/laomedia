<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLES
$mediaID = $_POST['mediaID'];
$caption = $_POST['caption'];

//DELETE CAPTION FILE
	$thiscaptionfile="../captions/" . $caption;
	if (!chmod($thiscaptionfile,0777)){echo "No file found.</br>";}
	unlink($thiscaptionfile);

//UPDATE media to no caption file
$stmt = $db->prepare("UPDATE media SET caption='none' WHERE mediaID = :mediaID");
$stmt->execute(array(':mediaID'=>$mediaID));


echo "The caption file, " .  $caption . ", has been deleted.";

//GO TO index.php
//$goto = "../settings.php?vid=" . $mediaID;
//echo "<script> window.location.href = '$goto' </script>";

?>

