<?php
global $ucicurl_db_version;

$ucicurl_db_version='0.1.7';

/**
 * ucicurl_set_db_tables function.
 *
 * @access public
 * @return void
 */
function ucicurl_set_db_tables() {
	global $wpdb;

	$wpdb->uci_results_races=$wpdb->prefix.'uci_curl_races';
	$wpdb->uci_results_results=$wpdb->prefix.'uci_curl_results';
	$wpdb->uci_results_riders=$wpdb->prefix.'uci_curl_riders';
	$wpdb->uci_results_rider_rankings=$wpdb->prefix.'uci_curl_rider_rankings';
	$wpdb->uci_results_related_races=$wpdb->prefix.'uci_curl_related_races';
	$wpdb->uci_results_series=$wpdb->prefix.'uci_curl_series';
	$wpdb->uci_results_series_overall=$wpdb->prefix.'uci_results_series_overall';
}
ucicurl_set_db_tables();

/**
 * ucicurl_db_install function.
 *
 * @access public
 * @return void
 */
function ucicurl_db_install() {
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');

	global $wpdb, $ucicurl_db_version;

	$wpdb->hide_errors();
	$wpdb->uci_results_races=$wpdb->prefix.'uci_curl_races';
	$wpdb->uci_results_results=$wpdb->prefix.'uci_curl_results';
	$wpdb->uci_results_riders=$wpdb->prefix.'uci_curl_riders';
	$wpdb->uci_results_rider_rankings=$wpdb->prefix.'uci_curl_rider_rankings';
	$wpdb->uci_results_related_races=$wpdb->prefix.'uci_curl_related_races';
	$wpdb->uci_results_series=$wpdb->prefix.'uci_curl_series';
	$wpdb->uci_results_series_overall=$wpdb->prefix.'uci_results_series_overall';

	$charset=$wpdb->get_charset_collate();

	$sql_races="
		CREATE TABLE $wpdb->uci_results_races (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			date DATE NOT NULL,
			event TEXT NOT NULL,
			nat VARCHAR(5) NOT NULL,
			class VARCHAR(5) NOT NULL ,
			winner VARCHAR(250) NOT NULL,
			season VARCHAR(50) NOT NULL,
			week bigint(20) NOT NULL DEFAULT '0',
			link TEXT NOT NULL,
			code TEXT NOT NULL,
			related_races_id bigint(20) NOT NULL DEFAULT '0',
			series_id bigint(20) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) $charset;
	";

	$sql_results="
		CREATE TABLE $wpdb->uci_results_results (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			race_id bigint(20) NOT NULL,
			place SMALLINT NOT NULL DEFAULT '0',
			name LONGTEXT NOT NULL,
			nat VARCHAR(5) NOT NULL,
			age TINYINT NOT NULL DEFAULT '0',
			result VARCHAR(10) NOT NULL,
			pcr bigint(20) NOT NULL DEFAULT '0',
			pcr VARCHAR(10) NOT NULL DEFAULT '0',
			rider_id bigint(20) NOT NULL,
			PRIMARY KEY (`id`)
		) $charset;
		ALTER DATABASE {$wpdb->uci_results_results} CHARACTER SET utf8;
	";

	$sql_riders="
		CREATE TABLE $wpdb->uci_results_riders (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
			name LONGTEXT NOT NULL,
			nat VARCHAR(5) NOT NULL,
			slug LONGTEXT NOT NULL,
			PRIMARY KEY (`id`)
		) $charset;
	";

	$sql_rider_rankings="
		CREATE TABLE $wpdb->uci_results_rider_rankings (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
			rider_id bigint(20) NOT NULL,
			points bigint(20) NOT NULL DEFAULT '0',
			season VARCHAR(50) NOT NULL,
			rank bigint(20) NOT NULL DEFAULT '0',
			week bigint(20) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) $charset;
	";

	$sql_related_races="
		CREATE TABLE $wpdb->uci_results_related_races (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
			race_ids TEXT NOT NULL,
			PRIMARY KEY (`id`)
		) $charset;
	";

	$sql_series="
		CREATE TABLE $wpdb->uci_results_series (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
			name TEXT NOT NULL,
			PRIMARY KEY (`id`)
		) $charset;
	";

	$sql_series_overall="
		CREATE TABLE $wpdb->uci_results_series_overall (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
			rider_id bigint(20) NOT NULL,
			points bigint(20) NOT NULL DEFAULT '0',
			series_id bigint(20) NOT NULL,
			season VARCHAR(50) NOT NULL,
			rank bigint(20) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) $charset;
	";

	dbDelta(array(
		$sql_races,
		$sql_results,
		$sql_riders,
		$sql_rider_rankings,
		$sql_related_races,
		$sql_series,
		$sql_series_overall,
	));

	add_option('ucicurl_db_version', $ucicurl_db_version);
}
register_activation_hook(__FILE__, 'ucicurl_db_install');

/**
 * ucicurl_db_update function.
 *
 * @access public
 * @return void
 */
