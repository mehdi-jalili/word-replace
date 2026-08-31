<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="RemoveRuleModal" class="w-replace-modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        
        <h3><?php esc_html_e('Are You Sure?', 'word-replace'); ?></h3>
        
        <div class="popup-button-cnt">

            <form method="post" name="w_replace_delete_rule" action="">
            <?php wp_nonce_field('delete_rule_nonce_action', 'w_replace_delete_rule_nonce'); ?>
                <button type="submit"
                        name="submit_delete_rule"
                        class="delete-rule-btn button-primary m-t-20"
                        id="deleteRuleBtn"
                        value="0">
                    <?php esc_html_e('Delete', 'word-replace'); ?>
                </button>
            </form>

            <button type="button"
				name="cancel_delete_rule"
				class="button-secondary m-t-20"
				id="cancelRuleBtn">
				<?php esc_html_e('Cancel', 'word-replace'); ?>
            </button>

        </div>
    </div>
</div>
