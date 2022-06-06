<script>

share: {
	init: function() {
		MD.onScroll();
		MD.share.window();
		MD.share.like();
	},
	window: function() {
		shares = document.querySelectorAll( '[data-share]' );
		for ( var i = 0; i < shares.length; i++ ) {
			shares[i].onclick = function() {
				window.open( this.getAttribute( 'href' ), 'newWindow','left=100, top=150, width=600, height=300, toolbar=0, resizable=1' );
				return false;
			}
		}
	},
	like: function() {
		var name = 'md_likes',
			likes = document.getElementsByClassName( 'share-like' ),
			totals = document.getElementsByClassName( 'share-total' );
		for ( var i = 0; i < likes.length; i++ ) {
			likes[i].onclick = function( e ) {
				e.preventDefault();
				if ( ! MD.hasClass( this, 'liked' ) ) {
					var post_id = this.getAttribute( 'data-share-id' ),
						post_type = this.getAttribute( 'data-share-type' ),
						archive = this.getAttribute( 'data-share-archive' ),
						counts = document.getElementsByClassName( 'share-count' ),
						request = new XMLHttpRequest();
					for ( var i = 0; i < counts.length; i++ )
						if ( likes[i].getAttribute( 'data-share-id' ) === post_id ) {
							counts[i].innerHTML++;
							MD.addClass( likes[i], 'liked' );
						}
					request.open( 'POST', MDJS.ajaxurl, true );
					request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
					request.onreadystatechange = function() {
						if ( request.readyState === 4 && request.status === 200 ) {
							var liked = MD.cookie.get( name ) ? JSON.parse( MD.cookie.get( name ) ) : [];
							liked.push( post_id );
							if ( totals )
								for ( var i = 0; i < totals.length; i++ )
									if ( totals[i].getAttribute( 'data-share-total' ) === post_type )
										totals[i].innerHTML++;
							MD.cookie.create( name, JSON.stringify( liked ), 365 );
						}
					};
					request.send( 'action=md_like&post_id=' + post_id + '&archive=' + archive + '&nonce=' + MDJS.nonce );
				}
			}
		}
	},
},