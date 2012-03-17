<?php

//$_GET['sid']='161';
//$_GET['chapter']='1';

$current = "";
 error_reporting(E_ALL);
// Checks that the given $num is actually a number.  Used to help prevent XSS attacks.
function isNum($num) {
	if(empty($num)) return false;
	if(!is_string($num)) return false;
	return preg_match("/^[0-9]+$/", $num);
}

// Same with the author list
function author_list($stories) {
	if($stories['coauthors']) {
		$authlink[] = $stories['penname'];
		$coquery = dbquery("SELECT "._PENNAMEFIELD." as penname, "._UIDFIELD." as uid FROM "._AUTHORTABLE." WHERE FIND_IN_SET("._UIDFIELD.", '".$stories['coauthors']."') > 0 ORDER BY "._PENNAMEFIELD);
		while($co = dbassoc($coquery)) {
			$authlink[] = $co['penname'];
		}
	}
	return isset($authlink) ? implode(", ", $authlink) : $stories['penname'];
}

$sid = isset($_GET['sid']) && isNum($_GET['sid']) ? $_GET['sid'] : false;
$chapter = isset($_GET['chapter']) && isNum($_GET['chapter']) ? $_GET['chapter'] : false;
$_GET['action'] = "printable";
$action = "printable";
define("USERUID", false);
define("USERPENNAME", false);
define("uLEVEL", 0);
define("isMEMBER", false);
define("isADMIN", false);

// Locate config.php and set the basedir path
$folder_level = "";
while (!file_exists($folder_level."header.php")) { $folder_level .= "../"; }
if(!defined("_BASEDIR")) define("_BASEDIR", $folder_level);

@include_once(_BASEDIR."config.php");
$output = "";

$settingsresults = dbquery("SELECT * FROM ".$settingsprefix."fanfiction_settings WHERE sitekey = '".$sitekey."'");
$settings = dbassoc($settingsresults);
foreach($settings as $var => $val) {
	$$var = stripslashes($val);
}
$debug = 0;
define("TABLEPREFIX", $tableprefix);
define("STORIESPATH", $storiespath);
include_once(_BASEDIR."includes/queries.php");
session_start( );

if(isset($_SESSION[$sitekey."_skin"])) $skin = $_SESSION[$sitekey."_skin"];

if(isset($_GET['skin'])) {
	$skin = $_GET['skin'];
	$_SESSION[$sitekey."_skin"] = $skin;
}


if(is_dir(_BASEDIR."skins/$skin")) $skindir = _BASEDIR."skins/$skin";
else $skindir = _BASEDIR."default_tpls";

if(file_exists(_BASEDIR."languages/{$language}.php")) require_once (_BASEDIR."languages/{$language}.php");
else require_once (_BASEDIR."languages/en.php");

if(file_exists("languages/{$language}.php")) require_once ("languages/{$language}.php");
else require_once ("languages/en.php");

if(!empty($sid)) {
	$storyquery = dbquery(_STORYQUERY." AND sid = '$sid' LIMIT 1");
	$storyinfo = dbassoc($storyquery);
}

require_once 'HTMLPurifier.standalone.php';
 
