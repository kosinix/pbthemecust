<?php



/**
 * Include force product option class
 * Include set predefined header design class
 */
require_once dirname( __FILE__ ) . '/lib/force_product_metadata.php';
require_once dirname( __FILE__ ) . '/lib/header_predefined_design/header_predefined_design.php';



/**
 * SMOF Modified / PBTheme
 *
 * @package WordPress
 * @subpackage  SMOF
 * @theme pbtheme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

add_action('init', 'of_options');

if (!function_exists('of_options')) {



    function of_options() {

        global $pbtheme_data;

        $curr_fonts = array(

            'Arial' => 'Arial',

            'Verdana, Geneva' => 'Verdana, Geneva',

            'Trebuchet' => 'Trebuchet',

            'Georgia' => 'Georgia',

            'Times New Roman' => 'Times New Roman',

            'Tahoma, Geneva' => 'Tahoma, Geneva',

            'Palatino' => 'Palatino',

            'Helvetica' => 'Helvetica'

        );

        //Header Elements

        $header_top_elements = array(

            'disabled' => array(

                'login-link' => 'login-link',

                'language-bar' => 'language-bar',

                'menu' => 'menu',

                'network-icons' => 'network-icons',

                'tagline' => 'tagline',

                'tagline-alt' => 'tagline-alt',

                'woo-login-link' => 'woo-login-link',

                'woo-cart' => 'woo-cart'

            ),

            "enabled" => array(

                'placebo' => 'placebo'

            )

        );

        //Footer Elements

        $footer_elements = array(

            'disabled' => array(

                'login-link' => 'login-link',

                'menu' => 'menu',

                'network-icons' => 'network-icons',

                'tagline' => 'tagline',

                'tagline-alt' => 'tagline-alt',

                'to-the-top' => 'to-the-top'

            ),

            "enabled" => array(

                'placebo' => 'placebo'

            )

        );

        //Ready Backgrounds

        $fixed_breadcrumbes = array('none' => 'none');

        $breadcrumbs = array();

        $breadcrumbs = glob(get_template_directory() . '/images/breadcrumbs/*');

        $breadcrumbs = array_filter($breadcrumbs, 'is_file');

        $breadcrumbs = array_map('basename', $breadcrumbs);

        $breadcrumbs = array_combine($breadcrumbs, $breadcrumbs);

        $fixed_breadcrumbes = (!empty($breadcrumbs) ? $fixed_breadcrumbes + $breadcrumbs : $fixed_breadcrumbes );

        //Ready Contacts

        $pbtheme_ready_contacts = array('none' => 'none');

        $counter = 0;

        if (isset($pbtheme_data['contact'])) {

            $pbtheme_contacts = $pbtheme_data['contact'];

            foreach ($pbtheme_contacts as $contact) {

                $counter++;

                $pbtheme_ready_contacts = $pbtheme_ready_contacts + array($counter => $contact['name']);

            }

        }

        //Create Menus

        $menus = get_terms('nav_menu', array('hide_empty' => false));

        $pbtheme_ready_menus = array('none' => 'none');

        foreach ($menus as $menu) {

            $pbtheme_ready_menus[$menu->slug] = $menu->name;

        }

        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */

        global $of_options;

        $of_options = array();

        $of_options[] = array("name" => __('General Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_general",

            "icon" => "imscadmin-admin-logo"

        );

        $of_options[] = array("name" => __('General Settings', 'pbtheme'),

            "desc" => "",

            "id" => "generalsettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Welcome to the Theme Options', 'pbtheme') . "</h3>

						" . __('Use this Theme Options page to setup your site. Save your option once you customize your PBTheme Theme.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Upload Logo', 'pbtheme'),

            "desc" => __('Upload your logo using the native media uploader, or define the URL directly.', 'pbtheme'),

            "id" => "logo",

            "std" => get_template_directory_uri() . '/images/logo.png',

            "type" => "media",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Upload Sticky Logo', 'pbtheme'),

            "desc" => __('Upload your sticky logo using the native media uploader, or define the URL directly.', 'pbtheme'),

            "id" => "logo_sticky",

            "std" => get_template_directory_uri() . '/images/logo_sticky.png',

            "type" => "media",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Logo Size', 'pbtheme'),

            "desc" => __('Set your logo size. Normal real height 40px (Retina images 80px in height), Bigger real height 60px (Retina images 120px in height), Biggest real height 80px (Retina images 160px in height)', 'pbtheme'),

            "id" => "logo_size",

            "std" => "normal",

            "type" => "select",

            "options" => array(

                "normal" => __('Normal', 'pbtheme'),

                "bigger" => __('Bigger', 'pbtheme'),

                "biggest" => __('Biggest', 'pbtheme')

            )

        );

        $of_options[] = array("name" => __('Upload Favicon', 'pbtheme'),

            "desc" => __('Upload your sites favorites icons. Please use .png transparent image files (128x128px).', 'pbtheme'),

            "id" => "favicon",

            "std" => get_template_directory_uri() . '/images/favicon.png',

            "type" => "media",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),

            "desc" => __('Upload your sites iPad and iPhone icons. Please use .png transparent image files (57x57px).', 'pbtheme'),

            "id" => "apple_ti57",

            "std" => get_template_directory_uri() . '/images/favicon.png',

            "type" => "media",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),

            "desc" => __('Upload your sites iPad and iPhone Retina icons. Please use .png transparent image files (72x72px).', 'pbtheme'),

            "id" => "apple_ti72",

            "std" => get_template_directory_uri() . '/images/favicon.png',

            "type" => "media",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),

            "desc" => __('Upload your sites iPad and iPhone icons. Please use .png transparent image files (114x114px).', 'pbtheme'),

            "id" => "apple_ti114",

            "std" => get_template_directory_uri() . '/images/favicon.png',

            "type" => "media",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),

            "desc" => __('Upload your sites iPad and iPhone Retina icons. Please use .png transparent image files (144x144px).', 'pbtheme'),

            "id" => "apple_ti144",

            "std" => get_template_directory_uri() . '/images/favicon.png',

            "type" => "media",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Header Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_layout",

            "icon" => "imscadmin-admin-header"

        );

        $of_options[] = array("name" => __('Header Settings', 'pbtheme'),

            "desc" => "",

            "id" => "header-settings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Header Settings', 'pbtheme') . "</h3>

						" . __('Set your smart header. Setup current header layout and elements that will be included in the top header. Also you can set your widgetized areas before the header.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

		
        $of_options[] = array("name" => __('Sticky Header', 'pbtheme'),

            "desc" => __('Disable or enable sticky header.', 'pbtheme'),

            "id" => "disable_sticky_header",

            "std" => 1,

            "type" => "switch",
            
            "class" => "of-group-small"

        );
		

		$of_options[] = array("name" => __('Disable Top Header', 'pbtheme'),

            "desc" => __('Hide the top header bar globally.', 'pbtheme'),

            "id" => "disable-top-header",

            "std" => 0,

            "type" => "switch",
            
            "class" => "of-group-small"

        );

		

				

        $of_options[] = array("name" => __('Header Layout', 'pbtheme'),

            "desc" => __('Select header layout.', 'pbtheme'),

            "id" => "header_layout",

            "std" => "small-right",

            "type" => "select",

            "options" => array(

                "news-central" => 'news-central',

                "small-left" => "small-left",

                "small-right" => "small-right"

            ),

            "class" => "of-group-small"

        );

		

		

		

        $of_options[] = array("name" => __('Top Header Widgets', 'pbtheme'),

            "desc" => __('Select number of widget areas before header.', 'pbtheme'),

            "id" => "header-widgets-before",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Top Header Left', 'pbtheme'),

            "desc" => __('Select top header left elements.', 'pbtheme'),

            "id" => "header-top-left",

            "std" => $header_top_elements,

            "type" => "sorter"

        );

        $of_options[] = array("name" => __('Top Header Right', 'pbtheme'),

            "desc" => __('Select top header right elements.', 'pbtheme'),

            "id" => "header-top-right",

            "std" => $header_top_elements,

            "type" => "sorter"

        );

        $of_options[] = array("name" => __('Top Header Menu', 'pbtheme'),

            "desc" => __('Select top header custom menu.', 'pbtheme'),

            "id" => "header_menu",

            "std" => "none",

            "type" => "select",

            "options" => $pbtheme_ready_menus,

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Top Header Networks', 'pbtheme'),

            "desc" => __('Select contact option for the header network icons.', 'pbtheme'),

            "id" => "header_networks",

            "std" => "none",

            "type" => "select",

            "options" => $pbtheme_ready_contacts,

            "class" => "of-group-small"

        );



	    /* Added by Shindiry Studio - Code Start */

        

        // Uncomment if needed, for now mega search options are disabled

        /*

	    if (is_plugin_active( 'Mega-Search/main.php' )) {

            $of_options[] = array(

                "name"  => __( "Search Type", "pbtheme" ),

                "desc"  => __( "Set Mega Search in header.", "pbtheme" ),

                "id"    => "header_search_custom",

                "std"   => 0,

                "type"  => "switch",

                "class" => "of-group-small"

            );

        }

        

        

        $of_options[] = array(

            "name" => __("Mega Search Shortcode", "pbtheme"),

            "desc" => __("Enter Mega Search Shortcode.", "pbtheme"),

            "id" => "header_mega_search_shortcode",

            "std" => '[pw-ajax-live-search id="ID"]',

            "type" => "textarea",

            "class" => "of-group-small"

        );

        */



	    $of_options[] = array(

		    "name"  => __( "Search Style", "pbtheme" ),

		    "desc"  => __( "Set Search bar inline. (can be overriden in page, post or product metabox settings)", "pbtheme" ),

		    "id"    => "header_search_style",

		    "std"   => 0,

		    "type"  => "switch",

		    "class" => "of-group-small"

	    );

	    $of_options[] = array(

		    "name"  => __( "Search option", "pbtheme" ),

		    "desc"  => __( "Search site ONLY for products.", "pbtheme" ),

		    "id"    => "header_search_type",

		    "std"   => 0,

		    "type"  => "switch",

		    "class" => "of-group-small"

	    );

	    /* Code End */



        $of_options[] = array("name" => __("Header Tagline", "pbtheme"),

            "desc" => __("Enter header tagline text.", "pbtheme"),

            "id" => "header_tagline",

            "std" => 'PBTheme',

            "type" => "textarea",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Header Tagline Alt", "pbtheme"),

            "desc" => __("Enter header tagline alternative text.", "pbtheme"),

            "id" => "header_tagline_alt",

            "std" => 'WordPress',

            "type" => "textarea",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => esc_html__('Header responsive breakpoint', 'pbtheme'),
            "desc" => esc_html__('Set on what screen width header should use responsive layout. Default value is 640.', 'pbtheme'),
            "id" => "header_breakpoint",
            "std" => "640",
            "min" => "0",
            "step" => "1",
            "max" => "5000",
            "type" => "sliderui",
        );
        
        /**
         * Header design section
         */
        if ( class_exists( 'Pbtheme_Header_Design_Init' ) ) {
        
            // Group
            $of_options[] = array("name" => esc_html__('Header design', 'pbtheme'),

                "type" => "heading",

                "group" => "div_grp_layout",

                "icon" => "imscadmin-admin-header"

            );



            // Info about starting section
            $of_options[] = array("name" => esc_html__('Header style settings', 'pbtheme'),

                "desc" => "",

                "id" => "header_design_info",

                "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Header style settings', 'pbtheme') . "</h3>

                            " . esc_html__('Style header.', 'pbtheme') . "",

                "icon" => false,

                "type" => "info"

            );



            // Acitvate / deactivate button
            $of_options[] = array(

                "name"  => esc_html__( "Enable header styles", "pbtheme" ),

                "desc"  => esc_html__( "Enable or disable header styles. This apply to all setting in this section below.", "pbtheme" ),

                "id"    => "header_styles_enabled",

                "std"   => 0,

                "type"  => "switch",

            );
            
            $header_styles_predefined_img = 
                    isset( $pbtheme_data['header_styles_predefined'] ) && $pbtheme_data['header_styles_predefined'] !== 'custom' ?
                    '<img src="' . ADMIN_DIR . 'assets/images/header_design/preview/' . esc_attr( $pbtheme_data['header_styles_predefined'] ) . '.jpg" />' :
                    '';
            
            $of_options[] = array("name" => esc_html__('Predefined header style', 'pbtheme'),

                "desc" => wp_kses_post ( __('Select one of the predefined header designs. <br /><span style="color: #FF0000">In order to achieve header design, this will override some other sections: Color Settings, Header Settings, Header style settings, Header search styles, Header logo styles, Header responsive menu settings, Header Button Settings, Contact Settings!</span>', 'pbtheme') )
                . '<div class="predefined_header_btn">'
                    . '<img style="display:none" src="' . ADMIN_DIR . 'assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />'
                    . '<button type="button" class="of_set_header_design button-primary">' . esc_html__( 'Apply selected', 'pbtheme' ) . '</button>'
                . '</div>'
                . '<div class="predefined_header_preview">' . $header_styles_predefined_img . '</div>',
                "id" => "header_styles_predefined",
                "std" => "custom",
                "type" => "select",
                "options" => array(
                    "custom" => esc_html__('Custom', 'pbtheme'),
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4",
                    "5" => "5",
                    "6" => "6",
                    "7" => "7",
                    "8" => "8",
                ),
            );
            
            // Header top bar color or pattern
            $of_options[] = array("name" => esc_html__('Header top bar color or pattern', 'pbtheme'),

                "desc" => esc_html__('Use header top bar color or pattern as background.', 'pbtheme'),

                "id" => "header_topbar_bgd_color_pattern",

                "std" => "none",

                "type" => "select",

                "options" => array(
                    "none" => esc_html__("None", 'pbtheme'),
                    "color" => esc_html__("Color", 'pbtheme'),
                    "pattern" => esc_html__("Pattern", 'pbtheme'),
                ),
                
                "class" => 'of-group-small-4'

            );

            $of_options[] = array("name" => esc_html__('Header top bar background color', 'pbtheme'),

                "desc" => esc_html__('Select the background color of the header top bar.', 'pbtheme'),

                "id" => "theme_color_top_header",

                "std" => "#d3d3d3",

                "type" => "color",

                "class" => 'of-group-small-4'

            );
            
            $header_topbar_bgd_opacity_array = array();
            for ($w = 0; $w <= 10; $w++) {
                $header_topbar_bgd_opacity_array[$w] = $w / 10;
            }
            
            $of_options[] = array("name" => esc_html__('Header top bar background color opacity', 'pbtheme'),
                "desc" => esc_html__('Enter value from 0 to 1. Example: 0.5', 'pbtheme'),
                "id" => "header_topbar_bgd_opacity",
                "std" => '10',
                "type" => "select",
                "options" => $header_topbar_bgd_opacity_array,
                "class" => 'of-group-small-4'
            );
            
            $of_options[] = array("name" => esc_html__('Upload header top bar background pattern', 'pbtheme'),

                "desc" => esc_html__('Upload your pattern image to use as backround.', 'pbtheme'),

                "id" => "header_topbar_bgd_pattern",

                "std" => IMAGES_FOLDER . 'patterns/pattern-header.jpg',

                "type" => "media",

                "class" => 'of-group-small-4'

            );
            
            $of_options[] = array("name" => esc_html__('Header top bar text color', 'pbtheme'),
                "desc" => esc_html__('Set header top bar text color.', 'pbtheme'),
                "id" => "header_topbar_text_color",
                "std" => "",
                "type" => "color",
                "class" => "of-group-small-3"
            );
            
            $of_options[] = array("name" => esc_html__('Header top bar link color', 'pbtheme'),
                "desc" => esc_html__('Set header top bar link color.', 'pbtheme'),
                "id" => "header_topbar_link_color",
                "std" => "",
                "type" => "color",
                "class" => "of-group-small-3"
            );
            
            $of_options[] = array("name" => esc_html__('Header top bar link hover color', 'pbtheme'),
                "desc" => esc_html__('Set header top bar link hover color.', 'pbtheme'),
                "id" => "header_topbar_link_hover_color",
                "std" => "",
                "type" => "color",
                "class" => "of-group-small-3"
            );
            
            $of_options[] = array("name" => esc_html__("Header top bar bottom border", "pbtheme"),
                "desc" => esc_html__("Enable or disable header top bar bottom border.", "pbtheme"),
                "id" => "header_topbar_border",
                "std"   => 1,
                "type"  => "switch",
                "class" => "of-group-small-3"
            );
            
            $of_options[] = array("name" => esc_html__('Header top bar bottom border color', 'pbtheme'),
                "desc" => esc_html__('Set header top bar bottom border color.', 'pbtheme'),
                "id" => "header_topbar_border_color",
                "std" => "",
                "type" => "color",
                "class" => "of-group-small-3"
            );
            
            $of_options[] = array("name" => esc_html__('Header top bar text font size', 'pbtheme'),
                "desc" => esc_html__('Set to 0 to use default theme font-size', 'pbtheme'),
                "id" => "header_topbar_font_size",
                "std" => "0",
                "min" => "0",
                "step" => "1",
                "max" => "99",
                "type" => "sliderui",
                "class" => "of-group-small-3"
            );
            
            // Header color or pattern
            $of_options[] = array("name" => esc_html__('Header color or pattern', 'pbtheme'),

                "desc" => esc_html__('Use header color or pattern as background.', 'pbtheme'),

                "id" => "header_bgd_color_pattern",

                "std" => "none",

                "type" => "select",

                "options" => array(
                    "none" => esc_html__("None", 'pbtheme'),
                    "color" => esc_html__("Color", 'pbtheme'),
                    "pattern" => esc_html__("Pattern", 'pbtheme'),
                ),
                
                "class" => 'of-group-small-4'

            );

            $of_options[] = array("name" => esc_html__('Header background color', 'pbtheme'),

                "desc" => esc_html__('Select the background color of the header.', 'pbtheme'),

                "id" => "theme_color_header",

                "std" => "#d3d3d3",

                "type" => "color",

                "class" => 'of-group-small-4'

            );
            
            $header_bgd_opacity_array = array();
            for ($w = 0; $w <= 10; $w++) {
                $header_bgd_opacity_array[$w] = $w / 10;
            }
            
            $of_options[] = array("name" => esc_html__('Header background color opacity', 'pbtheme'),
                "desc" => esc_html__('Enter value from 0 to 1. Example: 0.5', 'pbtheme'),
                "id" => "header_bgd_opacity",
                "std" => '10',
                "type" => "select",
                "options" => $header_bgd_opacity_array,
                "class" => 'of-group-small-4'
            );
            
            $of_options[] = array("name" => esc_html__('Upload header background pattern', 'pbtheme'),

                "desc" => esc_html__('Upload your pattern image to use as backround.', 'pbtheme'),

                "id" => "header_bgd_pattern",

                "std" => IMAGES_FOLDER . 'patterns/pattern-header.jpg',

                "type" => "media",

                "class" => 'of-group-small-4'

            );
            
            $of_options[] = array("name" => esc_html__('Header link color', 'pbtheme'),
                "desc" => esc_html__('Set header link color.', 'pbtheme'),
                "id" => "header_link_color",
                "std" => "",
                "type" => "color",
                "class" => "of-group-small-4"
            );
            
            $of_options[] = array("name" => esc_html__('Header link hover color', 'pbtheme'),
                "desc" => esc_html__('Set header link hover color.', 'pbtheme'),
                "id" => "header_link_hover_color",
                "std" => "",
                "type" => "color",
                "class" => "of-group-small-4"
            );
            
            $of_options[] = array("name" => esc_html__("Header bottom border", "pbtheme"),
                "desc" => esc_html__("Enable or disable header bottom border.", "pbtheme"),
                "id" => "header_border",
                "std"   => 1,
                "type"  => "switch",
                "class" => "of-group-small-4"
            );
            
            $of_options[] = array("name" => esc_html__('Header bottom border color', 'pbtheme'),
                "desc" => esc_html__('Set header bottom border color.', 'pbtheme'),
                "id" => "header_border_color",
                "std" => "",
                "type" => "color",
                "class" => "of-group-small-4"
            );
            
            /**
             * This option is not managed via Pbtheme_Header_Design class, 
             * it is already coded in css file so don't need to add it to custom css
             */
            $of_options[] = array("name" => esc_html__("Header Shadow", "pbtheme"),

                "desc" => esc_html__("Choose header style. With shadow of plain flat design.", "pbtheme"),

                "id" => "header_shadow",

                "std" => 1,

                "on" => esc_html__("Shadow", 'pbtheme'),

                "off" => esc_html__("Flat", 'pbtheme'),

                "type" => "switch",

                "class" => "of-group-small-3"

            );
            
            // Header menu settings
            $of_options[] = array("name" => esc_html__('Header menu font size', 'pbtheme'),

                "desc" => esc_html__('Set to 0 to use default theme font-size', 'pbtheme'),

                "id" => "header_menu_font_size",

                "std" => "0",

                "min" => "0",

                "step" => "1",

                "max" => "99",

                "type" => "sliderui",

                "class" => 'of-group-small-3'

            );
            
            $of_options[] = array("name" => esc_html__('Header menu distance between links', 'pbtheme'),

                "desc" => esc_html__('Set to 0 to use default theme value', 'pbtheme'),

                "id" => "header_menu_distance_links",

                "std" => "0",

                "min" => "0",

                "step" => "1",

                "max" => "99",

                "type" => "sliderui",

                "class" => 'of-group-small-3'

            );
            
            $of_options[] = array("name" => esc_html__('Header Menu height', 'pbtheme'),

                "desc" => esc_html__('Set menu height to full or medium header hight.', 'pbtheme'),

                "id" => "header_menu_height",

                "std" => "full",

                "type" => "select",

                "options" => array(
                    "full" => esc_html__("Full height", 'pbtheme'),
                    "medium" => esc_html__("Medium height", 'pbtheme'),
                ),
                
                "class" => 'of-group-small-3'

            );
            
            $of_options[] = array("name" => esc_html__('Header menu hover effect', 'pbtheme'),

                "desc" => esc_html__('Set menu hover effect.', 'pbtheme'),

                "id" => "header_menu_hover_effect",

                "std" => "full",

                "type" => "select",

                "options" => array(
                    "none" => esc_html__("No effect", 'pbtheme'),
                    "baseline" => esc_html__("Baseline", 'pbtheme'),
                    "background" => esc_html__("Background", 'pbtheme'),
                ),
                
                "class" => 'of-group-small-3'

            );
            
            $of_options[] = array("name" => esc_html__('Header effect color', 'pbtheme'),

                "desc" => esc_html__('Set hover effect color.', 'pbtheme'),

                "id" => "header_menu_hover_effect_color",

                "std" => "#d3d3d3",

                "type" => "color",

                "class" => "of-group-small-3"

            );
            
            $of_options[] = array("name" => esc_html__('Header menu subitem background color', 'pbtheme'),

                "desc" => esc_html__('Set menu subitem background color.', 'pbtheme'),

                "id" => "header_menu_subitem_bgd_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-3"

            );
            
            $of_options[] = array("name" => esc_html__('Header menu subitem color', 'pbtheme'),

                "desc" => esc_html__('Set Menu subitem color.', 'pbtheme'),

                "id" => "header_menu_subitem_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-3"

            );
            
            $of_options[] = array("name" => esc_html__('Header menu subitem hover color', 'pbtheme'),

                "desc" => esc_html__('Set Menu subitem hover color.', 'pbtheme'),

                "id" => "header_menu_subitem_hover_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-3"

            );
            
            /**
             * Header search styles
             */
            // Info section
            $of_options[] = array("name" => esc_html__('Header search styles', 'pbtheme'),

                "desc" => "",

                "id" => "header_design_search_style",

                "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Header search styles', 'pbtheme') . "</h3>

                            " . esc_html__('Style header search colors.', 'pbtheme') . "",

                "icon" => false,

                "type" => "info"

            );
            
            $of_options[] = array("name" => esc_html__('Header search color', 'pbtheme'),

                "desc" => esc_html__('Set header search color.', 'pbtheme'),

                "id" => "header_search_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-4"

            );
            
            $of_options[] = array("name" => esc_html__('Header search icon color', 'pbtheme'),

                "desc" => esc_html__('Set header search icon color.', 'pbtheme'),

                "id" => "header_search_icon_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-4"

            );
            
            $of_options[] = array("name" => esc_html__('Header search default text color', 'pbtheme'),

                "desc" => esc_html__('Set placeholder text color in search.', 'pbtheme'),

                "id" => "header_search_def_text_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-4"

            );
            
            $of_options[] = array("name" => esc_html__('Header search active text color', 'pbtheme'),

                "desc" => esc_html__('Set text color when you typed something in search.', 'pbtheme'),

                "id" => "header_search_active_text_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-4"

            );
            
            /**
             * Header logo settings
             */
            // Info section
            $of_options[] = array("name" => esc_html__('Header logo styles', 'pbtheme'),
                "desc" => "",
                "id" => "header_design_logo_style",
                "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Header logo styles', 'pbtheme') . "</h3>
                            " . esc_html__('Style logo located in header.', 'pbtheme') . "",
                "icon" => false,
                "type" => "info"
            );
            
            $of_options[] = array("name" => esc_html__('Header logo width', 'pbtheme'),
                "desc" => esc_html__('This apply only on header logo. Not sticky header logo or responsive header logo. Set to 0 to use default theme value', 'pbtheme'),
                "id" => "header_logo_width",
                "std" => "0",
                "min" => "0",
                "step" => "1",
                "max" => "999",
                "type" => "sliderui",
                "class" => "of-group-small"
            );
            
            $of_options[] = array(
                "name"  => esc_html__( "Logo overflow", "pbtheme" ),
                "desc"  => esc_html__( "If enabled logo will start at the header top and go outside bottom border.", "pbtheme" ),
                "id"    => "header_logo_overflow",
                "std"   => 0,
                "type"  => "switch",
                "class" => "of-group-small"
            );
            
            /**
             * Header logo settings
             */
            // Info section
            $of_options[] = array("name" => esc_html__('Header responsive menu', 'pbtheme'),

                "desc" => "",

                "id" => "header_design_responsive_menu_style",

                "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Header responsive menu', 'pbtheme') . "</h3>

                            " . esc_html__('Use this settings to override above styles for responsive menu.', 'pbtheme') . "",

                "icon" => false,

                "type" => "info"

            );
            
            $of_options[] = array("name" => esc_html__('Header responsive menu icons color', 'pbtheme'),

                "desc" => esc_html__('Set responsive menu icons color.', 'pbtheme'),

                "id" => "header_resp_menu_icon_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-3"

            );
            
            $of_options[] = array("name" => esc_html__('Header responsive menu icons hover color', 'pbtheme'),

                "desc" => esc_html__('Set responsive menu icons hover color.', 'pbtheme'),

                "id" => "header_resp_menu_icon_hover_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-3"

            );
            
            $of_options[] = array("name" => esc_html__('Header responsive menu line color', 'pbtheme'),

                "desc" => esc_html__('Set responsive menu line color. The lines that separate menu items.', 'pbtheme'),

                "id" => "header_resp_menu_line_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small-3"

            );
        
        } // class_exists Pbtheme_Header_Design


		/**
         * Sellocity custom button shortcode
         */

        if ( class_exists( 'Sellocity_Custom_Button' ) ) {

            

            // Group

            $of_options[] = array("name" => __('Header Button', 'pbtheme'),

                "type" => "heading",

                "group" => "div_grp_layout",

                "icon" => "imscadmin-admin-header"

            );

           

            // Info about starting button section

            $of_options[] = array("name" => esc_html__('Header Button Settings', 'pbtheme'),

                "desc" => "",

                "id" => "custom_button_info",

                "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Header Button Settings', 'pbtheme') . "</h3>

                            " . esc_html__('Set custom button to appear in header and you can customize it here.', 'pbtheme') . "",

                "icon" => false,

                "type" => "info"

            );

            

            // Acitvate / deactivate button

            $of_options[] = array(

                "name"  => esc_html__( "Enable custom button", "pbtheme" ),

                "desc"  => esc_html__( "Enable or disable custom button in header", "pbtheme" ),

                "id"    => "sellocity_btn_enabled",

                "std"   => 0,

                "type"  => "switch",

                "class" => "of-group-small"

            );

            

            // Button text

            $of_options[] =  array("name" => esc_html__('Button text', 'pbtheme'),

                "desc" => esc_html__('Please enter text for button.', 'pbtheme'),

                "id" => "sellocity_btn_text",

                "std" => esc_html__('Button', 'pbtheme'),

                "type" => "text",

                "class" => "of-group-small"

            );

            

            // Button animation

            $of_options[] = array("name" => esc_html__('Button animation type', 'pbtheme'),

                "desc" => esc_html__('Choose animation type of button', 'pbtheme'),

                "id" => "sellocity_btn_hover_btn_type",

                "std" => "hover_none",

                "type" => "select",

                "options" => array(  "hover_none" => esc_html__("None", "pbtheme"), "hover_winona" => esc_html__("Winona", "pbtheme"), "hover_saqui" => esc_html__("Saqui", "pbtheme") , "hover_antiman" => esc_html__("Antiman", "pbtheme") ),

                "class" => "of-group-small"

            );

            

            // Button position

            $of_options[] = array("name" => esc_html__('Button size', 'pbtheme'),

                "desc" => esc_html__('Choose button size.', 'pbtheme'),

                "id" => "sellocity_btn_css_class",

                "std" => "sellocity-custom-btn-medium",

                "type" => "select",

                "options" => array( "sellocity-custom-btn-small" => esc_html__("Small", "pbtheme"), "sellocity-custom-btn-medium" => esc_html__("Medium", "pbtheme"), "sellocity-custom-btn-large" => esc_html__("Large", "pbtheme")),

                "class" => "of-group-small"

            );

            

            // Button text size

            $of_options[] = array("name" => esc_html__('Button text size', 'pbtheme'),

                "desc" => esc_html__('Choose sutton text size.', 'pbtheme'),

                "id" => "sellocity_btn_text_size",

                "std" => "btn_med",

                "type" => "select",

                "options" => array( "btn_med" => esc_html__("Medium", "pbtheme"), "btn_lrg" => esc_html__("Large", "pbtheme"), "btn_smll" => esc_html__("Small", "pbtheme") ),

                "class" => "of-group-small"

            );

        

            // Override font size

            $of_options[] =  array("name" => esc_html__('Override font size (px)', 'pbtheme'),

                "desc" => esc_html__('Choose font size for button text in pixels. Enter only number without sufix px. Leave field empty if you want to use Button text size defined properties.', 'pbtheme'),

                "id" => "sellocity_btn_font_size_p",

                "std" => '',

                "type" => "text",

                "class" => "of-group-small"

            );

            

            // Letter spacing

            $of_options[] = array("name" => esc_html__('Letter spacing', 'pbtheme'),

                "desc" => esc_html__('Minimal value: 0, Maximum value 10.', 'pbtheme'),

                "id" => "sellocity_btn_letter_spacing",

                "std" => "0",

                "min" => "0",

                "step" => "1",

                "max" => "10",

                "type" => "sliderui",

                "class" => "of-group-small"

            );

            

            // Button type

            $of_options[] = array("name" => esc_html__('Button type', 'pbtheme'),

                "desc" => esc_html__('Choose type of button.', 'pbtheme'),

                "id" => "sellocity_btn_type",

                "std" => "btn_crcl",

                "type" => "select",

                "options" => array( "btn_sqr" => esc_html__("Squared", "pbtheme"), "btn_rnd" => esc_html__("Rounded", "pbtheme"), "btn_crcl" => esc_html__("Circled", "pbtheme") ),

                "class" => "of-group-small"

            );

            

            // Button link

            $of_options[] =  array("name" => esc_html__('Button link', 'pbtheme'),

                "desc" => esc_html__('Please enter url to link button..', 'pbtheme'),

                "id" => "sellocity_btn_link_url",

                "std" => '#',

                "type" => "text",

                "class" => "of-group-small"

            );

            

            // Button and icon position - type

            $of_options[] = array("name" => esc_html__('Button icon type', 'pbtheme'),

                "desc" => esc_html__('Choose type of button and icon', 'pbtheme'),

                "id" => "sellocity_btn_icon_type",

                "std" => "icon_no",

                "type" => "select",

                "options" => array( "icon_no" => "None", "icon_vis_left" => "Always visible icon on left", "icon_vis_right" => "Always visible icon on right", "icon_hov_left" => "Show icon on hover on the left", "icon_hov_right" => "Show icon on hover on the right" ),

                "class" => "of-group-small"

            );

            

            // Icon select

            $sellocity_custom_btn_preview = '<div data-png-url="' . esc_js( Sellocity_Custom_Button::get_root_url() . '/assets/images/fontAwsome/' ) . '" id="sellocity-custom-btn-preview"></div>';

            $of_options[] = array("name" => __('Custom button icon', 'pbtheme'),

                "desc" => __('Selected icon:', 'pbtheme') . $sellocity_custom_btn_preview,

                "id" => "sellocity_btn_icon_class",

                "std" => "none",

                "type" => "select",

                "options" => Sellocity_Custom_Button::get_fontAwsome_png_array(),

                "class" => "of-group-small"

            );

            

            // Background color

            $of_options[] = array("name" => esc_html__('Background color', 'pbtheme'),

                "desc" => esc_html__('Choose background color for button.', 'pbtheme'),

                "id" => "sellocity_btn_back_color",

                "std" => "#34495e",

                "type" => "color",

                "class" => "of-group-small"

            );

            

            // Hover color

            $of_options[] = array("name" => esc_html__('Hover background color', 'pbtheme'),

                "desc" => esc_html__('Choose background color for button on hover.', 'pbtheme'),

                "id" => "sellocity_btn_hover_color",

                "std" => "#182a38",

                "type" => "color",

                "class" => "of-group-small"

            );

            

            // Text color

            $of_options[] = array("name" => esc_html__('Text color', 'pbtheme'),

                "desc" => esc_html__('Choose color for text.', 'pbtheme'),

                "id" => "sellocity_btn_text_color",

                "std" => "#ffffff",

                "type" => "color",

                "class" => "of-group-small"

            );

            

            // Text hover color

            $of_options[] = array("name" => esc_html__('Text hover color', 'pbtheme'),

                "desc" => esc_html__('Choose color for text on hover.', 'pbtheme'),

                "id" => "sellocity_btn_text_color_hover",

                "std" => "#ffffff",

                "type" => "color",

                "class" => "of-group-small"

            );

            

            // Border width

            $of_options[] = array("name" => esc_html__('Border width', 'pbtheme'),

                "desc" => esc_html__('Minimal value: 0, Maximum value 10 (pixels).', 'pbtheme'),

                "id" => "sellocity_btn_border_width",

                "std" => "1",

                "min" => "0",

                "step" => "1",

                "max" => "10",

                "type" => "sliderui",

                "class" => "of-group-small"

            );

            

            // Border color

            $of_options[] = array("name" => esc_html__('Border color', 'pbtheme'),

                "desc" => esc_html__('Choose border color.', 'pbtheme'),

                "id" => "sellocity_btn_border_color",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small"

            );

            

            // Border hover color

            $of_options[] = array("name" => esc_html__('Border hover color', 'pbtheme'),

                "desc" => esc_html__('Choose border color on hover.', 'pbtheme'),

                "id" => "sellocity_btn_border_color_hover",

                "std" => "",

                "type" => "color",

                "class" => "of-group-small"

            );

        

        } // end class_exists


        $of_options[] = array("name" => __('Breadcrumbs', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_layout",

            "icon" => "imscadmin-admin-breadcrumbs"

        );

        $of_options[] = array("name" => __('Breadcrumbs', 'pbtheme'),

            "desc" => "",

            "id" => "bloglayout",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Breadcrumbs', 'pbtheme') . "</h3>

						" . __('Setup basic breadcrumbs style.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );
        
        $of_options[] = array(

            "name"  => esc_html__( "Breadcrumbs on / off", "pbtheme" ),

            "desc"  => esc_html__( "Enable or disable breadcrumbs", "pbtheme" ),

            "id"    => "sellocity_breadcrumbs_enabled",

            "std"   => 0,

            "type"  => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Breadcrumbs Background', 'pbtheme'),

            "desc" => __('Select breadcrumbs background. You can add more backgrounds to your /wp-content/themes/pbtheme/images/breadcrumbs/ folder.', 'pbtheme'),

            "id" => "breadcrumbs-style",

            "std" => "abstract-white.jpg",

            "type" => "select",

            "options" => $fixed_breadcrumbes,

        );

        $of_options[] = array("name" => __("Breadcrumbs Line", "pbtheme"),

            "desc" => __("Enter before breadcrumbs text line.", "pbtheme"),

            "id" => "breadcrumbs_line",

            "std" => '',

            "type" => "textarea"

        );

        $of_options[] = array("name" => __('Single Post and Blog', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_layout",

            "icon" => "imscadmin-admin-single"

        );

        $of_options[] = array("name" => __('Blog Settings', 'pbtheme'),

            "desc" => "",

            "id" => "bloglayout",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Blog Settings', 'pbtheme') . "</h3>

						" . __('Select default blog style.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Override Blog Layout', 'pbtheme'),

            "desc" => __('Override default blog layout.', 'pbtheme'),

            "id" => "blog_layout",

            "std" => "6",

            "type" => "select",

            "options" => array(

                "1" => "Blocks",

                "2" => "2 Columns",

                "3" => "3 Columns",

                "4" => "4 Columns",

                "5" => "5 Columns",

                "6" => "Fullwidth Feat Area",

                "7" => "Small Image"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Single Post Settings', 'pbtheme'),

            "desc" => "",

            "id" => "singlepost-settings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Single Post Settings', 'pbtheme') . "</h3>

						" . __('Setup your single posts.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Hide Featured Image', 'pbtheme'),

            "desc" => __('Hide featured images on Single Posts.', 'pbtheme'),

            "id" => "pbtheme_hide_featarea",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Hide Post Title', 'pbtheme'),

            "desc" => __('Hide post titles on Single Posts.', 'pbtheme'),

            "id" => "pbtheme_hide_title",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        /* $of_options[] = array( "name" 		=> __('Hide Post Footer', 'pbtheme'),

          "desc" 		=> __('Hide post footer on Single Posts.', 'pbtheme'),

          "id" 		=> "pbtheme_hide_footer",

          "std" 		=> 0,

          "type" 		=> "switch",

          "class" 	=> "of-group-small"

          ); */

        $of_options[] = array("name" => __('Hide Post Meta', 'pbtheme'),

            "desc" => __('Hide post information on Single Posts. This info is shown just bellow Featured Image.', 'pbtheme'),

            "id" => "pbtheme_hide_meta",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Hide Post Tags", "pbtheme"),

            "desc" => __("Hide post tags at the end of Single Posts.", "pbtheme"),

            "id" => "pbtheme_hide_tags",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Hide Author Information", "pbtheme"),

            "desc" => __("Hide author information on Single Posts.", "pbtheme"),

            "id" => "pbtheme_hide_author",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Hide Post Navigation", "pbtheme"),

            "desc" => __("Hide post navigation at the end of Single Posts.", "pbtheme"),

            "id" => "pbtheme_hide_navigation",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        /**
         * Social settings for single post
         */
        $of_options[] = array("name" => __("Hide Post Share", "pbtheme"),

            "desc" => __("Hide post share bar for Single Posts.", "pbtheme"),

            "id" => "pbtheme_hide_share",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __('Share bar position', 'pbtheme'),

            "desc" => __('Share bar position for single post.', 'pbtheme'),

            "id" => "pbtheme_single_share_position",

            "std" => "bottom",

            "type" => "select",

            "options" => array(

                "top" => esc_html__("Top", 'pbtheme'),

                "bottom" => esc_html__("Bottom", 'pbtheme'),

                "both" => esc_html__("Both", 'pbtheme'),

            ),

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __("Hide Facebook", "pbtheme"),
            
            "desc" => __("Hide single post Facebook share.", "pbtheme"),

            "id" => "pbtheme_hide_post_fb_share",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __("Hide Google+", "pbtheme"),
            
            "desc" => __("Hide single post Google+ share.", "pbtheme"),

            "id" => "pbtheme_hide_post_go_share",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __("Hide Twitter", "pbtheme"),
            
            "desc" => __("Hide single post Twitter share.", "pbtheme"),

            "id" => "pbtheme_hide_post_tw_share",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __("Hide LinkedIn", "pbtheme"),
            
            "desc" => __("Hide single post LinkedIn share.", "pbtheme"),

            "id" => "pbtheme_hide_post_li_share",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __("Hide Pinterest", "pbtheme"),
            
            "desc" => __("Hide single post Pinterest share.", "pbtheme"),

            "id" => "pbtheme_hide_post_pi_share",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        /**
         * Footer settings
         */
        $of_options[] = array("name" => __('Footer Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_layout",

            "icon" => "imscadmin-admin-footer"

        );

        $of_options[] = array("name" => __('Footer Settings', 'pbtheme'),

            "desc" => "",

            "id" => "footersettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Footer Settings', 'pbtheme') . "</h3>

						" . __('Choose footer columns. Set up the copyright text.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __("Disable Footer Widget Areas", "pbtheme"),

            "desc" => __("This option will disable footer widget areas.", "pbtheme"),

            "id" => "footer_widgets",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Footer Widget Areas', 'pbtheme'),

            "desc" => __('Select number of footer widget areas.', 'pbtheme'),

            "id" => "footer_sidebar",

            "std" => "4",

            "type" => "select",

            "options" => array(

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Footer Left', 'pbtheme'),

            "desc" => __('Select bottom footer left elements.', 'pbtheme'),

            "id" => "footer-top-left",

            "std" => $footer_elements,

            "type" => "sorter"

        );

        $of_options[] = array("name" => __('Footer Right', 'pbtheme'),

            "desc" => __('Select bottom footer right elements.', 'pbtheme'),

            "id" => "footer-top-right",

            "std" => $footer_elements,

            "type" => "sorter"

        );

        $of_options[] = array("name" => __('Footer Menu', 'pbtheme'),

            "desc" => __('Select top footer custom menu.', 'pbtheme'),

            "id" => "footer_menu",

            "std" => "none",

            "type" => "select",

            "options" => $pbtheme_ready_menus,

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Footer Networks', 'pbtheme'),

            "desc" => __('Select contact option for the footer network icons.', 'pbtheme'),

            "id" => "footer_networks",

            "std" => "none",

            "type" => "select",

            "options" => $pbtheme_ready_contacts,

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Footer Tagline", "pbtheme"),

            "desc" => __("Enter footer tagline text.", "pbtheme"),

            "id" => "footer_tagline",

            "std" => 'PBTheme',

            "type" => "textarea",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Footer Tagline Alt", "pbtheme"),

            "desc" => __("Enter footer tagline alternative text.", "pbtheme"),

            "id" => "footer_tagline_alt",

            "std" => 'WordPress',

            "type" => "textarea",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Footer Up Arrow", "pbtheme"),

            "desc" => __("Enter text or FontAwesome icon code for the Up Arrow.", "pbtheme"),

            "id" => "footer_up_text",

            "std" => '',

            "type" => "textarea",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Style Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_general",

            "icon" => "imscadmin-admin-style"

        );

        $of_options[] = array("name" => __('Style Settings', 'pbtheme'),

            "desc" => "",

            "id" => "stylesettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Style Settings', 'pbtheme') . "</h3>

						" . __('Setup your PBTheme Style. Choose colors and fonts.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Select Body Font', 'pbtheme'),

            "desc" => __('Selected main font is used for text on pages and posts.', 'pbtheme'),

            "id" => "font",

            "std" => "Arial",

            "type" => "select",

            "options" => $curr_fonts,

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Use Google Font For Body', 'pbtheme'),

            "desc" => __('Override body font with a Google font from Google Fonts Directory.', 'pbtheme'),

            "id" => "font_ggl_on",

            "std" => 1,

            "type" => "switch",

            "folds" => 1,

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Select Body Google Font', 'pbtheme'),

            "desc" => __('Selected Google font will be used for body text.', 'pbtheme'),

            "id" => "font_ggl",

            "std" => array("face" => "Open Sans", "style" => "normal", "weight" => "400"),

            "type" => "typography",

            "fold" => 'font_ggl_on'

        );

        $of_options[] = array("name" => __('Select Header Font', 'pbtheme'),

            "desc" => __('Selected font used for H1,H2,H3,H4,H5,H6 tags.', 'pbtheme'),

            "id" => "font_header",

            "std" => "Arial",

            "type" => "select",

            "options" => $curr_fonts,

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Use Google Font For Headings', 'pbtheme'),

            "desc" => __('Override headings font with a Google font from Google Fonts Directory.', 'pbtheme'),

            "id" => "font_header_ggl_on",

            "std" => 1,

            "type" => "switch",

            "folds" => 1,

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Select Headings Google Font', 'pbtheme'),

            "desc" => __('Selected Google font will be used for H1,H2,H3,H4,H5,H6 tags.', 'pbtheme'),

            "id" => "font_header_ggl",

            "std" => array("face" => "Roboto", "style" => "normal", "weight" => "400"),

            "type" => "typography",

            "fold" => 'font_header_ggl_on'

        );

        $of_options[] = array("name" => __('Colors', 'pbtheme'),

            "desc" => "",

            "id" => "stylesettingscolors",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Color Settings', 'pbtheme') . "</h3>

						" . __('Setup the colors used with PBTheme Theme. Achieve any style which fits your style.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Theme Color', 'pbtheme'),

            "desc" => __('Select Theme Color. Default color code is #82b440.', 'pbtheme'),

            "id" => "theme_color",

            "std" => "#82b440",

            "type" => "color",

            "class" => "of-group-smaller"

        );

        $of_options[] = array("name" => __('Theme Color #1', 'pbtheme'),

            "desc" => __('Select Theme Color #1. This color is used for text, borders and elements. Default color code is #111111.', 'pbtheme'),

            "id" => "theme_color_dark",

            "std" => "#111111",

            "type" => "color",

            "class" => "of-group-smaller"

        );

        $of_options[] = array("name" => __('Theme Color #2', 'pbtheme'),

            "desc" => __('Select Theme Color #2. This color is used for all the backgrounds. Default color code is #ffffff.', 'pbtheme'),

            "id" => "theme_color_light",

            "std" => "#ffffff",

            "type" => "color",

            "class" => "of-group-smaller"

        );

        $of_options[] = array("name" => __('Pale Theme Color', 'pbtheme'),

            "desc" => __('Select Pale Theme Color. This color is used for pale elements. Default color code is #cccccc.', 'pbtheme'),

            "id" => "theme_color_palee",

            "std" => "#cccccc",

            "type" => "color",

            "class" => "of-group-smaller"

        );

        $of_options[] = array("name" => __('Text Color', 'pbtheme'),

            "desc" => __('Select Text Color. Default color code is #444444.', 'pbtheme'),

            "id" => "theme_color_textt",

            "std" => "#444444",

            "type" => "color",

            "class" => "of-group-smaller"

        );
        
        $of_options[] = array(
            "name" => __('Link Color', 'pbtheme'),

            "desc" => __('Select Link Color. Default color code is #0071b9.', 'pbtheme'),

            "id" => "theme_link_color_textt",

            "std" => "#0071b9",

            "type" => "color",

            "class" => "of-group-smaller"

        );
        
        $of_options[] = array(
            "name" => __('Link Hover Color', 'pbtheme'),

            "desc" => __('Select Link Color. Default hover color code is #47a0d9.', 'pbtheme'),

            "id" => "theme_link_hover_color_textt",

            "std" => "#47a0d9",

            "type" => "color",

            "class" => "of-group-smaller"

        );

        $of_options[] = array("name" => __('Footer Text Color', 'pbtheme'),

            "desc" => __('Select footer text color. Default color code is #ffffff.', 'pbtheme'),

            "id" => "theme_color_footer_textt",

            "std" => "#ffffff",

            "type" => "color",

            "class" => "of-group-smaller"

        );

        $of_options[] = array("name" => __('Footer Background Color', 'pbtheme'),

            "desc" => __('Select footer background text color. Default color code is #222222.', 'pbtheme'),

            "id" => "theme_color_footer_bg",

            "std" => "#1b1b1b",

            "type" => "color",

            "class" => "of-group-smaller"

        );

        $of_options[] = array("name" => __('Additional Style Settings', 'pbtheme'),

            "desc" => "",

            "id" => "addstylesettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Additional Style Settings', 'pbtheme') . "</h3>

						" . __('Setup your additional pbtheme style.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __("Header/Footer Borders", "pbtheme"),

            "desc" => __("Select pale or dark styled borders in header and footer.", "pbtheme"),

            "id" => "pbtheme_layout_color",

            "std" => 1,

            "on" => __("Pale", 'pbtheme'),

            "off" => __("Dark", 'pbtheme'),

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Sidebar and Widgetized areas', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_layout",

            "icon" => "imscadmin-admin-sidebar"

        );

        $of_options[] = array("name" => __('Sidebar Settings', 'pbtheme'),

            "desc" => "",

            "id" => "sidebarsettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebar Settings', 'pbtheme') . "</h3>

						" . __('Setup default post/product archive sidebar. Create custom sidebars to use on pages.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Sidebar Width', 'pbtheme'),

            "desc" => __('Choose your sidebars width.', 'pbtheme'),

            "id" => "sidebar-size",

            "std" => "3",

            "type" => "select",

            "options" => array(

                "3" => "Third",

                "4" => "Fourth",

                "5" => "Fifth"

            )

        );

        $of_options[] = array("name" => __('Blog Archive Sidebar', 'pbtheme'),

            "desc" => __('Enable Blog Archive sidebar. This sidebar appears on Blog Archive pages.', 'pbtheme'),

            "id" => "sidebar-blog",

            "std" => 1,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Sidebar Position", "pbtheme"),

            "desc" => __("Use left or right sidebar.", "pbtheme"),

            "id" => "sidebar-position",

            "std" => 0,

            "on" => "Left",

            "off" => "Right",

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Single Posts Sidebar', 'pbtheme'),

            "desc" => __('Enable Single Posts sidebar. This sidebar appears on Single Posts.', 'pbtheme'),

            "id" => "sidebar-single",

            "std" => 1,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Sidebar Position", "pbtheme"),

            "desc" => __("Use left or right sidebar.", "pbtheme"),

            "id" => "sidebar-single-position",

            "std" => 0,

            "on" => "Left",

            "off" => "Right",

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Widgetized Areas', 'pbtheme'),

            "desc" => "",

            "id" => "sidebarsettingswidgets",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas', 'pbtheme') . "</h3>

						" . __('Use special widgetized areas. Before and after blog archives and single posts.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Before Archive', 'pbtheme'),

            "desc" => __('Select number of widget areas before blog archives.', 'pbtheme'),

            "id" => "blog-widgets-before",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('After Archive', 'pbtheme'),

            "desc" => __('Select number of widget areas after blog archives.', 'pbtheme'),

            "id" => "blog-widgets-after",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Before Single Post', 'pbtheme'),

            "desc" => __('Select number of widget areas before single post.', 'pbtheme'),

            "id" => "single-widgets-before",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('After Single Post', 'pbtheme'),

            "desc" => __('Select number of widget areas after single post.', 'pbtheme'),

            "id" => "single-widgets-after",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Sidebar Manager', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_layout",

            "icon" => "imscadmin-admin-sidebar"

        );

        $of_options[] = array("name" => __('Sidebars', 'pbtheme'),

            "desc" => "",

            "id" => "sidebarsettingssidebars",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebar Manager', 'pbtheme') . "</h3>

						" . __('Create new sidebars to use in your pages and posts. Create unlimited number of sidebars and use them in Profit Builder via Sidebar element.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Sidebars', 'pbtheme'),

            "desc" => __('Unlimited sidebars for your pages/posts.', 'pbtheme'),

            "id" => "sidebar",

            "std" => array(

                1 => array(

                    'order' => 1,

                    'title' => 'Your first Sidebar!'

                )

            ),

            "type" => "sidebar"

        );
        
         /**
         * Widgets style
         */
        $of_options[] = array("name" => esc_html__('Widgets Style Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_layout",
            "icon" => "imscadmin-admin-style"
        );

        $of_options[] = array("name" => esc_html__('Widgets Style Settings', 'pbtheme'),
            "desc" => "",
            "id" => "widgets_style_settings_info",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . esc_html__('Widgets Style Settings', 'pbtheme') . "</h3>
						" . esc_html__('Setup your PBTheme widgets style.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        
        $of_options[] = array("name" => esc_html__('Top bar widgets area background color', 'pbtheme'),
            "desc" => esc_html__('Clear the value to use theme default color.', 'pbtheme'),
            "id" => "widgets_style_topbar_warea_bgd",
            "std" => "",
            "type" => "color",
            "class" => 'of-group-small-3'
        ); 
        
        $of_options[] = array("name" => esc_html__('Widget title color', 'pbtheme'),
            "desc" => esc_html__('Clear the value to use theme default color.', 'pbtheme'),
            "id" => "widgets_style_title_color",
            "std" => "",
            "type" => "color",
            "class" => 'of-group-small-3'
        ); 
                
        $of_options[] = array("name" => esc_html__('Widget title background color', 'pbtheme'),
            "desc" => esc_html__('Clear the value to use theme default color.', 'pbtheme'),
            "id" => "widgets_style_title_bgd_color",
            "std" => "",
            "type" => "color",
            "class" => 'of-group-small-3'
        );

        $of_options[] = array("name" => esc_html__('Widget title line', 'pbtheme'),
            "desc" => esc_html__('Selected widget title line style', 'pbtheme'),
            "id" => "widgets_style_title_line",
            "std" => "long-line",
            "type" => "select",
            "options" => array(
                "long-line" => esc_html__( "Long", "pbtheme" ),
                "no-line" => esc_html__( "No line", "pbtheme" ),
                "short-line" => esc_html__( "Short", "pbtheme" ),
                "short-left-line" => esc_html__( "Short Left", "pbtheme" ),
                "short-bottom-line" => esc_html__( "Short Bottom", "pbtheme" ),
                "long-bottom-line" => esc_html__( "Left Bottom", "pbtheme" ),
                "vertical-left-line" => esc_html__( "Vertical Left", "pbtheme" ),
            ),
            "class" => "of-group-small-3"
        );
        
        $of_options[] = array("name" => esc_html__('Widget title line size', 'pbtheme'),
            "desc" => esc_html__('Set line size for widget title. Set to 0 to use theme default size.', 'pbtheme'),
            "id" => "widgets_style_title_line_size",
            "std" => "1",
            "min" => "0",
            "step" => "1",
            "max" => "99",
            "type" => "sliderui",
            "class" => "of-group-small-3"
        );
        
        $of_options[] = array("name" => esc_html__('Widget title line color', 'pbtheme'),
            "desc" => esc_html__('Clear the value to use theme default color.', 'pbtheme'),
            "id" => "widgets_style_title_line_color",
            "std" => "",
            "type" => "color",
            "class" => 'of-group-small-3'
        );
        
        /**
         * Contact settings
         */

        $of_options[] = array("name" => __('Contact Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_general",

            "icon" => "imscadmin-admin-contact"

        );

        $of_options[] = array("name" => __('Contact Form Custom Message', 'pbtheme'),

            "desc" => "",

            "id" => "contactsettingsemail",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Contact Form Custom Message', 'pbtheme') . "</h3>" .

            __('Set your Contact Form custom message. This message will appear once the E-Mail is sent.', 'pbtheme'),

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Contact Form Custom Message', 'pbtheme'),

            "desc" => __('Enter custom HTML/Text.', 'pbtheme'),

            "id" => "contactform_message",

            "std" => "",

            "type" => "textarea"

        );

        $of_options[] = array("name" => __('Contact Settings', 'pbtheme'),

            "desc" => "",

            "id" => "contactsettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Contact Settings', 'pbtheme') . "</h3>" .

            __('Setup your team members.', 'pbtheme'),

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Contact Settings / Team Members', 'pbtheme'),

            "desc" => __('Add or remove contact options/team members. You can use this entries later in your content as Team element or Contact Form element.', 'pbtheme'),

            "id" => "contact",

            "std" => array(

                1 => array(

                    'order' => 1,

                    'name' => 'Your first Contact!',

                    'url' => get_template_directory_uri() . '/images/logo.png',

                    'job' => 'designer',

                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',

                    'email' => 'google@gmail.com',

                    'contact' => array(

                        1 => array(

                            'socialnetworksurl' => '#',

                            'socialnetworks' => 'white_facebook.png'

                        )

                    )

                )

            ),

            "type" => "contact"

        );

        $of_options[] = array("name" => __('Custom CSS', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_general",

            "icon" => "imscadmin-admin-css"

        );

        $of_options[] = array("name" => __('Custom CSS', 'pbtheme'),

            "desc" => "",

            "id" => "csssettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Custom CSS', 'pbtheme') . "</h3>

						" . __('Write some custom CSS for your site.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Custom CSS', 'pbtheme'),

            "desc" => __('Quickly add some CSS to your theme by adding it to this block.', 'pbtheme'),

            "id" => "custom-css",

            "std" => "",

            "type" => "textarea"

        );

        $of_options[] = array("name" => __('bbPress Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_bbpress",

            "icon" => "imscadmin-admin-bbpress"

        );

        $of_options[] = array("name" => __('bbPress Settings', 'pbtheme'),

            "desc" => "",

            "id" => "bbpresssettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('General - <span>bbPress</span>', 'pbtheme') . "</h3>

							" . __('Setup you bbPress Forums.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __("bbPress Forum Prefix", "pbtheme"),

            "desc" => __("Enter forum prefix to be used in the breadcrumbs.", "pbtheme"),

            "id" => "bbpress_forum",

            "std" => 'PBTheme Forum',

            "type" => "text",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('bbPress Settings', 'pbtheme'),

            "desc" => "",

            "id" => "bbpresssid",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebars - <span>bbPress</span>', 'pbtheme') . "</h3>

							" . __('Setup you bbPress forum sidebars.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Sidebar Width', 'pbtheme'),

            "desc" => __('Choose your sidebars width.', 'pbtheme'),

            "id" => "sidebar-bbpress-size",

            "std" => "3",

            "type" => "select",

            "options" => array(

                "3" => "Third",

                "4" => "Fourth",

                "5" => "Fifth"

            )

        );

        $of_options[] = array("name" => __('bbPress Forum Sidebar', 'pbtheme'),

            "desc" => __('Enable Forum sidebar. This sidebar appears on bbPress Forum pages.', 'pbtheme'),

            "id" => "sidebar-bbpress",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Sidebar Position", "pbtheme"),

            "desc" => __("Use left or right sidebar.", "pbtheme"),

            "id" => "sidebar-bbpress-position",

            "std" => 0,

            "on" => "Left",

            "off" => "Right",

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('bbPress Widgetized Areas', 'pbtheme'),

            "desc" => "",

            "id" => "bbpresssid",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas - <span>bbPress</span>', 'pbtheme') . "</h3>

							" . __('Setup you bbPress forum widgetized areas.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Before Forum', 'pbtheme'),

            "desc" => __('Select number of widget areas before forum.', 'pbtheme'),

            "id" => "bbpress-widgets-before",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('After Forum', 'pbtheme'),

            "desc" => __('Select number of widget areas after forum products.', 'pbtheme'),

            "id" => "bbpress-widgets-after",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('WooCommerce General', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_woo",

            "icon" => "imscadmin-admin-woo"

        );

        $of_options[] = array("name" => __('WooCommerce General', 'pbtheme'),

            "desc" => "",

            "id" => "woosettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('General - <span>WooCommerce</span>', 'pbtheme') . "</h3>

							" . __('Setup basic Woocommerce settings.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Products Per Page', 'pbtheme'),

            "desc" => __('Set number of products per page for the shop and product archives.', 'pbtheme'),

            "id" => "woo_per_page",

            "std" => "16",

            "min" => "1",

            "step" => "1",

            "max" => "50",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Woocommerce Columns', 'pbtheme'),

            "desc" => __('Default number of Woocommerce columns on Shop and Archive pages.', 'pbtheme'),

            "id" => "woo-columns",

            "std" => "4",

            "type" => "select",

            "options" => array(

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Woocommerce Related Columns', 'pbtheme'),

            "desc" => __('Default number of Woocommerce columns for related and upsells.', 'pbtheme'),

            "id" => "woo-columns-rel",

            "std" => "4",

            "type" => "select",

            "options" => array(

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        //new options start

        $of_options[] = array("name" => __('Products Title Height', 'pbtheme'),

            "id" => "woo_title_height",

            "desc" => __('This determines the height in pixels of the title container for products shown in featured areas such as related items and archive view', 'pbtheme'),

            "std" => "60",

            "min" => "1",

            "step" => "1",

            "max" => "100",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

		

        // Added by shindiri studio

        $of_options[] = array("name" => __('Enable Meta Data', 'pbtheme'),

            "id" => "woo_meta_data",

            "desc" => __('Enable or disable meta data like SKU, tags, etc.', 'pbtheme')

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        

        $of_options[] = array("name" => __('Enable Social Sharing', 'pbtheme'),

            "id" => "woo_soc_sharing",

            "desc" => __('Enable or disable social sharing', 'pbtheme')

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        

        $of_options[] = array("name" => __('Enable Description', 'pbtheme'),

            "id" => "woo_product_descp",

            "desc" => __('Enable or disable product description', 'pbtheme')

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Enable Additional information', 'pbtheme'),

            "id" => "woo_product_addinfo",

            "desc" => __('Enable or disable product additional information', 'pbtheme')

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "std" => 1,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Enable Reviews', 'pbtheme'),

            "id" => "woo_product_reviews",

            "desc" => __('Enable or disable product reviews', 'pbtheme')

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        

        $of_options[] = array("name" => __('Enable Related Products', 'pbtheme'),

            "id" => "woo_single_related",

            "desc" => __('Enable or disable related products', 'pbtheme') 

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __('Hide breadcrumbs', 'pbtheme'),

            "id" => "woo_enable_breadcrumbs",

            "desc" => __('Hide breadcrumbs on products', 'pbtheme') 

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        

       $of_options[] =  array("name" => __('Add to cart text', 'pbtheme'),

            "desc" => __('Enter text', 'pbtheme')

                . Pb_Theme_Force_Product_Metadata::force_settings_button() 

                . Pb_Theme_Force_Product_Metadata::force_settings_description(),

            "id" => "woo_addtocart",

            "std" => __('Add to cart', 'pbtheme'),

            "type" => "text",

            "class" => "of-group-small"

       );

       

       $of_options[] = array("name" => __('Add to cart on single product padding top', 'pbtheme'),

            "id" => "woo_single_add_to_cart_pt",

            "desc" => __('Select padding-top css value in px', 'pbtheme'),

            "std" => "20",

            "min" => "1",

            "step" => "1",

            "max" => "500",

            "type" => "sliderui",

            "class" => "of-group-small"

       );

        

       $of_options[] = array("name" => __('Add to cart on single product padding bottom', 'pbtheme'),

            "id" => "woo_single_add_to_cart_pb",

            "desc" => __('Select padding-bottom css value in px', 'pbtheme'),

            "std" => "20",

            "min" => "1",

            "step" => "1",

            "max" => "500",

            "type" => "sliderui",

            "class" => "of-group-small"

       );

        

       // End shindiri

		

		$of_options[] = array("name" => __('Disable Add to Cart Icon', 'pbtheme'),

            "id" => "woo_disable_carticon",

            "desc" => __('Disable the add to cart icon on product thumbnails', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

		

		$of_options[] = array("name" => __('Force Product Thumbnail Image Flip', 'pbtheme'),

            "id" => "woo_force_imageflip",

            "desc" => __('Enables the product thumbnail image flip even if the product has only one image', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

		

		$of_options[] = array("name" => __('Hide products count for categories', 'pbtheme'),

            "id" => "woo_disable_catcount",

            "desc" => __('Disable the add to cart icon on product thumbnails', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );



        // Added by shindiri studio

        $of_options[] = array("name" => __('Hide product categories on product archives', 'pbtheme'),

            "id" => "woo_disable_product_category",

            "desc" => __('Disable product categories on shop and all product archives', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        // Added by shindiri studio

        $of_options[] = array("name" => __('WooCommerce price color', 'pbtheme'),

            "desc" => __('Product price color across the site. Default is theme color', 'pbtheme'),

            "id" => "wc_price_color",

            "std" => "",

            "type" => "color",

            "class" => "of-group-small"

        );

        // Added by shindiri studio

        $of_options[] = array("name" => __('WooCommerce message background color', 'pbtheme'),

            "desc" => __('(like one that is shown on single product when added to cart.)', 'pbtheme'),

            "id" => "wc_message_body_color",

            "std" => "#000000",

            "type" => "color",

            "class" => "of-group-small"

        );

		// Added by shindiri studio

        $of_options[] = array("name" => __('WooCommerce message text color', 'pbtheme'),

            "desc" => __('(like one that is shown on single product when added to cart.)', 'pbtheme'),

            "id" => "wc_message_text_color",

            "std" => "#ffffff",

            "type" => "color",

            "class" => "of-group-small"

        );

		// Added by shindiri studio

        $of_options[] = array("name" => __('WooCommerce message link in text color', 'pbtheme'),

            "desc" => __('(like one that is shown on single product when added to cart.)', 'pbtheme'),

            "id" => "wc_message_link_color",

            "std" => "#82b440",

            "type" => "color",

            "class" => "of-group-small"

        );

        

        // Added by shindiri studio

        $of_options[] = array("name" => __('WooCommerce message icon color', 'pbtheme'),

            "desc" => __('(Default color is message text color.)', 'pbtheme'),

            "id" => "wc_message_icon_color",

            "std" => "",

            "type" => "color",

            "class" => "of-group-small"

        );

		

		

        $of_options[] = array("name" => __('Enable Facebook', 'pbtheme'),

            "id" => "woo_enable_fb",

            "desc" => __('Allow products to be shared to Facebook', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

		

        $of_options[] = array("name" => __('Enable Twitter', 'pbtheme'),

            "id" => "woo_enable_tw",

            "desc" => __('Allow products to be shared to Twitter', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Enable Pinterest', 'pbtheme'),

            "id" => "woo_enable_pin",

            "desc" => __('Allow products to be shared to Pinterest', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

		$of_options[] = array("name" => __('Enable Google Plus', 'pbtheme'),

            "id" => "woo_enable_gplus",

            "desc" => __('Allow products to be shared to Google Plus', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

		

     /*$of_options[] = array("name" => __('Enable Email', 'pbtheme'),

            "id" => "woo_enable_email",

            "desc" => __('Allow products to be shared by Email', 'pbtheme'),

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );*/

//new options end

        $of_options[] = array("name" => __('WooCommerce Sidebars', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_woo",

            "icon" => "imscadmin-admin-woo"

        );

        $of_options[] = array("name" => __('WooCommerce Sidebars', 'pbtheme'),

            "desc" => "",

            "id" => "woosid",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebars - <span>WooCommerce</span>', 'pbtheme') . "</h3>

							" . __('Setup Woocommerce sidebar settings.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Woocommerce Sidebar Width', 'pbtheme'),

            "desc" => __('Choose your Woocommerce sidebars width.', 'pbtheme'),

            "id" => "sidebar-size-woo",

            "std" => "Third",

            "type" => "select",

            "options" => array(

                "3" => "Third",

                "4" => "Fourth",

                "5" => "Fifth"

            )

        );

        $of_options[] = array("name" => __('Woocommerce Archive Sidebar', 'pbtheme'),

            "desc" => __('Enable Woocommerce sidebar on Archive and Shop pages.', 'pbtheme'),

            "id" => "sidebar-woo",

            "std" => 1,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Woocommerce Archive Sidebar Position", "pbtheme"),

            "desc" => __("Use left or right sidebar.", "pbtheme"),

            "id" => "woo-sidebar-position",

            "std" => 1,

            "on" => "Left",

            "off" => "Right",

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Woocommerce Post Sidebar', 'pbtheme'),

            "desc" => __('Enable Woocommerce sidebar on Single Posts.', 'pbtheme'),

            "id" => "sidebar-woo-single",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Woocommerce Post Sidebar Position", "pbtheme"),

            "desc" => __("Use left or right sidebar.", "pbtheme"),

            "id" => "woo-sidebar-position-single",

            "std" => 1,

            "on" => "Left",

            "off" => "Right",

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('WooCommerce Widgetized Areas', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_woo",

            "icon" => "imscadmin-admin-woo"

        );

        $of_options[] = array("name" => __('WooCommerce Widgetized Areas', 'pbtheme'),

            "desc" => "",

            "id" => "woowid",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas - <span>WooCommerce</span>', 'pbtheme') . "</h3>

							" . __('Setup Woocommerce widgetized areas before and after content.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Woocommerce Widgetized Areas', 'pbtheme'),

            "desc" => "",

            "id" => "woowidgets",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas', 'pbtheme') . "</h3>

							" . __('Use special widgetized areas. Before and after shop archives and single products.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Before Shop Archive', 'pbtheme'),

            "desc" => __('Select number of widget areas before shop archives.', 'pbtheme'),

            "id" => "shop-widgets-before",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('After Shop Archive', 'pbtheme'),

            "desc" => __('Select number of widget areas after shop archives.', 'pbtheme'),

            "id" => "shop-widgets-after",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Before Product', 'pbtheme'),

            "desc" => __('Select number of widget areas before single products.', 'pbtheme'),

            "id" => "product-widgets-before",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('After Product', 'pbtheme'),

            "desc" => __('Select number of widget areas after single products.', 'pbtheme'),

            "id" => "product-widgets-after",

            "std" => "none",

            "type" => "select",

            "options" => array(

                "none" => "none",

                "1" => "1",

                "2" => "2",

                "3" => "3",

                "4" => "4"

            ),

            "class" => "of-group-small"

        );

        /*

          if ( !get_transient('PBTheme_Demo_Remove') ) {

          $of_options[] = array( 	"name" 		=> __('Demo Installation', 'pbtheme'),

          "type" 		=> "heading",

          "group" 	=> "div_grp_demo",

          "icon" 		=> "imscadmin-admin-demo"

          );

          $of_options[] = array( 	"name" 		=> __('Demo Installation', 'pbtheme'),

          "desc" 		=> "",

          "id" 		=> "demoinstallation",

          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Install PBTheme Demo - <span>Demo</span>', 'pbtheme')."</h3><h5><span class='red'>".__("IMPORTANT",'pbtheme')."</span></h5>

          ".__('Demo content can only be installed on a clean Wordpress installation. If you have any posts/pages do not install demo as it will overwrite all your posts and pages. Make sure that your Wordpress version is 3.8 or newer.', 'pbtheme'),

          "icon" 		=> true,

          "type" 		=> "info"

          );

          $of_options[] = array( 	"name" 		=> __('Step 1', 'pbtheme'),

          "desc" 		=> "",

          "id" 		=> "demo_plugins_h",

          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Step 1 - Plugins', 'pbtheme')."</h3>

          ".__('Install and activate all the plugins used by PBTheme Theme.', 'pbtheme')."",

          "icon" 		=> true,

          "type" 		=> "info"

          );

          $of_options[] = array( 	"name" 		=> __('Installed Plugins', 'pbtheme'),

          "desc" 		=> __('This list shows needed plugins. Please install and activate all the plugins before you continue with the demo installation.', 'pbtheme'),

          "id" 		=> "demo_plugins",

          "std" 		=> "",

          "type" 		=> "demoplugins"

          );

          $of_options[] = array( 	"name" 		=> __('Step 2', 'pbtheme'),

          "desc" 		=> "",

          "id" 		=> "demo_images_h",

          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Step 2 - Images', 'pbtheme')."</h3>

          ".__('Download images used in the demo content.', 'pbtheme')."",

          "icon" 		=> true,

          "type" 		=> "info"

          );

          $of_options[] = array( 	"name" 		=> __('Download Images', 'pbtheme'),

          "desc" 		=> __('If you cannot see all of the images on left then your installation has failed due to an error caused by max_execution_time of your server. Either contact your service provider or try uploading the file again and again until you can see all the images.', 'pbtheme'),

          "id" 		=> "demo_images",

          "std" 		=> "",

          "type" 		=> "demoimages"

          );

          $of_options[] = array( 	"name" 		=> __('Step 3', 'pbtheme'),

          "desc" 		=> "",

          "id" 		=> "demo_content_h",

          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Step 3 - Demo Content', 'pbtheme')."</h3>

          ".__('One click demo installation. Please make sure you have completed the previous two steps!', 'pbtheme')."",

          "icon" 		=> true,

          "type" 		=> "info"

          );

          $of_options[] = array( 	"name" 		=> __('Demo Content', 'pbtheme'),

          "desc" 		=> __('Click the Install Demo Content button to make your site look just like our PBTheme Demo', 'pbtheme').' <a href="http://www.imsuccesscenter.com/demo/?item=PBTheme_Wordpress">'.__('LINK', 'pbtheme').'</a>. '.__('Please make sure you regenerate your thumbnails upon the demo installation.', 'pbtheme'),

          "id" 		=> "demo_content",

          "std" 		=> "",

          "type" 		=> "democontent"

          );

          $of_options[] = array( 	"name" 		=> __('Remove Demo', 'pbtheme'),

          "desc" 		=> "",

          "id" 		=> "demoinstallation",

          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Notice', 'pbtheme')."</h3><h5><span class='red'>".__("IMPORTANT",'pbtheme')."</span></h5>

          ".__('If you do not want to use the Demo Content and you wish this option to be removed from the PBTheme Theme options panel click the Remove Demo Tab button.', 'pbtheme')."<br/><br/><a href='#' id='demo_remove' class='button-primary'>".__('Remove Demo Tab', 'pbtheme')."</a>",

          "icon" 		=> true,

          "type" 		=> "info"

          );

          }

         */

        $of_options[] = array("name" => __('General', 'pbtheme'),

            "id" => "div_grp_general",

            "type" => "group"

        );

        $of_options[] = array("name" => __('Layout', 'pbtheme'),

            "id" => "div_grp_layout",

            "type" => "group"

        );

        $of_options[] = array("name" => __('Advanced', 'pbtheme'),

            "id" => "div_grp_advanced",

            "type" => "group"

        );

        /* 				

          if ( !get_transient('PBTheme_Demo_Remove') ) {

          $of_options[] = array( 	"name" 		=> __('Demo', 'pbtheme'),

          "id"			=> "div_grp_demo",

          "type" 		=> "group"

          );

          }

         */

        if (DIVWP_WOOCOMMERCE === true) {

            $of_options[] = array("name" => __('WooCommerce', 'pbtheme'),

                "id" => "div_grp_woo",

                "type" => "group"

            );

        }

        if (DIVWP_BBPRESS === true) {

            $of_options[] = array("name" => __('bbPress', 'pbtheme'),

                "id" => "div_grp_bbpress",

                "type" => "group"

            );

        }

        $of_options[] = array("name" => __('Advanced General', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_advanced",

            "icon" => "imscadmin-admin-advanced"

        );

        $of_options[] = array("name" => __('Advanced General', 'pbtheme'),

            "desc" => "",

            "id" => "advgen",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced General', 'pbtheme') . "</h3>

						" . __('Set custom CSS classes, Google Analytics code.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __("Responsive / Fixed Layout", "pbtheme"),

            "desc" => __("Enable Responsive layout or use Fixed layout insted.", "pbtheme"),

            "id" => "responsive",

            "std" => 1,

            "on" => "Responsive",

            "off" => "Fixed",

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Disable PBTheme Mega Menu", "pbtheme"),

            "desc" => __("Activate this option to disable PBTheme Mega Menu. This option can be usefull if you want to use plugins such as UberMenu and other mega menu plugins.", "pbtheme"),

            "id" => "disable_menu",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Enable Comments on Pages", "pbtheme"),

            "desc" => __("If you need comments on pages please enable this switch.", "pbtheme"),

            "id" => "enable_comments",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );
        
        $of_options[] = array("name" => __("Enable Open Graph Meta Tags", "pbtheme"),

            "desc" => __("Enable Open Graph Meta Tags.", "pbtheme"),


            "id" => "enable_ogmeta",

            "std" => 1,

            "type" => "switch",

            "class" => "of-group-small"

        );

        

        $of_options[] = array("name" => __('Tracking Code', 'pbtheme'),

            "desc" => __('Paste your Google Analytics (or other) tracking code here. This will be added into the header of your site.', 'pbtheme'),

            "id" => "tracking-code",

            "std" => "",

            "type" => "textarea"

        );

		$of_options[] = array("name" => __('Footer Tracking Code', 'pbtheme'),

            "desc" => __('Paste your footer tracking code here. This will be added into the footer of your site.', 'pbtheme'),

            "id" => "tracking-code-footer",

            "std" => "",

            "type" => "textarea"

        );

        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_advanced",

            "icon" => "imscadmin-admin-advlay"

        );

        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),

            "desc" => "",

            "id" => "advlaysettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout', 'pbtheme') . "</h3>

						" . __('Set your layout. Set up your default margins used in the PBTheme Theme.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __("Boxed / Wide Layout", "pbtheme"),

            "desc" => __("Enable Boxed layout or use Wide layout insted.", "pbtheme"),

            "id" => "boxed",

            "std" => 0,

            "on" => "Boxed",

            "off" => "Wide",

            "type" => "switch"

        );

        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),

            "desc" => "",

            "id" => "layhigh",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout Settings - <span>High Resolution</span>', 'pbtheme') . "</h3>

						" . __('Set your advanced layout settings for high resolution devices.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Content Width / High Resolution', 'pbtheme'),

            "desc" => __('Responsive width for high resolutions. Default value 1200px.', 'pbtheme'),

            "id" => "content_width",

            "std" => "1200",

            "min" => "960",

            "step" => "1",

            "max" => "1200",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Column Margins', 'pbtheme'),

            "desc" => __('Column margins for high resolutions. Default value 36px.', 'pbtheme'),

            "id" => "fb_hres_c",

            "std" => "36",

            "min" => "1",

            "step" => "1",

            "max" => "60",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Default Bottom Margin', 'pbtheme'),

            "desc" => __('Set the default bottom margin on all elements. Default 36px.', 'pbtheme'),

            "id" => "fb_bmargin",

            "std" => "36",

            "min" => "1",

            "step" => "1",

            "max" => "100",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),

            "desc" => "",

            "id" => "laymed",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout Settings - <span>Medium Resolution</span>', 'pbtheme') . "</h3>

						" . __('Set your advanced layout settings for medium resolution devices.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Medium Resolution', 'pbtheme'),

            "desc" => __('Responsive width for medium resolutions. Default value 768px.', 'pbtheme'),

            "id" => "fb_mres_w",

            "std" => "768",

            "min" => "480",

            "step" => "1",

            "max" => "1200",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Column Margins', 'pbtheme'),

            "desc" => __('Column margins for medium resolutions. Default value 10px.', 'pbtheme'),

            "id" => "fb_mres_c",

            "std" => "18",

            "min" => "1",

            "step" => "1",

            "max" => "60",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Hide Sidebars", "pbtheme"),

            "desc" => __("Hide sidebars after current width.", "pbtheme"),

            "id" => "fb_mres_s",

            "std" => 0,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),

            "desc" => "",

            "id" => "laylow",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout Settings - <span>Low Resolution</span>', 'pbtheme') . "</h3>

						" . __('Set your advanced layout settings for low resolution devices.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Low Resolution', 'pbtheme'),

            "desc" => __('Responsive width for low resolutions. Default value 640px.', 'pbtheme'),

            "id" => "fb_lres_w",

            "std" => "640",

            "min" => "320",

            "step" => "1",

            "max" => "1200",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Column Margins', 'pbtheme'),

            "desc" => __('Column margins for low resolutions. Default value 5px.', 'pbtheme'),

            "id" => "fb_lres_c",

            "std" => "12",

            "min" => "1",

            "step" => "1",

            "max" => "60",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Hide Sidebars", "pbtheme"),

            "desc" => __("Hide sidebars after current width.", "pbtheme"),

            "id" => "fb_lres_s",

            "std" => 1,

            "type" => "switch",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Featured Images', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_advanced",

            "icon" => "imscadmin-admin-fimage"

        );

        $of_options[] = array("name" => __('Setup Featured Images', 'pbtheme'),

            "desc" => "",

            "id" => "advancedfimage",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Setup Featured Images', 'pbtheme') . "</h3>

						" . __('Set your default featured image resolution. These settings control the featured image size on fullwidth elements, fullwidth blog and single posts featured area. If you alter these default settings please regenerate your thumbnails.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Featured Image Width', 'pbtheme'),

            "desc" => __('Set fullwidth elements featured image height.', 'pbtheme'),

            "id" => "fimage_width",

            "std" => "960",

            "min" => "640",

            "step" => "1",

            "max" => "1200",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Featured Image Height', 'pbtheme'),

            "desc" => __('Set fullwidth elements featured image height.', 'pbtheme'),

            "id" => "fimage_height",

            "std" => "600",

            "min" => "400",

            "step" => "1",

            "max" => "1200",

            "type" => "sliderui",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Language Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_advanced",

            "icon" => "imscadmin-admin-languages"

        );

        $of_options[] = array("name" => __('Language Settings', 'pbtheme'),

            "desc" => "",

            "id" => "languagesettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Languages Manager', 'pbtheme') . "</h3>

						" . __('Add languages and URLs to the translated version. You can use WPML, qTranslate or other tools to translate your site.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        require_once(ABSPATH . 'wp-admin/includes/admin.php');

        $path = 'transposh-translation-filter-for-wordpress/transposh.php';

        if (is_plugin_active(plugin_basename($path))) {

            $of_options[] = array("name" => __("Enable Transposh", "pbtheme"),

                "desc" => __("We detected the Transposh plugin, would you like to use the widget in place of the languages dropdown?", "pbtheme"),

                "id" => "transposh_enable",

                "std" => 0,

                "type" => "switch",

                "class" => "div_grp_advanced"

            );

        }

        $of_options[] = array("name" => __('Languages', 'pbtheme'),

            "desc" => __('Unlimited sidebars for your pages/posts.', 'pbtheme'),

            "id" => "language",

            "std" => array(

                1 => array(

                    'order' => 1,

                    'flag' => 'france.png',

                    'langurl' => '#'

                )

            ),

            "type" => "language"

        );

        $of_options[] = array("name" => __('Twitter Settings', 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_advanced",

            "icon" => "imscadmin-admin-twitter"

        );

        $of_options[] = array("name" => __('Twitter Settings', 'pbtheme'),

            "desc" => "",

            "id" => "advancedsettings",

            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Twitter Settings', 'pbtheme') . "</h3>

						" . __('Set custom Twitter API keys. Go to', 'divison') . '<a href="http://dev.twitter.com/" target="_blank">' . __('Twitter Developer pages', 'pbtheme') . '</a>' . __('to get your secure keys.', 'pbtheme') . "",

            "icon" => true,

            "type" => "info"

        );

        $of_options[] = array("name" => __('Consumer Key', 'pbtheme'),

            "desc" => __('Consumer key provided by dev.twitter.com', 'pbtheme'),

            "id" => "twitter_ck",

            "std" => "GxayQKDHXgVXRriYSQnrxA",

            "type" => "text",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Consumer Secret', 'pbtheme'),

            "desc" => __('Consumer secret provided by dev.twitter.com', 'pbtheme'),

            "id" => "twitter_cs",

            "std" => "0tzhz6qpGc9S2eheWXDY1UZLJE0OQJ6ZqCnyeArekkw",

            "type" => "text",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Access token', 'pbtheme'),

            "desc" => __('Access token provided by dev.twitter.com', 'pbtheme'),

            "id" => "twitter_at",

            "std" => "966576138-o7EYr6hqQCGC3OhBLY7TdGV5x7U0EboaQayVqT9I",

            "type" => "text",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __('Access token secret', 'pbtheme'),

            "desc" => __('Access token secret provided by dev.twitter.com', 'pbtheme'),

            "id" => "twitter_ats",

            "std" => "rlaYNo2hpc7ndlQwPIM3aVidomdx8LMxEPNzla9RdZvTT",

            "type" => "text",

            "class" => "of-group-small"

        );

        $of_options[] = array("name" => __("Backup Options", 'pbtheme'),

            "type" => "heading",

            "group" => "div_grp_advanced",

            "icon" => "imscadmin-admin-backup"

        );

        $of_options[] = array("name" => __("Backup and Restore Options", 'pbtheme'),

            "id" => "of_backup",

            "std" => "",

            "type" => "backup",

            "desc" => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', 'pbtheme')

        );

        $of_options[] = array("name" => __("Transfer Theme Options Data", 'pbtheme'),

            "id" => "of_transfer",

            "std" => "",

            "type" => "transfer",

            "desc" => __("You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click \"Import Options\"", 'pbtheme')

        );

    }



//End function: of_options()

}//End chack if function exists: of_options()

?>