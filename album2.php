<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$albumID = 6;


//$stmt = $db->prepare("SELECT albummedia.albumID, albums.album, albums.permission FROM albummedia LEFT JOIN albums ON albummedia.albumID = albums.albumID WHERE albummedia.mediaID = :mediaID");

$stmt = $db->prepare("SELECT albummedia.albumID, media.title FROM albummedia LEFT JOIN media ON albummedia.mediaID = media.mediaID WHERE albummedia.albumID = :albumID ORDER BY media.title ASC");
$stmt->execute(array(':albumID'=> $albumID));
$result = $stmt->fetchAll();
		foreach($result as $row){
			echo $row['title'] . "<br/>";
		} 
		
?>