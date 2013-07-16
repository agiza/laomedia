LAO Media Server
Stephen Tuttle 2013
Penn State University
College of the Liberal Arts 
Outreach and Online Education Department

This software is an interface for the Wowza media server (wowza.com). The Wowza software is installed as per their instructions on a Linux Red Hat OS box with Apache, MySQL, and PHP. It is also integrated with Penn State's Cosign/WebAccess. This allows seamless restricted access viewing of videos when used with our Plone and Angel CMS's.

Set Up

In MySQL create a database with a name of your choosing.

Edit file dbconnect.php -
	enter name of database, login user, and password
	
Edit admin/createtables.php -
	uncomment table creation script lines
	run script from browser
	recomment or remove this file as safeguard
	
	Initial setup creates an 'admin' user. Go to admin/index.php to enter new admin and contributers.

Edit validateUser.php
	This file is included in every page. Make changes here to implement Cosign/Webaccess or assign your new admin userID for a local installation.


Edit playerConfig.php
	This file is included in all pages that display the JW player. It includes variables that are likely unique to your setup. Change as necessary.
		
	In our setup an alias for the Wowza 'content' directory has been placed in the same directory as the interface files(/var/www/html).

	
Use

http://[serverhost]/index.html displays 'Permission Denied' to catch http access.

https://[serverhost]/index.php is 'home' page. Access screened for admin or contributor roles.

index.php
	Upload 3 different file types - single video(.mp4, H.264), multi bitrate(3 files, lg, med, sm of different bitrate compressions for auto detect delivery), and audio files(.mp3).
	Display most watched media. Counter is triggered and stored on player start.
	Search media by title, tag, or description
	Album listing. Create New Album.
	
Upload Media
	Upon successful upload user is directed to settings.php. This page contains all controls for editing metadata for each media item. Add to album, edit permissions, add caption file, add custom poster image, edit display size and embed code, delete media.
	
Display Media
	Four pages are used to display: two for a display page (media.php & mediasecure.php) and two for embedding in an iframe (mediaframe.php & mediaframesecure.php). In both cases the file is checked as an http call(media.php or mediaframe.php) with a passed mediaID#.
If the video has 'public' permission/access the video is shown. If the video has 'restricted' or 'PSU' access the page is redirected to https://mediasecure.php or mediaframesecure.php where Cosign/Webaccess requires login and the userID is obtained. 
This userID is then checked against database for permissions to show or deny viewing.

Application Roles
	There are 3 roles assigned to users of this application; admin, contributor, and instructor. These are registered by an admin on admin/index.php.
	Admin has access to all pages and files.
	Contributor has access to all pages except admin directory.
	Instructor has access to only the album.php(listing of media in album) and the settings.php page. However they only have the ability to change permissions on that page.
	
Viewing Permissions
	Viewing permission can be set to Public, PSU, or Restricted. They can be set on an album and all media in that album inherit those permissions or can be set on the media item itself which overrides album permissions. 