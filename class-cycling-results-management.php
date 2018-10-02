<?php
/**
 * Main CRM class
 *
 * @package CRM
 * @since   0.1.0
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
     * (default value: '0.1.0')
     *
     * @var string
     * @access public
     */
    public $version = '0.1.0';

    /**
     * admin
     *
     * (default value: '')
     *
     * @var mixed
     * @access public
     */
    public $admin = '';


    /**
     * UCI rankings.
     *
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $uci_rankings = '';

    /**
     * riders
     *
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $riders = '';

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
        $this->define( 'CRM_PATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'CRM_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'CRM_VERSION', $this->version );
        $this->define( 'CRM_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin/' );
        $this->define( 'CRM_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin/' );
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
        include_once( CRM_PATH . 'class-crm-riders.php' ); // our riders functions

        include_once( CRM_PATH . 'admin/class-uci-rankings-admin.php' );
        include_once( CRM_PATH . 'admin/class-crm-update-rider-rankings.php' );

        include_once( CRM_PATH . 'class-uci-rankings.php' );

        include_once( CRM_PATH . 'functions/races.php' ); // races functions
        include_once( CRM_PATH . 'functions/riders.php' ); // riders functions
        include_once( CRM_PATH . 'functions/utility.php' ); // utility functions
        include_once( CRM_PATH . 'functions/wp-query.php' ); // modify wp query functions
        include_once( CRM_PATH . 'functions.php' ); // generic functions

        include_once( CRM_PATH . 'admin/class-crm-admin.php' ); // admin page
        include_once( CRM_PATH . 'admin/notices.php' ); // admin notices function
        include_once( CRM_PATH . 'admin/class-crm-add-race-results.php' ); // add races/results to db
        include_once( CRM_PATH . 'admin/custom-columns.php' ); // custom columns for our admin pages

        include_once( CRM_PATH . 'lib/name-parser.php' ); // a php nameparser
        include_once( CRM_PATH . 'class-crm-shortcodes.php' ); // our shortcodes
        include_once( CRM_PATH . 'lib/flags.php' ); // our flag stuff

        // discipline classes //
        include_once( CRM_PATH . 'disciplines/class-crm-discipline.php' );

        include_once( CRM_PATH . 'class-crm-install.php' );
        include_once( CRM_PATH . 'crm-update-functions.php' );
    }

    /**
     * Init hooks.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {
        register_activation_hook( CRM_PLUGIN_FILE, array( 'CRM_Install', 'install' ) );
        // register_deactivation_hook( PCL_PLUGIN_FILE, array( 'PCL_Uninstall', 'uninstall' ) );
        add_action( 'init', array( $this, 'init' ), 0 );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts_styles' ) );

        add_filter( 'query_vars', array( $this, 'register_query_vars' ) );
    }

    /**
     * Init.
     *
     * @access public
     * @return void
     */
    public function init() {
        $this->load_files();
        $this->rewrite_rules();

        $this->riders = new CRM_Riders();
        $this->uci_rankings = new UCI_Rankings();

        if ( is_admin() ) :
            $this->admin = new CRM_Admin();
        endif;
    }

    public function frontend_scripts_styles() {

        // include on search page //
        /*
        if ( is_page( $uci_results_pages['search'] ) ) :
        wp_enqueue_script( 'uci-results-search-script', CRM_URL . '/js/search.js', array( 'jquery' ), '0.1.0' );

        wp_localize_script( 'uci-results-search-script', 'searchAJAXObject', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        endif;
        */

        wp_register_script( 'uci-results-front-end', CRM_URL . '/js/front-end.js', array( 'jquery' ), '0.1.0', true );

        wp_localize_script( 'uci-results-front-end', 'UCIResultsFrontEnd', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

        wp_enqueue_script( 'uci-results-front-end' );

        wp_enqueue_style( 'crm-fa-style', CRM_URL . 'css/font-awesome.min.css' );
        wp_enqueue_style( 'crm-style', CRM_URL . 'css/crm.css' );
    }

    private function load_files() {
        $dirs = array(
            'post-types',
            'taxonomies',
        );

        foreach ( $dirs as $dir ) :
            foreach ( glob( CRM_PATH . $dir . '/*.php' ) as $file ) :
                include_once( $file );
            endforeach;
        endforeach;
    }

    public function rewrite_rules() {
        add_rewrite_rule( 'uci-rankings/([^/]*)/([^/]*)/?', 'index.php?rankings_discipline=$matches[1]&rankings_date=$matches[2]', 'top' );
        add_rewrite_rule( 'crm-rankings/([^/]*)/([^/]*)/?', 'index.php?crm_rankings_discipline=$matches[1]&crm_rankings_season=$matches[2]', 'top' );
    }

    public function register_query_vars( $vars ) {
        $vars[] = 'rankings_date';
        $vars[] = 'rankings_discipline';
        $vars[] = 'crm_rankings_season';
        $vars[] = 'crm_rankings_discipline';

        return $vars;
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
