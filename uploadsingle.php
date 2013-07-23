<?php

//VALIDATE USER - returns $userID
include "validateUser.php";

include "dbconnect.php";

include "validateContributor.php";
?>

<!DOCTYPE html>
<html lang="en">
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
	<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">

<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="../assets/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        
    
    <style>

    label {
  		display: inline;
  		font-size:1.5em;
	}
	#middlecontent{
		height:800px;
	}
	 	
    </style>


    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
     <script type="text/javascript">
     
      function fileSelected(thisfile) {
        var file = document.getElementById('fileToUpload' + thisfile).files[0];
        if (file) {
          var fileSize = 0;
          if (file.size > 1024 * 1024)
            fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
          else
            fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

          document.getElementById('fileName' + thisfile).innerHTML = 'Name: ' + file.name;
          document.getElementById('fileSize' + thisfile).innerHTML = 'Size: ' + fileSize;
        }
      }

   function uploadFile() {
  		
   		//Check required fields
   		var thisTitle = document.getElementById('title').value;
   			if(thisTitle == ""){ alert("Please enter required title"); return false;}
   		var uploadFile = document.getElementById('fileToUpload0').value;
   			if(uploadFile == ""){ alert("Please select a file to upload"); return false;}
   			
   		document.getElementById('displayprogress').style.display = 'block';
   		
   		var xhr = new XMLHttpRequest();
  		var fd = new FormData(document.getElementById('form1'));
  		
  		/* event listners */
  		xhr.upload.addEventListener("progress", uploadProgress, false);
  		xhr.addEventListener("load", uploadComplete, false);
  		xhr.addEventListener("error", uploadFailed, false);
  		xhr.addEventListener("abort", uploadCanceled, false);
 		/* Be sure to change the url below to the url of your upload server side script */
  		xhr.open("POST", "writeUploadSingle.php");
  		xhr.send(fd);
		}


      function uploadProgress(evt) {
        if (evt.lengthComputable) {
          var percentComplete = Math.round(evt.loaded * 100 / evt.total);
          document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
         document.getElementById('file').value = percentComplete.toString(); 
        }
        else {
          document.getElementById('progressNumber').innerHTML = 'unable to compute';
        }
      }	
		
      function uploadComplete(evt) {
        /* This event is raised when the server send back a response */
        //alert(evt.target.responseText);        
        //alert("all files have been sent");
        document.getElementById('displayprogress').style.display = 'none';
        document.getElementById('message').innerHTML = evt.target.responseText       
      }
      
      function uploadFailed(evt) {
        alert("There was an error attempting to upload the file.");
      }

      function uploadCanceled(evt) {
        alert("The upload has been canceled by the user or the browser dropped the connection.");
      }
    </script>

	</head>

<body>
	
	<div class="container">
		<div class="row">
			<div id="header">
			<img id="PSUlogo" src="assets/img/logo.png" alt="Penn State Logo" />
			<a href="index.php"><img id="laoMedia" src="assets/img/headGraphic.png" alt="Liberal Arts Online Media" /></a>
			</div>
		</div>
    	
    	<div id= "middlecontent" class="row">
    		
    		<div class="span7 offset1">
    		<h1>Upload Video</h1>
    		<br/>
    		<form id="form1" action="writeUploadSingle.php" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="index.php"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        		
    			<label for="title">Title: </label>
    			<input class="span4" type="text" id="title" name="title" placeholder="Title" required>
    			<br/>
    			
    			<fieldset>
				<legend>Format:</legend>
				<input type="radio" id="wide" name="format" value="wide" checked>				
				<label for="wide"> Wide</label>&nbsp;&nbsp;&nbsp;
    			<input type="radio" id="standard" name="format" value="standard" >
    			<label for="format"> Standard</label>
    			</fieldset>
    			<br/>
    			
        		<div class="fileupload-buttonbar">         
                	<div class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <label for="fileToUpload0">Add File</label>
                    <input type="file" name="files" id="fileToUpload0" onchange="fileSelected(0);"/>
                </div>
                <div>
                <span id="fileName0"></span>&nbsp;&nbsp;&nbsp;
    			<span id="fileSize0"></span>
    			</div>
                
              
                <br/><br/>
               
                <input type="button" class="btn btn-primary start" onclick="uploadFile()" value="Upload" />&nbsp;&nbsp;&nbsp;
                <a href="index.php" class="btn">Cancel</a>
               
              <br/><br/>
              
              	<div id="displayprogress" style="display:none;">
                <span id="progressNumber">0%</span><br/>
                 <progress id="file" max="100" value="1" style="width:400px;">
				</progress>
				</div> 
				<br/>
 
  			<div id="message"></div>
                           
        </div>

		</form>
    	</div><!--leftcolumn-->
    		
    	<div class="span4">
    		<div class="well" style="margin:1em;"><h3>Guidelines</h3>
			<ul>
			<li>All form fields required.</li>
			<li>Videos must be in the .mp4 or .m4v (Apple's version) file type and use H264 codec.</li>
			</ul>
</div>  
    		</div><!--rightcolumn-->
		</div><!--middlecontent-->
    </div> <!-- /container -->
    
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>  

  </body>
</html>
