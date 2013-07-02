<?php
//THIS INCLUDE FILE GIVES $userID
include "../validateUser.php";

include "../dbconnect.php";

include "validateAdmin.php";

import_request_variables("pg","p_");
$userID2 = $p_userID2;

//DELETE DATA FROM DB
$stmt = $db->prepare("DELETE FROM users WHERE userID=:userID2");
$stmt->execute(array(':userID2'=>$userID2));


//return
$goto = "index.php";
echo "<script> window.location.href = '$goto' </script>";

?>