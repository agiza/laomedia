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
	
