<?php

/**
 * Resultsquery class.
 */
class Resultsquery {
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
	 * q function.
	 *
	 * @access public
	 * @return void
	 */
	public function q() {
		$results_query=new UCI_Results_Query($this->_params);

		return $results_query;
	}

}
?>