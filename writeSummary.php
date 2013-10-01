<?php

//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

//IMPORT VARIABLES
$mediaID = $_POST['mediaID'];
$title = strip_tags($_POST['title']);
$description = strip_tags($_POST['description']);
$tags = strip_tags($_POST['tags']);
$date = time();


$stmt = $db->prepare("UPDATE media SET title=:title,description=:description,tags=:tags WHERE mediaID = :mediaID");
$stmt->execute(array(':title'=>$title,':description'=>$description,':tags'=>$tags,':mediaID'=>$mediaID));

//GO TO admin.php
$goto = "settings.php?vid=" . $mediaID;
echo "<script> window.location.href = '$goto' </script>";

?>

