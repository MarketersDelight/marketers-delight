<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

<?php
	if ( $loop['featured_image'] == 'above_headline' )
		md_featured_image( 'post', $loop );

	if ( ! md_has_headline_cover() )
		md_headline( array( 'loop' => $loop ) );

	if ( $loop['featured_image'] !== 'above_headline' )
		md_featured_image( 'post', $loop );

	md_content( $loop );

	md_hook_content_item();
?>

</article>
