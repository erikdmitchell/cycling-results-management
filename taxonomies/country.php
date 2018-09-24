<?php
function country_init() {
    register_taxonomy(
        'country', array( 'riders', 'races' ), array(
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
                'name'                       => __( 'Country', 'crm' ),
                'singular_name'              => _x( 'Country', 'taxonomy general name', 'crm' ),
                'search_items'               => __( 'Search countries', 'crm' ),
                'popular_items'              => __( 'Popular countries', 'crm' ),
                'all_items'                  => __( 'All countries', 'crm' ),
                'parent_item'                => __( 'Parent country', 'crm' ),
                'parent_item_colon'          => __( 'Parent country:', 'crm' ),
                'edit_item'                  => __( 'Edit country', 'crm' ),
                'update_item'                => __( 'Update country', 'crm' ),
                'add_new_item'               => __( 'New country', 'crm' ),
                'new_item_name'              => __( 'New country', 'crm' ),
                'separate_items_with_commas' => __( 'Separate countries with commas', 'crm' ),
                'add_or_remove_items'        => __( 'Add or remove countries', 'crm' ),
                'choose_from_most_used'      => __( 'Choose from the most used countries', 'crm' ),
                'not_found'                  => __( 'No countries found.', 'crm' ),
                'menu_name'                  => __( 'Countries', 'crm' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'country',
        )
    );

}
add_action( 'init', 'country_init' );

