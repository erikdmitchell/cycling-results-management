<?php

/**
 * Season taxonomy.
 *
 * @access public
 * @return void
 */
function season_init() {
    register_taxonomy(
        'season', array( 'races' ), array(
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
                'name'                       => __( 'Season', 'crm' ),
                'singular_name'              => _x( 'Season', 'taxonomy general name', 'crm' ),
                'search_items'               => __( 'Search season', 'crm' ),
                'popular_items'              => __( 'Popular season', 'crm' ),
                'all_items'                  => __( 'All season', 'crm' ),
                'parent_item'                => __( 'Parent season', 'crm' ),
                'parent_item_colon'          => __( 'Parent season:', 'crm' ),
                'edit_item'                  => __( 'Edit season', 'crm' ),
                'update_item'                => __( 'Update season', 'crm' ),
                'add_new_item'               => __( 'New season', 'crm' ),
                'new_item_name'              => __( 'New season', 'crm' ),
                'separate_items_with_commas' => __( 'Separate season with commas', 'crm' ),
                'add_or_remove_items'        => __( 'Add or remove season', 'crm' ),
                'choose_from_most_used'      => __( 'Choose from the most used season', 'crm' ),
                'not_found'                  => __( 'No season found.', 'crm' ),
                'menu_name'                  => __( 'Season', 'crm' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'season',
            'rest_controller_class' => 'UCI_REST_Terms_Controller',
        )
    );

}
add_action( 'init', 'season_init' );

