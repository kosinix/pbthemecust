<?php

/**
 * Return options to override to achieve desired header design
 */
return array(
    // Logo 
    'logo' => get_template_directory_uri() . '/admin/assets/images/header_design/predefined_header/logo_overflow-dark-1.png',
    
    // Header Settings
    'disable_sticky_header' => '1',
    'disable-top-header' => '0',
    'header_layout' => 'news-central',
    'header-widgets-before' => 'none',
    'header-top-left' =>
    array(
        'disabled' =>
        array(
            'placebo' => 'placebo',
            'login-link' => 'login-link',
            'language-bar' => 'language-bar',
            'tagline-alt' => 'tagline-alt',
            'menu' => 'menu',
            'network-icons' => 'network-icons',
            'woo-login-link' => 'woo-login-link',
            'woo-cart' => 'woo-cart',
        ),
        'enabled' =>
        array(
            'placebo' => 'placebo',
            'tagline' => 'tagline',
        ),
    ),
    'header-top-right' =>
    array(
        'disabled' =>
        array(
            'placebo' => 'placebo',
            'login-link' => 'login-link',
            'language-bar' => 'language-bar',
            'tagline-alt' => 'tagline-alt',
            'tagline' => 'tagline',
            'network-icons' => 'network-icons',
            'woo-login-link' => 'woo-login-link',
            'woo-cart' => 'woo-cart',
        ),
        'enabled' =>
        array(
            'placebo' => 'placebo',
            'menu' => 'menu',
        ),
    ),
    'header_menu' => 'login-menu',
    'header_networks' => 'none',
    'header_search_style' => '0',
    'header_search_type' => '0',
    'header_tagline' => '<a href="#"><< SEE MORE INSTRUCTIONS</a>',
    'header_tagline_alt' => '',
    
    // Header style settings
    'header_styles_enabled' => '1',
    'header_topbar_bgd_color_pattern' => 'color',
    'theme_color_top_header' => '#020202',
    'header_topbar_text_color' => '#bfbfbf',
    'header_topbar_link_color' => '#bfbfbf',
    'header_topbar_link_hover_color' => '#faaa19',
    'header_topbar_border_color' => '',
    'header_topbar_font_size' => '0',
    'header_bgd_color_pattern' => 'color',
    'theme_color_header' => '#000000',
    'header_link_color' => '#ffffff',
    'header_link_hover_color' => '',
    'header_border_color' => '#000000',
    'header_shadow' => '0',
    'header_menu_font_size' => '0',
    'header_menu_distance_links' => '0',
    'header_menu_height' => 'full',
    'header_menu_hover_effect' => 'none',
    'header_menu_hover_effect_color' => '#d3d3d3',
    'header_menu_subitem_bgd_color' => '',
    'header_menu_subitem_color' => '',
    'header_menu_subitem_hover_color' => '',
    'header_topbar_bgd_opacity' => '4',
    'header_bgd_opacity' => '1',
    'header_logo_overflow' => '1',
    'header_topbar_border' => '1',
    'header_border' => '1',
    
    // Header search styles
    'header_search_color' => '',
    'header_search_icon_color' => '',
    'header_search_def_text_color' => '',
    'header_search_active_text_color' => '',
    
    // Header logo styles
    'header_logo_width' => '0',
    
    // Header responsive menu
    'header_resp_menu_icon_color' => '',
    'header_resp_menu_icon_hover_color' => '',
    'header_resp_menu_line_color' => '',
    
    // Header Button Settings
    'sellocity_btn_enabled' => '0',
    'sellocity_btn_text' => 'Button',
    'sellocity_btn_hover_btn_type' => 'hover_none',
    'sellocity_btn_css_class' => 'sellocity-custom-btn-medium',
    'sellocity_btn_text_size' => 'btn_med',
    'sellocity_btn_font_size_p' => '',
    'sellocity_btn_letter_spacing' => '0',
    'sellocity_btn_type' => 'btn_crcl',
    'sellocity_btn_link_url' => '#',
    'sellocity_btn_icon_type' => 'icon_no',
    'sellocity_btn_icon_class' => 'none',
    'sellocity_btn_back_color' => '#34495e',
    'sellocity_btn_hover_color' => '#182a38',
    'sellocity_btn_text_color' => '#ffffff',
    'sellocity_btn_text_color_hover' => '#ffffff',
    'sellocity_btn_border_width' => '1',
    'sellocity_btn_border_color' => '',
    'sellocity_btn_border_color_hover' => '',
    
    // Color Settings
    'theme_color' => '#faaa19',
    'theme_color_dark' => '#111111',
    'theme_color_light' => '#ffffff',
    'theme_color_palee' => '#cccccc',
    'theme_color_textt' => '#444444',
    'theme_color_footer_textt' => '#ffffff',
    'theme_color_footer_bg' => '#1b1b1b',
    
    // Contacts settings
    'contact' =>
    array(
        1 =>
        array(
            'order' => '1',
            'name' => 'Your first Contact!',
            'url' => get_template_directory_uri() . '/images/logo.png',
            'email' => 'google@gmail.com',
            'job' => 'designer',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'contact' =>
            array(
                1 =>
                array(
                    'socialnetworksurl' => '#',
                    'socialnetworks' => 'white_facebook.png',
                ),
            ),
        ),
    ),
);
