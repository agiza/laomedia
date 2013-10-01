<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "validateAdmin.php";

//$mediaID=1084;

//ALTER TABLE - ADD TRANSCRIPT COLUMN
//$stmt = $db->prepare("ALTER TABLE media ADD transcript VARCHAR( 40 ) after caption");

//UPDATE TABLES

//set new permission on media
//$stmt = $db->prepare("UPDATE media SET permission=:permission");
//$stmt->execute(array(':permission'=>'album'));

//$stmt = $db->prepare("DELETE FROM media WHERE mediaID='$mediaID'");

$stmt = $db->prepare("UPDATE media SET transcript = 'none'");
$stmt->execute();
echo "Update completed."
?>