<?php
/*
Plugin Name: WooThumbs - Multiple Images per Variation
Plugin URI: http://www.jckemp.com
Description: Display multiple images for each variation of a product.
Version: 4.0.0
Author: James Kemp
Author Email: support@jckemp.com  
*/

class JckWooThumbs {

/* 	=============================
   	// !Constants 
   	============================= */	
   	
   	public $name = 'WooThumbs - Multiple Images per Variation';
   	public $shortname = 'WooThumbs';
	public $slug = 'jckWooThumbs';
	public $settings_framework;
	public $plugin_path;
    public $plugin_url;
    public $version = "4.0.0";
	
/* 	=============================
   	// !Constructor 
   	============================= */
   	
	function __construct() {
		
		$this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        
		// register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_woocommerce_variation_transitions' ) );

		// Hook up to the init action
		add_action( 'init', array( &$this, 'init_woocommerce_variation_transitions' ) );
	}
  
/* 	=============================
   	// !Runs when the plugin is activated 
   	============================= */  
   	
	function install_woocommerce_variation_transitions() {
		// do not generate any output here
	}
  
/* 	=============================
   	// !Runs when the plugin is initialized 
   	============================= */
   	
	function init_woocommerce_variation_transitions() {
		// Setup localization
		load_plugin_textdomain( $this->slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		
		// Load JavaScript and stylesheets
		// $this->register_scripts_and_styles();
		$this->remove_hooks();
		
		if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/redux/ReduxCore/framework.php' ) ) {
			require_once( dirname( __FILE__ ) . '/redux/ReduxCore/framework.php' );
		}
		if ( !isset( $jckWooThumbs ) && file_exists( dirname( __FILE__ ) . '/options/woothumbs-options.php' ) ) {
			require_once( dirname( __FILE__ ) . '/options/woothumbs-options.php' );
		}

	/* 	=============================
	   	// !Actions and Filters 
	   	============================= */
	   	
	   	// !Admin Actions
	   	add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ));  
	   	add_action( 'wp_ajax_admin_load_thumbnails', array( &$this, 'admin_load_thumbnails' ));
	   	add_action( 'woocommerce_process_product_meta', array( &$this, 'save_images' ));
	   
	    // !Frontend Actions
	    add_action('woocommerce_before_single_product_summary', array( &$this, 'show_product_images' ), 20); 
	    add_action( 'wp_ajax_nopriv_load_images', array( &$this, 'ajax_load_images' ));		
		add_action( 'wp_ajax_load_images', array( &$this, 'ajax_load_images' ));
		add_action('wp_ajax_'.$this->slug.'-css', array( &$this, 'dynamic_css' ));
		add_action('wp_ajax_nopriv_'.$this->slug.'-css', array( &$this, 'dynamic_css' ));
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_scripts_and_styles' ));
	}

