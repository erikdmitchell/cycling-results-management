<?php
/**
 * Yoast SEO mods
 *
 * @package CRM
 * @since   0.1.0
 */

/**
 * CRM_Yoast_SEO_Mods class.
 */
class CRM_Yoast_SEO_Mods {

    /**
     * post_types
     *
     * (default value: array())
     *
     * @var array
     * @access private
     */
    private $post_types = array();

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->set_post_types();

        add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 11 );
        add_action( 'admin_init', array( $this, 'init' ) );

        add_filter( 'wpseo_metabox_prio', array( $this, 'to_bottom' ) );
    }

    /**
     * Set post types.
     *
     * @access private
     * @return void
     */
    private function set_post_types() {
        $this->post_types = array(
            'races',
            'riders',
        );
    }

    /**
     * Init.
     *
     * @access public
     * @return void
     */
    public function init() {
        if ( ( isset( $_GET['post'] ) && in_array( get_post_type( $_GET['post'] ), $this->post_types ) ) ) :
            add_action( 'admin_enqueue_scripts', array( $this, 'remove_dependencies' ), 11 );
        endif;
    }

    /**
     * Remove yaost metaboxes.
     *
     * @access public
     * @return void
     */
    public function remove_meta_boxes() {
        foreach ( $this->post_types as $post_type ) :
            remove_meta_box( 'wpseo_meta', $post_type, 'normal' );
        endforeach;

        remove_meta_box( 'wpseo_meta', 'crm_country', 'normal' );
    }

    /**
     * Remove Yoast dependencies.
     *
     * @access public
     * @return void
     */
    public function remove_dependencies() {
        wp_deregister_script( 'yoast-seo-post-scraper' );
        wp_deregister_script( 'yoast-seo-term-scraper' );
        wp_deregister_script( 'yoast-seo-featured-image' );
    }

    /**
     * Moves yoast metabox to bottom.
     *
     * @access public
     * @return void
     */
    public function to_bottom() {
        return 'low';
    }

}

new CRM_Yoast_SEO_Mods();
