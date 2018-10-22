<?php

class CRM_Shortcodes {

    function __construct() {
        add_shortcode( 'crm_main', array( $this, 'crm_main' ) );
        add_shortcode( 'crm_uci_rankings', array( $this, 'crm_uci_rankings' ) );
    }

    function crm_main( $atts ) {
        $atts = shortcode_atts(
            array(), $atts, 'crm_main'
        );

        return crm_get_template_part( 'main' );
    }
    
    function crm_uci_rankings($atts) {
        $atts = shortcode_atts(
            array(), $atts, 'crm_uci_rankings'
        );        
        
        return crm_get_template_part( 'uci-rankings-landing' );
    }

}

new CRM_Shortcodes();
