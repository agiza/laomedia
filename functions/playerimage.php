<?php

//select playerimage
//if custom media image use that one
//elseif custom album image use that one
//else use default

	$stmt2 = $db->prepare("SELECT albummedia.albumID, albums.album, albums.posterimage FROM albummedia LEFT JOIN albums ON albummedia.albumID = albums.albumID WHERE albummedia.mediaID = :mediaID");
	$stmt2->execute(array(':mediaID'=> $mediaID));
	$row = $stmt2->fetch();
	if($posterimage==1){//video poster image
		$playerimage = "playerimage/" . $mediaID . ".jpg";
	}elseif($row['posterimage'] == 1){//album poster image
		$playerimage =  "playerimage/album" . $row['albumID'] . ".jpg";
	}else{
		if ($format=='wide'){
			if($size == 'lg'){$playerimage =  "playerimage/wideLg.jpg";}
			if($size == 'med'){$playerimage = "playerimage/wideMed.jpg";}
			if($size == 'sm'){$playerimage =  "playerimage/wideSm.jpg";}
		}
		if ($format=='standard'){
			if($size == 'lg'){$playerimage =  "playerimage/standardLg.jpg";}
			if($size == 'med'){$playerimage = "playerimage/standardMed.jpg";}
			if($size == 'sm'){$playerimage =  "playerimage/standardSm.jpg";}
		}
	}


