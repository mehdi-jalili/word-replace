<?php

class w_replace_models
{

    public function __construct(){
        $this->check_table();
    }


    public function check_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'w_replace_rules';
        $cache_key = 'w_replace_db_exists';
        $table_exists = wp_cache_get( $cache_key );
        
        if ( false === $table_exists ) {

            $table_exists = $wpdb->get_results( $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( $table_name ) ) );
            
            wp_cache_set( $cache_key, $table_exists );
        
        }
    }


    public function create_table(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'w_replace_rules';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            target_word varchar(255) NOT NULL,
            word_replace varchar(255) NOT NULL,
            where_to_replace varchar(5) NOT NULL,
            page_id int(6),
            page_name varchar(255),
            post_id int(6),
            post_name varchar(255),
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function Set_new_rule($new_rule_args){
        global $wpdb;
        $table_name = $wpdb->prefix . 'w_replace_rules';

        $this->check_table();

        $result = $wpdb->insert(
            $table_name, array(
                'target_word' => $new_rule_args['target_word'],
                'word_replace' => $new_rule_args['word_replace_with'],
                'where_to_replace' => $new_rule_args['where_to_replace_rule'],
                'page_id' => $new_rule_args['page_id'],
                'page_name' => $new_rule_args['page_name'],
                'post_id' => $new_rule_args['post_id'],
                'post_name' => $new_rule_args['post_name'],
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s'
            )
        );

        if ($result === false) {
            return false;
        } else {
            return true;
        }
    }

    public static function delete_rule( $row_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'w_replace_rules';
        $cache_key_delete = 'w_replace_db_delete';
    	$table_row_delete = wp_cache_get( $cache_key_delete );

	    if ( false === $table_row_delete ) {

            $delete_result = $wpdb->delete( $table_name, array( 'ID' => $row_id ), array( '%d' ) );
            wp_cache_set( $cache_key_delete, $table_row_delete );
        
        }

        if ( $delete_result ) {
            return true;
        } else {
            $wpdb->print_error();
            return false;
        }
    }

    public static function get_rules() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'w_replace_rules';
        $rules = null;
        $cache_key_get = 'w_replace_db_get';
    	$table_row_get = wp_cache_get( $cache_key_get );

	    if ( false === $table_row_get ) {

            $rules = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}w_replace_rules" );
            wp_cache_set( $cache_key_get, $table_row_get );
        
        }

        if ( $rules === null ) {
            $this->create_table();
            return null;
        }

        return $rules;
    }
}
