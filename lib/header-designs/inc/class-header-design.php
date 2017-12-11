<?php

/**
 * Class implementing theme options on site frontend
 */
class Pbtheme_Header_Design implements SplSubject {
    
    private $status;
    private $observers;
    
    public function __construct() {
        $this->observers = new SplObjectStorage();
    }
    
    /**
     * Notify all observers (theme options) to check and add dynamic css
     */
    public function apply_styles( $status ) {
        $this->status = $status;
        $this->notify();
    }

    /**
     * 
     * @param \SplObserver $observer
     */
    public function attach(SplObserver $observer ) {
        $this->observers->attach( $observer );
    }
    
    /**
     * 
     * @param \SplObserver $observer
     */
    public function detach(SplObserver $observer ) {
        $this->observers->detach( $observer );
    }
    
    public function notify() {
        foreach ( $this->observers as $observer ) {
            $observer->update( $this );
        }
    }
    
    public function get_status() {
        return $this->status;
    }
  
}
