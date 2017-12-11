<?php

/**
 * Blog posts widget class
 */
class PBTheme_Blog_Posts_Slider_Widget {
    
    /**
     * Root url to this folder without backslash
     * @var string
     */
    private $root_url;
    
    /**
     * Unique widget id used for custom inline css
     * @var string
     */
    private $widget_id;
    
    /**
     * Number of slides
     * @var int
     */
    private $slides_count = 0;
    private $order;
    private $order_by;
    private $posts_per_page;
    private $category;
    
    public function __construct() {
        $this->root_url = get_template_directory_uri() . '/widgets/blog_posts_slider';
        add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
    }
    
    /**
     * Render widget frontend view
     * 
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance The settings for the particular instance of the widget.
     * @param array $form_fields Widget admin form fields configuration array
     * @param string $this->id Widget generated unique id by instance number. 
     *                        Can be used to target this widget instance only
     */
    public function render_view( $args, $instance, $form_fields ) {
        
        $widget_title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        
        if ( ! empty( $widget_title ) ) {
            echo $args['before_title'] . esc_html( $widget_title ) . $args['after_title'];
        }
        
        /**
         * Define vars
         */
        $this->order = isset( $instance['order'] ) && $instance['order'] === 'asc' ? 'ASC' : $form_fields['order']['default'];
        $this->order_by = isset( $instance['order_by'] ) && !empty( $instance['order_by'] ) ? $instance['order_by'] : $form_fields['order_by']['default'];
        $this->posts_per_page = isset( $instance['posts_per_page'] ) && !empty( $instance['posts_per_page'] ) && intval( $instance['posts_per_page'] ) !== 0 ? $instance['posts_per_page'] : '-1';
        $this->category = isset( $instance['category'] ) && $instance['category'] === 'all' ? '' : $instance['category'];
        $align_class = isset( $instance['alignment'] ) && $instance['alignment'] === 'left' ? 'wpbtheme_bps--left' : 'wpbtheme_bps--' . $instance['alignment'];
        
        // Unique widget id used for custom inline css
        $this->widget_id = uniqid( 'wpbtheme_bps-' );
        
        // Build wrapper classes
        $classes = array();
        $classes[] = $align_class;
                
        /**
         * Inline custom colors css
         */
        echo $this->inline_css( $instance );
        
        /**
         * Prepare slides and setup some data before html output
         */
        $slides_html = $this->slides();
                
        /**
         * Html output start
         */

        // Start wrapper
        echo '<div id="' . esc_attr( $this->widget_id ) . '" class="wpbtheme_bps ' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '" data-count="' . esc_js( $this->slides_count ) . '">';
        
        echo ' <div class="swiper-container">
            <div class="swiper-wrapper">
                
                ' . $slides_html . '

            </div>';

        // Add next and prev arrows
        if ( $this->slides_count > 1 ) {
            echo '<!-- Add Arrows -->
                <div class="swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
                <div class="swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>';
        }
        
        echo '</div>';

        // End wrapper
        echo '</div>';

        echo $args['after_widget'];
        
        /**
         * Add scripts
         */
        $this->add_scripts();
        
    }
    
    /**
     * Return slides html
     * @return string
     */
    private function slides() {
        
        $slides = '';
        
        // WP_Query arguments
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => intval( $this->posts_per_page ),
            'ignore_sticky_posts' => true,
            'order' => sanitize_text_field( $this->order ),
            'orderby' => sanitize_text_field( $this->order_by ),
        );
        
        if ( !empty( $this->category ) ) {
            $args['cat'] = $this->category;
        }

        // The Query
        $query = new WP_Query( $args );
        
        if ( is_wp_error( $query ) || ! $query->have_posts() ) {
            return esc_html__( 'No posts found', 'pbtheme' );
        }
        
        // Set number of slides var
        $this->slides_count = $query->post_count;

        /**
         * Build slides
         */
        while ( $query->have_posts() ) : $query->the_post();
        
            $img_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : IMAGES_FOLDER . 'placeholder/placeholder.jpg';
            
            $category = get_the_category();
            $category = isset( $category[0] ) 
                    ? 
                    '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '" '
                    . 'title="' . esc_url( get_category_link( $category[0]->name ) ) . '">'
                    . '' . strip_tags( $category[0]->name ) . ''
                    . '</a>'
                    :
                    '';

            $slides .= '<div class="swiper-slide">
                            <div class="wpbtheme_bps__post">
                            
