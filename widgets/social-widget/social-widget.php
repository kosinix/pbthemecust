<?php

/**
 * Social widget
 */

class PBTheme_Social_Widget extends WP_Widget {

    function __construct() {

        $widget_ops = array(
            'classname' => 'widget-pbtheme-social square_social_feed margin-bottom20',
            'description' => __('Social subscribers', 'pbtheme')
        );

        parent::__construct('pbtheme_social', '+ PBTheme Social', $widget_ops);
    }



    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $facebook_id = empty($instance['facebook']) ? '' : apply_filters('widget_facebook', $instance['facebook']);
        $twitter_id = empty($instance['twitter']) ? '' : apply_filters('widget_twitter', $instance['twitter']);
        $list_id = empty($instance['list']) ? '' : apply_filters('widget_list', $instance['list']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        echo '<div>';

        if ($facebook_id !== '') {
            if (!get_transient('pbtheme_facebook_users')) {
                $facebook_curl_url = sprintf('http://graph.facebook.com/%1$s', $facebook_id);
                $c = curl_init($facebook_curl_url);

                curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

                $result = curl_exec($c);

                curl_close($c);

                $facebookData = json_decode($result);

                $transient = sprintf('<div class="single_block_wrapper"><div class="single_block"><a href="%2$s" class="block fb margin-bottom6"></a><div class="border-box pbtheme_header_font div_dtext">%1$s<div>%3$s</div></div></div></div>', $facebookData->likes, $facebookData->link, __('FANS', 'pbtheme'));

                set_transient('pbtheme_facebook_users', $transient, 1800);

                echo $transient;

            } else {

                echo get_transient('pbtheme_facebook_users');

            }

        }

        if ($twitter_id !== '') {

            if (!get_transient('pbtheme_twitter_users')) {

                global $pbtheme_data;

                $consumerkey = $pbtheme_data['twitter_ck'];
                $consumersecret = $pbtheme_data['twitter_cs'];
                $accesstoken = $pbtheme_data['twitter_at'];
                $accesstokensecret = $pbtheme_data['twitter_ats'];
                $twitter_url = 'http://www.twitter.com/' . $twitter_id;
                $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
                $data_connection = $connection->get("https://api.twitter.com/1.1/users/show.json?screen_name=" . $twitter_id . "&include_entities=true");
                $twitterData = json_decode(json_encode($data_connection));

                $transient = sprintf('<div class="single_block_wrapper"><div class="single_block"><a href="%2$s" class="block tw margin-bottom6"></a><div class="border-box pbtheme_header_font div_dtext">%1$s<div>%3$s</div></div></div></div>', $twitterData->followers_count, $twitter_url, __('FOLLOWERS', 'pbtheme'));

                set_transient('pbtheme_twitter_users', $transient, 1800);

                echo $transient;

            } else {

                echo get_transient('pbtheme_twitter_users');

            }



            if (in_array('sendpress/sendpress.php', apply_filters('active_plugins', get_option('active_plugins'))) && $list_id !== '') {

                global $wpdb;

                $table = SendPress_Data::list_subcribers_table();

                $query = "SELECT COUNT(*) FROM " . SendPress_Data::subscriber_table() . " as t1," . SendPress_Data::list_subcribers_table() . " as t2," . SendPress_Data::subscriber_status_table() . " as t3";

                $query .= " WHERE (t1.subscriberID = t2.subscriberID) AND (t2.status = t3.statusid ) AND(t2.status = %d) AND (t2.listID =  %d)";

                $count = $wpdb->prepare($query, 2, $list_id);

                $type = 'get_var';

                $result = $wpdb->$type($count);

                printf('<div class="single_block_wrapper"><div class="single_block last"><a href="%2$s" class="block rss margin-bottom6"></a><div class="border-box pbtheme_header_font div_dtext">%1$s<div>%3$s</div></div></div></div>', $result, home_url(), __('SUBSCRIBERS', 'pbtheme'));

            }

        }

        echo '<div class="clearfix"></div>';
        echo '</div>';

        echo $after_widget;

    }



    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook'] = strip_tags($new_instance['facebook']);
        $instance['twitter'] = strip_tags($new_instance['twitter']);
        $instance['list'] = strip_tags($new_instance['list']);
        return $instance;

    }



    function form($instance) {

        $instance = wp_parse_args(

            (array) $instance, array(
            'title' => '',
            'facebook' => '',
            'twitter' => '',
            'list' => ''

        ));

        $title = strip_tags($instance['title']);
        $facebook = strip_tags($instance['facebook']);
        $twitter = strip_tags($instance['twitter']);
        $list = strip_tags($instance['list']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'pbtheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook Page ID', 'pbtheme'); ?> : </label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter ID', 'pbtheme'); ?> :</label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('SendPress list ID', 'pbtheme'); ?> :</label>
            <input class="widefat" id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>" type="text" value="<?php echo esc_attr($list); ?>" /></p>
    <?php
    }

}
add_action('widgets_init', create_function('', 'return register_widget("PBTheme_Social_Widget");'));