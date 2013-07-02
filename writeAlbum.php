<?php

//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

//IMPORT VARIABLES
import_request_variables("pg","p_");

$album = strip_tags($p_album);
$description = strip_tags($p_description);

//insert the new album name
	$stmt = $db->prepare("INSERT INTO albums(album,description,permission) VALUES(:album,:description,:permission)");
	$stmt->execute(array(':album' => $album, ':description' => $description, ':permission'=>'public'));
	
$albumID = $db->lastInsertId('albumID');

//GO TO index.php
$goto = "index.php";
echo "<script> window.location.href = '$goto' </script>";

?>

