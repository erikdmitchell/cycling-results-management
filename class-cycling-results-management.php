<?php
/**
 * Main CRM class
 *
 * @package CRM
 * @since   1.0.0
 */

/**
 * Final Cycling_Results_Management class.
 *
 * @final
 */
final class Cycling_Results_Management {

    /**
     * version
     *
     * (default value: '1.0.0')
     *
     * @var string
     * @access public
     */
    public $version = '1.0.0';

    /**
     * pages
     *
     * (default value: array())
     *
     * @var array
     * @access public
     */
    public $pages = array();

    /**
     * _instance
     *
     * (default value: null)
     *
     * @var mixed
     * @access protected
     * @static
     */
    protected static $_instance = null;

    /**
     * Instance.
     *
     * @access public
     * @static
     * @return instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define constants.
     *
     * @access private
     * @return void
     */
    private function define_constants() {        
        $this->define('CRM_PATH', plugin_dir_path(__FILE__));
        $this->define('CRM_URL', plugin_dir_url(__FILE__));
        $this->define('CRM_VERSION', $this->version);
        $this->define('CRM_ADMIN_PATH', plugin_dir_path(__FILE__).'admin/');
        $this->define('CRM_ADMIN_URL', plugin_dir_url(__FILE__).'admin/');        
    }

    /**
     * Define function.
     *
     * @access private
     * @param mixed $name
     * @param mixed $value
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Includes.
     *
     * @access public
     * @return void
     */
    public function includes() {
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
    }

    /**
     * Init hooks.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {
        //register_activation_hook( PCL_PLUGIN_FILE, array( 'Pickle_Custom_Login_Install', 'install' ) );
        //register_deactivation_hook( PCL_PLUGIN_FILE, array( 'PCL_Uninstall', 'uninstall' ) );

        add_action( 'init', array( $this, 'init' ), 0 );
    }

    /**
     * Init.
     *
     * @access public
     * @return void
     */
    public function init() {
        $this->pages = get_option( 'pcl_pages' );
    }

}

/**
 * CRM function.
 *
 * @access public
 * @return class instance
 */
function cycling_results_management() {
    return Cycling_Results_Management::instance();
}

// Global for backwards compatibility.
$GLOBALS['cycling_results_management'] = cycling_results_management();