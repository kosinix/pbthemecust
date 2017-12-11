<?php

class Predic_Widget_Builder {
    
    private $widget;
    
    public function __construct( $atts ) {
        $this->widget = new Predic_Widget_Factory( $atts );
        $this->widget_init();
    }
    
    public function widget_init() {
        add_action( 'widgets_init', array( $this, 'register_widget' ) );
    }

    public function register_widget() {
        register_widget( $this->widget );
    }
    
}