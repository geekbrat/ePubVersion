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
$output = "";
try {
    dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_codeblocks` WHERE code_module = 'epubversion'");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_name LIKE 'epubmanage%'");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_panels` WHERE panel_name LIKE 'epubbrowse%'");
}       catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubanon`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubicon`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubsitelogo`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubimg`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubrw`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubtidy`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubtagline`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epubsitename`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".$settingsprefix."fanfiction_settings` DROP `epuburl`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` DROP `epub`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
try {
	dbquery("ALTER TABLE `".TABLEPREFIX."fanfiction_stories` DROP `epubread`");
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
	include("version.php");
try {
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_modules` WHERE name = '$moduleName'");	
}        catch (Exception $e) {
             $output .= 'Caught exception: '.$e->getMessage()." But Continued\n";
        }
	$output .= write_message(_ACTIONSUCCESSFUL);
}
else if($confirm == "no") {
	$output = write_message(_ACTIONCANCELLED);
}
else {
	$epubquery = dbquery("SHOW COLUMNS FROM ".$settingsprefix."fanfiction_settings LIKE 'epub%'");
	if(dbnumrows($epubquery)) $output = write_message(_CONFIRMUNINSTALL."<BR /><B>WARNING<B>: This script was specifically written to IGNORE all errors, and continue removing items from the MySQL database. ALL items relating to ePubVersion will removed including USER settings.<br /><a href='uninstall.php?confirm=yes'>"._YES."</a> "._OR." <a href='uninstall.php?confirm=no'>"._NO."</a>");
	else $output .= write_message(_MODNOTINSTALLED);
}
$tpl->assign("output", $output);
$tpl->printToScreen( );
?>

