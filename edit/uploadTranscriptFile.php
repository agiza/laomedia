<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLES
$mediaID = $_POST['mediaID'];
$filename = $_FILES['file']['name'];

//GET LOWER CASE OF FILE EXTENSION
$ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

//ALLOWED FILE TYPES
$allowedfiles = array('txt');

//alert if wrong file type
if(!in_array($ext, $allowedfiles)){	
		echo "<script type='text/javascript'>
		alert('Invalid File Type. File must be a .txt.');
		history.go(-1);
		</script>";
		exit();
	}
	
	
$transcriptfile = $mediaID  . "." . $ext;
$transcripthtml = $mediaID  . ".html";


if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    $filename = $_FILES['file']['name'];
    $file_tmp =$_FILES['file']['tmp_name'];
    $filecontents = file_get_contents($_FILES['file']['tmp_name']);
    $filecontents = strip_tags($filecontents);
    $filecontentsHTML = "<!DOCTYPE html> <html lang='en'> <head> <meta charset='utf-8' /> <title>Transcript</title></head><body>" . $filecontents . "</body></html>";
    
    //MOVE UPLOADED TXT FILE TO TRANSCRIPTS FOLDER
    $moveto = "../transcripts/" . $transcriptfile;
    move_uploaded_file($file_tmp, $moveto); 
    
    //WRITE SAME FILE AS HTML
    $moveto = "../transcripts/" . $transcripthtml;
    $fp = fopen("$moveto", "w");
	if (!$fp) die ("Cannot open file");

	fwrite($fp, $filecontentsHTML);
	fclose($fp); 
	echo "HTML File Saved.";  
    }else{
     echo "Error: No file selected.<br/>";    
    }


$stmt = $db->prepare("UPDATE media SET transcript = :transcript WHERE mediaID = :mediaID");
$stmt->execute(array(':transcript'=>$transcriptfile,':mediaID'=>$mediaID));

//GO TO admin.php
$goto = "../settings.php?vid=" . $mediaID . "&showpane=pane4";
echo "<script> window.location.href = '$goto' </script>";

?>

