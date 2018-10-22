<?php

/**
 * Registers the `crm_country` taxonomy,
 * for use with 'riders', 'races'.
 */


/**
 * CRM country taxonomy.
 *
 * Registers the `crm_country` taxonomy, for use with 'riders', 'races'.
 *
 * @access public
 * @return void
 */
function crm_country_init() {
    register_taxonomy(
        'crm_country', array( 'riders', 'races' ), array(
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
                'name'                       => __( 'Countries', 'crm' ),
                'singular_name'              => _x( 'Country', 'taxonomy general name', 'crm' ),
                'search_items'               => __( 'Search Countries', 'crm' ),
                'popular_items'              => __( 'Popular Countries', 'crm' ),
                'all_items'                  => __( 'All Countries', 'crm' ),
                'parent_item'                => __( 'Parent Country', 'crm' ),
                'parent_item_colon'          => __( 'Parent Country:', 'crm' ),
                'edit_item'                  => __( 'Edit Country', 'crm' ),
                'update_item'                => __( 'Update Country', 'crm' ),
                'view_item'                  => __( 'View Country', 'crm' ),
                'add_new_item'               => __( 'Add New Country', 'crm' ),
                'new_item_name'              => __( 'New Country', 'crm' ),
                'separate_items_with_commas' => __( 'Separate Countries with commas', 'crm' ),
                'add_or_remove_items'        => __( 'Add or remove Countries', 'crm' ),
                'choose_from_most_used'      => __( 'Choose from the most used Countries', 'crm' ),
                'not_found'                  => __( 'No Countries found.', 'crm' ),
                'no_terms'                   => __( 'No Countries', 'crm' ),
                'menu_name'                  => __( 'Countries', 'crm' ),
                'items_list_navigation'      => __( 'Countries list navigation', 'crm' ),
                'items_list'                 => __( 'Countries list', 'crm' ),
                'most_used'                  => _x( 'Most Used', 'crm_country', 'crm' ),
                'back_to_items'              => __( '&larr; Back to Countries', 'crm' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'crm_country',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        )
    );

}
add_action( 'init', 'crm_country_init' );

/**
 * Sets the post updated messages for the `crm_country` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `crm_country` taxonomy.
 */
function crm_country_updated_messages( $messages ) {

    $messages['crm_country'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => __( 'Country added.', 'crm' ),
        2 => __( 'Country deleted.', 'crm' ),
        3 => __( 'Country updated.', 'crm' ),
        4 => __( 'Country not added.', 'crm' ),
        5 => __( 'Country not updated.', 'crm' ),
        6 => __( 'Countries deleted.', 'crm' ),
    );

    return $messages;
}
add_filter( 'term_updated_messages', 'crm_country_updated_messages' );
