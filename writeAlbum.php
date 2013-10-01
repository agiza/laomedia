<?php

//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

//IMPORT VARIABLES
$album = strip_tags($_POST['album']);
$description = strip_tags($_POST['description']);


//insert the new album name
	$stmt = $db->prepare("INSERT INTO albums(album,description,permission) VALUES(:album,:description,:permission)");
	$stmt->execute(array(':album' => $album, ':description' => $description, ':permission'=>'public'));
	
$albumID = $db->lastInsertId('albumID');

//GO TO index.php
$goto = "index.php";
echo "<script> window.location.href = '$goto' </script>";

?>

