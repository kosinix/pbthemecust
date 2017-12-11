<?php

/**
 * SMOF Admin
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.4.0
 * @author      Syamil MJ
 */

/**
 * Head Hook
 *
 * @since 1.0.0
 */

function of_head() { do_action( 'of_head' ); }

/**
 * Add default options upon activation else DB does not exist
 *
 * @since 1.0.0
 */

function of_option_setup()

{

	global $of_options, $options_machine;

	$options_machine = new Options_Machine($of_options);

	if (!of_get_options())

	{

		of_save_options($options_machine->Defaults);

	}

}

/**
 * Change activation message
 *
 * @since 1.0.0
 */

function optionsframework_admin_message() { 

	if(get_option("pbtheme_skiped", "true") != "true"){

	//Tweaked the message on theme activate

?>

	<script type="text/javascript">

	jQuery(function(){

		var message = '<p>This theme comes with an <a href="<?php echo admin_url('admin.php?page=pbtheme_options'); ?>">options panel</a> to configure settings. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';

		jQuery('.themes-php #message2').html(message);

	});

	</script>

<?php

	}

}

/**
 * Get header classes
 *
 * @since 1.0.0
 */

function of_get_header_classes_array() 

{

	global $of_options;

	foreach ($of_options as $value) 

	{

		if ($value['type'] == 'heading')

			$hooks[] = str_replace(' ','',strtolower($value['name']));

	}

	return $hooks;

}

/**
 * Get options from the database and process them with the load filter hook.
 *
 * @author Jonah Dahlquist
 * @since 1.4.0
 * @return array
 */

function of_get_options($key = null, $data = null) {

	do_action('of_get_options_before', array(

		'key'=>$key, 'data'=>$data

	));

	if ($key != null) { // Get one specific value

		$data = get_theme_mod($key, $data);

	} else { // Get all values

		$data = get_theme_mods();

	}

	if(is_array($data) && !isset($data['tracking-code-footer'])){

		$data['tracking-code-footer']='';	

	}
  
  if(is_array($data) && !isset($data['theme_link_color_textt'])){

		$data['theme_link_color_textt']=$data['theme_color_dark'];	

	}
  
  if(is_array($data) && !isset($data['theme_link_hover_color_textt'])){

		$data['theme_link_hover_color_textt']=$data['theme_color'];	

	}
  
  if(is_array($data) && !isset($data['enable_ogmeta'])){

		$data['enable_ogmeta']=1;	

	}

  if(is_array($data) && !isset($data['woo_product_addinfo'])){

		$data['woo_product_addinfo']=1;	

	}


	$data = apply_filters('of_options_after_load', $data);

	do_action('of_option_setup_before', array(

		'key'=>$key, 'data'=>$data

	));

	return $data;

}

/**
 * Save options to the database after processing them
 *
 * @param $data Options array to save
 * @author Jonah Dahlquist
 * @since 1.4.0
 * @uses update_option()
 * @return void
 */

function of_save_options($data, $key = null) {

	global $smof_data;

	if (empty($data))

		return;

	do_action('of_save_options_before', array(

		'key'=>$key, 'data'=>$data

	));

	$data = apply_filters('of_options_before_save', $data);

	if ($key != null) { // Update one specific value

		if ($key == BACKUPS) {

			unset($data['smof_init']); // Don't want to change this.

		}

		set_theme_mod($key, $data);

	} else { // Update all values in $data

		foreach ( $data as $k=>$v ) {

			if (!isset($smof_data[$k]) || $smof_data[$k] != $v) { // Only write to the DB when we need to

				set_theme_mod($k, $v);

			}

		}

	}

	do_action('of_save_options_after', array(

		'key'=>$key, 'data'=>$data

	));

}



