<<<<<<< HEAD
<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function w_replace_autoloader( string $class_name ): void {

    if ( ! str_starts_with( $class_name, 'w_replace' ) ) {
        return;
    }

    $class_file = W_REPLACE_PLUGIN_INC . '/' . $class_name . '.php';

    if ( is_readable( $class_file ) ) {
        require_once $class_file;
    }
}

=======
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

>>>>>>> a17dcb73cc217c0ca88508842ad816bfa013fb83
spl_autoload_register( 'w_replace_autoloader' );