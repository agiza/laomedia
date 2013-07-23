<?php

echo"This file is run on set up of this software to set up initial tables in the database. 
Running this deletes all information in any existing tables. As a precaution comment tags have been inserted to prevent accidental deletion. If you are certain you want to run this file you must open the file in a text editor and delete the comment tags and save the file before running it from your browser.";


/*
UNCOMMENT SECTION BELOW TO CREATE TABLES
AFTER RUNNING THIS SCRIPT FROM BROWSER RE-COMMENT AS SAFEGUARD AGAINST DATA DELETION
OR REMOVE THIS FILE COMPLETELY 
*/


include "../dbconnect.php";

/*
//CREATE USERS TABLE
$dropTable = $db->prepare('DROP TABLE IF EXISTS users');
$dropTable->execute();

$createTable = $db->prepare('CREATE TABLE users(rowID int, userID varchar(8) primary key, firstname varchar(30), lastname varchar(30), role varchar(10), lastlogin varchar(30))');
$createTable->execute();

//create a PSU Only album with psu permission
$addAdmin = $db->prepare("INSERT INTO users(userID,role) VALUES('admin','admin')");
$addAdmin->execute();
	
echo"<h3>A new 'users' table has been created.</h3><br/>";


//CREATE media TABLE
//integer types will be a 1 or 0 for true or false
$dropTable = $db->prepare('DROP TABLE IF EXISTS media');
$dropTable->execute();

$createTable = $db->prepare('CREATE TABLE media (mediaID int primary key auto_increment, title varchar(50), description varchar(200), tags varchar(200), permission varchar(20), password varchar(20), filename varchar(50), uploaddate varchar(20), owner varchar(50), type varchar(20),format varchar(20), size varchar(10), posterimage integer, caption varchar(40), viewcount integer)');
$createTable->execute();

$alterTable = $db->exec("ALTER TABLE media auto_increment=1000");

echo"<h3>A new 'media' table has been created.</h3><br/>";


//CREATE mediapermission TABLE
//what userID's can view videos in this album
$dropTable = $db->prepare('DROP TABLE IF EXISTS mediapermission');
$dropTable->execute();

$createTable = $db->prepare('CREATE TABLE mediapermission (mediaID int index, userID varchar(10))');
$createTable->execute();

echo"<h3>A new 'mediapermission' table has been created.</h3><br/>";


//CREATE albums TABLE
//album title, description, permission, how many videos in album?
$dropTable = $db->prepare('DROP TABLE IF EXISTS albums');
$dropTable->execute();

$createTable = $db->prepare('CREATE TABLE albums (albumID int primary key auto_increment, album varchar(50) unique, description varchar(200), permission varchar(20), courseID varchar(30), posterimage integer)');
$createTable->execute();

//create a PSU Only album with psu permission
$stmt = $db->prepare("INSERT INTO albums(album,description,permission) VALUES(:album,:description,:permission)");
	$stmt->execute(array(':album' => 'PSU Only', ':description' => 'This album gives PSU login access permission to any video it contains.', ':permission' => 'psu'));

echo"<h3>A new 'albums' table has been created.</h3><br/>";


//CREATE albummedia TABLE
//what videos are in this album
$dropTable = $db->prepare('DROP TABLE IF EXISTS albummedia');
$dropTable->execute();

$createTable = $db->prepare('CREATE TABLE albummedia (albumID int index, mediaID integer)');
$createTable->execute();

echo"<h3>A new 'albummedia' table has been created.</h3><br/>";


//CREATE albumpermission TABLE
//what userID's can view videos in this album
$dropTable = $db->prepare('DROP TABLE IF EXISTS albumpermission');
$dropTable->execute();

$createTable = $db->prepare('CREATE TABLE albumpermission (albumID int index, userID varchar(10))');
$createTable->execute();

echo"<h3>A new 'albumpermission' table has been created.</h3><br/>";
*/
echo "<a href='index.php'>Go to Administration panel to manage users.</a>";

?>

