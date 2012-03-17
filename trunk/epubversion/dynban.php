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

// Set the content-type 
header("Content-type: image/png");

// Create an image
$im = imagecreatetruecolor(400, 30);

// Create some colors using previous image. It makes it easier to refrence these later.
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);


// Define the path to your background image and font. These can and will be made dynamic.
     $pnggraph=_BASEDIR.'modules/epubversion/banners/INVIS-Banner.png';
     $font = $fullpath.'sigbanner/fonts/arial.ttf';
     $ifont = 7;
     $tfont = 6;

	list($width, $height, $type, $attr) = getimagesize($pnggraph);

function bmiddle($height, $txt, $ifont){
	$fheight = ImageFontHeight($ifont);
	$theight = $fheight;
	$middle = ceil(($height/3)+($height/3));
	return $middle;
}

function tmiddle($height, $txt, $ifont){
	$fheight = ImageFontHeight($ifont);
	$theight = $fheight;
	$middle = ceil(($height/3)-($height/3));
	return $middle;
}

function middle($height, $txt, $ifont){
	$fheight = ImageFontHeight($ifont);
	$theight = $fheight;
	$middle = ceil($height/3);
	return $middle;
}

function center($width, $txt, $ifont){
	$fwidth = ImageFontWidth($ifont);
	$twidth = $fwidth * strlen($txt);
	$center = ceil(($width - $twidth) / 2);
	return $center;
}

// Start creating your image
     $image = '';
           $image = imagecreatefrompng ($pnggraph);

// This is where I was stuck for so long. Alpha blending needs to be set FALSE for GD to preserve the Alpha transparencies of the background image
     ImageSaveAlpha($image, true);
     ImageAlphaBlending($image, false);

$f_n = 1;
$s_n = 1;

// add your text to your image.

	$image_string = ImageString($image, $ifont, center($width, $settings['epubtagline'], $ifont)-$f_n, middle($height, $settings['epubtagline'], $ifont)-$f_n, $settings['epubtagline'], $grey);
	$image_string = ImageString($image, $ifont, center($width, $settings['epubtagline'], $ifont), middle($height, $settings['epubtagline'], $ifont), $settings['epubtagline'], $black);
	$image_string = ImageString($image, $ifont, center($width, $settings['epubtagline'], $ifont)+$f_n, middle($height, $settings['epubtagline'], $ifont)-$s_n, $settings['epubtagline'], $white);
	$image_string = ImageString($image, $tfont, center($width, $settings['epubsitename'], $tfont)-$f_n, tmiddle($height, $settings['epubsitename'], $tfont)-$f_n, $settings['epubsitename'], $grey);
	$image_string = ImageString($image, $tfont, center($width, $settings['epubsitename'], $tfont), tmiddle($height, $settings['epubsitename'], $tfont), $settings['epubsitename'], $black);
	$image_string = ImageString($image, $tfont, center($width, $settings['epubsitename'], $tfont)+$f_n, tmiddle($height, $settings['epubsitename'], $tfont)-$s_n, $settings['epubsitename'], $white);
	$image_string = ImageString($image, $tfont, center($width, $settings['epuburl'], $tfont)-$f_n, bmiddle($height, $settings['epuburl'], $tfont)-$f_n, $settings['epuburl'], $grey);
	$image_string = ImageString($image, $tfont, center($width, $settings['epuburl'], $tfont), bmiddle($height, $settings['epuburl'], $tfont), $settings['epuburl'], $black);
	$image_string = ImageString($image, $tfont, center($width, $settings['epuburl'], $tfont)+$f_n, bmiddle($height, $settings['epuburl'], $tfont)-$s_n, $settings['epuburl'], $white);

// Display your Finished image as a PNG
     ImagePNG($image);
?>

