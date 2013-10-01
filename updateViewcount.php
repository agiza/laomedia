<?php

//THIS PAGE CALLED BY VIDEO.PHP PAGE
//AJAX CALL WHEN PLAY IS CLICKED

include "dbconnect.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$mediaID = $_POST['mediaID'];
$viewcount= $_POST['viewcount'];

$stmt = $db->prepare("UPDATE media SET viewcount=:viewcount WHERE mediaID = :mediaID");
$stmt->execute(array(':viewcount'=>$viewcount,':mediaID'=>$mediaID));

?>

