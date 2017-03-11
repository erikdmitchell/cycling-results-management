<?php
///////// RIDERS

function uci_get_riders($args='') {
	global $uci_riders;

	$default_args=array(
		'rider_ids' => '',
		'results' => false,
		'last_result' => false,
		'race_ids' => '',
		'results_season' => '',
		'ranking' => false,
		'stats' => false
	);
	$args=wp_parse_args($args, $default_args);	
	$riders=$uci_riders->get_riders($args);

	return $riders;
}			

/**
 * uci_results_get_rider_results function.
 * 
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function uci_results_get_rider_results($args='') {
	$default_args=array(
		'rider_id' => 0, 
		'race_ids' => '', 
		'seasons' => '', 
		'places' => '',
		'race_classes' => '',
		'race_series' => '',
	);
	$args=wp_parse_args($args, $default_args);
	
	extract($args);
	
	if (!$rider_id)
		return false;
		
	$results=array();
	
	if (!is_array($race_ids) && !empty($race_ids))
		$race_ids=explode(',', $race_ids);

	if (!is_array($seasons) && !empty($seasons))
		$seasons=explode(',', $seasons);

	if (!is_array($places) && !empty($places))
		$places=explode(',', $places);

	if (!is_array($race_classes) && !empty($race_classes))
		$race_classes=explode(',', $race_classes);

	if (!is_array($race_series) && !empty($race_series))
		$race_series=explode(',', $race_series);
		
    // get race ids via meta //
	$results_args_meta = array(
		'posts_per_page' => -1,
		'post_type' => 'races',
		'meta_query' => array(
		    array(
		        'key' => '_rider_'.$rider_id,
		    )
		),
		'fields' => 'ids'
	);
	
	// check specific race ids //
	if (!empty($race_ids))
		$results_args_meta['post__in']=$race_ids;

	// check specific seasons //
	if (!empty($seasons))
		$results_args_meta['tax_query'][]=array(
			'taxonomy' => 'season',
			'field' => 'slug',
			'terms' => $seasons
		);

	// check specific race_classes //
	if (!empty($race_classes))
		$results_args_meta['tax_query'][]=array(
			'taxonomy' => 'race_class',
			'field' => 'slug',
			'terms' => $race_classes
		);

	// check specific race_series //
	if (!empty($race_series))
		$results_args_meta['tax_query'][]=array(
			'taxonomy' => 'series',
			'field' => 'slug',
			'terms' => $race_series
		);

	$race_ids=get_posts($results_args_meta);
	
	foreach ($race_ids as $race_id) :
		$result=get_post_meta($race_id, '_rider_'.$rider_id, true);
		$result['race_id']=$race_id;
		$result['race_name']=get_the_title($race_id);
		
		if (!empty($places)) :
			if (in_array($result['place'], $places)) :
				$results[]=$result;			
			endif;
		else :
			$results[]=$result;
		endif;
	endforeach;

	return $results;
}

function uci_get_rider_id($slug='') {
	global $wpdb;

	$id=$wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$slug'");

	return $id;
}

///////// RACES

/**
 * uci_results_get_race_results function.
 * 
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function uci_results_get_race_results($race_id=0) {
	$post_meta=get_post_meta($race_id);
	$riders=array();
	
	// get only meta (riders); we need //
	foreach ($post_meta as $key => $value) :
		if (strpos($key, '_rider_') !== false) :
			if (isset($value[0])) :
				$riders[]=unserialize($value[0]);
			endif;			
		endif;
	endforeach;
	
	return $riders;
}

/**
 * uci_race_has_results function.
 * 
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function uci_race_has_results($race_id=0) {
	$post_meta=get_post_meta($race_id);
	$keys=array_keys($post_meta);    

	return (int) preg_grep('/_rider_/', $keys);	
}

/**
 * uci_get_race_twitter function.
 * 
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function uci_get_race_twitter($race_id=0) {
	if (empty($race_id))
		return false;

	return get_post_meta($race_id, '_race_twitter', true);
}

/**
 * uci_get_related_races function.
 * 
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function uci_get_related_races($race_id=0) {
	global $wpdb;

	$related_races=array();
	$related_race_id=uci_get_related_race_id($race_id);
	
	if (!$related_race_id)
		return array();
	
	$related_races_ids=uci_get_related_races_ids($race_id);

	if (is_wp_error($related_races_ids) || $related_races_ids===null)
		return false;

	$related_races=get_posts(array(
		'include' => $related_races_ids,
		'post_type' => 'races',
		'orderby' => 'meta_value',
		'meta_key' => '_race_date',
	));
	
	// append some meta //
	foreach ($related_races as $race) :
		$race->race_date=get_post_meta($race->ID, '_race_date', true);
	endforeach;

	return $related_races;
}

/**
 * uci_get_related_races_ids function.
 * 
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function uci_get_related_races_ids($race_id=0) {
	global $wpdb;

	$related_race_id=uci_get_related_race_id($race_id);
	
	if (!$related_race_id)
		return array();
	
	$related_races_ids=$wpdb->get_col("SELECT race_id FROM $wpdb->uci_results_related_races WHERE related_race_id = $related_race_id");

	if (is_wp_error($related_races_ids) || $related_races_ids===null)
		return false;

	return $related_races_ids;
}

/**
 * uci_get_related_race_id function.
 * 
 * @access public
 * @param int $race_id (default: 0)
 * @return void
 */
