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
        $post->nat = crm_get_first_term( $post->ID, 'crm_country' );
        $post->rankings = cycling_results_management()->riders->get_rider_rankings( $post->ID );
        $post->results = crm_get_rider_results(
            array(
                'rider_id' => $post->ID,
            )
        );
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
        $post->results = crm_results_get_race_results( $post->ID );
    endforeach;

    return $posts;
}
add_action( 'the_posts', 'crm_races_post_details', 10, 2 );

