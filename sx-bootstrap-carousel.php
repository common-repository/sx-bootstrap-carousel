<?php
/*
Plugin Name: SX Bootstrap Carousel
Plugin URI: http://www.redweb.tn/bootstrap-carousel/
Description: A custom post type for choosing images and content which outputs <a href="http://getbootstrap.com/javascript/#carousel" target="_blank">Bootstrap Carousel</a> from a shortcode. Requires Bootstrap javascript and CSS to be loaded separately.
Version: 1.0.0
Author: Sabri El Gueder
Author URI: http://www.sabri-elgueder.tn
Text Domain: sx-bootstrap-carousel
License: GPLv2
*/

// Initialise - load in translations
function sxbc_loadtranslations () {
	$plugin_dir = basename(dirname(__FILE__)).'/languages';
	load_plugin_textdomain( 'sx-bootstrap-carousel', false, $plugin_dir );
}
add_action('plugins_loaded', 'sxbc_loadtranslations');

////////////////////////////
// Custom Post Type Setup
////////////////////////////
add_action( 'init', 'sxbc_post_type' );
function sxbc_post_type() {
	$labels = array(
		'name' => __('Carousel Images', 'sx-bootstrap-carousel'),
		'singular_name' => __('Carousel Image', 'sx-bootstrap-carousel'),
		'add_new' => __('Add New', 'sx-bootstrap-carousel'),
		'add_new_item' => __('Add New Carousel Image', 'sx-bootstrap-carousel'),
		'edit_item' => __('Edit Carousel Image', 'sx-bootstrap-carousel'),
		'new_item' => __('New Carousel Image', 'sx-bootstrap-carousel'),
		'view_item' => __('View Carousel Image', 'sx-bootstrap-carousel'),
		'search_items' => __('Search Carousel Images', 'sx-bootstrap-carousel'),
		'not_found' => __('No Carousel Image', 'sx-bootstrap-carousel'),
		'not_found_in_trash' => __('No Carousel Images found in Trash', 'sx-bootstrap-carousel'),
		'parent_item_colon' => '',
		'menu_name' => __('Carousel', 'sx-bootstrap-carousel')
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'exclude_from_search' => true,
		'publicly_queryable' => false,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => 21,
		'menu_icon' => 'dashicons-images-alt',
		'supports' => array('title','editor','thumbnail', 'page-attributes')
	); 
	register_post_type('sxbc', $args);
}
// Create a taxonomy for the carousel post type
function sxbc_taxonomies () {
	$args = array('hierarchical' => true);
	register_taxonomy( 'carousel_category', 'sxbc', $args );
}
add_action( 'init', 'sxbc_taxonomies', 0 );


// Add theme support for featured images if not already present
// http://wordpress.stackexchange.com/questions/23839/using-add-theme-support-inside-a-plugin
function sxbc_addFeaturedImageSupport() {
	$supportedTypes = get_theme_support( 'post-thumbnails' );
	if( $supportedTypes === false ) {
		add_theme_support( 'post-thumbnails', array( 'sxbc' ) );	  
		add_image_size('featured_preview', 100, 55, true);
	} elseif( is_array( $supportedTypes ) ) {
		$supportedTypes[0][] = 'sxbc';
		add_theme_support( 'post-thumbnails', $supportedTypes[0] );
		add_image_size('featured_preview', 100, 55, true);
	}
}
add_action( 'after_setup_theme', 'sxbc_addFeaturedImageSupport');

// Load in the pages doing everything else!
require_once('sxbc-admin.php');
require_once('sxbc-settings.php');
require_once('sxbc-frontend.php');

