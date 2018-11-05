<?php

/**
 * Series taxonomy.
 *
 * @access public
 * @return void
 */
function series_init() {
    register_taxonomy(
        'series', array( 'races' ), array(
            'hierarchical'      => true,
            'public'            => true,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_admin_column' => false,
            'query_var'         => true,
            'rewrite'           => true,
            'capabilities'      => array(
                'manage_terms'  => 'edit_posts',
                'edit_terms'    => 'edit_posts',
                'delete_terms'  => 'edit_posts',
                'assign_terms'  => 'edit_posts',
            ),
            'labels'            => array(
                'name'                       => __( 'Series', 'crm' ),
                'singular_name'              => _x( 'Series', 'taxonomy general name', 'crm' ),
                'search_items'               => __( 'Search series', 'crm' ),
                'popular_items'              => __( 'Popular series', 'crm' ),
                'all_items'                  => __( 'All series', 'crm' ),
                'parent_item'                => __( 'Parent series', 'crm' ),
                'parent_item_colon'          => __( 'Parent series:', 'crm' ),
                'edit_item'                  => __( 'Edit series', 'crm' ),
                'update_item'                => __( 'Update series', 'crm' ),
                'add_new_item'               => __( 'New series', 'crm' ),
                'new_item_name'              => __( 'New series', 'crm' ),
                'separate_items_with_commas' => __( 'Separate series with commas', 'crm' ),
                'add_or_remove_items'        => __( 'Add or remove series', 'crm' ),
                'choose_from_most_used'      => __( 'Choose from the most used series', 'crm' ),
                'not_found'                  => __( 'No series found.', 'crm' ),
                'menu_name'                  => __( 'Series', 'crm' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'series',
            'rest_controller_class' => 'UCI_REST_Terms_Controller',
        )
    );

}
add_action( 'init', 'series_init' );

