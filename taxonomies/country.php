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
                'name'                       => __( 'Country', 'uci-results' ),
                'singular_name'              => _x( 'Country', 'taxonomy general name', 'uci-results' ),
                'search_items'               => __( 'Search countries', 'uci-results' ),
                'popular_items'              => __( 'Popular countries', 'uci-results' ),
                'all_items'                  => __( 'All countries', 'uci-results' ),
                'parent_item'                => __( 'Parent country', 'uci-results' ),
                'parent_item_colon'          => __( 'Parent country:', 'uci-results' ),
                'edit_item'                  => __( 'Edit country', 'uci-results' ),
                'update_item'                => __( 'Update country', 'uci-results' ),
                'add_new_item'               => __( 'New country', 'uci-results' ),
                'new_item_name'              => __( 'New country', 'uci-results' ),
                'separate_items_with_commas' => __( 'Separate countries with commas', 'uci-results' ),
                'add_or_remove_items'        => __( 'Add or remove countries', 'uci-results' ),
                'choose_from_most_used'      => __( 'Choose from the most used countries', 'uci-results' ),
                'not_found'                  => __( 'No countries found.', 'uci-results' ),
                'menu_name'                  => __( 'Countries', 'uci-results' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'country',
            'rest_controller_class' => 'UCI_REST_Terms_Controller',
        )
    );

}
add_action( 'init', 'country_init' );

