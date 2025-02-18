<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap">
	<?php $w_replace_check = new w_replace_replacement_logic(); ?>
	
	<h1><?php esc_html_e('Word Replace', 'word-replace'); ?></h1>
	<br>

	<a href="<?php echo esc_url( site_url() . '/wp-admin/admin.php?page=word-replace-add' ); ?>" class="page-title-action"><?php esc_html_e('Add New Rule', 'word-replace'); ?></a>

	<hr class="wp-header-end">
	<div id="notice-container"></div>

		<div id="tableContainer">
			<?php include W_REPLACE_PLUGIN_T_PARTS . '/w_replace_main_table.php'; ?>
		</div>

	<?php include W_REPLACE_PLUGIN_T_PARTS . '/w_replace_delete_modal.php'; ?>

	<div class="clear"></div>
</div>
