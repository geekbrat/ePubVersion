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

	if(!isset($count)) $count = 0;
	$epubslist->newBlock("epubs");
	$epubslist->assign("title", "<a href='"._BASEDIR."viewstory.php?sid=".$epub['sid']."'>".stripslashes($epub['title'])."</a>");
	$epubslist->assign("author", !empty($epub['uid']) ? "<a href='"._BASEDIR."viewuser.php?uid=".$epub['uid']."'>".$epub['penname']."</a>" : $epub['recname']);
	$epubslist->assign("summary", format_story($epub['summary']));
	$epubslist->assign("sid", $epub['sid']);
	$epubslist->assign("score", ratingpics($epub['rating']));
	$epubslist->assign("rating", $ratingslist[$epub['rid']]['name']);
	$allclasslist = "";
	$storyclasses = array( );
	if($epub['classes']) {
		foreach(explode(",", $epub['classes']) as $c) {
			if(isset($action) && $action == "printable") $storyclasses[$classlist["$c"]['type']][] = $classlist[$c]['name'];
			else $storyclasses[$classlist["$c"]['type']][] = "<a href='browse.php?type=class&amp;type_id=".$classlist["$c"]['type']."&amp;classid=$c'>".$classlist[$c]['name']."</a>";
		}
	}
	foreach($classtypelist as $num => $c) {
		if(isset($storyclasses[$num])) {
			$epubslist->assign($c['name'], implode(", ", $storyclasses[$num]));
			$allclasslist .= "<span class='label'>".$c['title'].": </span> ".implode(", ", $storyclasses[$num])."<br />";
		}
		else {
			$epubslist->assign($c['name'], _NONE);
			$allclasslist .= "<span class='label'>".$c['title'].": </span> "._NONE."<br />";
		}
	}		
	$epubslist->assign("oddeven", $count % 2 ? "odd" : "even");
	$epubslist->assign("classifications", $allclasslist);
	$epubslist->assign("characters", (!empty($epub['charid']) ? charlist($epub['charid']) : _NONE));
	$epubslist->assign("category",  $epub['catid'] == '-1' || !$epub['catid'] ? _NONE : catlist($epub['catid']));
	$epubslist->assign("completed"   , ($epub['completed'] ? _YES : _NO) );
	if($reviewsallowed) {
		$epubslist->assign("reviews", "<a href=\"reviews.php?type=ST&item=".$epub['sid']."\">"._REVIEWS."</a>");
		$epubslist->assign("numreviews", "<a href=\"reviews.php?type=ST&item=".$epub['sid']."\">".$epub['reviews']."</a>");
		$epubslist->assign("addreview", "[<a href=\"reviews.php?action=add&amp;type=ST&item=".$epub['sid']."\">"._SUBMITREVIEW."</a>]");
	}
	if(isMEMBER) {
		$epubslist->assign("dlepub", " [<a href=\"modules/epubversion/epubversion.php?sid=".$epub['sid']."&amp;chapter=ALL\">"._EPUBDL."</a> (".$epub['epubread']." Downloads)]");
		}
	$epubslist->assign("published" , date("$dateformat", $epub['date']) );
	if($recentdays) {
		$recent = time( ) - ($recentdays * 24 * 60 *60);
		if($epub['date'] > $recent) $epubslist->assign("new", isset($new) ? file_exists(_BASEDIR.$new) ? "<img src='$new' alt='"._NEW."'>" : $new : _NEW);
	}
	$adminlinks = "";
	$epubslist->assign("reportthis", "[<a href=\""._BASEDIR."contact.php?action=report&amp;url=browse.php?type=epub&sid=".$epub['sid']."\">"._REPORTTHIS."</a>]");
	$epubslist->assign("oddeven", ($count % 2 ? "odd" : "even"));
	if(isADMIN && uLEVEL < 4 || (USERUID == $epub['uid'] && !empty($epub['uid']))) $epubslist->assign("adminlinks", "<div class=\"adminoptions\"><span class='label'>".(isADMIN ? _ADMINOPTIONS : _OPTIONS).":</span> ".$adminlinks."</div>");
	$count++;

?>
