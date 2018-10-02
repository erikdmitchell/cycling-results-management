<?php

class CRM_Related_Races_Meta_Box {

    public function __construct() {
        if ( is_admin() ) :
            add_action( 'load-post.php', array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        endif;
        
        add_action( 'wp_ajax_add_related_races_to_race', array( $this, 'ajax_add_related_races_to_race' ) );
        add_action( 'wp_ajax_remove_related_race', array( $this, 'ajax_remove_related_race' ) );        
        add_action( 'wp_ajax_search_related_races', array( $this, 'ajax_search_related_races' ) );        
        add_action( 'wp_ajax_show_related_races_box', array( $this, 'ajax_show_related_races_box' ) );
    }

    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );
    }

    /**
     * admin_scripts_styles function.
     *
     * @access public
     * @param mixed $hook
     * @return void
     */
    public function admin_scripts_styles( $hook ) {
        wp_enqueue_script( 'uci-results-related-races-admin', CRM_ADMIN_URL . '/js/related-races.js', array( 'jquery' ), '0.1.0', true );
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        $post_types = array( 'races' );

        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'related_races',
                __( 'Related Races', 'uci-results' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'normal',
                'default'
            );
        }
    }

    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
        $prefix = 'race';

        // Add an nonce field so we can check for it later. //
        wp_nonce_field( 'update_related_races', 'uci_results_admin_related_races' );
        add_thickbox();

        $related_races = crm_get_related_races( $post->ID );
        $related_race_id = crm_get_related_race_id( $post->ID );
        ?>
        
        <div class="uci-results-metabox related-races">
            <?php foreach ( $related_races as $race ) : ?>
                <div id="race-<?php echo $race->ID; ?>" class="row">
                    <div class="race-name"><?php echo $race->post_title; ?></div>
                    <div class="race-date"><?php echo date( get_option( 'date_format' ), strtotime( $race->race_date ) ); ?></div>
                    <div class="action-icons"><a href="#" class="remove-related-race" data-id="<?php echo $race->ID; ?>" data-rrid="<?php echo $related_race_id; ?>"><span class="dashicons dashicons-dismiss"></span></a></div>
                </div>
            <?php endforeach; ?>
            <div class="row add-race">
                <a id="add-related-race" href="#" data-id="<?php echo $post->ID; ?>"><span class="dashicons dashicons-plus-alt"></span></a>
            </div>
        </div>
        
        <?php
    }
    
    /**
     * AJAX shore related races box.
     *
     * @access public
     * @return void
     */
    public function ajax_show_related_races_box() {        
        echo cycling_results_management()->admin->get_admin_page( 'add-related-races' );

        wp_die();
    } 
    
    /**
     * AJAX search related races.
     *
     * @access public
     * @return void
     */
    public function ajax_search_related_races() {
        global $wpdb;

        $html = null;
        $query = $_POST['query'];       
        $races = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_title LIKE '%$query%' AND post_type = 'races'");
        $related_races_ids = crm_get_related_races_ids( $_POST['id'] );

        // build out html //
        foreach ( $races as $race ) :
            if ( $race->ID == $_POST['id'] || in_array( $race->ID, $related_races_ids ) ) {
                continue; // skip if current race or already linked
            }

            $country = array_pop( wp_get_post_terms( $race->ID, 'country' ) );
            $class = array_pop( wp_get_post_terms( $race->ID, 'race_class' ) );
            $season = array_pop( wp_get_post_terms( $race->ID, 'season' ) );

            $html .= '<tr>';
                $html .= '<th scope="row" class="check-column"><input id="cb-select-' . $race->ID . '" type="checkbox" name="races[]" value="' . $race->ID . '"></th>';
                $html .= '<td class="race-date">' . date( get_option( 'date_format' ), strtotime( get_post_meta( $race->ID, '_race_start', true ) ) ) . '</td>';
                $html .= '<td class="race-name">' . $race->post_title . '</td>';
                $html .= '<td class="race-nat">' . $country->name . '</td>';
                $html .= '<td class="race-class">' . $class->name . '</td>';
                $html .= '<td class="race-season">' . $season->name . '</td>';
            $html .= '</tr>';
        endforeach;

        echo $html;

        wp_die();
    } 
    
    /**
     * AJAX add related races to race.
     *
     * @access public
     * @return void
     */
    public function ajax_add_related_races_to_race() {
        global $wpdb;

        parse_str( $_POST['form'], $form );

        $html = null;
        $races = $form['races'];
        $related_race_id = crm_get_related_race_id( $_POST['id'] );
        $last_related_race_id = $wpdb->get_var( "SELECT MAX(related_race_id) FROM {$wpdb->prefix}crm_related_races" );

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
            $wpdb->insert( "{$wpdb->prefix}crm_related_races", $data );
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
     * AJAX remove related race.
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

}

new CRM_Related_Races_Meta_Box();