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

if(!defined("_CHARSET")) exit( );

if(file_exists(_BASEDIR."modules/epubversion/languages/{$language}.php")) include_once(_BASEDIR."modules/epubversion/languages/{$language}.php");
else include_once(_BASEDIR."modules/epubversion/languages/en.php");
if(empty($type) || $type != "epubbrowse") { // If you're already browsing recommendations no sense putting them in the other results.
$query = array( ); $chars = array( );
if(isset($charid)) {
	if(!is_array($charid)) $charid = array($charid);
	if(count($charid) > 0) {
		foreach($charid as $c) {
			if(empty($c)) continue;
			$chars[] = "FIND_IN_SET('$c', charid) > 0";
		}
		if(count($chars)) $query[] = implode(" OR ", $chars);
	}
}
if(is_array($catid) && count($catid) > 0) {
	$categories = array( );
	// Get the recursive list.
	foreach($catid as $cat) {
		if($cat == "false" || empty($cat) || $cat == -1) continue;
		$categories = array_merge($categories, recurseCategories($cat));
	}
	// Now format the SQL
	$cats = array( );
	foreach($categories as $cat) {
		$cats[] = "FIND_IN_SET($cat, catid) > 0 ";
	}
	// Now implode the SQL list
	if(!empty($cats)) $query[] = "(".implode(" OR ", $cats).")";
}
if($searchterm) {
	if($searchtype == "penname") $query[] = "author LIKE '%$searchterm%'";
	if($searchtype == "title") $query[] = "title LIKE '%$searchterm%'";
	if($searchtype == "summary") $query[] = "summary LIKE '%$searchterm%'";
}
if(isset($classin)) {
	$cList = array( );
	foreach($classin as $cl) {
		$cList[] = "FIND_IN_SET('$cl', classes) > 0";
	}
	if(count($cList) > 0) $query[] = "(".implode(" OR ", $cList).")";		
}
if(!empty($rid)) $query[] = findclause("rid", $rid);
if(!empty($uid)) $query[] = "1 = 0";
if(count($query) > 0) {
	$where = "WHERE ".implode(" AND epub = '1' AND ", $query);
	$query = dbquery("SELECT * FROM ".TABLEPREFIX."fanfiction_stories $where");
	$reccount = dbnumrows($query);
	if($reccount > 0) $otherresults[] = "<a href='browse.php?type=epubbrowse&amp;$terms'>$reccount "._EPUBS."</a>";
}
	unset($where, $query, $chars, $cats);
}

?>
