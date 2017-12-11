<?php
/**
 * SMOF Interface
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.4.0
 * @author      Syamil MJ
 */
/**
 * Admin Init
 *
 * @uses wp_verify_nonce()
 * @uses header()
 *
 * @since 1.0.0
 */
function optionsframework_admin_init() 
{
    
	// Rev up the Options Machine
	global $of_options, $options_machine;

	$options_machine = new Options_Machine($of_options);
        
	$smof_data = of_get_options();
	$data = $smof_data;
	do_action('optionsframework_admin_init_before', array(
			'of_options'		=> $of_options,
			'options_machine'	=> $options_machine,
			'smof_data'			=> $smof_data
		));
	if (empty($smof_data['smof_init'])) { // Let's set the values if the theme's already been active
		of_save_options($options_machine->Defaults);
		set_theme_mod('smof_init', date('r'));
		set_theme_mod('pbtheme_version', '2.0');
		$smof_data = of_get_options();
		$options_machine = new Options_Machine($of_options);
	}
	do_action('optionsframework_admin_init_after', array(
			'of_options'		=> $of_options,
			'options_machine'	=> $options_machine,
			'smof_data'			=> $smof_data
		));
}
/**
 * Create Options page
 *
 * @uses add_theme_page()
 * @uses add_action()
 *
 * @since 1.0.0
 */
function optionsframework_add_admin() {
    global $imscpbtheme_lc;
    if($imscpbtheme_lc && !$imscpbtheme_lc->CheckLicense()) return;
	$of_page = add_theme_page( THEMENAME, 'PBTheme Options', 'edit_theme_options', 'pbtheme_options', 'optionsframework_options_page' );
	// Add framework functionaily to the head individually
	add_action("admin_print_scripts-$of_page", 'of_load_only');
	add_action("admin_print_styles-$of_page",'of_style_only');
}
/**
 * Build Options page
 *
 * @since 1.0.0
 */
function optionsframework_options_page(){
	global $options_machine;
	/*
	//for debugging
	$smof_data = of_get_options();
	print_r($smof_data);
	*/
	include_once( ADMIN_PATH . 'front-end/options.php' );
}
/**
 * Create Options page
 *
 * @uses wp_enqueue_style()
 *
 * @since 1.0.0
 */
function of_style_only(){
	wp_enqueue_style('admin-style', ADMIN_DIR . 'assets/css/admin-style.css');
	wp_enqueue_style('admin-fonts', ADMIN_DIR . 'assets/fonts/imscadmin.css');
	//wp_enqueue_style('color-picker', ADMIN_DIR . 'assets/css/colorpicker.css');
	wp_enqueue_style('jquery-ui-custom-admin', ADMIN_DIR .'assets/css/jquery-ui-custom.css');
	if ( !wp_style_is( 'wp-color-picker','registered' ) ) {
		wp_register_style( 'wp-color-picker', ADMIN_DIR . 'assets/css/color-picker.min.css' );
	}
	wp_enqueue_style( 'wp-color-picker' );
}	
/**
 * Create Options page
 *
 * @uses add_action()
 * @uses wp_enqueue_script()
 *
 * @since 1.0.0
 */
