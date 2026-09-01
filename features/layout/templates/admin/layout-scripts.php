<script>
( function() {

	function toggleDisplay( id, show ) {
		var element = document.getElementById( id );

		if ( element )
			element.style.display = show ? 'block' : 'none';
	}

	function toggleClass( id, className, state ) {
		var element = document.getElementById( id );

		if ( element )
			element.classList.toggle( className, state );
	}

	function bindToggle( id, handler ) {
		var element = document.getElementById( id );

		if ( element )
			element.onchange = function() { handler( this.checked, this ); };
	}

	bindToggle( '<?php echo $prefix; ?>_content_remove', function( checked ) {
		toggleClass( 'md_layout', 'remove-content-box', checked );
		toggleDisplay( 'content_options', ! checked );
		toggleDisplay( 'layout_fields_tabs', ! checked );
	} );

	<?php if ( in_array( $screen['post_type'], array( 'post', 'page' ) ) && ! $screen['is_term'] ) : ?>
	bindToggle( '<?php echo $prefix; ?>_content_headline', function( checked ) {
		toggleDisplay( 'headline_options', ! checked );
	} );
	<?php endif; ?>

	<?php foreach ( $layouts as $layout => $fields ) :
		if ( empty( $fields['areas'] ) )
			continue;

		if ( $screen['is_post'] || $screen['is_term'] ) : ?>

		bindToggle( '<?php echo "{$prefix}_{$layout}_" . ( $fields['has'] ? 'remove' : 'add' ); ?>', function( checked ) {
			toggleDisplay( '<?php echo "{$layout}_options"; ?>', <?php echo $fields['has'] ? '! checked' : 'checked'; ?> );
		} );

		<?php else : ?>

		bindToggle( '<?php echo "{$prefix}_{$layout}_global"; ?>', function( checked ) {
			toggleClass( '<?php echo "{$layout}_fields"; ?>', 'is-global', checked );
		} );

	<?php endif; endforeach; ?>

} )();
</script>
