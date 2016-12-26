<?php
global $uci_results_post;

/**
 * uci_results_init function.
 *
 * @access public
 * @return void
 */
function uci_results_init() {
	global $uci_results_pages;

	$uci_results_pages=array();
	$uci_results_pages['single_rider'] = get_option('single_rider_page_id', 0);
	$uci_results_pages['single_race'] = get_option('single_race_page_id', 0);
	$uci_results_pages['country'] = get_option('country_page_id', 0);
	$uci_results_pages['rider_rankings'] = get_option('rider_rankings_page_id', 0);
	$uci_results_pages['races'] = get_option('races_page_id', 0);
	$uci_results_pages['search'] = get_option('uci_results_search_page_id', 0);
}
add_action('init', 'uci_results_init', 1);

/**
 * uci_results_rewrite_rules function.
 *
 * @access public
 * @return void
 */
function uci_results_rewrite_rules() {
	global $uci_results_pages;

	$single_rider_url=ltrim(str_replace( home_url(), "", get_permalink($uci_results_pages['single_rider'])), '/');
	$single_race_url=ltrim(str_replace( home_url(), "", get_permalink($uci_results_pages['single_race'])), '/');
	$country_url=ltrim(str_replace( home_url(), "", get_permalink($uci_results_pages['country'])), '/');

	if (!empty($single_rider_url))
		add_rewrite_rule($single_rider_url.'([^/]*)/?', 'index.php?page_id='.$uci_results_pages['single_rider'].'&rider_slug=$matches[1]', 'top');

	if (!empty($single_race_url))
		add_rewrite_rule($single_race_url.'([^/]*)/?', 'index.php?page_id='.$uci_results_pages['single_race'].'&race_code=$matches[1]', 'top');

	if (!empty($country_url))
		add_rewrite_rule($country_url.'([^/]*)/?', 'index.php?page_id='.$uci_results_pages['country'].'&country_slug=$matches[1]', 'top');
}
add_action('init', 'uci_results_rewrite_rules', 10, 0);

/**
 * uci_results_register_query_vars function.
 *
 * @access public
 * @param mixed $vars
 * @return void
 */
function uci_results_register_query_vars( $vars ) {
  $vars[] = 'rider_slug';
  $vars[] = 'race_code';
  $vars[] = 'country_slug';

  return $vars;
}
add_filter( 'query_vars', 'uci_results_register_query_vars');

/**
 * uci_results_plugin_updater function.
 * 
 * @access public
 * @return void
 */
function uci_results_plugin_updater() {
	define( 'WP_GITHUB_FORCE_UPDATE', true );

	if (is_admin()) {
		$config = array(
			'slug' => plugin_basename(UCI_RESULTS_PATH),
			'proper_folder_name' => 'uci-results',
			'api_url' => 'https://api.github.com/repos/erikdmitchell/uci-results',
			'raw_url' => 'https://raw.github.com/erikdmitchell/uci-results/master',
			'github_url' => 'https://github.com/erikdmitchell/uci-results',
			'zip_url' => 'https://github.com/erikdmitchell/uci-results/archive/master.zip',
			'sslverify' => true,
			'requires' => '4.0',
			'tested' => '4.7',
			'readme' => 'readme.txt',
			'access_token' => '',
		);
print_r($config);
		new WP_GitHub_Updater($config);
	}

}
add_action('init', 'uci_results_plugin_updater');
?>