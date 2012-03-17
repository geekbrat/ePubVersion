<?php
// ----------------------------------------------------------------------
// Copyright (c) 2010 by Kirstyn Amanda Fox
// Based on DisplayWorld for eFiction 3.0
// Copyright (c) 2005 by Tammy Keefer
// Valid HTML 4.01 Transitional
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


if(!defined("_CHARSET")) exit( );

if($current == "viewstory") {
	if(file_exists(_BASEDIR."modules/epubversion/languages/{$language}.php")) include_once(_BASEDIR."modules/epubversion/languages/{$language}.php");
	else include_once(_BASEDIR."modules/epubversion/languages/en.php");

	$epubs = dbquery("SELECT sid, epub FROM ".TABLEPREFIX."fanfiction_stories WHERE sid = '".$sid."'");
	$epub = dbassoc($epubs);
        $title_san = preg_replace("/ /", "_", $storyinfo['title']);
        $title_san = preg_replace("/\@/", "at", $title_san);
        $title_san = preg_replace("/\&/", "and", $title_san);
        $title_san = preg_replace("/\W/", "", $title_san);
	if (!empty($epub[epub]) && $epub[epub] == "1") {
		if(!isMEMBER && !$epubanon) accessDenied( );
			if($epubrw == "1") {
				if(!empty($chapter) && $chapters > 1) $printepub = "<img src='".(isset($epubicon) ? $epubicon : $icon)."' border='0' alt='"._EPUB."'> <a href=\"modules/epubversion/epubs/$sid/$chapter/$title_san.epub\">"._CHAPTER."</a> "._OR." <a href=\"modules/epubversion/epubs/$sid/all/$title_san.epub\">"._STORY."</a>";
				else $printepub = "<a href=\"modules/epubversion/epubs/$sid/1/$title_san.epub\"><img src='".(isset($epubicon) ? $epubicon :  $icon)."' border='0' alt='"._EPUB."'></a>";
			}
			else {
				if(!empty($chapter) && $chapters > 1) $printepub = "<img src='".(isset($epubicon) ? $epubicon : $icon)."' border='0' alt='"._EPUB."'> <a href=\"modules/epubversion/epubversion.php?sid=$sid&amp;chapter=$chapter\">"._CHAPTER."</a> "._OR." <a href=\"modules/epubversion/epubversion.php?sid=$sid&amp;chapter=all\">"._STORY."</a>";
				else $printepub = "<a href=\"modules/epubversion/epubversion.php?sid=$sid&amp;chapter=1\"><img src='".(isset($epubicon) ? $epubicon :  $icon)."' border='0' alt='"._EPUB."'></a>";
			}
			$tpl->assign("printepub", $printepub);
	
	}
}
?>