function of_options_after_load_missing($data){

    if($data && is_array($data)){

        if(!isset($data['woo_title_height']))

            $data['woo_title_height'] = 60;

        

        if(!isset($data['woo_meta_data']))

            $data['woo_meta_data'] = 'on';

        

        if(!isset($data['woo_soc_sharing']))

            $data['woo_soc_sharing'] = 'on';

        

        if(!isset($data['woo_product_descp']))

            $data['woo_product_descp'] = 'on';

        
        if(!isset($data['woo_product_addinfo']))

            $data['woo_product_addinfo'] = 'on';



        if(!isset($data['woo_product_reviews']))

            $data['woo_product_reviews'] = 'on';

        

        if(!isset($data['woo_single_related']))

            $data['woo_single_related'] = 'on';
        
        
        if(!isset($data['woo_enable_breadcrumbs']))

            $data['woo_enable_breadcrumbs'] = 'on';

        

        if(!isset($data['woo_addtocart']))

            $data['woo_addtocart'] = __('Add to cart', 'pbtheme');

        

        if(!isset($data['woo_single_add_to_cart_pt']))

            $data['woo_single_add_to_cart_pt'] = '20';

        

        if(!isset($data['woo_single_add_to_cart_pb']))

            $data['woo_single_add_to_cart_pb'] = '20';

        

		if(!isset($data['woo_disable_carticon']))

            $data['woo_disable_carticon'] = 0;

        

		if(!isset($data['woo_force_imageflip']))

            $data['woo_force_imageflip'] = 0;	  

		   

		if(!isset($data['woo_disable_catcount']))

            $data['woo_disable_catcount'] = 0;



		/* Added by Shindiri Studio */

		if(!isset($data['woo_disable_product_category']))

            $data['woo_disable_product_category'] = 0;

        

		if(!isset($data['wc_price_color']))

            $data['wc_price_color'] = '';

        

		if(!isset($data['wc_message_body_color']))

            $data['wc_message_body_color'] = '#000000';

        

		if(!isset($data['wc_message_text_color']))

            $data['wc_message_text_color'] = '#ffffff';

        

		if(!isset($data['wc_message_link_color']))

            $data['wc_message_link_color'] = '#82b440';

        

		if(!isset($data['wc_message_icon_color']))

            $data['wc_message_icon_color'] = '';

		   

        if(!isset($data['woo_enable_fb']))

            $data['woo_enable_fb'] = 0;

            

        if(!isset($data['woo_enable_tw']))

            $data['woo_enable_tw'] = 0;

            

        if(!isset($data['woo_enable_pin']))

            $data['woo_enable_pin'] = 0;

			

		if(!isset($data['woo_enable_gplus']))

            $data['woo_enable_gplus'] = 0;	

            

        if(!isset($data['woo_enable_email']))

            $data['woo_enable_email'] = 0;

            

        if(!isset($data['transposh_enable']))

            $data['transposh_enable'] = 0;

			

		if(!isset($data['disable_sticky_header']))

            $data['disable_sticky_header'] = 1;
        
        
		if(!isset($data['disable-top-header']))

            $data['disable-top-header'] = 0;



	    /* Added by Shindiry Studio */

		if(!isset($data['header_search_custom']))

			$data['header_search_custom'] = 0;



		if(!isset($data['header_mega_search_shortcode']))

			$data['header_mega_search_shortcode'] = '[pw-ajax-live-search id="ID"]';



	    if(!isset($data['header_search_style']))

		    $data['header_search_style'] = 0;



	    if(!isset($data['header_search_type']))

		    $data['header_search_type'] = 0;
        
        
	    if(!isset($data['header_breakpoint']))

		    $data['header_breakpoint'] = '640';

	    /* End code  */
        
        /**
         * Header design styles
         */
        
		if(!isset($data['header_styles_enabled']))

            $data['header_styles_enabled'] = 0;
        
        
		if(!isset($data['header_styles_predefined']))

            $data['header_styles_predefined'] = 'custom';
        
        
        // Header top bar
        if(!isset($data['header_topbar_bgd_color_pattern']))

            $data['header_topbar_bgd_color_pattern'] = 'none';
        
        
        if(!isset($data['header_topbar_bgd_opacity']))

            $data['header_topbar_bgd_opacity'] = '10';
        
        
        if(!isset($data['theme_color_top_header']))

            $data['theme_color_top_header'] = '#d3d3d3';
        
        
        if(!isset($data['header_topbar_bgd_pattern']))

            $data['header_topbar_bgd_pattern'] = get_template_directory_uri() . '/images/' . 'patterns/pattern-header.jpg';
        
        
        if(!isset($data['header_topbar_text_color']))

            $data['header_topbar_text_color'] = '';
        
        
        if(!isset($data['header_topbar_link_color']))

            $data['header_topbar_link_color'] = '';
        
        
        if(!isset($data['header_topbar_link_hover_color']))

            $data['header_topbar_link_hover_color'] = '';
        
        
        if(!isset($data['header_topbar_border']))

            $data['header_topbar_border'] = 1;
        
        
        if(!isset($data['header_topbar_border_color']))

            $data['header_topbar_border_color'] = '';
        
        
        if(!isset($data['header_topbar_font_size']))

            $data['header_topbar_font_size'] = '0';

        
        // Header 
        if(!isset($data['header_bgd_color_pattern']))

            $data['header_bgd_color_pattern'] = 'none';
        
        
        if(!isset($data['theme_color_header']))

            $data['theme_color_header'] = '#d3d3d3';
        
        
        if(!isset($data['header_bgd_opacity']))

            $data['header_bgd_opacity'] = '#d3d3d3';

        
        if(!isset($data['header_bgd_pattern']))

            $data['header_bgd_pattern'] = get_template_directory_uri() . '/images/' . 'patterns/pattern-header.jpg';
        
        
        if(!isset($data['header_link_color']))

            $data['header_link_color'] = '';
        
        
        if(!isset($data['header_link_hover_color']))

            $data['header_link_hover_color'] = '';
        
        
        if(!isset($data['header_border']))

            $data['header_border'] = 1;
        
        
        if(!isset($data['header_border_color']))

            $data['header_border_color'] = '';
        
        
        if(!isset($data['header_shadow']))

            $data['header_shadow'] = 1;
        
        
        if(!isset($data['header_menu_hover_effect']))

            $data['header_menu_hover_effect'] = 'none';
        
        
        if(!isset($data['header_menu_hover_effect_color']))

            $data['header_menu_hover_effect_color'] = '#d3d3d3';
        
        
        if(!isset($data['header_menu_height']))

            $data['header_menu_height'] = '';
        
        
        if(!isset($data['header_menu_font_size']))

            $data['header_menu_font_size'] = '0';
        
        
        if(!isset($data['header_menu_distance_links']))

            $data['header_menu_distance_links'] = '0';
        
        
        if(!isset($data['header_menu_subitem_bgd_color']))

            $data['header_menu_subitem_bgd_color'] = '';
        
        
        if(!isset($data['header_menu_subitem_color']))

            $data['header_menu_subitem_color'] = '';
        
        
        if(!isset($data['header_menu_subitem_hover_color']))

            $data['header_menu_subitem_hover_color'] = '';
        
        
        if(!isset($data['header_search_color']))

            $data['header_search_color'] = '';
        
        
        if(!isset($data['header_search_icon_color']))

            $data['header_search_icon_color'] = '';
        
        
        if(!isset($data['header_search_def_text_color']))

            $data['header_search_def_text_color'] = '';
        
        
        if(!isset($data['header_search_active_text_color']))

            $data['header_search_active_text_color'] = '';
        
        
        if(!isset($data['header_logo_width']))

            $data['header_logo_width'] = '0';
        
        
        if(!isset($data['header_logo_overflow']))

            $data['header_logo_overflow'] = 0;
        
        
        if(!isset($data['header_resp_menu_icon_color']))

            $data['header_resp_menu_icon_color'] = '';
        
        
        if(!isset($data['header_resp_menu_icon_hover_color']))

            $data['header_resp_menu_icon_hover_color'] = '';
        
        
        if(!isset($data['header_resp_menu_line_color']))

            $data['header_resp_menu_line_color'] = '';
        

		/**
         * Sellocity custom button shortcode
         */

        if(!isset($data['sellocity_btn_enabled']))

            $data['sellocity_btn_enabled'] = 0;

        

        if(!isset($data['sellocity_btn_text']))

            $data['sellocity_btn_text'] = esc_html__('Button', 'pbtheme');

        

        if(!isset($data['sellocity_btn_hover_btn_type']))

            $data['sellocity_btn_hover_btn_type'] = 'hover_none';

        

        if(!isset($data['sellocity_btn_text_size']))

            $data['sellocity_btn_text_size'] = 'btn_med';

        

        if(!isset($data['sellocity_btn_css_class']))

            $data['sellocity_btn_css_class'] = 'sellocity-custom-btn-medium';

        

        if(!isset($data['sellocity_btn_font_size_p']))

            $data['sellocity_btn_font_size_p'] = '';

        

        if(!isset($data['sellocity_btn_letter_spacing']))

            $data['sellocity_btn_letter_spacing'] = '0';

        

        if(!isset($data['sellocity_btn_type']))

            $data['sellocity_btn_type'] = 'btn_crcl';

        

        if(!isset($data['sellocity_btn_link_url']))

            $data['sellocity_btn_link_url'] = '#';

      

        if(!isset($data['sellocity_btn_icon_type']))

            $data['sellocity_btn_icon_type'] = '';

        

        if(!isset($data['sellocity_btn_icon_class']))

            $data['sellocity_btn_icon_class'] = 'none';

        

        if(!isset($data['sellocity_btn_back_color']))

            $data['sellocity_btn_back_color'] = '#34495e';

        

        if(!isset($data['sellocity_btn_hover_color']))

            $data['sellocity_btn_hover_color'] = '#182a38';

        

        if(!isset($data['sellocity_btn_text_color']))

            $data['sellocity_btn_text_color'] = '#ffffff';

        

        if(!isset($data['sellocity_btn_text_color_hover']))

            $data['sellocity_btn_text_color_hover'] = '#ffffff';

        

        if(!isset($data['sellocity_btn_border_width']))

            $data['sellocity_btn_border_width'] = '1';

        

        if(!isset($data['sellocity_btn_border_color']))

            $data['sellocity_btn_border_color'] = '';

        

        if(!isset($data['sellocity_btn_border_color_hover']))

            $data['sellocity_btn_border_color_hover'] = '';	
        
        // Breadcrumbs
        if(!isset($data['sellocity_breadcrumbs_enabled']))

            $data['sellocity_breadcrumbs_enabled'] = 0;	

				
        // Single post share
        if(!isset($data['pbtheme_single_share_position']))
            $data['pbtheme_single_share_position'] = 'bottom';
        
        
        if(!isset($data['pbtheme_hide_post_fb_share']))
            $data['pbtheme_hide_post_fb_share'] = 0;
        
        
        if(!isset($data['pbtheme_hide_post_go_share']))
            $data['pbtheme_hide_post_go_share'] = 0;
        
        
        if(!isset($data['pbtheme_hide_post_tw_share']))
            $data['pbtheme_hide_post_tw_share'] = 0;
        
        
        if(!isset($data['pbtheme_hide_post_li_share']))
            $data['pbtheme_hide_post_li_share'] = 0;
        
        
        if(!isset($data['pbtheme_hide_post_pi_share']))
            $data['pbtheme_hide_post_pi_share'] = 0;
        
        /**
         * Widgets style
         */
        if(!isset($data['widgets_style_topbar_warea_bgd']))
		    $data['widgets_style_topbar_warea_bgd'] = '';
        
        if(!isset($data['widgets_style_title_color']))
		    $data['widgets_style_title_color'] = '';
        
        if(!isset($data['widgets_style_title_bgd_color']))
		    $data['widgets_style_title_bgd_color'] = '';
        
        if(!isset($data['widgets_style_title_line']))
		    $data['widgets_style_title_line'] = 'long-line';
        
        if(!isset($data['widgets_style_title_line_size']))
		    $data['widgets_style_title_line_size'] = '1';
        
        if(!isset($data['widgets_style_title_line_color']))
		    $data['widgets_style_title_line_color'] = '';
        
    }

    

    return $data;

}

add_filter('of_options_after_load', 'of_options_after_load_missing');

/**
 * For use in themes
 *
 * @since forever
 */

$data = of_get_options();

$pbtheme_data = of_get_options();

$data = $pbtheme_data;