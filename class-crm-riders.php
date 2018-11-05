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
            'offset' => 0,
            'discipline' => 'cyclocross',
            'season' => $this->get_recent_season(),
        );
        $args = wp_parse_args( $args, $default_args );

        if ( -1 == $args['limit'] ) :
            $limit = '';
        elseif ( $args['offset'] >= 1 ) :
            $limit = 'LIMIT ' . $args['limit'] . ',' . $args['offset'];
        else :
            $limit = 'LIMIT ' . $args['limit'];
        endif;

        $rankings_db = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}crm_rider_rankings WHERE discipline = '" . $args['discipline'] . "' AND season = '" . $args['season'] . "' ORDER BY rank $limit" );

        // append name.
        foreach ( $rankings_db as $rider ) :
            $rider->name = get_the_title( $rider->rider_id );
            $rider->nat = crm_get_first_term( $rider->rider_id, 'crm_country' );
        endforeach;

        // add details for links (possibly pag).
        $rankings['riders'] = $rankings_db;
        $rankings['discipline'] = $args['discipline'];
        $rankings['season'] = $args['season'];

        return $rankings;
    }

    /**
     * Get recent rankings season from db.
     *
     * @access public
     * @param string $discipline (default: 'cyclocross').
     * @return string
     */
    public function get_recent_season( $discipline = 'cyclocross' ) {
        global $wpdb;

        $season = $wpdb->get_var( "SELECT DISTINCT season FROM {$wpdb->prefix}crm_rider_rankings WHERE discipline = '$discipline' ORDER BY season DESC LIMIT 1" );

        return $season;
    }

    /**
     * Get all rankings seasons.
     *
     * @access public
     * @param string $discipline (default: 'cyclocross')
     * @return void
     */
    public function get_seasons( $discipline = 'cyclocross' ) {
        global $wpdb;

        $season = $wpdb->get_col( "SELECT DISTINCT season FROM {$wpdb->prefix}crm_rider_rankings WHERE discipline = '$discipline' ORDER BY season DESC" );

        return $season;
    }

    /**
     * Get rankings disciplines.
     *
     * @access public
     * @param string $season (default: '').
     * @return array
     */
    public function get_rankings_disciplines( $season = '' ) {
        global $wpdb;

        $where = '';

        if ( ! empty( $season ) ) :
            $where = "WHERE season = '$season'";
        endif;

        $disciplines = $wpdb->get_col( "SELECT DISTINCT discipline FROM {$wpdb->prefix}crm_rider_rankings $where ORDER BY discipline ASC" );

        return $disciplines;
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
