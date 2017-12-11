<?php

/**
 * Blog posts widget class
 */
class PBTheme_WC_Product_Slider_Widget {
    
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
    private $sorting;
    private $posts_per_page;
    private $category;
    
    public function __construct() {
        $this->root_url = get_template_directory_uri() . '/widgets/wc_product_slider';
        add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
        
        /**
         * Add widget  via builder
         */
        $this->map_and_init();
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
        
        $widget_title = isset( $instance['title'] ) ? $instance['title'] : '';
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        
        if ( ! empty( $widget_title ) ) {
            echo $args['before_title'] . esc_html( $widget_title ) . $args['after_title'];
        }
        
        /**
         * Define vars
         */
        $this->sorting = isset( $instance['sorting'] ) && !empty( $instance['sorting'] ) ? $instance['sorting'] : $form_fields['sorting']['default'];
        $this->posts_per_page = isset( $instance['posts_per_page'] ) && !empty( $instance['posts_per_page'] ) && intval( $instance['posts_per_page'] ) !== 0 ? $instance['posts_per_page'] : '-1';
        $this->category = isset( $instance['category'] ) && $instance['category'] === 'all' ? '' : $instance['category'];
        $align_class = isset( $instance['alignment'] ) && $instance['alignment'] === 'left' ? 'wpbtheme_wps--left' : 'wpbtheme_wps--' . $instance['alignment'];
        
        // Unique widget id used for custom inline css
        $this->widget_id = uniqid( 'wpbtheme_wps-' );
        
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
        echo '<div id="' . esc_attr( $this->widget_id ) . '" class="wpbtheme_wps ' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '" data-count="' . esc_js( $this->slides_count ) . '">';
        
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

        // The Query
        $query = $this->get_products_query( $this->posts_per_page, $this->category, $this->sorting );
       
        if ( is_wp_error( $query ) || ! $query->have_posts() ) {
            return esc_html__( 'No product found', 'pbtheme' );
        }
        
        // Set number of slides var
        $this->slides_count = $query->post_count;

        /**
         * Build slides
         */
        while ( $query->have_posts() ) : $query->the_post();
        
            $img_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : IMAGES_FOLDER . 'placeholder/placeholder.jpg';
            
            
            
            $categories = get_the_terms( get_the_ID(), 'product_cat' );
            $category = isset( $categories[0] ) 
                    ? 
                    '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" '
                    . 'title="' . esc_url( get_category_link( $categories[0]->name ) ) . '">'
                    . '' . strip_tags( $categories[0]->name ) . ''
                    . '</a>'
                    :
                    '';
            ob_start();
            ?>
            <div class="swiper-slide">
                <div id="wpbtheme_wps-id-<?php echo esc_attr( get_the_ID() ) ?>" class="wpbtheme_wps__post">

                    <div class="wpbtheme_wps__post-cat"><?php echo $category; ?></div>
                    <div class="wpbtheme_wps__post-img" style="background-image: url('<?php echo esc_url( $img_url ) ?>')"></div>
                    
                    <?php woocommerce_show_product_sale_flash(); ?>

                    <div class="wpbtheme_wps__post-content">

                        <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="wpbtheme_wps__post-title"><?php echo $this->limit_chars( strip_tags( get_the_title() ), 58 ); ?></a>

                        <div class="wpbtheme_wps__post-bottom">
                            <span class="wpbtheme_wps__post-price"><?php woocommerce_template_loop_price(); ?></span>
                            <span class="wpbtheme_wps__post-button"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php esc_html_e( 'View product', 'pbtheme' ); ?></a></span>  
                        </div>

                    </div><!-- .wpbtheme_wps__post-content -->

                </div><!-- .wpbtheme_wps__post -->

            </div>
            <?php
            $slides .= ob_get_clean();
        
        endwhile;
        wp_reset_postdata();
        
        return $slides;
        
        
    }
    
    /**
     * Return WP_Query for shortcode
     *
     * @since 1.0.0
     *
     * @param int    $posts_per_page   Posts per page for Query.
     * @param string    $cat_id   Category id to limit results to this one category.
     * @param string    $products_sorting   What type of products to fetch: rand, featured, recent, top_rated, best_selling or on_sale...
     * @param int    $product_id   Id of one product to return Query for
     * 
     * @return  object  Return WP_Query object 
     */
    protected function get_products_query( $posts_per_page, $cat_id, $products_sorting = '', $product_id = FALSE ) {
        
        $the_query_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => intval( $posts_per_page ),
            'ignore_sticky_posts' => true,
            'meta_query' => array()
        );
        
        // Fetch only one product
        if ( !empty( $product_id ) ) {
            $the_query_args['p'] = absint( $product_id );
        }
        
