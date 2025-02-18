<?php

class w_replace_delete_rule {

    private $row_id;

    public function delete_rule_handler(){
        
        if ( ! isset( $_POST['w_replace_delete_rule_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['w_replace_delete_rule_nonce'] ) ), 'w_replace_delete_rule_nonce' ) ) {
            wp_send_json_error( array( 'error' => 'Invalid nonce verification.' ) );
            return;
        }
   
        if ( isset( $_POST['rowId'] ) ) {
            $this->row_id = sanitize_text_field( wp_unslash( $_POST['rowId'] ) );
            $delete_result = w_replace_models::delete_rule($this->row_id);
        
            if ( $delete_result === true ) {
                wp_send_json_success( array( 'result' => 'Row deleted successfully' ) );
            } else {
                wp_send_json_error( array( 'message' => 'Failed to delete row' ) );
            }
        } else {
            wp_send_json_error(array('message' => 'Invalid request'));
        }
    }
}
