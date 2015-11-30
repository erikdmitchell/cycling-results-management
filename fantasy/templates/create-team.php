<?php
/**
 * The template for the create team page.
 *
 * @since Version 0.0.1
 */
?>
<?php
$max_riders=6;
?>
<div class="row">
	<div class="fantasy-cycling-create-team col-md-5">
		<?php if ($race_id=fc_get_race_id()) : ?>
			<form name="new-team" id="new-team" method="post" action="">
				<?php
				if (!is_user_logged_in()) :
					echo '<a href="/login/">Please login first.</a><br />';
					echo '<a href="/register/">Click here to register.</a><br />';
					return;
				endif;

				$team=fc_get_user_team();

				if (!$team || $team=='') :
					echo '<a href="'.get_edit_user_link().'">Please add a team name first.</a>';
					return;
				endif;

				$dd_list=fc_rider_list_dropdown_race(array(
					'id' => $race_id,
					'name' => 'riders[]',
					'echo' => false
				));
				$race=fc_get_race($race_id);
				?>
				<div class="row">
					<div class="col-md-3">
						<label for="race">Race:</label>
					</div>
					<div class="col-md-9">
						<span class="race-name"><?php echo $race->name; ?></span>
						<input type="hidden" name="race" value="<?php echo $race_id; ?>" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="team-name">Team Name:</label>
					</div>
					<div class="col-md-9">
						<?php echo stripslashes($team); ?>
						<input type="hidden" name="team_name" value="<?php echo $team; ?>" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="team-name">Rider 1:</label>
					</div>
					<div class="col-md-9">
						<?php echo $dd_list; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="team-name">Rider 2:</label>
					</div>
					<div class="col-md-9">
						<?php echo $dd_list; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="team-name">Rider 3:</label>
					</div>
					<div class="col-md-9">
						<?php echo $dd_list; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="team-name">Rider 4:</label>
					</div>
					<div class="col-md-9">
						<?php echo $dd_list; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="team-name">Rider 5:</label>
					</div>
					<div class="col-md-9">
						<?php echo $dd_list; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="team-name">Rider 6:</label>
					</div>
					<div class="col-md-9">
						<?php echo $dd_list; ?>
					</div>
				</div>

				<?php if (fc_race_has_start_list($_GET['race_id']) && fc_is_race_roster_edit_open($_GET['race_id'])) : ?>
					<input type="submit" name="submit" id="submit" value="Create Team" />
				<?php endif; ?>

				<input type="hidden" name="wp_user_id" value="<?php echo get_current_user_id(); ?>" />
				<input type="hidden" name="create_team" value="1" />
			</form>
		<?php else : ?>
			There was an error, please try again.
		<?php endif; ?>
	</div>
	<div class="last-year-results col-md-4">
		<?php if ($prev_results=fc_get_race_results(fc_get_last_years_code($race_id),10)) : ?>
			<h3>Last Year's Top Ten</h3>
			<div class="row header">
				<div class="place col-md-2">Place</div>
				<div class="rider-name col-md-6">Name</div>
				<div class="nat col-md-2">Nat</div>
				<!-- <div class="time col-md-2">Time</div> -->
			</div>
			<?php foreach ($prev_results as $result) : ?>
				<div class="row">
					<div class="place col-md-2"><?php echo $result->place; ?></div>
					<div class="rider-name col-md-6"><?php echo $result->name; ?></div>
					<div class="nat col-md-2"><?php echo get_country_flag($result->nat); ?></div>
					<!-- <div class="time col-md-2"><?php echo $result->time; ?></div> -->
				</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
<!--
	<div class="world-cup-standings col-md-3">
		<?php $standings=get_wcp_standings(); ?>
		<h3>World Cup Standings</h3>
		<div class="row header">
			<div class="place col-md-2">Place</div>
			<div class="rider-name col-md-7">Name</div>
			<div class="points col-md-2">Points</div>
		</div>
		<?php foreach ($standings as $standing) : ?>
			<div class="row">
				<div class="place col-md-2"><?php echo $standing->rank; ?></div>
				<div class="rider-name col-md-7"><?php echo $standing->name; ?></div>
				<div class="points col-md-2"><?php echo $standing->points; ?></div>
			</div>
		<?php endforeach; ?>
	</div>
-->
</div><!-- .row -->
<?php $race_id=fc_get_race_id(); ?>
<div class="fc-team-roster">
	<div class="row header">
		<div class="col-md-1">&nbsp;</div>
		<div class="col-md-3">Rider</div>
		<div class="col-md-2">Last Year</div>
		<div class="col-md-2">
			<div class="row">
				<div class="col-md-12">Last Week</div>
			</div>
			<div class="row last-week-race-name">
				<div class="col-md-12"><?php echo fc_fantasy_get_last_week_race_name($race_id); ?></div>
			</div>
		</div>
		<div class="col-md-1">Rank</div>
		<div class="col-md-3 season-points">
			<div class="row">
				<div class="col-md-12">
					UCI Points
				</div>
			</div>
			<div class="row sub-col">
				<div class="col-md-2 c2">C2</div>
				<div class="col-md-2 c1">C1</div>
				<div class="col-md-2 cc">CC</div>
				<div class="col-md-2 cn">CN</div>
				<div class="col-md-2 cdm">CDM</div>
				<div class="col-md-2 cm">CM</div>
			</div>
		</div>

	</div>
	<?php for ($i=0;$i<$max_riders;$i++) : ?>
		<div id="rider-<?php echo $i; ?>" class="row add-remove-rider">
			<div class="col-md-1"><?php fc_add_rider_modal_btn(); ?></div>
			<div class="col-md-3 rider-name"></div>
			<div class="col-md-2 last-year-finish"></div>
			<div class="col-md-2 last-week-finish"></div>
			<div class="col-md-1 rank"></div>
			<div class="col-md-3 season-points">
				<div class="row sub-col">
					<div class="col-md-2 c2"></div>
					<div class="col-md-2 c1"></div>
					<div class="col-md-2 cc"></div>
					<div class="col-md-2 cn"></div>
					<div class="col-md-2 cdm"></div>
					<div class="col-md-2 cm"></div>
				</div>
			</div>
		</div>
	<?php endfor; ?>
</div>
<?php fc_add_rider_modal(array('race_id' => $race_id)); ?>
