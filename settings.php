<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

include "playerConfig.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

$mediaID = $p_vid;


//GET VIDEO INFO
$stmt = $db->prepare("SELECT * FROM media WHERE mediaID=:mediaID");
$stmt->execute(array(':mediaID'=> $mediaID));
$row = $stmt->fetch();
$title = $row['title'];
$description = $row['description'];
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
if($type=="audio"){$width="300"; $height="80";}

//set player image
include "functions/playerimage.php";


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
</script>

<!--settingsAjax.js includes functions called to make changes to DB, uses above variables-->
<script src="assets/js/settingsAjax.js"></script>
                                     
</head>
<body>
<div class="container">
	<div class="row">
		<div id="header">
			<img id="PSUlogo" src="assets/img/PSUlogo.png" alt="Penn State Logo" />
			<a href="index.php"><img id="laoMedia" src="assets/img/laoMedia.png" alt="Liberal Arts Online Media" /></a>
		</div>
	</div>
    	
    <div id="middlecontent" class="row">

<?php // no file found
	if($stmt->rowCount() == 0){
		echo "<div style='width:640px;height:500px;margin:0 auto;text-align:center'> <h2>No video with this ID# was found.</div>";
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
    		<li class="active"><a href="#pane1" data-toggle="tab">Summary</a></li>
    		<li><a href="#pane2" data-toggle="tab">Album</a></li>
    		<li><a href="#pane3" data-toggle="tab">Permission</a></li>
    		<li><a href="#pane4" data-toggle="tab">Caption</a></li>
    		<li><a href="#pane5" data-toggle="tab">Title Image</a></li>
    		<li><a href="#pane6" data-toggle="tab">Format/Size/Embed</a></li>
    		<li><a href="#pane7" data-toggle="tab"><span style="color:red;">Delete</span></a></li>
  		</ul>
  
  <div class="tab-content">
    
    <!--SUMMARY-->
    <div id="pane1" class="tab-pane active" >
    <div class='span7'>
     	<form method='POST' action='writeSummary.php' style='font-size:1.4em;'>
     	
     		<input type='hidden' name='mediaID' value='<?php echo $mediaID; ?>'/>
     	
			<label for='title' class='vidlabel control-label'>Title: </label>			
			<input id='title' name='title' style="width:400px;" value='<?php echo $title; ?>'/><br/><br/>
				
			<label for='description' class='vidlabel control-label'>Description</label>			
			<textarea id='description' name='description' style="width:400px;"><?php echo $description; ?></textarea><br/>
					
			<label for='tags' class='vidlabel control-label'>Tags:</label>
			<input id='tags' name='tags' style="width:400px;" value='<?php echo $tags; ?>'/>
			<br/>
			<span class='form-notes' style>Add some relevant keywords to make your video easier to find. (Separate your tags with commas, please.)</small>
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
      <p class='notes'>Select to include in an album. <br/>Select 'No album' to remove from all albums.</p>
      <form>
   		<table style='width:100%;'>
   		<tr>
   		
   		<?php
   		//current album assignments - place in array
   		$currentalbums[]=array();
   		$stmt = $db->prepare("SELECT av.albumID,a.album FROM albummedia av LEFT JOIN albums a
	ON av.albumID = a.albumID WHERE av.mediaID = :mediaID");
			$stmt->execute(array(':mediaID'=> $mediaID));
			$row_count = $stmt->rowCount();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {      
				$currentalbums[] =  $row['album'];
				if($row['album'] == 'PSU Only'){$psualbum = 'yes';}
			}
			
		echo "<td><input type='radio' name='album' id='album0' value=0 onClick='changeAlbum(0)'";
   		if($row_count < 1){
   			echo " checked";
   			}
   		echo "> No album</td>";
   
		//list of all albums with radio buttons
		//mark checked if this video in album
		//onclick() ajax call to make DB change
		$stmt = $db->prepare("SELECT * FROM albums ORDER BY album");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$i=2;
		foreach( $result as $row ) {
		echo "<td><input type='radio' name='album' id='album" . $i ."' value='" . $row['albumID'] . "' onClick='changeAlbum(" . $row['albumID'] . ")'";
			if(in_array($row['album'],$currentalbums)){
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
	<div class="span8">
      Restricted access permissions are set at the album level. Create an album and assign the desired permissions. Add media to that album.
      <br/><br/>
       <small class='vidlabel'>
       <ul>
       <li>Permission on an album can be set to "public", "PSU", or "restricted".</li>
       <li>Public can be viewed by all.</li>
       <li>PSU can be viewed by all who can login through WebAccess.</li>
       <li>Restricted can be viewed by listed PSU ID's.</li>
       <li>Media is "public" by default.</li>
       </ul>
       </small> 
       </div>
       
       <!--right column-->
   		<div class="span3">
   		
   			<a href='createAlbum.php?mediaID=<?php echo $mediaID; ?>' class="btn btn-success"><i class="icon-plus icon-white"> </i> Create New Album</a>	
    	</div>
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
     Uploaded caption files must have the extension .srt. This is a common timestamped file used for captioning.
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
    
    <!--TITLE IMAGE-->
    <div id="pane5" class="tab-pane">
    <?php
    if($type == 'audio'){ ?>
    <p>Audio files do not use title images.</p>
    <?php }else{ ?>
    <div class="span5">
    <?php	
      	if($posterimage==0){
    	echo"<p>Currently this video uses the default background image.</p>";
    	}else{
    	echo"<img src='playerimage/thumbs/" . $mediaID . ".jpg' align='left'/>";
    	echo "<p>Currently a custom image is available. This file can be overwritten with a newly uploaded file if changes are needed.</p>";
    	}
     ?> 	
      	<form id="posterimage" action="edit/writePosterimage.php" method="POST" enctype="multipart/form-data">
     	 	<input type='hidden' name='mediaID' value='<?php echo $mediaID; ?>'/>
      
            <label for="selectimage" class='vidlabel'>Upload File</label>
            <input type="file" name="image_file" id="selectimage"/><br/><br/>
            <button type="submit" class="btn btn-success"/><i class='icon-plus icon-white'></i> UPLOAD IMAGE FILE</button>
            <br/><br/>
            <small class='vidlabel'>Note: File must be .jpeg or .jpg file type.</small>
        
        </form>
        
        </div>
        
    	<div class="span3 offset1">
     		<small>
     		<strong class='vidlabel'>Image Size</strong><br/>
     The uploaded image should be the same size as the video. Standard format video is 480(w) X 360(h). Wide format video is 640(w) X 360(h).
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
    

</body>
</html>
