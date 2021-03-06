<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */
get_header();
global $pbtheme_data;

// Share bar position (can be overrided via post settings)
$share_bar_position = get_post_meta( get_the_ID(), 'pbtheme_single_share_position', true );
// $share_bar_position Will be empty for compatibility untill user visit post admin screen and save post
$share_bar_position = $share_bar_position === 'inherit' || empty( $share_bar_position ) ? $pbtheme_data['pbtheme_single_share_position'] : $share_bar_position;

$hide_sidebar = get_post_meta(get_the_ID(), 'pbtheme_hide_sidebar', true);
if ($hide_sidebar !== '1') :
    if ($pbtheme_data['sidebar-single'] == 1) {
        switch ($pbtheme_data['sidebar-size']):
            case '3' :
                $blog_class = 'pbuilder_column pbuilder_column-2-3 pbtheme_hidden_flow margin-bottom36';
                $sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-3';
                break;
            case '4' :
                $blog_class = 'pbuilder_column pbuilder_column-3-4 pbtheme_hidden_flow margin-bottom36';
                $sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-4';
                break;
            case '5' :
                $blog_class = 'pbuilder_column pbuilder_column-4-5 pbtheme_hidden_flow margin-bottom36';
                $sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-5';
                break;
        endswitch;
    }
    else {
        $blog_class = 'pbuilder_column pbuilder_column-1-1 margin-bottom36';
    }
else :
    $blog_class = 'pbuilder_column pbuilder_column-1-1 margin-bottom36';
