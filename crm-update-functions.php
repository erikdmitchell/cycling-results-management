<?php
/**
 * CRM Updates
 *
 * Functions for updating data.
 */
defined( 'ABSPATH' ) || exit;

/**
 * Update crm_rider_rankings db.
 *
 * @return void
 */
function crm_update_110_rider_rankings() {
    global $wpdb;

    $wpdb->query( "ALTER TABLE {$wpdb->prefix}crm_related_races DROP week" );

}

function crm_update_110_db_version() {
    // CRM_Install::update_db_version('1.1.0');
}
