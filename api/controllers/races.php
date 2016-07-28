<?php

/**
 * Races class.
 */
class Races {
	private $_params;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $params
	 * @return void
	 */
	public function __construct($params) {
		$this->_params=$params;
	}

	/**
	 * _default function.
	 *
	 * @access public
	 * @return void
	 */
	public function _default() {
		return $this->races();
	}

	/**
	 * races function.
	 *
	 * @access public
	 * @return void
	 */
	public function races() {
		$default_args=array(
			'limit' => 15
		);
		$args=wp_parse_args($_REQUEST, $default_args);

		$races=new UCI_Results_Query(array(
			'per_page' => $args['limit'],
			'type' => 'races'
		));

		return $races->posts;
	}

	/**
	 * seasons function.
	 *
	 * @access public
	 * @return void
	 */
	public function seasons() {
		global $ucicurl_races;

		return $ucicurl_races->seasons();
	}

	/**
	 * series function.
	 *
	 * @access public
	 * @return void
	 */
	public function series() {
		global $wpdb;

		$series=$wpdb->get_results("SELECT * FROM $wpdb->uci_results_series");

		return $series;
	}

	/**
	 * buildRaceCode function.
	 *
	 * @access public
	 * @return void
	 */
	public function buildRaceCode(){
		global $uci_results_add_races;

		$code=$uci_results_add_races->build_race_code($this->_params['name'], $this->_params['date']);

		return $code;
	}
}
?>