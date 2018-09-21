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

		<section class="admin">
			<h2>Administrator</h2>

			<table class="form-table">
				<tbody>

					<tr>
						<th scope="row">
							<label form="enable_cron_log">Enable Cron Log</label>
						</th>
						<td>
							<input type="checkbox" name="enable_cron_log" id="enable_cron_log" value="1" <?php checked(get_option('uci_results_enable_cron_log', ''), 1); ?>>
							
							<a href="<?php echo UCI_RESULTS_URL; ?>cron.log" class="button button-secondary">View Log</a> (<span id="uci-results-cron-job-log-size"><?php echo uci_results_format_size(filesize(UCI_RESULTS_PATH.'cron.log')); ?></span>)
							<a href="" class="button button-secondary" id="uci-results-clear-log">Clear Log</a>
							
							<p class="description">
								Our primary results task is set to run via cron job. If this box is checked, the cron job will output information to the log.
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

	<section class="admin-actions">
		<h2>Actions</h2>

		<div id="uci-results-actions-message"></div>

		<div class="empty-db warning message">
			<input type="hidden" id="uci-results-remove-data-nonce" value="<?php echo wp_create_nonce('uci-results-remove-data-nonce'); ?>" /> 
			<p>This operation will remove all data and databases created by this plugin.</p>
			<button class="button button-primary warning-button" class="remove-data" id="uci-results-remove-data">Remove Data</button>
		</div>

	</section>

</div>