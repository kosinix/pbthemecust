<?php

/**
 * Twitter widget
 */

class PBTheme_Twitter_Widget extends WP_Widget {

        function __construct() {

            $widget_ops = array(
                'classname' => 'widget-pbtheme-twitter twitter_module',
                'description' => __('Show your twitter feeds', 'pbtheme')
            );

            parent::__construct('pbtheme_twitter', '+ PBTheme Twitter', $widget_ops);

        }

        function widget($args, $instance) {

            extract($args, EXTR_SKIP);
            
            echo $before_widget;
            
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $user = empty($instance['user']) ? '' : apply_filters('widget_user', $instance['user']);
            $count = empty($instance['count']) ? '' : apply_filters('widget_count', $instance['count']);

            if (!empty($title)) {

                echo $before_title . $title . $after_title;

            }

            echo pbtheme_twitter_feed($user, $count);
            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['user'] = strip_tags($new_instance['user']);
            $instance['count'] = strip_tags($new_instance['count']);
            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args(
                (array) $instance, array(
                'title' => '',
                'user' => '',
                'count' => 5
            ));

            $title = strip_tags($instance['title']);
            $user = strip_tags($instance['user']);
            $count = strip_tags($instance['count']);

            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
            <p><label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($user); ?>" /></p>
            <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" /></p>
        <?php
        }

    }
    add_action('widgets_init', create_function('', 'return register_widget("PBTheme_Twitter_Widget");'));