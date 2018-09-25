<?php
/**
 * uci_get_first_term function.
 *
 * @access public
 * @param int    $post_id (default: 0)
 * @param string $taxonomy (default: '')
 * @return void
 */
function uci_get_first_term( $post_id = 0, $taxonomy = '' ) {
    $terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'names' ) );

    if ( is_wp_error( $terms ) ) {
        return false;
    }

    if ( isset( $terms[0] ) ) {
        return $terms[0];
    }

    return false;
}

/**
 * uci_get_country_dropdown function.
 *
 * @access public
 * @param string $name (default: 'country')
 * @param string $selected (default: '')
 * @return void
 */
function uci_get_country_dropdown( $name = 'country', $selected = '' ) {
    $html = null;
    $countries = get_terms(
        array(
            'taxonomy' => 'crm_country',
            'hide_empty' => false,
        )
    );

    $html .= '<select id="' . $name . '" name="' . $name . '" class="' . $name . '">';
        $html .= '<option value="0">-- Select Country --</option>';
    foreach ( $countries as $country ) :
        $html .= '<option value="' . $country->slug . '" ' . selected( $selected, $country->slug, false ) . '>' . $country->name . '</option>';
            endforeach;
    $html .= '</select>';

    return $html;
}

/**
 * Get country URL
 *
 * @access public
 * @param string $slug (default: '')
 * @return void
 */
function crm_country_url( $slug = '' ) {
    $country = strtolower( $slug );

    echo site_url( 'crm_country/' . $country );
}

/**
 * crm_pagination function.
 *
 * @access public
 * @param string $numpages (default: '')
 * @param string $pagerange (default: '')
 * @param string $paged (default: '')
 * @return void
 */
function crm_pagination( $numpages = '', $pagerange = '', $paged = '' ) {
    global $paged;

    $html = null;

    if ( empty( $pagerange ) ) {
        $pagerange = 2;
    }

    if ( empty( $paged ) ) {
        $paged = 1;
    }

    if ( $numpages == '' ) :
        global $wp_query;

        $numpages = $wp_query->max_num_pages;

        if ( ! $numpages ) :
            $numpages = 1;
        endif;
    endif;

    $pagination_args = array(
        'base'            => get_pagenum_link( 1 ) . '%_%',
        'format'          => '?paged=%#%',
        'total'           => $numpages,
        'current'         => $paged,
        'mid_size'        => $pagerange,
        'prev_text'       => __( '&laquo;' ),
        'next_text'       => __( '&raquo;' ),
    );

    $paginate_links = paginate_links( $pagination_args );

    if ( $paginate_links ) :
        $html .= '<nav class="uci-pagination">';
            // $html.="<span class='page-numbers page-num'>Page " . $paged . " of " . $numpages . "</span> ";
            $html .= $paginate_links;
        $html .= '</nav>';
    endif;

    echo $html;
}

function crm_uci_rankings_url( $discipline = 'cyclocross', $date = '' ) {
    $url = site_url( 'uci-rankings/' . strtolower( $discipline ) . '/' . $date );

    echo $url;
}
