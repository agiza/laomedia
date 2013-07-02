<?php

include "../validateUser.php";

include "../dbconnect.php";

//include "validateAdmin.php";

$userID2 = $_POST['userID'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$role = $_POST['role'];
$lastlogin=date("Y-m-d h:i");


//IS THIS USER ALREADY REGISTERED
$stmt = $db->prepare("SELECT userID FROM users WHERE userID=:userID2");
$stmt->execute(array(':userID2'=> $userID2));
$row_count = $stmt->rowCount();


if($row_count == 0){//NOT REGISTERED - INSERT NEW
	$stmt = $db->prepare("INSERT INTO users (userID, firstname,lastname,role)VALUES (:userID2,:firstname,:lastname,:role)");
	$stmt->execute(array(':userID2' => $userID2,  ':firstname'=>$firstname,':lastname'=>$lastname,':role'=>$role));

	}else{//REGISTERED - JUST UPDATE NAME, ROLE

	$stmt = $db->prepare("UPDATE users SET firstname=:firstname,lastname=:lastname, role=:role WHERE userID = :userID2");
	$stmt->execute(array(':userID2' => $userID2,  ':firstname'=>$firstname,':lastname'=>$lastname,':role'=>$role));
}


//GO TO index.php
$goto = "index.php";
echo "<script> window.location.href = '$goto' </script>";


?>
