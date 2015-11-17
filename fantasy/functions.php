<?php
/**
 * fc_get_user_teams function.
 *
 * @access public
 * @param int $user_id (default: 0)
 * @return void
 */
function fc_get_user_teams($user_id=0) {
	global $wpdb;

	if (!$user_id)
		return 'No user found.';

	$html=null;
	$teams=$wpdb->get_results("SELECT team,id FROM wp_fc_teams WHERE wp_user_id=$user_id GROUP BY team");

	if (!count($teams)) :
		$html.='No teams found. Click <a href="/fantasy/create-team/">here</a> to create one.';
		return $html;
	endif;

	$html.='<ul class="fantasy-cycling-user-teams">';
		foreach ($teams as $team) :
			$html.='<li id="team-'.$team->id.'"><a href="/fantasy/team?team='.urlencode($team->team).'">'.$team->team.'</a></li>';
		endforeach;
	$html.='</ul>';

	return $html;
}

/**
 * fc_user_teams function.
 *
 * @access public
 * @param int $user_id (default: 0)
 * @return void
 */
function fc_user_teams($user_id=0) {
	echo fc_get_user_teams($user_id);
}

/**
 * fc_rider_list_dropdown function.
 *
 * @access public
 * @param bool $name (default: false)
 * @param int $min_rank (default: 0)
 * @param int $max_rank (default: 10)
 * @param string $select_title (default: 'Select a Rider')
 * @param bool $echo (default: true)
 * @return void
 */
function fc_rider_list_dropdown($name=false,$min_rank=0,$max_rank=10,$select_title='Select a Rider',$echo=true) {
	global $wpdb,$RiderStats;

	$html=null;
	$riders=$RiderStats->get_riders(array(
		'pagination' => false,
		'limit' => "$min_rank,$max_rank"
	));

	if (!$name)
		$name=generateRandomString();

	$html.='<select name="'.$name.'" id="'.$name.'">';
		$html.='<option value="0">'.$select_title.'</option>';
		foreach ($riders as $rider) :
			$html.='<option value="'.$rider->rider.'">'.$rider->rider.'</option>';
		endforeach;
	$html.='</select>';

	if ($echo) :
		echo $html;
	else :
		return $html;
	endif;
}

/**
 * fc_rider_list_dropdown_race function.
 *
 * @access public
 * @param array $args (default: array())
 * @return void
 */
function fc_rider_list_dropdown_race($args=array()) {
	global $wpdb,$RiderStats;

	$default_args=array(
		'id' => 1,
		'name' => false,
		'min_rank' => 0,
		'max_rank' => 10,
		'select_title' => 'Select a Rider',
		'echo' => true
	);
	$args=array_merge($default_args,$args);
	$html=null;

	extract($args);

	$riders=$wpdb->get_col("SELECT start_list FROM wp_fc_races WHERE id=$id");
	$riders=unserialize($riders[0]);

	// if we have no start list //
	if (empty($riders))
		return '<div class="no-start-list">No start list yet, check back soon.</div>';

	// Sort the array by name //
	sort($riders);

	if (!$name)
		$name=generateRandomString();

	$html.='<select name="'.$name.'" id="'.$name.'">';
		$html.='<option value="0">'.$select_title.'</option>';
		foreach ($riders as $rider) :
			$country=$wpdb->get_var("SELECT nat FROM wp_uci_rider_data WHERE name='$rider' GROUP BY nat");
			$html.='<option value="'.$rider.'">'.$rider.' ('.$country.')</option>';
		endforeach;
	$html.='</select>';

	if ($echo) :
		echo $html;
	else :
		return $html;
	endif;
}

/**
 * fc_process_create_team function.
 *
 * @access public
 * @return void
 */
