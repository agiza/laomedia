<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$start = $p_start;

//how many media files?		
	$stmt = $db->prepare("SELECT mediaID FROM media");
	$stmt->execute();
	$totalMedia = $stmt->rowCount('mediaID');
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
    <div class='sectiontitle'>&nbsp;Media Listing
    <div style="float:right;"><?php echo $totalMedia; ?> files.&nbsp;&nbsp;</div>
    </div>
    <br/>
        <table class="table table-striped">
        <tr><th>ID #</th><th>Title</th><th>Description</th><th>Tags</th><th><a href="mediaListingDate.php?start=0">Upload Date</a></th><th>Views</th></tr>
    <?php
		
	$stmt = $db->prepare("SELECT * FROM media ORDER BY title LIMIT $start,20");
		$stmt->execute();		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$uploaddate =  date('m/d/Y',$row['uploaddate']);
		$title = stripslashes($row['title']);
			echo "<tr><td>" . $row['mediaID'] . "</td><td><a href='settings.php?vid=" . $row['mediaID'] . "'>" . $title . "</a></td><td>" . $row['description'] . "</td><td>" . $row['tags'] . "</td><td>" . $uploaddate . "</td><td>" . $row['viewcount'] ."</td></tr>";
		}		        

   
    ?>
    
    </table>
    
    <?php
    //create page links
		$pages = ceil($totalMedia/20);//total of pages needed for links
		
    if($totalMedia > 20){
    	echo "<div style='text-align:center;'>Pages: ";
    	for($i=0;$i<$pages;$i++){
    		$start2=($i * 20);
    		if($start2 != $start){
    		echo "<a href='mediaListing.php?start=" . $start2 . "'>" . ($i + 1) . "&nbsp;&nbsp;</a>";
    		}else{//no link for page we're on
    			echo ($i + 1) . "&nbsp;&nbsp";
    		}
    	
    	}
    	echo "</div>";
    }
    ?>
    <br/>
    
    </div><!--close middlecontent-->
			
		
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>    
    <script src="assets/js/bootstrap.js"></script>
    

</body>
</html>
