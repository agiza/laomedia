<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateInstructor.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$albumID = $p_albumID;

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
			<img id="PSUlogo" src="assets/img/PSUlogo.png" alt="Penn State Logo" />
			<a href="index.php"><img id="laoMedia" src="assets/img/laoMedia.png" alt="Liberal Arts Online Media" /></a>
		</div>
	</div>

    	
    <div id="middlecontent2" class="row">
    
    <?php
    $stmt = $db->prepare("SELECT * FROM albums WHERE albumID = $albumID");
		$stmt->execute();
		$row = $stmt->fetch();
		$album = stripslashes($row['album']); 
		$description = stripslashes($row['description']);
		$permission = $row['permission'];
	
	$stmt = $db->prepare("SELECT albummedia.albumID, media.mediaID, media.title, media.description,media.permission,media.caption,media.viewcount FROM albummedia LEFT JOIN media ON albummedia.mediaID = media.mediaID WHERE albummedia.albumID = :albumID ORDER BY media.title ASC");
$stmt->execute(array(':albumID'=> $albumID));
$videocount = $stmt->rowCount(); 
$result = $stmt->fetchAll();
		
    ?>
    	<div class='span8 offset1'>
    	<br/>
    		<div class='sectiontitle' >Album: <?php echo $album; ?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php 
			if($videocount > 1){
				echo $videocount . " files.";
			}			
			if($videocount == 1){
				echo $videocount . " file.";
			}
			?>
			
			</div>
			<div class="notes indent"><?php echo $description; ?></div>
			<div class="notes indent">Permission: <?php echo $permission; ?></div>
			<div class="notes indent"><span class="red"># </span>indicates album permissions overridden.</div>
		</div>
		
		<div class='span3'>
		<br/>
		<?php
		//no album editing for instructors or of PSU albumID=1
		if($role != 'instructor'){
			if($albumID != 1){
				echo "<a href='edit/editAlbum.php?albumID=" . $albumID . "' class='btn btn-info'>Edit Album Properties</a>";
			}
		}
		?>
			
		</div>
					
		<br/><br/>
		
        
    <div class="row span10 offset1" style='margin-top:1em;'>
     <table class="table table-striped">
        <tr><th>ID #</th><th>Title</th><th>Description</th><th>Caption</th><th>Views</th></tr>
    <?php
  		foreach($result as $row){
			//echo $row['title'] . "<br/>";
		
   
		$uploaddate =  date('m/d/Y',$row['uploaddate']);
		
		 
			echo "<tr><td>" . $row['mediaID'] . "</td><td>";
			if($row['permission'] != 'album'){
				echo"<span class='red'># </span>";
				}
			echo "<a href='settings.php?vid=" . $row['mediaID'] . "'>" . $row['title'] . "</a></td><td>" . $row['description'] . "</td>";
			if($row['caption'] != 'none'){
				echo "<td>&nbsp;&nbsp; <i class='icon-ok'></i></td>";
			}else{
				echo "<td></td>";
			}
			echo "<td>" . $row['viewcount'] ."</td></tr>";
		}		        
    
    ?>
    
    </table>
   
    
    </div>
    
    </div>
    
    </div><!--close middlecontent-->
    
    
			
		
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>    
    <script src="assets/js/bootstrap.js"></script>
    

</body>
</html>
