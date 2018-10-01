<?php

/**
 * Loades templates from plugins, themes or this plugin.
 *
 * @access public
 * @param mixed $template string.
 * @return string
 */
function crm_template_loader( $template ) {
    global $wp_query, $post;

    $located = false;
    $template_slug = '';

    if ( is_archive() ) :
        $obj = get_queried_object();

        if ( isset( $obj->taxonomy ) ) :
            $template_slug = 'country';
        endif;
    endif;

    if ( isset( $post->post_type ) ) {
        $template_slug = $post->post_type;
    }

    if ( is_single() ) {
        $template_slug = "$template_slug-single";
    }

    if ( get_query_var( 'rankings_discipline' ) && get_query_var( 'rankings_date' ) ) {
        $template_slug = 'uci-rankings';
    }

    if ( get_query_var( 'crm_rankings_discipline' ) && get_query_var( 'crm_rankings_season' ) ) {
        $template_slug = 'crm-rankings';
    }

    // check theme(s), then plugin.
    if ( file_exists( get_stylesheet_directory() . '/crm/' . $template_slug . '.php' ) ) :
        $located = get_stylesheet_directory() . '/crm/' . $template_slug . '.php';
    elseif ( file_exists( get_template_directory() . '/crm/' . $template_slug . '.php' ) ) :
        $located = get_template_directory() . '/crm/' . $template_slug . '.php';
    elseif ( file_exists( CRM_PATH . 'templates/' . $template_slug . '.php' ) ) :
        $located = CRM_PATH . 'templates/' . $template_slug . '.php';
    endif;

    // we found a template.
    if ( $located ) {
        $template = $located;
    }

    return $template;
}
add_filter( 'template_include', 'crm_template_loader' );

/**
 * Gets a template and allows params to be passed.
 *
 * @access public
 * @param string $template_name (default: '').
 * @param string $atts (default: '').
 * @return html
 */
function crm_get_template_part( $template_name = '', $atts = '' ) {
    if ( empty( $template_name ) ) {
        return false;
    }

    ob_start();

    do_action( 'crm_get_template_part' . $template_name );

    if ( file_exists( get_stylesheet_directory() . '/crm/' . $template_name . '.php' ) ) :
        include( get_stylesheet_directory() . '/crm/' . $template_name . '.php' );
    elseif ( file_exists( get_template_directory() . '/crm/' . $template_name . '.php' ) ) :
        include( get_template_directory() . '/crm/' . $template_name . '.php' );
    else :
        include( CRM_PATH . 'templates/' . $template_name . '.php' );
    endif;

    $html = ob_get_contents();

    ob_end_clean();

    return $html;
}

/**
 * Converts an array to an object.
 *
 * @access public
 * @param mixed $array array.
 * @return object
 */
function crm_array_to_object( $array ) {
    $object = new stdClass();
    foreach ( $array as $key => $value ) {
        if ( is_array( $value ) ) {
            $value = crm_array_to_object( $value );
        }
        $object->$key = $value;
    }
    return $object;
}

/**
 * Parse args.
 *
 * @access public
 * @param mixed $a array.
 * @param mixed $b array.
 * @return array
 */
function crm_parse_args( &$a, $b ) {
    $a = (array) $a;
    $b = (array) $b;
    $result = $b;
    foreach ( $a as $k => &$v ) {
        if ( is_array( $v ) && isset( $result[ $k ] ) ) {
            $result[ $k ] = crm_parse_args( $v, $result[ $k ] );
        } else {
            $result[ $k ] = $v;
        }
    }

    return $result;
}


