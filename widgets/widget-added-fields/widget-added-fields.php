<?php

/**
 * Add custom fields to existing widgets
 *
 * Will use addded fields for custom css
 */

class PBTheme_Widget_Added_Fields {

    // All color fields to use in widgets
    private $bgd_color = 'pbtheme_bgd_color';
    private $border_color = 'pbtheme_border_color';
    private $title_color = 'pbtheme_title_color';
    private $utility_color = 'pbtheme_utility_color';
    private $text_color = 'pbtheme_text_color';
    private $link_color = 'pbtheme_link_color';

    /**
     * Hold all fielsd and it's labels for admin form and save
     * @var array
     */
    private $fields;
    /**
     * Hold fields assignet to widgets
     * @var array
     */
    private $fields_by_widgets;

    /**
     * Array of widgets id's to add fields to
     * @var array
     */
    private $customized = array(
        'pbtheme_twitter',
        'pbtheme_category',
        'archives',
        'categories',
        'pages',
        'recent-comments',
        'recent-posts',
        'rss',
        'tag_cloud',
        'woocommerce_product_categories',
        'woocommerce_product_tag_cloud',
        'woocommerce_products',
        'woocommerce_recent_reviews'
    );

    /**
     * Constructor
     */
    public function __construct() {

        $this->fields = array(
            $this->bgd_color => esc_html__( 'Background color:', 'pbtheme' ),
            $this->text_color => esc_html__( 'Text color:', 'pbtheme' ),
            $this->title_color => esc_html__( 'Title color:', 'pbtheme' ),
            $this->border_color => esc_html__( 'Border color:', 'pbtheme' ),
            $this->utility_color => esc_html__( 'Utility color:', 'pbtheme' ),
            $this->link_color => esc_html__( 'Link color:', 'pbtheme' )
        );

        $this->fields_by_widgets = $this->fields_by_widgets();

        // Override existing widgets
        add_action( 'in_widget_form', array( $this, 'add_fields' ), 20, 3 );
        add_filter( 'widget_update_callback', array( $this, 'save' ), 10, 4 );
        add_filter( 'widget_display_callback', array( $this, 'render_view' ), 20, 3 );

        // Add admin scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    /**
     * Add color picker script
     */
    public function admin_scripts( $hook ) {

        if ( ! $hook === 'widgets.php' ) {
            return;
        }

        // Add the color picker css file
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'pbtheme-widgets-added-fields', get_template_directory_uri() . '/widgets/widget-added-fields/assets/js/main.js', array( 'wp-color-picker' ), false, true );
        wp_enqueue_style( 'wp-color-picker' );

    }

