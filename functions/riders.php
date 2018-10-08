<?php

/**
 * Get rider results.
 *
 * @access public
 * @param string $args (default: '').
 * @return object
 */
function crm_get_rider_results( $args = '' ) {
    $default_args = array(
        'rider_id' => 0,
        // 'race_ids' => '',
        // 'seasons' => '',
        // 'places' => '',
        // 'race_classes' => '',
        // 'race_series' => '',
        // 'start_date' => '',
        // 'end_date' => '',
    );
    $args = wp_parse_args( $args, $default_args );

    if ( ! $args['rider_id'] ) {
        return false;
    }

    $results = array();

    /*
    if ( ! is_array( $race_ids ) && ! empty( $race_ids ) ) {
        $race_ids = explode( ',', $race_ids );
    }

    if ( ! is_array( $seasons ) && ! empty( $seasons ) ) {
        $seasons = explode( ',', $seasons );
    }

    if ( ! is_array( $places ) && ! empty( $places ) ) {
        $places = explode( ',', $places );
    }

    if ( ! is_array( $race_classes ) && ! empty( $race_classes ) ) {
        $race_classes = explode( ',', $race_classes );
    }

    if ( ! is_array( $race_series ) && ! empty( $race_series ) ) {
        $race_series = explode( ',', $race_series );
    }
    */

    // get race ids via meta.
    $results_args_meta = array(
        'posts_per_page' => -1,
        'post_type' => 'races',
        'meta_query' => array(
            array(
                'key' => '_rider_' . $args['rider_id'] . '_result_place',
                // 'compare' => 'LIKE',
            ),
        ),
        'fields' => 'ids',
    );

    /*
    // check specific race ids.
    if ( ! empty( $race_ids ) ) {
        $results_args_meta['post__in'] = $race_ids;
    }
    */

    /*
    // check specific seasons.
    if ( ! empty( $seasons ) ) {
        $results_args_meta['tax_query'][] = array(
            'taxonomy' => 'season',
            'field' => 'slug',
            'terms' => $seasons,
        );
    }
    */

    /*
    // check specific race_classes.
    if ( ! empty( $race_classes ) ) {
        $results_args_meta['tax_query'][] = array(
            'taxonomy' => 'race_class',
            'field' => 'slug',
            'terms' => $race_classes,
        );
    }
    */

    /*
    // check specific race_series.
    if ( ! empty( $race_series ) ) {
        $results_args_meta['tax_query'][] = array(
            'taxonomy' => 'series',
            'field' => 'slug',
            'terms' => $race_series,
        );
    }
    */

    /*
    // between two dates.
    if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
        $results_args_meta['meta_query'][] = array(
            'key' => '_race_date',
            'value' => array( $start_date, $end_date ),
            'compare' => 'BETWEEN',
            'type' => 'DATE',
        );
    }
    */

    $race_ids = get_posts( $results_args_meta );

    foreach ( $race_ids as $race_id ) :
        $result = array();

        $result['place'] = get_post_meta( $race_id, '_rider_' . $args['rider_id'] . '_result_place', true );
        $result['uci_points'] = get_post_meta( $race_id, '_rider_' . $args['rider_id'] . '_result_uci_points', true );
        $result['race_id'] = $race_id;
        $result['race_name'] = get_the_title( $race_id );
        $result['race_date'] = date( 'Y-m-d', strtotime( get_post_meta( $race_id, '_race_start', true ) ) );
        $result['race_class'] = crm_get_first_term( $race_id, 'race_class' );
        $result['race_season'] = crm_get_first_term( $race_id, 'season' );

        $results[] = $result;
    endforeach;

    return $results;
}

/**
 * Rider URL.
 *
 * @access public
 * @param string $slug (default: '').
 * @return url
 */
function crm_rider_url( $slug = '' ) {
    if ( ! is_numeric( $slug ) ) :
        $post = get_page_by_path( $slug, OBJECT, 'riders' );
        $post_id = $post->ID;
    else :
        $post_id = $slug;
    endif;

    echo get_permalink( $post_id );
}

/**
 * Riders URL.
 *
 * @access public
 * @param string $slug (default: '').
 * @return url
 */
function crm_riders_url( $slug = '' ) {
    echo site_url( '/riders' );
}

/**
 * Add rider.
 *
 * @access public
 * @param string $name (default: '').
 * @param string $country (default: '').
 * @return integer
 */
function crm_results_add_rider( $name = '', $country = '' ) {
    if ( empty( $name ) ) {
        return 0;
    }

    $rider_id = crm_search_rider( $name );

    // check if we have a rider id, otherwise create one //
    if ( ! $rider_id ) :
        $rider_insert = array(
            'post_title' => $name,
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'riders',
            'post_name' => sanitize_title_with_dashes( $name ),
        );

        $rider_id = wp_insert_post( $rider_insert );

        wp_set_object_terms( $rider_id, $country, 'crm_country', false );
    endif;

    return $rider_id;
}

/**
 * Search for a rider.
 *
 * @access public
 * @param string $name (default: '').
 * @return boolean
 */
function crm_search_rider( $name = '' ) {
    $rider = get_page_by_title( $name, OBJECT, 'riders' );

    if ( $rider === null || empty( $rider->ID ) ) :
        $posts = get_posts(
            array(
                'post_per_page' => 1,
                'post_type' => 'riders',
                's' => $name,
                'fields' => 'ids',
            )
        );

        if ( count( $posts ) && isset( $posts[0] ) ) :
            return $posts[0];
        endif;
    else :
        return $rider->ID;
    endif;

    return false;
}

/**
 * Get rider rankings.
 *
 * @access public
 * @param string $args (default: '').
 * @return object
 */
function crm_get_rider_rankings( $args = '' ) {
    return cycling_results_management()->riders->get_riders_rankings($args);
}

/**
 * Get rider name by ID.
 *
 * @access public
 * @param int $rider_id (default: 0).
 * @return string
 */
function crm_get_rider_name( $rider_id = 0 ) {
    return get_the_title( $rider_id );
}

/**
 * Get riders rankings seasons.
 * 
 * @access public
 * @param string $discipline (default: 'cyclocross')
 * @return array
 */
function crm_riders_rankings_seasons( $discipline = 'cyclocross' ) {
    return cycling_results_management()->riders->get_seasons($discipline);
}

function crm_riders_rankings_disciplines( $season = '' ) {
    return cycling_results_management()->riders->get_rankings_disciplines($season);
}
