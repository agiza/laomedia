<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//$mediaID=1084;

//UPDATE TABLES

//set new permission on media
//$stmt = $db->prepare("UPDATE media SET permission=:permission");
//$stmt->execute(array(':permission'=>'album'));

$stmt = $db->prepare("DELETE FROM media WHERE mediaID='$mediaID'");
//$stmt->execute();

?>