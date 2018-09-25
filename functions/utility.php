<?php

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
 * uci_get_template_part function.
 *
 * @access public
 * @param string $template_name (default: '')
 * @param string $atts (default: '')
 * @return void
 */
function uci_get_template_part( $template_name = '', $atts = '' ) {
    if ( empty( $template_name ) ) {
        return false;
    }

    ob_start();

    do_action( 'uci_results_get_template_part' . $template_name );

    if ( file_exists( get_stylesheet_directory() . '/uci-results/' . $template_name . '.php' ) ) :
        include( get_stylesheet_directory() . '/uci-results/' . $template_name . '.php' );
    elseif ( file_exists( get_template_directory() . '/uci-results/' . $template_name . '.php' ) ) :
        include( get_template_directory() . '/uci-results/' . $template_name . '.php' );
    else :
        include( UCI_RESULTS_PATH . 'templates/' . $template_name . '.php' );
    endif;

    $html = ob_get_contents();

    ob_end_clean();

    return $html;
}

/**
 * array_to_object function.
 *
 * @access public
 * @param mixed $array
 * @return void
 */
function array_to_object( $array ) {
    $object = new stdClass();
    foreach ( $array as $key => $value ) {
        if ( is_array( $value ) ) {
            $value = array_to_object( $value );
        }
        $object->$key = $value;
    }
    return $object;
}

/**
 * uci_results_format_size function.
 *
 * @access public
 * @param string $size (default: '')
 * @return void
 */
function uci_results_format_size( $size = '' ) {
    $sizes = array( ' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB' );

    if ( $size == 0 ) :
        return( 'n/a' );
    else :
        return ( round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . $sizes[ $i ] );
    endif;
}

/**
 * crm_display_total function.
 *
 * @access public
 * @param array $arr (default: array())
 * @return void
 */
function crm_display_total( $arr = array() ) {
    if ( ! $arr || empty( $arr ) ) :
        echo 0;
    else :
        echo count( $arr );
    endif;
}

/**
 * crm_parse_args function.
 *
 * @access public
 * @param mixed &$a
 * @param mixed $b
 * @return void
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


