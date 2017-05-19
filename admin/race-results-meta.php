<?php

/**
 * process_road_race_results function.
 * 
 * @access public
 * @param mixed $meta_value
 * @param mixed $race_id
 * @param mixed $result
 * @param mixed $rider_id
 * @return void
 */
function process_road_race_results($meta_value, $race_id, $result, $rider_id) {
	$meta_value['place']=$meta_value['rank'];
	
	unset($meta_value['rank']);

	return $meta_value;
}
add_filter('uci_results_insert_race_result_road', 'process_road_race_results', 10, 4);

/**
 * process_cyclocross_race_results function.
 * 
 * @access public
 * @param mixed $meta_value
 * @param mixed $race_id
 * @param mixed $result
 * @param mixed $rider_id
 * @return void
 */
function process_cyclocross_race_results($meta_value, $race_id, $result, $rider_id) {
	if (!isset($result->par) || empty($result->par) || is_null($result->par)) :
		$par=0;
	else :
		$par=$result->par;
	endif;

	if (!isset($result->pcr) || empty($result->pcr) || is_null($result->pcr)) :
		$pcr=0;
	else :
		$pcr=$result->pcr;
	endif;

	return $meta_value;
}
add_filter('uci_results_insert_race_result_cyclocross', 'process_cyclocross_race_results', 10, 4);

//$meta_value=apply_filters('uci_results_insert_race_result', $meta_value, $race_id, $result, $rider_id);				
?>