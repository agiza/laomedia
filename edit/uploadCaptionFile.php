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
$allowedfiles = array('srt', 'vtt');

//alert if wrong file type
if(!in_array($ext, $allowedfiles)){	
		echo "<script type='text/javascript'>
		alert('Invalid File Type. File must be a .srt or .vtt file.');
		history.go(-1);
		</script>";
		exit();
	}
	
$captionfile = $mediaID  . "." . $ext;


if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    $filename = $_FILES['file']['name'];
    $file_tmp =$_FILES['file']['tmp_name'];
    $moveto = "../captions/" . $captionfile;
    move_uploaded_file($file_tmp, $moveto);    
    }else{
     echo "Error: No file selected.<br/>";    
    }


$stmt = $db->prepare("UPDATE media SET caption = :caption WHERE mediaID = :mediaID");
$stmt->execute(array(':caption'=>$captionfile,':mediaID'=>$mediaID));

//GO TO admin.php
$goto = "../settings.php?vid=" . $mediaID . "&showpane=pane4";
echo "<script> window.location.href = '$goto' </script>";

?>

