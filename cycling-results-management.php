<?php
/**
 * Plugin Name: Cycling Results Management
 * Plugin URI: http://therunup.com
 * Description: A WordPress plugin to manage cycling results. Designed to manage UCI race results and riders.
 * Version: 0.2.0
 * Author: Erik Mitchell
 * Author URI: http://erikmitchell.net
 * Text Domain: crm
 *
 * @package CRM
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! defined( 'CRM_PLUGIN_FILE' ) ) {
    define( 'CRM_PLUGIN_FILE', __FILE__ );
}

// Include the main Pickle_Custom_Login class.
if ( ! class_exists( 'Cycling_Results_Management' ) ) {
    include_once dirname( __FILE__ ) . '/class-cycling-results-management.php';
}
