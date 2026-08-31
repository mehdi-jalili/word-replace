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

spl_autoload_register( 'w_replace_autoloader' );