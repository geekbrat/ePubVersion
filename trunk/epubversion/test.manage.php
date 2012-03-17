<?php
// ----------------------------------------------------------------------
// Copyright (C) Kirstyn Amanda Fox
// http://storyportal.net/software/epub
// Based on eFiction 3.x 
// Copyright (c) 2007 by Tammy Keefer
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

$current = "epubversion";

include ("../../header.php");

if(file_exists( "$skindir/default.tpl")) $tpl = new TemplatePower("$skindir/default.tpl" );
else $tpl = new TemplatePower(_BASEDIR."default_tpls/default.tpl");
if(file_exists("$skindir/listings.tpl")) $tpl->assignInclude( "listings", "$skindir/listings.tpl" );
else $tpl->assignInclude( "listings", _BASEDIR."default_tpls/listings.tpl" );
$tpl->assignInclude( "header", "$skindir/header.tpl" );
$tpl->assignInclude( "footer", "$skindir/footer.tpl" );
include(_BASEDIR."includes/pagesetup.php");

$usid=USERUID;
if(file_exists("languages/{$language}.php")) include_once("languages/{$language}.php");
else include_once("languages/en.php");

	if(!isADMIN && isset($uid)) accessDenied( ); // Trying to edit someone else's recommendation and not an admin.
	else if(isADMIN && uLEVEL < 4 && isset($_GET['admin'])) $admin = 1;
	else $admin = 0;


if(isset($_POST['submit'])) {

$num = count($_POST['epubon']);
$i = 0;
while($i < $num){
	$epubon = $_POST[epubon][$i] == 1 ? 1 : 0;
	$esid = $_POST[esid][$i];
	$epubimg = $_POST[epubimg][$i];
$result = dbquery("UPDATE ".TABLEPREFIX."fanfiction_stories SET epub = '".$epubon."' WHERE sid = '".$esid."'")
	. dbquery("UPDATE ".TABLEPREFIX."fanfiction_stories SET epubcover = '".$epubimg."' WHERE sid = '".$esid."'");
++$i;

}
if($result) $output .= write_message(_ACTIONSUCCESSFUL);
else $output .= write_error(_ERROR);
}
else {
$displaylist = true;

$userdir="stories/".$usid."/images/";
$coverdir="modules/epubversion/covers/";

if($displaylist) {
	if(!$action) $output .= "<div id='pagetitle'>"._MANAGEEPUBBOOKS."</div><BR /><CENTER><div style=\"max-width:400px;\" >"._EPUBDESC."<br /><br />"._COVERDESC."</div></CENTER>";
	$epubs =  dbquery("SELECT sid, title, uid, epub, count, epubcover, epubread FROM ".TABLEPREFIX."fanfiction_stories WHERE uid = '".USERUID."'");
	if(dbnumrows($epubs)) {
		$output .= "<form method=\"POST\" style=\"margin: 1em auto;\" enctype=\"multipart/form-data\" action=\"manage.php\" name=\"epubm\">"
			."<table class='tblborder' style='margin: 1em auto;'>"
			."<tr><th class='tblborder'>"._BOOKTITLE."</th>";
		$output .= "<th class='tblborder'>"._EPUBENABLE."</th>";
		$output .= "<th class='tblborder'>"._EPUBCOVER."</th>";
		$output .= "<th class='tblborder'>"._EPUBCOVERTHUMB."</th>";
		$output .= "<th class='tblborder'>"._EPUBREAD."</th>";
		$output .= "<th class='tblborder'>"._SCOUNT."</th></tr>";
		$i = 0;
		while($epub = dbassoc($epubs)) {

$output .= "<script language=\"javascript\">"
."<!--"
."function showimage1()"
."{"
."if (!document.images)"
."return"
."document.images.pictures1.src="
."document.epubm.picture1.options[document.epubm.picture1.selectedIndex].value"
."}"
."//-->";


			$output .= "\n\n<script language=\"javascript\">\n"
				."<!--\n"
				."function showimage$i()\n"
				."	{\n"
				."	if (!document.images)\n"
				."		return\n"
				."	document.images.cover$1.src=\n"
				."	document.epubm.epubimg$i.options[document.epubm.epubimg$i.selectedIndex].value\n"
				."	}\n"
				."//-->\n"
				."</script>\n\n";
			
			$output .= "<tr><td class='tblborder'>".stripslashes($epub['title'])."</td>\n";
			$output .= "<td class='tblborder'>\n"
				."<input type=\"hidden\" name=\"esid[$i]\" value=\"".$epub['sid']."\">"
				."<input type=\"radio\" name=\"epubon[$i]\" value=\"1\"".($epub['epub'] == "1" ? " Checked" : " ")."/> "._YES
				." | <input type=\"radio\" name=\"epubon[$i]\" value=\"0\"".($epub['epub'] == "0" ? " Checked" : " ")."/> "._NO
				."</td><td class='tblborder'><select name=\"picture1\" onChange=\"showimage1()\">\n";
			$output .= "<option value=\"\"".(stripslashes($epub['epubcover']) == "" ? " selected" : "").(!$epub['epubcover'] ? " selected" : "").">NONE</option>\n";
			$output .= "<optgroup label=\"User Images\">\n";
			$userimages = opendir(_BASEDIR.$userdir);

			while (($file = readdir($userimages))) {
			$userfiles[] = $file;
			}

			closedir($userimages); 
			sort($userfiles);
			reset($userfiles);

			foreach ($userfiles as $ucover){
				if ($ucover != "." && $ucover != ".." && $ucover != "imagelist.js") {
				$output .= "<option value=\"".$userdir.$ucover."\"".(stripslashes($epub['epubcover']) == $userdir.$ucover ? " selected" : "").">".$ucover."</option>\n";
				}
			}

			unset($userfiles);

			$output .= "</optgroup><optgroup label=\"Site Images\">\n";

			$coverimages = opendir(_BASEDIR.$coverdir);

			while (($cfile = readdir($coverimages))) {
			$coverfiles[] = $cfile;
			}

			closedir($coverimages); 
			sort($coverfiles);
			reset($coverfiles);

			foreach ($coverfiles as $scover){
				if ($scover != "." && $scover != "..") {
				$output .= "<option value=\"".$coverdir.$scover."\"".(stripslashes($epub['epubcover']) == $coverdir.$scover ? " selected" : "").">".$scover."</option>\n";
				}
			}
				
			unset($coverfiles);

			$output .= "</optgroup></select></td><td class='tblborder'><CENTER><img src=\"/modules/epubversion/dynthumb.php?img=".$epub['epubcover']."&size=100\" name=\"picture1\"></CENTER>\n"
				."</td><td class='tblborder'><CENTER>".$epub['epubread']."</CENTER>\n"
				."</td><td class='tblborder'><CENTER>".$epub['count']."</CENTER>\n"
				."</td></td>\n";
			++$i;
		}
		$output .= "<TR><TD colspan=\"4\"><div id='submitdiv' align='center'><INPUT type=\"submit\" id=\"submit\" class=\"button\" name=\"submit\" value=\""._SUBMIT."\"></div></TD></TR></table></form>\n";
	}
	else $output .= write_message(_NORESULTS);
}
}
$tpl->assign("output", $output);
$tpl->printToScreen();

?>

