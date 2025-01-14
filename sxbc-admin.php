<?php
/*****************************************************
* SX Bootstrap Carousel
* http://www.redweb.tn/bootstrap-carousel/
* ----------------------------------------------------
* sxbc-admin.php
* Code to customise the WordPress admin pages
******************************************************/

///////////////////
// ADMIN PAGES
///////////////////

// Add column in admin list view to show featured image
// http://wp.tutsplus.com/tutorials/creative-coding/add-a-custom-column-in-posts-and-custom-post-types-admin-screen/
function sxbc_get_featured_image($post_ID) {  
	$post_thumbnail_id = get_post_thumbnail_id($post_ID);  
	if ($post_thumbnail_id) {  
		$post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');  
		return $post_thumbnail_img[0];  
	}  
}
function sxbc_columns_head($defaults) {  
	$defaults['featured_image'] = __('Featured Image', 'sx-bootstrap-carousel');  
	$defaults['category'] = __('Category', 'sx-bootstrap-carousel');  
	return $defaults;  
}  
function sxbc_columns_content($column_name, $post_ID) {  
	if ($column_name == 'featured_image') {  
		$post_featured_image = sxbc_get_featured_image($post_ID);  
		if ($post_featured_image) {  
			echo '<a href="'.get_edit_post_link($post_ID).'"><img src="' . $post_featured_image . '" alt="" style="max-width:100%;" /></a>';  
		}  
	}
	if ($column_name == 'category') {  
		$post_categories = get_the_terms($post_ID, 'carousel_category');
		if ($post_categories) {
			$output = '';
			foreach($post_categories as $cat){
				$output .= $cat->name.', ';
			}
			echo trim($output, ', ');
		} else {
			echo 'No categories';
		}
	}
}
add_filter('manage_sxbc_posts_columns', 'sxbc_columns_head');  
add_action('manage_sxbc_posts_custom_column', 'sxbc_columns_content', 10, 2);

// Extra admin field for image URL
function sxbc_image_url(){
	global $post;
	$custom = get_post_custom($post->ID);
	$sxbc_image_url = isset($custom['sxbc_image_url']) ?  $custom['sxbc_image_url'][0] : '';
	$sxbc_image_url_openblank = isset($custom['sxbc_image_url_openblank']) ?  $custom['sxbc_image_url_openblank'][0] : '0';
	$sxbc_image_link_text = isset($custom['sxbc_image_link_text']) ?  $custom['sxbc_image_link_text'][0] : '';
	?>
            
	<label for="sxbc_image_url"><?php _e('Image URL', 'sx-bootstrap-carousel'); ?>:</label>
	<input type="text" name="sxbc_image_url" value="<?php echo $sxbc_image_url; ?>" /> <br />
	<small><?php _e('(optional - leave blank for no link)', 'sx-bootstrap-carousel'); ?></small><br /><br />
	
	<label for="sxbc_image_url_openblank"><input type="checkbox" name="sxbc_image_url_openblank" <?php if($sxbc_image_url_openblank == 1){ echo ' checked="checked"'; } ?> value="1" /> <?php _e('Open link in new window?', 'sx-bootstrap-carousel'); ?></label><br /><br />
	
	<label for="sxbc_image_link_text"><?php _e('Button Text', 'sx-bootstrap-carousel'); ?>:</label>
	<input type="text" name="sxbc_image_link_text" value="<?php echo $sxbc_image_link_text; ?>" /> <br />
	<small><?php _e('(optional - leave blank for default, only shown if using link buttons)', 'sx-bootstrap-carousel'); ?></small>
	<?php
}
function sxbc_admin_init_custpost(){
	add_meta_box("sxbc_image_url", "Image Link URL", "sxbc_image_url", "sxbc", "side", "low");
}
add_action("add_meta_boxes", "sxbc_admin_init_custpost");
function sxbc_mb_save_details(){
	global $post;
	if (isset($_POST["sxbc_image_url"])) {
		$openblank = 0;
		if(isset($_POST["sxbc_image_url_openblank"]) && $_POST["sxbc_image_url_openblank"] == '1'){
			$openblank = 1;
		}
		update_post_meta($post->ID, "sxbc_image_url", esc_url($_POST["sxbc_image_url"]));
		update_post_meta($post->ID, "sxbc_image_url_openblank", $openblank);
		update_post_meta($post->ID, "sxbc_image_link_text", sanitize_text_field($_POST["sxbc_image_link_text"]));
	}
}
add_action('save_post', 'sxbc_mb_save_details');


///////////////////
// CONTEXTUAL HELP
///////////////////
function sxbc_contextual_help_tab() {
    $screen = get_current_screen();
    if( $screen->post_type === 'sxbc'){
        $help = '<p>You can add a <strong>SX Bootstrap Carousel</strong> image carousel using the shortcode <code>[sx-carousel]</code>.</p>
                <p>You can read the full plugin documentation on the <a href="http://wordpress.org/plugins/sx-bootstrap-carousel/" target="_blank">WordPress plugins page</a></p>
                <p>Most settings can be changed in the <a href="">settings page</a> but you can also specify options for individual carousels
                using the following settings:</p>
		
                <ul>
                <li><code>interval</code> <em>(default 5000)</em>
                <ul>
                <li>Length of time for the caption to pause on each image. Time in milliseconds.</li>
                </ul></li>
			
                <li><code>showcaption</code> <em>(default true)</em>
                <ul>
                <li>Whether to display the text caption on each image or not. true or false.</li>
                </ul></li>
			
                <li><code>showcontrols</code> <em>(default true)</em>
                <ul>
                <li>Whether to display the control arrows or not. true or false.</li>
                </ul></li>
			
                <li><code>orderby</code> and <code>order</code> <em>(default menu_order ASC)</em>
                <ul>
                <li>What order to display the posts in. Uses WP_Query terms.</li>
                </ul></li>
			
                <li><code>category</code> <em>(default all)</em>
                <ul>
                <li>Filter carousel items by a comma separated list of carousel category slugs.</li>
                </ul></li>
			
                <li><code>image_size</code> <em>(default full)</em>
                <ul>
                <li>WordPress image size to use, useful for small carousels</li>
                </ul></li>
			
                <li><code>id</code> <em>(default all)</em>
                <ul>
                <li>Specify the ID of a specific carousel post to display only one image.</li>';
        if(isset($_GET['post'])){
            $help .= '<li>The ID of the post you\'re currently editing is <strong>'.$_GET['post'].'</strong></li>';
        }
        $help .= '
            </ul></li>
			
        <li><code>twbs</code> <em>(default 2)</em>
        <ul>
        <li>Output markup for Twitter Bootstrap Version 2 or 3.</li>
        </ul></li>
        </ul>
        ';
        $screen->add_help_tab( array(
            'id' => 'sxbc_contextual_help',
            'title' => __('Carousel'),
            'content' => __($help)
                ) );
        }
    } // if( $screen->post_type === 'sxbc'){
add_action('load-post.php', 'sxbc_contextual_help_tab');
add_action('load-post-new.php', 'sxbc_contextual_help_tab');