/* 	=============================
   	// !Action and Filter Functions
   	============================= */
   	
   	// Edit Screen Functions
   	
   	public function admin_scripts() {
		global $post, $pagenow;
		if($post) {
			if(get_post_type( $post->ID ) == "product" && ($pagenow == "post.php" || $pagenow == "post-new.php")) {	
				wp_enqueue_script($this->slug, plugins_url('js/admin-scripts.js', __FILE__), array('jquery'), '2.0.1', true);
				wp_enqueue_style( 'jck_wt_admin_css', plugins_url('css/admin-styles.css', __FILE__), false, '2.0.1' );
				
				$vars = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( $this->slug.'_ajax' )
				);
				wp_localize_script( $this->slug, 'vars', $vars );
			}
		}
	}
	
	function save_images($post_id){
	
		if(isset($_POST['variation_image_gallery'])) {
			foreach($_POST['variation_image_gallery'] as $varID => $variation_image_gallery) {
				update_post_meta($varID, 'variation_image_gallery', $variation_image_gallery);	
			}
		}
		
	}
	
	function admin_load_thumbnails() {
		
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], $this->slug.'_ajax' ) ) { die ( 'Invalid Nonce' ); }
			
			$attachments = get_post_meta($_GET['varID'], 'variation_image_gallery', true);
			$attachmentsExp = array_filter(explode(',', $attachments));
			$imgIDs = array(); ?>
			
			<ul class="wooThumbs">
			
				<?php if(!empty($attachmentsExp)) { ?>
				
					<?php foreach($attachmentsExp as $id) { $imgIDs[] = $id; ?>
						<li class="image" data-attachment_id="<?php echo $id; ?>">
							<a href="#" class="delete" title="Delete image"><?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?></a>
						</li>
					<?php } ?>
				
				<?php } ?>
			
			</ul>
			<input type="hidden" class="variation_image_gallery" name="variation_image_gallery[<?php echo $_GET['varID']; ?>]" value="<?php echo $attachments; ?>">
		
		<?php exit;
	}
	
	// !Remove the default images from WooCommerce Product Pages
	
	public function remove_hooks() {
		remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
		// Remove images from Bazar theme
		if( class_exists( 'YITH_WCMG' ) ) {
			$this->remove_filters_for_anonymous_class( 'woocommerce_before_single_product_summary', 'YITH_WCMG_Frontend', 'show_product_images', 20 );
			$this->remove_filters_for_anonymous_class( 'woocommerce_product_thumbnails', 'YITH_WCMG_Frontend', 'show_product_thumbnails', 20 );
		}
	}
	
	// !Display Images on Frontend
	
	public function show_product_images(){
		require_once('frontend/images.php');
	}
	
	public function ajax_load_images(){
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], $this->slug.'_ajax' ) ) { die ( 'Invalid Nonce' ); }
		
		header('Content-type: application/json');
		
		$data = array();
		$data['response'] = 'success';
		
		$imgIds = $this->get_all_image_ids($_REQUEST['variation']);
		$images = $this->get_all_image_sizes($imgIds);
		
		require_once('frontend/loop-images.php');
		
		$data['content'] = $return;
		
		echo json_encode($data);
		
		exit;
	}
  
/* 	=============================
   	// !Frontend Scripts and Styles 
   	============================= */
   	
	public function register_scripts_and_styles() {
		global $jckWooThumbs;
		
		//$this->load_file( 'royalslider', '/js/royalslider/jquery.royalslider.js', true );
		//$this->load_file( 'imageZoom-js', '/js/imageZoom/jquery.imagezoom.min.js', true );
		$this->load_file( $this->slug . '-tools', '/js/woothumbs.tools.min.js', true );
		$this->load_file( $this->slug . '-script', '/js/scripts.js', true );
		
		//$this->load_file( 'royalslider', '/js/royalslider/royalslider.css' );
		//$this->load_file( 'royalslider-default-skin', '/js/royalslider/skins/minimal-white/rs-minimal-white.css' );
		//$this->load_file( 'imageZoom-css', '/js/imageZoom/imagezoom.css' );
		
		$this->load_file( $this->slug . '-tools-css', '/css/woothumbs.tools.min.css' );
		
		wp_enqueue_style($this->slug . 'dynamic-css', admin_url('admin-ajax.php').'?action='.$this->slug.'-css');
		
		// Localise Script
		
		$vars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( $this->slug.'_ajax' ),
			'loading_icon' => plugins_url('img/loading.gif', __FILE__),
			'slug' => $this->slug,
			'options' => $jckWooThumbs
		);
		wp_localize_script( $this->slug . '-script', 'jck_wt_vars', $vars );
	} // end register_scripts_and_styles
	
	// !Dynamic CSS
	public function dynamic_css(){
		global $jckWooThumbs;
		
		include('css/styles.php');
		
		exit;
	}
	
