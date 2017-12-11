<?php

/**
 * Class implementing theme options on site frontend
 */
class Pbtheme_Widgets_Design implements SplSubject {
    
    private $observers;
    
    public function __construct() {
        $this->observers = new SplObjectStorage();
    }
    
    /**
     * Notify all observers (theme options) to check and add dynamic css
     */
    public function apply_styles() {
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
  
}
