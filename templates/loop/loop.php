<?php

echo "<$html class=\"" . implode( ' ', get_post_class( $classes ) ) . '">';

md_hook_content_top();

md_byline( 'before_post', array( 'classes' => 'post-meta' ) );

md_featured_image( 'post', array( 'show_image' => array( 'above_headline' ) ) );

md_title();

md_featured_image( 'post', array( 'show_image' => array( 'below_headline' ) ) );

md_hook_before_the_content();

if ( $loop['content'] !== 'hide' && ( get_the_content() || get_the_excerpt() || is_404() ) ) {

	echo "<section id=\"the_content\" class=\"the-content\">";

	md_hook_the_content_top();

	md_featured_image( 'post', array( 'show_image' => array( 'left', 'right', 'center' ) ) );

	if ( $loop['content'] == 'full' || ( ( is_singular() || is_404() ) && ( in_the_loop() || isset( $loop['in_loop'] ) ) ) ) {
		if ( is_404() && ! md_has_custom_404() )
			include_once md_template( 'loop/404', true );
		else
			if ( md_post_meta( array( 'layout', 'content', 'wpautop' ) ) )
				echo get_the_content();
			else
				the_content( esc_html( $loop['read_more'] ) );

		wp_link_pages();
	}
	elseif ( empty( $loop['content'] ) && get_the_excerpt() ) echo
		wpautop( wp_trim_words( get_the_excerpt(), $loop['excerpt_length'], $loop['excerpt_more'] ) ) .
		( empty( $loop['excerpt_settings']['remove_text'] ) ? '<p class="read-more"><a href="' . get_permalink() . '" class="more-link">' . esc_html( $loop['read_more'] ) . '</a></p>' : '' );

	md_hook_the_content_bottom();

	echo "</section>";

}

md_hook_after_the_content();

if ( ! isset( $loop['post_footer']['remove'] ) )
	md_byline( 'after_post', array(
		'loop' => $loop,
		'classes' => 'post-footer',
		'html' => 'footer'
	) );

md_hook_content_item();

md_hook_content_bottom();

echo "</$html>";