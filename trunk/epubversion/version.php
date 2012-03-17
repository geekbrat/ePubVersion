<?php
/*
This file will be called by admin/modules.php and update.php to determine if 
the module version in the database is the current version of the module.  
The version number in this file will be the current version.
*/

if(!defined("_CHARSET")) exit( );

$moduleVersion = "1.3";
$moduleName = "ePubVersion";

$moduleDescription = "This module adds to the view story page an option to download an ePub version. It also adds an Administration panel, and the ability for Authors to Enable or Dissable ePub creation for each story.";
$moduleAuthor = "Kirstyn Amanda Fox";
$moduleAuthorEmail = "geekbrat@gmail.com";
$moduleWebsite = "http://storyportal.net/software/epub";


?>
