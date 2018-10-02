<?php
class CRM_Admin {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );
        add_action( 'admin_init', array( $this, 'init' ) );
        add_action( 'admin_init', array( $this, 'save_settings' ) );
        add_action( 'save_post', array( $this, 'assign_parent_terms' ), 10, 2 );

        add_action( 'wp_ajax_uci_remove_related_race', array( $this, 'ajax_remove_related_race' ) );
        add_action( 'wp_ajax_show_related_races_box', array( $this, 'ajax_show_related_races_box' ) );
        add_action( 'wp_ajax_add_related_races_to_race', array( $this, 'ajax_add_related_races_to_race' ) );
        add_action( 'wp_ajax_race_id_search', array( $this, 'ajax_race_id_search' ) );
    }

    /**
     * admin_scripts_styles function.
     *
     * @access public
     * @return void
     */
    public function admin_scripts_styles( $hook ) {
        global $wp_scripts;

        $jquery_ui_version = $wp_scripts->registered['jquery-ui-core']->ver;

        wp_enqueue_script( 'uci-results-admin', CRM_ADMIN_URL . '/js/admin.js', array( 'jquery' ), '0.1.0', true );

        wp_enqueue_style( 'uci-results-api-admin-styles', CRM_ADMIN_URL . 'css/admin.css', '0.1.0' );
    }

    /**
     * register_menu_page function.
     *
     * @access public
     * @return void
     */
    public function register_menu_page() {
        $parent_slug = 'cycling-results-management';
        $manage_options_cap = 'manage_options';

        add_menu_page( __( 'Cycling Results', 'crm' ), 'Cycling Results', $manage_options_cap, $parent_slug, array( $this, 'admin_page' ), 'dashicons-media-spreadsheet', 80 );
        add_submenu_page( $parent_slug, 'Riders', 'Riders', $manage_options_cap, 'edit.php?post_type=riders' );
        add_submenu_page( $parent_slug, 'Races', 'Races', $manage_options_cap, 'edit.php?post_type=races' );
        add_submenu_page( $parent_slug, 'Countries', 'Countries', $manage_options_cap, 'edit-tags.php?taxonomy=crm_country' );
        add_submenu_page( $parent_slug, 'Class', 'Class', $manage_options_cap, 'edit-tags.php?taxonomy=race_class&post_type=races' );
        add_submenu_page( $parent_slug, 'Discipline', 'Discipline', $manage_options_cap, 'edit-tags.php?taxonomy=discipline&post_type=races' );
        add_submenu_page( $parent_slug, 'Series', 'Series', $manage_options_cap, 'edit-tags.php?taxonomy=series&post_type=races' );
        add_submenu_page( $parent_slug, 'Season', 'Season', $manage_options_cap, 'edit-tags.php?taxonomy=season&post_type=races' );
        add_submenu_page( $parent_slug, 'Settings', 'Settings', $manage_options_cap, $parent_slug );
        add_submenu_page( $parent_slug, 'Rider Rankings', 'Rider Rankings', $manage_options_cap, 'admin.php?page=' . $parent_slug . '&subpage=rider-rankings' );
        add_submenu_page( $parent_slug, 'UCI Rankings', 'UCI Rankings', $manage_options_cap, 'admin.php?page=' . $parent_slug . '&subpage=uci-rankings' );
    }

    /**
     * admin_page function.
     *
     * @access public
     * @return void
     */
    public function admin_page() {
        $html = null;
        $subpage = isset( $_GET['subpage'] ) ? $_GET['subpage'] : 'settings';

        $html .= '<div class="wrap crm">';
            $html .= '<h1>Cycling Results Management</h1>';

        switch ( $subpage ) :
            case 'rider-rankings':
                $html .= $this->get_admin_page( 'rider-rankings' );
                break;
            case 'settings':
                $html .= $this->get_admin_page( 'settings' );
                break;
            case 'results':
                if ( isset( $_GET['action'] ) && $_GET['action'] == 'add-csv' ) :
                    $html .= $this->get_admin_page( 'results-csv' );
                    endif;
                break;
            case 'uci-rankings':
                $html .= $this->get_admin_page( 'uci-rankings' );
                break;
            default:
                $html .= $this->get_admin_page( 'settings' );
            endswitch;

        $html .= '</div><!-- /.wrap -->';

        echo $html;
    }

    /**
     * get_admin_page function.
     *
     * @access public
     * @param bool $template_name (default: false)
     * @return void
     */
    public function get_admin_page( $template_name = false ) {
        $html = null;

        if ( ! $template_name ) {
            return false;
        }

        ob_start();

        if ( file_exists( CRM_PATH . "adminpages/$template_name.php" ) ) {
            include_once( CRM_PATH . "adminpages/$template_name.php" );
        }

        $html = ob_get_contents();

        ob_end_clean();

        return $html;
    }
    
    /**
     * Init.
     * 
     * @access public
     * @return void
     */
    public function init() {
        $this->load_files();
    }

    /**
     * save_settings function.
     *
     * @access public
     * @return void
     */
    public function save_settings() {
        if ( ! isset( $_POST['crm_admin_settings'] ) || ! wp_verify_nonce( $_POST['crm_admin_settings'], 'update_settings' ) ) {
            return;
        }
    }

    /**
     * ajax_remove_related_race function.
     *
     * @access public
     * @return void
     */
    public function ajax_remove_related_race() {
        global $wpdb;

        $wpdb->delete(
            $wpdb->uci_results_related_races, array(
                'race_id' => $_POST['id'],
                'related_race_id' => $_POST['rrid'],
            )
        );

        echo true;

        wp_die();
    }

    /**
     * ajax_add_related_races_to_race function.
     *
     * @access public
     * @return void
     */
    public function ajax_add_related_races_to_race() {
        global $wpdb;

        parse_str( $_POST['form'], $form );

        $html = null;
        $races = $form['races'];
        $related_race_id = uci_get_related_race_id( $_POST['id'] );
        $last_related_race_id = $wpdb->get_var( "SELECT MAX(related_race_id) FROM $wpdb->uci_results_related_races" );

        // if no rr id - increase last by 1 //
        if ( ! $related_race_id ) :
            $related_race_id = $last_related_race_id + 1;
            update_post_meta( $_POST['id'], '_race_related', $related_race_id );
        endif;

        foreach ( $races as $race_id ) :
            $data = array(
                'race_id' => $race_id,
                'related_race_id' => $related_race_id,
            );
            $wpdb->insert( $wpdb->uci_results_related_races, $data );
        endforeach;

        // get races information //
        foreach ( $races as $race_id ) :
            $html .= '<div id="race-' . $race_id . '" class="row">';
                $html .= '<div class="race-name">' . get_the_title( $race_id ) . '</div>';
                $html .= '<div class="race-date">' . date( get_option( 'date_format' ), strtotime( get_post_meta( $race_id, '_race_date', true ) ) ) . '</div>';
                $html .= '<div class="action-icons"><a href="#" class="remove-related-race" data-id="' . $race_id . '" data-rrid="' . $related_race_id . '"><span class="dashicons dashicons-dismiss"></span></a></div>';
            $html .= '</div>';
        endforeach;

        $return = array(
            'related_race_id' => $related_race_id,
            'html' => $html,
        );

        echo json_encode( $return );

        wp_die();
    }

    /**
     * assign_parent_terms function.
     *
     * @access public
     * @param mixed $post_id
     * @param mixed $post
     * @return void
     */
    public function assign_parent_terms( $post_id, $post ) {
        if ( $post->post_type != 'races' ) {
            return $post_id;
        }

        // terms with parents //
        $terms_with_parents = array( 'race_class', 'series', 'season' );

        // get all assigned terms in race_class and update parent //
        foreach ( $terms_with_parents as $term_cat ) :
            $terms = wp_get_post_terms( $post_id, $term_cat );

            foreach ( $terms as $term ) :
                while ( $term->parent != 0 && ! has_term( $term->parent, $term_cat, $post ) ) :
                    // move upward until we get to 0 level terms
                    wp_set_post_terms( $post_id, array( $term->parent ), $term_cat, true );
                    $term = get_term( $term->parent, $term_cat );
                endwhile;
            endforeach;
        endforeach;

    }

    /**
     * ajax_race_id_search function.
     *
     * @access public
     * @return void
     */
    public function ajax_race_id_search() {
        $html = '';
        $posts = get_posts(
            array(
                'posts_per_page' => -1,
                'post_type' => 'races',
                's' => $_POST['string'],
            )
        );

        $html .= '<select multiple id="races-list" name="race_search_id" size=20 style="height: 100%;">';
        foreach ( $posts as $post ) :
            $html .= '<option value="' . $post->ID . '">' . $post->post_title . ' (' . get_post_meta( $post->ID, '_race_date', true ) . ')</option>';
            endforeach;
        $html .= '</select>';

        echo $html;

        wp_die();
    }

    private function load_files() {
        $dirs = array(
            'metaboxes',
        );

        foreach ( $dirs as $dir ) :
            foreach ( glob( CRM_ADMIN_PATH . $dir . '/*.php' ) as $file ) :
                include_once( $file );
            endforeach;
        endforeach;
    }

}
