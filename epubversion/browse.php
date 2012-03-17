<?php
// ----------------------------------------------------------------------
// Copyright (c) 2011 by Kirstyn Amanda Fox (GeekBrat@Gmail.Com)
// Based on eFiction 3.x Recomendations Module
// Copyright (c) 2007 by Tammy Keefer
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

//if(!defined("_CHARSET")) exit( );

if(file_exists("modules/epubversion/languages/{$language}.php")) include_once("modules/epubversion/languages/{$language}.php");
else include_once("modules/epubversion/languages/en.php");

	$storyquery = "";
	$output .= "<div id='pagetitle'>"._EPUBBROWSE."</div>\n";
	$output .= build_alphalinks("browse.php?$terms&amp;", $let);
	if(isMEMBER) $output .= "<div class='respond'><a href='modules/epubversion/manage.php'>"._EPUBMANAGE."</a></div>";	
	unset($numrows);
	$classes = array( );
	$epubquery = array( );
	if(!empty($charid)) {
		$chars = array( );
		foreach($charid as $c) {
			if(empty($c)) continue;
			$chars[] = "FIND_IN_SET('$c', charid) > 0";
		}
		$epubquery[] = implode(" OR ", $chars);
	}
	if(isset($catid) && count($catid) > 0) {
		$categories = array( );
		// Get the recursive list.
		foreach($catid as $cat) {
			if($cat == "false" || empty($cat)) continue;
			$categories = array_merge($categories, recurseCategories($cat));
		}
		// Now format the SQL
		$cats = array( );
		foreach($categories as $cat) {
			$cats[] = "FIND_IN_SET($cat, catid) > 0 ";
		}
		// Now implode the SQL list
		if(!empty($cats)) $epubquery[] = "(".implode(" OR ", $cats).")";
	}
	if(!empty($summary)) $reccquery[] = "summary LIKE '%$summary%'";
	if(!empty($title)) $epubquery[] = "summary LIKE '%$title%'";
	if($classin) {
		foreach($classin as $class) {
			if(empty($class)) continue;
			$epubquery[] = "FIND_IN_SET($class, classes) > 0";
		}
	}
	if($classex) {
		foreach($classex as $class) {
			$epubquery[] = "FIND_IN_SET($class, classes) = 0";
		}
	}
	if(isset($_GET['classid']) && isNumber($_GET['classid'])) {
		$classid = $_GET['classid'];
		$epubquery[] = "FIND_IN_SET($classid, classes) > 0";
	}

	if(!empty($rid)) $epubquery[] = "FIND_IN_SET(rid, '".(is_array($rid) ? implode(",", $rid) : $rid)."') > 0";
	if(isset($_REQUEST['complete'])) {
		if($_REQUEST['complete'] == 1) $epubquery[] = "completed = '1'";
		else if(empty($_REQUEST['complete'])) $epubquery[] = "completed = '0'";
	}

	if($let == _OTHER) $epubquery[] = "title REGEXP '^[^a-z]'";
	else if(!empty($let)) $epubquery[] = "title LIKE '$let%'";

	$epubquery[] = "validated > 0";
	$epubquery = "epub = '1' AND ".implode(' AND ', $epubquery)." ORDER BY ".($defaultsort == 1 ? "date DESC" : "title ASC");
	$list = dbquery("SELECT sid FROM ".TABLEPREFIX."fanfiction_stories".(!empty($epubquery) ? " WHERE $epubquery" : ""));
	$numrows = dbnumrows($list);
	if($numrows > 0) {
		if(file_exists("./$skindir/epubs.tpl")) $epubslist = new TemplatePower("./$skindir/epubs.tpl");
		else $epubslist = new TemplatePower(_BASEDIR."modules/epubversion/default_tpls/epubs.tpl");
		$epubslist->prepare( );
		$epubs = dbquery("SELECT epub.*, "._PENNAMEFIELD." as penname, UNIX_TIMESTAMP(epub.date) as date FROM ".TABLEPREFIX."fanfiction_stories as epub LEFT JOIN "._AUTHORTABLE." ON epub.uid = "._UIDFIELD." WHERE epub.validated > 0 ".(!empty($epubquery) ? " AND $epubquery" : "")." LIMIT $offset, $itemsperpage");
		$count = 0;
		while($epub = dbassoc($epubs)) {
			include("epubsblock.php");
		}
		$tpl->newBlock("listings");
		$tpl->assign("pagelinks", $epubslist->getOutputContent( ));
		$tpl->newBlock("listings");
		if($numrows > $itemsperpage) $tpl->assign("pagelinks", build_pagelinks("browse.php?$terms&amp;", $numrows, $offset));
		$tpl->gotoBlock("_ROOT");
	}
	else $output .= write_message(_NORESULTS);
	unset($chars, $cList, $cats);
?>
