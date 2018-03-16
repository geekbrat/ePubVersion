ePubVersion for eFiction 3.x
----------
Copyright (c) 2010 by Kirstyn Amanda Fox

http://storyportal.net/epub

Based on:
 - Display Word Module developed for eFiction 3.0
  - - Copyright (c) 2006 by Tammy Keefer

http://efiction.hugosnebula.com/

LICENSE
----------
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License (GPL) as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

To read the license please visit http://www.gnu.org/copyleft/gpl.html

Requirements:
----------
 - PHP 5.3.2
 -  PHP Compiled with DOM and PCRE
 -  eFiction 3.5
 -  Optional:
 -  PHP Compiled with Tidy and GD
 -  PHP GD with PNG/JPEG/GIF support
 -  APACHE Mod_Rewrite

To install this module:
----------
1. Upload the entire epubversion folder to the the modules folder within your eFiction installation.

2. Go to http://yoursite.com/admin.php?action=modules or http://www.yoursite.com/modules/epubversion/install.php where www.yoursite.com is your eFiction site's address.

3. Goto the main default_tpls folder. Open up viewstory.tpl and storyindex.tpl and add {printepub} where you want the ePub icon/options to appear.

4. Do the same for any skins with their own viewstory.tpl and/or storyindex.tpl You can define $epubicon in your skin's variables.php to override the default ePub icon if you so desire. 

 - - There is one language definition in the en.php file in the languages folderb that provides the alt text for the ePub icon as well as the various text bits for the rest of the expanded modules..

- - The configuration variables that used to be set at the top of epubversion.php are now set via the modules admin options panel. You can access this by visiting http://yoursite.com/admin.php?action=modules&module=epubversion&admin=true or by goting to the "Admin" page, clicking on "Modules" and then clicking on the "Options" link next to ePubVersion.

- - These variables are used to configure you "Brought to you by" line on the cover page, and the OPF's publisher information line

- - This module now has the ability for Authors to turn on/off ePub editions for each of their stories. This is done via the User/Account menu, by clicking on "Manage ePub Stories". By default, the ePub bit is set to "0" or "Off" for all authors. They must "Opt In"

5. We have added several new tags to ePubVersion that can be added to your templates.

- `{printepub}` -- Displays a direct Download link for ePub version of a Story.

- `{printthumb}` -- Displays a thumbnail Image of the epub/Book Cover is one has been set in "Manage ePub Stories". 
-  - Cover image is dynamicaly resized so that max width or height is no larger than 100px. Original Aspect ratios are kept.
- - Requires PHP Compiled with GD, and GD compiled with PNG/JPG/GIF support.

- `{cover}` -- Displays an Image of the epub/Book Cover is one has been set in "Manage ePub Stories". 
-  - Cover image is dynamicaly resized so that max width or height is no larger than 250px. Original Aspect ratios are kept.
- - Requires PHP Compiled with GD, and GD compiled with PNG/JPG/GIF support.

- `{epubcount}` -- Returns the number of times a Story has been downloaded as "epub" in numerical form.
----------

*Below you will find sample usage of the new skin tags as found in my index.tpl (recentblock) and listings.tpl (storyblock). I have made use of CSS to make the various "boxes" size properly around the images.*

----------

    <!\-\- START BLOCK : recentblock -->
    
    <div style="min-height:100px; height:auto!important; height:100px;">
    
    <span style="font-weight: bold; font-size: 1.2em; border-bottom: 1px dashed #999; margin-bottom: 5px;">
    
    {printthumb}{title} by {author} {printepub}</span><br />
    
    <span class="label">Summary: </span>{summary}<br />
    
    <span class="label">Rated:</span> {rating} {score} <span class="label">Categories:</span> {category} {classes}</span>
    
    <br />
    
    </div>
    
    <hr>
    
    <!\-\- END BLOCK : recentblock -->
```
   <!\-\- START BLOCK : storyblock -->
    
    <div class="listbox {oddeven}">
    
    <div class="title"><span class="t2">{title}</span> by {author} <span class="label">Rated:</span> {rating} {roundrobin} {score} \[{reviews} - {numreviews}\] {new} </div>
    
    <div style="min-height:250px; height:auto!important; height:250px;">
    
    {cover}
    
    <div class="content"><span class="label">Summary: </span>{featuredstory}{summary}<br />
    
    <span class="label">Categories:</span> {category} <br />
    
    <span class="label">Characters: </span> {characters}<br />
    
    {classifications}
    
    <span class="label">Series:</span> {serieslinks}<br />
    
    <span class="label">Chapters: </span> {numchapters} {toc}<br />
    
    <span class="label">Completed:</span> {completed}
    
    <span class="label">Word count:</span> {wordcount} <span class="label">Read Count:</span> {count} <span class="label">ePub Downloads:</span> {epubcount}
    
    {adminlinks}</div></div>
    
    <div class="tail"><span class="label">{printepub} {addtofaves} {reportthis} Published: </span>{published} <span class="label">Updated:</span> {updated}</div>
    
    </div>
    
    {comment}
    
    <!\-\- END BLOCK : storyblock -->
```
To uninsall this module:
----------
1. Go to http://www.yoursite.com/modules/epubversion/uninstall.php where www.yoursite.com is your eFiction site's address.

> Written with [StackEdit](https://stackedit.io/).
