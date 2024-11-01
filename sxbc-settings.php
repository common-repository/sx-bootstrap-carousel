<?php
/*****************************************************
* SX Bootstrap Carousel
* http://www.redweb.tn/bootstrap-carousel/
* ----------------------------------------------------
* sxbc-settings.php
* Code to handle the Settings page
******************************************************/

///////////////////
// SETTINGS PAGE
///////////////////

// Set up settings defaults
register_activation_hook(__FILE__, 'sxbc_set_options');
function sxbc_set_options (){
	$defaults = array(
		'interval' => '5000',
		'showcaption' => 'true',
		'showcontrols' => 'true',
		'customprev' => '',
		'customnext' => '',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'category' => '',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
		'before_caption' => '<p>',
		'after_caption' => '</p>',
		'image_size' => 'full',
		'link_button' => '1',
		'link_button_text' => 'Read more',
		'link_button_class' => 'btn btn-default pull-right',
		'link_button_before' => '',
		'link_button_after' => '',
		'id' => '',
		'twbs' => '3',
		'use_background_images' => '0',
		'background_images_height' => '500',
        'background_images_style_size' => 'cover',
        'use_javascript_animation' => '1',
	);
	add_option('sxbc_settings', $defaults);
}
// Clean up on uninstall
register_activation_hook(__FILE__, 'sxbc_deactivate');
function sxbc_deactivate(){
	delete_option('sxbc_settings');
}


// Render the settings page
class sxbc_settings_page {
	// Holds the values to be used in the fields callbacks
	private $options;
			
