<?php

class w_replace_reg_script {

    public static function enqueue_scripts() {

        wp_register_style('w-replace-admin-style', W_REPLACE_PLUGIN_URL . 'assets/admin/css/adminstyle.css', array(), '1.0.0', '');
        wp_enqueue_style('w-replace-admin-style');
    
        wp_register_script('replace-script', W_REPLACE_PLUGIN_URL . 'assets/admin/js/replace.js', array(), '1.0.0', true);
        wp_enqueue_script('replace-script');

        wp_register_script('replace-modal', W_REPLACE_PLUGIN_URL . 'assets/admin/js/modalDelete.js', array(), '1.0.0', true);
        wp_enqueue_script('replace-modal');

        wp_register_script('replace-ajax-script', W_REPLACE_PLUGIN_URL . 'assets/admin/js/ajax.js', array(), '1.0.0', true);
        wp_enqueue_script('replace-ajax-script');

        wp_localize_script('replace-ajax-script', 'w_replace_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce_add_new' => wp_create_nonce('w_replace_add_new_rule_nonce'), // Make sure this matches in JS
            'nonce_delete' => wp_create_nonce('w_replace_delete_rule_nonce'),
            'add_rule_text' => __('Add New Rule', 'word-replace'),
            'fill_all_fields' => __('Please Fill All the Fields', 'word-replace'),
            'rule_added' => __('Rule Has Been Added', 'word-replace'),
            'repeated_word' => __('Repeated word detected', 'word-replace'),
            'error_occurred' => __('Error occurred', 'word-replace'),
            'delete_rule_text' => __('Delete', 'word-replace'),
            'delete_successful' => __('Rule has been deleted', 'word-replace'),
            'delete_unsuccessful' => __('Error: Delete was not successful', 'word-replace'),
        ));
        
    } 
}
