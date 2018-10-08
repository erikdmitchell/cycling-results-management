<?php
/**
 * set_custom_edit_riders_columns function.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function set_custom_edit_riders_columns( $columns ) {
    unset( $columns['date'] );

    $columns['country'] = __( 'Country', 'crm' );
    return $columns;
}
add_filter( 'manage_riders_posts_columns', 'set_custom_edit_riders_columns' );

/**
 * custom_riders_columns function.
 *
 * @access public
 * @param mixed $column
 * @param mixed $post_id
 * @return void
 */
function custom_riders_columns( $column, $post_id ) {
    switch ( $column ) :
        case 'country':
            $terms = get_the_term_list( $post_id, 'crm_country', '', ',', '' );

            if ( is_string( $terms ) ) :
                echo $terms;
            else :
                _e( 'Unable to get country', 'crm' );
            endif;
            break;
    endswitch;
}
add_action( 'manage_riders_posts_custom_column', 'custom_riders_columns', 10, 2 );

/**
 * set_custom_edit_races_columns function.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function set_custom_edit_races_columns( $columns ) {
    unset( $columns['date'] );

    $columns['race_date'] = __( 'Date', 'crm' );
    $columns['country'] = __( 'Country', 'crm' );
    $columns['class'] = __( 'Class', 'crm' );
    $columns['season'] = __( 'Season', 'crm' );
    $columns['series'] = __( 'Series', 'crm' );

    return $columns;
}
add_filter( 'manage_races_posts_columns', 'set_custom_edit_races_columns' );

/**
 * custom_races_columns function.
 *
 * @access public
 * @param mixed $column
 * @param mixed $post_id
 * @return void
 */
function custom_races_columns( $column, $post_id ) {
    switch ( $column ) :
        case 'country':
            $terms = get_the_term_list( $post_id, 'crm_country', '', ',', '' );

            if ( is_string( $terms ) ) :
                echo $terms;
            else :
                _e( 'Unable to get country', 'crm' );
            endif;
            break;
        case 'class':
            $terms = get_the_term_list( $post_id, 'race_class', '', ',', '' );

            if ( is_string( $terms ) ) :
                echo $terms;
            else :
                _e( 'Unable to get class', 'crm' );
            endif;
            break;
        case 'season':
            $terms = get_the_term_list( $post_id, 'season', '', ',', '' );

            if ( is_string( $terms ) ) :
                echo $terms;
            else :
                _e( 'Unable to get season', 'crm' );
            endif;
            break;
        case 'series':
            $terms = get_the_term_list( $post_id, 'series', '', ',', '' );

            if ( is_string( $terms ) ) :
                echo $terms;
            else :
                _e( '', 'crm' );
            endif;
            break;
        case 'race_date':
            echo get_post_meta( $post_id, '_race_date', true );
            break;
    endswitch;
}
add_action( 'manage_races_posts_custom_column', 'custom_races_columns', 10, 2 );