        // If selected any category add category param
        if( !empty( $cat_id ) ) {
            
            $the_query_args['tax_query'] =  array(
                array(
                    'taxonomy'  => 'product_cat',
                    'field'     => 'id',
                    'terms'     => $cat_id
                )               
            );
        }

        
        // If selected sorting
        switch ( $products_sorting ) {
            case 'rand':
                $the_query_args['orderby'] = 'rand';
                $the_query_args['order'] = 'desc';
               
                break;
            
            case 'featured':
                $the_query_args['meta_key'] = '_featured';
                $the_query_args['meta_value'] = 'yes';
                $the_query_args['orderby'] = 'date';
                $the_query_args['order'] = 'desc';
               
                break;
            
            case 'recent':
                $the_query_args['orderby'] = 'date';
                $the_query_args['order'] = 'desc';

                break;
            
            case 'top_rated':
                add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
                
                break;
            
            case 'best_selling':
                $the_query_args['orderby'] = 'meta_value_num';
                $the_query_args['meta_key'] = 'total_sales';
                $the_query_args['meta_value'] = '0';
                $the_query_args['meta_compare'] = '>';

                break;
            
            case 'on_sale':
                $the_query_args['orderby'] = 'meta_value_num';
                $the_query_args['meta_query'][] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'numeric'
                    ),
                    array(
                        'key' => '_min_variation_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'numeric'
                    )
                );

                break;
        }
        
        // Add visible products in catalog only and stock meta query
        $the_query_args['meta_query'][] = WC()->query->get_meta_query();

        $the_query = new WP_Query( $the_query_args );
        
        // If showing top rated products remove added filter 
        if ( $products_sorting === 'top_rated' ) {
            remove_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
        }
        
        return $the_query;
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
            $css .= $widget_id . ' .wpbtheme_wps__post-content{background-color:' . esc_attr( $instance['bgd_color']  ) . ';}';
        }
        
        // Custom border color
        if ( isset( $instance['border_color'] ) && !empty( $instance['border_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_wps__post,'
                    . $widget_id . ' .wpbtheme_wps__post-bottom '
                    . '{border-color:' . esc_attr( $instance['border_color']  ) . ';}';
        }
        
        // Custom cagegory background color
        if ( isset( $instance['category_bgd_color'] ) && !empty( $instance['category_bgd_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_wps__post-cat a {background-color:' . esc_attr( $instance['category_bgd_color']  ) . ';}';
        }
        
        // Category text color
        if ( isset( $instance['category_text_color'] ) && !empty( $instance['category_text_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_wps__post-cat a {color:' . esc_attr( $instance['category_text_color']  ) . ';}';
        }
        
        // Title color
        if ( isset( $instance['content_title_color'] ) && !empty( $instance['content_title_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_wps__post-title {color:' . esc_attr( $instance['content_title_color']  ) . ';}';
        }
        
        // Price color
        if ( isset( $instance['price_color'] ) && !empty( $instance['price_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme_wps__post-price h5,'
                    . $widget_id . ' .wpbtheme_wps__post-price * '
                    . ' {color:' . esc_attr( $instance['price_color']  ) . ';}';
        }
        
        // Product link button color
        if ( isset( $instance['button_color'] ) && !empty( $instance['button_color'] ) ) {
            $css .= $widget_id . ' ..wpbtheme_wps__post-button a'
                    . ' {color:' . esc_attr( $instance['button_color']  ) . ';}';
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
        wp_enqueue_script( 'wpbtheme-wc-products-slider', $this->root_url . '/assets/js/main.js', array('pbtheme-idangerous'), false, true );
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
        
        $categories = get_terms( array(
            'taxonomy'  => 'product_cat',
            'hide_empty' => false,
            'fields' => 'id=>name',
            'orderby'      => 'name',
            'order'        => 'ASC',
            'number'       => '',
        ) );
        
        $categories = is_array( $categories ) && !empty( $categories ) 
                ? array( 'all' => esc_html__( 'All', 'pbtheme' ) ) + $categories
                : array( 'all' => esc_html__( 'All', 'pbtheme' ) );

        return $categories;
        
    }
    
    /**
     * Map and init blog posts widget widget
     */
    public function map_and_init() {

        $config = array(

            // Core configuration
            'base_id' => 'pbtheme_wc_products_slider_widget',
            'name' => esc_html__('+ PBTheme WooCommerce product slider', 'pbtheme'),
            'callback' => array( $this, 'render_view' ),

            'widget_ops' => array(
                'classname' => 'wpbtheme_wc_products_slider',
                'description' => esc_html__( 'Display products slider from selected category.', 'pbtheme' ),
                'customize_selective_refresh' => false,
            ),

            'form_fields' => array(

                'title' => array(
                    'type' => 'text',
                    'label' => esc_html__( 'Widget title:', 'pbtheme' ),
                    'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' ),
                ),
                'category' =>array(
                    'type' => 'select',
                    'label' => esc_html__( 'Category', 'pbtheme' ),
                    'options' => array( 'callable' => array( $this, 'get_select_cat_options' ) ),
                    'default' => 'all'
                ),
                'posts_per_page' => array(
                    'type' => 'text',
                    'label' => esc_html__( 'Number of posts (leave empty to display all):', 'pbtheme' ),
                    'placeholder' => esc_html__( 'Use only numbers please', 'pbtheme' ),
                ),
                'sorting' =>array(
                    'type' => 'select',
                    'label' => esc_html__( 'Products selection', 'pbtheme' ),
                    'options' => array(
                        'rand' => esc_html__( 'Random', 'pbtheme' ),
                        'featured' => esc_html__( 'Featured', 'pbtheme' ),
                        'recent' => esc_html__( 'Recent', 'pbtheme' ),
                        'top_rated' => esc_html__( 'Top rated', 'pbtheme' ),
                        'best_selling' => esc_html__( 'Best selling', 'pbtheme' ),
                        'on_sale' => esc_html__( 'On sale', 'pbtheme' ),
                    ),
                    'default' => 'recent'
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
                'content_title_color' => array(
                    'type' => 'color', 
                    'label' => esc_html__( 'Title color:', 'pbtheme' ),
                    'alpha' => true,
                    'default' => ''
                ),
                'price_color' => array(
                    'type' => 'color', 
                    'label' => esc_html__( 'Price color:', 'pbtheme' ),
                    'alpha' => true,
                    'default' => ''
                ),
                'button_color' => array(
                    'type' => 'color', 
                    'label' => esc_html__( 'Link to product color:', 'pbtheme' ),
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
        
        predic_widget()->add_widget( $config );
    }
}
if ( class_exists( 'WooCommerce' ) ) {
    new PBTheme_WC_Product_Slider_Widget();
}