endif;
$pbtheme_page_width = get_post_meta(get_the_ID(), 'pbtheme_page_width', true);
?>
<div id="pbtheme_content" class="pbtheme_container" style="<?php if ($pbtheme_page_width != '') echo ' max-width: ' . $pbtheme_page_width . '; '; ?>">
    <?php if (isset($pbtheme_data['single-widgets-before']) || $pbtheme_data['single-widgets-before'] !== 'none') : ?>
        <div class="anivia_row pbuilder_row">
            <div>
                <?php
                $single_widgets_before = $pbtheme_data['single-widgets-before'];
                for ($i = 1; $i <= $single_widgets_before; $i++) {
                    printf('<div class="pbuilder_column pbuilder_column-1-%1$s">', $single_widgets_before);
                    dynamic_sidebar('single-widgets-before-' . $i);
                    printf('</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $single_widgets_before);
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="anivia_row pbuilder_row">
        <div>
            <?php
            $hide_sidebar = get_post_meta(get_the_ID(), 'pbtheme_hide_sidebar', true);
            if ($hide_sidebar !== '1') :
                ?>
                <?php if ($pbtheme_data['sidebar-single'] == 1 && $pbtheme_data['sidebar-single-position'] == 1) : ?>
                    <div class="<?php echo $sidebar_class; ?>">
                        <?php dynamic_sidebar('sidebar-single'); ?>
                    </div>
                    <?php
                endif;
            endif;
            ?>
            <?php if (have_posts()) : ?>
                <div id="content" class="<?php echo $blog_class; ?>">
                    <?php the_post(); ?>
                    <div class="single_full_news_element fullwidth">
                        <?php
                        $hide_nav = get_post_meta(get_the_ID(), 'pbtheme_hide_navigation', true);
                        $hide_nav = ( $hide_nav == '' ? $pbtheme_data['pbtheme_hide_navigation'] : $hide_nav );

                        if (($hide_nav !== '1') || ( $pbtheme_data['pbtheme_hide_navigation'] !== '1' && $hide_nav !== '1')) :
                            ?>
                            <div class="singlepost-navigation">
                                <?php
                                $next_post = get_next_post();
                                $prev_post = get_previous_post();
                                if (!empty($next_post)):
                                    ?>
                                    <div class="prev-post-link<?php if (empty($prev_post)) echo ' last-article'; ?> a-inherit">
                                        <a href="<?php echo get_permalink($next_post->ID); ?>" class="pbtheme_header_font div_single_prev" title="<?php echo $next_post->post_title; ?>"><?php _e('Previous post', 'pbtheme'); ?></a><a href="<?php echo get_permalink($next_post->ID); ?>" class="pbtheme_header_font div_single_nav" title="<?php echo $next_post->post_title; ?>"><?php echo $next_post->post_title; ?></a>
                                    </div>
                                <?php endif; ?>

                                <?php
                                if (!empty($prev_post)):
                                    ?>
                                    <div class="next-post-link<?php if (empty($next_post)) echo ' first-article'; ?> a-inherit">
                                        <a href="<?php echo get_permalink($prev_post->ID); ?>" class="pbtheme_header_font div_single_nav" title="<?php echo $prev_post->post_title; ?>"><?php echo $prev_post->post_title; ?></a><a href="<?php echo get_permalink($prev_post->ID); ?>" class="pbtheme_header_font div_single_next" title="<?php echo $prev_post->post_title; ?>"><?php _e('Next post', 'pbtheme'); ?></a>
                                    </div>
                                <?php endif; ?>
                                <div class="clearfix"></div>
                            </div>
                        <?php endif; ?>
                        <?php
                        $hide_feat = get_post_meta(get_the_ID(), 'pbtheme_hide_featarea', true);
                        $hide_feat = ( $hide_feat == '' ? $pbtheme_data['pbtheme_hide_featarea'] : $hide_feat );

                        if (($hide_feat !== '1') || ( $pbtheme_data['pbtheme_hide_featarea'] !== '1' && $hide_feat !== '1')) {
                            echo pbtheme_get_featarea('pbtheme-fullblog');
                        }
                        ?>
                        <?php
                        $hide_title = get_post_meta(get_the_ID(), 'pbtheme_hide_title', true);
                        $hide_title = ( $hide_title == '' ? $pbtheme_data['pbtheme_hide_title'] : $hide_title );

                        if (($hide_title !== '1') || ( $pbtheme_data['pbtheme_hide_title'] !== '1' && $hide_title !== '1')) :
                            ?>
                            <h2 class="margin-bottom6 entry-title"><?php the_title(); ?></h2>
                        <?php endif; ?>
                            
                        <?php 
                        /**
                         * Post subtitle
                         * 
                         * @hooked Pbtheme_Post_Subtitle/show_text - 20
                         */
                        do_action( 'pbtheme_post_subtitle_text' );
                        ?>
                            
                            
                        <?php
                        $hide_meta = get_post_meta(get_the_ID(), 'pbtheme_hide_meta', true);
                        $hide_meta = ( $hide_meta == '' ? $pbtheme_data['pbtheme_hide_meta'] : $hide_meta );

                        if (($hide_meta !== '1') || ( $pbtheme_data['pbtheme_hide_meta'] !== '1' && $hide_meta !== '1')) :
                            $timecode = get_the_date() . ' @ ' . get_the_time();
                            $num_comments = get_comments_number();
                            if (comments_open()) {
                                if ($num_comments == 0) {
                                    $comments = __('Leave a comment', 'pbtheme');
                                } elseif ($num_comments > 1) {
                                    $comments = $num_comments . __(' Comments', 'pbtheme');
                                } else {
                                    $comments = __('1 Comment', 'pbtheme');
                                }
                                $write_comments = '<a href="' . get_comments_link() . '">' . $comments . '</a>';
                            } else {
                                $write_comments = __('Comments are off for this post.', 'pbtheme');
                            }
                            echo '<div class="posts_meta margin-bottom18">'
                                    . '<div class="div_avatar_meta inline-block">' . get_avatar(get_the_author_meta('email'), 40) . '</div>'
                                    . '<div class="div_date_meta inline-block post-date date updated">' . $timecode . '</div>'
                                    . '<div class="div_author_meta inline-block a-inherit author">' . __('by', 'anivia') . ' ' . get_the_author_link() . '</div>'
                                    . '<div class="div_category_meta inline-block a-inherit">' . __('in', 'pbtheme') . ' ' . get_the_category_list(', ') . '</div>'
                                    . '<div class="div_comment_meta inline-block a-inherit">' . $write_comments . '</div>'
                                . '</div>';
                        endif;
                        
                        
                        /**
                         * Share social networks - top
                         */
                        if ( $share_bar_position === 'top' || $share_bar_position === 'both' ) {
                            pbtheme_single_social_share();
                        }
                            
                        ?>  
                        <div id="div_pure_single">
                            <?php the_content(); ?>
                        </div>
                        <div class="clearfix"></div>
                        <?php posts_nav_link(); ?>
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="bold margin-bottom20">' . __('View', 'pbtheme') . ': ',
                            'after' => '</div>',
                            'next_or_number' => 'next',
                            'nextpagelink' => __('Next page', 'pbtheme'),
                            'previouspagelink' => __('Previous page', 'pbtheme'),
                            'pagelink' => '%',
                            'echo' => 1
                        ));
                        ?>
                        <?php
                        $hide_tags = get_post_meta(get_the_ID(), 'pbtheme_hide_tags', true);
                        $hide_tags = ( $hide_tags == '' ? $pbtheme_data['pbtheme_hide_tags'] : $hide_tags );

                        if (($hide_tags !== '1') || ( $pbtheme_data['pbtheme_hide_tags'] !== '1' && $hide_tags !== '1')) :
                             the_tags('<p class="single-tags-list">', ' ', '</p>'); 
                        endif; 
                        
                        /**
                         * Share social networks - bottom
                         */
                        if ( $share_bar_position === 'bottom' || $share_bar_position === 'both' ) {
                            pbtheme_single_social_share();
                        }
                        
                        // After post sidebar (widget area)
                        if (isset($pbtheme_data['single-widgets-after']) || $pbtheme_data['single-widgets-after'] !== 'none') : ?>
                            <div class="anivia_row pbuilder_row">
                                <div>
                                    <?php
                                    $single_widgets_after = $pbtheme_data['single-widgets-after'];
                                    for ($i = 1; $i <= $single_widgets_after; $i++) {
                                        printf('<div class="pbuilder_column pbuilder_column-1-%1$s">', $single_widgets_after);
                                        dynamic_sidebar('single-widgets-after-' . $i);
                                        printf('</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $single_widgets_after);
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif;
                    
                        $hide_author = get_post_meta(get_the_ID(), 'pbtheme_hide_author', true);
                        $hide_author = ( $hide_author == '' ? $pbtheme_data['pbtheme_hide_author'] : $hide_author );

                        if (($hide_author !== '1') || ( $pbtheme_data['pbtheme_hide_author'] !== '1' && $hide_author !== '1')) :
                            ?>
                            <div class="blog_post_author_box margin-bottom36">
                                <?php echo get_avatar(get_the_author_meta('email'), 100); ?>
                                <div class="info_wrap">
                                    <h3 class="margin-bottom12"><?php the_author(); ?></h3><!-- name -->
                                    <div><?php the_author_meta('description'); ?></div>
                                </div><!-- info_wrap -->
                                <div class="clearfix"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php comments_template(); ?>
                </div>
                <?php
            else :
                _e('No posts', 'pbtheme');
            endif;
            ?>
            <?php
            $hide_sidebar = get_post_meta(get_the_ID(), 'pbtheme_hide_sidebar', true);
            if ($hide_sidebar !== '1') :
                ?>
                <?php if ($pbtheme_data['sidebar-single'] == 1 && $pbtheme_data['sidebar-single-position'] == 0) : ?>
                    <div class="<?php echo $sidebar_class; ?>">
                        <?php dynamic_sidebar('sidebar-single'); ?>
                    </div>
                    <?php
                endif;
            endif;
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>