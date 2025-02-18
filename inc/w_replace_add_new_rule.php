<?php 

class w_replace_add_new_rule
{

    private $target_word;
    private $word_replace_with;
    private $where_to_replace_rule;
    private $page_id;
    private $post_id;
    private $page_name;
    private $post_name;
    private $new_rule_args;


    public function add_new_rule(){
        
        if ( ! isset( $_POST['w_replace_add_new_rule_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['w_replace_add_new_rule_nonce'] ) ), 'w_replace_add_new_rule_nonce' ) ) {
            wp_send_json_error( array( 'error' => 'Invalid nonce verification.' ) );
            return;
        }
    
        
        if ( isset( $_POST['target_word'] ) && ! empty( $_POST['target_word'] ) ) {
            $this->target_word = sanitize_text_field( wp_unslash( $_POST['target_word'] ) );
        }

        if ( isset( $_POST['word_replace_with'] ) && ! empty( $_POST['word_replace_with'] ) ) {
            $this->word_replace_with = sanitize_text_field( wp_unslash( $_POST['word_replace_with'] ) );
        }

        if ( isset( $_POST['where_to_replace_rule'] ) && ! empty( $_POST['where_to_replace_rule'] ) ) {
            $this->where_to_replace_rule = sanitize_text_field( wp_unslash( $_POST['where_to_replace_rule'] ) );
        }
        
        
        if ($this->where_to_replace_rule == 'page') {

            if ( isset( $_POST['page_replace_rule'] ) && ! empty( $_POST['page_replace_rule'] ) ) {
                $this->page_id = sanitize_text_field( wp_unslash( $_POST['page_replace_rule'] ) );
            }

            if ( isset( $_POST['page_name'] ) && ! empty( $_POST['page_name'] ) ) {
                $this->page_name = sanitize_text_field( wp_unslash( $_POST['page_name'] ) );
            }
            
            $this->post_id = null;
            $this->post_name = null;
            
        } elseif ($this->where_to_replace_rule == 'post') {

            if ( isset( $_POST['post_replace_rule'] ) && ! empty( $_POST['post_replace_rule'] ) ) {
                $this->post_id = sanitize_text_field( wp_unslash( $_POST['post_replace_rule'] ) );
            }

            if ( isset( $_POST['post_name'] ) && ! empty( $_POST['post_name'] ) ) {
                $this->post_name = sanitize_text_field( wp_unslash( $_POST['post_name'] ) );
            }

            $this->page_id = null;
            $this->page_name = null;

        } elseif ($this->where_to_replace_rule == 'all'){

            $this->page_id = null;
            $this->post_id = null;

        }

        $this->new_rule_args = array(
            'target_word' => $this->target_word,
            'word_replace_with' => $this->word_replace_with,
            'where_to_replace_rule' => $this->where_to_replace_rule,
            'page_id' => $this->page_id,
            'page_name' => $this->page_name,
            'post_id' => $this->post_id,
            'post_name' => $this->post_name
        );

        $w_replace_new_rule = new w_replace_models;
        $w_replace_new_rule->check_table();
        $w_new_rule_result = $w_replace_new_rule->Set_new_rule($this->new_rule_args);
        
        if ($w_new_rule_result === true) {
            wp_send_json_success(array('result' => $result));
        } else {
            wp_send_json_error('set failed');
        }

    }

}
