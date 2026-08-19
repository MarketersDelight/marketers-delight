( function( window, document, $ ) {
	window.MD = {
		init: function() {
			this.clone.init();
			this.sortable.init();
			if ( document.querySelector( '[data-md-collection]' ) )
				this.collections();
			this.action();
			this.toggle();
			this.tabs();
			this.conditional();
			this.range();
			this.linkFields.init();
			this.codeEditor();
			this.media();
			this.color();
			this.colorScheme();
			this.devices();
			this.update();
			this.clipboard();
			this.initWpEditor();
			this.select2Init();
		},
		initWpEditor: function() {
			var inits = document.getElementsByClassName( 'md-toggle-wp-editor' );

			for ( var i = 0; i < inits.length; i++ )
				inits[i].onclick = function( e ) {
					if ( ! $( this ).hasClass( 'remove-editor-wrap' ) )
						MD.wpEditor( this.getAttribute( 'id' ) );

					$( this ).addClass( 'remove-editor-wrap' );
				}
		},
		select2Init: function() {
			var inits = document.getElementsByClassName( 'md-select2-init' );

			for ( var i = 0; i < inits.length; i++ )
				inits[i].onclick = function( e ) {
					var parent = $( this ).parent( '.md-field-select' );
					parent.addClass( 'has-select2' );
					parent.find( '.md-select2' ).select2().select2( 'open' );
				}
		},
		wpEditor: function( el ) {
			if ( ! $( el ).hasClass( 'wp-editor-wrap' ) )
				wp.editor.initialize( el, {
					tinymce: {
						wpautop : true,
						plugins : 'charmap colorpicker hr lists tabfocus textcolor wordpress wpautoresize wpeditimage wpemoji wplink wptextpattern',
						toolbar1 : 'formatselect, link, bold, italic, blockquote, bullist, numlist, wp_adv, listbuttons, undo, redo',
						toolbar2 : 'alignleft, aligncenter, alignright, strikethrough, hr, forecolor, pastetext, charmap',
						textarea_rows : 5
					},
					quicktags : 'strong, em, link, block, del, ins, img, ul, ol, li, code, more, close',
					mediaButtons : true
				});
		},
		clipboard: function() {
			$( document ).on( 'click', '.md-clipboard', function() {
				var text = this.getAttribute( 'data-md-clipboard' );
				navigator.clipboard.writeText( text );
				$( this ).next( '.md-tooltip' ).html( 'Copied!' );
			});
		},
		collections: function() {
			$( document ).on( 'click', '[data-md-collection-action]', function() {
				var button = $( this ),
					collection = button.closest( '[data-md-collection]' ),
					action = button.data( 'md-collection-action' ),
					item = button.closest( '[data-md-collection-item]' );

				if ( action === 'edit' || action === 'cancel' ) {
					var editing = action === 'edit';
					item.find( '[data-md-collection-summary]' ).prop( 'hidden', editing );
					item.find( '[data-md-collection-editor]' ).prop( 'hidden', ! editing );
					return;
				}

				if ( action === 'trash' && ! confirm( collection.data( 'md-collection-confirm' ) ) )
					return;

				var fields, fieldRoot,
					items = collection.find( '[data-md-collection-items]' ),
					itemID = item.data( 'md-collection-item' ),
					endpoint = collection.data( 'md-collection-endpoint' ),
					url = itemID ? endpoint + '/' + itemID : endpoint,
					status = collection.find( '[data-md-collection-status]' );

				if ( action === 'add' || action === 'save' ) {
					fields = {};
					fieldRoot = action === 'add' ? button.closest( '.md-collection-add' ) : item;

					fieldRoot.find( '[data-md-collection-field]' ).each( function() {
						var field = $( this ),
							value = field.val();

						if ( field.data( 'md-collection-type' ) === 'terms' )
							value = value.split( ',' ).map( function( term ) {
								return term.trim();
							} ).filter( Boolean );

						fields[field.data( 'md-collection-field' )] = value;
					} );
				}

				if ( action === 'more' ) {
					var exclude = items.find( '[data-md-collection-item]' ).map( function() {
						return $( this ).data( 'md-collection-item' );
					} ).get();

					url += '?exclude=' + encodeURIComponent( exclude.join( ',' ) );
				}

				status.text( '' ).removeClass( 'is-error' );
				button.prop( 'disabled', true ).addClass( 'is-busy' );

				$.ajax( {
					url: url,
					type: {
						add: 'POST',
						save: 'PATCH',
						trash: 'DELETE',
						more: 'GET'
					}[action],
					headers: { 'X-WP-Nonce': collection.data( 'md-collection-nonce' ) },
					contentType: fields ? 'application/json' : undefined,
					data: fields ? JSON.stringify( { fields: fields } ) : undefined
				} ).done( function( result ) {
					if ( action === 'add' ) {
						items.prepend( result.html );
						fieldRoot.find( '[data-md-collection-field]' ).each( function() {
							var field = $( this ),
								type = field.data( 'md-collection-type' );

							if ( this.type === 'date' )
								return;

							if ( type === 'upload' )
								field.closest( '.md-upload' ).find( '.md-upload-remove' ).trigger( 'click' );
							else
								field.val( '' ).trigger( 'change' );
						} );
						collection.find( '[data-md-collection-empty]' ).prop( 'hidden', true );
						status.text( collection.data( 'md-collection-added' ) );
					}
					else if ( action === 'save' ) {
						item.replaceWith( result.html );
						status.text( collection.data( 'md-collection-saved' ) );
					}
					else if ( action === 'trash' ) {
						item.remove();
						collection.find( '[data-md-collection-empty]' ).prop( 'hidden', !! items.find( '[data-md-collection-item]' ).length );
						status.text( collection.data( 'md-collection-trashed' ) );
					}
					else if ( ! result.has_more )
						button.closest( '.md-collection-more' ).remove();

					if ( action !== 'more' )
						collection.find( '[data-md-collection-count]' ).text( result.count );
				} ).fail( function( request ) {
					var message = request.responseJSON && request.responseJSON.message ? request.responseJSON.message : collection.data( 'md-collection-error' );
					status.text( message ).addClass( 'is-error' );
				} ).always( function() {
					button.prop( 'disabled', false ).removeClass( 'is-busy' );
				} );
			} );
		},
		uniqueID: function() {
			var ret = '',
				n = Math.round( new Date().getTime() + ( Math.random() * 100 ) ),
				index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			for ( var i = Math.floor( Math.log( parseInt( n ) ) / Math.log( index.length ) ); i >= 0; i-- )
				ret = ret + index.substr( ( Math.floor( parseInt( n ) / Math.floor( Math.pow( parseFloat( index.length ), parseInt( i ) ) ) ) % index.length ), 1 );
			return ret.split( '' ).reverse().join( '' );
		},
		clone: {
			init: function() {
				this.new();
				this.delete( '.md-group' );
			},
			new: function() {
				$( document ).on( 'click', '.md-clone-add', function( e ) {
					var group = $( this ).data( 'clone-group' ),
						groupID = $( document.getElementById( 'md_group_' + group ) ),
						empty = groupID.children( '.md-group.empty' ).first(),
						clone = empty.clone( true ),
						token = '{clone:' + group + '}';
					MD.clone.filter( clone, token );
					clone.insertAfter( groupID.children( '.md-group' ).last() );
					clone.addClass( 'md-clone-new' );
					clone.removeClass( 'empty' );
					clone.show();
					clone.find( '.md-focus' ).focus();
					MD.select2Init();
					MD.initWpEditor();
					MD.clone.delete( '.md-group' );
					MD.linkFields.init();
					jscolor.install();
					clone.find( '.md-groups, .md-sort' ).each( function() {
						new Sortable( this, { handle: '.md-reorder', animation: 150 } );
					});
				});
			},
			filter: function( e, token ) {
				token = token || '{clone}';

				var newID = MD.uniqueID(),
					tags = e.find( '[for], [name], [id], [data-clone-group]' ),
					attrs = [ 'for', 'name', 'id', 'data-clone-group' ];
				e.find( '.md-clone-label' ).html( newID );
				tags.each( function() {
					var tag = $( this );
					$.each( attrs, function( i, attr ) {
						var val = tag.attr( attr );
						if ( val && val.indexOf( token ) > -1 )
							tag.attr( attr, val.split( token ).join( newID ) );
					});
				});
				e.find( '.md-populate-date' ).val( Math.round( new Date().getTime() / 1000 ) );
				e.find( '.md-populate-user-id' ).val( MDJS.user_id );
			},
			delete: function( parent ) {
				$( document ).on( 'click', '.md-delete', function( e ) {
					$( this ).closest( parent ).slideUp( 'fast', function() {
						$( this ).remove();
					});
				});
			}
		},
		sortable: {
			init: function() {
				this.groups();
				this.sort();
				this.shared();
				this.builder();
			},
			groups: function() {
				var groups = document.getElementsByClassName( 'md-groups' );
				for ( var i = 0; i < groups.length; i++ )
					new Sortable( groups[i], {
						handle: '.md-reorder',
						animation: 150
					});
			},
			sort: function() {
				var sort = document.getElementsByClassName( 'md-sort' );
				for ( var i = 0; i < sort.length; i++ )
					var sort = new Sortable( sort[i], {
						handle: '.md-reorder',
						animation: 150,
						onEnd: function ( e ) {
							var items = sort.toArray(),
								ignore = items.indexOf( 'hide' );

							if ( ignore > -1 )
								items.splice( ignore, 1 );

							$( e.item.parentElement ).find( '.md-sort-order' ).val( items.join( ',' ) );
						}
					});
			},
			shared: function() {
				var shared = document.getElementsByClassName( 'md-shared' );
				for ( var i = 0; i < shared.length; i++ )
					new Sortable( shared[i], {
						handle: '.md-reorder',
						animation: 150,
						group: 'shared',
						onAdd: function ( e ) {
							var canvas = e.item.parentElement.getAttribute( 'data-canvas' );
							$( e.item ).find( '.canvas-area' ).val( canvas );
						}
					});
			},
			builder: function() {
				var builder = document.getElementsByClassName( 'md-builder' );
				for ( var i = 0; i < builder.length; i++ )
					new Sortable( builder[i], {
						handle: '.md-reorder',
						animation: 150,
						group: 'builder',
						onAdd: function ( e ) {
							var canvas = e.item.parentElement.getAttribute( 'data-canvas' );
							$( e.item ).find( '.canvas-area' ).val( canvas );
						}
					});
				var elements = document.getElementsByClassName( 'md-builder-elements' );
				for ( var i = 0; i < elements.length; i++ )
					new Sortable( elements[i], {
						group: {
							name: 'builder',
							pull: 'clone',
							put: false
						},
						handle: '.md-reorder',
						animation: 150,
						onClone: function ( e ) {
							MD.clone.filter( $( e.item ) );
						}
					});
				MD.clone.delete( '.md-builder-group' );
				$( '.md-builder-tabs .nav-tab-active' ).each( function() {
					MD.filterPalette( $( this ) );
				});
			}
		},
		tabs: function() {
			$( document ).on( 'click', '.md-tab', function( e ) {
				e.preventDefault();
				var tab = $( this ).data( 'md-tab' ),
					parent = $( this ).closest( '.md-tabs' );
				parent.find( '.md-tab' ).removeClass( 'nav-tab-active' );
				$( this ).addClass( 'nav-tab-active' );
				parent.children( '.md-tab-content' ).removeClass( 'active' );
				parent.children( '.' + tab ).addClass( 'active' );
				MD.filterPalette( $( this ) );
			});
		},
		filterPalette: function( tab ) {
			var context = tab.data( 'context' ),
				palette = tab.closest( '.md-builder-tabs' ).prev( '.md-builder-controls' ).find( '.md-builder-elements' );
			if ( ! palette.length || ! context ) return;
			palette.children().each( function() {
				var elContext = $( this ).data( 'context' );
				$( this ).toggle( ! elContext || elContext === context );
			});
		},
		conditional: function() {
			$( document ).on( 'change', '.md-conditional-option', function( e ) {
				var el = $( this ),
					val = el.is( '[type="checkbox"]' ) ? ( this.checked ? el.val() : '' ) : el.val(),
					parent = el.closest( '.md-conditional' ),
					children = function() {
						return $( this ).closest( '.md-conditional' ).is( parent );
					};

				if ( parent.hasClass( 'md-conditional-invert' ) )
					val = val ? '' : '1';

				parent.find( '.md-conditional-item' ).filter( children ).hide();
				parent.find( '.md-conditional-' + val ).filter( children ).show();
			} );
		},
		toggle: function() {
			$( document ).on( 'click', 'h3.md-widget-title, .md-widget-handle, .md-toggle-arrow', function() {
				var toggle = $( this );
				if ( toggle.parent().hasClass( 'open' ) )
					toggle.parent().removeClass( 'open' );
				else {
					toggle.closest( '.md-toggle' ).siblings( '.md-toggle' ).removeClass( 'open' );
					toggle.closest( '.md-toggle' ).toggleClass( 'open');
				}
				jscolor.install();
			});
		},
		linkFields: {
			init: function() {
				var linkTypes = document.getElementsByClassName( 'md-link-type' ),
					linkStyles = document.getElementsByClassName( 'md-link-style' );
				this.linkToggle( linkTypes, 'type' );
				this.linkToggle( linkStyles, 'style' );
			},
			linkToggle: function( fields, prefix ) {
				for ( var i = 0; i < fields.length; i++ ) {
					fields[i].onclick = function( e ) {
						var parent = jQuery( this ).parents( '.md-group-link' );
						parent.removeClass( function( index, className ) {
							if ( prefix == 'type' ) //wtf
								var classes = ( className.match( /(^|\s)type-\S+/g ) || [] ).join( ' ' );
							else if ( prefix == 'style' )
								var classes = ( className.match( /(^|\s)style-\S+/g ) || [] ).join( ' ' );
							return classes;
						});
						if ( this.value )
							parent.addClass( prefix + '-' + this.value );
					}
				}
			}
		},
		range: function() {
			$( document ).on( 'input change', '.md-range-field', function() {
				var parent = $( this ).parents( '.md-range' ),
					number = parent.find( '.md-range-number' );
				number.val( $( this ).val() );
			});
			$( document ).on( 'input change', '.md-range-number', function() {
				var parent = $( this ).parents( '.md-range' ),
					range = parent.find( '.md-range-field' );
				range.val( $( this ).val() );
			});
			$( document ).on( 'click', '.md-range-reset', function() {
				var data = $( this ).data( 'default' ),
					parent = $( this ).parents( '.md-range' )
					number = parent.find( '.md-range-number' ),
					range = parent.find( '.md-range-field' );
				number.val( '' );
				range.val( data );
			});
		},
		color: function() {
			jscolor.presets.default = {
				format: 'any',
				required: false,
				width: 140,
				height: 140,
				paletteCols: 8,
				onChange: function() {
					var el = this.valueElement;
					if ( el.value !== '' )
						$( el ).addClass( 'md-has-color-value' );
					else
						$( el ).removeClass( 'md-has-color-value ' );

					var sourceWrap = $( el ).closest( '.md-color-source-wrap' );
					if ( sourceWrap.length )
						MD.syncColorSourceCustom( sourceWrap, el.value );
				},
				palette: MDJS.colors.colors,
			};
			$( '.md-color-picker-reset' ).on( 'click', function( e ) {
				e.preventDefault();
				$( this ).parent().parent().find( '.md-color-picker' ).removeClass( 'md-has-color-value' ).css( 'background-image', 'none' ).val( '' );
			});
		},
		colorScheme: function() {
			$( document ).on( 'click', '.md-color-source-trigger', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				var trigger = $( this ),
					wrap = trigger.closest( '.md-color-source-wrap' ),
					isOpen = wrap.hasClass( 'is-open' );

				MD.closeColorSources();

				if ( ! isOpen ) {
					wrap.addClass( 'is-open' );
					trigger.attr( 'aria-expanded', 'true' );
					wrap.find( '.md-color-source-menu' ).prop( 'hidden', false );
				}
			} );

			$( document ).on( 'click', '.md-color-source-option, .md-color-source-palette-option', function() {
				var option = $( this ),
					wrap = option.closest( '.md-color-source-wrap' ),
					mode = option.data( 'mode' );

				wrap.find( '.md-color-source-mode' ).val( mode );

				if ( mode === 'palette' )
					wrap.find( '.md-color-source-palette-value' ).val( option.data( 'palette-key' ) );

				MD.updateColorSource( wrap );
				MD.closeColorSources();
				wrap.find( '.md-color-source-trigger' ).trigger( 'focus' );
			} );

			$( document ).on( 'input change', '.md-color-source-custom .md-color-picker', function() {
				MD.syncColorSourceCustom( $( this ).closest( '.md-color-source-wrap' ), $( this ).val() );
			} );

			$( document ).on( 'click', '.md-color-source-reset', function() {
				var wrap = $( this ).closest( '.md-color-source-wrap' ),
					input = wrap.find( '.md-color-source-custom .md-color-picker' );

				input.removeClass( 'md-has-color-value' ).css( 'background-image', 'none' ).val( '' );
				MD.syncColorSourceCustom( wrap, '' );
				jscolor.hide();
			} );

			$( document ).on( 'click', '.md-color-source-apply', function() {
				var wrap = $( this ).closest( '.md-color-source-wrap' ),
					input = wrap.find( '.md-color-source-custom .md-color-picker' );

				if ( ! input.val() ) {
					input.trigger( 'focus' );
					return;
				}

				MD.setColorSourceCustom( wrap );
				MD.closeColorSources();
				wrap.find( '.md-color-source-trigger' ).trigger( 'focus' );
			} );

			$( document ).on( 'keydown', '.md-color-source-custom .md-color-picker', function( e ) {
				if ( e.key === 'Enter' ) {
					e.preventDefault();
					$( this ).closest( '.md-color-source-custom' ).find( '.md-color-source-apply' ).trigger( 'click' );
				}
			} );

			$( document ).on( 'keydown', function( e ) {
				if ( e.key === 'Escape' ) {
					var open = $( '.md-color-source-wrap.is-open' );
					MD.closeColorSources();
					open.find( '.md-color-source-trigger' ).trigger( 'focus' );
				}
			} );

			$( document ).on( 'click', function( e ) {
				if ( $( '.md-color-source-wrap.is-open' ).length && ! $( e.target ).closest( '.md-color-source-wrap, .jscolor-wrap' ).length )
					MD.closeColorSources();
			} );
		},
		closeColorSources: function() {
			$( '.md-color-source-wrap.is-open' ).each( function() {
				$( this ).removeClass( 'is-open' ).find( '.md-color-source-trigger' ).attr( 'aria-expanded', 'false' );
				$( this ).find( '.md-color-source-menu' ).prop( 'hidden', true );
			} );

			jscolor.hide();
		},
		setColorSourceCustom: function( wrap ) {
			wrap.find( '.md-color-source-mode' ).val( 'custom' );
			MD.updateColorSource( wrap );
		},
		syncColorSourceCustom: function( wrap, value ) {
			var input = wrap.find( '.md-color-source-custom .md-color-picker' );

			wrap.find( '.md-color-source-reset' ).prop( 'disabled', ! value );
			input.toggleClass( 'md-has-color-value', !! value );

			if ( value )
				MD.setColorSourceCustom( wrap );
			else if ( wrap.find( '.md-color-source-mode' ).val() === 'custom' ) {
				input.css( 'background-image', 'none' );
				wrap.find( '.md-color-source-mode' ).val( 'default' );
				MD.updateColorSource( wrap );
			}
		},
		updateColorSource: function( wrap ) {
			var mode = wrap.find( '.md-color-source-mode' ).val(),
				option = wrap.find( '.md-color-source-default' ),
				hex = option.data( 'hex' ),
				title = option.data( 'summary-title' ),
				meta = option.data( 'summary-meta' );

			if ( mode === 'palette' ) {
				var paletteKey = wrap.find( '.md-color-source-palette-value' ).val();
				option = wrap.find( '.md-color-source-palette-option' ).filter( function() {
					return String( $( this ).data( 'palette-key' ) ) === paletteKey;
				} ).first();
				hex = option.data( 'hex' );
				title = option.data( 'summary-title' );
				meta = option.data( 'summary-meta' );
			}
			else if ( mode === 'custom' ) {
				hex = wrap.find( '.md-color-source-custom .md-color-picker' ).val();
				title = wrap.data( 'custom-label' ) + ( hex ? ' · ' + String( hex ).toUpperCase() : '' );
				meta = wrap.data( 'hex-meta' );
			}

			wrap.find( '.md-color-source-title' ).first().text( title );
			wrap.find( '.md-color-source-meta' ).first().text( meta );
			wrap.find( '.md-color-source-option, .md-color-source-palette-option' ).removeClass( 'is-selected' ).attr( 'aria-pressed', 'false' );
			wrap.find( '.md-color-source-custom' ).toggleClass( 'is-selected', mode === 'custom' );

			if ( mode !== 'custom' )
				option.addClass( 'is-selected' ).attr( 'aria-pressed', 'true' );

			var swatch = wrap.find( '.md-color-source-swatch' );

			if ( hex )
				swatch.removeClass( 'is-empty' ).css( 'background-color', hex );
			else
				swatch.addClass( 'is-empty' ).css( 'background-color', '' );
		},
		codeEditor: function() {
			$( '.md-code-editor textarea' ).keydown( function( e ) {
				if ( e.keyCode === 9 ) {
					var start = this.selectionStart,
						end = this.selectionEnd,
						$this = $( this ),
						value = $this.val();
					$this.val( value.substring( 0, start ) + "\t" + value.substring( end ) );
					this.selectionStart = this.selectionEnd = start + 1;
					e.preventDefault();
				}
			});
		},
		fileUpload: function( id, uploadAction ) {
			$( '#' + id ).on( 'change', function() {
				var upload = $( this ),
					formData = new FormData(),
					file = upload.prop( 'files' )[0],
					parent = upload.parents( '.md-file-upload' );
				formData.append( 'action', 'md_file' );
				formData.append( 'upload_action', uploadAction );
				formData.append( 'nonce', MDJS.nonce );
				formData.append( 'file', file );
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					beforeSend: function() {
						parent.find( '.md-loading' ).css( 'display', 'inline-block' );
					},
					success: function( response ) {
						parent.find( '.md-loading' ).hide();
						parent.find( '.md-file-upload-success' ).fadeIn().delay( 3000 ).fadeOut();
						window.location.reload( true );
					}
				});
			});
		},
		devices: function() {
			$( '.md-device' ).on( 'click', function( e ) {
				var device = $( this ).attr( 'id' ),
					wrap = $( this ).parents( '.wrap' );
				$( '.md-device' ).removeClass( 'active' );
				$( this ).addClass( 'active' );
				wrap.removeClass( 'desktop mobile' );
				wrap.addClass( device );
			});
		},
		update: function() {
			$( document ).on( 'click', '.md-update-button', function( e ) {
				e.preventDefault();
				var alert = $( this ).data( 'md-alert' );
				if ( alert && ! confirm( alert ) ) return;
				window.location = $( this ).attr( 'href' );
			});
		},
		action: function() {
			$( document ).on( 'click', '.md-action', function( e ) {
				e.preventDefault();
				var trigger = $( this ),
					action = trigger.data( 'md-action' ),
					alert = trigger.data( 'md-alert' ),
					canvas = trigger.data( 'md-canvas' ),
					itemID = trigger.data( 'md-dropin-id' ),
					licenseKey = '';
				if ( action == 'activate-license' || action == 'deactivate-license' )
					licenseKey = $( '#marketers_delight_settings_license_key' ).val();
				if ( alert && ! confirm( alert ) )
					return;
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'md_action',
						nonce: MDJS.nonce,
						action_type: action,
						canvas: canvas,
						dropin_id: itemID,
						license_key: licenseKey
					},
					beforeSend: function() {
						trigger.find( '.dashicons' ).addClass( 'md-loading' );
					},
					success: function( response ) {
						trigger.find( '.dashicons' ).removeClass( 'md-loading' ).removeClass( 'dashicons-update-alt' ).addClass( 'dashicons-yes' );
						if ( canvas === undefined )
							window.location.reload( true );
						else {
							var canvasEl = $( canvas );
							$( canvasEl ).html( response );
						}
					}
				});
			});
		},
		integrations: function() {
			$( document ).on( 'click', '.md-integration-button', function( e ) {
				e.preventDefault();
				var button = $( this ),
					actionType = button.data( 'md-integration-action' ),
					integration = button.data( 'md-integration' ),
					block = '.md-integration.' + integration,
					loading = $( block + ' .md-loading' );
				if ( actionType == 'manual_refresh' ) {
					$( block ).addClass( 'manual-refresh' );
					$( block + ' .step-1' ).show();
					$( block + ' .step-2' ).hide();
					return;
				}
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'md_integrations',
						form: $( '#md-form' ).serialize(),
						integration: integration,
						action_type: actionType,
					},
					beforeSend: function() {
						button.prop( 'disabled', true );
						loading.css( 'display', 'inline-block' );
					},
					success: function( template ) {
						loading.hide();
						$( block ).replaceWith( template );
						$( block ).addClass( 'open' );
					},
					error: function() {
						loading.hide();
						$( block ).addClass( 'invalid' );
						button.prop( 'disabled', false );
					}
				});
			});
		},
		stickyPostTypes: function() {
			if ( MDJS.screen == 'post' ) {
				if ( parseInt( MDJS.is_sticky ) )
					$( '#post-visibility-display' ).text( 'Public, Sticky' );
				$( '#post-visibility-select label[for="visibility-radio-public"]' ).next( 'br' ).after(
					'<span id="sticky-span">' +
						'<input id="sticky" name="sticky" type="checkbox" value="sticky"' + MDJS.checked_attribute + ' /> ' +
						'<label for="sticky" class="selectit">Stick to the top of stream</label>' +
						'<br />' +
					'</span>'
				);
			}
			else {
				$( 'span.title:contains(\'Status\')' ).parent().after(
					'<label class="alignleft">' +
						'<input type="checkbox" name="sticky" value="sticky" /> ' +
						'<span class="checkbox-title">Make this post sticky</span>' +
					'</label>'
				);
			}
		},
		media: function() {
			var getUploadPreviewSrc = function( upload ) {
				var attrs = upload && upload.attributes ? upload.attributes : {},
					sizes = attrs.sizes || {};

				if ( sizes.thumbnail && sizes.thumbnail.url )
					return sizes.thumbnail.url;

				if ( sizes.medium && sizes.medium.url )
					return sizes.medium.url;

				if ( sizes.full && sizes.full.url )
					return sizes.full.url;

				return attrs.url || '';
			};

			$( document ).on( 'click', '.md-upload-add', function() {
				var parent = $( this ).parents( '.md-upload' ),
					isMultiple = $( this ).data( 'md-multiple' ) === true;
				media = wp.media.frames.file_frame = wp.media({
					frame: 'select',
					multiple: isMultiple,
					library: { type: 'image' }
				});
				media.on( 'select', function() {
					var selection = media.state().get( 'selection' ),
						uploadID = parent.find( '.md-upload-id' );
					if ( selection ) {
						if ( isMultiple ) {
                			var ids = uploadID.val() ? uploadID.val().split( ',' ).filter( Boolean ) : [];
                			selection.each( function( upload ) {
								var id = upload.attributes.id,
									previewSrc = getUploadPreviewSrc( upload ),
									img = $( '<img>' ).attr( {
										src: previewSrc,
										alt: 'Preview image'
									} ),
									span = $( '<span>' ).attr( {
										class: 'md-upload-multi-remove',
										'data-md-image-id' : id,
									} );
                    			ids.push( id );
								span.append( img );
                    			parent.find( '.md-upload-preview-image' ).append( span );
                			});
                			uploadID.val( ids.join( ',' ) );
						}
						else {
							selection.each( function( upload ) {
								var previewSrc = getUploadPreviewSrc( upload );
								uploadID.val( upload.attributes.id );
								parent.find( '.md-upload-preview-image img' ).attr( 'src', previewSrc );
							});
						}
						parent.addClass( 'has-upload' );
					}
				});
				media.open();
			});
			$( document ).on( 'click', '.md-upload-remove', function() {
				var parent = $( this ).parents( '.md-upload' );
				parent.removeClass( 'has-upload' );
				parent.find( '.md-upload-id' ).val( '' );
				parent.find( '.md-upload-preview-image img' ).attr( 'src', '' );
				parent.find( '.md-upload-id-label' ).remove();
				parent.find( '.md-upload-action' ).remove();
			});
			$( document ).on( 'click', '.md-upload-multi-remove', function() {
    			var img = $( this ),
        			parent = img.closest( '.md-upload' ),
        			idToRemove = img.data( 'md-image-id' ),
        			idField = parent.find( '.md-upload-id' ),
        			idsArr = idField.val().split( ',' ).filter( Boolean ),
        			index = idsArr.indexOf( idToRemove.toString() );

    			if ( index !== -1 ) {
        			idsArr.splice( index, 1 );
        			idField.val( idsArr.join( ',' ) );
    			}

    			img.remove();

    			if ( parent.find( '.md-upload-preview-image img' ).length === 0 ) {
        			parent.removeClass( 'has-upload' );
    			}
			});
		}
	}
	MD.init();
})( window, document, jQuery );
