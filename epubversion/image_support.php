<?php

/**
 * @author Kirstyn Amanda Fox (GeekBrat@Gmail.Com)
 * 100$ all original code by me! It may not be elegant, but I'm frikkin proud of it!
 * @copyright 2011
 */

function getImage($url, $epubimg) {
	if ($epubimg == "1"){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$fileContents = curl_exec($ch);
		curl_close($ch);
		return $fileContents;
	}
	else if ($epubimg == "2"){
		$fileContents = file_get_contents($url);
		return $fileContents;
	}
	else {
		exit();
	}
}


function addImages($story, $site_host, $book, $ch_num, $epubimg){

   if ($epubimg != "0"){

//Add Trailing slash to $site_host just in case....

	$site_host=$site_host."/";

// Take variable $story, search for links, load them into array media

	preg_match_all('/(img|src)\=(\"|\')[^\"\'\>]+/i', $story, $media);

// Take media[0] and filter down to just the url and store it as img_src

	$img_src=preg_replace('/(img|src)(\"|\'|\=\"|\=\')(.*)/i',"$3",$media[0]);


// Check to see if any images were found, if none were found, return the same story text with no processing.

	if (!$img_src) {return $story;}

// Define a new empty array for $new_img and img_replace

		$img_new = array ();
		$img_replace = array ();

// Take links, determin if jpg, png, or gif (for each)

		$img_num = 0;
		$img_unique = array_unique($img_src);
		foreach( $img_unique as $img_url){

//Following code was defined to determine if $img_url is a valid offsite url, and turn it into one if it is not.

			$scheme=strtolower(parse_url($img_url, PHP_URL_SCHEME));
			if ($scheme=="http"||$scheme=="https"||$scheme=="ftp"){
				$img_san=$img_url;
			}
			else if (!$scheme){
				$img_san=$site_host.$img_url;
			}    
//Determine the image type....

			preg_match('/png|gif|jpg|jpe|jpeg|php/i', substr($img_url,-4),$img_type,PREG_OFFSET_CAPTURE);

                        if (strtolower($img_type[0][0]) == "php") {
                                preg_match('/dynban.php/i', $img_url,$img_type);
                        }
//			preg_match('/png|gif|jpg|jpe|jpeg|dynban.php/i', $img_url,$img_type); 

//Make $img_type all lower case
			$img_type = strtolower($img_type[0][0]);

    // If PNG
			if ($img_type == "png") {
        			$img = getImage($img_san, $epubimg);
        			$mimetype = "image/png";
			}

    // If GIF
			else if ($img_type == "gif") {
        			$img = getImage($img_san, $epubimg);
        			$mimetype = "image/gif";
			}

    // If JPG
    			else if ($img_type == "jpg" || $img_type == "jpeg" || $img_type == "jpe") {
        			$img = getImage($img_san, $epubimg);
        			$mimetype = "image/jpeg";
    			}

			else if ($img_type == "dynban.php") {
				$img = getImage($img_san, $epubimg);
				$mimetype = "image/png";
				$img_type="png";
			}
    // ELSE
    			else {
				$img = "There was an error retrieving image ".$img_san." . Sorry.";
        			$mimetype = "text";
        			$img_type = "txt";

    			}


    			$img_filename = "image".$ch_num."-".$img_num.".".$img_type;
    			$img_fileid = "image".$ch_num.$img_num.$img_type;
    			$img_new[] = $img_filename;
    			$img_replace[] = "'".$img_url."'";
    			$book->addFile($img_filename, $img_fileid,  $img, $mimetype);
    			$img_num++;
		}
// Take image urls, and search and replace them in $story and save them as $story_sanitized

		$story_san=preg_replace($img_replace, $img_new, $story);
		return $story_san;
	}
    else {
   $story_san=preg_replace("/<img src=[^>]+>/i", "", $story);	
   return $story_san;
   }
}
?>
