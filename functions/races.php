<?php

/**
 * Get races.
 *
 * @access public
 * @param string $args (default: '').
 * @return object
 */
function crm_get_races( $args = '' ) {
    $default_args = array(
        'id' => '',
        'per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => '_race_date',
        'order' => 'DESC',
        'results' => false,
        'offset' => '',
    );
    $args = wp_parse_args( $args, $default_args );

    extract( $args );

    $post_args = array(
        'posts_per_page' => $per_page,
        'include' => $id,
        'post_type' => 'races',
        'meta_query'      => array(
            'relation'    => 'OR',
            '_race_date' => array(
                'key'     => '_race_date',
                'compare' => 'EXISTS',
            ),
            '_race_start' => array(
                'key'     => '_race_start',
                'compare' => 'EXISTS',
            ),
        ),
    );

    $races = get_posts( $post_args );

    foreach ( $races as $race ) :
        $race = crm_race_details( $race );

        if ( $results ) {
            $race->results = crm_results_get_race_results( $race->ID );
        }
    endforeach;

    // check for single race //
    if ( 1 == count( $races ) ) {
        $races = $races[0];
    }

    return $races;
}

/**
 * Get race details.
 *
 * @access public
 * @param string $race (default: '').
 * @return object
 */
function crm_race_details( $race = '' ) {
    $race->race_date = get_post_meta( $race->ID, '_race_start', true );
    $race->nat = crm_race_country( $race->ID );
    $race->class = crm_race_class( $race->ID );
    $race->season = crm_race_season( $race->ID );
    $race->series = crm_race_series( $race->ID );

    return $race;
}

/**
 * Get race country.
 * 
 * @access public
 * @param int $race_id (default: 0).
 * @return string
 */
function crm_race_country( $race_id = 0 ) {
    $countries = wp_get_post_terms( $race_id, 'country', array( 'fields' => 'names' ) );

    if ( isset( $countries[0] ) ) :
        $country = $countries[0];
    else :
        $country = '';
    endif;

    return $country;
}

/**
 * Get race class.
 *
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function crm_race_class( $race_id = 0 ) {
    $classes = wp_get_post_terms( $race_id, 'race_class', array( 'fields' => 'names' ) );

    if ( isset( $classes[0] ) ) :
        $class = $classes[0];
    else :
        $class = '';
    endif;

    return $class;
}

/**
 * Get race season.
 * 
 * @access public
 * @param int $race_id (default: 0).
 * @return string
 */
function crm_race_season( $race_id = 0 ) {
    $seasons = wp_get_post_terms( $race_id, 'season', array( 'fields' => 'names' ) );

    if ( isset( $seasons[0] ) ) :
        $season = $seasons[0];
    else :
        $season = '';
    endif;

    return $season;
}

/**
 * Get race discipline.
 * 
 * @access public
 * @param int $race_id (default: 0).
 * @return string
 */
function crm_race_discipline( $race_id = 0 ) {
    $disciplines = wp_get_post_terms( $race_id, 'discipline', array( 'fields' => 'names' ) );

    if ( isset( $disciplines[0] ) ) :
        $discipline = $disciplines[0];
    else :
        $discipline = '';
    endif;

    return $discipline;
}

/**
 * Get race series.
 * 
 * @access public
 * @param int $race_id (default: 0).
 * @return string
 */
function crm_race_series( $race_id = 0 ) {
    $series_arr = wp_get_post_terms( $race_id, 'series', array( 'fields' => 'names' ) );

    if ( isset( $series_arr[0] ) ) :
        $series = $series_arr[0];
    else :
        $series = '';
    endif;

    return $series;
}

/**
 * Get race results.
 * 
 * @access public
 * @param int $race_id (default: 0).
 * @param string $format (default: 'array').
 * @return object
 */
