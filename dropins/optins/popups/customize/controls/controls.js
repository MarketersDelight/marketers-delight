/**
 * Send data through Controls to Preview.
 *
 * @since 5.0
*/

jQuery( function( $ ) {
	// Add handy refresh button to top of Customzier
	$( '#customize-header-actions' ).append(
		'<button type="button" class="customize-controls-refresh-plugin">' +
			'<i class="dashicons dashicons-update"></i>' +
		'</button>'
	);
	$( '.customize-controls-refresh-plugin' ).on( 'click', function() {
		wp.customize.previewer.refresh();
	});
	// Set Popups Designer actions
	wp.customize.panel( 'md_popups_designer', function( section ) {
		section.expanded.bind( function( isExpanding ) {
			if ( isExpanding ) {
				$.each( mdPopups.popups, function( c, id ) {
					wp.customize.section( 'marketers_delight[popups_data][' + id + ']', function( section ) {
						section.expanded.bind( function( isExpanding ) {
							if ( isExpanding ) {
								wp.customize.previewer.send( 'md-popups-edit', {
									expanded: isExpanding,
									popupId: id
								});
								wp.customize.previewer.refresh();
							}
						});
					});
				});
			}
			else {
				wp.customize.previewer.send( 'md-popups-close', {
					home_url: wp.customize.settings.url.home
				});
			}
		});
	});
	// Create insane settings toggles
	$( '.md-customize-toggle' ).on( 'click', function() {
		var group = $( this ).data( 'md-customize-toggle-group' ),
			field = $( this ).data( 'md-customize-toggle-field' ),
			current = '[id^="customize-control-' + group + '-' + field + '"]';
		$( this ).toggleClass( 'md-customize-label-toggle-active' );
		$( '.md-customize-label-toggle' ).not( this ).removeClass( 'md-customize-label-toggle-active' );
		$( current ).toggleClass( 'md-customize-toggle-active' );
		$( '[id^="customize-control-' + group + '"]' ).not( current ).removeClass( 'md-customize-toggle-active' );
	});
});