<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

include "../playerConfig.php";//$uploadVideoPath,

//IMPORT VARIABLE
$mediaID = $_GET['mediaID'];

$stmt = $db->prepare("SELECT * FROM media WHERE mediaID=:mediaID");
$stmt->execute(array(':mediaID'=> $mediaID));
$row = $stmt->fetch();

//DELETE VIDEO FILE
if($row['type']=='multivid'){
	//delete all video files that contain the mediaID #
	$dirname = $uploadVideoPath;
	$dir = opendir($dirname);

	while(false != ($file = readdir($dir))){
		$fullpathtofile= $uploadVideoPath . $file;
			$pos = strpos($file,$mediaID);
			if($pos!==false){
			unlink($fullpathtofile);
			}
	}
}elseif($row['type']=='singlevid'){//just delete the one file
	$thisvideofile= $uploadVideoPath . $mediaID . ".mp4";
	unlink($thisvideofile);
}elseif($row['type']=='audio'){
	$thisaudiofile= "../" . $uploadAudioPath . $mediaID . ".mp3";
	unlink($thisaudiofile);
}

//DELETE CAPTION FILE IF EXISTS
if($row['caption'] != 'none'){
	$thiscaptionfile="../captions/" . $row['caption'];
	unlink($thiscaptionfile);
}

//DELETE CUSTOM IMAGE FILE IF EXISTS
if($row['image']==1){
	$thisimagefile="../playerimage/" . $mediaID . ".jpg";
	unlink($thisimagefile);
}


//DELETE DATA FROM DB
$stmt = $db->prepare("DELETE FROM media WHERE mediaID=:mediaID");
$stmt->execute(array(':mediaID'=>$mediaID));

$stmt = $db->prepare("DELETE FROM albummedia WHERE mediaID=:mediaID");
$stmt->execute(array(':mediaID'=>$mediaID));

//GO TO index.php
$goto = "../index.php";
echo "<script> window.location.href = '$goto' </script>";

?>

