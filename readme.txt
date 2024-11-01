=== SX Bootstrap Carousel ===
Contributors: sabrex82
Tags: carousel, slider, image, bootstrap
Requires at least: 3.3.6 
Tested up to: 4.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Carousel plugin is a component for cycling through elements, like a carousel (slideshow).

== Description ==

A custom post type for choosing images and content which outputs a [carousel](http://getbootstrap.com/javascript/#carousel) from [Twitter Bootstrap](http://www.getbootstrap.com) using the shortcode `[sx-carousel]`. 

The plugin assumes that you're already using Bootstrap, so you need to load the Bootstrap javascript and CSS separately.

* [Download Twitter Bootstrap](http://getbootstrap.com/)
* [Bootstrap Carousel in action](http://getbootstrap.com/javascript/#carousel)

If you'd like to contribute to this plugin, you can find it [hosted on GitHub](https://github.com/redweb-tn/sx-bootstrap-carousel).

= Shortcode Options =
If you'd like different settings for different carousels, you can override these by using shortcode options...

* `interval` _(default 5000)_
    * Length of time for the caption to pause on each image. Time in milliseconds.
`[sx-carousel interval="12000"]`


* `showcaption` _(default true)_
    * Whether to display the text caption on each image or not. `true` or `false`.
`[sx-carousel showcaption="false"]`


* `showcontrols` _(default true)_
    * Whether to display the control arrows or not. `true` or `false`.
`[sx-carousel showcontrols="false"]`


* `orderby` and `order` _(default `menu_order` `ASC`)_
	* What order to display the posts in. Uses [WP_Query terms](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters).
`[sx-carousel orderby="rand"]
[sx-carousel orderby="date" orderby="DESC"]`


* `category` _(default all)_
	* Filter carousel items by a comma separated list of carousel category slugs.
`[sx-carousel category="homepage,highlights"]`


* `id` _(default all)_
	* Specify the ID of a specific carousel post to display only one image.
	* Find the image ID by looking at the edit post link, eg. post 109 would be `/wp-admin/post.php?post=109&action=edit`
`[sx-carousel id="109"]`


* `twbs` _(default 2)_
	* Output markup for Twitter Bootstrap Version 2 or 3.
`[sx-carousel twbs="3"]`


= Contributing =

If you would like to contribute to this plugin, please go to the [GitHub repository](https://github.com/redweb-tn/sx-bootstrap-carousel) and make a personal fork of the development version. You can then make your changes and submit a pull request. I will happily review the code and then merge when we're both happy. You can read more details [here](https://github.com/redweb-tn/sx-bootstrap-carousel/blob/master/CONTRIBUTING.md).

== Installation ==

= The easy way: =

1. Go to the Plugins Menu in WordPress
1. Search for "SX Bootstrap Carousel"
1. Click 'Install'
1. Activate the plugin

= Manual Installation =

1. Download the plugin file from this page and unzip the contents
1. Upload the `sx-bootstrap-carousel` folder to the `/wp-content/plugins/` directory
1. Activate the `sx-bootstrap-carousel` plugin through the 'Plugins' menu in WordPress

= Once Activated =

1. Make sure that your theme is loading the [Twitter Bootstrap](http://www.getbootstrap.com) CSS and Carousel javascript
1. Place the `[sx-carousel]` shortcode in a Page or Post
1. Create new items in the `Carousel` post type, uploading a Featured Image for each.
	1. *Optional:* You can hyperlink each image by entering the desired url `Image Link URL` admin metabox when adding a new carousel image.


== Frequently Asked Questions ==

= The carousel doesn't start sliding itself / setting interval doesn't work =

This can be caused by having your jQuery and Bootstrap javascript files included in the wrong place.

* Make sure that jQuery is only being included once
* Make sure that the Bootstrap javascript file is being included after jQuery
	* NB: This often means putting it after `wp_head()` in your theme's `header.php` file
* Make sure that both jQuery and Bootstrap are being included in the theme header, not footer
* Make sure that the Bootstrap javascript file is referenced _after_ the jQuery file.

= How do I insert the carousel? =

First of all, install and activate the plugin. Go to 'Carousel' in the WordPress admin pages and add some images. Then, insert the carousel using the `[sx-carousel]` into the body of any page.

= Can I insert the carousel into a WordPress template instead of a page? =

Absolutely - you just need to use the [do_shortcode](http://codex.wordpress.org/Function_Reference/do_shortcode) WordPress function. For example:
`<?php echo do_shortcode('[sx-carousel]'); ?>`

= I get grey bars at the side of my images / The image isn't aligned (or doesn't reach the far side of the carousel) =

This happens when the carousel is bigger than your images. Either upload higher resolution images, or select the "Use background images?" option in the settings (this will stretch the images though, so they may get a little blurry).

= Can I change the order that the images display in? =

You can specify the order that the carousel displays images by changing the setting in the Settings page, or by using the `orderby` and `order` shortcode attributes. The settings page has common settings, or you can use any terms described for the [WP_Query orderby terms](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters) for the shortcode.

= Can I have different carousels with different images on the same site? =

Yes - create a few categories and add your specific images to a specific category. Then, when inserting the shortcode into the page, specify which category you want it to display images from using the `category` shortcode attribute.

= Can I customise the way it looks / works? =

The carousel shortcode has a number of attributes that you can use to customise the output. These are described on the main plugin [Description](http://wordpress.org/plugins/sx-bootstrap-carousel/) page.

= Help! Nothing is showing up at all =

1. Is the plugin installed and activated?
1. Have you added any items in the `Carousel` post type?
1. Have you placed the `[sx-carousel]` shortcode in your page?

Try writing the shortcode using the 'Text' editor instead of the 'Visual' editor, as the visual editor can sometimes add extra unwanted markup.

= My images are showing but they're all over the place =

Is your theme loading the Bootstrap CSS and Javascript? _(look for `bootstrap.css` in the source HTML)_

= The carousel makes the content jump each time it changes =

You need to make sure that each image is the same height. You can do this by setting an `Aspect ratio` in the `Edit Image` section of the WordPress Media Library and cropping your images.

== Screenshots ==

1. Admin list interface showing Carousel images and titles.
2. Admin image interface showing optional title and caption (Excerpt) fields, along with Category, order, image and URL
3. Admin Screen for Settings Carousel
4. Example output. Requires Bootstrap CSS and Javascript to be loaded (see documentation).

== Changelog ==

= 1.0 =
* Initial release