function tidyStory($icky_story, $epubtidy) {

	$icky_Story = mb_convert_encoding($icky_story, 'HTML-ENTITIES', "UTF-8");


	if ($epubtidy == "1"||$epubtidy == "2"||$epubtidy == "3") {
		$endtags = array("/<\/b>/i", "/<\/u>/i", "/<\/i>/i", "/<\/s>/i", "/<\/em>/i", "/<\/strong>/i");
		$less_icky_story = $icky_story;
		$less_icky_story = preg_replace("/<b>/i","<span style=\"font-weight: bold;\">",$less_icky_story);
		$less_icky_story = preg_replace("/<u>/i","<span style=\"text-decoration:underline;\">",$less_icky_story);
		$less_icky_story = preg_replace("/<i>/i","<span style=\"font-style:italic;\">",$less_icky_story);
		$less_icky_story = preg_replace("/<s>/i","<span style=\"text-decoration:line-through;\">",$less_icky_story);
		$less_icky_story = preg_replace("/<em>/i","<span style=\"font-style:italic;\">",$less_icky_story);
		$less_icky_story = preg_replace("/<strong>/i","<span style=\"font-weight: bold;\">",$less_icky_story);
		$less_icky_story = preg_replace($endtags,"</span>",$less_icky_story);
	}

	if ($epubtidy == "1"||$epubtidy == "3") { 
		$tidy_config = array(
			'clean'                 => true,
			'join-classes'          => true,
			'join-styles'		=> true,
			'enclose-block-text'	=> true,
			'drop-empty-paras'	=> true,
			'enclose-text'		=> true,
			'logical-emphasis'	=> true,
			'lower-literals'	=> true,
			'quote-nbsp'		=> true,
			'word-2000'		=> true,
			'break-before-br'	=> true,
			'alt-text'           	=> 'Image',
			'quote-nbsp'		=> false,
			'output-encoding'	=> 'utf8',
			'indent'                => true,
			'output-xhtml'          => true,
			'wrap'			=> 100);
		$tidy_story = new tidy;
		$tidy_story->parseString($less_icky_story, $tidy_config, 'utf8');
		$tidy_story->cleanRepair();
	}

	else if ($epubtidy == "3") {
		$less_icky_story = $tidy_story;
 	}

	else if ($epubtidy == "2"||$epubtidy == "3") {
		$pure_config = HTMLPurifier_Config::createDefault();
		$pure_config->set('Core.Encoding', 'UTF-8'); 
		$pure_config->set('HTML.Doctype', 'XHTML 1.1');
		$pure_config->set('HTML.TidyLevel', 'heavy');
		$pure_config->set('AutoFormat.AutoParagraph', 'true');
		$pure_config->set('Core.ConvertDocumentToFragment', 'false');
		$purifier = new HTMLPurifier($pure_config);
		$tidy_story = $purifier->purify($less_icky_story);
	}

	else {
		$tidy_story = $icky_story;
	}

	return $tidy_story;
}

// Added 3.3
function nl2br2($string) {
	$string = str_replace(array("\r\n", "\r", "\n", "\n\r"), "<br />", $string);
	return $string;
}

// Formats the text of the story when displayed on screen.
function format_story($text) {
	$text = trim($text);
	if(strpos($text, "<br>") === false && strpos($text, "<p>") === false && strpos($text, "<br />") === false) $text = nl2br2($text);
	if(_CHARSET != "ISO-8859-1" && _CHARSET != "US-ASCII") return stripslashes($text);
	$badwordchars = array(chr(212), chr(213), chr(210), chr(211), chr(209), chr(208), chr(201), chr(145), chr(146), chr(147), chr(148), chr(151), chr(150), chr(133));
	$fixedwordchars = array('&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8212;', '&#8211;', '&#8230;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8212;', '&#8211;',  '&#8230;' );
	$text = str_replace($badwordchars,$fixedwordchars,stripslashes($text));
	return $text;
}

