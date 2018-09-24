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

        self::update_version();
        self::update();

        delete_transient( 'crm_installing' );
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
