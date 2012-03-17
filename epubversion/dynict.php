<?php
// ----------------------------------------------------------------------
// Copyright (c) 2011 by Kirstyn Amanda Fox
// http://storyportal.net/software/epub
// Valid HTML 4.01 Transitional
// Based on "Challenges" by Tammy Keefer
// http://hugosnebula.com/
// Based on eFiction 1.1
// Copyright (C) 2003 by Rebecca Smallwood.
// http://efiction.sourceforge.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

// Locate config.php and set the basedir path
$folder_level = "";
while (!file_exists($folder_level."header.php")) { $folder_level .= "../"; }
if(!defined("_BASEDIR")) define("_BASEDIR", $folder_level);
@include_once(_BASEDIR."config.php");
$output = "";
$settingsresults = dbquery("SELECT * FROM ".$settingsprefix."fanfiction_settings WHERE sitekey = '".$sitekey."'");
$settings = dbassoc($settingsresults);
foreach($settings as $var => $val) {
        $$var = stripslashes($val);
}
$debug = 0;
define("TABLEPREFIX", $tableprefix);
define("STORIESPATH", $storiespath);
include_once(_BASEDIR."includes/queries.php");

	$pnggraph=_BASEDIR.$settings['epubicon'];
	list($width, $height, $type, $attr) = getimagesize($pnggraph);

echo "Width = ".$width."\n";
echo "Height = ".$height."\n";
echo "Type = ".$type."\n";
echo "Attr = ".$attr."\n";

if ($type="1") {
	ehco "Bite GIF";
}

?>
