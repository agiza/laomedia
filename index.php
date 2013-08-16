<?php
//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";

include "playerConfig.php";

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
	<!--<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">-->

<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="../assets/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	
	<style type="text/css">
		
	</style>

<!-- PLAYER CODE AND KEY -->
	<script type='text/javascript' src='assets/jwplayer/jwplayer.js'></script>
	<script type="text/javascript">jwplayer.key="<?php echo $playerKey; ?>";</script>

                                     
</head>
<body>
<div class="container">
	<div class="row">
		<div id="header">
			<img id="PSUlogo" src="assets/img/logo.png" alt="Penn State Logo" />
			<img id="laoMedia" src="assets/img/headGraphic.png" alt="Liberal Arts Online Media" />
		</div>
	</div>

    	
    <div id="middlecontent" class="row">
    
    <!--LEFT COLUMN-->
      <div class="span8">
     <a href="media.php?id=1024">Screencast Tour</a>
        <div style="font-size:1.5em;font-weight:600;text-align:center;margin:1em 0;">Media delivery in support of Penn State University's <br/>College of the Liberal Arts Online Courses.</div>
         
         <div class="span8" style="margin:2em 0 3em;">
        	<div class="span2 offset1">
         		<a href="uploadsingle.php" class="btn btn-success">
            	<i class="icon-arrow-up icon-white"></i>
            	<span>Upload Single Video File</span>     
        		</a>
        	</div>
       	 	<div class="span2">
         		<a href="uploadmulti.php" class="btn btn-success">
            	<i class="icon-arrow-up icon-white"></i>
            	<span>Upload Multi Bitrate Video Files</span>     
        		</a>
        	</div>
        	<div class="span2">
         		<a href="uploadaudio.php" class="btn btn-success">
            	<i class="icon-arrow-up icon-white"></i>
            	<span>Upload Audio File</span>     
        		</a>
        	</div>
        </div>
        
		<div style="margin-top:2em;">
          <div class='sectiontitle'>Search Our Media</div>
          <div class="notes">Search will look for a match of your text to any part of the searched criteria and is not case sensitive.</div><br/>
          <form class="form-inline" method="POST" action="search.php">
          <label for="searchfor">for a </label>&nbsp;
          <select id="searchfor" name="searchfor" class="span2">
          <option value="title" selected="yes">Title</option>
          <option value="tags">Tags</option>
          <option value="description">Description</option>
          </select>
          &nbsp;<label for="title"> that includes: </label>&nbsp;
    	  <input class="span3" id="matching" name="matching" placeholder="any text"> 
    	  &nbsp;
    	  <button type="submit" class="btn">GO</button>
          </form> 
          <a href="mediaListing.php?start=0" class="btn btn-info">View Complete Listing</a> 
          <br/><br/>      
        </div>
        
               
  
      
      
    </div><!--CLOSE LEFT COLUMN-->
      
      
	   <!--RIGHT COLUMN-->
	  <div class="span4">
		
		<?php
		//GET VIDEO INFO
		$stmt = $db->prepare("SELECT * FROM media ORDER BY viewcount DESC LIMIT 1");
		$stmt->execute();
		$row = $stmt->fetch();
		$mediaID= $row['mediaID'];
		$title = $row['title'];
		$title = stripslashes($title);
		$description = $row['description'];
		$tags = $row['tags'];
		$origfilename = $row['filename'];
		$uploaddate = $row['uploaddate'];
		$owner = $row['owner'];//userID of one who uploaded file
		$type = $row['type'];//is multi bitrate available?
		$permission = $row['permission'];//get permissions - public or limited?
		$caption = $row['caption'];//are captions available?
		$format = $row['format'];
		$size = $row['size'];
		$posterimage = $row['posterimage'];//poster image uploaded or use default?
		$viewcount = $row['viewcount'];
		
		
	//set player image
	include "functions/playerimage.php";
		
		//format player on this page
		if($format == 'standard'){
			$height = 225;
		}elseif($format == 'wide'){
			$height = 170;
		}elseif($type == 'audio'){
			$height = 26;
			}
			
		$width = "300px";
	?>
	
			
	<div style="margin:0px auto;padding:0px;border:none;width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;">
				
		
<div id='mediaspace' >[replaced by player]</div>
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
            {file:"captions/<?php echo $mediaID; ?>.srt",
            	label: "English",
            	kind: "captions",
            	default: true }           
        	],
        	
    <?php } ?>
		}],
    	height: "100%",
    	width: "100%",
    	captions: {
        		back: true,
        		color:"ffffff",
        		fontsize: 16
    		}
	});
		</script>
		</div><!--close video player box-->
		<div style="font-weight:600;">
		<span class='vidlabel'>Most Watched Video: </span><?php echo $viewcount; ?> views.<br/>
		<span class='vidlabel'>Title: </span><a href='settings.php?vid=
		<?php echo $mediaID . "'>" . $title; ?></a><br/>
		<span class='vidlabel'>Description: </span><?php echo $description; ?><br/>
		<span class='vidlabel'>Tags: </span><?php echo $tags; ?><br/>
		<span class='vidlabel'>Uploaded: </span><?php echo date('m/d/Y', $uploaddate); ?>
		
		</div>
	  </div>
	 
	   <div id="middlecontent2" class="row span11" style="margin-top:2em;">
	   <div style="float:right;"><a href="createAlbum.php" class="btn btn-small btn-success"><i class="icon-plus icon-white"></i> Create New Album</a></div>
        <div class='sectiontitle'>Albums</div>
        <div class="notes">Albums are collections of videos. They can include an unlimited number of videos and can be assigned restricted access. </div>
        <table id='albumlisting' style='width:100%;'>
   		<tr>
   		
        <?php
		//list of all albums
		
		$stmt = $db->prepare("SELECT * FROM albums ORDER BY album");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$i=1;
		foreach( $result as $row ) {
		echo "<td><a href='album.php?albumID=" . $row['albumID'] . "'>" . $row['album'] . "</a> ";
		if(($row['permission']=='restricted') || ($row['permission']=='psu')){
			echo "<i class='icon-lock'></i>";
		} 
		
		echo "</td>";
		 if (($i % 5) == 0){ echo"</tr><tr>";}
		$i++;
		}
?>
   </tr></table>
    
   
    
    </table> 
        </div>
	  
	</div><!--close middlecontent-->
			
		
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>    
    <script src="assets/js/bootstrap.js"></script>
    

</body>
</html>
