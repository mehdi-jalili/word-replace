<?php
/**
 * Plugin Name: Word Replace
 * Version: 0.9.1
 * Plugin URI:  https://github.com/mehdi-jalili/word-replace/
 * Description: Easily Replace all the text you want in real-time.
 * Author: Mehdi Jalili
 * Author URI: https://github.com/mehdi-jalili/
 * Requires at least: 6.8
 * Tested up to: 7.1
 * Requires PHP: 8.1
 * Text Domain: word-replace
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 */
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 – GPLv2) as published by
the Free Software Foundation.
*/

 if ( !defined( 'ABSPATH' ) ) {
	die();
}

define( 'W_REPLACE_PLUGIN_DIR', plugin_dir_path(__FILE__) );
define( 'W_REPLACE_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'W_REPLACE_PLUGIN_INC', W_REPLACE_PLUGIN_DIR . 'inc' );
define( 'W_REPLACE_PLUGIN_T_PARTS', W_REPLACE_PLUGIN_DIR . 'assets/admin/template-parts/' );

require_once "autoloader.php";


function w_replace_activate_plugin(){
    $w_replace_table_check = new w_replace_models();
    $w_replace_table_check->check_table();
}

register_activation_hook( __FILE__, 'w_replace_activate_plugin' );



$w_replace_menu = new w_replace_menu();
add_action('admin_menu', [$w_replace_menu, 'w_replace_menu_pages']);

$w_replace_enqueue_scripts = new w_replace_reg_script();
add_action('admin_enqueue_scripts', [$w_replace_enqueue_scripts, 'enqueue_scripts']);

$w_replace_ajax_new_rule = new w_replace_add_new_rule();
add_action('wp_ajax_add_new_rule', [$w_replace_ajax_new_rule, 'add_new_rule']);

$w_replace_ajax_delete_rule = new w_replace_delete_rule();
add_action('wp_ajax_delete_rule', [$w_replace_ajax_delete_rule, 'delete_rule_handler']);


if (!is_admin()) {
	$w_replace_logic_apply = new w_replace_replacement_logic();
}