    /**
     * Add fields to widget admin form
     * @param WP_Widget $this The widget instance, passed by reference.
     * @param null $return Return null if new fields are added.
     * @param array $instance An array of the widget's settings.
     */
    public function add_fields( $widget, $return, $instance ) {

        if ( ! in_array( $widget->id_base, $this->customized ) ) {
            return;
        }

        ?>
        <div class="pbtheme-widgets-added-fields" id="<?php echo esc_attr( $widget->get_field_id( 'color-picker_' ) ); ?>">
            <?php
                // Echo all fields
                foreach ( $this->fields as $field => $label ) :

                // Discard fields which we do not need
                if ( ! in_array( $field, $this->fields_by_widgets[ $widget->id_base ] ) ) {
                    continue;
                }

                $value = isset( $instance[ $field ]  ) ? $instance[ $field ] : '';
            ?>
            <p>
                <label for="<?php echo $widget->get_field_id( $field ); ?>"><?php echo esc_attr( $label ); ?></label>
                <br />
                <input
                    class="widefat color-field"
                    id="<?php echo $widget->get_field_id( $field ); ?>"
                    type="text"
                    name="<?php echo $widget->get_field_name( $field ); ?>"
                    value="<?php echo sanitize_text_field( $value ); ?>"
                />
            </p>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Save custom fields
     * @param array $instance The current widget instance's settings.
    * @param array $new_instance Array of new widget settings.
    * @param array $old_instance Array of old widget settings.
    * @param WP_Widget $widget The current widget instance.
     * @return array The current widget instance's settings.
     */
    public function save( $instance, $new_instance, $old_instance, $widget ) {

        if ( ! in_array( $widget->id_base, $this->customized ) ) {
            return $instance;
        }

        /**
         * Just return new save including our custom added fields
         */

        foreach ( $this->fields as $field => $label ) {
            if ( isset( $new_instance[ $field ] ) ) {
                $instance[ $field ] = $new_instance[ $field ];
            }
        }

        return $instance;
    }

    /**
     * Render frontend custom css
     * @param array $instance The current widget instance's settings.
    * @param WP_Widget $this The current widget instance.
    * @param array $args An array of default widget arguments.
     * @return array The current widget instance's settings
     */
    public function render_view( $instance, $widget, $args ) {

        if ( ! in_array( $widget->id_base, $this->customized ) ) {
            return $instance;
        }
        // Get widget generated id
        $widget_id = $widget->id;

        $css = '';

        switch ( $widget->id_base ) {
            case 'pbtheme_twitter':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter ul.tweets-list {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter .pbtheme-widget-title .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter .tweet-post {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter ul.tweets-list {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter .tweets-list .text-ago {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter .tweets-list li.relative {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Link color
                if ( isset( $instance[ $this->link_color ] ) && !empty( $instance[ $this->link_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter .tweets-list div.tweet-post a {color: ' . esc_attr( $instance[ $this->link_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-twitter ul.tweets-list {padding: 14px;}';
                }
            break;

            case 'pbtheme_category':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-cat ul {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-cat .pbtheme-widget-title .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-cat ul li h4 a {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-cat ul {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-cat .category_meta a {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-cat .date_meta {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ' !important;}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget-pbtheme-cat ul {padding: 14px;}';
                }
            break;

            case 'archives':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_archive ul {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_archive .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_archive ul {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_archive ul li {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_archive label.screen-reader-text {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_archive ul {padding: 14px;}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_archive ul li {margin-left: 14px;}';
                }
            break;

            case 'categories':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_categories ul {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_categories .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_categories ul {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_categories ul li.cat-item {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_categories label.screen-reader-text {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_categories ul {padding: 14px;}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_categories ul li.cat-item {margin-left: 14px;}';
                }
            break;

            case 'pages':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_pages ul {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_pages .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_pages ul {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_pages ul li.page_item {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_pages ul {padding: 14px;}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_pages ul li {margin-left: 14px;}';
                }
            break;

            case 'recent-comments':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments ul {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments ul li a {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments ul {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments ul li span.comment-author-link {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments ul li span.comment-author-link a {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments ul {padding: 14px;}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_comments ul li {margin-left: 14px;}';
                }
            break;

            case 'recent-posts':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_entries ul {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_entries .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_entries ul li a {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_entries ul {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_entries ul li span.post-date {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_entries ul {padding: 14px;}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_entries ul li {margin-left: 14px;}';
                }
            break;

            case 'rss':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss ul {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Text color
                if ( isset( $instance[ $this->text_color ] ) && !empty( $instance[ $this->text_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss ul li div.rssSummary {color: ' . esc_attr( $instance[ $this->text_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss ul li a.rsswidget {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss ul {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss ul li span.rss-date {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss ul li cite {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_rss ul {padding: 14px;}';
                }
            break;

            case 'tag_cloud':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_tag_cloud div.tagcloud {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Text color
                if ( isset( $instance[ $this->text_color ] ) && !empty( $instance[ $this->text_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_tag_cloud div.tagcloud a {color: ' . esc_attr( $instance[ $this->text_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_tag_cloud .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_tag_cloud div.tagcloud {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_tag_cloud div.tagcloud a {background-color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_tag_cloud div.tagcloud {padding: 14px;}';
                }
            break;

            case 'woocommerce_product_categories':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_categories ul.product-categories {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_categories .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_categories ul.product-categories {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_categories ul li.cat-item {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_categories select.dropdown_product_cat {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_categories ul.product-categories {padding: 14px;}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_categories ul.product-categories li {margin-left: 14px;}';
                }
            break;

            case 'woocommerce_product_tag_cloud':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_tag_cloud div.tagcloud {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Text color
                if ( isset( $instance[ $this->text_color ] ) && !empty( $instance[ $this->text_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_tag_cloud div.tagcloud a {color: ' . esc_attr( $instance[ $this->text_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_tag_cloud .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_tag_cloud div.tagcloud {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_tag_cloud div.tagcloud a {background-color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_product_tag_cloud div.tagcloud {padding: 14px;}';
                }
            break;

            case 'woocommerce_products':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_products ul.product_list_widget {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_products .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_products .product-title {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_products ul.product_list_widget {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_products ul li .woocommerce-Price-amount {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_products ul.product_list_widget {padding: 14px;}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_products ul.product_list_widget li {margin-left: 14px;}';
                }
            break;

            case 'woocommerce_recent_reviews':
                // Background color
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_reviews ul.product_list_widget {background-color: ' . esc_attr( $instance[ $this->bgd_color ] ) . ';}';
                }
                // Title color
                if ( isset( $instance[ $this->title_color ] ) && !empty( $instance[ $this->title_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_reviews .title_holder {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_reviews ul.product_list_widget li a {color: ' . esc_attr( $instance[ $this->title_color ] ) . ';}';
                }
                // Border color
                if ( isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_reviews ul.product_list_widget {border: 1px solid ' . esc_attr( $instance[ $this->border_color ] ) . ';}';
                }
                // Utility color
                if ( isset( $instance[ $this->utility_color ] ) && !empty( $instance[ $this->utility_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_reviews ul li span.reviewer {color: ' . esc_attr( $instance[ $this->utility_color ] ) . ';}';
                }
                // Add padding when Backgroun or Border colors are set
                if ( isset( $instance[ $this->bgd_color ] ) && !empty( $instance[ $this->bgd_color ] ) || isset( $instance[ $this->border_color ] ) && !empty( $instance[ $this->border_color ] ) ) {
                    $css .= '#' . esc_attr( $widget_id ) . '.widget_recent_reviews ul.product_list_widget, .widget_recent_reviews ul.product_list_widget li {padding: 14px;}';
                }
            break;

            default:
            break;
        }

        // If any of the widgets have custom colors
        if ( ! empty( $css ) ) {
            echo '<style type="text/css">';
            echo $css;
            echo '</style>';
        }

        return $instance;

    }

    /**
     * Set which fields goeis int which widget admin form
     */
    private function fields_by_widgets() {

        return array(

            'pbtheme_twitter' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color,
                $this->link_color
            ),
            'pbtheme_category' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'archives' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'categories' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'pages' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'recent-comments' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'recent-posts' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'rss' => array(
                $this->bgd_color,
                $this->text_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'tag_cloud' => array(
                $this->bgd_color,
                $this->text_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'woocommerce_product_categories' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'woocommerce_product_tag_cloud' => array(
                $this->bgd_color,
                $this->text_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'woocommerce_products' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            ),
            'woocommerce_recent_reviews' => array(
                $this->bgd_color,
                $this->title_color,
                $this->border_color,
                $this->utility_color
            )


        );

    }

}
new PBTheme_Widget_Added_Fields();
