<?php

/*
This file is included in all pages that display or upload video.
The player source path includes variables defined here.
The path looks like this:
file: "http://' . $server . $wowzaport . $videocontent .
These variable values will differ on different server installations
Make changes as needed.
*/

//SERVER HOST
$server="mamp/laomedia";
//production
//$server="server address";

//DEFAULT WOWZA PORT
$wowzaport = ":1935/";

//DEFAULT PLAYER APPLICATION
$videocontent = "vod/";

//UPLOAD VIDEO PATH
//local install
$uploadVideoPath = "content/";

//production
//$uploadVideoPath = "/usr/local/WowzaMediaServer/content/";

//UPLOAD AUDIO PATH
$uploadAudioPath = "content/";

//JWPLAYER REQUIRES KEY
$playerKey = "qjdF661dSqQzJ9K4WWi0cAirBCRoQV5ioxGtEg==";



