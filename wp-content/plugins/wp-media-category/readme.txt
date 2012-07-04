=== WP Media Category ===
Contributors: tiutalk
Author URI: http://thiagobelem.net/
Tags: attachments, categories, media, uploads, category, taxonomy
Requires at least: 2.9
Tested up to: 3.0-beta1
Stable tag: 0.2.1b

== Description ==

Include the category taxonomy to your media files.

No configuration needed: just *Plug&Play*!

This is a functional beta release! :)

== Installation ==

1. Upload the extracted plugin folder and contained files to your `/wp-content/plugins/` directory using FTP
2. Activate the plugin through the '**Plugins**' menu in WordPress
3. Go to your **Media Library** and edit one file or upload a new one

Check the screenshots tab to see where the new field'll appear.

= Usage =

You can list all the media files from a category using the [get_posts()](http://codex.wordpress.org/Template_Tags/get_posts) WordPress function on your theme files.

Here's an example of usage:

	<?php
	// Create an array with all medias from category #2 
	$args = array(
		'post_type' => 'attachment', // search only attachments (media files)
		'cat' => '2', // from category #2
		'numberposts' => -1 // unlimited results
	);
	$medias = get_posts($args);
	// Now you can loop through $medias array and list the files! :)
	?>

== Frequently Asked Questions ==

= What taxonomy this plugin use? =

This plugin use the `category` taxonomy

= How many categories a media can belongs to? =

Until now, the media can be added to **only one** category.

On future versions i'll add support to more than one category.

== Screenshots ==

1. The category select field is inserted at the end of the "Edit media" page
2. When you select a category that has childs a new select input will appear

== Changelog == 

= 0.2.1b =
* jQuery 1.4.2 include fix

= 0.2b =
* Beta release

= 0.1.2 & 0.1.3 =
* jQuery 1.4.2 addition

= 0.1.1 =
* Small bugfix

= 0.1 =
* First release
* Translations: en-US & pt-BR

== Upgrade Notice ==

= 0.2.1b =
The version 0.1.x has bugs.. Update to see the plugin running smoothly! :)
