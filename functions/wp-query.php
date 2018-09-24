<?php

function riders_the_posts_details( $posts, $query ) {
    global $uci_riders;

    if ( $query->query_vars['post_type'] != 'riders' ) {
        return $posts;
    }

    foreach ( $posts as $post ) :
        $post->nat = uci_get_first_term( $post->ID, 'country' );
        $post->tiwtter = get_post_meta( $post->ID, '_rider_twitter', true );

        if ( $query->get( 'ranking' ) ) :
            $post->rank = $uci_riders->get_rider_rank( $post->ID );
        endif;
    endforeach;

    return $posts;
}
add_action( 'the_posts', 'riders_the_posts_details', 10, 2 );

/**
 * Append race details to races post
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

