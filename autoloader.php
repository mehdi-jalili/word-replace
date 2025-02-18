<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function w_replace_autoloader( $class_name ) {

    if ( false !== strpos( $class_name, 'w_replace' ) ) {
        $class_file = W_REPLACE_PLUGIN_INC . "/$class_name.php";

        if ( file_exists( $class_file ) ) {
            require_once $class_file;
        }else{
            die( esc_html( "$class_file not found" ) );  
        }
    }

}

spl_autoload_register( 'w_replace_autoloader' );