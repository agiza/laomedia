<?php

$mediaID=1006;

include "playerConfig.php";

$db=null;

?>

<html>
<head>
	<title>LAO Media</title>
	<style type="text/css">
		
	</style>

<!-- START OF THE PLAYER EMBEDDING-->
<script type='text/javascript' src='assets/jwplayer/jwplayer.js'></script>

<script type="text/javascript">jwplayer.key="<? echo $playerkey; ?>";</script>

                                     
</head>
<body>
<div id='mediaspace' style="margin:0px;padding:0px;border:none;"></div>

<script type="text/javascript">

	jwplayer("mediaspace").setup({
	playlist: [{
		image: "../vidframes/defaultWide.jpg",
		title:"<?php echo $title; ?>",
    	sources: [
    		<?php echo"echo '{file: "rtmp://' . $server . $wowzaport . $videocontent . 'mp4:' . $mediaID . '.mp4"}';"; ?>   
		],
		height: "360",
    	width: "640",
    	 rtmp: {bufferlength: 3      	
});
			
	</script>

<script src="assets/js/jquery.js"></script>    
</body>
</html>

