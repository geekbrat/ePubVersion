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

global $language;
global $epubanon;
global $epubrw;

if(file_exists(_BASEDIR."modules/epubversion/languages/{$language}.php")) include_once(_BASEDIR."modules/epubversion/languages/{$language}.php");
else include_once(_BASEDIR."modules/epubversion/languages/en.php");

if (!empty($stories['epub']) && $stories['epub'] == "1") {

	if(isMEMBER || $epubanon) {

	        $title_san = preg_replace("/ /", "_", $stories['title']);

		if($epubrw == "1") {
			$printepub = " [ <a href=\"modules/epubversion/epubs/".$stories['sid']."/all/$title_san.epub\">Download ePub</a> ] ";
		}
		else {
			$printepub = " [ <a href=\"modules/epubversion/epubversion.php?sid=".$stories['sid']."&amp;chapter=all\">Download ePub</a> ] ";
		}
		$epubcount = $stories['epubread'];
		$tpl->assign("printepub", $printepub);
		$tpl->assign("epubcount", $epubcount);
	}
}

?>
