<?php
//FORCE HTTPS
/*COMMENT OUT THIS REDIRECT FOR LOCAL INSTALLATION
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);   
}
*/

//VALIDATE USER - returns $userID
//this include file will block any without webaccess login
include "validateUser.php";

include "dbconnect.php";

include "playerConfig.php";

//IMPORT VARIABLE MEDIA ID#
$mediaID = $_GET['id'];

if(!is_numeric($mediaID)){
	echo "<div style='width:300px;margin:50 auto;font-size:1.5em;'>Invalid media ID.</div>";
	exit();
	}

$albumAllow = 1; //set default album permission

//IS VIDEO IN ALBUM WITH RESTRICTED ACCESS?
$stmt = $db->prepare("SELECT albummedia.albumID, albums.album, albums.permission FROM albummedia LEFT JOIN albums ON albummedia.albumID = albums.albumID WHERE albummedia.mediaID = :mediaID");
			$stmt->execute(array(':mediaID'=> $mediaID));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$albumID =  $row['albumID'];
				//IF ALBUM PERMISSION RESTRICTED 
				if($row['permission'] == 'restricted'){
					$albumAllow=0;//set flag to restricted
					//DOES THIS USER HAVE PERMISSION?
					$stmt2 = $db->prepare("SELECT userID FROM albumpermission WHERE albumID=:albumID AND userID=:userID");
					$stmt2->execute(array(':albumID'=> $albumID, ':userID'=>$userID));
					$row2 = $stmt2->fetch();
					if($stmt2->rowCount() > 0){
						//this user has permission
						$albumAllow=1;					
					}
				}
			
//GET VIDEO INFO
$stmt = $db->prepare("SELECT * FROM media WHERE mediaID=:mediaID");
$stmt->execute(array(':mediaID'=> $mediaID));
$row = $stmt->fetch();
$title = $row['title'];
$type = $row['type'];//is multi bitrate available?
$permission = $row['permission'];//get permissions - public or limited?
$caption = $row['caption'];//are captions available?
$format = $row['format'];
$size = $row['size'];
$posterimage = $row['posterimage'];//poster image uploaded or use default?
$viewcount = $row['viewcount'] + 1;

//CHECK VIDEO PERMISSIONS
if(($permission == 'album') && ($albumAllow == 0)){
	echo"<div style='text-align:center;margin-top:150px;'>This video has restricted viewing access.</div>";
	exit();
}elseif($permission == 'hidden'){
	echo"<div style='text-align:center;margin-top:150px;'>This video has restricted viewing access.</div>";
	exit();
}elseif($permission == 'restricted'){
	//DOES THIS USER HAVE PERMISSION?
	$stmt = $db->prepare("SELECT userID FROM mediapermission WHERE mediaID=:mediaID AND userID=:userID");
	$stmt->execute(array(':mediaID'=> $mediaID, ':userID'=>$userID));
		$row2 = $stmt->fetch();
		if($stmt->rowCount() == 0){
		echo"<div style='text-align:center;margin-top:150px;'>This video has restricted viewing access.</div>";
		exit();				
		}
}


	
//set player size
include "functions/playersize.php";

//set player image
include "functions/playerimage.php";

$db=null;

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
	.offscreen {
		position: absolute;
		right: 101%;
		overflow: hidden;
	}
	</style>

<!-- START OF THE PLAYER EMBEDDING-->
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

	<div id="middlecontent2" class="row">
	<div style="margin:0px auto;padding:0px;border:none;width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;">
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
  					data: dataString, 
  					success: function(data) {		
  						document.getElementById('message').innerHTML=data;
  					}   				
   		 		});      

     		});
			
	</script>

   
	</div>
	
	<!--offscreen play button for screen readers -->
		<div class="offscreen">
			<a href="#"  onclick='jwplayer().play()'>Start or Pause Video Playback</a>
		</div>

</div>
<script src="../assets/js/jquery.js"></script> 
</body>
</html>

