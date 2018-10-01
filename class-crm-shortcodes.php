<?php
    
class CRM_Shortcodes {
    
    function __construct() {
        add_shortcode( 'crm_main', array($this, 'crm_main') );        
    }

    function crm_main( $atts ) {
        $atts = shortcode_atts(
            array(), $atts, 'crm_main'
        );

        return crm_get_template_part( 'main' );
    }
    
}

new CRM_Shortcodes();
