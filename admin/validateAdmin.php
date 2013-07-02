<?php

//GET USER INFO

$stmt = $db->prepare("SELECT * FROM users WHERE userID=:userID");
$stmt->execute(array(':userID'=> $userID));
$row = $stmt->fetch();

		$firstname =  $row['firstname'];
		$lastname =  $row['lastname'];
		$name = $firstname . " " . $lastname;
		$role= $row['role'];

//CHECK FOR 'Admin' STATUS
if($role !='admin'){
	echo"<div style='text-align:center;'><br/><h3>You do not have access to this page.<br> Please contact the site course administrator if you think this in error.</h3></div>";
			exit();		
}


?>