function ucicurl_db_update() {
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');

	global $wpdb, $ucicurl_db_version;

	echo $installed_version=get_option('ucicurl_db_version');

	if ($installed_version!=$ucicurl_db_version) :
		$wpdb->hide_errors();
		$wpdb->uci_results_races=$wpdb->prefix.'uci_curl_races';
		$wpdb->uci_results_results=$wpdb->prefix.'uci_curl_results';
		$wpdb->uci_results_riders=$wpdb->prefix.'uci_curl_riders';
		$wpdb->uci_results_rider_rankings=$wpdb->prefix.'uci_curl_rider_rankings';
		$wpdb->uci_results_related_races=$wpdb->prefix.'uci_curl_related_races';
		$wpdb->uci_results_series=$wpdb->prefix.'uci_curl_series';
		$wpdb->uci_results_series_overall=$wpdb->prefix.'uci_results_series_overall';

		$sql_races="
			CREATE TABLE $wpdb->uci_results_races (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				date DATE NOT NULL,
				event TEXT NOT NULL,
				nat VARCHAR(5) NOT NULL,
				class VARCHAR(5) NOT NULL ,
				winner VARCHAR(250) NOT NULL,
				season VARCHAR(50) NOT NULL,
				week bigint(20) NOT NULL DEFAULT '0',
				link TEXT NOT NULL,
				code TEXT NOT NULL,
				related_races_id bigint(20) NOT NULL DEFAULT '0',
				series_id bigint(20) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			);
		";

		$sql_results="
			CREATE TABLE $wpdb->uci_results_results (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				race_id bigint(20) NOT NULL,
				place SMALLINT NOT NULL DEFAULT '0',
				name LONGTEXT NOT NULL,
				nat VARCHAR(5) NOT NULL,
				age TINYINT NOT NULL DEFAULT '0',
				result VARCHAR(10) NOT NULL,
				pcr bigint(20) NOT NULL DEFAULT '0',
				pcr VARCHAR(10) NOT NULL DEFAULT '0',
				rider_id bigint(20) NOT NULL,
				PRIMARY KEY (`id`)
			);
			ALTER DATABASE {$wpdb->uci_results_results} CHARACTER SET utf8;
		";

		$sql_riders="
			CREATE TABLE $wpdb->uci_results_riders (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
				name LONGTEXT NOT NULL,
				nat VARCHAR(5) NOT NULL,
				slug LONGTEXT NOT NULL,
				PRIMARY KEY (`id`)
			);
		";

		$sql_rider_rankings="
			CREATE TABLE $wpdb->uci_results_rider_rankings (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
				rider_id bigint(20) NOT NULL,
				points bigint(20) NOT NULL DEFAULT '0',
				season VARCHAR(50) NOT NULL,
				rank bigint(20) NOT NULL DEFAULT '0',
				week bigint(20) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			);
		";

		$sql_related_races="
			CREATE TABLE $wpdb->uci_results_related_races (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
				race_ids TEXT NOT NULL,
				PRIMARY KEY (`id`)
			);
		";

		$sql_series="
			CREATE TABLE $wpdb->uci_results_series (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
				name TEXT NOT NULL,
				PRIMARY KEY (`id`)
			);
		";

		$sql_series_overall="
			CREATE TABLE $wpdb->uci_results_series_overall (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
				rider_id bigint(20) NOT NULL,
				points bigint(20) NOT NULL DEFAULT '0',
				series_id bigint(20) NOT NULL,
				season VARCHAR(50) NOT NULL,
				rank bigint(20) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			);
		";

		dbDelta(array(
			$sql_races,
			$sql_results,
			$sql_riders,
			$sql_rider_rankings,
			$sql_related_races,
			$sql_series,
			$sql_series_overall,
		));

		update_option('ucicurl_db_version', $ucicurl_db_version);
	endif;
}

/**
 * ucicurl_update_db_check function.
 *
 * @access public
 * @return void
 */
function ucicurl_update_db_check() {
	global $ucicurl_db_version;

	if (get_option('ucicurl_db_version')!=$ucicurl_db_version)
		ucicurl_db_update();

	return;
}
add_action('plugins_loaded', 'ucicurl_update_db_check');

/**
 * uci_results_empty_database_tables function.
 *
 * @access public
 * @return void
 */
function uci_results_empty_database_tables() {
	global $wpdb;

	$wpdb->query("TRUNCATE TABLE $wpdb->uci_results_races");
	$wpdb->query("TRUNCATE TABLE $wpdb->uci_results_results");
	$wpdb->query("TRUNCATE TABLE $wpdb->uci_results_riders");
	$wpdb->query("TRUNCATE TABLE $wpdb->uci_results_rider_rankings");
	$wpdb->query("TRUNCATE TABLE $wpdb->uci_results_related_races");
	$wpdb->query("TRUNCATE TABLE $wpdb->uci_results_series");
	$wpdb->query("TRUNCATE TABLE $wpdb->uci_results_series_overall");
}

/**
 * uci_results_remove_database_tables function.
 *
 * @access public
 * @return void
 */
function uci_results_remove_database_tables() {
	global $wpdb;

	$wpdb->query("DROP TABLE $wpdb->uci_results_races");
	$wpdb->query("DROP TABLE $wpdb->uci_results_results");
	$wpdb->query("DROP TABLE $wpdb->uci_results_riders");
	$wpdb->query("DROP TABLE $wpdb->uci_results_rider_rankings");
	$wpdb->query("DROP TABLE $wpdb->uci_results_related_races");
	$wpdb->query("DROP TABLE $wpdb->uci_results_series");
	$wpdb->query("DROP TABLE $wpdb->uci_results_series_overall");
}
?>