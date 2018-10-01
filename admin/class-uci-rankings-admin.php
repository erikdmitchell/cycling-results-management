<?php

class UCI_Rankings_Admin {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        global $wpdb;

        $this->table_name = $wpdb->prefix . 'crm_uci_rankings';

        add_action( 'wp_ajax_uci_add_rider_rankings', array( $this, 'ajax_process_csv_file' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );
    }

    /**
     * admin_scripts_styles function.
     *
     * @access public
     * @param mixed $hook
     * @return void
     */
    public function admin_scripts_styles( $hook ) {
        global $wp_scripts;

        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'uci-rankings-script', CRM_ADMIN_URL . 'js/uci-rankings.js', array( 'jquery-ui-datepicker' ), CRM_VERSION, true );

        wp_enqueue_style( 'jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/ui-lightness/jquery-ui.min.css' );

        wp_enqueue_media();
    }

    /**
     * ajax_process_csv_file function.
     *
     * @access public
     * @return void
     */
    public function ajax_process_csv_file() {
        $args = array();
        parse_str( $_POST['form'], $args );

        $this->process_csv_file( $args );

        echo '<div class="notice notice-success is-dismissible">CSV file processed and inserted into db.</div>';

        wp_die();
    }

    /**
     * process_csv_file function.
     *
     * @access public
     * @param string $args (default: '')
     * @return void
     */
    public function process_csv_file( $args = '' ) {
        global $wpdb;

        $default_args = array(
            'file' => '',
            'date' => '',
            'discipline' => 0,
            'clean_names' => 0,
        );
        $args = wp_parse_args( $args, $default_args );

        extract( $args );

        if ( empty( $file ) || $file == '' ) {
            return false;
        }

        if ( empty( $date ) ) {
            $date = date( 'Y-m-d' );
        }

        ini_set( 'auto_detect_line_endings', true ); // added for issues with MAC

        $data = array();
        $row_counter = 1;
        $headers = array();
        $file = wp_remote_fopen( $file );

        $file = str_replace( "\r\n", "\n", trim( $file ) );
        $rows = explode( "\n", $file );

        // process csv rows //
        foreach ( $rows as $row => $cols ) :
            $cols = str_getcsv( $cols, ',' );

            if ( $row_counter == 1 ) :
                $headers = array_map( 'sanitize_title_with_dashes', $cols );
                else :
                    $data[] = array_combine( $headers, $cols );
                endif;

                $row_counter++;
        endforeach;

        // clean rank, add rider id via name and add date.
        foreach ( $data as $key => $row ) :
            $rank_arr = explode( ' ', $row['rank'] );
            $name = trim( str_replace( '*', '', $row['name'] ) );

            // clean name.
            if ( $clean_names ) {
                $name = $this->clean_names( $name );
            }

            // nation check.
            $found_nation_key = false;

            foreach ( $row as $k => $v ) :
                if ( strpos( $k, 'nation' ) !== false ) :
                    $found_nation_key = $k;
                    break;
                endif;
            endforeach;

            if ( $found_nation_key ) :
                $country = $this->convert_country( $row[ $found_nation_key ] );
            endif;
            // end nation check.
            $data[ $key ]['rank'] = $rank_arr[0];
            $data[ $key ]['rider_id'] = crm_results_add_rider( $name, $country );
            $data[ $key ]['date'] = $date;
            $data[ $key ]['name'] = $name;
            $data[ $key ]['discipline'] = $discipline;
        endforeach;

        $this->insert_rankings_into_db( $data );

        // update our option so we know we have a ranking change.
        $update_date = $date . ' ' . date( 'H:i:s' );
        update_option( "uci_rankings_last_update_$discipline", $update_date );

        // $this->last_update = $date;
        return true;
    }

    /**
     * clean_names function.
     *
     * @access protected
     * @param string $name (default: '')
     * @return void
     */
    protected function clean_names( $name = '' ) {
        if ( empty( $name ) ) {
            return '';
        }

        $name_arr = explode( ' ', $name );
        $last_el = array_pop( $name_arr );
        array_unshift( $name_arr, $last_el );

        return implode( ' ', $name_arr );
    }

    /**
     * convert_country function.
     *
     * @access protected
     * @param string $country (default: '')
     * @return void
     */
    protected function convert_country( $country = '' ) {
        global $flags_countries_arr;

        $country_code = '';

        if ( strtolower( $country ) == 'great britain' ) :
            return 'GBR';
        endif;

        foreach ( $flags_countries_arr as $code => $arr ) :
            if ( strtolower( $arr[0] ) == strtolower( $country ) ) :
                $country_code = $arr[2];
                break;
            endif;
        endforeach;

        return $country_code;
    }

    /**
     * insert_rankings_into_db function.
     *
     * @access protected
     * @param array $data (default: array())
     * @return void
     */
    protected function insert_rankings_into_db( $data = array() ) {
        global $wpdb;

        $table_columns = $this->get_columns();
        $data_clean = $this->data_table_cols_match( $data, $table_columns );

        foreach ( $data_clean as $arr ) :
            // skip if no name //
            if ( $arr['name'] == '' ) {
                continue;
            }

            // check if this entry exists and pull ID so we can update //
            $id = $wpdb->get_var( 'SELECT id FROM ' . $this->table_name . ' WHERE name = "' . $arr['name'] . "\" AND date = '" . $arr['date'] . "'" );

            if ( $id !== null ) :
                $wpdb->update( $this->table_name, $arr, array( 'id' => $id ) );
            else :
                $wpdb->insert( $this->table_name, $arr );
            endif;
        endforeach;

        return;
    }

    /**
     * data_table_cols_match function.
     *
     * @access protected
     * @param string $data (default: '')
     * @param string $columns (default: '')
     * @return void
     */
    protected function data_table_cols_match( $data = '', $columns = '' ) {
        if ( empty( $data ) || empty( $columns ) ) {
            return $data;
        }

        $data_clean = array();

        foreach ( $data as $arr ) :
            $new_arr = array();

            foreach ( $arr as $key => $value ) :
                if ( in_array( $key, $columns ) ) :
                    $new_arr[ $key ] = $arr[ $key ];
                endif;
            endforeach;

            $data_clean[] = $new_arr;
        endforeach;

        return $data_clean;
    }

    /**
     * get_columns function.
     *
     * @access public
     * @return void
     */
    public function get_columns() {
        global $wpdb;

        return $wpdb->get_col( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $this->table_name . "'" );
    }
}

new UCI_Rankings_Admin();
