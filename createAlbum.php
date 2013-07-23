<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

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

<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="../assets/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	
	<style type="text/css">
		
	</style>
	<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">


	</SCRIPT>
                   
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
    <div class='sectiontitle span8 offset1'>Create New Album</div>
    <br/>
      <div class='span6 offset1'>
			<form class='form-inline' method='post' action='writeAlbum.php' enctype="multipart/form-data" style='font-size:1.4em;'>
			<input type='hidden' name='albumID' value='<?php echo $albumID; ?>'/>
			<label for='album' class='vidlabel' style='display:inline;'>Name:</label>
			<input id='album' name='album' value='<?php echo $album; ?>' style="width:300px;"/>
			<br/><br/>
			
			<label for='description' class='vidlabel'>Description</label><br/>			
			<textarea id='description' name='description' style="width:400px;"><?php echo $description; ?></textarea><br/><br/>
			
   
		<input type='submit' value='CREATE NEW ALBUM' class='btn btn-success'/>
		&nbsp;&nbsp;&nbsp;
                <a href="index.php" class="btn">Cancel</a><br/>
		</form>
    </div>
    
    <div class="span4" style='font-size:1.2em;'>
      <strong>Album Properties</strong>
       <ul class='notes'>
       <li>To edit album properties select album from home page album listing.
       <li>Permissions can be set to "public", "PSU", or "restricted".</li>
       	<ul><li>Public can be viewed by all.</li>
       	<li>Restricted can be viewed by listed PSU ID's.</li>
       	<li>User list can be populated with a course roster by entering a Course ID#.</li>
       	</ul>
       <li>Add album cover image.</li>
      
       </ul>
      
		</div><!--close right column-->
    
    
    </div><!--close middlecontent-->
			
		
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>    
    <script src="assets/js/bootstrap.js"></script>
    

</body>
</html>
