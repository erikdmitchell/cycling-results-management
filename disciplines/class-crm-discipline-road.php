<?php

class CRM_Discipline_Road extends CRM_Discipline {

    public function __construct() {
        add_filter( 'race_results_metabox_rider_output_road', array( $this, 'race_results_metabox' ), 10, 2 );
        add_filter( 'crm_insert_race_result_road', array( $this, 'clean_results' ), 10, 3 );
    }

    public function clean_results( $meta_values, $race, $args ) {
        global $uci_results_add_races;

        if ( $args['type'] == 'team' ) {
            return '';
        }

        switch ( $args['type'] ) :
            case 'team':
                $meta_values = '';
                break;
            case 'mountain':
                $meta_values['place'] = $meta_values['rank'];
                $meta_values['points'] = $meta_values['result'];
                unset( $meta_values['rank'] );
                unset( $meta_values['result'] );
                break;
            case 'points':
                $meta_values['place'] = $meta_values['rank'];
                $meta_values['points'] = $meta_values['result'];
                unset( $meta_values['rank'] );
                unset( $meta_values['result'] );
                break;
            case 'general':
                $meta_values['place'] = $meta_values['rank'];
                $meta_values['time'] = $meta_values['result'];
                unset( $meta_values['rank'] );
                unset( $meta_values['result'] );
                break;
            case 'result':
                $meta_values['place'] = $meta_values['rank'];
                $meta_values['time'] = $meta_values['result'];
                unset( $meta_values['rank'] );
                unset( $meta_values['result'] );
                break;
        endswitch;

        // append rider id //
        // $meta_values['rider_id']=$uci_results_add_races->get_rider_id($meta_values['name'], $meta_values['nat'], true);
        // remove what we do not need //
        unset( $meta_values['name'] );
        unset( $meta_values['nat'] );
        unset( $meta_values['team'] );
        unset( $meta_values['age'] );

        // append type //
        foreach ( $meta_values as $key => $meta_value ) :
            $meta_values[ $args['type'] . '_' . $key ] = $meta_value;
            unset( $meta_values[ $key ] );
        endforeach;

        return $meta_values;
    }

    public function race_results_metabox( $output, $post_id ) {
        $output = array( 'result_place', 'name', 'nat', 'result_time' );

        return $output;
    }

}

