<?php if ( in_array( 'category', $byline ) ) :
	$categories = get_the_category();
	$category = ! empty( $categories[0] ) ? $categories[0] : '';
	if ( empty( $category ) )
		return false;
?>
	<span class="byline-category byline-item"><?php echo md_icon( 'tags' ); ?> <a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></a></span>
<?php endif; ?>