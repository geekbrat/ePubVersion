<?php
// ----------------------------------------------------------------------
// Copyright (c) 2011 by Kirstyn Amanda Fox
// http://storyportal.net/software/epub
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

if(file_exists(_BASEDIR."languages/{$language}.php")) require_once (_BASEDIR."languages/{$language}.php");

else require_once (_BASEDIR."languages/en.php");

$settingsresults = dbquery("SELECT * FROM ".$settingsprefix."fanfiction_settings WHERE sitekey = '".$sitekey."'");
$settings = dbassoc($settingsresults);

foreach($settings as $var => $val) {
        $$var = stripslashes($val);
}

$debug = 0;
define("TABLEPREFIX", $tableprefix);
define("STORIESPATH", $storiespath);
include_once(_BASEDIR."includes/queries.php");

$sid = isset($_GET['sid']) ? $_GET['sid'] : false;
$size = isset($_GET['size']) ? $_GET['size'] : "100";

$covres = dbquery("SELECT sid, epubcover FROM ".TABLEPREFIX."fanfiction_stories WHERE sid = '".$sid."'");
$cover = dbassoc($covres);
foreach($cover as $var => $val) {
	$$var = stripslashes($val);
}

if (isset($cover['epubcover'])) {
	$pnggraph=_BASEDIR.$cover['epubcover'];
}
else {
	$pnggraph=_BASEDIR."stories/1/images/FlowerGirls-Cover-1-S.gif";
}

$imagefile=basename($pnggraph);

$thumbfile=$imagefile."-".$size.".thumb";

$thumbdir=_BASEDIR."modules/epubversion/thumbs/";

if (file_exists($thumbdir.$thumbfile)) {

	$imagesize = getimagesize($thumbdir.$thumbfile);
	$mime = $imagesize['mime'];
	$thumbnail = file_get_contents($thumbdir.$thumbfile);
	header("Content-type: ".$mime);
	echo ($thumbnail);
}

else {

	list($width, $height, $type, $attr) = getimagesize($pnggraph);
	$imagesize = getimagesize($pnggraph);
	$mime = $imagesize['mime'];
	$image = '';

	$ht=$height; 
	$wd=$width; 

	if($width>$size){ 
	    $diff = $width-$size; 
	    $percnt_reduced = (($diff/$width)*100); 
	    $ht = $height-(($percnt_reduced*$height)/100); 
	    $wd= $width-$diff; 
	} 

	if($height>$size){ 
	    $diff = $height-$size; 
	    $percnt_reduced = (($diff/$height)*100); 
	    $wd = $width-(($percnt_reduced*$width)/100); 
	    $ht= $height-$diff; 
	} 

	$new_width=$wd;
	$new_height=$ht;

	if ($type=="3") {
		$image = imagecreatefrompng ($pnggraph);
		ImageSaveAlpha($image, true);
		ImageAlphaBlending($image, true);
		$tmp_img = imagecreatetruecolor( $new_width, $new_height );
		ImageSaveAlpha($tmp_img, true);
		ImageAlphaBlending($tmp_img, true);
		$trans_colour = imagecolorallocatealpha($image, 0, 0, 0, 127);
		imagefill($tmp_img, 0, 0, $trans_colour);
		imagecopyresampled( $tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		header("Content-type: ".$mime);
		ImagePNG($tmp_img, $thumbdir.$thumbfile);
		ImagePNG($tmp_img);
		imagedestroy($tmp_img);
	}

	if ($type=="2") {
		$image = imagecreatefromjpeg ($pnggraph);
		$tmp_img = imagecreatetruecolor( $new_width, $new_height );
		imagecopyresized( $tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		header("Content-type: ".$mime);
		ImageJPEG($tmp_img, $thumbdir.$thumbfile);
		ImageJPEG($tmp_img);
		imagedestroy($tmp_img);
	}

	if ($type=="1") {
		$image = imagecreatefromgif ($pnggraph);
		$tmp_img = imagecreatetruecolor( $new_width, $new_height );
		$black = imagecolorallocate($tmp_img, 0, 0, 0);
		imagecolortransparent($tmp_img, $black);
		imagefill($tmp_img, 0, 0, $black);
		imagecopyresized( $tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		header("Content-type: ".$mime);
		ImageGIF($tmp_img, $thumbdir.$thumbfile);
		ImageGIF($tmp_img);
		imagedestroy($tmp_img);
	}
}
?>

