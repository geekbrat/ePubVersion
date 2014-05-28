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
if($confirm == "yesoff"||$confirm == "yeson") {
try {
 if (false === dbquery("select `epubimg` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubimg` TINYINT( 1 ) default '0'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
 if (false === dbquery("select `epubanon` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubanon` TINYINT( 1 ) NOT NULL default '1'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
 if (false === dbquery("select `epubtidy` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubtidy` TINYINT( 1 ) NOT NULL default '0'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
 if (false === dbquery("select `epubrw` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubrw` TINYINT( 1 ) NOT NULL default '0'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
 if (false === dbquery("select `epubsitename` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubsitename` varchar(200) NOT NULL default 'Your SiteName Here!'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epubtagline` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubtagline` varchar(200) NOT NULL default 'Catchy Tag-Line Here!'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epuburl` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epuburl` varchar(200) NOT NULL default 'http://your-stie.url'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epubsitelogo` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubsitelogo` varchar(200) NOT NULL default 'modules/epubversion/banners/DEFAULT-Banner.png'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epubicon` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubicon` varchar(200) NOT NULL default 'modules/epubversion/images/3-default-epub.png'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epubcover` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubcover` varchar(200) NULL default NULL");
 } else {
    dbquery("ALTER TABLE  `".$settingsprefix."fanfiction_settings` CHANGE  `epubcover`  `epubcover` VARCHAR( 200 ) NULL DEFAULT NULL");
    dbquery("UPDATE `".$settingsprefix."fanfiction_settings` SET `epubcover`= NULL WHERE sitekey = '".$sitekey."'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epublang` from `".$settingsprefix."fanfiction_settings` limit 0")) {
    dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epublang` varchar(2) NULL default NULL");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epubcover` from `".TABLEPREFIX."fanfiction_stories` limit 0")) {
    dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubcover` varchar(200) NULL default NULL");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epubread` from `".TABLEPREFIX."fanfiction_stories` limit 0")) {
    dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubread` TINYINT( 1 ) NOT NULL default '1'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {

 if (false === dbquery("select `epublang` from `".TABLEPREFIX."fanfiction_stories` limit 0")) {
    dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epublang` VARCHAR( 2 ) NULL DEFAULT NULL");
 }
    dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/otherresults.php\");', 'otherresults', 'epubversion');");
    dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/recentblock.php\");', 'storyblock', 'epubversion');");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
if($confirm == "yeson") {
try {

 if (false === dbquery("select `epub` from `".TABLEPREFIX."fanfiction_stories` limit 0")) {
    dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epub` TINYINT( 0 ) NOT NULL default '1'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
}
if($confirm == "yesoff") {
try {

 if (false === dbquery("select `epub` from `".TABLEPREFIX."fanfiction_stories` limit 0")) {
    dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epub` TINYINT( 0 ) NOT NULL default '0'");
 }
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
}
	dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ( 'include(_BASEDIR.\"modules/epubversion/storyblock.php\");', 'viewstory', 'epubversion');");
	$subsquery1 = dbquery("SELECT panel_id FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_type = 'S' AND panel_hidden = '0'");
	$subs1 = mysql_num_rows($subsquery1);
	$subs++;
	dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_panels`(`panel_name`, `panel_title`, `panel_url`, `panel_level`, `panel_order`, `panel_hidden`, `panel_type` ) VALUES ('epubmanage', 'Manage ePub Stories', 'modules/epubversion/manage.php', '1', '$subs', '0', 'S');");
	$subsquery2 = dbquery("SELECT panel_id FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_type = 'B' AND panel_hidden = '0'");
	$subs2 = mysql_num_rows($subsquery2);
	$subs++;
	dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_panels` (`panel_name` ,`panel_title` ,`panel_url` ,`panel_level` ,`panel_order` ,`panel_hidden` ,`panel_type` ) VALUES ('epubbrowse', 'ePub eBooks', 'modules/epubversion/browse.php', '0', '$subs2', 0, 'B');");
	include("version.php");
	dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_modules`(`version`, `name`) VALUES('$moduleVersion', '$moduleName')");
	$output = write_message(_ACTIONSUCCESSFUL);
}
elseif($confirm == "no") {
	$output = write_message(_ACTIONCANCELLED);
}
else {
	$output = write_message("<IMG SRC=\"http://storyportal.net/wp-content/uploads/ePubVersion-Logo-L-300x225.png\"><H1>"._CONFIRMINSTALL."</H1><H2><a href='install.php?confirm=yesoff'>"._YES." (ePub Default OFF)</a></H2><H2><a href='install.php?confirm=yeson'>"._YES." (ePub Default ON)</a></H2><H2><a href='install.php?confirm=no'>"._NO."</a></H2>");
}
$tpl->assign("output", $output);
$tpl->printToScreen( );
?>
