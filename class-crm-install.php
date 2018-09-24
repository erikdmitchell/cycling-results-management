<?php
/**
 * CRM install class
 *
 * @package CRM
 * @since   1.0.0
 */

/**
 * CRM_Install class.
 */
class CRM_Install {

    /**
     * Updates
     *
     * @var mixed
     * @access private
     * @static
     */
    private static $updates = array();

    /**
     * Init
     *
     * @access public
     * @static
     * @return void
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
    }

    /**
     * Check version.
     *
     * @access public
     * @static
     * @return void
     */
    public static function check_version() {
        if ( get_option( 'crm_version' ) !== cycling_results_management()->version ) {
            self::install();
        }
    }

    /**
     * Install.
     *
     * @access public
     * @static
     * @return void
     */
    public static function install() {
        if ( ! is_blog_installed() ) {
            return;
        }

        // Check if we are not already running this routine.
        if ( 'yes' === get_transient( 'crm_installing' ) ) {
            return;
        }

        // If we made it till here nothing is running yet, lets set the transient now.
        set_transient( 'crm_installing', 'yes', MINUTE_IN_SECONDS * 10 );

        self::create_tables();
        self::update_version();
        self::maybe_update_db_version();
        self::update();

        delete_transient( 'crm_installing' );
    }
    
    private static function create_tables() {
        global $wpdb;
        
        $wpdb->hide_errors();
            
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
        dbDelta(self::get_schema());
    }
    
    private static function get_schema() {
        global $wpdb;
		
		$collate = '';
		
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		
		$tables = "
    		CREATE TABLE $wpdb->crm_rider_rankings (
    		  id bigint(20) NOT NULL AUTO_INCREMENT,
    			rider_id bigint(20) NOT NULL,
    			points bigint(20) NOT NULL DEFAULT '0',
    			season VARCHAR(50) NOT NULL,
    			rank bigint(20) NOT NULL DEFAULT '0',
    			week bigint(20) NOT NULL DEFAULT '0',
    			PRIMARY KEY (`id`)
    		) $charset;
    	
    		CREATE TABLE $wpdb->crm_related_races (			
    			id bigint(20) NOT NULL AUTO_INCREMENT,
    			race_id bigint(20) NOT NULL DEFAULT '0',
    			related_race_id bigint(20) NOT NULL DEFAULT '0',
    			PRIMARY KEY (`id`)
    		) $charset;
    
    		CREATE TABLE $wpdb->crm_uci_rankings (
    			id bigint(20) NOT NULL AUTO_INCREMENT,
    			name TEXT NOT NULL,
    			rider_id bigint(20) NOT NULL DEFAULT '0',
    			rank bigint(20) NOT NULL DEFAULT '0',
    			age bigint(20) NOT NULL DEFAULT '0',
    			points bigint(20) NOT NULL DEFAULT '0',
    			discipline bigint(20) NOT NULL DEFAULT '0',
    			date DATE NOT NULL,
    			PRIMARY KEY (`id`)
    		) $charset;	
		";

		return $tables;  
    }
    
    private static function maybe_update_db_version() {
		if ( self::needs_db_update() ) {
			self::update();
		} else {
			self::update_db_version();
		}
	}  
	
    private static function needs_db_update() {
		$current_db_version = get_option( 'crm_db_version', null );
		$updates            = self::get_db_update_callbacks();
		
		return ! is_null( $current_db_version ) && version_compare( $current_db_version, max( array_keys( $updates ) ), '<' );
	}	  

    private static function get_db_update_callbacks() {
        return self::$db_updates;
    }

    public static function update_db_version( $version = null ) {
		delete_option( 'crm_db_version' );
		add_option( 'crm_db_version', is_null( $version ) ? cycling_results_management()->version : $version );
	}

    /**
     * Update function.
     *
     * @access private
     * @static
     * @return void
     */
    private static function update() {
        $current_version = get_option( 'crm_version' );

        foreach ( self::get_update_callbacks() as $version => $update_callbacks ) :
            if ( version_compare( $current_version, $version, '<' ) ) :
                foreach ( $update_callbacks as $update_callback ) :
                    $update_callback();
                endforeach;
            endif;
        endforeach;
    }

    /**
     * Get update callbacks.
     *
     * @access public
     * @static
     * @return updates
     */
    public static function get_update_callbacks() {
        return self::$updates;
    }

    /**
     * Update version.
     *
     * @access private
     * @static
     * @return void
     */
    private static function update_version() {
        delete_option( 'crm_version' );

        add_option( 'crm_version', cycling_results_management()->version );
    }

}

CRM_Install::init();
