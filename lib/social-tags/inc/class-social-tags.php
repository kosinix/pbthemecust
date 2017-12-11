<?php

/**
 * Class responsible for distributing social tags
 */
class Shi_Social_Tags {
    
    public static function create_network( $network ) {
        
        switch ( $network ) {
            
            case 'twitter':
                return new Shi_Social_Tags_Twitter();
                break;
            
            case 'facebook':
                return new Shi_Social_Tags_Facebook();
                break;

            default:
                return false;
                break;
            
        }
        
    }
    
}