                                <div class="wpbtheme_bps__post-cat">' . $category . '</div>
                                <div class="wpbtheme_bps__post-img" style="background-image: url(\'' . esc_url( $img_url ) . '\')"></div>

                                <div class="wpbtheme_bps__post-content">
                                
                                    <a href="' . esc_url( get_the_permalink() ) . '" class="wpbtheme_bps__post-title">' . $this->limit_chars( strip_tags( get_the_title() ), 62 ) . '</a>
                                    <div class="wpbtheme_bps__post-text">' . $this->limit_chars( strip_tags( get_the_excerpt() ), 80 ) . '</div>

                                    <div class="wpbtheme_bps__post-bottom">
                                        <span class="wpbtheme_bps__post-comments"><i class="fa fa-comment-o" aria-hidden="true"></i>' . strip_tags( sprintf( _nx( '1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'pbtheme' ), number_format_i18n( get_comments_number() ) ) ) . '</span>
                                        <span class="wpbtheme_bps__post-date"><i class="fa fa-calendar-o" aria-hidden="true"></i>' . strip_tags( sprintf( _x( '%s ago', '%s = human-readable time difference', 'pbtheme' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) ) . '</span>  
                                    </div>
                            
                                </div><!-- .wpbtheme_bps__post-content -->
                            
                            </div><!-- .wpbtheme_bps__post -->

                        </div>';
        
        endwhile;
        wp_reset_postdata();
        
        return $slides;
        
        
    }
    
    /**
     * Limit words in string
     */
    private function limit_words( $text, $limit ) {
        if ( str_word_count( $text, 0 ) > $limit ) {
            $words = str_word_count( $text, 2 );
            $pos = array_keys( $words );
            $text = substr( $text, 0, $pos[ $limit ] ) . '...';
        }
        return $text;
    }
    
    /**
     * Limit characters in string
     */
    private function limit_chars( $text, $limit ) {

        if ( strlen( $text ) > intval( $limit ) ) {
            return substr( $text, 0, $limit ) . '...';
        }
        
        return $text;

    }

    /**
     * Return inline css to include for widget custom colors
     * @param array $instance
     * @return string
     */
    private function inline_css( $instance ) {
        
        $css = '';
        $widget_id = '#' . $this->widget_id;
        
        // Custom bgd color
        if ( isset( $instance['bgd_color'] ) && !empty( $instance['bgd_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post-content{background-color:' . esc_attr( $instance['bgd_color']  ) . ';}';
        }
        
        // Custom border color
        if ( isset( $instance['border_color'] ) && !empty( $instance['border_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post,'
                    . $widget_id . ' .wpbtheme_bps__post-bottom '
                    . '{border-color:' . esc_attr( $instance['border_color']  ) . ';}';
        }
        
        // Custom cagegory background color
        if ( isset( $instance['category_bgd_color'] ) && !empty( $instance['category_bgd_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post-cat a {background-color:' . esc_attr( $instance['category_bgd_color']  ) . ';}';
        }
        
        // Category text color
        if ( isset( $instance['category_text_color'] ) && !empty( $instance['category_text_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post-cat a {color:' . esc_attr( $instance['category_text_color']  ) . ';}';
        }
        
        // Title color
        if ( isset( $instance['title_color'] ) && !empty( $instance['title_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post-title {color:' . esc_attr( $instance['title_color']  ) . ';}';
        }
        
        // Text color
        if ( isset( $instance['text_color'] ) && !empty( $instance['text_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post-text {color:' . esc_attr( $instance['text_color']  ) . ';}';
        }
        
        // Comments and date color
        if ( isset( $instance['comments_date_color'] ) && !empty( $instance['comments_date_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post-comments,'
                    . $widget_id . ' .wpbtheme_bps__post-date '
                    . ' {color:' . esc_attr( $instance['comments_date_color']  ) . ';}';
        }
        
        // Icon color
        if ( isset( $instance['icon_color'] ) && !empty( $instance['icon_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_bps__post-comments i,'
                    . $widget_id . ' .wpbtheme_bps__post-date i '
                    . ' {color:' . esc_attr( $instance['icon_color']  ) . ';}';
        }
        
        // Navigation bgd color
        if ( isset( $instance['navigation_bgd_color'] ) && !empty( $instance['navigation_bgd_color'] ) ) {
            $css .= $widget_id . ' .swiper-button-prev,'
                    . $widget_id . ' .swiper-button-next '
                    . ' {background-color:' . esc_attr( $instance['navigation_bgd_color']  ) . ';}';
        }
        
        // Navigation icon color
        if ( isset( $instance['navigation_icon_color'] ) && !empty( $instance['navigation_icon_color'] ) ) {
            $css .= $widget_id . ' .swiper-button-prev i,'
                    . $widget_id . ' .swiper-button-next i '
                    . ' {color:' . esc_attr( $instance['navigation_icon_color']  ) . ';}';
        }
        
        if ( ! empty( $css ) ) {
            return '<style type="text/css">' . $css . '</style>';
        }
        
        return $css;
        
    }
    
    /**
     * Add script for widget frontend
     */
    public function add_scripts() {
        wp_enqueue_script( 'pbtheme-idangerous', get_template_directory_uri() . '/js/idangerous.swiper-2.4.2.min.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'wpbtheme-blog-posts-slider', $this->root_url . '/assets/js/main.js', array('pbtheme-idangerous'), false, true );
    }
    
    /**
     * Add styles for widget frontend
     */
    public function add_styles() {
        wp_enqueue_style( 'pbtheme-idangerous', get_template_directory_uri() . '/css/idangerous.swiper.css' );
    }
    
    /**
     * Return select options for select category in admin form
     * @return array
     */
    public function get_select_cat_options() {
        
        $categories = get_categories( array(
            'hide_empty' => false,
            'fields' => 'id=>name',
        ) );
        
        
        $categories = is_array( $categories ) && !empty( $categories ) 
                ? array( 'all' => esc_html__( 'All', 'pbtheme' ) ) + $categories
                : array( 'all' => esc_html__( 'All', 'pbtheme' ) );

        return $categories;
        
    }
}

/**
 * Map and init blog posts widget widget
 */
$pbtheme_bps_widget_widget_init = new PBTheme_Blog_Posts_Slider_Widget();
$pbtheme_bps_widget_widget = array(
    
    // Core configuration
    'base_id' => 'pbtheme_ablog_posts_slider_widget',
    'name' => esc_html__('+ PBTheme Blog posts slider', 'pbtheme'),
    'callback' => array( $pbtheme_bps_widget_widget_init, 'render_view' ),

    'widget_ops' => array(
        'classname' => 'wpbtheme_blog_posts_slider',
        'description' => esc_html__( 'Display posts slider from selected category.', 'pbtheme' ),
        'customize_selective_refresh' => false,
    ),
    
    'form_fields' => array(
        
        'widget_title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Widget title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' ),
        ),
        'category' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Category', 'pbtheme' ),
            'options' => $pbtheme_bps_widget_widget_init->get_select_cat_options(),
            'default' => 'all'
        ),
        'posts_per_page' => array(
            'type' => 'text',
            'label' => esc_html__( 'Number of posts (leave empty to display all):', 'pbtheme' ),
            'placeholder' => esc_html__( 'Use only numbers please', 'pbtheme' ),
        ),
        'order' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Posts order', 'pbtheme' ),
            'options' => array(
                'asc' => esc_html__( 'Ascending', 'pbtheme' ),
                'desc' => esc_html__( 'Descending', 'pbtheme' ),
            ),
            'default' => 'desc'
        ),
        'order_by' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Posts order by', 'pbtheme' ),
            'options' => array(
                'date' => esc_html__( 'Date', 'pbtheme' ),
                'title' => esc_html__( 'Title', 'pbtheme' ),
                'rand' => esc_html__( 'Random', 'pbtheme' ),
                'modified' => esc_html__( 'Modified date', 'pbtheme' ),
                'menu_order' => esc_html__( 'Menu order', 'pbtheme' ),
                'comment_count' => esc_html__( 'Comment count', 'pbtheme' ),
            ),
            'default' => 'date'
        ),
        'alignment' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Alignment', 'pbtheme' ),
            'options' => array(
                'left' => esc_html__( 'Left', 'pbtheme' ),
                'center' => esc_html__( 'Center', 'pbtheme' ),
            ),
            'default' => 'left'
        ),
        'bgd_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Background color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'border_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Border color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'category_bgd_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Category background color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'category_text_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Category text color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'title_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Title color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'text_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Text color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'icon_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Icon color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'comments_date_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Comments and date color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'navigation_bgd_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Navigation arrows background color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'navigation_icon_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Navigation arrows color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
    )
    
);
predic_widget()->add_widget( $pbtheme_bps_widget_widget );