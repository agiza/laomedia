<?php

//UNCOMMENT SECTION BELOW FOR USE WITH COSIGN/WEBACCESS

/*	
if(isset($_SERVER['REMOTE_USER'])){
	$userID = $_SERVER['REMOTE_USER'];
}else{
	echo"<div style='text-align:center;margin-top:30px;'><h3>Permisson Denied.</h3></div>";
	exit();
	}
*/	

/*
ASSIGN $userID BELOW FOR LOCAL INSTALLATION OR TO OVERRIDE WEBACCESS ASSIGNMENT
THIS CAN BE USED TO MASQUERADE AS ANOTHER USER WITHIN THE SOFTWARE
*/

$userID = "admin";//comment out this line when using Cosign
$userID = "srt142";

$userID=strtolower($userID);

?>