<?php

/**
 * CRM_Riders class.
 *
 * @since 0.1.0
 */
class CRM_Riders {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {

    }

    /**
     * Get riders rankings list.
     *
     * @access public
     * @param string $args (default: '').
     * @return object
     */
    public function get_riders_rankings( $args = '' ) {
        global $wpdb;

        $rankings = array();
        $default_args = array(
            'limit' => -1,
            'discipline' => 'cyclocross',
            'season' => $this->get_recent_season(),
        );
        $args = wp_parse_args( $args, $default_args );

        if ( $args['limit'] < 0 ) :
            $limit = '';
        else :
            $limit = 'LIMIT ' . $args['limit'];
        endif;

        $rankings_db = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}crm_rider_rankings WHERE discipline = '" . $args['discipline'] . "' AND season = '" . $args['season'] . "' ORDER BY rank $limit" );

        // append name.
        foreach ( $rankings_db as $rider ) :
            $rider->name = get_the_title( $rider->rider_id );
        endforeach;

        // add details for links (possibly pag).
        $rankings['riders'] = $rankings_db;
        $rankings['discipline'] = $args['discipline'];
        $rankings['season'] = $args['season'];

        return $rankings;
    }

    /**
     * Get recent season from db.
     *
     * @access public
     * @param string $discipline (default: 'cyclocross').
     * @return string
     */
    public function get_recent_season( $discipline = 'cyclocross' ) {
        global $wpdb;

        $season = $wpdb->get_var( "SELECT DISTINCT season FROM {$wpdb->prefix}crm_rider_rankings WHERE discipline = '$discipline' ORDER BY season DESC" );

        return $season;
    }

    /**
     * Get rider rankings from db.
     *
     * @access public
     * @param int $rider_id (default: 0).
     * @return object
     */
    public function get_rider_rankings( $rider_id = 0 ) {
        global $wpdb;

        $rankings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}crm_rider_rankings WHERE rider_id = $rider_id" );

        return $rankings;
    }

}
