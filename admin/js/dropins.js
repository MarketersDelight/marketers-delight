( function( $ ) {

	var text = MDDropins.text,
		MDDropinsScreen = {

		init: function() {
			this.filters();
			this.toggle();
			this.upload();
			this.updates();
		},

		format: function( string, value ) {
			return string.replace( '%s', value ).replace( '%d', value );
		},

		install: function( data ) {
			var formData = new FormData();

			formData.append( 'action', 'md_install_dropin' );
			formData.append( 'nonce', MDDropins.nonce );

			$.each( data, function( key, value ) {
				formData.append( key, value );
			} );

			return $.ajax( {
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: formData,
				contentType: false,
				processData: false
			} );
		},

		error: function( request ) {
			var json = request.responseJSON;

			return json && json.data ? json.data : { message: text.failed };
		},

		filters: function() {
			var self = this,
				views = $( '.md-dropins-views' ),
				search = $( '#md_dropin_search' ),
				table = $( '.md-dropins-table' ),
				status = views.find( '.md-submenu-active' ).data( 'view' ) || 'all';

			if ( ! table.length )
				return;

			views.on( 'click', '[data-local]', function( e ) {
				if ( ! views.find( '.md-submenu-active' ).is( '[data-local]' ) )
					return;

				e.preventDefault();

				views.find( '.md-submenu-item' ).removeClass( 'md-submenu-active' ).removeAttr( 'aria-current' );

				$( this ).addClass( 'md-submenu-active' ).attr( 'aria-current', 'page' );

				status = $( this ).data( 'view' );

				self.filter( table, status, search.val() );
			} );

			search.on( 'input', function() {
				self.filter( table, status, search.val() );
			} );

			search.closest( 'form' ).on( 'submit', function( e ) {
				e.preventDefault();

				self.filter( table, status, search.val() );
			} );
		},

		filter: function( table, status, term ) {
			var shown = 0,
				url = new URL( window.location.href );

			term = $.trim( term || '' ).toLowerCase();

			table.find( 'tbody tr.md-dropin-row' ).each( function() {
				var row = $( this ),
					show = ( status === 'active' ? row.hasClass( 'is-active' ) : status === 'inactive' ? row.hasClass( 'is-inactive' ) : status === 'upgrade' ? row.hasClass( 'has-update' ) : true ) &&
						( ! term || String( row.attr( 'data-search' ) ).indexOf( term ) !== -1 );

				row.prop( 'hidden', ! show );
				row.next( '.md-dropin-notice-row' ).prop( 'hidden', ! show );

				if ( ! show )
					row.find( '.check-column input' ).prop( 'checked', false );

				shown += show ? 1 : 0;
			} );

			table.find( '.md-dropins-empty' ).prop( 'hidden', shown > 0 );
			table.find( 'thead .check-column input, tfoot .check-column input' ).prop( 'checked', false );

			$( '.md-dropins .displaying-num' ).text( this.format( shown === 1 ? text.item : text.items, shown ) );
			$( 'input[name="dropin_status"]' ).val( status );

			if ( status === 'all' )
				url.searchParams.delete( 'dropin_status' );
			else
				url.searchParams.set( 'dropin_status', status );

			if ( term )
				url.searchParams.set( 's', term );
			else
				url.searchParams.delete( 's' );

			window.history.replaceState( null, '', url.toString() );
		},

		toggle: function() {
			$( '.md-upload-toggle' ).on( 'click', function() {
				var button = $( this ),
					panel = $( '#' + button.attr( 'aria-controls' ) ),
					open = button.attr( 'aria-expanded' ) !== 'true';

				button.attr( 'aria-expanded', open ? 'true' : 'false' );
				panel.prop( 'hidden', ! open );

				if ( open )
					panel.find( 'input[type="file"]' ).trigger( 'focus' );
			} );
		},

		upload: function() {
			var form = $( '.md-dropin-upload-form' ),
				input = form.find( 'input[type="file"]' ),
				submit = form.find( 'input[type="submit"]' ),
				results = $( '.md-dropin-results' ),
				self = this;

			if ( ! form.length )
				return;

			input.on( 'change', function() {
				submit.prop( 'disabled', ! this.files.length );
			} );

			form.on( 'submit', function( e ) {
				var files = $.makeArray( input.prop( 'files' ) );

				e.preventDefault();

				if ( ! files.length )
					return;

				submit.prop( 'disabled', true );
				input.prop( 'disabled', true );
				results.prop( 'hidden', false );

				self.queue( $.map( files, function( file ) {
					var row = $( '<tr class="md-dropin-result"><td><strong></strong></td><td class="md-dropin-result-current">—</td><td class="md-dropin-result-uploaded">—</td><td class="md-dropin-result-requires">—</td><td><span class="md-dropin-result-status"></span></td></tr>' );

					self.resultState( row, 'busy' );
					row.data( 'file', file ).find( 'strong' ).text( file.name );
					row.find( '.md-dropin-result-status' ).text( text.installing );

					results.find( 'tbody' ).append( row );

					return function() {
						return self.uploadOne( file, row, false );
					};
				} ) ).always( function() {
					input.val( '' ).prop( 'disabled', false );
				} );
			} );

			results.on( 'click', '.md-dropin-replace-all', function() {
				var waiting = results.find( '.md-dropin-result.is-exists' );

				if ( ! confirm( self.format( text.replaceAllConfirm, waiting.length ) ) )
					return;

				self.queue( waiting.map( function() {
					var row = $( this );

					return function() {
						return self.uploadOne( row.data( 'file' ), row, true );
					};
				} ).get() );
			} );
		},

		resultActions: function() {
			var results = $( '.md-dropin-results' ),
				waiting = results.find( '.md-dropin-result.is-exists' ).length,
				busy = results.find( '.md-dropin-result.is-busy' ).length,
				all = results.find( '.md-dropin-replace-all' ),
				refresh = results.find( '.md-dropin-results-refresh' );

			all.text( this.format( text.updateAll, waiting ) ).prop( 'hidden', busy || waiting < 2 );
			refresh.prop( 'hidden', busy || ! results.find( '.is-new' ).length );
			results.find( '.md-dropin-results-actions' ).prop( 'hidden', all.prop( 'hidden' ) && refresh.prop( 'hidden' ) );
			results.prop( 'hidden', ! results.find( '.md-dropin-result' ).length );
		},

		queue: function( jobs ) {
			var done = $.Deferred(),
				index = 0;

			function next() {
				if ( index >= jobs.length ) {
					done.resolve();
					return;
				}

				jobs[index++]().always( next );
			}

			next();

			return done.promise();
		},

		uploadOne: function( file, row, overwrite ) {
			var self = this;

			this.resultState( row, 'busy' );
			row.find( '.md-dropin-result-status' ).text( text.installing );
			row.find( '.md-dropin-result-buttons' ).remove();
			this.resultActions();

			return this.install( { file: file, overwrite: overwrite ? 1 : '' } ).done( function( response ) {
				self.installed( row, response.data );
			} ).fail( function( request ) {
				var error = self.error( request );

				if ( error.code === 'md_dropin_exists' )
					self.compare( file, row, error.data );
				else {
					self.resultState( row, 'error' );
					row.find( '.md-dropin-result-status' ).text( error.message );
				}
			} ).always( function() {
				self.resultActions();
			} );
		},

		resultState: function( row, state ) {
			row.removeClass( 'is-busy is-done is-error is-exists' ).addClass( 'is-' + state );
		},

		installed: function( row, data ) {
			var status = row.find( '.md-dropin-result-status' );

			this.resultState( row, 'done' );
			row.toggleClass( 'is-new', ! data.replaced );
			row.find( 'strong' ).text( data.name );
			row.find( '.md-dropin-result-uploaded' ).text( data.version );

			if ( data.replaced )
				$( '.md-dropins-table tr[data-slug="' + data.slug + '"] .md-dropin-title .md-badge' ).text( data.version );

			status.text( data.replaced ? text.replaced : ( data.active ? text.activated : text.installed ) );

			if ( data.activate_url )
				status.append( ' ', $( '<a class="button button-small"></a>' ).attr( 'href', data.activate_url ).text( text.activate ) );
		},

		compare: function( file, row, data ) {
			var self = this;

			this.resultState( row, 'exists' );
			row.find( 'strong' ).text( data.current.Name || file.name );
			row.find( '.md-dropin-result-current' ).text( data.current.Version || '—' );
			row.find( '.md-dropin-result-uploaded' ).text( data.uploaded.Version || '—' );
			row.find( '.md-dropin-result-requires' ).text( data.uploaded.RequiresMD || '—' );
			row.find( '.md-dropin-result-status' ).text( text.exists ).after(
				$( '<span class="md-dropin-result-buttons"></span>' ).append(
					$( '<button type="button" class="button button-small button-primary"></button>' ).text( text.replace ).on( 'click', function() {
						self.uploadOne( file, row, true );
					} ),
					$( '<button type="button" class="button button-small"></button>' ).text( text.cancel ).on( 'click', function() {
						row.remove();
						self.resultActions();
					} )
				)
			);
		},

		updates: function() {
			var self = this;

			$( document ).on( 'click', '.md-update-row .md-update-button, .md-update-row .md-update-retry', function( e ) {
				var button = $( this ),
					row = button.closest( '.md-update-row' ),
					alert = button.data( 'md-alert' );

				e.preventDefault();

				if ( row.hasClass( 'is-updating' ) || ( alert && ! confirm( alert ) ) )
					return;

				self.updateRow( row ).done( function() {
					if ( row.data( 'md-update' ) === 'theme' || ! self.remaining().length )
						window.location.reload();
				} );
			} );

			$( document ).on( 'click', '.md-update-all', function( e ) {
				var button = $( this ),
					card = button.closest( '.md-updates-card' ),
					dropins = card.find( '.md-update-row[data-md-update="dropin"]' ).not( '.is-updated' ),
					theme = card.find( '.md-update-row[data-md-update="theme"]' ).not( '.is-updated' ),
					jobs = [];

				e.preventDefault();

				if ( button.prop( 'disabled' ) || ! confirm( text.updateAllConfirm ) )
					return;

				button.prop( 'disabled', true ).addClass( 'is-busy' ).text( text.updating );

				dropins.add( theme ).each( function() {
					var row = $( this );

					jobs.push( function() {
						return self.updateRow( row );
					} );
				} );

				self.queue( jobs ).always( function() {
					if ( card.find( '.md-update-row.is-error' ).length ) {
						button.prop( 'disabled', false ).removeClass( 'is-busy' );
						self.counts( card );

						return;
					}

					window.location.reload();
				} );
			} );
		},

		remaining: function() {
			return $( '.md-update-row' ).not( '.is-updated' );
		},

		updateRow: function( row ) {
			var self = this,
				type = row.data( 'md-update' ),
				slug = row.data( 'slug' ),
				meta = row.find( '.md-update-meta' ),
				data = type === 'theme' ?
					{ action: 'update-theme', slug: slug, _ajax_nonce: MDDropins.updatesNonce } :
					{ action: 'md_update_dropin', slug: slug, nonce: MDDropins.nonce };

			row.removeClass( 'is-error' ).addClass( 'is-updating' ).attr( 'aria-busy', 'true' );
			row.find( '.md-update-error, .md-update-retry' ).remove();
			row.find( '.md-update-button' ).after( '<span class="md-spinner" aria-hidden="true"></span>' );

			return $.post( ajaxurl, data, null, 'json' ).then( function( response ) {
				if ( ! response || ! response.success )
					return $.Deferred().reject( { responseJSON: response } );

				var version = type === 'theme' ? response.data.newVersion : response.data.version;

				row.removeClass( 'is-updating' ).addClass( 'is-updated' ).removeAttr( 'aria-busy' );
				row.find( '.md-spinner' ).replaceWith( '<span class="md-status-icon" aria-hidden="true"></span>' );

				if ( version )
					meta.text( self.format( text.updated, version ) );

				self.counts( row.closest( '.md-updates-card' ) );

				return response;
			} ).fail( function( request ) {
				var json = request && request.responseJSON,
					message = json && json.data ? ( json.data.message || json.data.errorMessage ) : '';

				row.removeClass( 'is-updating' ).addClass( 'is-error' ).removeAttr( 'aria-busy' );
				row.find( '.md-spinner' ).remove();
				meta.after( $( '<span class="md-update-error" role="alert"></span>' ).text( message || text.updateFailed ) );
				row.find( '.md-update-button' ).after( $( '<button type="button" class="button md-update-retry"></button>' ).text( text.retry ) );
			} );
		},

		counts: function( card ) {
			var left = card.find( '.md-update-row' ).not( '.is-updated' ).length,
				all = card.find( '.md-update-all' );

			card.find( '.md-updates-head .md-badge' ).toggleClass( 'is-warning', left > 0 ).toggleClass( 'is-success', ! left ).text( left === 1 ? text.update : ( left ? MDDropinsScreen.format( text.updates, left ) : text.upToDate ) );

			if ( ! all.hasClass( 'is-busy' ) )
				all.text( MDDropinsScreen.format( text.updateAll, left ) ).toggle( left > 0 );
		}

	};

	$( function() {
		MDDropinsScreen.init();
	} );

} )( jQuery );
