<?php

abstract class Predic_Widget_Form_Field {
    
    abstract public function __construct( $atts, $id, $name, $value );


    abstract public function field();
    
}