function fc_process_create_team() {
	global $wpdb;

	if (isset($_POST['create_team']) && $_POST['create_team']) :
		$table='wp_fc_teams';

		$data=array(
			'wp_user_id' => $_POST['wp_user_id'],
			'data' => implode('|',$_POST['riders']),
			'team' => $_POST['team_name'],
			'race_id' => $_POST['race'],
		);

		$wpdb->insert($table,$data);

		wp_redirect('/fantasy/team?team='.urlencode($_POST['team_name']));
		exit;
	endif;
}
add_action('init','fc_process_create_team');

/**
 * fc_get_team function.
 *
 * @access public
 * @param bool $team (default: false)
 * @return void
 */
function fc_get_team($team=false) {
	global $wpdb;

	if (!$team && isset($_GET['team']))
		$team=$_GET['team'];

	if (!$team || $team=='')
		return false;

	$html=null;
	$team_results=fc_get_teams_results($team);

	$html.='<div class="team">';
		$html.='<h3>'.$team.'</h3>';

		$html.='<div class="results">';
			foreach ($team_results as $results) :
				$html.='<div class="row">';
					$html.='<div class="race-name col-md-6"><a href="/fantasy/standings/?race_id='.$results->race_id.'">'.$results->race_name.'</a></div>';
					$html.='<div class="total-points col-md-2">'.$results->total.'</div>';
				$html.='</div>';

				$html.='<div class="riders">';
					foreach ($results->riders as $rider) :
						$html.='<div class="rider row">';
							$html.='<div class="name col-md-6">'.$rider->name.'<span class="nat">'.get_country_flag($rider->nat).'</span></div>';
							$html.='<div class="place col-md-2">'.$rider->place.'</div>';
							$html.='<div class="points col-md-2">'.$rider->points.'</div>';
						$html.='</div>';
					endforeach;
				$html.='</div>';

			endforeach;
		$html.='</div>';
	$html.='</div>';

	return $html;
}

/**
 * fc_get_race_standings function.
 *
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function fc_get_race_standings($race_id=0) {
	$html=null;
	$teams=fc_get_teams_results(false,$race_id);
	$place=1;

	$html.='<div class="fantasy-cycling-team-standings">';
		$html.='<h2>'.$teams->race_name.'</h2>';
		$html.='<div class="team-standings">';
			$html.='<div class="row header">';
				$html.='<div class="rank col-md-3">Rank</div>';
				$html.='<div class="name col-md-6">Team</div>';
				$html.='<div class="points col-md-3">Points</div>';
			$html.='</div>';
			foreach ($teams->teams as $team) :
				$html.='<div class="row">';
					$html.='<div class="rank col-md-3">'.$place.'</div>';
					$html.='<div class="name col-md-6"><a href="/fantasy/team?team='.urlencode($team->team_name).'">'.$team->team_name.'</a></div>';
					$html.='<div class="points col-md-3">'.$team->total.'</div>';
				$html.='</div>';
				$place++;
			endforeach;
		$html.='</div>';
	$html.='</div>';

	return $html;
}

/**
 * fc_get_teams_results function.
 *
 * @access public
 * @param bool $team_name (default: false)
 * @param int $race_id (default: 0)
 * @return void
 */
