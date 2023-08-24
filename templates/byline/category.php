<?php if ( in_array( 'category', $byline ) ) :
	$taxonomy = 'category';
	$taxonomies = get_object_taxonomies( $post_type );

	if ( ! empty( $taxonomies[0] ) )
		$taxonomy = esc_attr( $taxonomies[0] );

	$terms = get_the_terms( $post_id, $taxonomy );
	$term = ! empty( $terms[0] ) ? $terms[0] : '';

	if ( empty( $term ) )
		return false;
?>

	<span class="byline-category byline-item">

		<a href="<?php echo get_term_link( $term->term_id ); ?>">
			<?php echo md_icon( 'tags' ); ?>
			<?php echo esc_html( $term->name ); ?>
		</a>

	</span>

<?php endif; ?>
