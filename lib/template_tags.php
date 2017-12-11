<?php

/**
 * Custom template tags for this theme.
 * 
 * @package PBTheme
 * 
 * @since 3.2.4
 */

if ( ! function_exists( 'pbtheme_single_social_share' ) ) :
/**
 * Echo share bar on single post page
 * 
 * @global array $pbtheme_data Theme options values
 */  
function pbtheme_single_social_share() {

    global $pbtheme_data;
    $post_id = get_the_ID();
    
    /**
     * $hide_share - Metabox: Return 1 when checkbox check to hide share or empty string
     * $theme_hide_share - Theme option: Return 1 when switch on to hide share or 0
     */
    
    
    
    // If user disable share in post settings
    $hide_share = get_post_meta( $post_id, 'pbtheme_hide_share', true );
    if ( $hide_share == 1 ) {
        return '';
    }
    
    // If user disabled share in theme options
    $theme_hide_share = $pbtheme_data['pbtheme_hide_share'];
    if ( $theme_hide_share == 1 ) {
        return '';
    }
    
    /**
     * Options from theme
     */
    $theme_hide_share_fb = $pbtheme_data['pbtheme_hide_post_fb_share'];
    $theme_hide_share_go = $pbtheme_data['pbtheme_hide_post_go_share'];
    $theme_hide_share_tw = $pbtheme_data['pbtheme_hide_post_tw_share'];
    $theme_hide_share_li = $pbtheme_data['pbtheme_hide_post_li_share'];
    $theme_hide_share_pi = $pbtheme_data['pbtheme_hide_post_pi_share'];
    
    
    /**
     * Options from metaboxes
     */
    $hide_share_fb = get_post_meta( $post_id, 'pbtheme_hide_share_fb', true );
    $hide_share_go = get_post_meta( $post_id, 'pbtheme_hide_share_go', true );
    $hide_share_tw = get_post_meta( $post_id, 'pbtheme_hide_share_tw', true );
    $hide_share_li = get_post_meta( $post_id, 'pbtheme_hide_share_li', true );
    $hide_share_pi = get_post_meta( $post_id, 'pbtheme_hide_share_pi', true );
    
    // If all buttons in post option disabled
    if ( 
           $hide_share_fb == 1 
        && $hide_share_go == 1
        && $hide_share_tw == 1
        && $hide_share_li == 1
        && $hide_share_pi == 1
    ) { return ''; }
    
    // If all buttons in theme options disabled
    if ( 
           $theme_hide_share_fb == 1 
        && $theme_hide_share_go == 1
        && $theme_hide_share_tw == 1
        && $theme_hide_share_li == 1
        && $theme_hide_share_pi == 1
    ) { return ''; }

    ?>
    <div class="post_comment_bar_wrapper pbtheme-single-share">
        <?php echo do_shortcode('[pbtheme_title align=\'left\']' . __('Share this article', 'pbtheme') . '[/pbtheme_title]'); ?>
        <nav class="social_bar a-inherit">
            <ul class="list_style">
                <?php
                $title = str_replace(' ', '%20', get_the_title());
                ?>
                <?php $large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large'); ?>
                
                <?php 
                // Facebook share
                if ( $hide_share_fb != 1 ) {
                    if ( $theme_hide_share_fb != 1 ) {
                ?>
                <li class="float_left"><a class="blog_socials pbtheme_google text-color-pale" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php echo $title; ?>" data-href="<?php the_permalink(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                                                return false;" target="_blank"><i class="fa fa-facebook"></i></a></li>
                <?php 
                    } 
                } 
                ?>
                
                <?php 
                // Google+ share
                if ( $hide_share_go != 1 ) {
                    if ( $theme_hide_share_go != 1 ) {
                ?>
                <li class="float_left"><a class="blog_socials pbtheme_facebook text-color-pale" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="60" data-show-faces="false" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                <?php 
                    } 
                } 
                ?>
                
                <?php 
                // Twitter share
                if ( $hide_share_tw != 1 ) {
                    if ( $theme_hide_share_tw != 1 ) {
                ?>
                <li class="float_left"><a class="blog_socials pbtheme_twitter text-color-pale" href="http://twitter.com/home/?status=<?php echo $title; ?>%20<?php the_permalink(); ?>" data-count-layout="horizontal" target="_blank"><i class="fa fa-twitter"></i></a></li>
                <?php 
                    } 
                } 
                ?>
                
                <?php 
                // LinkedIn share
                if ( $hide_share_li != 1 ) {
                    if ( $theme_hide_share_li != 1 ) {
                ?>
                <li class="float_left"><a class="blog_socials pbtheme_linked text-color-pale" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php echo $title; ?>&amp;source=<?php echo home_url(); ?>"><i class="fa fa-linkedin"></i></a></li>
                <?php 
                    } 
                } 
                ?>
                
                <?php 
                // LinkedIn share
                if ( $hide_share_pi != 1 ) {
                    if ( $theme_hide_share_pi != 1 ) {
                ?>
                <li class="float_left"><a class="blog_socials pbtheme_pinterest text-color-pale" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo $large_image_url[0]; ?>&amp;description=<?php echo $title; ?>" data-count-layout="horizontal"><i class="fa fa-pinterest"></i></a></li>
                <?php 
                    } 
                } 
                ?>
                
            </ul>
        </nav>
    </div>
    <?php

}
    
endif;