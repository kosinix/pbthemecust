<?php

/**
 * Posts / categories widget
 */

class PBTheme_Categories_Widget extends WP_Widget {

    function __construct() {

        $widget_ops = array(
            'classname' => 'widget-pbtheme-cat',
            'description' => __('Show category posts', 'pbtheme'));
        parent::__construct('pbtheme_category', '+ PBTheme Category Posts', $widget_ops);

    }



    function widget($args, $instance) {

        extract($args, EXTR_SKIP);

        echo $before_widget;

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

        if (isset($instance['order'])) : $order = $instance['order'];

        else : $order = 'date';

        endif;

        if (isset($instance['category'])) : $category = $instance['category'];

        else : $category = '-1';

        endif;

        if (isset($instance['number'])) : $number = $instance['number'];

        else : $number = '5';

        endif;

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        echo '<ul>';

        $out = '';

        $query_string = array(
            'post_type' => 'post',
            'posts_per_page' => $number,
            'ignore_sticky_posts' => true,
            'orderby' => $order

        );

        if ($category !== "-1") {
            $query_string = $query_string + array(
                'cat' => $category
            );
        }

        $pbtheme_posts = new WP_Query($query_string);

        if ($pbtheme_posts->have_posts()) :

            while ($pbtheme_posts->have_posts()): $pbtheme_posts->the_post();

                $out .= '<li>';

                if (has_post_thumbnail()) {
                    $out .= '<a class="float_left margin-right12 smaller_image pbtheme_hover" href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'pbtheme-square') . '</a>';
                }

                $timecode = get_the_date();
                $out .= '<div class="posts_meta margin-bottom6 pbtheme_header_font overflow_ellipsis div_dtext"><div class="category_meta inline-block a-inherit">' . get_the_category_list(', ') . '</div><div class="date_meta inline-block">' . $timecode . '</div></div>';
                $out .= '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
                $out .= '<div class="clearfix"></div>';

                $out .= '</li>';

            endwhile;

        endif;

        wp_reset_query();

        echo $out;

        echo '</ul>';

        echo $after_widget;

    }



    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['order'] = strip_tags($new_instance['order']);
        $instance['category'] = strip_tags($new_instance['category']);
        $instance['number'] = strip_tags($new_instance['number']);

        return $instance;

    }



    function form($instance) {

        $instance = wp_parse_args(

            (array) $instance, array(
            'title' => '',
            'order' => 'date',
            'category' => '34',
            'number' => '5'

        ));

        $title = strip_tags($instance['title']);
        $number = strip_tags($instance['number']);

        ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

        <p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'pbtheme'); ?> : <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">

                    <option value="date" <?php $selected = ( $instance['order'] === 'date') ? 'selected = "selected"' : '';

    echo $selected; ?>><?php _e('Recent', 'pbtheme'); ?></option>

                    <option value="comment_count" <?php $selected = ( $instance['order'] === 'comment_count') ? 'selected = "selected"' : '';

    echo $selected; ?>><?php _e('Popular', 'pbtheme'); ?></option>

                    <option value="rand" <?php $selected = ( $instance['order'] === 'rand') ? 'selected = "selected"' : '';

    echo $selected; ?>><?php _e('Random', 'pbtheme'); ?></option>

                    <option value="author" <?php $selected = ( $instance['order'] === 'author') ? 'selected = "selected"' : '';

    echo $selected; ?>><?php _e('Author', 'pbtheme'); ?></option>

                    <option value="title" <?php $selected = ( $instance['order'] === 'title') ? 'selected = "selected"' : '';

    echo $selected; ?>><?php _e('Title', 'pbtheme'); ?></option>

                </select></label></p>

        <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category', 'pbtheme');

    wp_dropdown_categories('show_option_none=All&show_count=1&orderby=name&echo=1&name=' . $this->get_field_name('category') . '&id=' . $this->get_field_id('category') . '&selected=' . $instance['category'] . ''); ?></label></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to display', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" /></label></p>
        <?php

    }

}
add_action('widgets_init', create_function('', 'return register_widget("PBTheme_Categories_Widget");'));