<?php

class CRM_Rider_Results_Meta_Box {

    public function __construct() {
        if ( is_admin() ) :
            add_action( 'load-post.php', array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        endif;
    }

    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
    }

    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'riders' );

        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'riders_results',
                __( 'Rider Results', 'uci-results' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'normal',
                'default'
            );
        }
    }

    public function render_meta_box_content( $post ) {
        $results = crm_get_rider_results( array( 'rider_id' => $post->ID ) );
        $output = apply_filters( 'rider_metabox_rider_output', array( 'race_name', 'place', 'uci_points', 'race_date', 'race_season' ), $post->ID );
        ?>
        
        <table class="uci-results-rider-results widefat fixed striped">
            <thead>
                <?php foreach ( $output as $slug ) : ?>
                    <th class="<?php echo $slug; ?>"><?php echo ucwords( str_replace( '_', ' ', $slug ) ); ?></th>
                <?php endforeach; ?>
            </thead>
            <tbody>
                <?php foreach ( $results as $result ) : ?>
                    <tr>
                        <?php foreach ( $output as $slug ) : ?>
                            <?php if ( isset( $result[ $slug ] ) ) : ?>
                                <td class="<?php echo $slug; ?>"><?php echo $result[ $slug ]; ?></td>
                            <?php else : ?>
                                <td class="<?php echo $slug; ?>">&nbsp;</td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
    }
}

new CRM_Rider_Results_Meta_Box();
