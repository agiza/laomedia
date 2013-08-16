<?php

//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLES
import_request_variables("pg","p_");
$albumID = $p_albumID;
$album = strip_tags($p_album);
$description = strip_tags($p_description);
$permission = $p_permission;
$courseID = null; //initial set
if($permission == 'restricted'){
	$courseID = strip_tags($p_courseID);
	$useraccessID = strip_tags($p_useraccessID);
	$useraccessID = str_replace(" ","",$useraccessID);//remove whitespace
	$userIDarray = explode(",",$useraccessID);
	$overwrite = $p_overwrite;
	}

$stmt = $db->prepare("UPDATE albums SET album=:album,description=:description,permission=:permission,courseID=:courseID WHERE albumID = :albumID");
	$stmt->execute(array(':album' => $album, ':description' => $description, ':permission' => $permission,':courseID'=>$courseID,':albumID' => $albumID));

//ALBUM PERMISSIONS
//IF OVERWRITE DELETE ALL FROM THIS ALBUM'S ALBUMPERMISSION
if($overwrite == 'overwrite'){
	//DELETE USERIDs FROM albummedia
	$stmt = $db->prepare("DELETE FROM albumpermission WHERE albumID=:albumID");
	$stmt->execute(array(':albumID'=>$albumID));
	}


//IF THERE IS A COURSEID RUN syncRoster.php ON ANGEL API
if ($courseID != null){
	//GET ANGEL API XML FILE
   // $feedURL = "https://cms.psu.edu/api/default.asp?APIACTION=PSU_TEAMLISTXML2&STRCOURSE_ID=" . $courseID . "&APIUSER=USER&APIPWD=PWD";
    $feedURL = "testRoster.xml";
    
    // read feed into SimpleXML object
    $xml = simplexml_load_file($feedURL) or die ("Unable to load XML file!");
    
    //CHECK FOR MATCHING COURSE ID
	if($xml->success->roster->course_id == ""){
		echo "<script>alert('No match for entered course ID. /n Please check and re-enter.');";
		echo "history.go(-1);</script>";
		exit();
	}
	
	$stmt = $db->prepare("INSERT INTO albumpermission(albumID,userID) VALUES(:albumID,:userID)");
	
	foreach($xml->success->roster->member as $member){
		$thisID = $member->user_id;
		$thisID = strtolower($thisID);
				  					
    	$stmt->execute(array(':albumID' => $albumID, ':userID' => $thisID));
    	}
}


//IF USERIDs ENTERED ADD THEM	
if ($useraccessID != null){
		$stmt = $db->prepare("INSERT INTO albumpermission(albumID,userID) VALUES(:albumID,:userID)");
		foreach($userIDarray as $val){
			$stmt->execute(array(':albumID' => $albumID, ':userID' => $val));
		}
	}
	


//GO TO
$goto = "editAlbum.php?albumID=" . $albumID;
echo "<script> window.location.href = '$goto' </script>";

?>

