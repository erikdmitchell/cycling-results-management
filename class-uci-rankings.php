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
     * is_ranks_updated function.
     *
     * @access public
     * @param string $date (default: '')
     * @return void
     */
    /*
    public function is_ranks_updated( $date = '' ) {
        if ( empty( $date ) ) {
            $date = date( 'Y-m-d' );
        }

        if ( $date <= $this->last_updated ) {
            return true;
        }

        return false;
    }
    */

    /**
     * get_rank function.
     *
     * @access public
     * @param int    $rider_id (default: 0)
     * @param string $discipline (default: 0)
     * @return void
     */
    public function get_rank( $rider_id = 0, $discipline = 0 ) {
        global $wpdb;

        if ( ! is_integer( $discipline ) ) :
            $_discipline = get_term_by( 'slug', $discipline, 'discipline' );

            $discipline = $_discipline->term_id;
        endif;

        $rank = $wpdb->get_row( 'SELECT rank, points, date, discipline FROM ' . $this->table_name . " WHERE rider_id = $rider_id AND discipline = $discipline ORDER BY date ASC LIMIT 1" );

        if ( $rank === null ) :
            $rank = new stdClass();

            $rank->rank = 0;
            $rank->points = 0;
            $rank->date = '';
            $rank->discipline = $discipline;
        endif;

        return $rank;
    }

    /**
     * max_rank function.
     *
     * @access public
     * @param string $date (default: '')
     * @param string $discipline (default: '')
     * @return void
     */
    /*
    public function max_rank( $date = '', $discipline = '' ) {
        global $wpdb;

        return $wpdb->get_var( 'SELECT MAX(rank) FROM ' . $this->table_name . ' ORDER BY date ASC' );
    }
    */

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
     * disciplines function.
     *
     * @access public
     * @return void
     */
    /*
    public function disciplines() {
        global $wpdb;

        $results=$wpdb->get_results("SELECT DISTINCT discipline AS id, t.name AS discipline FROM ".$this->table_name." INNER JOIN ".$wpdb->terms." t ON ".$this->table_name.".discipline = t.term_id");

        return $results;
    }
    */

    /**
     * recent_date function.
     *
     * @access public
     * @param int $discipline (default: 0)
     * @return void
     */
    /*
    public function recent_date( $discipline = 0 ) {
        global $wpdb;

        if ( ! is_numeric( $discipline ) ) :
            $term = get_term_by( 'slug', $discipline, 'discipline' );
            $discipline = $term->term_id;
        endif;

        return $wpdb->get_var( 'SELECT date FROM ' . $this->table_name . " WHERE discipline = $discipline ORDER BY date DESC LIMIT 1" );
    }
    */
}


/**
 * uci_rankings_last_update function.
 *
 * @access public
 * @return void
 */
/*
function uci_rankings_last_update() {
    global $uci_rankings;

    return $uci_rankings->last_update;
}
*/

