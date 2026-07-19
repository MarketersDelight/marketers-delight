jQuery( document ).ready( function( $ ) {
	function resetTabs( tabs ) {
		tabs.find( '.md-tab' ).removeClass( 'nav-tab-active' ).first().addClass( 'nav-tab-active' );
		tabs.find( '.md-tab-content' ).removeClass( 'active' ).first().addClass( 'active' );
	}

	$( '.md-check-val' ).on( 'change', function() {
		var loop = $( this ).closest( '.md-loop' ),
			active = this.value ? ( this.value === 'category' || this.value === 'category_posts' ) : loop.hasClass( 'is-category-inherited' );

		loop.toggleClass( 'has-category-posts', active );

		if ( ! active )
			resetTabs( loop.find( '.md-loop-options' ) );
	});

	$( '.md-content-val' ).on( 'change', function() {
		var group = $( this ).closest( '.md-loop-post-group' ),
			visible = this.value ? ( this.value !== 'hide' ) : ! group.hasClass( 'is-content-hidden-inherited' );

		group.find( '.md-loop-content-options' ).toggle( visible );
	});

	$( '.md-num-val' ).on( 'change', function() {
		var loop = $( this ).closest( '.md-loop' );

		if ( this.value >= 1 )
			loop.addClass( 'has-featured' );
		else {
			loop.removeClass( 'has-featured' );
			resetTabs( loop.find( '.md-loop-post' ) );
		}
	});
} );
