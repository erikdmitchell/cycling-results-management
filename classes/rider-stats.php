<?php
/**
 * RiderStats class.
 *
 * @since Version 1.0.1
 */
class RiderStats {

	public $admin_url_vars='?page=uci-cross&tab=riders';

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * get_season_rider_rankings function.
	 *
	 * @access public
	 * @param array $user_args (default: array())
	 * @return void
	 */
	public function get_season_rider_rankings($args=array()) {
		global $wpdb,$uci_curl;

		$html=null;
		$paged=1;
		$per_page=15;

		if (isset($_GET['paged']))
			$paged=$_GET['paged'];

		$default_args=array(
			'pagination' => true,
			'paged' => $paged,
			'per_page' => $per_page
		);
		$args=array_merge($default_args,$args);

		$riders=$this->get_riders($args);

		$prev_page=$paged-1;
		$next_page=$paged+1;
		$max_pages=ceil($this->max_riders/$per_page);

		$html.='<h3>Rider Rankings</h3>';

		$html.='<div id="season-rider-rankings" class="season-rider-rankings">';
			$html.='<div class="header row">';
				$html.='<div class="rank col-md-1">Rank</div>';
				$html.='<div class="rider col-md-3">Rider</div>';
				$html.='<div class="uci col-md-1">UCI</div>';
				$html.='<div class="wcp col-md-1">WCP</div>';
				$html.='<div class="winning col-md-1">Win %*</div>';
				$html.='<div class="sos col-md-1">SOS</div>';
				$html.='<div class="total col-md-1">Total</div>';
			$html.='</div>';

			foreach ($riders as $rider) :
				$html.='<div class="row">';
					$html.='<div class="rank col-md-1">'.$rider->rank.'</div>';
					$html.='<div class="rider col-md-3">'.$rider->rider.'</div>';
					$html.='<div class="uci col-md-1">'.$rider->uci.'</div>';
					$html.='<div class="wcp col-md-1">'.$rider->wcp.'</div>';
					$html.='<div class="winning col-md-1">'.number_format($rider->weighted_win_perc,3).'</div>';
					$html.='<div class="sos col-md-1">'.$rider->sos.'</div>';
					$html.='<div class="total col-md-1">'.number_format($rider->total,3).'</div>';
				$html.='</div>';
			endforeach;

			$html.='<div class="rider-pagination uci-pagination">';
				if ($paged!=1)
					$html.='<div class="prev-page"><a href="'.admin_url($this->admin_url_vars).'&paged='.$prev_page.'">Previous</a></div>';

				if ($paged!=$max_pages)
					$html.='<div class="next-page"><a href="'.admin_url($this->admin_url_vars).'&paged='.$next_page.'">Next</a></div>';
			$html.='</div>';

		$html.='</div>';

		return $html;
	}

