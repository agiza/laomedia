//FORMAT AJAX
	function changeFormat(newFormat){
		format = newFormat;
		var dataString = 'mediaID='+ mediaID + '&format=' + format + '&size=' + size;
		$.ajax({
			type: "POST",  
 			 url: "edit/changeFormatSize.php",  
  			data: dataString,  
  			success: function(data) {		
  				document.getElementById('formatResponse').innerHTML=data;
  			}   			
   		 });      
		
	return false;  			
	}
	
//SIZE AJAX
	function changeSize(newSize){
		size = newSize;	
		var dataString = 'mediaID='+mediaID + '&size=' + size + '&format=' + format;
		$.ajax({
			type: "POST",  
 			 url: "edit/changeFormatSize.php",  
  			data: dataString,  
  			success: function(data) {		
  				document.getElementById('formatResponse').innerHTML=data;
  			}   			
   		 });      
	return false;  		
	}
	
//ALBUM AJAX
	function changeAlbum(albumID){
		document.getElementById('albumResponse').innerHTML="";
		var dataString = 'mediaID='+mediaID + '&albumID=' + albumID;
		$.ajax({
			type: "POST",  
 			 url: "edit/changeAlbum.php",  
  			data: dataString,  
  			success: function(data) {		
  				document.getElementById('albumResponse').innerHTML=data;
  			}   			
   		 });      
	return false;  		
	
	}
	
//PERMISSION AJAX
	function changePermission(){
		//var dataString = $("#permissionForm").serialize();
		var dataString = 'mediaID='+mediaID;
		$.ajax({
			type: "POST",  
 			 url: "changePermission.php",  
  			data: dataString,  
  			success: function(data) {		
  				document.getElementById('permissionResponse').innerHTML=data;
  			}   			
   		 });      
	return false;  		
	
	}

//DELETE CAPTION AJAX
	function deleteCaption(){
		var dataString = 'mediaID='+mediaID + '&caption=' + caption;
		$.ajax({
			type: "POST",  
 			 url: "edit/deleteCaption.php",  
  			data: dataString,  
  			success: function(data) {		
  				document.getElementById('captionResponse').innerHTML=data;
  				document.getElementById('iscaptionavail').innerHTML= "<p>Currently no caption file is available.</p>";
  			}   			
   		 });      
	return false;  		
	
	}
