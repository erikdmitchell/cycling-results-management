<div class="crm-settings">

	<form action="" method="post">
		<input type="hidden" name="save_settings" value="1" />

		<section class="pages">
		  <h2>Pages</h2>

		  <p>Manage the WordPress pages assigned to each CRM page.</p>

		  <table class="form-table">
		  	<tbody>
		      <tr>
		      	<th scope="row" valign="top">
							<label for="single_rider_page_id">Single Rider Page:</label>
						</th>
						<td>
							<?php wp_dropdown_pages(array(
								'name' => 'single_rider_page_id',
								'show_option_none' => '-- '.__('Select One', 'crm').' --',
								'selected' => cycling_results_management()->pages['single-rider']
							)); ?>
							<a target="_blank" href="<?php echo admin_url('post.php?post='.cycling_results_management()->pages['single-rider'].'&action=edit'); ?>" class="button button-secondary">Edit Page</a>
							&nbsp;
							<a target="_blank" href="<?php echo get_permalink(cycling_results_management()->pages['single-rider']); ?>" class="button button-secondary">View Page</a>
						</td>
					</tr>

		      <tr>
		      	<th scope="row" valign="top">
							<label for="single_race_page_id">Single Race Page:</label>
						</th>
						<td>
							<?php wp_dropdown_pages(array(
								'name' => 'single_race_page_id',
								'show_option_none' => '-- '.__('Select One', 'crm').' --',
								'selected' => cycling_results_management()->pages['single-race']
							)); ?>
							<a target="_blank" href="<?php echo admin_url('post.php?post='.cycling_results_management()->pages['single-race'].'&action=edit'); ?>" class="button button-secondary">Edit Page</a>
							&nbsp;
							<a target="_blank" href="<?php echo get_permalink(cycling_results_management()->pages['single-race']); ?>" class="button button-secondary">View Page</a>
						</td>
					</tr>

		      <tr>
		      	<th scope="row" valign="top">
							<label for="country_page_id">Country Page:</label>
						</th>
						<td>
							<?php wp_dropdown_pages(array(
								'name' => 'country_page_id',
								'show_option_none' => '-- '.__('Select One', 'crm').' --',
								'selected' => cycling_results_management()->pages['country']
							)); ?>
							<a target="_blank" href="<?php echo admin_url('post.php?post='.cycling_results_management()->pages['country'].'&action=edit'); ?>" class="button button-secondary">Edit Page</a>
							&nbsp;
							<a target="_blank" href="<?php echo get_permalink(cycling_results_management()->pages['country']); ?>" class="button button-secondary">View Page</a>
						</td>
					</tr>

		      <tr>
		      	<th scope="row" valign="top">
							<label for="riders_page_id">Riders Page:</label>
						</th>
						<td>
							<?php wp_dropdown_pages(array(
								'name' => 'riders_page_id',
								'show_option_none' => '-- '.__('Select One', 'crm').' --',
								'selected' => cycling_results_management()->pages['riders']
							)); ?>
							<a target="_blank" href="<?php echo admin_url('post.php?post='.cycling_results_management()->pages['riders'].'&action=edit'); ?>" class="button button-secondary">Edit Page</a>
							&nbsp;
							<a target="_blank" href="<?php echo get_permalink(cycling_results_management()->pages['riders']); ?>" class="button button-secondary">View Page</a>
						</td>
					</tr>

		      <tr>
		      	<th scope="row" valign="top">
							<label for="races_page_id">Races Page:</label>
						</th>
						<td>
							<?php wp_dropdown_pages(array(
								'name' => 'races_page_id',
								'show_option_none' => '-- '.__('Select One', 'crm').' --',
								'selected' => cycling_results_management()->pages['races']
							)); ?>
							<a target="_blank" href="<?php echo admin_url('post.php?post='.cycling_results_management()->pages['races'].'&action=edit'); ?>" class="button button-secondary">Edit Page</a>
							&nbsp;
							<a target="_blank" href="<?php echo get_permalink(cycling_results_management()->pages['races']); ?>" class="button button-secondary">View Page</a>
						</td>
					</tr>

					<tr>
		      			<th scope="row" valign="top">
							<label for="uci_results_search_page_id">Search Page:</label>
						</th>
						<td>
							<?php wp_dropdown_pages(array(
								'name' => 'uci_results_search_page_id',
								'show_option_none' => '-- '.__('Select One', 'crm').' --',
								'selected' => cycling_results_management()->pages['search']
							)); ?>
							<a target="_blank" href="<?php echo admin_url('post.php?post='.cycling_results_management()->pages['search'].'&action=edit'); ?>" class="button button-secondary">Edit Page</a>
							&nbsp;
							<a target="_blank" href="<?php echo get_permalink(cycling_results_management()->pages['search']); ?>" class="button button-secondary">View Page</a>
						</td>
					</tr>
					
					<tr>
		      			<th scope="row" valign="top">
							<label for="uci_results_uci_rankings_page_id">UCI Rankings Page:</label>
						</th>
						<td>
							<?php wp_dropdown_pages(array(
								'name' => 'uci_results_uci_rankings_page_id',
								'show_option_none' => '-- '.__('Select One', 'crm').' --',
								'selected' => cycling_results_management()->pages['rankings']
							)); ?>
							<a target="_blank" href="<?php echo admin_url('post.php?post='.cycling_results_management()->pages['rankings'].'&action=edit'); ?>" class="button button-secondary">Edit Page</a>
							&nbsp;
							<a target="_blank" href="<?php echo get_permalink(cycling_results_management()->pages['rankings']); ?>" class="button button-secondary">View Page</a>
						</td>
					</tr>					
					
					<tr>
						<th scope="row" valign="top">
							<label for="template_disable">Disable Templates</label>
						</th>
						<td>
							<input type="checkbox" name="template_disable" id="template_disable" value="1" <?php checked(get_option('uci_results_template_disable', 0), 1, true); ?> />
							<p class="description">
								When logged in as an admin, the default templates will be shown. Custom templates will be ignored.
							</p>
						</td>
					</tr>

				</tbody>
			</table>
		</section>

		<p class="submit">
			<input name="submit" type="submit" class="button button-primary" value="Save Settings">
		</p>

	</form>

</div>