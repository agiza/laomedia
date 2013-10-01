<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLES
$mediaID = $_POST['mediaID'];
$transcript = $_POST['transcript'];

//DELETE TRANSCRIPT FILE
	$thistranscriptfile="../transcripts/" . $transcript;
	if (!chmod($thistranscriptfile,0777)){echo "No file found.</br>";}
	unlink($thistranscriptfile);
	
//DELETE TRANSCRIPT HTML FILE
	$thistranscriptfile="../transcripts/" . $mediaID . ".html";
	if (!chmod($thistranscriptfile,0777)){echo "No file found.</br>";}
	unlink($thistranscriptfile);	

//UPDATE media to no transcript file
$stmt = $db->prepare("UPDATE media SET transcript='none' WHERE mediaID = :mediaID");
$stmt->execute(array(':mediaID'=>$mediaID));


echo "The transcript file, " .  $transcript . ", has been deleted.";

//GO TO index.php
//$goto = "../settings.php?vid=" . $mediaID;
//echo "<script> window.location.href = '$goto' </script>";

?>

