<?php

/**
 * Functionality responsible for applying theme options from widgets style section
 * 
 * Implementation done via Observer design pattern
 */
class Pbtheme_Widgts_Design_Init {
    
    public function __construct() {
        $this->include_observers();
        
        add_action( 'pbtheme_dynamic_css', array( $this, 'dynamic_css' ), 20 );
    }
    
    /**
     * Check if fields in theme options are activated by user and adds custom css
     * 
     * @hooked Action hook used pbtheme_dynamic_css which is added in wp_head action
     */
    public function dynamic_css() {
        
        global $pbtheme_data;
        
        // Observers
        $this->include_observers();
        
        // Subject
        require_once dirname( __FILE__ ) . '/inc/class-widgets-design.php';
        $design = new Pbtheme_Widgets_Design();
        
        /**
         * Attach below any theme options to apply
         */
        
        // Observers
        $observers = array(
            'Pbtheme_Topbar_Widget_Area',
        );
       
        /**
         * Apply styles via observers
         */
        $this->add_observers( $observers, $design );
        $design->apply_styles();
        
    }
    
    /**
     * Attach observers to subject
     * @param array $observers Array of class names to attach to subject
     * @param SplObserver $subject Class that implements SplObserver
     */
    private function add_observers( $observers, $subject ) {
        
        if ( is_array( $observers ) ) {
            
            foreach ( $observers as $observer) {
                
                if ( class_exists( $observer ) ) {
                
                    $subject->attach( new $observer() );
                
                }
            }
            
        }
        
    }
    
    private function include_observers() {
        
        $dir = dirname( __FILE__ ) . '/inc/options/';
        
        if ( ! is_dir( $dir ) ) {
            return new WP_Error( 'directory_not_found', wp_kses_post( sprintf( __( 'Directory %s not found', "pbtheme" ), $dir ) ) );
        }
        
        $files = new DirectoryIterator( $dir );
        
        foreach ( $files as $file ) {
            
            if( $file->isDot() ) {
                continue;
            }

            include_once $file->getPathname();
            
        }
        
        return true;
        
    }
    
}
new Pbtheme_Widgts_Design_Init();