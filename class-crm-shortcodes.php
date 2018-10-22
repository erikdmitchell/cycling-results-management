<?php

class CRM_Shortcodes {

    function __construct() {
        add_shortcode( 'crm_main', array( $this, 'crm_main' ) );
        add_shortcode( 'crm_races', array( $this, 'crm_races' ) );
        add_shortcode( 'crm_races', array( $this, 'crm_riders' ) );
        add_shortcode( 'crm_uci_rankings', array( $this, 'crm_uci_rankings' ) );
    }

    /**
     * Main results and rankings.
     * 
     * @access public
     * @param mixed $atts
     * @return void
     */
    function crm_main( $atts ) {
        $atts = shortcode_atts(
            array(), $atts, 'crm_main'
        );

        return crm_get_template_part( 'main' );
    }

    /**
     * UCI rankings.
     * 
     * @access public
     * @param mixed $atts
     * @return void
     */
    function crm_uci_rankings( $atts ) {
        $atts = shortcode_atts(
            array(), $atts, 'crm_uci_rankings'
        );

        return crm_get_template_part( 'uci-rankings-landing' );
    }

    /**
     * Races list.
     * 
     * @access public
     * @param mixed $atts
     * @return void
     */
    function crm_races( $atts ) {
        $atts = shortcode_atts(
            array(), $atts, 'crm_races'
        );

        return crm_get_template_part( 'races' );
    }

    /**
     * Riders list.
     * 
     * @access public
     * @param mixed $atts
     * @return void
     */
    function crm_riders( $atts ) {
        $atts = shortcode_atts(
            array(), $atts, 'crm_riders'
        );

        return crm_get_template_part( 'riders' );
    }

}

new CRM_Shortcodes();
