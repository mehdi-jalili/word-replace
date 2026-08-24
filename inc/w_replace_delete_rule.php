<?php

class w_replace_delete_rule {
    
    private const NONCE_ACTION = 'w_replace_delete_rule_nonce';
    private const NONCE_NAME = 'w_replace_delete_rule_nonce';
    
    private int $row_id = 0;
    
    public function delete_rule_handler(): void {
        // بررسی Nonce
        if (!$this->verify_nonce()) {
            wp_send_json_error(['message' => 'Invalid security token.']);
            return;
        }
        
        // دریافت و اعتبارسنجی ID
        if (!$this->get_and_validate_row_id()) {
            wp_send_json_error(['message' => 'Invalid row ID provided.']);
            return;
        }
        
        // حذف قانون
        $this->delete_rule();
    }
    
    private function verify_nonce(): bool {
        if (!isset($_POST[self::NONCE_NAME])) {
            return false;
        }
        
        $nonce = sanitize_text_field(wp_unslash($_POST[self::NONCE_NAME]));
        return wp_verify_nonce($nonce, self::NONCE_ACTION);
    }
    
    private function get_and_validate_row_id(): bool {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if (!isset($_POST['rowId'])) {
            return false;
        }
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $row_id = sanitize_text_field(wp_unslash($_POST['rowId']));
        
        if (!is_numeric($row_id) || $row_id <= 0) {
            return false;
        }
        
        $this->row_id = (int)$row_id;
        return true;
    }
    
    private function delete_rule(): void {
        try {
            $deleted = w_replace_models::delete_rule($this->row_id);
            
            if ($deleted) {
                wp_send_json_success([
                    'message' => 'Row deleted successfully',
                    'row_id' => $this->row_id,
                ]);
            } else {
                wp_send_json_error(['message' => 'Failed to delete row.']);
            }
        } catch (Exception $e) {
            wp_send_json_error(['message' => 'An error occurred while deleting.']);
        }
    }
}
