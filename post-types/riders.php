<?php

/**
 * Riders post type.
 * 
 * @access public
 * @return void
 */
function riders_init() {
    register_post_type(
        'riders', array(
            'labels'            => array(
                'name'                => __( 'Riders', 'crm' ),
                'singular_name'       => __( 'Riders', 'crm' ),
                'all_items'           => __( 'All Riders', 'crm' ),
                'new_item'            => __( 'New riders', 'crm' ),
                'add_new'             => __( 'Add New', 'crm' ),
                'add_new_item'        => __( 'Add New riders', 'crm' ),
                'edit_item'           => __( 'Edit riders', 'crm' ),
                'view_item'           => __( 'View riders', 'crm' ),
                'search_items'        => __( 'Search riders', 'crm' ),
                'not_found'           => __( 'No riders found', 'crm' ),
                'not_found_in_trash'  => __( 'No riders found in trash', 'crm' ),
                'parent_item_colon'   => __( 'Parent riders', 'crm' ),
                'menu_name'           => __( 'Riders', 'crm' ),
            ),
            'public'            => true,
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'show_in_menu'      => false,
            'supports'          => array( 'title' ),
            'has_archive'       => true,
            'rewrite'           => true,
            'query_var'         => true,
            'menu_icon'         => 'dashicons-admin-post',
            'show_in_rest'      => true,
            'rest_base'         => 'riders',
        )
    );

}
add_action( 'init', 'riders_init' );

/**
 * Riders post type messages.
 * 
 * @access public
 * @param mixed $messages
 * @return void
 */
function riders_updated_messages( $messages ) {
    global $post;

    $permalink = get_permalink( $post );

    $messages['riders'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf( __( 'Riders updated. <a target="_blank" href="%s">View riders</a>', 'crm' ), esc_url( $permalink ) ),
        2 => __( 'Custom field updated.', 'crm' ),
        3 => __( 'Custom field deleted.', 'crm' ),
        4 => __( 'Riders updated.', 'crm' ),
        /* translators: %s: date and time of the revision */
        5 => isset( $_GET['revision'] ) ? sprintf( __( 'Riders restored to revision from %s', 'crm' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __( 'Riders published. <a href="%s">View riders</a>', 'crm' ), esc_url( $permalink ) ),
        7 => __( 'Riders saved.', 'crm' ),
        8 => sprintf( __( 'Riders submitted. <a target="_blank" href="%s">Preview riders</a>', 'crm' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        9 => sprintf(
            __( 'Riders scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview riders</a>', 'crm' ),
            // translators: Publish box date format, see http://php.net/date
            date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink )
        ),
        10 => sprintf( __( 'Riders draft updated. <a target="_blank" href="%s">Preview riders</a>', 'crm' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'riders_updated_messages' );

