<?php
//VALIDATE USER - returns $userID
include "../validateUser.php";

include "../dbconnect.php";

include "../validateContributor.php";

//IMPORT VARIABLES
import_request_variables("pg","p_");

$albumID = $p_albumID;

 $stmt = $db->prepare("SELECT * FROM albums WHERE albumID = $albumID");
		$stmt->execute();
		$row = $stmt->fetch();
		$album = stripslashes($row['album']); 
		$description = stripslashes($row['description']);
		$permission = $row['permission'];
		$courseID = $row['courseID'];
		$posterimage = $row['posterimage'];

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
	<link rel="../stylesheet" href="assets/css/jquery.fileupload-ui.css">
	<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="../assets/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	
	<style type="text/css">
		
	</style>
	<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
	//CALLED BY ONCLICK ON FORMAT RADIO BUTTON
	function changePermission(){	
		if(document.getElementById('perm_restricted').checked) {
			document.getElementById('restrictedOptions').className='unhidden';
		}
		if(document.getElementById('perm_psu').checked) {
			document.getElementById('restrictedOptions').className='hidden';
		}
		if(document.getElementById('perm_public').checked) {
			document.getElementById('restrictedOptions').className='hidden';
		} 
 	return;	
	}


	</SCRIPT>
                   
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
    
    <br/>
    
    <div class='sectiontitle span9 offset1'>
    Edit <?php echo $album; ?> Album Properties</div>
    <div class="span2">
    <a href="../album.php?albumID=<?php echo $albumID; ?>" class="btn btn-small">Return to album listing</a></div>
    <br/><br/>
      <div class='span6 offset1'>
			<form class='form-inline albumForms' method='post' action='writeEditAlbum.php'>
			<input type='hidden' name='albumID' value='<?php echo $albumID; ?>'/>
			<label for='album' class='vidlabel' style='display:inline;'>Name:</label>
			<input id='album' name='album' value='<?php echo $album; ?>' style="width:300px;"/>
			<br/><br/>
			
			<label for='description' class='vidlabel'>Description</label><br/>			
			<textarea id='description' name='description' style="width:400px;"><?php echo $description; ?></textarea><br/><br/>
			
			<label for='perm_public' class='vidlabel'>Public</label>
			<input type="radio" name="permission" id="perm_public" value="public" onClick="changePermission()"
			<?php if($permission == 'public'){ echo "checked='checked'";} ?>/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    		<label for='perm_public' class='vidlabel'>PSU</label>
			<input type="radio" name="permission" id="perm_psu" value="psu" onClick="changePermission()"
			<?php if($permission == 'psu'){ echo "checked='checked'";} ?>/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <label for='perm_restricted' class='vidlabel'>Restricted</label> 
    <input type="radio" name="permission" id="perm_restricted" value="restricted" onClick="changePermission();"
    <?php if($permission == 'restricted'){ echo "checked='checked'";} ?>
    />
    <br/><br/>
    
    <!--DISPLAY IF RESTRICTED CHECKED-->
    <div id="restrictedOptions" 
    <?php 
    	if($permission != 'restricted'){ 
    		echo "class='hidden'>";
    	}else{ 
    		echo "class='unhidden'>";
    	}
    ?>
    	<legend>If Restricted...</legend>	
    	<label for='courseID' class='vidlabel' style='display:inline;'>Course ID#</label>
		<input id='courseID' name='courseID' style='width:300px;' value='<?php echo $courseID; ?>'/>
		<div class='notes'>The permission list will be populated with the course roster.</div>
		
		<br/>
       <label for='useraccessID' class='vidlabel'>Or add PSU user ID's separated by commas.</label>
       <textarea id='useraccessID' name='useraccessID' style='width:400px;'> </textarea><br/>
       <label for='add' style="display:inline;" class='vidlabel'>Add to list: </label>
       <input id='add' type='radio' name='overwrite' value='add' checked="checked">
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       <label for='overwrite' style="display:inline;" class='vidlabel'>Overwrite existing: </label>
       <input id='overwrite' type='radio' name='overwrite' value='overwrite'>
    	<br/><br/>
    </div>
    <br/>
		
		
		<input type='submit' value='SUBMIT CHANGES' class='btn btn-success'/>
		&nbsp;&nbsp;&nbsp;
                <a href="../album.php?albumID=<?php echo $albumID; ?>" class="btn">Cancel</a><br/>
		</form>
<br/>
<!--UPLOAD IMAGE-->
<legend>Poster Image for this Album</legend>
		<form class="albumForms" method="post" enctype="multipart/form-data" action="uploadAlbumImage.php">
		<input type='hidden' name='albumID' value='<?php echo $albumID; ?>'/>
		<?php
		if($posterimage==1){
			echo "<img src='../playerimage/thumbs/album" . $albumID . ".jpg' style='float:left;margin-right:.3em;'>";
			echo "<span class='notes'> Current image.<br/> Can be overwritten with upload.</span><br/>";
			}
		?>
		 <label for="selectimage" class='vidlabel'>Select File</label>
            <input type="file" name="image_file" id="selectimage"/><br/>
            <small class='notes'>File must be .jpeg or .jpg file type.<br/>
            </small>
            <br/>
            <button type='submit' class='btn btn-success'/><i class='icon-plus icon-white'></i> UPLOAD IMAGE</button>
		&nbsp;&nbsp;&nbsp; 
		<?php
    if($posterimage==1){
    echo"<a href='deleteAlbumImage.php?albumID=" . $albumID . "' class='btn btn-danger btn-small'> <i class='icon-remove icon-white'></i> DELETE ALBUM IMAGE</a>
    <br/><br/>";
    }
    ?>           
            </form>
            

		 <br/>
    <a href="deleteAlbum.php?albumID=<?php echo $albumID; ?>" class="btn btn-danger btn-small"> <i class="icon-remove icon-white"></i> DELETE THIS ALBUM</a>
    <br/>This will delete this album and all associated data. All videos that were assigned to  this album will remain available.
    <br/><br/><br/>
    
    </div>
    
    <div class="span4" style='font-size:1.2em;'>
      <strong>Album Permissions</strong>
       <ul class='notes'>
       <li>Permission can be set to "public", "PSU", or "restricted".</li>
       <li>Public can be viewed by all.</li>
       <li>PSU can be viewed by all who can login through WebAccess.</li>
       <li>Restricted can be viewed by listed PSU ID's.</li>
       <li>User list can be populated with a course roster by entering a Course ID#.</li>
       <li>Media in album inherit these permissions unless overridden.</li>
       <li><a href="../assets/img/PermissionsChart.jpg" target="_blank">Permissions Flowchart</a></li>
       </ul>
       <strong>Album Image</strong>
       <ul class='notes'>
       <li>Uploaded image will be the poster image for all videos in this album unless overridden at the video level.</li>
       </ul>
       
       <?php
       if($permission == 'restricted'){
        	echo "<strong>Current Permission List</strong>";
        	echo "<div class='notes smaller' >";
        	$stmt = $db->prepare("SELECT userID FROM albumpermission WHERE albumID = :albumID");
			$stmt->execute(array(':albumID'=> $albumID));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {      
			echo $row['userID'] . ", ";
			}
			echo "</div>";
		}		   
        
        ?>
      
		</div><!--close right column-->
    
    <br/><br/>
    </div><!--close middlecontent-->
			
		
    </div> <!-- /container -->
    
    <script src="../assets/js/jquery.js"></script>    
    <script src="../assets/js/bootstrap.js"></script>
    

</body>
</html>
