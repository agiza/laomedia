<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateInstructor.php";

include "playerConfig.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$mediaID = $p_vid;
if(!is_numeric($mediaID)){
	echo"<div style='width:640px;height:500px;margin:0 auto;text-align:center'> <h2>Invalid Request</div>";
	exit();
	}


//check for passed showpane variable
if(isset($p_showpane)){
	$showpane = $p_showpane;
	}


//GET VIDEO INFO
$stmt = $db->prepare("SELECT * FROM media WHERE mediaID=:mediaID");
$stmt->execute(array(':mediaID'=> $mediaID));
$row = $stmt->fetch();
$title = $row['title'];
$title = stripslashes($title);
$description = $row['description'];
$description = stripslashes($description);
$tags = $row['tags'];
$origfilename = $row['filename'];
$uploaddate = $row['uploaddate'];
$uploaddate = date('m/d/Y',$uploaddate);
$owner = $row['owner'];//userID of one who uploaded file
$type = $row['type'];//is multi bitrate available?
$permission = $row['permission'];//get permissions - public or limited?
$caption = $row['caption'];//are captions available?
$format = $row['format'];
$size = $row['size'];
$posterimage = $row['posterimage'];//poster image uploaded or use default?
$viewcount = $row['viewcount'];

//set player size for this page
if($format=="standard"){$width="480"; $height="360";}
if($format=="wide"){$width="640"; $height="360";}
if($type=="audio"){$width="300"; $height="26";}

//set player image
include "functions/playerimage.php";

//current album assignment   		
$albumsearch = $db->prepare("SELECT av.albumID,a.album,a.permission FROM albummedia av LEFT JOIN albums a ON av.albumID = a.albumID WHERE av.mediaID = :mediaID");
		$albumsearch->execute(array(':mediaID'=> $mediaID));
		$is_in_album = $albumsearch->rowCount();
		$row = $albumsearch->fetch(PDO::FETCH_ASSOC);      
		$currentalbum =  $row['album'];
		$albumPermission = $row['permission'];
		$currentAlbumID = $row['albumID'];


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

<!-- PLAYER CODE AND KEY -->
<script type='text/javascript' src='assets/jwplayer/jwplayer.js'></script>
<script type="text/javascript">jwplayer.key="<?php echo $playerKey; ?>";</script>

<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
//set variables from database for use in ajax calls
size = '<?php echo $size; ?>';
format = '<?php echo $format; ?>';
caption = '<?php echo $caption; ?>';
mediaID = <?php echo $mediaID; ?>;
showpane = '<?php echo $showpane; ?>';

</script>

<!--settingsAjax.js includes functions called to make changes to DB, uses above variables-->
<script src="assets/js/settingsAjax.js"></script>
                                     
</head>
<body>
<div class="container">
	<div class="row">
		<div id="header">
			<img id="PSUlogo" src="assets/img/logo.png" alt="Penn State Logo" />
			<a href="index.php"><img id="laoMedia" src="assets/img/headGraphic.png" alt="Liberal Arts Online Media" /></a>
		</div>
	</div>
    	
    <div id="middlecontent" class="row">

