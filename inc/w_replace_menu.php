<?php

class w_replace_menu {

    public function w_replace_menu_pages() {
        add_menu_page(
            __('Word Replace', 'word-replace'),
            __('Word Replace', 'word-replace'),
            'manage_options',
            'word-replace',
            array($this, 'w_replace_mainpage_content'),
            'dashicons-admin-users'
        );
        
        add_submenu_page(
            'word-replace',
            __('Add', 'word-replace'),
            __('Add', 'word-replace'),
            'manage_options',
            'word-replace-add',
        array($this, 'w_replace_addpage_content')
        );
    }

    
    public function w_replace_mainpage_content() {
        include_once W_REPLACE_PLUGIN_DIR . "assets/admin/pages/w_replace_mainpage_content.php";
    }
        
    public function w_replace_addpage_content() {
        include_once W_REPLACE_PLUGIN_DIR . "assets/admin/pages/w_replace_add_word_content.php";
    }
        
}
