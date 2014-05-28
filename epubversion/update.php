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

if(file_exists("languages/".$language.".php")) include_once("languages/".$language.".php");

else include_once("languages/en.php");

if(!isADMIN) accessDenied( );

$confirm = isset($_GET['confirm']) ? $_GET['confirm'] : false;
$epd = isset($_GET['epd']) ? $_GET['epd'] : "on";
include("version.php");

$currentVersion = dbrow(dbquery("SELECT version FROM ".TABLEPREFIX."fanfiction_modules WHERE name = '$moduleName' LIMIT 1"));
if(empty($currentVersion ) || $currentVersion[0] < 1.1) {
	if($confirm == "yes") {
		if(empty($currentVersion) || $currentVersion[0] == 0) {
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubimg` TINYINT( 1 ) default '0'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubrw` TINYINT( 1 ) default '0'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubanon` TINYINT( 1 ) NOT NULL default '1'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubtidy` TINYINT( 1 ) default '0'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubsitename` varchar(200) NOT NULL default 'Your SiteName Here!'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubtagline` varchar(200) NOT NULL default 'Catchy Tag-Line Here!'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epuburl` varchar(200) NOT NULL default 'http://your-stie.url'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubsitelogo` varchar(200) NOT NULL default 'modules/epubversion/banners/DEFAULT-Banner.png'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubicon` varchar(200) NOT NULL default 'modules/epubversion/images/3-default-epub.png'");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epublang` varchar(2) NULL default NULL");
			dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubcover` varchar(200) NULL default NULL");
			dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubcover` varchar(200) NULL default NULL");
			dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubread` TINYINT( 1 ) NOT NULL default '1'");
			dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epublang` VARCHAR( 2 ) NULL DEFAULT NULL");
			dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/recentblock.php\");', 'storyblock', 'epubversion');");
			dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/otherresults.php\");', 'otherresults', 'epubversion');");

			if($epd == "on"){
    				dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epub` TINYINT( 1 ) NOT NULL default '1'");
			} 
			else{
  		 		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epub` TINYINT( 1 ) NOT NULL default '0'");
			}

        		dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ( 'include(_BASEDIR.\"modules/epubversion/storyblock.php\");', 'viewstory', 'epubversion');");
			$subsquery1 = dbquery("SELECT panel_id FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_type = 'S' AND panel_hidden = '0'");
			list($subs1) = mysql_num_rows($subsquery1);
			$subs1++;
		        dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_panels` (`panel_name` , `panel_title` , `panel_url` , `panel_level` , `panel_order` , `panel_hidden` , `panel_type` ) VALUES ('epubmanage', 'Manage ePub Stories', 'modules/epubversion/manage.php', '1', '$subs1', '0', 'S');");
			$subsquery2 = dbquery("SELECT panel_id FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_type = 'S' AND panel_hidden = '0'");
			list($subs2) = mysql_num_rows($subsquery2);
			$subs2++;
		        include("version.php");
			dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_panels` (`panel_name` , `panel_title` , `panel_url` , `panel_level` , `panel_order` , `panel_hidden` , `panel_type` ) VALUES ('epubbrowse', 'ePub eBooks', 'modules/epubversion/browse.php', '1', '$subs2', '0', 'B');");
			dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_modules`(`version`, `name`) VALUES('$moduleVersion', '$moduleName')");
	}
	else if($currentVersion[0] < 1.0) {
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubimg` TINYINT( 1 ) default '0'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubrw` TINYINT( 1 ) default '0'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubanon` TINYINT( 1 ) NOT NULL default '1'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubtidy` TINYINT( 1 ) default '0'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubsitename` varchar(200) NOT NULL default 'Your SiteName Here!'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubtagline` varchar(200) NOT NULL default 'Catchy Tag-Line Here!'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epuburl` varchar(200) NOT NULL default 'http://your-stie.url'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubsitelogo` varchar(200) NOT NULL default 'modules/epubversion/banners/DEFAULT-Banner.png'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubicon` varchar(200) NOT NULL default 'modules/epubversion/images/3-default-epub.png'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epublang` varchar(2) NULL default NULL");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubread` TINYINT( 1 ) NOT NULL default '1'");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epublang` VARCHAR( 2 ) DEFAULT NULL");
		dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/recentblock.php\");', 'storyblock', 'epubversion');");
		dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/otherresults.php\");', 'otherresults', 'epubversion');");

		if($epd == "on"){
    			dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epub` TINYINT( 1 ) NOT NULL default '1'");
		} 
		else{
  			 dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epub` TINYINT( 1 ) NOT NULL default '0'");
		}

	$subsquery1 = dbquery("SELECT panel_id FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_type = 'S' AND panel_hidden = '0'");
	list($subs1) = mysql_num_rows($subsquery1);
	$subs1++;
        dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_panels` (`panel_name`, `panel_title`, `panel_url`, `panel_level`, `panel_order`, `panel_hidden`, `panel_type` ) VALUES ('epubmanage', 'Manage ePub Stories', 'modules/epubversion/manage.php', '1', '$subs1', '0', 'S');");
	$subsquery2 = dbquery("SELECT panel_id FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_type = 'B' AND panel_hidden = '0'");
	list($subs2) = mysql_num_rows($subsquery2);
	$subs2++;
	dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_panels` (`panel_name` , `panel_title` , `panel_url` , `panel_level` , `panel_order` , `panel_hidden` , `panel_type` ) VALUES ('epubbrowse', 'ePub eBooks', 'modules/epubversion/browse.php', '1', '$subs2', '0', 'B');");

	}
	else if($currentVersion[0] < 1.1) {
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubimg` TINYINT( 1 ) default '0'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubrw` TINYINT( 1 ) default '0'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epublang` varchar(2) NULL default NULL");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epublang` VARCHAR( 2 ) NULL DEFAULT NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubread` TINYINT( 1 ) NOT NULL default '1'");
		dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/recentblock.php\");', 'storyblock', 'epubversion');");
	}
	else if($currentVersion[0] < 1.2) {
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubrw` TINYINT( 1 ) default '0'");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epublang` varchar(2) NULL default NULL");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epublang` VARCHAR( 2 ) NULL DEFAULT NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubread` TINYINT( 1 ) NOT NULL default '1'");
		dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/recentblock.php\");', 'storyblock', 'epubversion');");
	}
	else if($currentVersion[0] < 1.3) {
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epublang` varchar(2) NULL default NULL");
		dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubcover` varchar(200) NULL default NULL");
		dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epublang` VARCHAR( 2 ) NULL DEFAULT NULL");
		dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/recentblock.php\");', 'storyblock', 'epubversion');");
	}
	else if($currentVersion[0] == 1.3) {
		try {
			if (false === dbquery("select `epubcover` from `".$settingsprefix."fanfiction_settings` limit 0")) {
				dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epubcover` varchar(200) NULL default NULL");
			 } else {
				dbquery("ALTER TABLE  `".$settingsprefix."fanfiction_settings` CHANGE  `epubcover`  `epubcover` VARCHAR( 200 ) NULL DEFAULT NULL");
				dbquery("UPDATE `".$settingsprefix."fanfiction_settings` SET `epubcover`= NULL WHERE sitekey = '".$sitekey."'");
			}
		} catch (Exception $e) {
			$output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
		}
		try {
			if (false === dbquery("select `epublang` from `".$settingsprefix."fanfiction_settings` limit 0")) {
				dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` ADD `epublang` varchar(2) NULL default NULL");
			}
		} catch (Exception $e) {
			$output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
		}
		try {
			if (false === dbquery("select `epubcover` from `".TABLEPREFIX."fanfiction_stories` limit 0")) {
				dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epubcover` varchar(200) NULL default NULL");
			}
		} catch (Exception $e) {
			$output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
		}
		try {
			if (false === dbquery("select `epublang` from `".$settingsprefix."fanfiction_settings` limit 0")) {
				dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` ADD `epublang` VARCHAR( 2 ) NULL DEFAULT NULL");
			}
			dbquery("INSERT INTO `".TABLEPREFIX."fanfiction_codeblocks` (`code_text`, `code_type`, `code_module`) VALUES ('include(_BASEDIR.\"modules/epubversion/recentblock.php\");', 'storyblock', 'epubversion');");
		} catch (Exception $e) {
			$output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
		}
	}
   	dbquery("UPDATE ".TABLEPREFIX."fanfiction_modules SET version = '$moduleVersion' WHERE name = '$moduleName' LIMIT 1");
	$output = write_message(_ACTIONSUCCESSFUL);
}
else if($confirm == "no") {
	$output = write_message(_ACTIONCANCELLED);
}
else {
	$output = write_message("<H1>"._CONFIRMUPDATE."</H1><H2><a href='update.php?confirm=yes&amp;epd=on'>"._YES."</a>(ePub Default ON)</H2><H2><a href='update.php?confirm=yes&amp;epd=off'>"._YES."</a>(ePub Default Off)</H2><H2><a href='update.php?confirm=no'>"._NO."</a></H2>");
}
}
else $output .= write_message(_ALREADYUPDATED);
$tpl->assign("output", $output);
$tpl->printToScreen( );
?>
