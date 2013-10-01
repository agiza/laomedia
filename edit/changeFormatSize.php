<?php
//THIS PAGE CALLED BY FORMAT/IMAGE FORM ON SETTINGS.PHP PAGE
//AJAX CALL ONCLICK RADIO BUTTON

//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

include "../playerConfig.php";

//IMPORT VARIABLES
$mediaID = $_POST['mediaID'];
$format= $_POST['format'];
$size = $_POST['size'];

//set width & height variables
include "../functions/playersize.php";

//update DB
$stmt = $db->prepare("UPDATE media SET format=:format, size=:size WHERE mediaID = :mediaID");
$stmt->execute(array(':format' => $format, ':size' => $size, ':mediaID' => $mediaID));

//return updated embed code
echo "<p>Copy and Paste</p><textarea style='width:500px;'><iframe src='//" . $server . "/mediaframe.php?id=" . $mediaID . "' height='" . $height . "' width='" . $width . "'></iframe></textarea>";


?>