function fc_get_teams_results($team_name=false,$race_id=false) {
	global $wpdb;

	if (!$team_name && !$race_id)
		return false;

	$where=array();

	if ($team_name)
		$where[]="team='{$team_name}'";

	if ($race_id)
		$where[]="race_id={$race_id}";

	if (!empty($where)) :
		if (count($where)==1) :
			$where='WHERE '.implode('',$where);
		else :
			$where='WHERE '.implode(' AND ',$where);
		endif;
	endif;

	$html=null;
	$teams_final=new stdClass();
	$fc_data_sql="
		SELECT
			team AS team_name,
			data AS riders,
			race_id,
			races.code,
			uci_races.event AS race_name
		FROM wp_fc_teams AS teams
		LEFT JOIN wp_fc_races AS races
		ON teams.race_id=races.id
		LEFT JOIN wp_uci_races AS uci_races
		ON races.code=uci_races.code
		$where
	";
	$teams=$wpdb->get_results($fc_data_sql);

	// split out riders into array and get points //
	foreach ($teams as $team) :
		$total=0;
		$team->riders=explode('|',$team->riders);
		foreach ($team->riders as $key => $rider) :
			$results=$wpdb->get_row("SELECT name, place, nat, par AS points FROM wp_uci_rider_data	WHERE code=\"{$team->code}\" AND name='{$rider}'");
			if (empty($results)) :
				$results=new stdClass();
				$results->name=$rider;
				$results->place=0;
				$results->nat='';
				$results->points=0;
			endif;
			$team->riders[$key]=$results;
			$total=$total+$results->points;
		endforeach;
		$team->total=$total;
	endforeach;

	// order by points //
	usort($teams, function ($a, $b) {
		return strcmp($b->total,$a->total);
	});

	if ($team_name) :
		$teams_final=$teams;
	endif;

	if ($race_id) :
		$teams_final->race_name=$wpdb->get_var("SELECT event AS race_name FROM wp_fc_races AS fcraces LEFT JOIN wp_uci_races AS races ON fcraces.code=races.code WHERE fcraces.id={$race_id}");
		$teams_final->teams=$teams;
	endif;

/*
echo '<pre>';
print_r($teams_final);
echo '</pre>';
*/

	return $teams_final;
}

/**
 * fc_get_standings function.
 *
 * @access public
 * @return void
 */
function fc_get_standings() {
	if (isset($_GET['race_id'])) :
		return fc_get_race_standings($_GET['race_id']);
	else :
		return fc_get_final_standings();
	endif;
}

/**
 * fc_standings function.
 *
 * @access public
 * @return void
 */
function fc_standings() {
	echo fc_get_standings();
}

/**
 * fc_get_final_standings function.
 *
 * @access public
 * @param int $limt (default: 10)
 * @return void
 */
function fc_get_final_standings($limt=10) {
	global $wpdb;

	$html=null;
	$sql="
		SELECT
			name,
			races.id,
			COUNT(teams.id) AS total_teams
		FROM wp_fc_races AS races
		LEFT JOIN wp_fc_teams AS teams
		ON races.id=teams.race_id
		GROUP BY races.id
		ORDER BY races.race_start
	";
	$races=$wpdb->get_results($sql);

	$html.='<div class="fantasy-cycling-final-standings">';
		$html.='<div class="final-standings">';
			$html.='<div class="row header">';
				$html.='<div class="name col-md-9">Race</div>';
				$html.='<div class="points col-md-3">Teams</div>';
			$html.='</div>';
			foreach ($races as $race) :
				$html.='<div class="row">';
					$html.='<div class="name col-md-9"><a href="/fantasy/standings?race_id='.$race->id.'">'.$race->name.'</a></div>';
					$html.='<div class="points col-md-3">'.$race->total_teams.'</div>';
				$html.='</div>';
			endforeach;
		$html.='</div>';
		$html.='<a href="/fantasy/standings/" class="more">View All &raquo;</a>';
	$html.='</div>';

	return $html;
}

/**
 * fc_final_standings function.
 *
 * @access public
 * @param int $limit (default: 10)
 * @return void
 */
function fc_final_standings($limit=10) {
	echo fc_get_final_standings($limit);
}

