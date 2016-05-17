<?php

/**
 * UCIcURLRiders class.
 */
class UCIcURLRiders {

	public $admin_pagination=array();

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {}

	/**
	 * get_riders function.
	 *
	 * @access public
	 * @param array $user_args (default: array())
	 * @return void
	 */
	public function get_riders($user_args=array()) {
		global $wpdb;

		$riders=array();
		$limit=false;
		$where=array();
		$paged=isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$default_args=array(
			'per_page' => 30,
			'order_by' => 'name',
			'order' => 'ASC',
			'name' => false,
			'nat' => false,
		);
		$args=array_merge($default_args,$user_args);

		// check filters //
		if (isset($_POST['ucicurl_admin']) && wp_verify_nonce($_POST['ucicurl_admin'], 'filter_riders'))
			$args=wp_parse_args($_POST, $args);

		// check search //
		if (isset($_GET['search']) && $_GET['search']!='')
			$where[]="name LIKE '%{$_GET['search']}%'";

		extract($args);

		// if we dont have a name and we have a limit, setup pagination //
		if (!$name && $per_page>0) :
			if ($paged==0) :
				$start=0;
			else :
				$start=$per_page*($paged-1);
			endif;
			$end=$per_page;
			$limit="LIMIT $start,$end";
		endif;

		// setup our where stuff //
		if ($name)
			$where[]="name='{$name}'";

		if ($nat)
			$where[]="nat='{$nat}'";

		// run our where //
		if (!empty($where)) :
			$where='WHERE '.implode(' AND ',$where);
		else :
			$where='';
		endif;

		$sql="
			SELECT
				*
			FROM $wpdb->ucicurl_riders
			$where
			ORDER BY $order_by $order
			$limit
		";

		$riders=$wpdb->get_results($sql);

		// for pagination //
		$this->admin_pagination['limit']=$args['per_page'];
		$this->admin_pagination['total']=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->ucicurl_riders $where");

		return $riders;
	}

	/**
	 * get_rider_rankings function.
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function get_rider_rankings($args=array()) {
		global $wpdb;

		$riders=array();
		$limit=false;
		$where=array();
		$paged=isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$default_args=array(
			'per_page' => 10,
			'order_by' => 'points',
			'order' => 'DESC',
			'name' => false,
			'nat' => false,
			'season' => false,
		);
		$args=wp_parse_args($args, $default_args);

		extract($args);

		// if we dont have a name and we have a limit, setup pagination //
		if (!$name && $per_page>0) :
			if ($paged==0) :
				$start=0;
			else :
				$start=$per_page*($paged-1);
			endif;
			$end=$per_page;
			$limit="LIMIT $start,$end";
		endif;

		// run our where //
		if (!empty($where)) :
			$where='WHERE '.implode(' AND ',$where);
		else :
			$where='';
		endif;

		$sql="
			SELECT
				riders.name,
				riders.nat,
				SUM(results.par) AS points
			FROM {$wpdb->ucicurl_riders} AS riders
			LEFT JOIN {$wpdb->ucicurl_results} AS results
			ON riders.id=results.rider_id
			LEFT JOIN {$wpdb->ucicurl_races} AS races
			ON results.race_id=races.id
			{$where}
			GROUP BY riders.name
			ORDER BY {$order_by} {$order}
			{$limit}
		";

		$riders=$wpdb->get_results($sql);

		return $riders;
	}

	/**
	 * get_rider function.
	 *
	 * @access public
	 * @param int $rider_id (default: 0)
	 * @return void
	 */
	public function get_rider($rider_id=0) {
		global $wpdb;

		$sql="
			SELECT *
			FROM {$wpdb->ucicurl_results}
			LEFT JOIN {$wpdb->ucicurl_races}
			ON {$wpdb->ucicurl_results}.race_id={$wpdb->ucicurl_races}.id
			WHERE rider_id={$rider_id}
		";
		$rider=$wpdb->get_row("SELECT * FROM {$wpdb->ucicurl_riders} WHERE id={$rider_id}");
		$rider->results=$wpdb->get_results($sql);

		return $rider;
	}

	/**
	 * admin_pagination function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_pagination() {
		$pagination=new UCIcURLPagination($this->admin_pagination['total'], $this->admin_pagination['limit'], admin_url('admin.php?page=uci-curl&tab=riders'));

		echo $pagination->get_pagination();
	}

	/**
	 * get_rider_id function.
	 *
	 * @access public
	 * @param string $name (default: '')
	 * @return void
	 */
	public function get_rider_id($name='') {
		global $wpdb;

		$id=$wpdb->get_var("SELECT id FROM {$wpdb->ucicurl_riders} WHERE name='{$name}'");

		return $id;
	}

	/**
	 * riders function.
	 *
	 * @access public
	 * @return void
	 */
	public function riders() {
		return $this->get_riders();
	}

	/**
	 * nats function.
	 *
	 * @access public
	 * @return void
	 */
	public function nats() {
		global $wpdb;

		$nats=$wpdb->get_col("SELECT DISTINCT(nat) FROM $wpdb->ucicurl_riders ORDER BY nat ASC");

		return $nats;
	}

}

$ucicurl_riders=new UCIcURLRiders();
?>