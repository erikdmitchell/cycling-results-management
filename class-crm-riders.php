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

    /**
     * get_riders function.
     *
     * @access public
     * @param string $args (default: '')
     * @return void
     */
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
        endforeach;

        return $riders;
    }

    /**
     * rider_last_race_result function.
     *
     * @access public
     * @param int $rider_id (default: 0)
     * @return void
     */
    public function rider_last_race_result( $rider_id = 0 ) {
        // get race ids via meta //
        $results_args_meta = array(
            'posts_per_page' => 1,
            'post_type' => 'races',
            'orderby' => 'meta_value',
            'meta_key' => '_race_date',
            'meta_query' => array(
                array(
                    'key' => '_rider_' . $rider_id,
                ),
            ),
            'fields' => 'ids',
        );
        $race_ids = get_posts( $results_args_meta );

        $last_race = uci_results_get_rider_results(
            array(
                'rider_id' => $rider_id,
                'race_ids' => $race_ids,
            )
        );

        if ( isset( $last_race[0] ) ) {
            return $last_race[0];
        }

        return;
    }

    public function get_rider_rankings( $rider_id = 0 ) {
        global $wpdb;
        
        $rankings = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}crm_rider_rankings WHERE rider_id = $rider_id");

        return $rankings;
    }

    /**
     * blank_rank function.
     *
     * @access protected
     * @param string $season (default: '')
     * @return void
     */
    protected function blank_rank( $season = '' ) {
        $rank = new stdClass();

        $rank->id = 0;
        $rank->points = 0;
        $rank->season = $season;
        $rank->rank = 0;
        $rank->week = 0;
        $rank->status = '';

        return $rank;
    }

    /**
     * get_twitter function.
     *
     * @access public
     * @param int $rider_id (default: 0)
     * @return void
     */
    public function get_twitter( $rider_id = 0 ) {
        return get_post_meta( $rider_id, '_rider_twitter', true );
    }

}