function crm_results_get_race_results( $race_id = 0, $format = 'array' ) {
    $riders = array();
    $rider_ids = crm_race_results_rider_ids( $race_id );
    $cols = crm_race_results_columns( $race_id );

    // add rider details //
    foreach ( $rider_ids as $id ) :
        $post = get_post( $id );

        $country = wp_get_post_terms( $id, 'country', array( 'fields' => 'names' ) );

        if ( isset( $country[0] ) ) :
            $nat = $country[0];
        else :
            $nat = '';
        endif;

        $arr = array(
            'ID' => $id,
            'name' => $post->post_title,
            'slug' => $post->post_name,
            'nat' => $nat,
        );

        // add results cols //
        foreach ( $cols as $col ) :
            $arr[ $col ] = get_post_meta( $race_id, '_rider_' . $id . '_' . $col, true );
        endforeach;

        $riders[] = $arr;
    endforeach;

    if ( $format == 'object' ) {
        $riders = crm_array_to_object( $riders );
    }

    return $riders;
}

/**
 * Get race columns.
 * 
 * @access public
 * @param int $race_id (default: 0).
 * @return array
 */
function crm_race_results_columns( $race_id = 0 ) {
    global $wpdb;

    $meta_keys = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta WHERE post_id = $race_id AND meta_key LIKE '_rider_%'" );
    $cols = array();

    foreach ( $meta_keys as $meta_key ) :
        $mk_arr = preg_split( '/[0-9]+/', $meta_key );
        $cols[] = ltrim( array_pop( $mk_arr ), '_' );
    endforeach;

    $cols = array_values( array_unique( $cols ) );

    return $cols;
}

/**
 * Race results rider ids.
 * 
 * @access public
 * @param int $race_id (default: 0).
 * @return object
 */
function crm_race_results_rider_ids( $race_id = 0 ) {
    global $wpdb;

    $ids = $wpdb->get_col( "SELECT REPLACE (REPLACE (meta_key, '_rider_', ''), '_result_place', '') AS id FROM $wpdb->postmeta WHERE post_id = $race_id AND meta_key LIKE '_rider_%_result_place'" );

    return $ids;
}

function crm_get_related_races( $race_id = 0 ) { // USED
    global $wpdb;

    $related_races = array();
    $related_race_id = crm_get_related_race_id( $race_id );

    if ( ! $related_race_id ) {
        return array();
    }

    $related_races_ids = crm_get_related_races_ids( $race_id );

    if ( is_wp_error( $related_races_ids ) || $related_races_ids === null ) {
        return false;
    }

    $related_races = get_posts(
        array(
            'include' => $related_races_ids,
            'post_type' => 'races',
            'orderby' => 'meta_value',
            'meta_key' => '_race_date',
        )
    );

    // append some meta //
    foreach ( $related_races as $race ) :
        $race->race_date = get_post_meta( $race->ID, '_race_date', true );
    endforeach;

    return $related_races;
}

function crm_get_related_races_ids( $race_id = 0 ) { // USED
    global $wpdb;

    $related_race_id = crm_get_related_race_id( $race_id );

    if ( ! $related_race_id ) {
        return array();
    }

    $related_races_ids = $wpdb->get_col( "SELECT race_id FROM {$wpdb->prefix}crm_related_races WHERE related_race_id = $related_race_id" );

    if ( is_wp_error( $related_races_ids ) || $related_races_ids === null ) {
        return false;
    }

    return $related_races_ids;
}

function crm_get_related_race_id( $race_id = 0 ) { // USED
    return get_post_meta( $race_id, '_race_related', true );
}

/**
 * Race URL.
 *
 * @access public
 * @param string $slug (default: '').
 * @return url
 */
function crm_race_url( $slug = '' ) {
    if ( ! is_numeric( $slug ) ) :
        $post = get_page_by_path( $slug, OBJECT, 'races' );
        $post_id = $post->ID;
    else :
        $post_id = $slug;
    endif;

    echo get_permalink( $post_id );
}

/**
 * Races URL.
 *
 * @access public
 * @return url
 */
function crm_races_url() {
    echo site_url( '/races' );
}

/**
 * CRM get race discipline.
 *
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function crm_get_race_discipline( $race_id = 0 ) {
    $disciplines = wp_get_post_terms( $race_id, 'discipline' );

    if ( isset( $disciplines[0] ) ) {
        return $disciplines[0]->slug;
    }

    return;
}

/**
 * Get race season.
 *
 * @access public
 * @param int $race_id (default: 0).
 * @return string
 */
function crm_get_race_season( $race_id = 0 ) {
    $seasons = wp_get_post_terms( $race_id, 'season' );

    if ( isset( $seasons[0] ) ) {
        return $seasons[0]->slug;
    }

    return;
}

