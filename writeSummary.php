<?php

//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$mediaID = $p_mediaID;
$title = strip_tags($p_title);
$description = strip_tags($p_description);
$tags = strip_tags($p_tags);
$date = time();


$stmt = $db->prepare("UPDATE media SET title=:title,description=:description,tags=:tags WHERE mediaID = :mediaID");
$stmt->execute(array(':title'=>$title,':description'=>$description,':tags'=>$tags,':mediaID'=>$mediaID));

//GO TO admin.php
$goto = "settings.php?vid=" . $mediaID;
echo "<script> window.location.href = '$goto' </script>";

?>

