<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

include "playerConfig.php";//$uploadVideoPath

//IMPORT VARIABLES
import_request_variables("p","p_");

$title = strip_tags($p_title);
$format = $p_format;
$date = time();
$filename = $_FILES['files']['name'];

$stmt = $db->prepare("INSERT INTO media(title,uploaddate,filename,permission,owner,type,caption,format,size,viewcount) VALUES(:title,:uploaddate,:filename,:permission,:owner,:type,:caption,:format,:size,:viewcount)");
$stmt->execute(array(':title' => $title, ':uploaddate'=>$date, ':filename'=>$filename,':permission'=>'public',':owner'=>$userID,':type'=>'singlevid',':caption'=>'none',':format'=>$format,':size'=>'med',':viewcount'=>0));

$mediaID = $db->lastInsertId('mediaID');

if (is_uploaded_file($_FILES['files']['tmp_name'])) {
    $filename = $_FILES['files']['name'];
    $file_size =$_FILES['files']['size'];
    $file_tmp =$_FILES['files']['tmp_name'];
    $file_type=$_FILES['files']['type'];
    $videoname = $mediaID . ".mp4";
    $moveto = $uploadVideoPath . $videoname;
    	if(move_uploaded_file($file_tmp, $moveto)){
    		echo "<div style='font-size:1.4em;'>This file was successfully uploaded.<br/>";
			echo $filename . " as VideoID# " . $mediaID . "<br/>";
			echo "<a href='settings.php?vid=" . $mediaID . "'>Go to Settings</a> for additional options for this video.";
			echo "</div>";
    	}else{
    		//if unsuccessfull delete data from DB
    		$stmt = $db->prepare("DELETE FROM media WHERE mediaID='$mediaID'");
			$stmt->execute();
    		echo "Uploading of this video was unsuccessful. Please contact admin.";
    	}
    }else{
     echo "Error: No file selected.<br/>";
    }

?>