function uci_get_related_race_id($race_id=0) {
	return get_post_meta($race_id, '_race_related', true);
}

/**
 * uci_get_race_seasons_dropdown function.
 * 
 * @access public
 * @param string $name (default: 'season')
 * @param string $selected (default: '')
 * @return void
 */
function uci_get_race_seasons_dropdown($name='season', $selected='') {
	$html=null;
	$seasons=get_terms( array(
	    'taxonomy' => 'season',
		'hide_empty' => false,
	));

	$html.='<select id="'.$name.'" name="'.$name.'" class="'.$name.'">';
		$html.='<option value="0">-- Select Season --</option>';
			foreach ($seasons as $season) :
				$html.='<option value="'.$season->slug.'" '.selected($selected, $season->slug, false).'>'.$season->name.'</option>';
			endforeach;
	$html.='</select>';
	
	return $html;
}

/**
 * uci_get_country_dropdown function.
 * 
 * @access public
 * @param string $name (default: 'country')
 * @param string $selected (default: '')
 * @return void
 */
function uci_get_country_dropdown($name='country', $selected='') {
	$html=null;
	$countries=get_terms( array(
	    'taxonomy' => 'country',
		'hide_empty' => false,
	));

	$html.='<select id="'.$name.'" name="'.$name.'" class="'.$name.'">';
		$html.='<option value="0">-- Select Country --</option>';
			foreach ($countries as $country) :
				$html.='<option value="'.$country->slug.'" '.selected($selected, $country->slug, false).'>'.$country->name.'</option>';
			endforeach;
	$html.='</select>';
	
	return $html;
}

////////// SEASON

/**
 * uci_get_season_weeks_dropdown function.
 * 
 * @access public
 * @param string $season (default: '')
 * @param string $selected (default: '')
 * @param string $name (default: 'week')
 * @return void
 */
function uci_get_season_weeks_dropdown($season='', $selected='', $name='week') {
	global $uci_cross_seasons;	
	
	$html=null;
	$weeks=$uci_cross_seasons->get_season_weeks($season);
	
	if (empty($weeks))
		return;

	$html.='<select id="'.$name.'" name="'.$name.'" class="'.$name.'">';
		$html.='<option value="0">-- Select Season --</option>';
			foreach ($weeks as $week) :
				$html.='<option value="'.$week->week.'" '.selected($selected, $week->week, false).'>'.$week->week.'</option>';
			endforeach;
	$html.='</select>';
	
	return $html;	
}

/**
 * uci_results_get_season_weeks function.
 *
 * @access public
 * @param string $season (default: '')
 * @return void
 */
function uci_results_get_season_weeks($season='') {
	global $uci_cross_seasons;	
	
	$html=null;
	$weeks=$uci_cross_seasons->get_season_weeks($season);
	
	if (empty($weeks))
		return;
		
	return $weeks;
}

/**
 * uci_results_get_default_rider_ranking_week function.
 *
 * @access public
 * @return void
 */
function uci_results_get_default_rider_ranking_week() {
	global $uci_cross_seasons;	
	
	$html=null;
	$weeks=$uci_cross_seasons->get_last_season_week($season);
	
	if (empty($weeks))
		return;
		
	return $weeks;
}

/**
 * uci_results_get_current_season function.
 *
 * @access public
 * @return void
 */
function uci_results_get_current_season() {
	$season_id=get_option('uci_results_current_season', 0);
	
	$season=get_term_by('id', $season_id, 'season');
	
	return $season;
}

/**
 * uci_results_get_previous_season function.
 * 
 * @access public
 * @return void
 */
function uci_results_get_previous_season() {
	$current_season=uci_results_get_current_season();
	$current_season_arr=explode('/', $current_season->name);

	// subtract one from each year //
	foreach ($current_season_arr as $key => $year) :
		$current_season_arr[$key]=absint($year)-1;
	endforeach;
	
	$prev_season_slug=implode('', $current_season_arr);
	$prev_season=$season=get_term_by('slug', $prev_season_slug, 'season');

	return $prev_season;
}

/**
 * uci_results_get_rider_rank function.
 * 
 * @access public
 * @param int $rider_id (default: 0)
 * @param string $season (default: '')
 * @param string $week (default: '')
 * @return void
 */
function uci_results_get_rider_rank($rider_id=0, $season='', $week='') {
	global $wpdb;

	$rank=$wpdb->get_var("SELECT rank FROM $wpdb->uci_results_rider_rankings WHERE rider_id=$rider_id AND season='$season' AND week=$week");

	if (!$rank)
		$rank=0;

	return $rank;
}
?>