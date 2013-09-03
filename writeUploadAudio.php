<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";


//IMPORT VARIABLES
import_request_variables("p","p_");

$title = strip_tags($p_title);
$date = time();
$filename = $_FILES['files']['name'];

//VALIDATE
//IF NO FILE SELECTED RETURN TO FORM
if (!$filename) {	
	echo"You must select a file to upload.";
	exit();
}

if($_FILES['files']['type'] != "audio/mpeg"){
	echo"Uploaded file must be an .mp3 file.";
	exit();
	}



	 
$stmt = $db->prepare("INSERT INTO media(title,uploaddate,filename,permission,owner,type,caption,format,size,viewcount) VALUES(:title,:uploaddate,:filename,:permission,:owner,:type,:caption,:format,:size,:viewcount)");
$stmt->execute(array(':title' => $title, ':uploaddate'=>$date, ':filename'=>$filename,':permission'=>'public',':owner'=>$userID,':type'=>'audio',':caption'=>'none',':format'=>'audio',':size'=>'med',':viewcount'=>0));

$mediaID = $db->lastInsertId('mediaID');


if (is_uploaded_file($_FILES['files']['tmp_name'])) {
    $filename = $_FILES['files']['name'];
    $file_size =$_FILES['files']['size'];
    $file_tmp =$_FILES['files']['tmp_name'];
    $file_type=$_FILES['files']['type'];
    $medianame = $mediaID . ".mp3";
    $moveto = "content/" . $medianame;
 
    	if(move_uploaded_file($file_tmp, $moveto)){
    		echo "<div style='font-size:1.4em;'>This file was successfully uploaded.<br/>";
			echo $filename . " as media ID# " . $mediaID . "<br/>";
			echo "<a href='settings.php?vid=" . $mediaID . "'>Go to Settings</a> for additional options for this video.";
			echo "</div>";
    	}else{
    		$stmt = $db->prepare("DELETE FROM media WHERE mediaID='$mediaID'");
			$stmt->execute();
    		echo "Uploading of this audio file was unsuccessful. Please contact admin.";
    	}
    }else{
     echo "Error: No file selected.<br/>";
    }



?>

