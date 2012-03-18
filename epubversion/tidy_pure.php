<?php

/**
 * @author Kirstyn Amanda Fox (GeekBrat)
 *
 * @copyright 2012
 */

require_once 'libs/HTMLPurifier.standalone.php';
 
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

?>