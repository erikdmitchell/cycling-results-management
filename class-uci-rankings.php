<?php

class UCI_Rankings {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        global $wpdb;

        $this->table_name = $wpdb->prefix . 'crm_uci_rankings';
    }

    /**
     * get_rankings function.
     *
     * @access public
     * @param string $args (default: '')
     * @return void
     */
    public function get_rankings( $args = '' ) {
        global $wpdb;

        $default_args = array(
            'fields' => 'all',
            'order' => 'ASC',
            'order_by' => 'date',
            'group_by' => '',
            'date' => '',
            'discipline' => 'road',
            'limit' => -1,
        );
        $args = wp_parse_args( $args, $default_args );
        $where = array();

        extract( $args );

        // setup group by //
        if ( ! empty( $group_by ) ) :
            $group_by = "GROUP BY $group_by";
        endif;

        // setup fields //
        if ( $fields == 'all' ) {
            $fields = '*';
        }

        // setup where vars //
        if ( ! empty( $discipline ) ) :
            if ( ! is_numeric( $discipline ) ) :
                $term = get_term_by( 'slug', $discipline, 'discipline' );
                $discipline = $term->term_id;
            endif;

            $where[] = "discipline = '$discipline'";
        endif;

        if ( ! empty( $date ) ) :
            $where[] = "date = '$date'";
        else :
            $where[] = "date = '" . $wpdb->get_var( 'SELECT date FROM ' . $this->table_name . " WHERE discipline = $discipline ORDER BY date DESC LIMIT 1" ) . "'";
        endif;

        // clean where var //
        if ( ! empty( $where ) ) :
            $where = 'WHERE ' . implode( ' AND ', $where );
        else :
            $where = '';
        endif;

        // setup limit //
        if ( $limit >= 0 ) :
            $limit = " LIMIT $limit";
        else :
            $limit = '';
        endif;

        $db_results = $wpdb->get_results(
            "
			SELECT $fields FROM $this->table_name
			$where
			$group_by			
			ORDER BY $order_by $order
			$limit
		"
        );

        if ( is_wp_error( $db_results ) ) {
            return false;
        }

        return $db_results;
    }

    /**
     * get_rankings_dates function.
     *
     * @access public
     * @param int $discipline (default: 0)
     * @return void
     */
    public function get_rankings_dates( $discipline = 0 ) {
        global $wpdb;

        $select = 'date, t.name AS discipline';
        $join = ' INNER JOIN ' . $wpdb->terms . ' t ON ' . $this->table_name . '.discipline = t.term_id';
        $where = '';

        if ( $discipline ) :
            if ( ! is_numeric( $discipline ) ) :
                $term = get_term_by( 'slug', $discipline, 'discipline' );
                $discipline = $term->term_id;
            endif;

            $select = 'date';
            $join = '';
            $where = "WHERE discipline = $discipline";
        endif;

        $dates = $wpdb->get_results( "SELECT DISTINCT $select FROM " . $this->table_name . " $join $where" );

        return $dates;
    }

    /**
     * Get disciplines.
     *
     * @access public
     * @return void
     */
    public function get_disciplines() {
        return get_terms( 'discipline', array() );
    }

}
