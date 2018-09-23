<?php

class CRM_Discipline_Cross extends CRM_Discipline {

    public function __construct() {
        add_filter( 'crm_insert_race_result_cyclocross', array( $this, 'clean_results' ), 10, 3 );
    }

    public function clean_results( $meta_values, $race, $args ) {
        $meta_values['result_place'] = $meta_values['result_rank'];
        unset( $meta_values['result_rank'] );
        
        // add points based on race class
        print_r($meta_values);
        print_r($race);
        print_r($args);

        /*
        if (!isset($result->par) || empty($result->par) || is_null($result->par)) :
            $par=0;
        else :
            $par=$result->par;
        endif;

        if (!isset($result->pcr) || empty($result->pcr) || is_null($result->pcr)) :
            $pcr=0;
        else :
            $pcr=$result->pcr;
        endif;
        */

        return $meta_values;
    }
    
    

}