function fc_get_fantasy_cycling_posts($limit=5) {
	$html=null;
	$args=array(
		'posts_per_page' => $limit+1,
		'post_type' => 'fantasy-cycling',
		'tax_query' => array(
			array(
				'taxonomy' => 'posttype',
				'field' => 'slug',
				'terms' => 'sticky',
				'operator' => 'NOT IN',
			),
		),
	);
	$posts=get_posts($args);
	$sticky_args=array(
		'posts_per_page' => 1,
		'post_type' => 'fantasy-cycling',
		'tax_query' => array(
			array(
				'taxonomy' => 'posttype',
				'field' => 'slug',
				'terms' => 'sticky'
			),
		),
	);
	$sticky_posts=get_posts($sticky_args);

	if (!count($posts))
		return false;

	// merge and slice posts //
	$posts=array_merge($sticky_posts,$posts);
	$posts=array_slice($posts,0,$limit);

	$html.='<ul class="fc-posts">';
		foreach ($posts as $post) :
			$terms=wp_get_post_terms($post->ID,'posttype',array('fields' => 'names'));
			$class='';
			$sticky=false;

			if (in_array('Sticky',$terms)) :
				$class.=' sticky';
				$sticky=true;
			endif;

			$html.='<li id="post-'.$post->ID.'" class="'.$class.'">';
				$html.='<a href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a>';
				if ($sticky) :
					$html.=': '.$post->post_content;
				endif;
			$html.='</li>';
		endforeach;
	$html.='</ul>';

	return $html;
}

/**
 * fc_fantasy_cycling_posts function.
 *
 * @access public
 * @param int $limit (default: 5)
 * @return void
 */
function fc_fantasy_cycling_posts($limit=5) {
	echo fc_get_fantasy_cycling_posts($limit);
}

/**
 * fc_get_upcoming_races function.
 *
 * @access public
 * @param int $limit (default: 3)
 * @return void
 */
function fc_get_upcoming_races($limit=3) {
	global $wpdb;

	$html=null;
	//$races=$wpdb->get_results("SELECT * FROM wp_fc_races ORDER BY race_start ASC LIMIT $limit");
	$races=$wpdb->get_results("SELECT * FROM wp_fc_races WHERE race_start > CURDATE() ORDER BY race_start ASC LIMIT $limit");

	if (isset($_GET['team'])) :
		$team=$_GET['team'];
	else :
		$team=fc_get_user_team(get_current_user_id());
	endif;

	$html.='<div class="fc-upcoming-races">';
		foreach ($races as $race) :
			if ($race->series!='single') :
				$series='<div class="series">('.$race->series.')';
			else :
				$series='';
			endif;

			$html.='<div id="race-'.$race->id.'" class="row">';
				$html.='<div class="date col-md-4">'.date('M. j, Y',strtotime($race->race_start)).': </div>';
				$html.='<div class="race-name col-md-8"><a href="/fantasy/create-team/?team='.urlencode($team).'&race_id='.$race->id.'">'.$race->name.'</a></div>';
				$html.=$series;
			$html.='</div>';
		endforeach;
	$html.='</div>';

	return $html;

}

/**
 * fc_upcoming_races function.
 *
 * @access public
 * @param int $limit (default: 3)
 * @return void
 */
function fc_upcoming_races($limit=3) {
	echo fc_get_upcoming_races($limit);
}

/**
 * generateRandomString function.
 *
 * @access public
 * @param int $length (default: 10)
 * @return void
 */
function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

/**
 * fc_get_template_html function.
 *
 * @access public
 * @param bool $template_name (default: false)
 * @return void
 */
function fc_get_template_html($template_name=false) {
	if (!$template_name)
		return false;

	ob_start();

	do_action('emcl_before_'.$template_name);

	require('templates/'.$template_name.'.php');

	do_action('emcl_after_'.$template_name);

	$html=ob_get_contents();

	ob_end_clean();

	return $html;
}

/**
 * fc_get_race function.
 *
 * @access public
 * @param mixed $race_id
 * @return void
 */
function fc_get_race($race_id) {
	global $wpdb;

	$race=$wpdb->get_row("SELECT * FROM wp_fc_races WHERE id={$race_id}");

	return $race;
}

/**
 * fc_get_user_team function.
 *
 * @access public
 * @param bool $user_id (default: false)
 * @return void
 */
function fc_get_user_team($user_id=false) {
	global $wpdb;

	if (!$user_id)
		$user_id=get_current_user_id();

	$team=$wpdb->get_var("SELECT DISTINCT team FROM wp_fc_teams WHERE wp_user_id={$user_id}");

	return $team;
}
?>