	// Start up
	public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
	}
			
	// Add settings page
	public function add_plugin_page() {
		add_submenu_page('edit.php?post_type=sxbc', __('Settings', 'sx-bootstrap-carousel'), __('Settings', 'sx-bootstrap-carousel'), 'manage_options', 'sx-bootstrap-carousel', array($this,'create_admin_page'));
	}
			
	// Options page callback
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'sxbc_settings' );
		if(!$this->options){
			sxbc_set_options ();
			$this->options = get_option( 'sxbc_settings' );
		}
		?>
		<div class="wrap">
		<h2>SX Bootstrap Carousel <?php _e('Settings', 'sx-bootstrap-carousel'); ?></h2>
		<p><?php printf(__('You can set the default behaviour of your carousels here. Most of these settings can be overridden by using %s shortcode attributes %s.', 'sx-bootstrap-carousel'),'<a href="http://wordpress.org/plugins/sx-bootstrap-carousel/" target="_blank">', '</a>'); ?></p>
					 
				<form method="post" action="options.php">
				<?php
						settings_fields( 'sxbc_settings' );   
						do_settings_sections( 'sx-bootstrap-carousel' );
						submit_button(); 
				?>
				</form>
		</div>
		<?php
	}
			
	// Register and add settings
	public function page_init() {		
		register_setting(
				'sxbc_settings', // Option group
				'sxbc_settings', // Option name
				array( $this, 'sanitize' ) // Sanitize
		);
		
        // Sections
		add_settings_section(
				'sxbc_settings_behaviour', // ID
				__('Carousel Behaviour', 'sx-bootstrap-carousel'), // Title
				array( $this, 'sxbc_settings_behaviour_header' ), // Callback
				'sx-bootstrap-carousel' // Page
		);
		add_settings_section(
				'sxbc_settings_setup', // ID
				__('Carousel Setup', 'sx-bootstrap-carousel'), // Title
				array( $this, 'sxbc_settings_setup' ), // Callback
				'sx-bootstrap-carousel' // Page
		);
		add_settings_section(
				'sxbc_settings_link_buttons', // ID
				__('Link Buttons', 'sx-bootstrap-carousel'), // Title
				array( $this, 'sxbc_settings_link_buttons_header' ), // Callback
				'sx-bootstrap-carousel' // Page
		);
		add_settings_section(
				'sxbc_settings_markup', // ID
				__('Custom Markup', 'sx-bootstrap-carousel'), // Title
				array( $this, 'sxbc_settings_markup_header' ), // Callback
				'sx-bootstrap-carousel' // Page
		);
        
		// Behaviour Fields
		add_settings_field(
				'interval', // ID
				__('Slide Interval (milliseconds)', 'sx-bootstrap-carousel'), // Title
				array( $this, 'interval_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_behaviour' // Section
		);
		add_settings_field(
				'showcaption', // ID
				__('Show Slide Titles / Captions?', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'showcaption_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_behaviour' // Section		   
		);
		add_settings_field(
				'showcontrols', // ID
				__('Show Slide Controls?', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'showcontrols_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_behaviour' // Section		   
		);
		add_settings_field(
				'orderby', // ID
				__('Order Slides By', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'orderby_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_behaviour' // Section		   
		);
		add_settings_field(
				'order', // ID
				__('Ordering Direction', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'order_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_behaviour' // Section		   
		);
		add_settings_field(
				'category', // ID
				__('Restrict to Category', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'category_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_behaviour' // Section		   
		);
        
        // Carousel Setup Section
		add_settings_field(
				'twbs', // ID
				__('Twitter Bootstrap Version', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'twbs_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_setup' // Section		   
		);
		add_settings_field(
				'image_size', // ID
				__('Image Size', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'image_size_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_setup' // Section		   
		);
		
		add_settings_field(
				'use_background_images', // ID
				__('Use background images?', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'use_background_images_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_setup' // Section		   
		);
		add_settings_field(
				'background_images_height', // ID
				__('Height if using bkgrnd images (px)', 'sx-bootstrap-carousel'), // Title
				array( $this, 'background_images_height_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_setup' // Section
		);
		add_settings_field(
				'background_images_style_size', // ID
				__('Background images size style', 'sx-bootstrap-carousel'), // Title
				array( $this, 'background_images_style_size_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_setup' // Section
		);
		add_settings_field(
				'use_javascript_animation', // ID
				__('Use Javascript to animate carousel?', 'sx-bootstrap-carousel'), // Title 
				array( $this, 'use_javascript_animation_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_setup' // Section		   
		);

		// Link buttons
		add_settings_field(
				'link_button', // ID
				__('Show links as button in caption', 'sx-bootstrap-carousel'), // Title
				array( $this, 'link_button_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_link_buttons' // Section
		);
		add_settings_field(
				'link_button_text', // ID
				__('Default text for link buttons', 'sx-bootstrap-carousel'), // Title
				array( $this, 'link_button_text_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_link_buttons' // Section
		);
		add_settings_field(
				'link_button_class', // ID
				__('Class for link buttons', 'sx-bootstrap-carousel'), // Title
				array( $this, 'link_button_class_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_link_buttons' // Section
		);
		add_settings_field(
				'link_button_before', // ID
				__('HTML before link buttons', 'sx-bootstrap-carousel'), // Title
				array( $this, 'link_button_before_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_link_buttons' // Section
		);
		add_settings_field(
				'link_button_after', // ID
				__('HTML after link buttons', 'sx-bootstrap-carousel'), // Title
				array( $this, 'link_button_after_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_link_buttons' // Section
		);
        
        // Markup Section
		add_settings_field(
				'customprev', // ID
				__('Custom prev button class', 'sx-bootstrap-carousel'), // Title
				array( $this, 'customprev_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_markup' // Section
		);
		add_settings_field(
				'customnext', // ID
				__('Custom next button class', 'sx-bootstrap-carousel'), // Title
				array( $this, 'customnext_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_markup' // Section
		);
		add_settings_field(
				'before_title', // ID
				__('HTML before title', 'sx-bootstrap-carousel'), // Title
				array( $this, 'before_title_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_markup' // Section
		);
		add_settings_field(
				'after_title', // ID
				__('HTML after title', 'sx-bootstrap-carousel'), // Title
				array( $this, 'after_title_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_markup' // Section
		);
		add_settings_field(
				'before_caption', // ID
				__('HTML before caption text', 'sx-bootstrap-carousel'), // Title
				array( $this, 'before_caption_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_markup' // Section
		);
		add_settings_field(
				'after_caption', // ID
				__('HTML after caption text', 'sx-bootstrap-carousel'), // Title
				array( $this, 'after_caption_callback' ), // Callback
				'sx-bootstrap-carousel', // Page
				'sxbc_settings_markup' // Section
		);
			 
	}
			
	// Sanitize each setting field as needed -  @param array $input Contains all settings fields as array keys
	public function sanitize( $input ) {
		$new_input = array();
		foreach($input as $key => $var){
			if($key == 'twbs' || $key == 'interval' || $key == 'background_images_height'){
				$new_input[$key] = absint( $input[$key] );
			} else if ($key == 'link_button_before' || $key == 'link_button_after' || $key == 'before_title' || $key == 'after_title' || $key == 'before_caption' || $key == 'after_caption'){
				$new_input[$key] = $input[$key]; // Don't sanitise these, meant to be html!
			} else { 
				$new_input[$key] = sanitize_text_field( $input[$key] );
			}
		}
		return $new_input;
	}
			
	// Print the Section text
	public function sxbc_settings_behaviour_header() {
            echo '<p>'.__('Basic setup of how each Carousel will function, what controls will show and which images will be displayed.', 'sx-bootstrap-carousel').'</p>';
	}
	public function sxbc_settings_setup() {
            echo '<p>'.__('Change the setup of the carousel - how it functions.', 'sx-bootstrap-carousel').'</p>';
	}
	public function sxbc_settings_link_buttons_header() {
            echo '<p>'.__('Options for using a link button instead of linking the image directly.', 'sx-bootstrap-carousel').'</p>';
	}
	public function sxbc_settings_markup_header() {
            echo '<p>'.__('Customise which CSS classes and HTML tags the Carousel uses.', 'sx-bootstrap-carousel').'</p>';
	}
			
	// Callback functions - print the form inputs
    // Carousel behaviour	
	public function interval_callback() {
			printf('<input type="text" id="interval" name="sxbc_settings[interval]" value="%s" size="15" />',
					isset( $this->options['interval'] ) ? esc_attr( $this->options['interval']) : '');
            echo '<p class="description">'.__('How long each image shows for before it slides. Set to 0 to disable animation.', 'sx-bootstrap-carousel').'</p>';
	}
	public function showcaption_callback() {
		if(isset( $this->options['showcaption'] ) && $this->options['showcaption'] == 'false'){
			$sxbc_showcaption_t = '';
			$sxbc_showcaption_f = ' selected="selected"';
		} else {
			$sxbc_showcaption_t = ' selected="selected"';
			$sxbc_showcaption_f = '';
		}
		print '<select id="showcaption" name="sxbc_settings[showcaption]">
			<option value="true"'.$sxbc_showcaption_t.'>'.__('Show', 'sx-bootstrap-carousel').'</option>
			<option value="false"'.$sxbc_showcaption_f.'>'.__('Hide', 'sx-bootstrap-carousel').'</option>
		</select>';
	}
	public function showcontrols_callback() {
		if(isset( $this->options['showcontrols'] ) && $this->options['showcontrols'] == 'false'){
			$sxbc_showcontrols_t = '';
			$sxbc_showcontrols_f = ' selected="selected"';
			$sxbc_showcontrols_c = '';
		} else if(isset( $this->options['showcontrols'] ) && $this->options['showcontrols'] == 'true'){
			$sxbc_showcontrols_t = ' selected="selected"';
			$sxbc_showcontrols_f = '';
			$sxbc_showcontrols_c = '';
		} else if(isset( $this->options['showcontrols'] ) && $this->options['showcontrols'] == 'custom'){
			$sxbc_showcontrols_t = '';
			$sxbc_showcontrols_f = '';
			$sxbc_showcontrols_c = ' selected="selected"';
		}
		print '<select id="showcontrols" name="sxbc_settings[showcontrols]">
			<option value="true"'.$sxbc_showcontrols_t.'>'.__('Show', 'sx-bootstrap-carousel').'</option>
			<option value="false"'.$sxbc_showcontrols_f.'>'.__('Hide', 'sx-bootstrap-carousel').'</option>
			<option value="custom"'.$sxbc_showcontrols_c.'>'.__('Custom', 'sx-bootstrap-carousel').'</option>
		</select>';
	}
	public function orderby_callback() {
		$orderby_options = array (
			'menu_order' => __('Menu order, as set in Carousel overview page', 'sx-bootstrap-carousel'),
			'date' => __('Date slide was published', 'sx-bootstrap-carousel'),
			'rand' => __('Random ordering', 'sx-bootstrap-carousel'),
			'title' => __('Slide title', 'sx-bootstrap-carousel')	  
		);
		print '<select id="orderby" name="sxbc_settings[orderby]">';
		foreach($orderby_options as $val => $option){
			print '<option value="'.$val.'"';
			if(isset( $this->options['orderby'] ) && $this->options['orderby'] == $val){
				print ' selected="selected"';
			}
			print ">$option</option>";
		}
		print '</select>';
	}
	public function order_callback() {
		if(isset( $this->options['order'] ) && $this->options['order'] == 'DESC'){
			$sxbc_showcontrols_a = '';
			$sxbc_showcontrols_d = ' selected="selected"';
		} else {
			$sxbc_showcontrols_a = ' selected="selected"';
			$sxbc_showcontrols_d = '';
		}
		print '<select id="order" name="sxbc_settings[order]">
			<option value="ASC"'.$sxbc_showcontrols_a.'>'.__('Ascending', 'sx-bootstrap-carousel').'</option>
			<option value="DESC"'.$sxbc_showcontrols_d.'>'.__('Decending', 'sx-bootstrap-carousel').'</option>
		</select>';
	}
	public function category_callback() {
		$cats = get_terms('carousel_category');
		print '<select id="orderby" name="sxbc_settings[category]">
			<option value="">'.__('All Categories', 'sx-bootstrap-carousel').'</option>';
		foreach($cats as $cat){
			print '<option value="'.$cat->name.'"';
			if(isset( $this->options['category'] ) && $this->options['category'] == $cat->name){
				print ' selected="selected"';
			}
			print ">".$cat->name."</option>";
		}
		print '</select>';
	}
	
    // Setup Section
	public function twbs_callback() {
		if(isset( $this->options['twbs'] ) && $this->options['twbs'] == '3'){
			$sxbc_twbs3 = ' selected="selected"';
			$sxbc_twbs2 = '';
		} else {
			$sxbc_twbs3 = '';
			$sxbc_twbs2 = ' selected="selected"';
		}
		print '<select id="twbs" name="sxbc_settings[twbs]">
			<option value="2"'.$sxbc_twbs2.'>2.x</option>
			<option value="3"'.$sxbc_twbs3.'>3.x (Default)</option>
		</select>';
        echo '<p class="description">'.__("Set according to which version of Bootstrap you're using.", 'sx-bootstrap-carousel').'</p>';
	}
	public function image_size_callback() {
		$image_sizes = get_intermediate_image_sizes();
		print '<select id="image_size" name="sxbc_settings[image_size]">
			<option value="full"';
			if(isset( $this->options['image_size'] ) && $this->options['image_size'] == 'full'){
				print ' selected="selected"';
			}
			echo '>Full (default)</option>';
		foreach($image_sizes as $size){
			print '<option value="'.$size.'"';
			if(isset( $this->options['image_size'] ) && $this->options['image_size'] == $size){
				print ' selected="selected"';
			}
			print ">".ucfirst($size)."</option>";
		}
		print '</select>';
        echo '<p class="description">'.__("If your carousels are small, you can a smaller image size to increase page load times.", 'sx-bootstrap-carousel').'</p>';
	}
	public function use_background_images_callback() {
		print '<select id="use_background_images" name="sxbc_settings[use_background_images]">';
		print '<option value="0"';
		if(isset( $this->options['use_background_images'] ) && $this->options['use_background_images'] == 0){
			print ' selected="selected"';
		}
		echo '>No (default)</option>';
		print '<option value="1"';
		if(isset( $this->options['use_background_images'] ) && $this->options['use_background_images'] == 1){
			print ' selected="selected"';
		}
		echo '>Yes</option>';
		print '</select>';
        echo '<p class="description">'.__("Experimental feature - Use CSS background images so that pictures auto-fill the space available.", 'sx-bootstrap-carousel').'</p>';
	}
	public function background_images_height_callback() {
		printf('<input type="text" id="background_images_height" name="sxbc_settings[background_images_height]" value="%s" size="15" />',
				isset( $this->options['background_images_height'] ) ? esc_attr( $this->options['background_images_height']) : '500px');
        echo '<p class="description">'.__("If using background images above, how tall do you want the carousel to be?", 'sx-bootstrap-carousel').'</p>';
	}

	public function use_javascript_animation_callback() {
		print '<select id="use_javascript_animation" name="sxbc_settings[use_javascript_animation]">';
		print '<option value="1"';
		if(isset( $this->options['use_javascript_animation'] ) && $this->options['use_javascript_animation'] == 1){
			print ' selected="selected"';
		}
		echo '>Yes (default)</option>';
		print '<option value="0"';
		if(isset( $this->options['use_javascript_animation'] ) && $this->options['use_javascript_animation'] == 0){
			print ' selected="selected"';
		}
		echo '>No</option>';
		print '</select>';
        echo '<p class="description">'.__("The Bootstrap Carousel is designed to work usign data-attributes. Sometimes the animation doesn't work correctly with this, so the default is to include a small portion of Javascript to fire the carousel. You can choose not to include this here.", 'sx-bootstrap-carousel').'</p>';
	}
	public function background_images_style_size_callback() {
		print '<select id="select_background_images_style_size" name="sxbc_settings[select_background_images_style_size]">';
		print '<option value="cover"';
		if(isset( $this->options['select_background_images_style_size'] ) && $this->options['select_background_images_style_size'] === 'cover'){
			print ' selected="selected"';
		}
		echo '>Cover (default)</option>';
		print '<option value="contain"';
		if(isset( $this->options['select_background_images_style_size'] ) && $this->options['select_background_images_style_size'] === 'contain'){
			print ' selected="selected"';
		}
		echo '>Contain</option>';
		print '<option value="auto"';
		if(isset( $this->options['select_background_images_style_size'] ) && $this->options['select_background_images_style_size'] === 'auto'){
			print ' selected="selected"';
		}
		echo '>Auto</option>';
		print '</select>';
        echo '<p class="description">'.__('If you find that your images are not scaling correctly when using background images try switching the style to \'contain\' or \'auto\'', 'sx-bootstrap-carousel').'</p>';
	}

	// Link buttons section
	public function link_button_callback(){
		print '<select id="link_button" name="sxbc_settings[link_button]">';
		print '<option value="1"';
		if(isset( $this->options['link_button'] ) && $this->options['link_button'] == 1){
			print ' selected="selected"';
		}
		echo '>Yes</option>';
		print '<option value="0"';
		if(!isset( $this->options['link_button'] ) || $this->options['link_button'] == 0){
			print ' selected="selected"';
		}
		echo '>No (Default)</option>';
		print '</select>';
		echo '<p class="description">'.__("If a URL is set for a carousel image, this option will create a button in the caption instead of linking the image itself.", 'sx-bootstrap-carousel').'</p>';
	}
	public function link_button_text_callback() {
			printf('<input type="text" id="link_button_text" name="sxbc_settings[link_button_text]" value="%s" size="20" />',
					isset( $this->options['link_button_text'] ) ? esc_attr( $this->options['link_button_text']) : 'Read more');
	}
	public function link_button_class_callback() {
			printf('<input type="text" id="link_button_class" name="sxbc_settings[link_button_class]" value="%s" size="20" />',
					isset( $this->options['link_button_class'] ) ? esc_attr( $this->options['link_button_class']) : 'btn btn-default pull-right');
			echo '<p class="description">'.__("Bootstrap style buttons must have the class <code>btn</code> and then one of the following: <code>btn-default</code>, <code>btn-primary</code>, <code>btn-success</code>, <code>btn-warning</code>, <code>btn-danger</code> or <code>btn-info</code>. No <code>.</code> prefixes. <code>pull-right</code> to float the button on the right. See the ", 'sx-bootstrap-carousel').' <a href="http://getbootstrap.com/css/#buttons-options" target="_blank">Bootstrap documentation</a>.</p>';
	}
	public function link_button_before_callback() {
			printf('<input type="text" id="link_button_before" name="sxbc_settings[link_button_before]" value="%s" size="20" />',
					isset( $this->options['link_button_before'] ) ? esc_attr( $this->options['link_button_before']) : '');
	}
	public function link_button_after_callback() {
			printf('<input type="text" id="link_button_after" name="sxbc_settings[link_button_after]" value="%s" size="20" />',
					isset( $this->options['link_button_after'] ) ? esc_attr( $this->options['link_button_after']) : '');
	}
    
    // Markup section
	public function before_title_callback() {
			printf('<input type="text" id="before_title" name="sxbc_settings[before_title]" value="%s" size="15" />',
					isset( $this->options['before_title'] ) ? esc_attr( $this->options['before_title']) : '<h4>');
	}
	public function customnext_callback() {
			printf('<input type="text" id="customnext" name="sxbc_settings[customnext]" value="%s" size="15" />',
					isset( $this->options['customnext'] ) ? esc_attr( $this->options['customnext']) : '');
	}
	public function customprev_callback() {
			printf('<input type="text" id="customprev" name="sxbc_settings[customprev]" value="%s" size="15" />',
					isset( $this->options['customprev'] ) ? esc_attr( $this->options['customprev']) : '');
	}
	public function after_title_callback() {
			printf('<input type="text" id="after_title" name="sxbc_settings[after_title]" value="%s" size="15" />',
					isset( $this->options['after_title'] ) ? esc_attr( $this->options['after_title']) : '</h4>');
	}
	public function before_caption_callback() {
			printf('<input type="text" id="before_caption" name="sxbc_settings[before_caption]" value="%s" size="15" />',
					isset( $this->options['before_caption'] ) ? esc_attr( $this->options['before_caption']) : '<p>');
	}
	public function after_caption_callback() {
			printf('<input type="text" id="after_caption" name="sxbc_settings[after_caption]" value="%s" size="15" />',
					isset( $this->options['after_caption'] ) ? esc_attr( $this->options['after_caption']) : '</p>');
	}	
	
}

if( is_admin() ){
		$sxbc_settings_page = new sxbc_settings_page();
}

// Add settings link on plugin page
function sxbc_settings_link ($links) { 
	$settings_link = '<a href="edit.php?post_type=sxbc&page=sx-bootstrap-carousel">'.__('Settings', 'sx-bootstrap-carousel').'</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}
$sxbc_plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$sxbc_plugin", 'sxbc_settings_link' );
