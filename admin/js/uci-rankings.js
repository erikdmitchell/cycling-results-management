jQuery(document).ready(function($) {
	
	// add-rider-rankings button - opens media uploader //
	$('.button.add-rider-rankings').click(function(e) {
		e.preventDefault();
	
		var _custom_media = true;
	    var _orig_send_attachment = wp.media.editor.send.attachment;
	    var send_attachment_bkp = wp.media.editor.send.attachment;
	    var button = $(this);

	    _custom_media = true;

	    wp.media.editor.send.attachment = function(props, attachment) {
	      if (_custom_media) {
		  	$('#add-rider-rankings-input').val(attachment.url);
	      } else {
	        return _orig_send_attachment.apply( this, [props, attachment] );
	      }
	    }

	    wp.media.editor.open(button);

	    return false;
	});	
	
	// insert ajax //
	$('#insert-rider-rankings').on('click', function(e) {
		e.preventDefault();
		
	    showLoader('#wpcontent');
	
		var data={
			'action' : 'uci_add_rider_rankings',
			'file' : $('#add-rider-rankings-input').val(),
			'custom_date' : $(this).parents('form').find('input#custom-date').val(),
			'discipline' : $(this).parents('form').find('select#discipline').val(),
		};
		
		$.post(ajaxurl, data, function(response) {
			$('#uci-admin-message').html(response);
	
			hideLoader();
		});
	});	
	
});