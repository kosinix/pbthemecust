<?php

/* 
 *  Latest tweet widget
 */
class PBTheme_Latest_Tweet_Widget {

    public function render_view( $args, $instance, $form_fields ) {
        
        $widget_title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        
        if ( ! empty( $widget_title ) ) {
            echo $args['before_title'] . esc_html( $widget_title ) . $args['after_title'];
        }
       
        echo $this->twitter_feed( $instance );

        echo $args['after_widget'];
        
    }
        
    private function twitter_feed( $instance ) {
        
        $html = '';
        
        /**
         * Check username
         */
        if ( isset( $instance['username'] ) && !empty( $instance['username'] ) ) {
            $user = sanitize_text_field( $instance['username'] );
        } else {
            return esc_html__( 'Please enter Twitter username in widget admin form!', 'pbtheme' );
        }
        
        /**
         * Define vars
         */
        $inline_styles = array();
        $border_color = isset( $instance['border_color'] ) && !empty( $instance['border_color'] ) ? 'border-color: '. esc_attr( $instance['border_color'] ) .';' : '';
        if ( !empty( $border_color ) ) {
            $inline_styles[] = $border_color;
        }
        
        if ( $instance['background_type'] == 'twitter' ) {
            $inline_styles[] = 'background-image: url(\''. get_template_directory_uri() .'/widgets/latest-tweet/assets/img/twitter_background.jpg\');';
        }
        else {
            $inline_styles[] = 'background-image: none;';
        }
        
        $background_color = isset( $instance['background_color'] ) && !empty( $instance['background_color'] ) ? 'background-color: '. esc_attr( $instance['background_color'] ) .';' : '';
        if ( !empty( $background_color ) ) {
            $inline_styles[] = $background_color;
        }
        
        $font_color_style = isset( $instance['font_color'] ) && !empty( $instance['font_color'] ) ? ' style="color: ' . esc_attr( $instance['font_color'] ) . ';"' : '';

        // Build inline style for widget
        $latest_tweet_content_holder_style = 'style="'. implode( ' ', $inline_styles ) .'"';
        
        
        
        /**
         * Set transient key to check cache
         */
        $transient_key = $user . "_latest_tweet_widget";
        $data = get_transient($transient_key);

        /**
         * Values from theme options
         */
        global $pbtheme_data;
        $consumerkey = $pbtheme_data['twitter_ck'];
        $consumersecret = $pbtheme_data['twitter_cs'];
        $accesstoken = $pbtheme_data['twitter_at'];
        $accesstokensecret = $pbtheme_data['twitter_ats'];

        /**
         * Get tweets
         */
         if (false === $data) {
            $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
            $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $user . "&count=1");
            $data = json_decode(json_encode($tweets));
         }

        /**
         * Validate if error
         */
        if ( ! is_array( $data ) || ! isset( $data[0] ) ) {
            return esc_html__( 'Something went wrong, please try later or use another username!', 'pbtheme' );
        }
       
        /**
         * Html output start
         */
        $date = isset( $data[0]->created_at ) ? substr( $data[0]->created_at, 0, 10 ) : '';
        $photo = isset( $data[0]->user->profile_image_url ) ? '<img src="'. esc_url( $data[0]->user->profile_image_url ) .'" >' : '';
        $text = isset( $data[0]->text ) ? wp_kses_post( $data[0]->text ) : '';

        $output = '';

        if ( is_array( $data ) ) :
            
            $output = '';
            
            $output .= '<div class="latest-tweet-content-holder" '. $latest_tweet_content_holder_style .'>';
            $output .= '<a href="https://twitter.com/'. esc_html( $user ) .'">'. $photo ;
            $output .= '<span> ' . esc_html( $user ) . ' </span></a>';
            $output .= '<div class="latest-tweet-text"' . $font_color_style . '>';
            $output .= $text;
            $output .= '</div>';
            $output .= '<p class="date"> <i class="fa fa-calendar"></i> <span>'. $date .'</span></p>';
            $output .= '</div>';

            set_transient($transient_key, $data, 1800);

            return $output;
            
        else :

            return esc_html__('Error, could not retrieve Twitter feed.', 'pbtheme');

        endif;

    }
    
}

$pbtheme_single_tweet_widget = array(
    
    // Core configuration
    'base_id' => 'wpbtheme_latest_tweet',
    'name' => esc_html__('+ PBTheme Latest tweet', 'pbtheme'),
    'callback' => array( new PBTheme_Latest_Tweet_Widget(), 'render_view' ),

    'widget_ops' => array(
        'classname' => 'wpbtheme-latest-tweet',
        'description' => esc_html__( 'Display latest tweet from an author.', 'pbtheme' ),
        'customize_selective_refresh' => false,
    ),
    
    'form_fields' => array(
        
        'widget_title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Widget title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' ),
        ),
        'username' => array(
            'type' => 'text',
            'label' => esc_html__( 'Twitter username:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Example: SomeUsername', 'pbtheme' ),
        ),
        'background_type' => array(
            'type' => 'select', 
            'label' => esc_html__( 'Label name:', 'pbtheme' ), 
            'options' => array(
                'twitter' => esc_html__( 'Default Twitter', 'pbtheme' ),
                'color' => esc_html__( 'Color', 'pbtheme' ),
            ),
            'default' => 'twitter'
        ),
        'background_color' => array(
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
        'font_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Font color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        
    )
    
);

predic_widget()->add_widget( $pbtheme_single_tweet_widget );