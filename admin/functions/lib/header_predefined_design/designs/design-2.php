<?php

/**
 * Return options to override to achieve desired header design
 */
return array(

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
            'menu' => 'menu',
            'network-icons' => 'network-icons',
            'tagline' => 'tagline',
            'tagline-alt' => 'tagline-alt',
            'woo-login-link' => 'woo-login-link',
            'woo-cart' => 'woo-cart',
        ),
        'enabled' =>
        array(
            'placebo' => 'placebo',
        ),
    ),
    'header-top-right' =>
    array(
        'disabled' =>
        array(
            'placebo' => 'placebo',
            'login-link' => 'login-link',
            'language-bar' => 'language-bar',
            'menu' => 'menu',
            'network-icons' => 'network-icons',
            'tagline' => 'tagline',
            'tagline-alt' => 'tagline-alt',
            'woo-login-link' => 'woo-login-link',
            'woo-cart' => 'woo-cart',
        ),
        'enabled' =>
        array(
            'placebo' => 'placebo',
        ),
    ),
    'header_menu' => 'none',
    'header_networks' => 'none',
    'header_search_style' => '0',
    'header_search_type' => '0',
    'header_tagline' => 'PBTheme',
    'header_tagline_alt' => 'WordPress',

    // Header style settings
    'header_styles_enabled' => '1',
    'header_topbar_bgd_color_pattern' => 'none',
    'theme_color_top_header' => '#d3d3d3',
    'header_topbar_text_color' => '',
    'header_topbar_link_color' => '',
    'header_topbar_link_hover_color' => '',
    'header_topbar_border_color' => '',
    'header_topbar_font_size' => '0',
    'header_bgd_color_pattern' => 'none',
    'theme_color_header' => '#d6d6d6',
    'header_link_color' => '',
    'header_link_hover_color' => '#914199',
    'header_border_color' => '',
    'header_shadow' => '0',
    'header_menu_font_size' => '13',
    'header_menu_distance_links' => '0',
    'header_menu_height' => 'medium',
    'header_menu_hover_effect' => 'baseline',
    'header_menu_hover_effect_color' => '#914199',
    'header_menu_subitem_bgd_color' => '',
    'header_menu_subitem_color' => '',
    'header_menu_subitem_hover_color' => '',
    'header_topbar_bgd_opacity' => '10',
    'header_bgd_opacity' => '10',
    'header_logo_overflow' => '0',
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
    'sellocity_btn_enabled' => '1',
    'sellocity_btn_text' => 'LEARN MORE',
    'sellocity_btn_hover_btn_type' => 'hover_winona',
    'sellocity_btn_css_class' => 'sellocity-custom-btn-small',
    'sellocity_btn_text_size' => 'btn_smll',
    'sellocity_btn_font_size_p' => '',
    'sellocity_btn_letter_spacing' => '1',
    'sellocity_btn_type' => 'btn_crcl',
    'sellocity_btn_link_url' => '#',
    'sellocity_btn_icon_type' => 'icon_no',
    'sellocity_btn_icon_class' => 'none',
    'sellocity_btn_back_color' => '',
    'sellocity_btn_hover_color' => '#914199',
    'sellocity_btn_text_color' => '#222222',
    'sellocity_btn_text_color_hover' => '#ffffff',
    'sellocity_btn_border_width' => '1',
    'sellocity_btn_border_color' => '#914199',
    'sellocity_btn_border_color_hover' => '',
    
    // Color Settings
    'theme_color' => '#914199',
    'theme_color_dark' => '#111111',
    'theme_color_light' => '#ffffff',
    'theme_color_palee' => '#ffffff',
    'theme_color_textt' => '#444444',
    'theme_color_footer_textt' => '#ffffff',
    'theme_color_footer_bg' => '#1b1b1b',
    
    // Contact settings
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