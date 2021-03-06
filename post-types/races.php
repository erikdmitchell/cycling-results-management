<?php

/**
 * Races post type.
 *
 * @access public
 * @return void
 */
function races_init() {
    register_post_type(
        'races', array(
            'labels'            => array(
                'name'                => __( 'Races', 'crm' ),
                'singular_name'       => __( 'Races', 'crm' ),
                'all_items'           => __( 'All Races', 'crm' ),
                'new_item'            => __( 'New races', 'crm' ),
                'add_new'             => __( 'Add New', 'crm' ),
                'add_new_item'        => __( 'Add New races', 'crm' ),
                'edit_item'           => __( 'Edit races', 'crm' ),
                'view_item'           => __( 'View races', 'crm' ),
                'search_items'        => __( 'Search races', 'crm' ),
                'not_found'           => __( 'No races found', 'crm' ),
                'not_found_in_trash'  => __( 'No races found in trash', 'crm' ),
                'parent_item_colon'   => __( 'Parent races', 'crm' ),
                'menu_name'           => __( 'Races', 'crm' ),
            ),
            'public'            => true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'show_in_menu'      => false,
            'supports'          => array( 'title', 'page-attributes' ),
            'has_archive'       => true,
            'rewrite'           => true,
            'query_var'         => true,
            'menu_icon'         => 'dashicons-admin-post',
            'show_in_rest'      => true,
            'rest_base'         => 'races',
        )
    );

}
add_action( 'init', 'races_init' );

/**
 * Race post type messages.
 *
 * @access public
 * @param mixed $messages
 * @return void
 */
function races_updated_messages( $messages ) {
    global $post;

    $permalink = get_permalink( $post );

    $messages['races'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf( __( 'Races updated. <a target="_blank" href="%s">View races</a>', 'crm' ), esc_url( $permalink ) ),
        2 => __( 'Custom field updated.', 'crm' ),
        3 => __( 'Custom field deleted.', 'crm' ),
        4 => __( 'Races updated.', 'crm' ),
        /* translators: %s: date and time of the revision */
        5 => isset( $_GET['revision'] ) ? sprintf( __( 'Races restored to revision from %s', 'crm' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __( 'Races published. <a href="%s">View races</a>', 'crm' ), esc_url( $permalink ) ),
        7 => __( 'Races saved.', 'crm' ),
        8 => sprintf( __( 'Races submitted. <a target="_blank" href="%s">Preview races</a>', 'crm' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        9 => sprintf(
            __( 'Races scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview races</a>', 'crm' ),
            // translators: Publish box date format, see http://php.net/date
            date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink )
        ),
        10 => sprintf( __( 'Races draft updated. <a target="_blank" href="%s">Preview races</a>', 'crm' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'races_updated_messages' );

