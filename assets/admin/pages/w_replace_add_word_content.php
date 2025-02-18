<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap">
	<h3 class="title"><?php esc_html_e('Add New Rule', 'word-replace'); ?></h3>
	<p><?php esc_html_e('The real-time masking find and replace rules will be applied prior to the website being rendered in the browser.', 'word-replace'); ?></p>
	
	<?php include W_REPLACE_PLUGIN_DIR . '/assets/admin/template-parts/w_replace_new_rule_form.php'; ?>

	<?php
		$all_rules_instance = new w_replace_replacement_logic();
		$all_rules = $all_rules_instance->re_order_rules_logic();
	?>
	<div class="clear"></div>
</div>

