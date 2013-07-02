<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "validateAdmin.php";

?>

<html>
<head>
    <meta charset="utf-8">
    <title>LiberalArtsOnline MEDIA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/laoMedia.css" rel="stylesheet">

<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="../assets/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	
	<style type="text/css">
		
	</style>

</head>
<body>
<div class="container">
	<div class="row">
		<div id="header">
			<img id="PSUlogo" src="../assets/img/PSUlogo.png" alt="Penn State Logo" />
			<a href="../index.php"><img id="laoMedia" src="../assets/img/laoMedia.png" alt="Liberal Arts Online Media" /></a>
		</div>
	</div>

    	
    <div id="middlecontent2" class="row">
    	
    	<div class="span10 offset1">
    	<div class='sectiontitle'>Manage Users</div><br/>
    	
			<div class="span6">

		<form  name="register" method="post"  action="writeNewUser.php">
			<div><strong>Register/Edit Admins/Contributors</strong></div>
			User ID: <input name="userID" type="text" size="20"/><br>
			Firstname: <input name="firstname" type="text" size="20"/><br>
			Lastname: <input name="lastname" type="text" size="20"/><br>
			Role: 
			<select name="role">
			<!--<option value="student">student</option>-->
			<option value="admin">admin</option>
			<option value="contrib">contributor</option>
			</select><br>
			<button class="btn btn-success" href="#" onclick="location.href='writeNewUser.php'">Register/Edit User</button>
			<br/>&bull; If user already exists this will update firstname, lastname, and role.
		</form>
		<strong>Current Users</strong><br/>
	Contributors: 
<?php
	$stmt = $db->prepare("SELECT * from users WHERE role='contrib'");
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $firstname = $row[firstname];
        $lastname  = $row[lastname]; 
        $name= $firstname . " " . $lastname;
        echo $name . " (" . $row['userID'] . "), ";
		}
	echo "<br/>Admins: ";		
	
	$stmt = $db->prepare("SELECT * from users WHERE role='admin'");
	$stmt->execute();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $firstname = $row[firstname];
        $lastname  = $row[lastname]; 
        $name= $firstname . " " . $lastname;
        echo $name . " (" . $row['userID'] . "), ";
	}

?>			
    	</div>
    	
    	
    		<div class="span3">
				<form  name="deleteUser" method="post" action="deleteUser.php">
				<strong>Delete User</strong><br/>
				User ID: <input name="userID2" type="text" size="20"/>&nbsp;&nbsp;
				<input type='submit' class="btn btn-danger" value="Delete User"/>
				</form>
			</div>
			
    </div><!--close middlecontent-->
			
		
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>    
    <script src="assets/js/bootstrap.js"></script>
    

</body>
</html>
