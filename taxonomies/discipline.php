<?php
function discipline_init() {
    register_taxonomy(
        'discipline', array( 'races' ), array(
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
                'name'                       => __( 'Disciplines', 'crm' ),
                'singular_name'              => _x( 'Discipline', 'taxonomy general name', 'crm' ),
                'search_items'               => __( 'Search Disciplines', 'crm' ),
                'popular_items'              => __( 'Popular Disciplines', 'crm' ),
                'all_items'                  => __( 'All Disciplines', 'crm' ),
                'parent_item'                => __( 'Parent Discipline', 'crm' ),
                'parent_item_colon'          => __( 'Parent Discipline:', 'crm' ),
                'edit_item'                  => __( 'Edit Discipline', 'crm' ),
                'update_item'                => __( 'Update Discipline', 'crm' ),
                'add_new_item'               => __( 'New Discipline', 'crm' ),
                'new_item_name'              => __( 'New Discipline', 'crm' ),
                'separate_items_with_commas' => __( 'Separate Disciplines with commas', 'crm' ),
                'add_or_remove_items'        => __( 'Add or remove Disciplines', 'crm' ),
                'choose_from_most_used'      => __( 'Choose from the most used Disciplines', 'crm' ),
                'not_found'                  => __( 'No Disciplines found.', 'crm' ),
                'menu_name'                  => __( 'Disciplines', 'crm' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'discipline',
            'rest_controller_class' => 'UCI_REST_Terms_Controller',
        )
    );

}
add_action( 'init', 'discipline_init' );