if($storyinfo) {
$fileDir = './';

// ePub READS/VIEWS
$epubres = dbquery("SELECT epubread FROM ".TABLEPREFIX."fanfiction_stories WHERE sid = '".$sid."'");
$epubcount = dbassoc($epubres);
$epubread = $epubcount['epubread'];
$epubread++;
$result = dbquery("UPDATE ".TABLEPREFIX."fanfiction_stories SET epubread = '".$epubread."' WHERE sid = '".$sid."'");

// Call the EPub class 
include_once("EPub.php");
$fileTime = date("D, d M Y H:i:s T");

// Start NEW Book
$book = new EPub();

include("image_support.php");
include("cover_img.php");

$sitename = stripslashes($settings['epubsitename']);
$sitetagline = stripslashes($settings['epubtagline']);
$siteurl = stripslashes($settings['epuburl']);
$epubimg = stripslashes($settings['epubimg']);
$sitelogo = stripslashes($settings['epubsitelogo']);

$epubs = dbquery("SELECT sid, epub, epubcover, epublang FROM ".TABLEPREFIX."fanfiction_stories WHERE sid = '".$sid."'");
$epub = dbassoc($epubs);
if ($epub['epub'] == "0") {
	die(_EPUBERROR);
}

if (isset($epub['epublang'])) {
        $epublang = stripslashes($epub['epublang']);
}
else if (isset($settings['epublang'])) {
        $epublang = stripslashes($settings['epublang']);
}
else {
      	$epublang = "en";
}

$currentVersion = dbrow(dbquery("SELECT version FROM ".TABLEPREFIX."fanfiction_modules WHERE name = 'ePubVersion' LIMIT 1"));

if(!isMEMBER && !$settings['epubanon']) {
die(_EPUBERRORANON);
}

//This should fix the phantom author error.....
$author = author_list($storyinfo);

//Some ePub Readers are title picky.....
$badchar = array(',', '\'', '\"', '\`', '&', '*', '%', '$', '#', '@', '!', '^' );
$title=str_replace($badchar,"",stripslashes($storyinfo['title']));

/*
if ($chapter == "all") {
	$ident = $sid."0109999";
}
else {
	$ident = $sid."010".$chapter;
}
*/
$ident = $url."/viewstory.php?sid=".$sid;

// Setup all the basic elements of the book. Later versions I will include a panel to edit Publisher info and Rights
$book->setTitle($title);
$book->setIdentifier("$ident", "URI"); 
$book->setLanguage("$epublang"); 
$book->setDescription("An On-The-Fly created ePub eBook from:".$sitename);
$book->setAuthor( author_list($storyinfo), author_list($storyinfo)); 
$book->setPublisher($sitename.": ".$sitetagline, $siteurl); 
$book->setDate(time());
$book->setRights("The original characters and plot of this story are the property of the author. No infringement of pre-existing copyright is intended. This story is copyright (c)".date('Y').", ".author_list($storyinfo). ". All rights reserved.");
$book->setSourceURL("$url/viewstory.php?sid=$sid");
$book->setGenerator("ePubVersion v$currentVersion");

// Let's add the cover before we do anything else, this way I only have to set it up once.

$cssData = "body {\n  margin-left: .5em;\n  margin-right: .5em;\n  text-align: justify;\n}\n\np {\n  font-family: serif;\n  font-size: 10pt;\n  text-align: justify;\n  text-indent: 1em;\n  margin-top: 0px;\n  margin-bottom: 1ex;\n}\n\nh1, h2 {\n  font-family: sans-serif;\n  font-style: italic;\n  text-align: center;\n  background-color: #6b879c;\n  color: white;\n  width: 100%;\n}\n\nh1 {\n    margin-bottom: 2px;\n}\n\nh2 {\n    margin-top: -2px;\n    margin-bottom: 2px;\n}\n";

$ucover = $epub['epubcover'];

$scover = $settings['epubcover'];

//echo $settings['epubcover'];

//echo $scover;

if (isset($settings['epubcover'])||isset($epub['epubcover'])) {
	if (isset($epub['epubcover'])) {
		$cover_image=coverImage($url, $book, $ucover, $epubimg);
	}

	else {
		$cover_image=coverImage($url, $book, $scover, $epubimg);
	}
}


/*
if (isset($settings['epubcover'])) {
        if (isset($epub['epubcover'])) {
//		$cover_parts = pathinfo($epub['epubcover']);
		$book->setCoverImage("/".$epub['epubcover']); 
	}
        else {  
		$book->setCoverImage("/".$settings['epubcover']);
	}
}
*/
// Now we start setting up the other crap...

	if(empty($chapter)) $chapter = "all"; // shouldn't happen but just in case
	unset($stories);
	if($chapter == "all") {

// This is the basic layout for the html page header for each chapter.	

		$content_start = "<?xml version=\"1.0\" ?>\n"
			. "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
			. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
			. "<html version=\"-//W3C//DTD XHTML 1.1//EN\"\n"
			. "      xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\"\n"
			. "      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n"
			. ">\n"
			. "<head>"
			. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
			. "<title>".stripslashes($storyinfo['title'])."</title>\n"
			. "</head><body>\n";

		if (isset($settings['epubcover'])) {
			if (isset($epub['epubcover'])) {
		                $cover_img = "<center><img src=\"".$ucover."\"></center><br \>";
			}
	
			else {
		                $cover_img = "<center><img src=\"".$scover."\"></center><br \>";
       			}
		}
		else {
			$cover_img = "";

		}

		$content_end = "</body>\n</html>\n";

// Lets start with the cover page, and include the Title and Author

		$cover_basic =  $cover_img."<h1>".stripslashes($storyinfo['title'])."</h1>\n<h2>By: ".$author."</h2>\n";

// If StoryNotes Exist, lets add them to the cover page.

		if($storyinfo['storynotes']) {
			$cover_notes = $storyinfo['storynotes'];
		}
		else $cover_notes = "\n";

// Now we put all the various cover content together, with Content_Start and add our coverpage to the book!		

		$cover_content = "<CENTER>This ePub brought to you by: ".$sitename.".</CENTER>"
			. "<CENTER><img src=\"".$sitelogo."\"></CENTER>";
		$ch_id = "cover";
		$cover_san = addImages($cover_basic.$cover_notes.$cover_content, $url, $book, $ch_id, $epubimg);
		if ($settings['epubtidy'] == "1") {
			$cover_xml = tidyStory($content_start.$cover_san.$content_end, $settings['epubtidy'])." ";
		}
		else if ($settings['epubtidy'] == "2") {
			$cover_xml = $content_start.tidyStory($cover_san, $settings['epubtidy']).$content_end;
		}
		else if ($settings['epubtidy'] == "3") {
			$cover_xml = $content_start.tidyStory($cover_san, 2).$content_end;
			$cover_xml = tidyStory($cover_xml, 3)." ";
		}
		else {
		$cover_xml = $cover_san;
		}
		$book->addChapter("Cover", "Cover.html", $cover_xml);
		$chapterinfo = dbquery("SELECT *, "._PENNAMEFIELD." as penname FROM (".TABLEPREFIX."fanfiction_chapters as c, "._AUTHORTABLE.") WHERE sid = '$sid' AND c.uid = "._UIDFIELD." ORDER BY inorder");
		while($c = dbassoc($chapterinfo)) {
			$chapter_start = "<h1>Chapter ".$c['inorder']."</h1>\n<h2>".$c['title']."</h2>\n";
			if($c['notes']) {
				$chapter_notes = $c['notes'];
			}
			else $chapter_notes = "\n"; 
			if($store == "files") {
				//shouldn't happen, but somehow has on occasion. :(
				if(!$c['uid']) {

// If anyone can think of a more elegant way to do this, let mew know. I might have it change the book name to "error".

					$errorc = $content_start . "<div style='text-align: center;'>"._ERROR."</div>We\'re sorry, but there was an error retrieving a chapter of this book";
					$book->addChapter("Chapter : Error", "ChapterError.html", $errorc);
					$book->finalize(); // Finalize the book, and build the archive.
					$zipData = $book->sendBook(stripslashes($storyinfo['title']));
					exit( );
				}
				$file = _BASEDIR.STORIESPATH."/".$c['uid']."/".$c['chapid'].".txt";
				$log_file = @fopen($file, "r");
				$file_contents = @fread($log_file, filesize($file));
				$story = $file_contents;
				@fclose($log_file);
			}
			else $story = $c['storytext'];

// The following lines cleans up problems between pre-2.0 stories and 2.0 stories.  If there's html, don't send it through nl2br and then clean up smart quotes.

			$story = format_story($story);
			if($c['endnotes']) {
				$chapter_endnotes = format_story($c['endnotes']);
			}
			else $chapter_endnotes = "\n";
			$lastnum = $c['inorder'];
			$chapter_san = addImages($chapter_start.$chapter_notes.$story.$chapter_endnotes, $url, $book, $c['inorder'], $epubimg);
			if ($settings['epubtidy'] == "1") {
				$chapter_xml = tidyStory($content_start.$chapter_san.$content_end, $settings['epubtidy'])." ";
			}
			else if ($settings['epubtidy'] == "2") {
				$chapter_xml = $content_start.tidyStory($chapter_san, $settings['epubtidy']).$content_end;
			}
			else if ($settings['epubtidy'] == "3") {
				$chapter_xml = $content_start.tidyStory($chapter_san, 2).$content_end;
				$chapter_xml = tidyStory($chapter_xml, 3)." ";
			}
			else {
				$chapter_xml = $chapter_san;
			}
			$book->addChapter("Chapter ".$c['inorder'].": ".stripslashes($c['title'])." ", "Chapter".$c['inorder'].".html", $chapter_xml);
		}
		$archivedat = _ARCHIVEDAT." <a href=\"$url/viewstory.php?sid=$sid\">$url/viewstory.php?sid=$sid</a><br />";
		$copyquery = dbquery("SELECT message_text FROM ".TABLEPREFIX."fanfiction_messages WHERE message_name = 'printercopyright' LIMIT 1");
		list($copyright) = dbrow($copyquery);
		$copy = $archivedat
			. "<BR /><BR /><P>"
			. $copyright
			. "</P><BR /><BR /><P>"
			. "This book was created \"On-The-Fly\" using <A HREF=\"http://efiction.org\">eFiction</A> and <A HREF=\"http://storyportal.net/software/epub/\">ePubVersion</A>"
			. "<CENTER><IMG SRC=\"http://storyportal.net/wp-content/uploads/ePubVersion-Logo-L.png\"></CENTER>"
			. "</P>";
		$ch_id = "copy";
		$copy_san = addImages($copy, $url, $book, $ch_id, $epubimg);
		if ($settings['epubtidy'] == "1") {
			$copy_xml = tidyStory($content_start.$copy_san.$content_end, $settings['epubtidy'])." ";
		}
		else if ($settings['epubtidy'] == "2") {
			$copy_xml = $content_start.tidyStory($copy_san, $settings['epubtidy']).$content_end;
		}
		else if ($settings['epubtidy'] == "3") {
			$copy_xml = $content_start.tidyStory($copy_san, 2).$content_end;
			$copy_xml = tidyStory($copy_xml, 3)." ";
		}
		else {
			$copy_xml = $copy_san;
		}
		$book->addChapter("Copy", "Copy.html", $copy_xml);

// Finalize the book, deliver the book to the arvhive/zip script, zip it up, and deliver the book to the user.

		$book->finalize();
		$zipData = $book->sendBook($title." [1-".$lastnum."]");
		exit();
		}
		else {

// 2nd Verse Similar to the first!

// This is the basic layout for the html page header for each chapter.	

			$content_start = "<?xml version=\"1.0\" ?>\n"
				. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
				. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
				. "<html version=\"-//W3C//DTD XHTML 1.1//EN\"\n"
				. "      xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\"\n"
				. "      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n"
				. ">\n"
				. "<head>"
				. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
				. "<title>".stripslashes($storyinfo['title'])."</title>\n"
				. "</head>\n"
				. "<body>\n";
			$content_end = "</body>\n</html>\n";

			if (isset($settings['epubcover'])) {
				if (isset($epub['epubcover'])) {
			                $cover_img = "<center><img src=\"".$ucover."\"></center><br \>";
				}
		
				else {
			                $cover_img = "<center><img src=\"".$scover."\"></center><br \>";
				}
			}
			else {
				$cover_img = "";

			}
// Lets start with the cover page, and include the Title and Author

			$cover_basic = $cover_img."<h1>".stripslashes($storyinfo['title'])."</h1>\n<h2>By: ".author_list($storyinfo)."</h2>\n";
			$chapterinfo = dbquery("SELECT *, "._PENNAMEFIELD." as penname FROM (".TABLEPREFIX."fanfiction_chapters as c, "._AUTHORTABLE.") WHERE sid = '$sid' AND inorder = '$chapter' AND c.uid = "._UIDFIELD." LIMIT 1");
			$c = dbassoc($chapterinfo);

// if the *CHAPTER* hasn't been validated and the viewer isn't an admin or the author throw them a warning.  

			if(empty($c['validated']) && !isADMIN && USERUID != $c['uid'] && !in_array($c['uid'], explode(",", $storyinfo['coauthors']))) {
				$warning = write_error(_ACCESSDENIED);
				$errorc = $content_start . "<div style='text-align: center;'>".$warning."</div>We\'re sorry, but there was an error retrieving a chapter of this book";	
				$book->addChapter("Chapter : Error", "ChapterError.html", $errorc);
				$book->finalize(); // Finalize the book, and build the archive.
				$zipData = $book->sendBook(stripslashes($storyinfo['title']));
				exit( );

			exit( );
			}
			if($c['inorder'] == 1 && $storyinfo['storynotes']) {
				$cover_notes = $storyinfo['storynotes'];
			}
			else $cover_notes = "\n";

// Now we put all the various cover content together, with Content_Start and add our coverpage to the book!		

			$cover_content = "<CENTER>This ePub brought to you by: ".$sitename.".</CENTER>"
				. "<CENTER><img src=\"".$sitelogo."\"></CENTER>";
			$ch_id = "cover";
			$cover_san = addImages($cover_basic.$cover_notes.$cover_content, $url, $book, $ch_id, $epubimg);
			if ($settings['epubtidy'] == "1") {
				$cover_xml = tidyStory($content_start.$cover_san.$content_end, $settings['epubtidy'])." ";
			}
			else if ($settings['epubtidy'] == "2") {
				$cover_xml = $content_start.tidyStory($cover_san, $settings['epubtidy']).$content_end;
			}
			else if ($settings['epubtidy'] == "3") {
				$cover_xml = $content_start.tidyStory($cover_san, 2).$content_end;
				$cover_xml = tidyStory($cover_xml, 3)." ";
			}
			else {
				$cover_xml = $cover_san;
			}
			$book->addChapter("Cover", "Cover.html", $cover_xml);
			$chapter_start = "<h1>Chapter ".$c['inorder']."</h1>\n<h2>".$c['title']."</h2>\n";
			if($c['notes']) {
				$chapter_notes = $c['notes'];
			}
			else $chapter_notes = "\n";

//shouldn't happen, but somehow has on occasion. :(

			if($store == "files") {
				if(!$c['uid']) {
					$errorc = $content_start . "<div style='text-align: center;'>"._ERROR."</div>We\'re sorry, but there was an error retrieving a chapter of this book";
					$book->addChapter("Chapter : Error", "ChapterError.html", $errorc);
					$book->finalize(); // Finalize the book, and build the archive.
					$zipData = $book->sendBook(stripslashes($storyinfo['title']));
					exit( );
				}
				$file = _BASEDIR.STORIESPATH."/".$c['uid']."/".$c['chapid'].".txt";
				$log_file = @fopen($file, "r");
				$file_contents = @fread($log_file, filesize($file));
				$story = $file_contents;
				@fclose($log_file);
			}
			else $story = $c['storytext'];

// The following lines cleans up problems between pre-2.0 stories and 2.0 stories.  If there's html, don't send it through nl2br and then clean up smart quotes.

			$story = format_story($story);

// I should probablt do this down below, but for now, lets assemble what we have.

			if($c['endnotes']) {
				$chapter_endnotes = format_story($c['endnotes']);
			}
			else $chapter_endnotes = "\n";
		$chapter_san = addImages($chapter_start.$chapter_notes.$story.$chapter_endnotes, $url, $book, $c['inorder'], $epubimg);
		if ($settings['epubtidy'] == "1") {
			$chapter_xml = tidyStory($content_start.$chapter_san.$content_end, $settings['epubtidy'])." ";
		}
		else if ($settings['epubtidy'] == "2") {
			$chapter_xml = $content_start.tidyStory($chapter_san, $settings['epubtidy']).$content_end;
		}
		else if ($settings['epubtidy'] == "3") {
			$chapter_xml = $content_start.tidyStory($chapter_san, 2).$content_end;
			$chapter_xml = tidyStory($chapter_xml, 3)." ";
		}
		else {
			$chapter_xml = $chapter_san;
		}
		$book->addChapter("Chapter ".$c['inorder'].": ".$c['title']." ", "Chapter".$c['inorder'].".html", $chapter_xml);
		$archivedat = _ARCHIVEDAT." <a href=\"$url/viewstory.php?sid=$sid\">$url/viewstory.php?sid=$sid</a><br />";
		$copyquery = dbquery("SELECT message_text FROM ".TABLEPREFIX."fanfiction_messages WHERE message_name = 'printercopyright' LIMIT 1");
		list($copyright) = dbrow($copyquery);
		$copy = $archivedat
			. "<BR /><BR /><P>"
			. $copyright
			. "</P><BR /><BR /><P>"
			. "This book was created \"On-The-Fly\" using <A HREF=\"http://efiction.org\">eFiction</A> and <A HREF=\"http://storyportal.net/software/epub/\">ePubVersion</A>"
			. "<CENTER><IMG SRC=\"http://storyportal.net/wp-content/uploads/ePubVersion-Logo-L.png\"></CENTER>"
			. "</P>";
		$ch_id = "copy";
		$copy_san = addImages($copy, $url, $book, $ch_id, $epubimg);
		if ($settings['epubtidy'] == "1") {
			$copy_xml = tidyStory($content_start.$copy_san.$content_end, $settings['epubtidy'])." ";
		}
		else if ($settings['epubtidy'] == "2") {
			$copy_xml = $content_start.tidyStory($copy_san, $settings['epubtidy']).$content_end;
		}
		else if ($settings['epubtidy'] == "3") {
			$copy_xml = $content_start.tidyStory($copy_san, 2).$content_end;
			$copy_xml = tidyStory($copy_xml, 3)." ";
		}
		else {
			$copy_xml = $copy_san;
		}
		$book->addChapter("Copy", "Copy.html", $copy_xml);

// Finalize the book, deliver the book to the arvhive/zip script, zip it up, and deliver the book to the user.

		$book->finalize();
		$zipData = $book->sendBook($title." [".$c['inorder']."]");
		exit( );
	}				

}

else {

	if(file_exists(_BASEDIR."languages/{$language}.php")) require_once (_BASEDIR."languages/{$language}.php");
	else require_once (_BASEDIR."languages/en.php");
	die(_ERROR);
}
?>
