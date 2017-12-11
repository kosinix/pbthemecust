<?php

/**
 * Functionality responsible for applying theme options from header style section
 * 
 * Implementation done via Observer design pattern
 */
class Pbtheme_Header_Design_Init {
    
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
        
        // Proceed only if user enabled custom header styles
        $enabled = $this->enabled();
        if ( ! $enabled ) {
            return;
        }
        
        // Observers
        $this->include_observers();
        
        // Subject
        require_once dirname( __FILE__ ) . '/inc/class-header-design.php';
        $design = new Pbtheme_Header_Design();
        
        /**
         * Attach below any theme options to apply
         */
        
        // Observers
        $observers = array(
            'Pbtheme_Header_Topbar_Bgd',
            'Pbtheme_Header_Topbar_Text_Color',
            'Pbtheme_Header_Topbar_Link_Color',
            'Pbtheme_Header_Topbar_Link_Hover_Color',
            'Pbtheme_Header_Topbar_Border',
            'Pbtheme_Header_Topbar_Font_Size',
            'Pbtheme_Header_Bgd',
            'Pbtheme_Header_Link_Color',
            'Pbtheme_Header_Link_Hover',
            'Pbtheme_Header_Border',
            'Pbtheme_Header_Menu_Style',
            'Pbtheme_Header_Search_Style',
            'Pbtheme_Header_Logo_Style',
            'Pbtheme_Header_Responsive_Menu',
        );
       
        /**
         * Apply styles via observers
         */
        $this->add_observers( $observers, $design );
        $design->apply_styles( $enabled );
        
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
            
            include_once $file->getPath() . '/' . $file->getFilename();
            
        }
        
        return true;
        
    }
    
    private function enabled() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_styles_enabled' ] ) && intval( $pbtheme_data[ 'header_styles_enabled' ] ) === 1 ? true : false ;
        
    }
    
}
new Pbtheme_Header_Design_Init();