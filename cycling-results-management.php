<?php
/**
 * Plugin Name: Cycling Results Management
 * Plugin URI: http://therunup.com
 * Description: A WordPress plugin to manage cycling results. Designed to manage UCI race results and riders.
 * Version: 1.0.0
 * Author: Erik Mitchell
 * Author URI: http://erikmitchell.net
 * Text Domain: crms
 */

define('CRM_PATH', plugin_dir_path(__FILE__));
define('CRM_URL', plugin_dir_url(__FILE__));
define('CRM_VERSION', '1.0.0');
define('CRM_ADMIN_PATH', plugin_dir_path(__FILE__).'admin/');
define('CRM_ADMIN_URL', plugin_dir_url(__FILE__).'admin/');

include_once(CRM_PATH.'classes/riders.php'); // our riders functions
include_once(CRM_PATH.'classes/rider-rankings-query.php'); // rider rankings query class
include_once(CRM_PATH.'classes/seasons.php');
include_once(CRM_PATH.'classes/uci-rankings.php');

include_once(CRM_PATH.'database.php'); // sets up our db tables
include_once(CRM_PATH.'functions/ajax.php'); // ajax functions
include_once(CRM_PATH.'functions/races.php'); // races functions
include_once(CRM_PATH.'functions/riders.php'); // riders functions
include_once(CRM_PATH.'functions/search.php'); // search functions
include_once(CRM_PATH.'functions/seasons.php'); // seasons functions
include_once(CRM_PATH.'functions/utility.php'); // utility functions
include_once(CRM_PATH.'functions/wp-query.php'); // modify wp query functions
include_once(CRM_PATH.'functions.php'); // generic functions

include_once(CRM_PATH.'init.php'); // functions to run on init

include_once(CRM_PATH.'admin/admin.php'); // admin page
include_once(CRM_PATH.'admin/notices.php'); // admin notices function
include_once(CRM_PATH.'admin/add-races.php'); // cURL and add races/results to db
include_once(CRM_PATH.'admin/rider-rankings.php'); // add and update rider rankings
include_once(CRM_PATH.'admin/wp-cli.php'); // wp cli functions
include_once(CRM_PATH.'admin/custom-columns.php'); // custom columns for our admin pages

include_once(CRM_PATH.'lib/name-parser.php'); // a php nameparser
include_once(CRM_PATH.'shortcode.php'); // our shortcodes
include_once(CRM_PATH.'lib/flags.php'); // our flag stuff

include_once(CRM_PATH.'stats/base.php'); // base stats class
include_once(CRM_PATH.'stats/init.php'); // init class
include_once(CRM_PATH.'stats/cross.php'); // cross stats class

// discipline classes //
include_once(CRM_PATH.'disciplines/base.php');

/**
 * is_crm_active function.
 *
 * @access public
 * @return void
 */
function is_crm_active() {
	if (in_array('cycling-results-management/cycling-results-management.php', apply_filters('active_plugins', get_option('active_plugins'))))
		return true;

	return false;
}