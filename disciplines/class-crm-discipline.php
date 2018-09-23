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
                    $class = 'CRM_Discipline' . ucfirst( $filename );

                    if ( file_exists( __DIR__ . '/' . $entry ) ) :
                        include_once( __DIR__ . '/' . $entry );
                        new $class();
                    endif;
                endif;
            endwhile;

            closedir( $handle );
        endif;
    }

}

new CRM_Discipline();

