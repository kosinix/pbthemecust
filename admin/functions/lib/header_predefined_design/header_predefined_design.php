<?php
/**
 * Class that set theme options to selected predefined header design
 *
 * @since 3.2.4
 */
class header_predefined_design {
    
    private $design;
    private $options;
    
    /**
     * Constructor
     * @since 3.2.4
     * @param string/int $design User selected design in theme options to implement
     * @param array $options Whole theme options values in array
     */
    public function __construct( $design, $options ) {
        $this->design = $design;
        $this->options = $options;
    }
    
    public function set_selected_design() {
        
        switch ( intval( $this->design ) ) {
            case 1:
                $file = dirname( __FILE__ ) . '/designs/design-1.php';
                $options = $this->get_replace_array( $file );
                break;
            
            case 2:
                $file = dirname( __FILE__ ) . '/designs/design-2.php';
                $options = $this->get_replace_array( $file );
                break;
            
            case 3:
                $file = dirname( __FILE__ ) . '/designs/design-3.php';
                $options = $this->get_replace_array( $file );
                break;
            
            case 4:
                $file = dirname( __FILE__ ) . '/designs/design-4.php';
                $options = $this->get_replace_array( $file );
                break;
            
            case 5:
                $file = dirname( __FILE__ ) . '/designs/design-5.php';
                $options = $this->get_replace_array( $file );
                break;
            
            case 6:
                $file = dirname( __FILE__ ) . '/designs/design-6.php';
                $options = $this->get_replace_array( $file );
                break;
            
            case 7:
                $file = dirname( __FILE__ ) . '/designs/design-7.php';
                $options = $this->get_replace_array( $file );
                break;
            
            case 8:
                $file = dirname( __FILE__ ) . '/designs/design-8.php';
                $options = $this->get_replace_array( $file );
                break;

            default:
                die('0');
                break;
        }
        
        return $options;
        
    }
    
    private function get_replace_array( $file ) {
        
        if (! file_exists( $file ) ) {
            return array();
        }

        $replace = require $file;
        return array_replace( $this->options, $replace );
        
    }
    
}