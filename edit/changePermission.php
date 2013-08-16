<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateInstructor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("p","p_");

$mediaID = $p_mediaID;
$useraccessID = strip_tags($p_useraccessID);
$useraccessID = strtolower($useraccessID);//make lowercase
$useraccessID= str_replace(" ","",$useraccessID);//remove whitespace
$useraccessID= str_replace("'","",$useraccessID);//remove single quotes
$useraccessID= str_replace('"',"",$useraccessID);//remove double quotes
$access=explode(",",$useraccessID);//array of separate userIDs
$permission = $p_permission;
$overwrite = $p_overwrite;
$pattern ="/^[A-Za-z]{3}[0-9]{1,4}$/";//regex for PSU user ID (3 letters, 1-4 numbers)


//set new permission on media
$stmt = $db->prepare("UPDATE media SET permission=:permission WHERE mediaID = :mediaID");
$stmt->execute(array(':permission'=>$permission,':mediaID'=>$mediaID));

if($permission=="restricted"){
	
	if($overwrite=='add'){
	$stmt = $db->prepare("INSERT INTO mediapermission(mediaID,userID) VALUES(:mediaID,:userID)");

		foreach($access as $accessID){
			if(preg_match($pattern,$accessID)){
				$stmt->execute(array(':mediaID' => $mediaID, ':userID'=>$accessID));
			}
		}
	}else{//overwrite==overwrite
		//DELETE CURRENT ACCESS ID'S
		$stmt = $db->prepare("DELETE FROM mediapermission WHERE mediaID=:mediaID");
		$stmt->execute(array(':mediaID' => $mediaID));
	
		//INSERT NEW ONES
		$stmt = $db->prepare("INSERT INTO mediapermission(mediaID,userID) VALUES(:mediaID,:userID)");

		foreach($access as $accessID){
			if(preg_match($pattern,$accessID)){
				$stmt->execute(array(':mediaID' => $mediaID, ':userID'=>$accessID));
			}
		}
	}
	
	//display list of access ID's on page
	echo "Current access ID's:<br/>";
	$stmt = $db->prepare("SELECT userID FROM mediapermission WHERE mediaID = :mediaID");
			$stmt->execute(array(':mediaID'=> $mediaID));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {      
				echo $row['userID'] . ", ";
			}
}elseif($permission=="psu"){
	//DELETE CURRENT ACCESS ID'S
		$stmt = $db->prepare("DELETE FROM mediapermission WHERE mediaID=:mediaID");
		$stmt->execute(array(':mediaID' => $mediaID));

	echo 'Permission has been set to "PSU".';
	
}elseif($permission=="album"){
	//DELETE CURRENT ACCESS ID'S
		$stmt = $db->prepare("DELETE FROM mediapermission WHERE mediaID=:mediaID");
		$stmt->execute(array(':mediaID' => $mediaID));
		echo 'Permissions will be inherited from album.';
		
}elseif($permission=="hidden"){
	//DELETE CURRENT ACCESS ID'S
		$stmt = $db->prepare("DELETE FROM mediapermission WHERE mediaID=:mediaID");
		$stmt->execute(array(':mediaID' => $mediaID));
		echo 'This video will be hidden from view.';
		
}elseif($permission=="public"){
	echo 'Permission has been set to "Public".';
}
	

?>

