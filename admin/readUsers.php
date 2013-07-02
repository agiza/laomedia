<html>
<head>
<title>User Tables</title>
</head>
<body>

<?php

include "../validateUser.php";

//CONNECT TO DATABASE
include "../dbconnect.php";

//include "validateAdmin.php";

//Users TABLE
echo "<div style='float:left;margin-right:15px;'>";
echo"<b>Users</b>";
echo "<table border=1><tr><th>UserID</th><th>Firstname</th><th>Lastname</th><th>Role</th><th>Lastlogin</th></tr>";
    
$stmt = $db->prepare("SELECT * from users");
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>$row[userID]</td><td>$row[firstname]</td><td>$row[lastname]</td><td>$row[role]</td><td>$row[lastlogin]</td></tr>";
}

 echo "</table>\n"; 

echo "<br></div>";


//CourseGroupUser TABLE

echo"<b>CourseGroupUser</b>";   
 echo "<table border=1><tr><th>Course Name</th><th>Group Name</th><th>User ID</th></tr>";
    
foreach ($db_handle->query("SELECT * from CourseGroupUser") as $row){
        echo "<tr><td>$row[coursename]</td><td>$row[groupname]</td><td>$row[userID]</td></tr>";
}

 echo "</table>";

echo "<br>";



?>

</body>
</html>