/* 	=============================
   	// !Helpers 
   	============================= */
	
	/* 	=============================
	   	Helper function for registering and enqueueing scripts and styles.
	   	@name: 			The ID to register with WordPress
	   	@file_path: 	The path to the actual file
	   	@is_script:		Optional argument for if the incoming file_path is a JavaScript source file.
	   	============================= */
		
		private function load_file( $name, $file_path, $is_script = false ) {
	
			$url = plugins_url($file_path, __FILE__);
			$file = plugin_dir_path(__FILE__) . $file_path;
	
			if( file_exists( $file ) ) {
				if( $is_script ) {
					wp_register_script( $name, $url, array('jquery'), false, true ); //depends on jquery
					wp_enqueue_script( $name );
				} else {
					wp_register_style( $name, $url );
					wp_enqueue_style( $name );
				} // end if
			} // end if
	
		} // end load_file
	
	/* 	=============================
	   	Allow to remove method for a hook when it's a class method used
	   	and the class doesn't have a variable assigned, but the class name is known
	   	@hook_name: 	Name of the wordpress hook
	   	@class_name: 	Name of the class where the add_action resides
	   	@method_name:	Name of the method to unhook
	   	@priority:		The priority of which the above method has in the add_action
	   	============================= */
	   	
		public function remove_filters_for_anonymous_class( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {
		        global $wp_filter;
		        
		        // Take only filters on right hook name and priority
		        if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) )
		                return false;
		        
		        // Loop on filters registered
		        foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
		                // Test if filter is an array ! (always for class/method)
		                if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
		                        // Test if object is a class, class and method is equal to param !
		                        if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && get_class($filter_array['function'][0]) == $class_name && $filter_array['function'][1] == $method_name ) {
		                                unset($wp_filter[$hook_name][$priority][$unique_id]);
		                        }
		                }
		                
		        }
		        
		        return false;
		}
		
	/* 	=============================
	   	Grabs the default variation ID, depending on the 
	   	settings for that particular product
	   	============================= */
	   	
	   	public function get_default_variation_id(){
	   		global $post, $woocommerce, $product;
	   		
	   		$defaultVarId = $product->id;
	   		
	   		if($product->product_type == 'variable'){
	   		
		   		$defaults = $product->get_variation_default_attributes();
				$variations = array_reverse($product->get_available_variations());
				
				if(!empty($defaults)){
					foreach($variations as $variation){
						
						$varCount = count($variation["attributes"]);
						
						$attMatch = 0; $partMatch = 0; foreach($defaults as $dAttName => $dAttVal){
							// $defaultVarId = false;
							if(isset($variation["attributes"]['attribute_'.$dAttName])) {
								$theAtt = $variation["attributes"]['attribute_'.$dAttName];
								if($theAtt == $dAttVal) {
									$attMatch++;
									$partMatch++;
								}
								if($theAtt == ""){
									$partMatch++;
								}
							}
						}
						
						if($varCount == $partMatch) {
							$defaultVarId = $variation['variation_id'];
						}
						
						if($varCount == $attMatch) {
							$defaultVarId = $variation['variation_id'];
						}
					}
				}
			
			}
			
			return $defaultVarId;
	   	}
	   	
	/* 	=============================
	   	Get all attached Image IDs
	   	@id = the product or variation ID
	   	============================= */
	   	
	   	public function get_all_image_ids($id){

		   	$allImages = array();	   	
		   	
		   	// Main Image
			if(has_post_thumbnail($id)){
				$allImages[] = get_post_thumbnail_id($id);
			}
			
			// WooThumb Attachments
			if(get_post_type($id) == 'product_variation'){
			   	$wtAttachments = array_filter(explode(',', get_post_meta($id, 'variation_image_gallery', true)));
			   	$allImages = array_merge($allImages, $wtAttachments);
		   	}
			
			// Gallery Attachments	
			if(get_post_type($id) == 'product'){
				$product = get_product($id);
				$attachIds = $product->get_gallery_attachment_ids();
				
				if(!empty($attachIds)){
					$allImages = array_merge($allImages, $attachIds);
				}
			}	
			
			return $allImages;
	   	}
	
	/* 	=============================
	   	Get required image sizes based 
	   	on array of image IDs
	   	============================= */
	   	
	   	public function get_all_image_sizes($imgIds){
	   		$images = array();
		   	if(!empty($imgIds)){
			   	foreach($imgIds as $imgId):
			   		if(!array_key_exists($imgId, $images)){
			   			$images[$imgId] = array(
			   				'large' => wp_get_attachment_image_src($imgId, 'large'),
			   				'single' => wp_get_attachment_image_src($imgId, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' )),
			   				'thumb' => wp_get_attachment_image_src($imgId, 'thumbnail')
			   			);
			   		}
			   	endforeach;
		   	}
		   	return $images;
	   	}
  
} // end class

$jckWooThumbsClass = new JckWooThumbs();
?>
<?php include('img/social.png'); ?>