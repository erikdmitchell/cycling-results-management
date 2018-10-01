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
        ?>
        
        <table class="uci-results-rider-results widefat fixed striped">
            <thead>
                <tr>
                    <th class="place">Place</th>
                    <th class="race">Race</th>
                    <th class="age">Age</th>
                    <th class="result">Result</th>
                    <th class="par">Par</th>
                    <th class="pcr">Pacr</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $results as $result ) : ?>
                    <tr id="rider-">
                        <td class="place"><?php echo $result['place']; ?></td>
                        <td class="name"><?php echo $result['race_name']; ?></td>
                        <td class="age"><?php echo $result['age']; ?></td>
                        <td class="result"><?php echo $result['result']; ?></td>
                        <td class="par"><?php echo $result['par']; ?></td>
                        <td class="pcr"><?php echo $result['pcr']; ?></td> 
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
    }
}

new CRM_Rider_Results_Meta_Box();