	/**
	 * get_riders function.
	 *
	 * @access public
	 * @param array $user_args (default: array())
	 * @return void
	 */
	public function get_riders($user_args=array()) {
		global $wpdb,$uci_curl,$wp_query;

		$riders=array();
		$limit=null;
		$rank=1;
		$default_args=array(
			'season' => '2015/2016',
			'pagination' => true,
			'paged' => 1,
			'per_page' => 15,
			'limit' => false
		);
		$args=array_merge($default_args,$user_args);

		extract($args);

		if ($pagination) :
			if ($paged==0) :
				$start=0;
			else :
				$start=$per_page*($paged-1);
			endif;
			$end=$per_page;
			$limit="LIMIT $start,$end";
			$rank=$start+1;
		elseif ($limit) :
			$limit="LIMIT $limit";
		endif;

		$sql="
			SELECT
				name AS rider,
				nat,
				SUM(uci_total) AS uci,
				SUM(wcp_total) AS wcp,
				SUM(wins) AS wins,
				SUM(races) AS races,
				SUM(wins/races) AS win_perc,
				SUM(uci_races) AS uci_races,
				SUM(races/uci_races) AS race_perc,
				SUM(((wins/races)+(races/uci_races))/2) AS weighted_win_perc,
				SUM(max_uci_points) AS max_uci_points,
				SUM(max_wcp_points) AS max_wcp_points,
				SUM(uci_total/max_uci_points) AS uci_perc,
				COALESCE(SUM(wcp_total/max_wcp_points),0) AS wcp_perc,
				SUM(sos) AS sos,
				(SELECT SUM( (COALESCE(SUM(wcp_total/max_wcp_points),0) + SUM(sos) + SUM(uci_total/max_uci_points) + SUM((((wins/races)+(races/uci_races))/2))) / 4 ) ) AS total
			FROM

			(
			SELECT
				results.name AS name,
				results.nat AS nat,
				SUM(results.par) AS uci_total,
				0 AS wcp_total,
				SUM(IF(results.place=1,1,0)) AS wins,
				COUNT(results.code) AS races,
				(SELECT COUNT(*) FROM $uci_curl->table WHERE season='$season') AS uci_races,
				(SELECT total FROM( SELECT SUM(results.par) AS total FROM $uci_curl->table AS races LEFT JOIN $uci_curl->results_table AS results ON races.code=results.code WHERE results.place=1 AND races.season='$season' GROUP BY races.code WITH ROLLUP ) t ORDER BY total DESC LIMIT 1) AS max_uci_points,
				0 AS max_wcp_points,
				0 AS sos
			FROM $uci_curl->results_table AS results
			LEFT JOIN $uci_curl->table AS races
			ON results.code=races.code
			WHERE season='$season'
			GROUP BY results.name

			UNION

			SELECT
				results.name AS name,
				results.nat AS nat,
				0 AS uci_total,
				SUM(results.par) AS wcp_total,
				0 AS wins,
				0 AS races,
				0 AS uci_races,
				0 AS max_uci_points,
				(SELECT	SUM(results.par) AS points FROM $uci_curl->table AS races LEFT JOIN $uci_curl->results_table AS results ON races.code=results.code WHERE results.place=1 AND races.class='CDM' AND races.season='$season') AS max_wcp_points,
				0 AS sos
			FROM $uci_curl->results_table AS results
			LEFT JOIN $uci_curl->table AS races
			ON results.code=races.code
			WHERE season='$season'
				AND races.class='CDM'
			GROUP BY results.name

			UNION

			SELECT
				results.name,
				results.nat AS nat,
				0 AS uci_total,
				0 AS wcp_total,
				0 AS wins,
				0 AS races,
				0 AS uci_races,
				0 AS max_uci_points,
				0 AS max_wcp_points,
				(SELECT SUM(((SELECT SUM(SUM(CASE races.class WHEN 'CM' THEN 5 WHEN 'CDM' THEN 4 WHEN 'CN' THEN 3 WHEN 'C1' THEN 2 WHEN 'C2' THEN 1 ELSE 0 END)/100)) + (SELECT SUM(SUM(races.fq)/100)))/2)) AS sos
			FROM $uci_curl->results_table AS results
			LEFT JOIN $uci_curl->table AS races
			ON results.code=races.code
			WHERE season='$season'
			GROUP BY results.name

			) t
			GROUP BY name
			ORDER BY total DESC
			$limit
		";

		$riders=$wpdb->get_results($sql);

		$max_riders=$wpdb->get_results("SELECT name FROM $uci_curl->results_table GROUP BY name");
		$wp_query->uci_curl_max_pages=$wpdb->num_rows; // set max

		// add rank //
		foreach ($riders as $rider) :
			$rider->rank=$rank;
			$rank++;
		endforeach;

		return $riders;
	}

	/**
	 * get_rider function.
	 *
	 * @access public
	 * @param int $name (default: 0)
	 * @return void
	 */
	public function get_rider($name=0) {
		global $wpdb,$uci_curl;

		if (!$name)
			return false;

		$season=get_query_var('season','2015/2016');
		$sql="
			SELECT
				results.place,
				results.nat AS country,
				results.par,
				races.code,
				races.date,
				races.event AS race,
				races.class,
				races.nat AS race_country,
				races.fq
			FROM $uci_curl->results_table AS results
			LEFT JOIN $uci_curl->table AS races
			ON results.code=races.code
			WHERE season='$season'
			AND name='$name'
			ORDER BY races.date
		";
		$results=$wpdb->get_results($sql);

		return $results;
	}

	/**
	 * get_country function.
	 *
	 * @access public
	 * @param int $name (default: 0)
	 * @return void
	 */
	public function get_country($name=0) {
		global $wpdb,$uci_curl;

		if (!$name)
			return false;

		$season=get_query_var('season','2015/2016');
		$sql="
			SELECT
				results.name AS rider,
				results.place,
				CASE WHEN results.par IS NULL OR results.par='' THEN 0 ELSE results.par END AS points,
				races.date,
				races.code,
				races.event AS race,
				races.class,
				races.nat AS race_country,
				races.fq
			FROM $uci_curl->results_table AS results
			LEFT JOIN $uci_curl->table AS races
			ON results.code=races.code
			WHERE season='$season'
			AND results.nat='$name'
			ORDER BY results.name,races.date,results.place
		";

		$results=$wpdb->get_results($sql);

		return $results;
	}

	// rider not used yet
	public function get_rank_seasons($rider=false) {
		global $wpdb,$uci_curl;

		$sql="
			SELECT
				season
			FROM $uci_curl->table
			WHERE season!=false
			GROUP BY season
			ORDER BY season ASC
		";

		$seasons=$wpdb->get_col($sql);

		return $seasons;
	}

}

$RiderStats=new RiderStats();
?>