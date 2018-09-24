<?php

class CRM_Discipline {

    public function __construct() {
        $this->load_subclasses();
    }

    function load_subclasses() {
        if ( $handle = opendir( __DIR__ ) ) :
            while ( false !== ( $entry = readdir( $handle ) ) ) :

                if ( $entry != '.' && $entry != '..' && $entry != 'class-crm-discipline.php' ) :
                    $filename = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $entry );
                    $class = $this->get_class_name( $filename );

                    if ( file_exists( __DIR__ . '/' . $entry ) ) :
                        include_once( __DIR__ . '/' . $entry );
                        new $class();
                    endif;
                endif;
            endwhile;

            closedir( $handle );
        endif;
    }

    protected function get_class_name( $filename ) {
        $class = '';
        $class_arr = explode( '-', $filename );
        $_class = array_shift( $class_arr );
        $class_arr[0] = strtoupper( $class_arr[0] );
        $class_arr = array_map( 'ucwords', $class_arr );
        $class = implode( '_', $class_arr );

        return $class;
    }

}

new CRM_Discipline();

