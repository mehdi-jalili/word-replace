<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="notice-container"></div>

<form method="post" action="" name="add_new_rule_replace_plugin" id="add_new_rule_replace_plugin">
	<?php wp_nonce_field('add_new_rule_nonce_action', 'w_replace_add_new_rule_nonce'); ?>
		<table class="m-t-20">
			<tr>
				<div class="d-flex justify-center">
					<th class="t-head"><?php esc_html_e('Text', 'word-replace'); ?></th>
					<td	>
						<textarea class="regular-text" placeholder="<?php echo esc_attr(__('Enter the target Word. Example: Hello World', 'word-replace')); ?>" name="target_word" id="target_word"></textarea>
					</td>
				</div>
			</tr>
			<tr>
				<td><br></td>
			</tr>
			<tr>
				<div class="d-flex justify-center">
					<th class="t-head"><?php esc_html_e('Replace with', 'word-replace'); ?></th>
					<td>
						<textarea class="regular-text" placeholder="<?php echo esc_attr(__('Enter the text you want to Replace. Example: Hi World', 'word-replace')); ?>" name="word_replace_with" id="word_replace_with"></textarea>
					</td>
				</div>
			</tr>
			<tr>
				<td><br></td>
			</tr>
			<tr>
				<th class="t-head"><?php esc_html_e('Where To Replace', 'word-replace'); ?></th>
				<td>
					<span>
						<select class="where-to-replace-select" required="required" value="" name="where_to_replace_rule" id="where_to_replace_rule">
							<option value="all"><?php esc_attr_e('All over the website', 'word-replace'); ?></option>
							<option value="page"><?php esc_attr_e('Page', 'word-replace'); ?></option>
							<option value="post"><?php esc_attr_e('Post', 'word-replace'); ?></option>
						</select>
					</span>
				
					<span id="page_replace_rule">
						<span class="t-head"><strong><?php esc_html_e('Select a Page', 'word-replace'); ?></strong></span>
						<select class="" value="" name="page_replace_rule" id="page_replace_rule_dropdown">
							<option value="all"><?php esc_attr_e('All', 'word-replace'); ?></option>
							<?php

								$pages_post_type = new w_replace_retrieve_posts;
								$post_type_page = 'page';
								$pages_post_type = $pages_post_type->pages_dropdown($post_type_page);
								
								foreach($pages_post_type as $page_post_type){
									if($page_post_type['title'] == ''){
										$page_post_type['title'] = 'No Title';
									}
									?>
									<option name="<?php echo esc_attr( $page_post_type['ID'] ); ?>" value="<?php echo esc_attr( $page_post_type['ID'] ); ?>">
    									<?php echo esc_html( $page_post_type['title'] ); ?>
									</option>

								<?php
								}
							?>						
						</select>
					</span>

					<span id="post_replace_rule">
						<span class="t-head"><strong><?php esc_html_e('Select a Post', 'word-replace'); ?></strong></span>
						<select class="" value="" name="post_replace_rule" id="post_replace_rule_dropdown">
							<option value="all"><?php esc_attr_e('All', 'word-replace'); ?></option>
							<?php
							$posts_post_type = new w_replace_retrieve_posts;
							$post_type_post = 'post';
							$posts_post_type = $posts_post_type->pages_dropdown($post_type_post);
							
							foreach($posts_post_type as $post_post_type){
								if($post_post_type['title'] == ''){
									$post_post_type['title'] = 'No Title';
								}
								?>								
								<option name="<?php echo esc_attr( $post_post_type['ID'] ); ?>" value="<?php echo esc_attr( $post_post_type['ID'] ); ?>">
    									<?php echo esc_html( $post_post_type['title'] ); ?>
									</option>
							<?php
								}
							?>	
						</select>
					</span>
				</td>
			</tr>
		</table>
		<br>
		<div class="section-submit-button">
			<button class="button-primary m-t-20" id="addNewRuleBtn">
				<?php esc_html_e('Add New Rule', 'word-replace'); ?>
			</button>
		</div>
</form>
