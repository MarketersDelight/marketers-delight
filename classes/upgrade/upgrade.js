jQuery( document ).ready( function( $ ) {
	var update_47 = {
		process: function( process, item, self ) {
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'run',
					process: process,
					item: item
				},
				dataType: 'json',
				beforeSend: function() {
					$( '#md47_' + process ).removeClass( 'md-hide' );
				},
				success: function( response ) {
					var process = response.process,
						item = response.item;

					if ( process == 'complete' ) {
						$( '.md-box .md47-success' ).removeClass( 'md-hide' );
						$( '.md-box .md47-before' ).addClass( 'md-hide' );
						return;
					}

					if ( process !== 'post_meta' && item == 'done' ) {
						$( '#md47_' + process + ' .md47-success' ).removeClass( 'md-hide' );
						$( '#md47_' + process + ' .md47-before' ).addClass( 'md-hide' );
					}
					else {
						console.log( item );
					}

					update_47.process( process, item, self );
				}
			});
		}
	};
	update_47.process( 'start', 0, self );
});