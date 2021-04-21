<?php
	$id = get_queried_object_id();
/*
	if ( is_category() )
		$source = get_cat_name( $id );
	elseif ( is_tax() ) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$source = $term->name;
	}
*/
	if ( is_post_type_archive() )
		$source = post_type_archive_title( '', false );
	elseif ( is_category() || is_tax() )
		$source = ucwords( get_post_type() );
	elseif ( is_home() )
		$source = __( 'Blog', 'md' );
	else
		$source = __( 'this website', 'md' );
	$label = sprintf( __( 'Search %s...', 'md' ), $source );
?>
<form role="search" method="get" id="searchform" class="search-form form-attached clear" action="<?php echo home_url( '/' ); ?>">
	<input type="search" class="search-input form-input" placeholder="<?php echo esc_attr( $label ); ?>" value="<?php echo get_search_query(); ?>" name="s" id="s" />
	<?php if ( is_post_type_archive() || is_home() || is_tax() || is_category() ) : ?>
		<input type="hidden" name="post_type" value="<?php echo get_post_type(); ?>" />
	<?php endif; ?>
	<button type="submit" class="search-submit form-submit md-icon-search" id="searchsubmit" /></button>
</form>