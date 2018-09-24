<?php

/**
 * Append riders details to riders post type.
 *
 * @access public
 * @param mixed $posts
 * @param mixed $query
 * @return void
 */
function crm_riders_posts_details( $posts, $query ) {
    global $uci_riders;

    if ( $query->query_vars['post_type'] != 'riders' ) {
        return $posts;
    }

    foreach ( $posts as $post ) :
        $post->nat = uci_get_first_term( $post->ID, 'crm_country' );
        $post->rank = cycling_results_management()->riders->get_rider_rank( $post->ID );
        $post->results = crm_get_rider_results(
            array(
                'rider_id' => $post->ID,
            )
        );
        /*
        $post->last_result = $uci_riders->rider_last_race_result( $rider_id );
        $post->rank = $uci_riders->get_rider_rank( $rider_id );
        $post->stats = uci_results_get_rider_stats( $rider_id );
        */
    endforeach;

    return $posts;
}
add_action( 'the_posts', 'crm_riders_posts_details', 10, 2 );

/**
 * Append race details to races post type.
 *
 * @access public
 * @param mixed $posts
 * @param mixed $query
 * @return void
 */
function crm_races_post_details( $posts, $query ) {
    global $uci_riders;

    if ( $query->query_vars['post_type'] != 'races' ) {
        return $posts;
    }

    foreach ( $posts as $post ) :
        $post = crm_race_details( $post );
        $post->results = uci_results_get_race_results( $post->ID );
    endforeach;

    return $posts;
}
add_action( 'the_posts', 'crm_races_post_details', 10, 2 );

