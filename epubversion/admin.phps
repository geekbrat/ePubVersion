<?php
// ----------------------------------------------------------------------
// Copyright (c) 2011 by Kirstyn Amanda Fox
// http://storyportal.net/software/epub
// Valid HTML 4.01 Transitional
// Based on "Challenges" by Tammy Keefer
// http://hugosnebula.com/
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
if(isset($_GET['action']) && ($_GET['action'] == "add" || $_GET['action'] == "edit")) $displayform = 1;

if(file_exists(_BASEDIR."languages/{$language}_admin.php")) include_once(_BASEDIR."languages/{$language}_admin.php");
else include_once(_BASEDIR."languages/en_admin.php");
if(file_exists(_BASEDIR."modules/epubversion/languages/{$language}.php")) include_once(_BASEDIR."modules/epubversion/languages/{$language}.php");
else include_once(_BASEDIR."modules/epubversion/languages/en.php");
$icondir = opendir(_BASEDIR."modules/epubversion/images");
$bannerdir = opendir(_BASEDIR."modules/epubversion/banners");
$output = "<div id='pagetitle'>"._ePubVersionAdmin."</div>";
$coverdir="modules/epubversion/covers/";
if(!isADMIN) accessDenied( );

if(isset($_POST['submit'])) {
	$epubanon = addslashes($_POST['epubanon']);
	$epublang = addslashes($_POST['epublang']);
	$epubtidy = addslashes($_POST['epubtidy']);
	$epubimg = addslashes($_POST['epubimg']);
	$epubrw = addslashes($_POST['epubrw']);
	$epubsitename = addslashes($_POST['epubsitename']);
	$epubsitelogo = addslashes($_POST['epubsitelogo']);
	$epubicon = addslashes($_POST['epubicon']);
	$epubtagline = addslashes($_POST['epubtagline']);
	$epubcover = addslashes($_POST['epubcover']);
	$epuburl = addslashes($_POST['epuburl']);
	$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubanon = '$epubanon'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epublang = '$epublang'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubtidy = '$epubtidy'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubimg = '$epubimg'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubrw = '$epubrw'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubsitename = '$epubsitename'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubtagline = '$epubtagline'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubsitelogo = '$epubsitelogo'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubicon = '$epubicon'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epubcover = '$epubcover'  WHERE sitekey = '".SITEKEY."'")
		. dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET epuburl = '$epuburl' WHERE sitekey = '".SITEKEY."'");
	if($result) $output .= write_message(_ACTIONSUCCESSFUL);
	else
    $output .= write_error(_ERROR);
}
else {
	$output .= "<TABLE><TR><TD valign=\"TOP\"><CENTER><a rel=\"attachment wp-att-113\" href=\"http://storyportal.net/software/epub/epubversion-logo-l/\"><img class=\"alignleft size-thumbnail wp-image-113\" title=\"ePubVersion-Logo-L\" src=\"http://storyportal.net/wp-content/uploads/ePubVersion-Logo-L-300x225.png\" alt=\"ePubVersion Logo Image\" width=\"150\" /></a><H2>Please consider a Gift.</H2><H3>Help support ePubVersion's future development.</H3>";
	$output .= "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"><input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\"><input type=\"hidden\" name=\"hosted_button_id\" value=\"6GSMHUL2V5476\"><table><tr><td><input type=\"hidden\" name=\"on0\" value=\"Amount\">Amount</td></tr><tr><td><select name=\"os0\">";
	$output .= "<option value=\"$1\">$1</option>";
	$output .= "<option value=\"$5\">$5</option>";
	$output .= "<option value=\"$10\">$10</option>";
	$output .= "<option value=\"$50\">$50</option></select>";
	$output .= "</td></tr></table><input type=\"hidden\" name=\"currency_code\" value=\"USD\"><input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\"><img alt=\"\" border=\"0\" src=\"https://www.paypal.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\"></form>";
	$output .= "</CENTER></TD><TD>";
	$output .= "<div  id=\"settingsform\"><form method=\"POST\" style=\"margin: 1em auto;\" enctype=\"multipart/form-data\" action=\"".$url."/admin.php?action=modules&amp;admin=true&amp;module=epubversion\">";
	$output .= "<TABLE><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBANON.": </b><br /><select name=\"epubanon\">";
	$output .= "<option value=\"1\"".(stripslashes($epubanon) == "1" ? " selected" : "").">"._YES."</option>";
	$output .= "<option value=\"0\"".(stripslashes($epubanon) == "0" ? " selected" : "").">"._NO."</option>";
	$output .= "</select>";
	$output .= "</TD><TD>"._EPUBANONDESC."</TD></TR><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBLANG.": </b><br /><select name=\"epublang\">";
	$output .= "<option value=\"en\"".(stripslashes($epublang) == "en" ? " selected" : "").">English</option>";
	$output .= "<option value=\"aa\"".(stripslashes($epublang) == "aa" ? " selected" : "").">Afar</option>";
	$output .= "<option value=\"ab\"".(stripslashes($epublang) == "ab" ? " selected" : "").">Abkhazian</option>";
	$output .= "<option value=\"af\"".(stripslashes($epublang) == "af" ? " selected" : "").">Afrikaans</option>";
	$output .= "<option value=\"am\"".(stripslashes($epublang) == "am" ? " selected" : "").">Amharic</option>";
	$output .= "<option value=\"ar\"".(stripslashes($epublang) == "ar" ? " selected" : "").">Arabic</option>";
	$output .= "<option value=\"as\"".(stripslashes($epublang) == "as" ? " selected" : "").">Assamese</option>";
	$output .= "<option value=\"ay\"".(stripslashes($epublang) == "ay" ? " selected" : "").">Aymara</option>";
	$output .= "<option value=\"az\"".(stripslashes($epublang) == "az" ? " selected" : "").">Azerbaijani</option>";
	$output .= "<option value=\"ba\"".(stripslashes($epublang) == "ba" ? " selected" : "").">Bashkir</option>";
	$output .= "<option value=\"be\"".(stripslashes($epublang) == "be" ? " selected" : "").">Byelorussian</option>";
	$output .= "<option value=\"bg\"".(stripslashes($epublang) == "bg" ? " selected" : "").">Bulgarian</option>";
	$output .= "<option value=\"bh\"".(stripslashes($epublang) == "bh" ? " selected" : "").">Bihari</option>";
	$output .= "<option value=\"bi\"".(stripslashes($epublang) == "bi" ? " selected" : "").">Bislama</option>";
	$output .= "<option value=\"bn\"".(stripslashes($epublang) == "bn" ? " selected" : "").">Bengali; Bangla</option>";
	$output .= "<option value=\"bo\"".(stripslashes($epublang) == "bo" ? " selected" : "").">Tibetan</option>";
	$output .= "<option value=\"br\"".(stripslashes($epublang) == "br" ? " selected" : "").">Breton</option>";
	$output .= "<option value=\"ca\"".(stripslashes($epublang) == "ca" ? " selected" : "").">Catalan</option>";
	$output .= "<option value=\"co\"".(stripslashes($epublang) == "co" ? " selected" : "").">Corsican</option>";
	$output .= "<option value=\"cs\"".(stripslashes($epublang) == "cs" ? " selected" : "").">Czech</option>";
	$output .= "<option value=\"cy\"".(stripslashes($epublang) == "cy" ? " selected" : "").">Welsh</option>";
	$output .= "<option value=\"da\"".(stripslashes($epublang) == "da" ? " selected" : "").">Danish</option>";
	$output .= "<option value=\"de\"".(stripslashes($epublang) == "de" ? " selected" : "").">German</option>";
	$output .= "<option value=\"dz\"".(stripslashes($epublang) == "dz" ? " selected" : "").">Bhutani</option>";
	$output .= "<option value=\"el\"".(stripslashes($epublang) == "el" ? " selected" : "").">Greek</option>";
	$output .= "<option value=\"en\"".(stripslashes($epublang) == "en" ? " selected" : "").">English</option>";
	$output .= "<option value=\"eo\"".(stripslashes($epublang) == "eo" ? " selected" : "").">Esperanto</option>";
	$output .= "<option value=\"es\"".(stripslashes($epublang) == "es" ? " selected" : "").">Spanish</option>";
	$output .= "<option value=\"et\"".(stripslashes($epublang) == "et" ? " selected" : "").">Estonian</option>";
	$output .= "<option value=\"eu\"".(stripslashes($epublang) == "eu" ? " selected" : "").">Basque</option>";
	$output .= "<option value=\"fa\"".(stripslashes($epublang) == "fa" ? " selected" : "").">Persian</option>";
	$output .= "<option value=\"fi\"".(stripslashes($epublang) == "fi" ? " selected" : "").">Finnish</option>";
	$output .= "<option value=\"fj\"".(stripslashes($epublang) == "fj" ? " selected" : "").">Fiji</option>";
	$output .= "<option value=\"fo\"".(stripslashes($epublang) == "fo" ? " selected" : "").">Faeroese</option>";
	$output .= "<option value=\"fr\"".(stripslashes($epublang) == "fr" ? " selected" : "").">French</option>";
	$output .= "<option value=\"fy\"".(stripslashes($epublang) == "fy" ? " selected" : "").">Frisian</option>";
	$output .= "<option value=\"ga\"".(stripslashes($epublang) == "ga" ? " selected" : "").">Irish</option>";
	$output .= "<option value=\"gd\"".(stripslashes($epublang) == "gd" ? " selected" : "").">Scots Gaelic</option>";
	$output .= "<option value=\"gl\"".(stripslashes($epublang) == "gl" ? " selected" : "").">Galician</option>";
	$output .= "<option value=\"gn\"".(stripslashes($epublang) == "gn" ? " selected" : "").">Guarani</option>";
	$output .= "<option value=\"gu\"".(stripslashes($epublang) == "gu" ? " selected" : "").">Gujarati</option>";
	$output .= "<option value=\"ha\"".(stripslashes($epublang) == "ha" ? " selected" : "").">Hausa</option>";
	$output .= "<option value=\"hi\"".(stripslashes($epublang) == "hi" ? " selected" : "").">Hindi</option>";
	$output .= "<option value=\"hr\"".(stripslashes($epublang) == "hr" ? " selected" : "").">Croatian</option>";
	$output .= "<option value=\"hu\"".(stripslashes($epublang) == "hu" ? " selected" : "").">Hungarian</option>";
	$output .= "<option value=\"hy\"".(stripslashes($epublang) == "hy" ? " selected" : "").">Armenian</option>";
	$output .= "<option value=\"ia\"".(stripslashes($epublang) == "is" ? " selected" : "").">Interlingua</option>";
	$output .= "<option value=\"ie\"".(stripslashes($epublang) == "ie" ? " selected" : "").">Interlingue</option>";
	$output .= "<option value=\"ik\"".(stripslashes($epublang) == "ik" ? " selected" : "").">Inupiak</option>";
	$output .= "<option value=\"in\"".(stripslashes($epublang) == "in" ? " selected" : "").">Indonesian</option>";
	$output .= "<option value=\"is\"".(stripslashes($epublang) == "is" ? " selected" : "").">Icelandic</option>";
	$output .= "<option value=\"it\"".(stripslashes($epublang) == "it" ? " selected" : "").">Italian</option>";
	$output .= "<option value=\"iw\"".(stripslashes($epublang) == "iw" ? " selected" : "").">Hebrew</option>";
	$output .= "<option value=\"ja\"".(stripslashes($epublang) == "ja" ? " selected" : "").">Japanese</option>";
	$output .= "<option value=\"ji\"".(stripslashes($epublang) == "ji" ? " selected" : "").">Yiddish</option>";
	$output .= "<option value=\"jw\"".(stripslashes($epublang) == "jw" ? " selected" : "").">Javanese</option>";
	$output .= "<option value=\"ka\"".(stripslashes($epublang) == "ks" ? " selected" : "").">Georgian</option>";
	$output .= "<option value=\"kk\"".(stripslashes($epublang) == "kk" ? " selected" : "").">Kazakh</option>";
	$output .= "<option value=\"kl\"".(stripslashes($epublang) == "kl" ? " selected" : "").">Greenlandic</option>";
	$output .= "<option value=\"km\"".(stripslashes($epublang) == "km" ? " selected" : "").">Cambodian</option>";
	$output .= "<option value=\"kn\"".(stripslashes($epublang) == "kn" ? " selected" : "").">Kannada</option>";
	$output .= "<option value=\"ko\"".(stripslashes($epublang) == "ko" ? " selected" : "").">Korean</option>";
	$output .= "<option value=\"ks\"".(stripslashes($epublang) == "ks" ? " selected" : "").">Kashmiri</option>";
	$output .= "<option value=\"ku\"".(stripslashes($epublang) == "ku" ? " selected" : "").">Kurdish</option>";
	$output .= "<option value=\"ky\"".(stripslashes($epublang) == "ky" ? " selected" : "").">Kirghiz</option>";
	$output .= "<option value=\"la\"".(stripslashes($epublang) == "la" ? " selected" : "").">Latin</option>";
	$output .= "<option value=\"ln\"".(stripslashes($epublang) == "ln" ? " selected" : "").">Lingala</option>";
	$output .= "<option value=\"lo\"".(stripslashes($epublang) == "lo" ? " selected" : "").">Laothian</option>";
	$output .= "<option value=\"lt\"".(stripslashes($epublang) == "lt" ? " selected" : "").">Lithuanian</option>";
	$output .= "<option value=\"lv\"".(stripslashes($epublang) == "lv" ? " selected" : "").">Latvian, Lettish</option>";
	$output .= "<option value=\"mg\"".(stripslashes($epublang) == "mg" ? " selected" : "").">Malagasy</option>";
	$output .= "<option value=\"mi\"".(stripslashes($epublang) == "mi" ? " selected" : "").">Maori</option>";
	$output .= "<option value=\"mk\"".(stripslashes($epublang) == "mk" ? " selected" : "").">Macedonian</option>";
	$output .= "<option value=\"ml\"".(stripslashes($epublang) == "ml" ? " selected" : "").">Malayalam</option>";
	$output .= "<option value=\"mn\"".(stripslashes($epublang) == "mn" ? " selected" : "").">Mongolian</option>";
	$output .= "<option value=\"mo\"".(stripslashes($epublang) == "mo" ? " selected" : "").">Moldavian</option>";
	$output .= "<option value=\"mr\"".(stripslashes($epublang) == "mr" ? " selected" : "").">Marathi</option>";
	$output .= "<option value=\"ms\"".(stripslashes($epublang) == "ms" ? " selected" : "").">Malay</option>";
	$output .= "<option value=\"mt\"".(stripslashes($epublang) == "mt" ? " selected" : "").">Maltese</option>";
	$output .= "<option value=\"my\"".(stripslashes($epublang) == "my" ? " selected" : "").">Burmese</option>";
	$output .= "<option value=\"na\"".(stripslashes($epublang) == "na" ? " selected" : "").">Nauru</option>";
	$output .= "<option value=\"ne\"".(stripslashes($epublang) == "ne" ? " selected" : "").">Nepali</option>";
	$output .= "<option value=\"nl\"".(stripslashes($epublang) == "nl" ? " selected" : "").">Dutch</option>";
	$output .= "<option value=\"no\"".(stripslashes($epublang) == "no" ? " selected" : "").">Norwegian</option>";
	$output .= "<option value=\"oc\"".(stripslashes($epublang) == "oc" ? " selected" : "").">Occitan</option>";
	$output .= "<option value=\"om\"".(stripslashes($epublang) == "om" ? " selected" : "").">(Afan) Oromo</option>";
	$output .= "<option value=\"or\"".(stripslashes($epublang) == "or" ? " selected" : "").">Oriya</option>";
	$output .= "<option value=\"pa\"".(stripslashes($epublang) == "pa" ? " selected" : "").">Punjabi</option>";
	$output .= "<option value=\"pl\"".(stripslashes($epublang) == "pl" ? " selected" : "").">Polish</option>";
	$output .= "<option value=\"ps\"".(stripslashes($epublang) == "ps" ? " selected" : "").">Pashto, Pushto</option>";
	$output .= "<option value=\"pt\"".(stripslashes($epublang) == "pt" ? " selected" : "").">Portuguese</option>";
	$output .= "<option value=\"qu\"".(stripslashes($epublang) == "qu" ? " selected" : "").">Quechua</option>";
	$output .= "<option value=\"rm\"".(stripslashes($epublang) == "rm" ? " selected" : "").">Rhaeto-Romance</option>";
	$output .= "<option value=\"rn\"".(stripslashes($epublang) == "rn" ? " selected" : "").">Kirundi</option>";
	$output .= "<option value=\"ro\"".(stripslashes($epublang) == "ro" ? " selected" : "").">Romanian</option>";
	$output .= "<option value=\"ru\"".(stripslashes($epublang) == "ru" ? " selected" : "").">Russian</option>";
	$output .= "<option value=\"rw\"".(stripslashes($epublang) == "rw" ? " selected" : "").">Kinyarwanda</option>";
	$output .= "<option value=\"sa\"".(stripslashes($epublang) == "sa" ? " selected" : "").">Sanskrit</option>";
	$output .= "<option value=\"sd\"".(stripslashes($epublang) == "sd" ? " selected" : "").">Sindhi</option>";
	$output .= "<option value=\"sg\"".(stripslashes($epublang) == "sg" ? " selected" : "").">Sangro</option>";
	$output .= "<option value=\"sh\"".(stripslashes($epublang) == "sh" ? " selected" : "").">Serbo-Croatian</option>";
	$output .= "<option value=\"si\"".(stripslashes($epublang) == "si" ? " selected" : "").">Singhalese</option>";
	$output .= "<option value=\"sk\"".(stripslashes($epublang) == "sk" ? " selected" : "").">Slovak</option>";
	$output .= "<option value=\"sl\"".(stripslashes($epublang) == "sl" ? " selected" : "").">Slovenian</option>";
	$output .= "<option value=\"sm\"".(stripslashes($epublang) == "sm" ? " selected" : "").">Samoan</option>";
	$output .= "<option value=\"sn\"".(stripslashes($epublang) == "sn" ? " selected" : "").">Shona</option>";
	$output .= "<option value=\"so\"".(stripslashes($epublang) == "so" ? " selected" : "").">Somali</option>";
	$output .= "<option value=\"sq\"".(stripslashes($epublang) == "sq" ? " selected" : "").">Albanian</option>";
	$output .= "<option value=\"sr\"".(stripslashes($epublang) == "sr" ? " selected" : "").">Serbian</option>";
	$output .= "<option value=\"ss\"".(stripslashes($epublang) == "ss" ? " selected" : "").">Siswati</option>";
	$output .= "<option value=\"st\"".(stripslashes($epublang) == "st" ? " selected" : "").">Sesotho</option>";
	$output .= "<option value=\"su\"".(stripslashes($epublang) == "su" ? " selected" : "").">Sundanese</option>";
	$output .= "<option value=\"sv\"".(stripslashes($epublang) == "sv" ? " selected" : "").">Swedish</option>";
	$output .= "<option value=\"sw\"".(stripslashes($epublang) == "sw" ? " selected" : "").">Swahili</option>";
	$output .= "<option value=\"ta\"".(stripslashes($epublang) == "ta" ? " selected" : "").">Tamil</option>";
	$output .= "<option value=\"te\"".(stripslashes($epublang) == "tr" ? " selected" : "").">Tegulu</option>";
	$output .= "<option value=\"tg\"".(stripslashes($epublang) == "tg" ? " selected" : "").">Tajik</option>";
	$output .= "<option value=\"th\"".(stripslashes($epublang) == "th" ? " selected" : "").">Thai</option>";
	$output .= "<option value=\"ti\"".(stripslashes($epublang) == "ti" ? " selected" : "").">Tigrinya</option>";
	$output .= "<option value=\"tk\"".(stripslashes($epublang) == "tk" ? " selected" : "").">Turkmen</option>";
	$output .= "<option value=\"tl\"".(stripslashes($epublang) == "tl" ? " selected" : "").">Tagalog</option>";
	$output .= "<option value=\"tn\"".(stripslashes($epublang) == "tn" ? " selected" : "").">Setswana</option>";
	$output .= "<option value=\"to\"".(stripslashes($epublang) == "to" ? " selected" : "").">Tonga</option>";
	$output .= "<option value=\"tr\"".(stripslashes($epublang) == "tr" ? " selected" : "").">Turkish</option>";
	$output .= "<option value=\"ts\"".(stripslashes($epublang) == "ts" ? " selected" : "").">Tsonga</option>";
	$output .= "<option value=\"tt\"".(stripslashes($epublang) == "tt" ? " selected" : "").">Tatar</option>";
	$output .= "<option value=\"tw\"".(stripslashes($epublang) == "tw" ? " selected" : "").">Twi</option>";
	$output .= "<option value=\"uk\"".(stripslashes($epublang) == "uk" ? " selected" : "").">Ukrainian</option>";
	$output .= "<option value=\"ur\"".(stripslashes($epublang) == "ur" ? " selected" : "").">Urdu</option>";
	$output .= "<option value=\"uz\"".(stripslashes($epublang) == "uz" ? " selected" : "").">Uzbek</option>";
	$output .= "<option value=\"vi\"".(stripslashes($epublang) == "vi" ? " selected" : "").">Vietnamese</option>";
	$output .= "<option value=\"vo\"".(stripslashes($epublang) == "vo" ? " selected" : "").">Volapuk</option>";
	$output .= "<option value=\"wo\"".(stripslashes($epublang) == "wo" ? " selected" : "").">Wolof</option>";
	$output .= "<option value=\"xh\"".(stripslashes($epublang) == "xh" ? " selected" : "").">Xhosa</option>";
	$output .= "<option value=\"yo\"".(stripslashes($epublang) == "yo" ? " selected" : "").">Yoruba</option>";
	$output .= "<option value=\"zh\"".(stripslashes($epublang) == "zh" ? " selected" : "").">Chinese</option>";
	$output .= "<option value=\"zu\"".(stripslashes($epublang) == "zu" ? " selected" : "").">Zulu</option>";
	$output .= "</select>";
	$output .= "</TD><TD>"._EPUBLANGDESC."</TD></TR><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBTIDY.": </b><br /><select name=\"epubtidy\" >";
	if (extension_loaded('tidy')){
		$output .= "<option value=\"1\"".(stripslashes($epubtidy) == "1" ? " selected" : "").">TIDY</option>";
	}
	$output .= "<option value=\"2\"".(stripslashes($epubtidy) == "2" ? " selected" : "").">PURE</option>";
	if (extension_loaded('tidy')){
		$output .= "<option value=\"3\"".(stripslashes($epubtidy) == "3" ? " selected" : "").">TIDY+PURE</option>";
	}
	$output .= "<option value=\"0\"".(stripslashes($epubtidy) == "0" ? " selected" : "").">NONE</option>";
	$output .= "</select>";
	$output .= "</TD><TD>"._EPUBTIDYDESC."</TD></TR><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBRW.": </b><br /><select name=\"epubrw\" ".(function_exists('apache_get_modules') ? (!apache_get_modules(mod_rewrite) ? " disabled" : "") : "").">";
	$output .= "<option value=\"1\"".(stripslashes($epubrw) == "1" ? " selected" : "").">"._YES."</option>";
	$output .= "<option value=\"0\"".(stripslashes($epubrw) == "0" ? " selected" : "").">"._NO."</option>";
	$output .= "</select>";
	$output .= "</TD><TD>"._EPUBRWDESC."</TD></TR><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBIMG.": </b><br /><select name=\"epubimg\" ".(!extension_loaded('curl') && !ini_get('allow_url_fopen') ? " disabled" : "").">";

	$output .= "<option value=\"0\"".(stripslashes($epubimg) == "0" ? " selected" : "").">NONE</option>";
	if (extension_loaded('curl')){
		$output .= "<option value=\"1\"".(stripslashes($epubimg) == "1" ? " selected" : "").">CURL</option>";
	}
	if (ini_get('allow_url_fopen')){
		$output .= "<option value=\"2\"".(stripslashes($epubimg) == "2" ? " selected" : "").">URL_FOPEN</option>";
	}
	$output .= "</select>";
	$output .= "</TD><TD>"._EPUBIMGDESC."</TD></TR><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBSITENAME.": </b><br /><input type=\"text\" name=\"epubsitename\" value=\"".stripslashes($epubsitename)."\" />";
	$output .= "</TD><TD>"._EPUBSITENAMEDESC."</TD></TR><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBTAGLINE.": </b><br /><input type=\"text\" name=\"epubtagline\" value=\"".stripslashes($epubtagline)."\" />";
	$output .= "</TD><TD>"._EPUBTAGLINEDESC."</TD></TR><TR><TD valign=\"TOP\">";
	$output .= "<b>"._EPUBURL.": </b><br /><input type=\"text\" name=\"epuburl\" value=\"".stripslashes($epuburl)."\" />";
	$output .= "</TD><TD>"._EPUBURLDESC."</TD></TR>";
        $output .="<tr><td class='tblborder'><b>"._EPUBCOVER.": </b><select name=\"epubcover\" >";
        $output .= "<option value=\"\"".(stripslashes($epubcover) == "" ? " selected" : "").(!$epubcover ? " selected" : "").">NONE</option>";
        $output .= "</optgroup><optgroup label=\"Site Images\">";
	if (is_dir(_BASEDIR.$coverdir)) {
      		$coverimages = opendir(_BASEDIR.$coverdir);
		while (($cfile = readdir($coverimages))) {
                	$coverfiles[] = $cfile;
         	}
                closedir($coverimages);
                sort($coverfiles);
                reset($coverfiles);
                foreach ($coverfiles as $scover){
                	if ($scover != "." && $scover != "..") {
                        	$output .= "<option value=\"".$coverdir.$scover."\"".(stripslashes($epubcover) == $coverdir.$scover ? " selected" : "").">".$scover."</option>";
                        }
                }
                unset($coverfiles);
        }
        $output .= "</optgroup></select></td><TD>"._EPUBCOVERDESC."</TD></TR></TABLE>";
	$output .= "<fieldset><legend>"._EPUBICONLBL."</legend><div style=\"overflow:scroll;white-space: nowrap;height:150px;width:600px;\">";
	while (($icon = readdir($icondir)) !== false){
		if ($icon != "." && $icon != "..") {
			$output .= "<INPUT type=\"radio\" name=\"epubicon\" value=\"modules/epubversion/images/".$icon."\"".(pathinfo(stripslashes($epubicon), PATHINFO_BASENAME) == $icon ? " checked" : "")."><IMG SRC=\"modules/epubversion/images/".$icon."\" align=\"middle\">";
	  	}
	}
	closedir($icondir);
	$output .= "</div></fieldset><br />"._DYNBAN."<br /><fieldset><legend>"._EPUBBANLBL."</legend><div style=\"overflow:scroll;white-space: nowrap;height:150px;width:600px;\">";
	if (extension_loaded('gd') && function_exists('imagecreatefrompng')){
		$output .= "<INPUT type=\"radio\" name=\"epubsitelogo\" id=\"\" value=\"modules/epubversion/dynban.php\"".(pathinfo(stripslashes($epubsitelogo), PATHINFO_BASENAME) == "dynban.php" ? " checked" : "")."><IMG SRC=\"modules/epubversion/dynban.php\" align=\"middle\">";
	}
	while (($banner = readdir($bannerdir)) !== false){
		if ($banner != "." && $banner != "..") {
			$output .= "<INPUT type=\"radio\" name=\"epubsitelogo\" id=\"\" value=\"modules/epubversion/banners/".$banner."\"".(pathinfo(stripslashes($epubsitelogo), PATHINFO_BASENAME) == $banner ? " checked" : "")."><IMG SRC=\"modules/epubversion/banners/".$banner."\" align=\"middle\">";
		}
	}
	closedir($bannerdir);
	$output .= "</div></fieldset>";
	$output .= "<div id='submitdiv' align=\"center\"><INPUT type=\"submit\" id=\"submit\" class=\"button\" name=\"submit\" value=\""._SUBMIT."\"></div></form></div></TD></TR></TABLE>";
}
?>
