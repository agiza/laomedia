<?php
include "dbconnect.php";

include "playerConfig.php";

//IMPORT VARIABLE assessmentID
import_request_variables("pg","p_");

//passed video id#
$mediaID = $p_id;
if(!is_numeric($mediaID)){
	echo "<div style='width:300px;margin:50 auto;font-size:1.5em;'>Invalid media ID.</div>";
	exit();
	}


//IS VIDEO IN ALBUM WITH RESTRICTED ACCESS
$stmt = $db->prepare("SELECT albummedia.albumID, albums.album, albums.permission FROM albummedia LEFT JOIN albums ON albummedia.albumID = albums.albumID WHERE albummedia.mediaID = :mediaID");
			$stmt->execute(array(':mediaID'=> $mediaID));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//IF ALBUM PERMISSION RESTRICTED REDIRECT TO HTTPS
				if($row['permission'] != 'public'){
					header("Location: https://" . $server . "/mediaframesecure.php?id=" . $mediaID);
				}
			}


//GET VIDEO INFO
$stmt = $db->prepare("SELECT * FROM media WHERE mediaID=:mediaID");
$stmt->execute(array(':mediaID'=> $mediaID));
$row = $stmt->fetch();
$title = $row['title'];
$type = $row['type'];//is multi bitrate available?
$caption = $row['caption'];//are captions available?
$format = $row['format'];
$size = $row['size'];
$posterimage = $row['posterimage'];//poster image uploaded or use default?
$viewcount = $row['viewcount'] + 1;
	
//set player size
include "functions/playersize.php";

//set player image
include "functions/playerimage.php";
$db=null;

?>

<html>
<head>
<title>LAO Media</title>

<!-- START OF THE PLAYER EMBEDDING-->
<script type='text/javascript' src='assets/jwplayer/jwplayer.js'></script>
<script type="text/javascript">jwplayer.key="<?php echo $playerKey; ?>";</script>
                                     
</head>

<body>
<div id='mediaspace' style="margin:0px;padding:0px;border:none;"></div>
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

			//when viewer hits play store count to DB
			jwplayer("mediaspace").onPlay(function() {       
       			//alert("playing");
       			var mediaID = <?php echo $mediaID; ?>;
       			var viewcount = <?php echo $viewcount; ?>;
				var dataString = 'mediaID='+mediaID + '&viewcount=' + viewcount;
				$.ajax({
					type: "POST",  
 					 url: "updateViewcount.php",  
  					data: dataString   				
   		 		});      

     		});
    
			
	</script>

<script src="assets/js/jquery.js"></script>    

</body>
</html>

