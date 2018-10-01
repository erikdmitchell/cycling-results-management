<?php

/**
 * CRM_Riders class.
 *
 * @since 1.0.0
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
    // check this function -- NOT USED
    public function get_riders( $args = '' ) {
        global $wpdb;

        $default_args = array(
            'per_page' => -1,
            'rider_ids' => '',
            'results' => false,
            'last_result' => false,
            'race_ids' => '',
            'results_season' => '',
            'ranking' => false,
            'stats' => false,
            'nat' => '',
            'orderby' => 'title',
            'order' => 'ASC',
            'page' => '',
        );
        $args = wp_parse_args( $args, $default_args );
        $riders = array();

        extract( $args );

        // setup rider ids //
        if ( ! is_array( $rider_ids ) && ! empty( $rider_ids ) ) :
            $rider_ids = explode( ',', $rider_ids );
        else :
            if ( $page ) :
                $offset = ( $page - 1 ) * $per_page;
            else :
                $offset = '';
            endif;

            $riders_args = array(
                'posts_per_page' => $per_page,
                'post_type' => 'riders',
                'orderby' => $orderby,
                'order' => $order,
                'fields' => 'ids',
                'offset' => $offset,
            );

            // check specific nat //
            if ( ! empty( $nat ) ) :
                $riders_args['tax_query'][] = array(
                    'taxonomy' => 'country',
                    'field' => 'slug',
                    'terms' => $nat,
                );
            endif;

            $rider_ids = get_posts( $riders_args );
        endif;

        if ( empty( $rider_ids ) ) {
            return;
        }

        foreach ( $rider_ids as $rider_id ) :
            /*
            $riders[] = $this->get_rider(
                array(
                    'rider_id' => $rider_id,
                    'results' => $results,
                    'last_result' => $last_result,
                    'race_ids' => $race_ids,
                    'results_season' => $results_season,
                    'ranking' => $ranking,
                    'stats' => $stats,
                )
            );
            */
        endforeach;

        return $riders;
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
