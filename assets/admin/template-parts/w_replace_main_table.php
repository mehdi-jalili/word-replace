<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<table class="wp-list-table widefat fixed striped table-view-list users">
			
	<thead>
		<tr>
			<th scope="col"><b><?php esc_html_e('Targeted word', 'word-replace'); ?></b></th>
			<th scope="col"><b><?php esc_html_e('Replace With', 'word-replace'); ?></b></th>
			<th scope="col"><b><?php esc_html_e('Where', 'word-replace'); ?></b></th>
			<th scope="col"><b><?php esc_html_e('Page Name', 'word-replace'); ?></b></th>
			<th scope="col"><b><?php esc_html_e('Post Name', 'word-replace'); ?></b></th>
			<th scope="col"><b><?php esc_html_e('Action', 'word-replace'); ?></b></th>
		</tr>
	</thead>

	<tbody id="the-list" data-wp-lists="list:user">

		<?php $w_replace_results = w_replace_models::get_rules(); 
		
			if ( empty( $w_replace_results ) ) { ?>

				<tr>
					<td colspan="6" style="text-align:center;">
						<b><?php esc_html_e( 'No records found', 'word-replace' ); ?></b>
					</td>
				</tr>

			<?php } else {
					
			foreach ($w_replace_results as $w_replace_row) { ?>

				<tr>	
					<td><?php echo esc_html( $w_replace_row->target_word ); ?></td>
            		<td><?php echo esc_html( $w_replace_row->word_replace ); ?></td>
                    <td><?php echo esc_html( $w_replace_row->where_to_replace ); ?></td>
            		<td><?php echo esc_html( $w_replace_row->page_name ?: '-' ); ?></td>
            		<td><?php echo esc_html( $w_replace_row->post_name ?: '-' ); ?></td>
					<td>
						<span class="delete">
							<button type="button"
								name="submit_delete_rule"
								class="delete-rule-word button-primary m-t-20"
								id="<?php echo esc_html($w_replace_row->id); ?>"
								onclick="getButtonId(this.id)">
									<?php esc_html_e('Remove', 'word-replace'); ?>
							</button>
						</span>
					</td>
				</tr>
				<?php
			} 
		} ?>

				
	</tbody>

</table>
