<?php

class CRM_Discipline_Cross extends CRM_Discipline {

    public function __construct() {
        add_filter( 'crm_insert_race_result_cyclocross', array( $this, 'clean_results' ), 10, 3 );
    }

    public function clean_results( $meta_values, $race, $args ) {
        $meta_values['result_place'] = $meta_values['result_rank'];
        
        unset( $meta_values['result_rank'] );
        
        // add points based on race class.
        $race_class = crm_race_class($race->ID);
        $meta_values['result_uci_points'] = $this->get_uci_points($race_class, $meta_values['result_place']);

        return $meta_values;
    }
    
    protected function get_uci_points($class = '', $place = 0) {
        $points = 0;
        
        $points_array = array(
            'C2' => array(40,30,20,15,10,8,6,4,2,1),
            'C1' => array(80,60,40,30,25,20,17,15,12,10,8,6,4,2,1),
            'CN' => array(100,60,40,30,25,20,15,10,5,3),
            'CC' => array(100,60,40,30,25,20,17,15,12,10,8,6,4,2,1),
            'CDM' => array(200,160,140,120,110,110,90,80,70,60,58,56,54,53,50,48,46,44,42,40,39,38,37,36,35,34,33,32,31,30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,5),
        );
        
        if ('CDM' == $class && $place >= 51) :
            return 5;
        else :
            $key = $place - 1;
            
            if (isset($points_array[$class][$key])) :
                return $points_array[$class][$key]; 
            endif;
        endif;
        
        return $place;
    }

}

