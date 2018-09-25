<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $uci_rider_stats;

$uci_rider_stats = array();

class UCIRiderStats {

    public $name;

    public $id;

    public $discipline;

    public $options;

    /**
     * __construct function.
     *
     * @access public
     * @param string $args (default: '')
     * @return void
     */
    public function __construct( $args = '' ) {
        $default_args = array(
            'id' => '',
            'name' => '',
            'discipline' => '',
            'options' => array(),
        );
        $args = crm_parse_args( $args, $default_args );

        $this->id = $args['id'];
        $this->name = $args['name'];
        $this->discipline = $args['discipline'];
        $this->options = $args['options'];
    }

    /**
     * get_stats function.
     *
     * @access public
     * @param int $rider_id (default: 0)
     * @return void
     */
    public function get_stats( $rider_id = 0 ) {
        return 'stats should be overridden in sub class';
    }

    /**
     * _register function.
     *
     * @access public
     * @return void
     */
    public function _register() {
        global $uci_rider_stats;

        $uci_rider_stats[ $this->id ] = $this;
    }

}

/**
 * uci_rider_stats_init function.
 *
 * @access public
 * @return void
 */
function uci_rider_stats_init() {
    uci_results_register_stats( 'UCICrossStats' );

    do_action( 'uci_rider_stats_init' );
}
add_action( 'init', 'uci_rider_stats_init', 1 );