function of_load_only() 
{
	//add_action('admin_head', 'smof_admin_head');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-input-mask', ADMIN_DIR .'assets/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('tipsy', ADMIN_DIR .'assets/js/jquery.tipsy.js', array( 'jquery' ));
	//wp_enqueue_script('color-picker', ADMIN_DIR .'assets/js/colorpicker.js', array('jquery'));
	wp_enqueue_script('cookie', ADMIN_DIR . 'assets/js/cookie.js', 'jquery');
	wp_enqueue_script('smof', ADMIN_DIR .'assets/js/smof.js', array( 'jquery' ));
    wp_localize_script( 'smof', 'pbtheme_smof_data', array(
        'admin_images' => ADMIN_DIR .'assets/images',
        'header_design_confirm_set' => 
        esc_html__( 'This will overwrite next sections in your theme options: Color Settings, Header Settings, Header style settings, Header search styles, Header logo styles, Header responsive menu settings, Header Button Settings, Contact Settings. Are you sure?', 'pbtheme' )
    ));
	// Enqueue colorpicker scripts for versions below 3.5 for compatibility
	if ( !wp_script_is( 'wp-color-picker', 'registered' ) ) {
		wp_register_script( 'iris', ADMIN_DIR .'assets/js/iris.min.js', array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_register_script( 'wp-color-picker', ADMIN_DIR .'assets/js/color-picker.min.js', array( 'jquery', 'iris' ) );
	}
	wp_enqueue_script( 'wp-color-picker' );
	/**
	 * Enqueue scripts for file uploader
	 */
	if ( function_exists( 'wp_enqueue_media' ) )
		wp_enqueue_media();
}

/**
 * Set options to save for profit builder
 * @since 3.2.4
 * @return array
 */
function pbtheme_pbuilder_options_array( $smof_data ) {
    return array (
        'bottom_margin' => $smof_data['fb_bmargin'],
        'high_rezolution_width' => $smof_data['content_width'],
        'high_rezolution_margin' => $smof_data['fb_hres_c'],
        'med_rezolution_width' => $smof_data['fb_mres_w'],
        'med_rezolution_margin' => $smof_data['fb_mres_c'],
        'med_rezolution_hide_sidebar' => ( $smof_data['fb_mres_s'] == 1 ) ? 'true' : 'false',
        'low_rezolution_width' => $smof_data['fb_lres_w'],
        'low_rezolution_margin' => $smof_data['fb_lres_c'],
        'low_rezolution_hide_sidebar' => ( $smof_data['fb_lres_s'] == 1 ) ? 'true' : 'false',
        'main_color' => $smof_data['theme_color'],
        'light_main_color' => $smof_data['theme_color'],
        'text_color' => $smof_data['theme_color_textt'],
        'link_color' => $smof_data['theme_link_color_textt'],
        'link_hover_color' => $smof_data['theme_link_hover_color_textt'],
        'title_color' => $smof_data['theme_color_dark'],
        'dark_back_color' => $smof_data['theme_color_dark'],
        'light_back_color' => $smof_data['theme_color_palee'],
        'dark_border_color' => $smof_data['theme_color_dark'],
        'light_border_color' => $smof_data['theme_color_palee']
    );
}

/**
 * Ajax Save Options
 *
 * @uses get_option()
 *
 * @since 1.0.0
 */
function of_ajax_callback() 
{
	global $options_machine, $of_options;
	$nonce=$_POST['security'];
	if (! wp_verify_nonce($nonce, 'of_ajax_nonce') ) die('-1'); 
	//get options array from db
	$all = of_get_options();
	$save_type = $_POST['type'];
    
	//echo $_POST['data'];
	//Uploads
	if($save_type == 'upload')
	{
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
		$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
			$upload_tracking[] = $clickedID;
			//update $options array w/ image URL			  
			$upload_image = $all; //preserve current data
			$upload_image[$clickedID] = $uploaded_file['url'];
			of_save_options($upload_image);
		 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }	
		 else { echo $uploaded_file['url']; } // Is the Response
	}
	elseif($save_type == 'image_reset')
	{
			$id = $_POST['data']; // Acts as the name
			$delete_image = $all; //preserve rest of data
			$delete_image[$id] = ''; //update array key with empty value	 
			of_save_options($delete_image ) ;
	}
	elseif($save_type == 'backup_options')
	{
		$backup = $all;
		$backup['backup_log'] = date('r');
		of_save_options($backup, BACKUPS) ;
		die('1'); 
	}
	elseif($save_type == 'restore_options')
	{
		$smof_data = get_option(BACKUPS);
		update_option(OPTIONS, $smof_data);
		of_save_options($smof_data);
		die('1'); 
	}
	elseif($save_type == 'import_options'){
		$smof_data = unserialize(base64_decode($smof_data)); //100% safe - ignore theme check nag
		of_save_options($smof_data);
		die('1'); 
	}
	elseif ($save_type == 'save')
	{
		wp_parse_str(stripslashes($_POST['data']), $smof_data);
		unset($smof_data['security']);
		unset($smof_data['of_save']);
		if ( DIVWP_FBUILDER === true ) {
            global $pbuilder;
			$options = pbtheme_pbuilder_options_array( $smof_data );
			$pbuilder->set_options($options);
		}
		of_save_options($smof_data);
		die('1');
	}
	elseif ($save_type == 'reset')
	{
		of_save_options($options_machine->Defaults);
		die('1'); //options reset
	}
	elseif ($save_type == 'demo_remove')
	{
		set_transient('PBTheme_Demo_Remove', 'true');
		die('1'); //options reset
	}
	elseif ($save_type == 'demo_install')
	{
		include_once(dirname(__FILE__) . '/../demo/demo-pbuilder-options.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-pbuilder-pages.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-posts.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-terms.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-revslider.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-woo.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-ctimeline.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-allaround.inc');
		include_once(dirname(__FILE__) . '/../demo/demo-addtemplates.inc');
		set_transient('PBTheme_Demo_Installation', 'installed');
		die('1'); //demo installed
	}
    /**
     * Force product setting and save options
     * 
     * @since 3.1.7
     */
    elseif ($save_type == 'force_product_setting')
	{
        $result = 1;
        
        
        
        
         
		/**
         * First save all options as in save type
         */
        wp_parse_str(stripslashes($_POST['data']), $smof_data);
          unset($smof_data['security']);
          unset($smof_data['of_save']);
          if ( DIVWP_FBUILDER === true ) {
                  global $pbuilder;
                  $options = pbtheme_pbuilder_options_array( $smof_data );     
            $pbuilder->set_options($options);
          }
          of_save_options($smof_data);
        
        if($_POST['field'] == 'enable_ogmeta'){
          echo 'Set OG to '.$smof_data['enable_ogmeta'];
        }
        
        /**
         * Force clicked setting to product metabox
         */
        if ( isset( $_POST['field'] ) && !empty( $_POST['field'] ) ) {
            // Set value to every product meta
            $force_product_meta = new Pb_Theme_Force_Product_Metadata( $_POST['field'], $smof_data[$_POST['field']] );
            $result = $force_product_meta->update_value_for_products();
        }
        
		die($result);
	}
    /**
     * Force save options and predefined header design
     * 
     * @since 3.2.4
     */
    elseif ($save_type == 'set_predefined_header_design')
	{
        
        if ( ! defined( 'DOING_AJAX' ) ) {
            die(0);
        }
        
		/**
         * First save all options as in save type
         */
        wp_parse_str(stripslashes($_POST['data']), $smof_data);
		unset($smof_data['security']);
		unset($smof_data['of_save']);
		if ( DIVWP_FBUILDER === true ) {
		global $pbuilder;
			$options = pbtheme_pbuilder_options_array( $smof_data );
			$pbuilder->set_options($options);
		}
        
        /**
         * Set selected predefined design
         */
        if ( isset( $_POST['field'] ) && $_POST['field'] === 'header_styles_predefined' ) {
            $field = esc_attr( $_POST['field'] );
            if ( class_exists( 'header_predefined_design' ) ) {
                $header_design = new header_predefined_design( $smof_data[$field], $smof_data );
                $smof_data = $header_design->set_selected_design();
            }
        }
        
        // Save options
        of_save_options($smof_data);
        
		die('1');
	}
	die();
}