<?php // no file found
	if($stmt->rowCount() == 0){
		echo "<div style='width:640px;height:500px;margin:0 auto;text-align:center'> <h2>No media with this ID# was found.</div>";
	}else{
?>   

	 <div style="margin:0px auto;padding:0px;border:none;width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;">
	
	<div id='mediaspace'>[replaced by player]</div>
		
<script type="text/javascript">
jwplayer("mediaspace").setup({
	playlist: [{
		image:"<?php echo $playerimage;?>",
		title:"<?php echo $title;?>",
    	sources: [
    		<?php
    		if($type == 'multivid'){
    			echo '{file: "http://' . $server . $wowzaport . $videocontent . 'smil:' . $mediaID . '.smil/jwplayer.smil"},';
    			echo '{file: "http://' . $server . $wowzaport . $videocontent . 'mp4:'  . $mediaID . '_hi.mp4/playlist.m3u8"}';
    		}elseif($type == 'singlevid'){
    			echo '{file: "rtmp://' . $server . $wowzaport . $videocontent . 'mp4:' . $mediaID . '.mp4"},';
    			echo '{file: "http://' . $server . $wowzaport . $videocontent . 'mp4:' .$mediaID . '.mp4/playlist.m3u8"}';
    		}elseif($type == 'audio'){
    			echo'{file: "audio/' . $mediaID . '.mp3"}';
    		}
    		?>    		 
		]
		<?php if($caption != 'none'){ ?>
    	,
    	tracks: [
            {file:"captions/<?php echo $caption; ?>",
            	label: "English",
            	kind: "captions",
            	default: true }           
        	],
        	captions: {
        		back: true,
        		color:"ffffff",
        		fontsize: 15
    		}
    <?php } ?>
		}],
    	height: "100%",
    	width: "100%"  	
    	
	});

		</script>
	  </div>
	</div><!--close middlecontent-->
			
	<div id="middlecontent2" class="row" style='font-size:1.4em;'>
								
	<div class="tabbable span12">

  		<ul class="nav nav-tabs" id="tabdivs">
    		<li class="#pane1"><a href="#pane1" data-toggle="tab">Summary</a></li>
    		<li><a href="#pane2" data-toggle="tab">Album</a></li>
    		<li><a href="#pane3" data-toggle="tab">Permission</a></li>
    		<li><a href="#pane4" data-toggle="tab">Caption</a></li>
    		<li><a href="#pane5" data-toggle="tab">Poster Image</a></li>
    		<li><a href="#pane6" data-toggle="tab">Format/Size/Embed</a></li>
    		<li><a href="#pane7" data-toggle="tab"><span class="red">Delete</span></a></li>
  		</ul>
  
  <div class="tab-content">
    
    <!--SUMMARY-->
    <div id="pane1" class="tab-pane active" >
    <div class='span7'>
     	<form method='POST' action='writeSummary.php' style='font-size:1.4em;'>
     	
     		<input type='hidden' name='mediaID' value='<?php echo $mediaID; ?>'/>
     	
			<label for='title' class='vidlabel control-label'>Title: </label>			
			<input id='title' name='title' style="width:400px;" value='<?php echo htmlentities($title, ENT_QUOTES); ?>'/><br/><br/>
				
			<label for='description' class='vidlabel control-label'>Description</label>			
			<textarea id='description' name='description' style="width:400px;"><?php echo $description; ?></textarea><br/>
					
			<label for='tags' class='vidlabel control-label'>Tags:</label>
			<input id='tags' name='tags' style="width:400px;" value='<?php echo $tags; ?>'/>
			<br/>
			<span class='form-notes' style>Add some relevant keywords to make your video easier to find. (Separate your tags with commas.)</small>
			<br/><br/>
			
			<button type='submit' class="btn btn-success"/><i class="icon-plus icon-white"> </i> SUBMIT CHANGES</button><br/>
			
			
		</form>
	</div>
	
	<div>
		<span class='vidlabel'>MediaID #:</span> <?php echo $mediaID; ?><br/>
		<span class='vidlabel'>Uploaded by:</span> <?php echo $owner; ?><br/>
		<span class='vidlabel'>Upload date:</span> <?php echo $uploaddate; ?><br/>
		<span class='vidlabel'>Type:</span>
		<?php 
			switch($type){
				case "singlevid":
					echo "Single video";
					break;
				case "multivid":
					echo "Multi bitrate video";
					break;
				case "audio":
					echo "audio";
					break;
			}		
		?>
		<br/>
		<span class='vidlabel'>In album: </span>
		<?php if($currentalbum ==""){
			echo "no album";
			}else{
			echo "<a href='album.php?albumID=" . $currentAlbumID . "'>" . $currentalbum . "</a>";
			}
		?>
			<br/>
		<span class='vidlabel'>Caption file:</span> <?php echo $caption; ?><br/>
		<span class='vidlabel'>Format:</span> <?php echo $format; ?><br/>
		<span class='vidlabel'>Original Filename:</span> <?php echo $origfilename; ?><br/>
		<span class='vidlabel'>Views:</span> <?php echo $viewcount; ?><br/>
		<a href='media.php?id=<?php echo $mediaID; ?>' target='_blank'>Display Media Page</a>
	</div>
	<br/>		
    </div>
    
    
    <!--ALBUM-->
    <div id="pane2" class="tab-pane">
      <div class='span8' style="min-height:300px;">
      <p class='notes'>Select to include in an album. <br/>
      Media inherits permissions and poster image of album.<br/>
      Select 'No album' to remove from all albums.</p>
      <form id='albumform' method='post' action='edit/changeAlbum.php'>
      	<input type='hidden' name='mediaID' value='<?php echo $mediaID; ?>'/>
   		<table style='width:100%;'>
   		<tr>
   		
   		<?php
   		//no album option
		echo "<td><input type='radio' name='albumID' id='album0' value=0 onClick='changeAlbum()'";
   		if($is_in_album < 1){//check this box if media is not in album
   			echo " checked";
   			}
   		echo "> No album</td>";
   
		//list of all albums with radio buttons
		//mark checked if this video in album
		//oncheck of album submit() form
		$stmt = $db->prepare("SELECT * FROM albums ORDER BY album");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$i=2;
		foreach( $result as $row ) {
		echo "<td><input type='radio' name='albumID' id='album" . $i ."' value='" . $row['albumID'] . "' onClick='changeAlbum()'";
			if($row['album']==$currentalbum){
				echo "checked> ";
			}else{ 
				echo "> ";
			}
			echo $row['album'];
				if(($row['permission']=='restricted') || ($row['permission']=='psu')){
					echo "<i class='icon-lock'></i>";
				} 
			echo "</td>";
		 if (($i % 3) == 0){ echo"</tr><tr>";}
		$i++;
		}
?>
   		</tr></table>
   		</form>
   		</div>
   
   		<!--right column-->
   		<div>
   			<a href='createAlbum.php?mediaID=<?php echo $mediaID; ?>' class="btn btn-success"><i class="icon-plus icon-white"> </i> Create New Album</a><br/>
   			<br/>
   			<div class="notes">
   			<small>Note: Any video included in the PSU Only album will have the PSU login permission.</small>
   			</div>
   			<div id="albumResponse"></div>
   		</div>
    
    </div>
    
    
   
    <!--PERMISSION-->
    <div id="pane3" class="tab-pane">
	<div class="span11 notes">
      Permissions for media are inherited from album settings. Generally permissions should be set at the album level. Make changes here only if the media is not in an album or you wish to override the album permissions.
      
      <br/><br/>
    <?php
    //display if in an album 
    if($currentAlbumID > 0){
       echo "This media is included in the <span class='green'>" . $currentalbum . "</span> album with permissions set to <span class='green'>" . $albumPermission . "</span>.";
      }else{
      echo "This media is not in an album.";
      } 
      ?>
      <hr/>
      </div>
      
      <div class="span6">
       <form id="permissionForm" method='POST' action='edit/changePermission.php'>
       <input type='hidden' name='mediaID' value='<?php echo $mediaID; ?>'/>
       	Public <input type="radio" name="permission" id="public" value="public" 
    <?php if($permission=='public'){echo "checked='checked'";} ?>/>&nbsp;&nbsp;&nbsp;
    	PSU <input type="radio" name="permission" id="psu" value="psu" 
    <?php if($permission=='psu'){echo "checked='checked'";} ?>/>&nbsp;&nbsp;&nbsp;
    	Restricted <input type="radio" name="permission" id="restricted" value="restricted" <?php if($permission=='restricted'){echo "checked='checked'";} ?>/>&nbsp;&nbsp;&nbsp;
    	<?php
    	//display album button if in an album 
    	if($currentAlbumID > 0){?>
    	Album <input type="radio" name="permission" id="album" value="album" <?php if($permission=='album'){echo "checked='checked'";} ?>/>&nbsp;&nbsp;&nbsp;
    	<?php } ?>
       Hide <input type="radio" name="permission" id="hide" value="hidden" <?php if($permission=='hidden'){echo "checked='checked'";} ?>/><br/><br/>
       <label for='useraccessID' class='vidlabel'>If restricted add PSU user ID's separated by commas.</label>
       <textarea id='useraccessID' name='useraccessID' style='width:400px;'> </textarea><br/>
       <label for='add' style="display:inline;" class='vidlabel'>Add to list: </label>
       <input id='add' type='radio' name='overwrite' value='add' checked="checked">
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       <label for='overwrite' style="display:inline;" class='vidlabel'>Overwrite existing: </label>
       <input id='overwrite' type='radio' name='overwrite' value='overwrite'><br/><br/>
       <a href="#" class="btn" onClick="changePermission()";/>Save Changes</a>
       </form>
    
       </div>
       
       <!--right column-->
   		<div class="span5">
   		
   			<small class='vidlabel'>
       <ul>
       <li>Permission can be set to "public", "PSU", "restricted", or "hide" and will override album settings.</li>
       <li>Public can be viewed by all.</li>
       <li>PSU can be viewed by all who can login through WebAccess.</li>
       <li>Restricted can be viewed by listed PSU ID's.</li>
       <li>Album inherits permissions from album setting.</li>
       <li>Hide blocks viewing regardless of album settings.</li>
       </ul>
       </small> 
    	</div>
    	<div id="permissionResponse" class="row span11">
    	<?php
    	if($permission=='restricted'){
    	echo "Current access ID's:<br/>";
	$stmt = $db->prepare("SELECT userID FROM mediapermission WHERE mediaID = :mediaID");
			$stmt->execute(array(':mediaID'=> $mediaID));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {      
				echo $row['userID'] . ", ";
			}
		}
		?>	
    	</div>
    	<br/><br/> <br/><br/> 
  </div><!--close permission tab-->
    
    
    <!--CAPTION-->
    <div id="pane4" class="tab-pane">
    <div class="span6">
    <div id="iscaptionavail">
    <?php
    	if($caption==0){
    	echo"<p>Currently no caption file is available.</p>";
    	}else{
    	echo "<p>A caption file has been added. <br/>This file can be overwritten with a newly uploaded file if changes are needed.</p>";
    	}
    ?>
      </div>
      <br/>
      <form id="caption" action="edit/uploadCaptionFile.php" method="POST" enctype="multipart/form-data">
     	 	<input type='hidden' name='mediaID' value='<?php echo $mediaID; ?>'/>
      
            <label for="selectfile" class='vidlabel'>Upload File</label>
            <input type="file" name="file" id="selectfile"/><br/><br/>
            <button type="submit" value="UPLOAD CAPTION FILE" class="btn btn-success"/><i class='icon-plus icon-white'></i> UPLOAD CAPTION FILE</button>
        
        </form>
     </div>
     
     <div class="span4 offset1">
     <small>
     <strong class='vidlabel'>Caption File Type</strong><br/>
     Uploaded caption files must have the extension .srt or .vtt. These are the most common timestamped files used for captioning.
     </small>
     <br/><br/>
     	<div id="captionResponse" style="color:red;">
     		<?php
     		if($caption != 0){
     			echo "<btn class='btn  btn-danger' onClick= 'deleteCaption();'><i class='icon-remove icon-white'></i> DELETE CAPTION FILE</button>";
     		}
     		?>
     	</div>
     </div>
     
     
                  
    </div><!--close caption tab-->
    
    <!--POSTER IMAGE-->
    <div id="pane5" class="tab-pane">
    <?php
    if($type == 'audio'){ ?>
    <p>Audio files do not use title images.</p>
    <?php }else{ ?>
    <div class="span5">
    <?php	
      	if($posterimage==0){
    	echo"<p>Currently this video uses the default or album poster image.</p>";
    	}else{
    	echo"<img src='playerimage/thumbs/" . $mediaID . ".jpg' align='left'/>";
    	echo "<p>Currently a custom image is available. This file can be overwritten with a newly uploaded file if changes are needed.</p>";
    	}
     ?> 	
      	<form id="posterimage" action="edit/writePosterimage.php" method="POST" enctype="multipart/form-data">
     	 	<input type='hidden' name='mediaID' value='<?php echo $mediaID; ?>'/>
      
            <label for="selectimage" class='vidlabel'>Select File</label>
            <input type="file" name="image_file" id="selectimage"/><br/><br/>
            <button type="submit" class="btn btn-success"/><i class='icon-plus icon-white'></i> UPLOAD IMAGE FILE</button>
            <br/><br/>
            <small class='vidlabel'>Note: File must be .jpeg or .jpg file type.</small>
        
        </form>
        
        </div>
        
    	<div class="span5 offset1">
     		<small class="notes">
     		<strong class='vidlabel'>Image Size</strong><br/>
     The uploaded image should be the same aspect ratio as the video.<br/><br/> Standard format video aspect ratio is 4:3. <br/>720(w) X 540(h) is an optimal standard format size.<br/><br/> Wide format aspect ratio is 16:9.<br/> 960(w) X 540(h) is an optimal wide format size.
     		</small><br/><br/>
     <?php
     if($posterimage==1){		
     		echo "<a href='edit/deleteVideoImage.php?mediaID=" . $mediaID . "' class='btn btn-danger'>Revert to Default Image</a>";
     		}
     	?>
     	</div>
     	
     <?php } //close conditional ?>
    </div>
    
    <!--FORMAT/SIZE-->
    <div id="pane6" class="tab-pane">
    <?php
    if($type == 'audio'){ ?>
    
    	<p>Audio files have only one format/size.</p>
   
    	<p>Copy and Paste</p>
    	<textarea style='width:500px;'><iframe src='http://<?php echo $server; ?>/mediaframe.php?id=<?php echo $mediaID; ?> ' height='<?php echo $height; ?>px' width='<?php echo $width; ?>px'></iframe>
    	</textarea>
    
    <?php }else{ ?>
    
    <form id="formatSize" method="post">
     <strong>The current format is:</strong><br/>
    Standard <input type="radio" name="format" id="standard" value="standard" onClick="changeFormat('standard')";
    <?php if($format=='standard'){echo "checked='checked'";} ?>/>&nbsp;&nbsp;&nbsp;
    Wide <input type="radio" name="format" id="wide" value="wide" onClick="changeFormat('wide')"; <?php if($format=='wide'){echo "checked='checked'";} ?>/>
    <br/><br/>
    
    <strong>The current size is:</strong><br/>
    Large<input type="radio" name="size" id="lgsize" value="lg" onClick="changeSize('lg')";
    <?php if($size=='lg'){echo "checked='checked'";} ?>/>&nbsp;&nbsp;&nbsp;
    Medium <input type="radio" name="size" id="medsize" value="med" onClick="changeSize('med')"; <?php if($size=='med'){echo "checked='checked'";} ?>/>&nbsp;&nbsp;&nbsp;
    Small <input type="radio" name="size" id="smsize" value="sm" onClick="changeSize('sm')"; <?php if($size=='sm'){echo "checked='checked'";} ?>/>
    </form>
    <br/>
    <strong>Embed code:</strong><br/>
    <div id='formatResponse'>
    <p>Copy and Paste</p>
    <textarea style='width:500px;'><iframe src='http://<?php echo $server; ?>/mediaframe.php?id=<?php echo $mediaID; ?> ' height='<?php echo $height; ?>px' width='<?php echo $width; ?>px'></iframe></textarea>
    
    </div>
    
     <?php } ?>
    </div>

    
    
    <div id="pane7" class="tab-pane">
    This will delete video and all associated data.
    <a href="edit/deleteMedia.php?mediaID=<?php echo $mediaID; ?>" class="btn btn-danger"> <i class="icon-remove icon-white"></i> DELETE THIS MEDIA</a>
    </div>
    
  			</div><!-- /.tab-content -->
		</div><!-- /.tabbable -->
	</div> 


<?php }//close no video conditional ?>		
		</div><!--middlecontent-->
		
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>        
    <script src="assets/js/bootstrap.js"></script>
    <script>
    	//show pane of passed variable
    	var pane = 'tabdivs a[href="#' +showpane+ '"]';
    	$('#' + pane).tab('show');
    </script>

</body>
</html>
