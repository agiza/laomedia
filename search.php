<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$searchfor = $p_searchfor;
$matching = $p_matching;

?>

<html>
<head>
    <meta charset="utf-8">
    <title>LiberalArtsOnline MEDIA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/laoMedia.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/jquery.fileupload-ui.css">
	<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">

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
			<img id="PSUlogo" src="assets/img/logo.png" alt="Penn State Logo" />
			<a href="index.php"><img id="laoMedia" src="assets/img/headGraphic.png" alt="Liberal Arts Online Media" /></a>
		</div>
	</div>

    	
    <div id="middlecontent2" class="row">
    <br/>
    <div class='sectiontitle'>&nbsp;&nbsp;Search Results</div>
     <br/>
    <table class="table table-striped">
    <tr><th>Media ID#</th> <th>Title</th> <th>Description</th> <th>Tags</th> <th>Filename</th> <th>Upload Date</th> <th>Type</th> <th>Views</th></tr>
    <?php   
     $stmt = $db->prepare("SELECT * FROM media WHERE $searchfor LIKE '%$matching%'");
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$uploaddate =  date('m/d/Y',$row['uploaddate']);
			$type = $row['type'];
		switch($type){
				case "singlevid":
					$type = "Single video";
					break;
				case "multivid":
					$type = "Multi bitrate video";
					break;
				case "audio":
					$type = "audio";
					break;
			}				
		
			echo "<tr><td>" . "<a href='settings.php?vid=" . $row['mediaID'] . "'>" . $row['mediaID'] . "</a></td><td>" . $row['title']  . "</td><td>" . $row['description'] . "</td><td>" . $row['tags'] . "</td><td>" . $row['filename']  . "</td><td>" . $uploaddate . "</td><td>" . $type . "</td><td>" . $row['viewcount'] ."</td></tr>";
		
		}
	
		?>
    
    	</table>
    </div><!--close middlecontent-->
			
		
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>    
    <script src="assets/js/bootstrap.js"></script>
    

</body>
</html>
