<?php
function race_class_init() {
    register_taxonomy(
        'race_class', array( 'races' ), array(
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
                'name'                       => __( 'Class', 'crm' ),
                'singular_name'              => _x( 'Class', 'taxonomy general name', 'crm' ),
                'search_items'               => __( 'Search classes', 'crm' ),
                'popular_items'              => __( 'Popular classes', 'crm' ),
                'all_items'                  => __( 'All classes', 'crm' ),
                'parent_item'                => __( 'Parent class', 'crm' ),
                'parent_item_colon'          => __( 'Parent class:', 'crm' ),
                'edit_item'                  => __( 'Edit class', 'crm' ),
                'update_item'                => __( 'Update class', 'crm' ),
                'add_new_item'               => __( 'New class', 'crm' ),
                'new_item_name'              => __( 'New class', 'crm' ),
                'separate_items_with_commas' => __( 'Separate classes with commas', 'crm' ),
                'add_or_remove_items'        => __( 'Add or remove classes', 'crm' ),
                'choose_from_most_used'      => __( 'Choose from the most used classes', 'crm' ),
                'not_found'                  => __( 'No classes found.', 'crm' ),
                'menu_name'                  => __( 'Class', 'crm' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'race_class',
            'rest_controller_class' => 'UCI_REST_Terms_Controller',
        )
    );

}
add_action( 'init', 'race_class_init' );

