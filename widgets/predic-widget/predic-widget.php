<?php
/**
 * Plugin Name: Predic widget builder framework
 * Plugin URI: 
 * Description: A Framework to build widget admin form
 * Version: 1.0.0
 * Author: Aleksandar Predic
 * Author URI: https://acapredic.com/
 * Requires at least: 4.0
 * Tested up to: 4.7.3
 *
 * Text Domain: predic_widget
 * Domain Path: /languages/
 *
 * @package Predic_Widget
 * @author Aleksandar Predic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( defined( 'PREDIC_WIDGET_VERSION' ) ) {
    trigger_error( esc_html__( 'Framework Predic_Widget already exists.', 'predic_widget' ), E_USER_WARNING );
    return; // Exit if class exist, framework already exists.
}

/**
 * Widget builder framework
 */
class Predic_Widget {
    
    private static $instance;
    private $version = '1.0.0';
    private $root_path;
    /**
     * Configuration array to create widgets from
     * @var array
     */
    private $widgets = array();
    
    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
	 * @since 1.0.0
     */
    protected function __construct() {
        $this->define_constants();
        
        // Include all core files
        require_once PREDIC_WIDGET_ROOT_PATH . '/class/abstract/class-predic-widget-form-field.php';
        require_once PREDIC_WIDGET_ROOT_PATH . '/class/class-predic-widget-factory.php';
        require_once PREDIC_WIDGET_ROOT_PATH . '/class/class-predic-widget-builder.php';
        
        //Register widgets from configuration array
        add_action( 'after_setup_theme', array( $this, 'create_widgets' ), 20 );
        
        // Add widgets admin scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }
    
    /**
	 * Main Predic_Widget instance
	 *
	 * Ensures only one instance of Predic_Widget is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Predic_Widget - Main instance.
	 */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
	 * Define Constants.
	 */
	private function define_constants() {
		$this->define( 'PREDIC_WIDGET_VERSION', $this->version );
		$this->define( 'PREDIC_WIDGET_ROOT_URL', get_template_directory_uri() . '/widgets/predic-widget' );
		$this->define( 'PREDIC_WIDGET_ROOT_PATH', get_template_directory() . '/widgets/predic-widget' );
		$this->define( 'PREDIC_WIDGET_ASSETS_URL', PREDIC_WIDGET_ROOT_URL . '/assets/' );
	}
    
    /**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
    
    public function add_widget( array $atts ) { 
        array_push( $this->widgets, $atts );
    }
    
    /**
     * Register widgets from configuration array
     * @hooked action after_setup_theme
     */
    public function create_widgets() {
        
        // Allow others to add more widgets to create automatically
        $this->widgets = apply_filters( 'predic_defined_widgets_array', $this->widgets );
        
        if ( empty( $this->widgets ) ) {
            return false;
        }
           
        foreach ( $this->widgets as $atts ) {
            new Predic_Widget_Builder( $atts );
        }
            
    }
    
    public function admin_scripts( $hook ) {
        
        if ( $hook !== 'widgets.php' ) {
            return;
        }
        
        wp_enqueue_style( 'predic-widget-admin-style', PREDIC_WIDGET_ASSETS_URL . '/css/main.css' );
    }
    
    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     * @since 1.0.0
     * @return void
     */
    private function __clone() {
        
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     * @since 1.0.0
     * @return void
     */
    private function __wakeup() {
        
    }
    
}
/**
 * Make instance here to include hooks before widgets are created
 */
Predic_Widget::get_instance();

/**
 * Main instance of Predic_Widget.
 *
 * Returns the main instance of Predic_Widget to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Predic_Widget
 */
function predic_widget() {
    return Predic_Widget::get_instance();
}