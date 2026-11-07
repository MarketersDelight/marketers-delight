( function( window, document, $ ) {
	window.MD = {
		init: function() {
			this.clone.init();
			this.sortable.init();
			this.tabs.init();
			this.toggles();
			this.conditional();
			this.range();
			this.loop();
			this.linkFields();
			this.codeEditor();
			this.clipboard();
			this.devices();
			this.wpEditor();
			this.media();
			this.fileUpload();
			this.color();
			this.picker.init();
			this.font.init();
			this.tokens();
			this.colorSource.init();
			this.license();
			this.integrations();
			if ( document.querySelector( '[data-md-collection]' ) )
				this.collections();
			this.liveSave.init();
		},

		// Groups & Builder

		uniqueID: function() {
			return Date.now().toString( 36 ) + Math.random().toString( 36 ).slice( 2, 6 );
		},
		clone: {
			init: function() {
				this.new();
				this.delete();
			},
			new: function() {
				$( document ).on( 'click', '.md-clone-add', function( e ) {
					var group = $( this ).data( 'clone-group' ),
						groupID = $( document.getElementById( 'md_group_' + group ) ),
						empty = groupID.children( '.md-group.empty' ).first(),
						clone = empty.clone( true ),
						token = '{clone:' + group + '}',
						addName = $( this ).siblings( '.md-group-add-name' );

					if ( addName.length && ! addName.val().trim() ) {
						addName.focus();
						return;
					}

					MD.clone.filter( clone, token );
					clone.insertAfter( groupID.children( '.md-group' ).last() );
					clone.removeClass( 'empty' );
					clone.show();

					if ( addName.length ) {
						clone.find( 'input[type="text"]' ).first().val( addName.val().trim() ).trigger( 'change' );
						addName.val( '' ).focus();
					}
					else
						clone.find( '.md-focus' ).focus();

					jscolor.install();
					clone.find( '.md-groups, .md-sort' ).each( function() {
						new Sortable( this, { handle: '.md-reorder', animation: 150 } );
					});
					$( document ).trigger( 'md:clone-added', [ clone ] );
				});
				$( document ).on( 'keydown', '.md-group-add-name', function( e ) {
					if ( e.key !== 'Enter' )
						return;

					e.preventDefault();
					$( this ).siblings( '.md-clone-add' ).trigger( 'click' );
				});
			},
			filter: function( e, token ) {
				token = token || '{clone}';

				var newID = MD.uniqueID(),
					tags = e.find( '[for], [name], [id], [data-clone-group]' ),
					attrs = [ 'for', 'name', 'id', 'data-clone-group' ];
				tags.each( function() {
					var tag = $( this );
					$.each( attrs, function( i, attr ) {
						var val = tag.attr( attr );
						if ( val && val.indexOf( token ) > -1 )
							tag.attr( attr, val.split( token ).join( newID ) );
					});
				});
			},
			delete: function() {
				$( document ).on( 'click', '.md-delete', function( e ) {
					var group = $( this ).closest( '.md-group' ),
						row = group.parent( '.md-builder-group' ),
						field = row.closest( '.md-field-builder' );

					if ( row.length )
						group = row;

					group.slideUp( 'fast', function() {
						$( this ).remove();

						if ( field.length )
							MD.sortable.counts( field );
					});
				});
			}
		},
		sortable: {
			init: function() {
				this.groups();
				this.shared();
				this.builder();
				$( '.md-field-builder' ).each( function() {
					MD.sortable.counts( this );
				});
			},
			counts: function( field ) {
				var builder = $( field ).closest( '.md-field-builder' ),
					counts = {};

				if ( ! builder.length )
					builder = $( field );

				builder.find( '.md-builder[data-builder-tab]' ).each( function() {
					var tab = $( this ).data( 'builder-tab' );
					counts[tab] = ( counts[tab] || 0 ) + $( this ).children( '.md-builder-group' ).not( '.sortable-ghost' ).length;
				});

				builder.find( '[data-builder-count-tab]' ).each( function() {
					var tab = $( this ).data( 'builder-count-tab' );
					var count = counts[tab] || 0;
					$( this ).find( '.md-builder-tab-count' ).text( count ? '(' + count + ')' : '' );
				});
			},
			groups: function() {
				var groups = document.querySelectorAll( '.md-groups, .md-sort' );
				for ( var i = 0; i < groups.length; i++ )
					new Sortable( groups[i], {
						handle: '.md-reorder',
						animation: 150
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
							MD.sortable.counts( e.item );
						},
						onRemove: function ( e ) {
							MD.sortable.counts( e.from );
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
				$( '.md-builder-tabs .nav-tab-active' ).each( function() {
					MD.sortable.filterPalette( $( this ) );
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
			}
		},
		tabs: {
			init: function() {
				$( '.md-tabs' ).each( function() {
					var tabs = $( this ),
						active = tabs.children( '.nav-tab-wrapper' ).find( '.nav-tab-active' ).data( 'md-tab' );

					if ( active && tabs.children( '.' + active ).length ) {
						tabs.children( '.md-tab-content' ).removeClass( 'active' );
						tabs.children( '.' + active ).addClass( 'active' );
					}

					tabs.addClass( 'md-tabs-ready' );
				});
				$( document ).on( 'click', '.md-tab', function( e ) {
					e.preventDefault();
					var tab = $( this ).data( 'md-tab' ),
						parent = $( this ).closest( '.md-tabs' );
					parent.children( '.nav-tab-wrapper' ).find( '.md-tab' ).removeClass( 'nav-tab-active' );
					$( this ).addClass( 'nav-tab-active' );
					parent.children( '.md-tab-content' ).removeClass( 'active' );
					parent.children( '.' + tab ).addClass( 'active' );
					MD.sortable.filterPalette( $( this ) );
				});
			},
			reset: function( tabs ) {
				tabs.children( '.nav-tab-wrapper' ).find( '.md-tab' ).removeClass( 'nav-tab-active' ).first().addClass( 'nav-tab-active' );
				tabs.children( '.md-tab-content' ).removeClass( 'active' ).first().addClass( 'active' );
			}
		},
		toggles: function() {
			$( document ).on( 'click', 'h3.md-widget-title, .md-widget-handle, .md-toggle-arrow', function() {
				var toggle = $( this );
				if ( toggle.parent().hasClass( 'open' ) )
					toggle.parent().removeClass( 'open' );
				else {
					toggle.closest( '.md-toggle' ).siblings( '.md-toggle' ).removeClass( 'open' );
					toggle.closest( '.md-toggle' ).toggleClass( 'open');
				}
			});
			$( document ).on( 'click', '.md-toggle > .md-updates-head', function( e ) {
				if ( ! $( e.target ).closest( 'button, a' ).length )
					$( this ).parent().toggleClass( 'open' );
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

				if ( el.is( '[type="checkbox"][data-md-inherit-parent]' ) )
					val = Number( this.checked ? el.val() : el.attr( 'data-md-inherit-parent' ) );

				if ( parent.hasClass( 'md-conditional-invert' ) )
					val = val ? '' : '1';

				parent.find( '.md-conditional-item' ).filter( children ).hide();
				parent.find( '.md-conditional-' + ( val || 'default' ) ).filter( children ).show();
			} );
		},

		// Fields

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
					parent = $( this ).parents( '.md-range' ),
					number = parent.find( '.md-range-number' ),
					range = parent.find( '.md-range-field' );
				number.val( '' );
				range.val( data );
			});
		},
		loop: function() {
			$( document ).on( 'change', '.md-check-val', function() {
				var loop = $( this ).closest( '.md-loop' ),
					active = this.value ? ( this.value === 'category' || this.value === 'category_posts' ) : loop.hasClass( 'is-category-inherited' );

				loop.toggleClass( 'has-category-posts', active );

				if ( ! active )
					MD.tabs.reset( loop.find( '.md-loop-options' ) );
			});
			$( document ).on( 'change', '.md-content-val', function() {
				var group = $( this ).closest( '.md-loop-post-group' ),
					visible = this.value ? ( this.value !== 'hide' ) : ! group.hasClass( 'is-content-hidden-inherited' );

				group.find( '.md-loop-content-options' ).toggle( visible );
			});
			$( document ).on( 'change', '.md-num-val', function() {
				var loop = $( this ).closest( '.md-loop' );

				loop.toggleClass( 'has-featured', this.value >= 1 );

				if ( this.value < 1 )
					MD.tabs.reset( loop.find( '.md-loop-post' ) );
			});
		},
		linkFields: function() {
			$( document ).on( 'change', '.md-link-style', function() {
				var parent = $( this ).closest( '.md-group-link' );
				parent.removeClass( function( index, className ) {
					return ( className.match( /(^|\s)style-\S+/g ) || [] ).join( ' ' );
				});
				if ( this.value )
					parent.addClass( 'style-' + this.value );
			});
		},
		codeEditor: function() {
			$( document ).on( 'keydown', '.md-code-editor textarea', function( e ) {
				if ( e.key !== 'Tab' || e.shiftKey || this.readOnly )
					return;

				e.preventDefault();
				this.setRangeText( '\t', this.selectionStart, this.selectionEnd, 'end' );
				this.dispatchEvent( new Event( 'input', { bubbles: true } ) );
			});
		},
		clipboard: function() {
			$( document ).on( 'click', '.md-clipboard', function() {
				var text = this.getAttribute( 'data-md-clipboard' );
				navigator.clipboard.writeText( text );
				$( this ).next( '.md-tooltip' ).text( MDJS.copied );
			});
		},
		devices: function() {
			$( document ).on( 'click', '.md-device', function() {
				var device = $( this ).attr( 'id' ),
					wrap = $( this ).parents( '.wrap' );
				$( '.md-device' ).removeClass( 'active' );
				$( this ).addClass( 'active' );
				wrap.removeClass( 'desktop mobile' );
				wrap.addClass( device );
			});
		},
		wpEditor: function() {
			$( document ).on( 'click', '.md-toggle-wp-editor', function() {
				if ( $( this ).hasClass( 'remove-editor-wrap' ) )
					return;

				$( this ).addClass( 'remove-editor-wrap' );
				wp.editor.initialize( this.id, {
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
			} );
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
					isMultiple = $( this ).data( 'md-multiple' ) === true,
					media = wp.media({
						frame: 'select',
						multiple: isMultiple,
						library: { type: 'image' }
					});
				media.on( 'select', function() {
					var selection = media.state().get( 'selection' ),
						uploadID = parent.find( '.md-upload-id' );
					if ( ! selection || ! selection.length )
						return;
					if ( isMultiple ) {
						var ids = uploadID.val() ? uploadID.val().split( ',' ).filter( Boolean ) : [];
						selection.each( function( upload ) {
							var id = upload.attributes.id,
								img = $( '<img>' ).attr( {
									src: getUploadPreviewSrc( upload ),
									alt: MDJS.preview_image
								} ),
								span = $( '<span>' ).attr( {
									class: 'md-upload-multi-remove',
									'data-md-image-id': id
								} );
							ids.push( id );
							span.append( img );
							parent.find( '.md-upload-preview-image' ).append( span );
						});
						uploadID.val( ids.join( ',' ) );
					}
					else {
						var upload = selection.first();
						uploadID.val( upload.attributes.id );
						parent.find( '.md-upload-preview-image img' ).attr( 'src', getUploadPreviewSrc( upload ) );
					}
					parent.addClass( 'has-upload' );
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
					idField = parent.find( '.md-upload-id' ),
					ids = idField.val().split( ',' ).filter( Boolean ),
					index = ids.indexOf( String( img.data( 'md-image-id' ) ) );

				if ( index !== -1 ) {
					ids.splice( index, 1 );
					idField.val( ids.join( ',' ) );
				}

				img.remove();

				if ( ! parent.find( '.md-upload-preview-image img' ).length )
					parent.removeClass( 'has-upload' );
			});
		},
		fileUpload: function() {
			$( document ).on( 'change', '.md-file-upload input[type="file"][data-md-upload-action]', function() {
				var loading = $( this ).closest( '.md-file-upload' ).find( '.md-loading' ),
					formData = new FormData();

				if ( ! this.files.length )
					return;

				formData.append( 'action', 'md_file' );
				formData.append( 'upload_action', this.getAttribute( 'data-md-upload-action' ) );
				formData.append( 'nonce', MDJS.nonce );
				formData.append( 'file', this.files[0] );
				loading.css( 'display', 'inline-block' );

				fetch( ajaxurl, {
					method: 'POST',
					body: formData,
					credentials: 'same-origin'
				} ).then( function( response ) {
					return response.json();
				} ).then( function( result ) {
					if ( ! result.success )
						throw new Error( result.data );

					window.location.reload();
				} ).catch( function( error ) {
					loading.hide();
					window.alert( error.message );
				} );
			});
		},

		// Pickers

		color: function() {
			jscolor.presets.default = {
				format: 'any',
				required: false,
				uppercase: false,
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
						MD.colorSource.sync( sourceWrap, el.value );
				},
				palette: MDJS.colors.colors,
			};
			$( document ).on( 'click', '.md-color-picker-reset', function( e ) {
				e.preventDefault();
				$( this ).parent().parent().find( '.md-color-picker' ).removeClass( 'md-has-color-value' ).css( 'background-image', 'none' ).val( '' );
			});
		},
		picker: {
			init: function() {
				$( document ).on( 'click', '.md-picker-trigger', function( e ) {
					e.preventDefault();
					e.stopPropagation();

					var trigger = $( this ),
						wrap = trigger.closest( '.md-picker' ),
						isOpen = wrap.hasClass( 'is-open' );

					MD.picker.close();

					if ( ! isOpen ) {
						wrap.addClass( 'is-open' );
						trigger.attr( 'aria-expanded', 'true' );
						wrap.find( '.md-picker-menu' ).prop( 'hidden', false );
					}
				} );

				$( document ).on( 'keydown', function( e ) {
					if ( e.key === 'Escape' ) {
						var open = $( '.md-picker.is-open' );
						MD.picker.close();
						open.find( '.md-picker-trigger' ).trigger( 'focus' );
					}
				} );

				$( document ).on( 'click', function( e ) {
					if ( $( '.md-picker.is-open' ).length && ! $( e.target ).closest( '.md-picker, .jscolor-wrap' ).length )
						MD.picker.close();
				} );
			},
			close: function() {
				$( '.md-picker.is-open' ).each( function() {
					$( this ).removeClass( 'is-open' ).find( '.md-picker-trigger' ).attr( 'aria-expanded', 'false' );
					$( this ).find( '.md-picker-menu' ).prop( 'hidden', true );
				} );

				jscolor.hide();
			}
		},
		font: {
			init: function() {
				$( document ).on( 'click', '.md-font-picker .md-picker-option:not(.md-font-custom-open)', function() {
					var wrap = $( this ).closest( '.md-font-picker' );
					MD.font.set( wrap, $( this ) );
					wrap.closest( '.col' ).find( '.md-font-type' ).val( '' );
					MD.picker.close();
					wrap.find( '.md-picker-trigger' ).trigger( 'focus' );
				} );

				$( document ).on( 'click', '.md-font-custom-open', function() {
					var wrap = $( this ).closest( '.md-font-picker' );
					MD.picker.close();
					wrap.find( '.md-picker-option' ).removeClass( 'is-selected' );
					$( this ).addClass( 'is-selected' );
					wrap.addClass( 'is-custom' ).find( '.md-font-custom-input' ).trigger( 'focus' );
				} );

				$( document ).on( 'click', '.md-font-list', function() {
					var wrap = $( this ).closest( '.md-font-picker' ),
						value = wrap.find( '.md-font-custom-input' ).val(),
						options = wrap.find( '.md-picker-option' ),
						match = options.filter( function() {
							return $( this ).data( 'stack' ) === value;
						} );
					wrap.removeClass( 'is-custom' );
					MD.font.set( wrap, match.length ? match.first() : options.first() );
					wrap.find( '.md-picker-trigger' ).trigger( 'focus' );
				} );
			},
			set: function( wrap, option ) {
				var input = wrap.find( '.md-font-custom-input' ),
					title = option.find( '.md-picker-title' ),
					stack = option.data( 'stack' );

				wrap.find( '.md-picker-option' ).removeClass( 'is-selected' );
				option.addClass( 'is-selected' );
				wrap.find( '.md-picker-trigger .md-picker-title' ).text( title.text() ).css( 'font-family', stack || option.data( 'preview' ) || '' );
				wrap.find( '.md-picker-trigger .md-font-badge' ).text( option.data( 'badge' ) );

				if ( input.val() !== stack ) {
					input.val( stack );
					input[0].dispatchEvent( new Event( 'change', { bubbles: true } ) );
				}
			}
		},
		tokens: function() {
			$( document ).on( 'mousedown', '.md-token-button, .md-token-option', function( e ) {
				e.preventDefault();
			} );

			$( document ).on( 'focus', '.md-token-field > input, .md-token-field > textarea', function() {
				this.mdTokenCaret = true;
			} );

			$( document ).on( 'click', '.md-token-button', function( e ) {
				e.stopPropagation();

				var button = $( this ),
					wrap = button.closest( '.md-token-field' ),
					isOpen = wrap.hasClass( 'is-open' );

				MD.picker.close();
				$( '.md-token-button' ).attr( 'aria-expanded', 'false' );

				if ( isOpen )
					return;

				wrap.append( $( '.md-token-menu[data-set="' + button.data( 'set' ) + '"]' ).first().prop( 'hidden', false ) ).addClass( 'is-open' );
				button.attr( 'aria-expanded', 'true' );
			} );

			$( document ).on( 'click', '.md-token-option', function() {
				var wrap = $( this ).closest( '.md-token-field' ),
					input = wrap.children( 'input, textarea' ).get( 0 ),
					start = input.mdTokenCaret ? input.selectionStart : input.value.length,
					end = input.mdTokenCaret ? input.selectionEnd : input.value.length;

				input.setRangeText( $( this ).data( 'token' ), start, end, 'end' );
				input.focus();
				input.dispatchEvent( new Event( 'input', { bubbles: true } ) );
				MD.picker.close();
				wrap.find( '.md-token-button' ).attr( 'aria-expanded', 'false' );
			} );
		},
		colorSource: {
			init: function() {
				$( document ).on( 'click', '.md-color-source-wrap .md-picker-option, .md-color-source-palette-option', function() {
					var option = $( this ),
						wrap = option.closest( '.md-color-source-wrap' ),
						mode = option.data( 'mode' );

					wrap.find( '.md-color-source-mode' ).val( mode );

					if ( mode === 'palette' )
						wrap.find( '.md-color-source-palette-value' ).val( option.data( 'palette-key' ) );

					MD.colorSource.update( wrap );
					MD.picker.close();
					wrap.find( '.md-picker-trigger' ).trigger( 'focus' );
				} );

				$( document ).on( 'input change', '.md-color-source-custom .md-color-picker', function() {
					MD.colorSource.sync( $( this ).closest( '.md-color-source-wrap' ), $( this ).val() );
				} );

				$( document ).on( 'click', '.md-color-source-reset', function() {
					var wrap = $( this ).closest( '.md-color-source-wrap' ),
						input = wrap.find( '.md-color-source-custom .md-color-picker' );

					input.removeClass( 'md-has-color-value' ).css( 'background-image', 'none' ).val( '' );
					MD.colorSource.sync( wrap, '' );
					jscolor.hide();
				} );

				$( document ).on( 'click', '.md-color-source-apply', function() {
					var wrap = $( this ).closest( '.md-color-source-wrap' ),
						input = wrap.find( '.md-color-source-custom .md-color-picker' );

					if ( ! input.val() ) {
						input.trigger( 'focus' );
						return;
					}

					MD.colorSource.set( wrap );
					MD.picker.close();
					wrap.find( '.md-picker-trigger' ).trigger( 'focus' );
				} );

				$( document ).on( 'keydown', '.md-color-source-custom .md-color-picker', function( e ) {
					if ( e.key === 'Enter' ) {
						e.preventDefault();
						$( this ).closest( '.md-color-source-custom' ).find( '.md-color-source-apply' ).trigger( 'click' );
					}
				} );
			},
			set: function( wrap ) {
				wrap.find( '.md-color-source-mode' ).val( 'custom' );
				MD.colorSource.update( wrap );
			},
			sync: function( wrap, value ) {
				var input = wrap.find( '.md-color-source-custom .md-color-picker' );

				wrap.find( '.md-color-source-reset' ).prop( 'disabled', ! value );
				input.toggleClass( 'md-has-color-value', !! value );

				if ( value )
					MD.colorSource.set( wrap );
				else if ( wrap.find( '.md-color-source-mode' ).val() === 'custom' ) {
					input.css( 'background-image', 'none' );
					wrap.find( '.md-color-source-mode' ).val( 'default' );
					MD.colorSource.update( wrap );
				}
			},
			update: function( wrap ) {
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

				wrap.find( '.md-picker-title' ).first().text( title );
				wrap.find( '.md-picker-meta' ).first().text( meta );
				wrap.find( '.md-picker-option, .md-color-source-palette-option' ).removeClass( 'is-selected' ).attr( 'aria-pressed', 'false' );
				wrap.find( '.md-color-source-custom' ).toggleClass( 'is-selected', mode === 'custom' );

				if ( mode !== 'custom' )
					option.addClass( 'is-selected' ).attr( 'aria-pressed', 'true' );

				var swatch = wrap.find( '.md-color-source-swatch' );

				if ( hex )
					swatch.removeClass( 'is-empty' ).css( 'background-color', hex );
				else
					swatch.addClass( 'is-empty' ).css( 'background-color', '' );
			}
		},

		// Requests

		license: function() {
			$( document ).on( 'click', '.md-action', function( e ) {
				e.preventDefault();
				var trigger = $( this ),
					action = trigger.data( 'md-action' ),
					alert = trigger.data( 'md-alert' ),
					canvas = trigger.data( 'md-canvas' ),
					licenseKey = '';
				if ( trigger.prop( 'disabled' ) )
					return;
				if ( action == 'activate-license' )
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
						license_key: licenseKey
					},
					beforeSend: function() {
						trigger.prop( 'disabled', true );
						trigger.closest( '.md-license' ).find( '.md-license-request-error' ).prop( 'hidden', true );
						trigger.find( '.dashicons' ).addClass( 'md-loading' );
					},
					success: function( response ) {
						if ( action === 'reset-icons' )
							window.location.reload();

						$( canvas ).html( response );
					},
					error: function() {
						trigger.closest( '.md-license' ).find( '.md-license-request-error' ).text( MDJS.license_request_error ).prop( 'hidden', false );
					},
					complete: function() {
						trigger.prop( 'disabled', false );
						trigger.find( '.dashicons' ).removeClass( 'md-loading' );
					}
				});
			});
			$( document ).on( 'click', '.md-license-toggle', function() {
				var fields = $( this ).closest( '.md-license' ).find( '.md-license-fields' );
				fields.toggleClass( 'open' );
				$( this ).attr( 'aria-expanded', fields.hasClass( 'open' ) ? 'true' : 'false' );
			});
		},
		integrations: function() {
			$( document ).on( 'click', '.md-integration-button', function( e ) {
				e.preventDefault();
				var button = $( this ),
					actionType = button.data( 'md-integration-action' ),
					integration = button.data( 'md-integration' ),
					block = '.md-integration.' + integration,
					loading = $( block + ' .md-loading' ),
					formFields = $( '#md-form' ).find( '[name="md_save_nonce"]' );
				if ( actionType == 'manual_refresh' ) {
					$( block ).addClass( 'manual-refresh' );
					$( block + ' .step-1' ).show();
					$( block + ' .step-2' ).hide();
					return;
				}
				if ( actionType == 'connect' )
					formFields = formFields.add( $( block ).find( ':input[name]' ) );
				button.prop( 'disabled', true );
				loading.css( 'display', 'inline-block' );
				fetch( ajaxurl, {
					method: 'POST',
					body: new URLSearchParams( {
						action: 'md_integrations',
						form: formFields.serialize(),
						integration: integration,
						action_type: actionType
					} ),
					credentials: 'same-origin'
				} ).then( function( response ) {
					if ( ! response.ok )
						throw new Error();

					return response.text();
				} ).then( function( template ) {
					loading.hide();
					$( block ).replaceWith( template );
					$( block ).addClass( 'open' );
				} ).catch( function() {
					loading.hide();
					$( block ).addClass( 'invalid' );
					button.prop( 'disabled', false );
				} );
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

					if ( window.tinymce )
						tinymce.triggerSave();

					fieldRoot.find( '[data-md-collection-field]' ).each( function() {
						var field = $( this );

						fields[field.data( 'md-collection-field' )] = field.val();
					} );
				}

				if ( action === 'more' ) {
					var exclude = items.find( '[data-md-collection-item]' ).map( function() {
						return $( this ).data( 'md-collection-item' );
					} ).get();

					url += ( url.indexOf( '?' ) === -1 ? '?' : '&' ) + 'exclude=' + encodeURIComponent( exclude.join( ',' ) );
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
						jscolor.install();
						fieldRoot.find( '[data-md-collection-field]' ).each( function() {
							var field = $( this ),
								type = field.data( 'md-collection-type' );

							if ( this.type === 'date' )
								return;

							if ( type === 'upload' )
								field.closest( '.md-upload' ).find( '.md-upload-remove' ).trigger( 'click' );
							else if ( this.tagName === 'SELECT' ) {
								$( this.options ).each( function() {
									this.selected = this.defaultSelected;
								} );
								field.trigger( 'change' );
							}
							else {
								field.val( this.defaultValue ).trigger( 'change' );

								if ( window.tinymce && tinymce.get( this.id ) )
									tinymce.get( this.id ).setContent( this.defaultValue );
							}
						} );
						collection.find( '[data-md-collection-empty]' ).prop( 'hidden', true );
						status.text( collection.data( 'md-collection-added' ) );
					}
					else if ( action === 'save' ) {
						item.find( '.md-toggle-wp-editor' ).each( function() {
							if ( window.tinymce && tinymce.get( this.id ) )
								wp.editor.remove( this.id );
						} );
						item.replaceWith( result.html );
						jscolor.install();
						status.text( collection.data( 'md-collection-saved' ) );
					}
					else if ( action === 'trash' ) {
						item.remove();
						collection.find( '[data-md-collection-empty]' ).prop( 'hidden', !! items.find( '[data-md-collection-item]' ).length );
						status.text( collection.data( 'md-collection-trashed' ) );
					}
					else if ( action === 'more' ) {
						items.append( result.html );
						jscolor.install();

						if ( ! result.has_more )
							button.closest( '.md-collection-more' ).remove();
					}

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

		// Save

		liveSave: {
			init: function() {
				var form = document.getElementById( 'md-form' );

				if ( ! form || ! form.querySelector( '.md-save-button' ) )
					return;

				this.form = form;
				this.button = form.querySelector( '.md-save-button' );
				this.status = form.querySelector( '.md-save-status' );
				this.fingerprint = this.fingerprintOf( form );
				this.phase = 'clean';
				this.text = MDJS.save.clean;

				window.addEventListener( 'load', this.remember.bind( this ) );
				form.addEventListener( 'submit', this.submit.bind( this ) );
				document.addEventListener( 'keydown', this.shortcut.bind( this ) );
				window.addEventListener( 'beforeunload', this.leave.bind( this ) );
				$( document ).on( 'md:clone-added', this.added.bind( this ) );
				form.addEventListener( 'input', this.check.bind( this ) );
				form.addEventListener( 'change', this.check.bind( this ) );
				new MutationObserver( this.check.bind( this ) ).observe( form, { childList: true, subtree: true } );
			},
			fingerprintOf: function( form ) {
				return form.elements.md_fingerprint ? form.elements.md_fingerprint.value : '';
			},
			values: function( form ) {
				var entries = [];

				if ( window.tinymce )
					tinymce.triggerSave();

				new FormData( form ).forEach( function( value, name ) {
					if ( typeof value === 'string' && name.indexOf( 'nonce' ) === -1 && name !== '_wp_http_referer' && name !== 'md_fingerprint' )
						entries.push( name + '=' + value.replace( /\r\n?/g, '\n' ) );
				} );

				return entries.join( '&' );
			},
			remember: function() {
				this.saved = this.values( this.form );
				this.state( 'clean' );
			},
			dirty: function() {
				return this.saved !== undefined && this.values( this.form ) !== this.saved;
			},
			check: function() {
				var self = this;

				if ( this.busy || this.saved === undefined || this.checking )
					return;

				this.checking = true;

				requestAnimationFrame( function() {
					self.checking = false;

					if ( self.phase !== 'error' && ! self.busy )
						self.state( self.dirty() ? 'dirty' : ( self.phase === 'saved' ? 'saved' : 'clean' ) );
				} );
			},
			submit: function( e ) {
				if ( ! e.submitter || e.submitter !== this.button )
					return;

				e.preventDefault();
				this.save();
			},
			shortcut: function( e ) {
				if ( ( e.metaKey || e.ctrlKey ) && ! e.altKey && ! e.shiftKey && ( e.key === 's' || e.key === 'S' ) ) {
					e.preventDefault();
					this.save();
				}
			},
			leave: function( e ) {
				if ( this.busy || ! this.dirty() )
					return;

				e.preventDefault();
				e.returnValue = '';
			},
			added: function( e, clone ) {
				var name = $( clone ).find( '.md-widget-edit input[type="text"]' ).first();

				if ( ! name.length )
					return;

				if ( ! name.val() )
					name.val( ( name.attr( 'placeholder' ) || '' ).replace( /\.+$/, '' ) );

				this.save( true );
			},
			state: function( phase, message ) {
				var strings = MDJS.save,
					self = this,
					text = phase === 'error' ? strings.error.replace( '%s', message || strings.failed ) : strings[phase];

				if ( phase === this.phase && text === this.text )
					return;

				clearTimeout( this.timer );
				this.phase = phase;
				this.text = text;
				this.status.className = 'md-save-status is-' + phase;
				this.status.setAttribute( 'aria-live', phase === 'error' ? 'assertive' : 'polite' );
				this.status.title = text;
				this.status.querySelector( '.md-save-text' ).textContent = text;
				this.button.value = phase === 'saving' ? strings.saving_button : strings.button;
				this.button.disabled = phase === 'saving' || phase === 'clean' || phase === 'saved';

				if ( phase === 'saved' )
					this.timer = setTimeout( function() {
						self.state( 'clean' );
					}, 3000 );
			},
			lock: function( locked ) {
				if ( locked )
					this.focus = document.activeElement;

				this.form.inert = locked;
				this.form.classList.toggle( 'md-is-saving', locked );

				if ( ! locked && this.focus && document.body.contains( this.focus ) )
					this.focus.focus( { preventScroll: true } );
			},
			save: function( force ) {
				var self = this;

				if ( this.busy || ( ! force && this.phase !== 'error' && ! this.dirty() ) )
					return;

				this.busy = true;
				this.state( 'saving' );
				this.lock( true );

				fetch( this.form.action, {
					method: 'POST',
					body: new FormData( this.form ),
					credentials: 'same-origin'
				} ).then( function( response ) {
					return response.text();
				} ).then( function( html ) {
					var page = new DOMParser().parseFromString( html, 'text/html' ),
						fresh = page.getElementById( 'md-form' ),
						error = page.querySelector( '.settings-error.notice-error, .wp-die-message, #error-page p' );

					if ( ! fresh || error )
						throw new Error( error ? error.textContent : '' );

					if ( self.fingerprintOf( fresh ) !== self.fingerprint || self.values( fresh ) !== self.values( self.form ) ) {
						self.saved = self.values( self.form );
						window.location.reload();
						return;
					}

					self.saved = self.values( self.form );
					self.busy = false;
					self.lock( false );
					self.state( 'saved' );
				} ).catch( function( error ) {
					self.busy = false;
					self.lock( false );
					self.state( 'error', error.message.trim() );
				} );
			}
		},

		// Post screens

		stickyPostTypes: function() {
			$( 'span.title:contains(\'Status\')' ).parent().after(
				$( '<label class="alignleft">' +
					'<input type="checkbox" name="sticky" value="sticky" /> ' +
					'<span class="checkbox-title"></span>' +
				'</label>' ).find( '.checkbox-title' ).text( MDJS.label_text ).end()
			);
		}
	}
	MD.init();
})( window, document, jQuery );
