<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

include "playerConfig.php";//$videoUploadPath

//IMPORT VARIABLES
import_request_variables("p","p_");

$title = strip_tags($p_title);
$format = $p_format;
$date = time();
$filename = $_FILES['files']['name'][0];

$stmt = $db->prepare("INSERT INTO media(title,uploaddate,filename,permission,owner,type,caption,format,size,viewcount) VALUES(:title,:uploaddate,:filename,:permission,:owner,:type,:caption,:format,:size,:viewcount)");
$stmt->execute(array(':title' => $title, ':uploaddate'=>$date, ':filename'=>$filename,':permission'=>'public',':owner'=>$userID,':type'=>'multivid',':caption'=>'none',':format'=>$format,':size'=>'med',':viewcount'=>0));

//this will be filename
$mediaID = $db->lastInsertId('mediaID');

//appended to filename
$bitrate=hi;

$count=0;

//output message to upload form
echo "<div style='font-size:1.4em;'>Files uploaded:<br/>";

//3 files uploaded with bitrates of 2000, 1000, 500
foreach($_FILES['files']['name'] as $filename ){

	if (is_uploaded_file($_FILES['files']['tmp_name'][$count])) {
    	$file_name = $_FILES['files']['name'][$count];
    	$file_size =$_FILES['files']['size'][$count];
    	$file_tmp =$_FILES['files']['tmp_name'][$count];
    	
    	$videoname = $mediaID ."_" . $bitrate . ".mp4";
   		$moveto = $uploadVideoPath . $videoname;
   		if(move_uploaded_file($file_tmp, $moveto)){
   		
   			echo $file_name . " as VideoID# " . $videoname . "<br/>";
    	}else{
    		//if unsuccessfull delete data from DB and exit()
    		$stmt = $db->prepare("DELETE FROM media WHERE mediaID='$mediaID'");
			$stmt->execute();
    		echo "Failed to upload file.";
    		exit();
    		}
    	
   	 }else{
    	 echo "Error: No file selected.<br/>";    
    }
    
    if($bitrate == med){$bitrate = low;}
    if($bitrate == hi){$bitrate = med;}
    
    $count++;
    
}

//create smil file for this video
$smilfile = '<smil>
  <head>
  </head>
  <body>
    <switch>
      <video src="mp4:' . $mediaID . '_hi.mp4" system-bitrate="1100000" width="960" height="540" />
      <video src="mp4:' . $mediaID . '_med.mp4" system-bitrate="650000" width="640" height="360" />
      <video src="mp4:' . $mediaID . '_low.mp4" system-bitrate="500000" width="480" height="270" />
    </switch>
    </body> 
</smil>';

$filename = $uploadVideoPath . $mediaID . ".smil";
$fp = fopen("$filename", "w");
if (!$fp) die ("Cannot open file");
fwrite($fp, $smilfile);
fclose($fp);


echo "<a href='settings.php?vid=" . $mediaID . "'>Go to Settings</a> for additional options for this video.";
echo "</div>";

?>

