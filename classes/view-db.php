<?php
/**
 @since Version 1.0.2
**/
class ViewDB {

	public $version='0.1.2';

	public function __construct() {
		add_action('admin_enqueue_scripts',array($this,'viewdb_scripts_styles'));
	}

	public function viewdb_scripts_styles() {

	}

	public function display_view_db_page() {
		global $wpdb,$uci_curl,$RaceStats;

		$html=null;
		$races=$RaceStats->get_races();
		$race_years=$uci_curl->get_years_in_db();

		$html.='<h3>Races In Database</h3>';

/*
		if (isset($_POST['submit']) && $_POST['submit']=='Add/Update FQ' && isset($_POST['races'])) :
			foreach ($_POST['races'] as $race_id) :
echo "$race_id - update fq<br>";
				//echo $this->update_fq($race_id);
			endforeach;
		endif;
*/

		$html.='<form name="races-in-db" class="races-in-db form-filter">';
			$html.='<div class="title">Filter</div>';
			$html.='<div class="row">';
				$html.='<label for="season" class="col-md-1">Season</label>';
				$html.='<div class="col-md-2">';
					$html.='<select name="season" id="season">';
						$html.='<option value="0">View All</option>';
						foreach ($race_years as $year) :
							$html.='<option value="'.$year.'">'.$year.'</option>';
						endforeach;
					$html.='</select>';
				$html.='</div>';
			$html.='</div>';
		$html.='</form>';

		$html.='<form name="add-races-to-db" method="post">';
			$html.='<div class="race-table">';
				$html.='<div class="header row">';
					$html.='<div class="checkbox col-md-1">&nbsp;</div>';
					$html.='<div class="date col-md-2">Date</div>';
					$html.='<div class="event col-md-2">Event</div>';
					$html.='<div class="nat col-md-1">Nat.</div>';
					$html.='<div class="class col-md-1">Class</div>';
					$html.='<div class="winner col-md-2">Winner</div>';
					$html.='<div class="season col-md-1">Season</div>';
					$html.='<div class="race-details col-md-2">&nbsp;</div>';
				$html.='</div>';

				foreach ($races as $key => $race) :
					$html.=$this->display_race_data($race,false,false);
				endforeach;

			$html.='</div><!-- .race-table -->';

			$html.='<input type="checkbox" id="selectall" />Select All';

/*
			$html.='<p class="submit">';
				$html.='<input type="submit" name="submit" id="submit" class="button button-primary" value="Add/Update FQ">';
			$html.='</p>';
*/

		$html.='</form>';

		echo $html;
	}

	/**
	 * display_race_data function.
	 *
	 * @access public
	 * @param mixed $race
	 * @return void
	 */
	public function display_race_data($race) {
		global $uci_curl,$RaceStats;

		$html=null;
		$results_classes=array('results');
		$field_quality_classes=array('race-fq');
		$results=$RaceStats->get_race_results_from_db($race->code);

		$html.='<div id="race-'.$race->id.'" class="row race" data-season="'.$race->season.'">';
			$html.='<div class="col-md-1"><input class="race-checkbox" type="checkbox" name="races[]" value="'.$race->id.'" /></div>';
			$html.='<div class="date col-md-2">'.$race->date.'</div>';
			$html.='<div class="event col-md-2">'.$race->event.'</div>';
			$html.='<div class="nat col-md-1">'.$race->nat.'</div>';
			$html.='<div class="class col-md-1">'.$race->class.'</div>';
			$html.='<div class="winner col-md-2">'.$race->winner.'</div>';
			$html.='<div class="season col-md-1">'.$race->season.'</div>';

			$html.='<div class="race-details col-md-2">';
				if (!$results) :
					$html.='NO RESULTS';
				else :
					$html.='[<a class="result" href="#" data-link="'.$race->link.'" data-id="race-'.$race->id.'">Results</a>]&nbsp;';
				endif;

				if (!isset($race->field_quality) || !$race->field_quality) :
					$html.='NO FQ';
				else :
					$html.='[<a class="details" href="#" data-id="race-'.$race->id.'">Details</a>]';
				endif;
			$html.='</div>';
		$html.='</div>';

		// race results //
		$html.='<div id="race-'.$race->id.'" class="'.implode(' ',$results_classes).'">';
			if ($results) :
				$html.='<div class="row header">';
					$html.='<div class="col-md-1">Place</div>';
					$html.='<div class="col-md-3">Name</div>';
					$html.='<div class="col-md-1">Nat.</div>';
					$html.='<div class="col-md-1">Age</div>';
					$html.='<div class="col-md-1">Time</div>';
					$html.='<div class="col-md-1">PAR</div>';
					$html.='<div class="col-md-1">PCR</div>';
				$html.='</div>';

				foreach ($results as $result) :
					$html.='<div class="row">';
						$html.='<div class="col-md-1">'.$result->place.'</div>';
						$html.='<div class="col-md-3">'.$result->name.'</div>';
						$html.='<div class="col-md-1">'.$result->nat.'</div>';
						$html.='<div class="col-md-1">'.$result->age.'</div>';
						$html.='<div class="col-md-1">'.$result->time.'</div>';
						$html.='<div class="col-md-1">'.$result->par.'</div>';
						$html.='<div class="col-md-1">'.$result->pcr.'</div>';
					$html.='</div>';
				endforeach;
			endif;
		$html.='</div>';

		// race details, including field quality //
		$html.='<div id="race-'.$race->id.'" class="'.implode(' ',$field_quality_classes).'">';
			if (isset($race->field_quality)) :
				$html.='<div class="row header">';
					$html.='<div class="col-md-2">WC Mult.</div>';
					$html.='<div class="col-md-2">UCI Mult.</div>';
					$html.='<div class="col-md-2">Field Quality</div>';
					$html.='<div class="col-md-2">Total</div>';
					$html.='<div class="col-md-2">Divider</div>';
					$html.='<div class="col-md-2">Race Total</div>';
				$html.='</div>';

				$html.='<div class="row">';
					$html.='<div class="col-md-2">'.$race->field_quality->wcp_mult.'</div>';
					$html.='<div class="col-md-2">'.$race->field_quality->uci_mult.'</div>';
					$html.='<div class="col-md-2">'.$race->field_quality->field_quality.'</div>';
					$html.='<div class="col-md-2">'.$race->field_quality->total.'</div>';
					$html.='<div class="col-md-2">'.$race->field_quality->divider.'</div>';
					$html.='<div class="col-md-2">'.$race->field_quality->race_total.'</div>';
				$html.='</div>';
			else :
				$html.='<div class="col-md-12">'.$race->id.' - This race had no field quality</div>';
			endif;
		$html.='</div>';

		return $html;
	}

/*
	function update_fq($race_id) {
		global $wpdb;
		global $uci_curl;
		$message=null;
		$fq=new Field_Quality();
		$race=$wpdb->get_row("SELECT * FROM $uci_curl->table WHERE id=$race_id");

		$race->data=unserialize(base64_decode($race->data));

		$race->data->field_quality=$fq->get_race_math($race->data);

		// build data array //
		$data=array(
			'data' => base64_encode(serialize($race->data)),
		);

		$where=array(
			'id' => $race_id
		);

		$wpdb->update($uci_curl->table,$data,$where);

		$message='<div class="updated">Updated '.$race->code.' fq.</div>';

		return $message;
	}
*/

}
?>