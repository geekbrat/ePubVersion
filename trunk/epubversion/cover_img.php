<?php

/**
 * @author Kirstyn Amanda Fox (GeekBrat@Gmail.Com)
 * 100$ all original code by me! It may not be elegant, but I'm frikkin proud of it!
 * @copyright 2011
 */

function coverImage($site_host, $book, $cover_img, $epubimg){

//echo $site_host."\n";
//echo $book."\n";
//echo $cover_img."\n";
//echo $epubimg."\n";

   if ($epubimg != "0"){

//Add Trailing slash to $site_host just in case....

	$site_host=$site_host."/";


// Take links, determin if jpg, png, or gif (for each)


			$scheme=strtolower(parse_url($cover_img, PHP_URL_SCHEME));
			if ($scheme=="http"||$scheme=="https"||$scheme=="ftp"){
				$img_san=$cover_imgl;
			}
			else if (!$scheme){
				$img_san=$site_host.$cover_img;
			}    
//Determine the image type....
//			$path_parts = pathinfo('/fake/path/$cover_img');
//			$img_type ==  $path_parts['extension'];

			preg_match('/png|gif|jpg|jpe|jpeg|php/i', substr($cover_img,-4),$img_type,PREG_OFFSET_CAPTURE); 

//			print_r($img_type[0][0]);

			if (strtolower($img_type[0][0]) == "php") {
				preg_match('/dynban.php/i', $cover_img,$img_type);
			}
//Make $img_type all lower case
//			if (isset($img_type[0]))(
			$img_type = strtolower($img_type[0][0]);
//			$img_type = strtolower($img_type);

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
              /* Create a blank image */

        			$img  = imagecreatetruecolor(150, 30);
        			$bgc = imagecolorallocate($img, 255, 255, 255);
        			$tc  = imagecolorallocate($img, 0, 0, 0);

        			imagefilledrectangle($img, 0, 0, 150, 30, $bgc);

        /* Output an error message */
				$img_error="Error Loading: ".$img_san;
        			imagestring($img, 1, 5, 5, $img_error, $tc); 
        			$mimetype = "image/png";
        			$img_type = "png";
    			}


    			$img_filename = "cover.".$img_type;
    			$img_fileid = "cover".$img_type;
    			$book->addFile($img_filename, $img_fileid,  $img, $mimetype);
//			echo($img_filename);
		}
		
//	)

return $img_filename;
}
?>
