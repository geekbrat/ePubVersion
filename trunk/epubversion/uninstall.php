<?php
$current = "epubversion";
include ("../../header.php");

//make a new TemplatePower object
if(file_exists( "$skindir/default.tpl")) $tpl = new TemplatePower("$skindir/default.tpl" );
else $tpl = new TemplatePower(_BASEDIR."default_tpls/default.tpl");
$tpl->assignInclude( "header", "$skindir/header.tpl" );
$tpl->assignInclude( "footer", "$skindir/footer.tpl" );
include(_BASEDIR."includes/pagesetup.php");
include_once(_BASEDIR."languages/".$language."_admin.php");
if(!isADMIN) accessDenied( );
$confirm = isset($_GET['confirm']) ? $_GET['confirm'] : false;
if($confirm == "yes") {
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_codeblocks` WHERE code_module = 'epubversion'");
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_name LIKE 'epubmanage%'");
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_name LIKE 'epubbrowse%'");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubanon`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubicon`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubsitelogo`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubtidy`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubimg`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubtagline`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubsitename`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epuburl`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubrw`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubcover`");
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epublang`");
	dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` DROP `epub`");
	dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` DROP `epubread`");
	dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` DROP `epubcover`");
	dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` DROP `epublang`");
	include("version.php");
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_modules` WHERE name = '$moduleName'");	
	$output = write_message(_ACTIONSUCCESSFUL);
}
else if($confirm == "leavedata") {
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_codeblocks` WHERE code_module = 'epubversion'");
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_name LIKE 'epubmanage%'");
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_name LIKE 'epubbrowse%'");
	include("version.php");
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_modules` WHERE name = '$moduleName'");	
	$output = write_message(_ACTIONSUCCESSFUL);
}
else if($confirm == "no") {
	$output = write_message(_ACTIONCANCELLED);
}
else {
	$epubquery = dbquery("SHOW COLUMNS FROM ".$settingsprefix."fanfiction_settings LIKE 'epubanon'");
	if(dbnumrows($epubquery)) $output = write_message(_CONFIRMUNINSTALL."<BR />"._EPUNINSTALLWARNING."<br /><a href='uninstall.php?confirm=yes'>"._YES."</a> "._OR." <a href='uninstall.php?confirm=leavedata'>"._LEAVEDATA."</a> "._OR." <a href='uninstall.php?confirm=no'>"._NO."</a>");
	else $output .= write_message(_MODNOTINSTALLED);
}
$tpl->assign("output", $output);
$tpl->printToScreen( );
?>
