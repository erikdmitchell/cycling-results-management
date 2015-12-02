<?php
/**
 * RiderStats class.
 *
 * @since Version 1.0.1
 */
class RiderStats {

	public $date_format='M. j Y';

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * get_riders function.
	 *
	 * get_riders() is no longer used on public site and needs to be renamed
	 *
	 * @access public
	 * @param array $user_args (default: array())
	 * @return void
	 *
	 * a slow query
	 *
	 */
	public function get_riders($user_args=array()) {
		global $wpdb,$uci_curl,$wp_query;

		$riders=array();
		$limit=false;
		$where=array();
		//$rank=1;
		//$total_divider=4;
		//$dates='';
		//$org_orderby=false;
		$paged=get_query_var('paged',1);
		$default_args=array(
			//'pagination' => true,
			//'paged' => 1,
			'per_page' => 15,
			//'limit' => false,
			'order_by' => 'total',
			'order' => 'DESC',
			'name' => false,
			'season' => '2015/2016',
			'country' => false, // not used yet
			'week' => false,
		);
		$args=array_merge($default_args,$user_args);
echo '<pre>';
print_r($args);
echo '</pre>';
		extract($args);

		// if we have a limit, setup pagination //
		if ($per_page>0) :
			if ($paged==0) :
				$start=0;
			else :
				$start=$per_page*($paged-1);
			endif;
			$end=$per_page;
			$limit="LIMIT $start,$end";
			//$rank=$start+1;
		endif;

		// setup our where stuff //
		if ($name)
			$where[]="name='{$name}'";

		if ($season)
			$where[]="season='{$season}'";

		if ($week)
			$where[]="week='{$week}'";

		// run our where //
		if (!empty($where)) :
			$where=implode(' AND ',$where);
		else :
			$where='';
		endif;

/*
		// our rank can be off if we sort by anything besides total, so we do that now //
		if ($order_by!='total') :
			$org_orderby=$order_by;
			$order_by='total';
		endif;
*/

		echo $sql="
			SELECT
				*
			FROM $uci_curl->uci_rider_rankings
			WHERE $where
			ORDER BY $order_by $order
			$limit
		";

		$riders=$wpdb->get_results($sql);

		//set our max pages var for pagination //
		if ($per_page>0) :
			$max_riders=$wpdb->get_var("SELECT COUNT(*) FROM $uci_curl->uci_rider_rankings WHERE $where");
			$wp_query->uci_curl_max_pages=$max_riders;
		endif;

		// add rank, if no name run rank if name, run all and get rank //
		if ($name) :

		endif;

		// clean variables //
		foreach ($riders as $rider) :
			$rider->sos=number_format($rider->sos,3);
			$rider->total=number_format($rider->total,3);
		endforeach;

/*
		// if order by is not rank, do that here //
		if ($org_orderby) :
			if (strpos($org_orderby,',') === false) : // checks if we have multiple sorts -- NEED A METHOD FOR THIS
				$order=array();
				foreach ($riders as $rider) :
					$order[]=$rider->$org_orderby;
				endforeach;
				array_multisort($order,SORT_ASC,$riders);
			endif;
		endif;
*/

		if ($name)
			$riders=$riders[0];

		return $riders;
	}

	/**
	 * get_rider_results function.
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	function get_rider_results($args=array()) {
		global $wpdb,$uci_curl;

		$html=null;
		$where=array();
		$default_args=array(
			'order_by' => 'date',
			'order' => 'DESC',
			'name' => false,
			'season' => false,
			'class' => false,
			'nat' => false,
			'place' => false
		);
		$args=array_merge($default_args,$args);

		extract($args);

		// setup our potential where statement //
		if ($name)
			$where[]="name='{$name}'";

		if ($season)
			$where[]="season='{$season}'";

		if ($class)
			$where[]="class='{$class}'";

		if ($nat)
			$where[]="nat='{$nat}'";

		if ($place)
			$where[]="place='{$place}'";

		if (!empty($where)) :
			$where=' WHERE '.implode(' AND ',$where);
		else :
			$where="";
		endif;

		$sql="
			SELECT
				name,
				place,
				results.nat,
				par AS points,
				season,
				STR_TO_DATE(date,'%e %M %Y') AS date,
				event,
				races.code,
				class,
				races.nat AS race_country,
				fq_table.fq
			FROM $uci_curl->results_table AS results
			LEFT JOIN $uci_curl->table AS races
			ON results.code=races.code
			LEFT JOIN $uci_curl->fq_table AS fq_table
			ON results.code=fq_table.code
			$where
			ORDER BY $order_by $order
		";
		$wpdb->query("SET SQL_BIG_SELECTS=1"); // fixes a minor sql bug
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

}

$RiderStats=new RiderStats();
?>