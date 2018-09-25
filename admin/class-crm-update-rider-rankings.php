<?php

/**
 * CRM_Update_Rider_Rankings class.
 */
class CRM_Update_Rider_Rankings {

    /**
     * race
     *
     * (default value: '')
     *
     * @var string
     * @access protected
     */
    protected $race = '';

    /**
     * results
     *
     * (default value: '')
     *
     * @var string
     * @access protected
     */
    protected $results = '';

    /**
     * table
     *
     * (default value: '')
     *
     * @var string
     * @access private
     */
    private $table = '';


    /**
     * __construct function.
     *
     * @access public
     * @param string $race (default: '')
     * @param string $results (default: '')
     * @return void
     */
    public function __construct( $race = '', $results = '' ) {
        global $wpdb;

        if ( empty( $race ) || empty( $results ) ) {
            return;
        }

        $this->table = "{$wpdb->prefix}crm_rider_rankings";
        $this->race = $race;
        $this->results = $results;

        $this->update();
    }


    /**
     * Update.
     *
     * @access protected
     * @return void
     */
    protected function update() {
        global $wpdb;

        // update individual riders.
        foreach ( $this->results as $result ) :
            $discipline = $this->race->discipline;
            $season = $this->race->season;
            $dbid = $wpdb->get_var( "SELECT id FROM $this->table WHERE rider_id = " . $result['rider_id'] . " AND discipline = '$discipline' AND season = '$season'" );

            if ( null === $dbid ) :
                $podiums = 0;
                $wins = 0;

                if ( $this->is_win( $result['place'] ) ) {
                    $wins = 1;
                }

                if ( $this->is_podium( $result['place'] ) ) {
                    $podiums = 1;
                }

                $data = array(
                    'rider_id' => $result['rider_id'],
                    'points' => $result['uci_points'],
                    'season' => $season,
                    'discipline' => $discipline,
                    'wins' => $wins,
                    'podiums' => $podiums,
                );

                $wpdb->insert( $this->table, $data );
            else :
                $data = '';

                // get races in season.
                $race_ids = get_posts(
                    array(
                        'posts_per_page' => -1,
                        'post_type' => 'races',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'season',
                                'field' => 'slug',
                                'terms' => $season,
                            ),
                        ),
                        'fields' => 'ids',
                    )
                );

                $data = $this->get_rider_results_data( $result['rider_id'], $race_ids );
                $where = array( 'id' => $dbid );
                $wpdb->update( $this->table, $data, $where );
            endif;

        endforeach;

        $this->update_rank( $discipline, $season );
    }

    /**
     * Get rider results data from db.
     *
     * @access private
     * @param int   $rider_id (default: 0)
     * @param array $race_ids (default: array())
     * @return void
     */
    private function get_rider_results_data( $rider_id = 0, $race_ids = array() ) {
        global $wpdb;

        $total_points = 0;
        $wins = 0;
        $podiums = 0;

        foreach ( $race_ids as $race_id ) :
            $place = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $race_id AND meta_key = '_rider_{$rider_id}_result_place'" );
            $points = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $race_id AND meta_key = '_rider_{$rider_id}_result_uci_points'" );

            $total_points = $total_points + $points;

            if ( $this->is_win( $place ) ) {
                $wins++;
            }

            if ( $this->is_podium( $place ) ) {
                $podiums++;
            }
        endforeach;

        $data = array(
            'points' => $total_points,
            'wins' => $wins,
            'podiums' => $podiums,
        );

        return $data;
    }

    /**
     * Check if place is win.
     *
     * @access private
     * @param int $place (default: 0)
     * @return void
     */
    private function is_win( $place = 0 ) {
        if ( 1 == $place ) {
            return true;
        }

        return false;
    }

    /**
     * Check if place is podium.
     *
     * @access private
     * @param int $place (default: 0)
     * @return void
     */
    private function is_podium( $place = 0 ) {
        if ( $place >= 1 && $place <= 3 ) {
            return true;
        }

        return false;
    }

    /**
     * Update overall rank in db.
     *
     * @access private
     * @param string $discipline (default: '')
     * @param string $season (default: '')
     * @return void
     */
    private function update_rank( $discipline = '', $season = '' ) {
        global $wpdb;

        if ( empty( $discipline ) || empty( $season ) ) {
            return;
        }

        $rank = 1;
        $rows = $wpdb->get_results( "SELECT * FROM $this->table WHERE discipline = '$discipline' AND season = '$season' ORDER BY points DESC" );

        foreach ( $rows as $row ) {
            $data = array( 'rank' => $rank );
            $where = array( 'id' => $row->id );
            $wpdb->update( $this->table, $data, $where );

            $rank ++;
        }
    }

}
