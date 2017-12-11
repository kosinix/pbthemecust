<?php

// Post Three cateogries widget

class PBTheme_ThreeCat_Widget extends WP_Widget {

        function __construct() {

            $widget_ops = array(
                'classname' => 'widget-pbtheme-catthree',
                'description' => __('Show category posts in tabs', 'pbtheme'));
            parent::__construct('pbtheme_catthree', '+ PBTheme Three Categories', $widget_ops);

        }

        function widget($args, $instance) {

            extract($args, EXTR_SKIP);

            echo $before_widget;

            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

            if (isset($instance['number'])) : $number = $instance['number'];

            else : $number = '3';

            endif;
            

            for ($i = 0; $i <= 2; $i++) {

                if (isset($instance['order' . $i])) : $order[$i] = $instance['order' . $i];

                else : $order[$i] = 'date';

                endif;

                if (isset($instance['category' . $i])) : $category[$i] = $instance['category' . $i];

                else : $category[$i] = '-1';

                endif;

            }

            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }

            for ($i = 0; $i <= 2; $i++) {

                $out = do_shortcode(sprintf('[pbtheme_title link="#" type="h3" align="left" bot_margin="12"]%1$s <i class="fa fa-angle-down"></i>[/pbtheme_title]', ( $category[$i] !== "-1" ? get_cat_name($category[$i]) : __('All Categories', 'pbtheme'))));

                $out .= sprintf('<ul%1$s>', ( $i == 0 ? ' class="div_first_child"' : ''));

                $query_string = array(
                    'post_type' => 'post',
                    'posts_per_page' => $number,
                    'ignore_sticky_posts' => true,
                    'orderby' => $order[$i]
                );

                if ($category !== "-1") {
                    $query_string['cat']=$category[$i];
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

                $out .= '</ul>';

                echo $out;

            }

            echo $after_widget;

        }



        function update($new_instance, $old_instance) {

            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['number'] = strip_tags($new_instance['number']);

            for ($i = 0; $i <= 2; $i++) {
                $instance['order' . $i] = strip_tags($new_instance['order' . $i]);
                $instance['category' . $i] = strip_tags($new_instance['category' . $i]);
            }

            return $instance;

        }



        function form($instance) {

            $instance = wp_parse_args(

                (array) $instance, array(
                'title' => '',
                'number' => '3',
                'order0' => 'date',
                'category0' => '-1',
                'order1' => 'date',
                'category1' => '-1',
                'order2' => 'date',
                'category2' => '-1',
            ));

            $title = strip_tags($instance['title']);
            $number = $instance['number'];

            ?>

            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

            <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to display', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" /></label></p>

        <?php

        for ($i = 0; $i <= 2; $i++) {
            ?>

                <p><?php printf('%1$s %2$s', __('Tab', 'pbtheme'), $i + 1); ?><hr/></p>

            <p><label for="<?php echo $this->get_field_id('order' . $i); ?>"><?php _e('Order', 'pbtheme'); ?> : <select id="<?php echo $this->get_field_id('order' . $i); ?>" name="<?php echo $this->get_field_name('order' . $i); ?>">

                        <option value="date" <?php $selected = ( $instance['order' . $i] === 'date') ? 'selected = "selected"' : '';

            echo $selected; ?>><?php _e('Recent', 'pbtheme'); ?></option>

                        <option value="comment_count" <?php $selected = ( $instance['order' . $i] === 'comment_count') ? 'selected = "selected"' : '';

            echo $selected; ?>><?php _e('Popular', 'pbtheme'); ?></option>

                        <option value="rand" <?php $selected = ( $instance['order' . $i] === 'rand') ? 'selected = "selected"' : '';

            echo $selected; ?>><?php _e('Random', 'pbtheme'); ?></option>

                        <option value="author" <?php $selected = ( $instance['order' . $i] === 'author') ? 'selected = "selected"' : '';

            echo $selected; ?>><?php _e('Author', 'pbtheme'); ?></option>

                        <option value="title" <?php $selected = ( $instance['order' . $i] === 'title') ? 'selected = "selected"' : '';

            echo $selected; ?>><?php _e('Title', 'pbtheme'); ?></option>

                    </select></label></p>

            <p><label for="<?php echo $this->get_field_id('category' . $i); ?>"><?php _e('Category', 'pbtheme'); ?> <?php wp_dropdown_categories('show_option_none=All&show_count=1&orderby=name&echo=1&name=' . $this->get_field_name('category' . $i) . '&id=' . $this->get_field_id('category' . $i) . '&selected=' . $instance['category' . $i] . ''); ?></label></p>
            <?php

        }

    }

}
add_action('widgets_init', create_function('', 'return register_widget("PBTheme_ThreeCat_Widget");'));