<?php

class CRM_Race_Details_Meta_Box {

    public function __construct() {
        if ( is_admin() ) :
            add_action( 'load-post.php', array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        endif;
    }

    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
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
        wp_enqueue_script( 'flatpickr-script', CRM_URL . 'admin/js/flatpickr.min.js', array( 'jquery' ), '2.4.8', true );

        wp_enqueue_style( 'flatpickr-style', CRM_URL . 'admin/css/flatpickr.min.css', '', '2.4.8' );

        wp_enqueue_script( 'crm-races-mb-script', CRM_ADMIN_URL . 'js/races-metabox.js', array( 'jquery-ui-datepicker' ) );
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'races' );

        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'race_details',
                __( 'Race Details', 'uci-results' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
        $prefix = '_race_';

        // Check if our nonce is set.
        if ( ! isset( $_POST['uci_results_admin_race_details'] ) ) {
            return $post_id;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['uci_results_admin_race_details'], 'update_race_details' ) ) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        // OK, it's safe for us to save the data now. //
        $data = $_POST['race'];
        $data = array_map( 'sanitize_text_field', $data ); // sanitize

        // append prefix to keys //
        foreach ( $data as $key => $value ) :
            $data[ $prefix . $key ] = $value;
            unset( $data[ $key ] );
        endforeach;

        // Update the meta //
        foreach ( $data as $meta_key => $meta_value ) :
            update_post_meta( $post_id, $meta_key, $meta_value );
        endforeach;
    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
        $prefix = 'race';

        // Add an nonce field so we can check for it later. //
        wp_nonce_field( 'update_race_details', 'uci_results_admin_race_details' );

        // get values in array by matching key w/ preifx //
        $meta = array();
        $post_meta = get_post_meta( $post->ID );
        $default_meta = array(
            'start' => '',
            'end' => '',
        );

        foreach ( $post_meta as $key => $value ) :
            $exp_key = explode( '_', $key );

            if ( $exp_key[1] == $prefix ) {
                $meta[ $exp_key[2] ] = $value[0];
            }
        endforeach;

        $meta = wp_parse_args( $meta, $default_meta );

        // Display the form, using the current value.
        ?>
        
        <div class="uci-results-metabox">
            <div class="row">
                <label for="start"><?php _e( 'Start Date', 'uci-results' ); ?></label>
                <input type="text" id="start" name="race[start]" class="uci-results-datepicker date" value="<?php echo esc_attr( $meta['start'] ); ?>" size="25" />
            </div>
            
            <div class="row">
                <label for="end-date"><?php _e( 'End Date', 'uci-results' ); ?></label>
                <input type="text" id="end" name="race[end]" class="uci-results-datepicker date" value="<?php echo esc_attr( $meta['end'] ); ?>" size="25" />
            </div>
        </div>
        
        <?php
    }

}

new CRM_Race_Details_Meta_Box();
