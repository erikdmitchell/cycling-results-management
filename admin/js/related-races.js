jQuery(document).ready(function($) {
	
	// remove related race //
	$('.remove-related-race').on('click', function(e) {
		e.preventDefault();
		
		var raceID=$(this).data('id');
		
		var data={
			'action' : 'uci_remove_related_race',
			'id' : raceID,
			'rrid' : $(this).data('rrid')
		};	
		
		$.post(ajaxurl, data, function(response) {			
			if (response==1) {				
				// remove row //
				$('.uci-results-metabox.related-races #race-' + raceID).remove();
			}			
		});
	});
	
	// add related race //
	
	/**
	 * related races ajax search
	 */
	$("#search-related-races").live("keyup", function(e) {
		// Set Search String
		var search_string = $(this).val();

	  // Do Search
	  if (search_string!=='' && search_string.length>=3) {
			$.ajax({
				type: 'post',
				url: ajaxurl,
				data: {
					action : 'search_related_races',
					query : search_string,
					race_id : $('#main_race_id').val()
				},
				success: function(response){
					$('#related-races-search-results').html(response);
				}
			});
	  }

	  return false;
	});
	
});